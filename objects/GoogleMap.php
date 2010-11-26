<?php
/**
 * This file is part of P4A - PHP For Applications.
 *
 * P4A is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of
 * the License, or (at your option) any later version.
 *
 * P4A is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with P4A.  If not, see <http://www.gnu.org/licenses/lgpl.html>.
 *
 * To contact the authors write to:                                     <br />
 * CreaLabs SNC                                                         <br />
 * Via Medail, 32                                                       <br />
 * 10144 Torino (Italy)                                                 <br />
 * Website: {@link http://www.crealabs.it}                              <br />
 * E-mail: {@link mailto:info@crealabs.it info@crealabs.it}
 *
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @author Fabrizio Balliano <fabrizio.balliano@crealabs.it>
 * @copyright CreaLabs SNC
 * @link http://www.crealabs.it
 * @link http://p4a.sourceforge.net
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package p4a
 */

/**
 * *********Traducción No Oficial *********
 * Este archivo es parte de P4A - PHP Para Aplicaciones
 *
 * P4A es Software Libre: usted puede redistribuirlo y/o modificarlo
 * bajo los términos de la GNU Lesser Licencia Pública General Menor (Lesser General Public License) tal y como está
 * publicada por la Fundación de Software Libre, en la versión 3 de
 * la Lincecia, o (a su elección) alguna versión posterior.
 *
 * P4A es distribuido con la esperanza de que pueda serle útil,
 * pero SIN NINGUNA GARANTÍA; sin siquiera la garantía implicita de
 * COMERCIABILIDAD o IDIONIDAD PARA UN PROPÓSITO PARTICULAR. Vea la
 * GNU Licencia Pública General Menor (Lesser General Public License) para más detalles.
 *
 * Usted debería tener una copia de la GNU Licencia Pública General Menor (GNU Lesser General Public License)
 * junto con P4A.  Sí no, vea <http://www.gnu.org/licenses/lgpl.html>.
 *
 * Para contactar a los autores escriba a:                              <br />
 * CreaLabs SNC                                                         <br />
 * Via Medail, 32                                                       <br />
 * 10144 Torino (Italy)                                                 <br />
 * Website: {@link http://www.crealabs.it}                              <br />
 * E-mail: {@link mailto:info@crealabs.it info@crealabs.it}
 *
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @author Fabrizio Balliano <fabrizio.balliano@crealabs.it>
 * @copyright CreaLabs SNC
 * @link http://www.crealabs.it
 * @link http://p4a.sourceforge.net
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package p4a
 */

/**
 * Sistema de Información para la Organización Comunitaria
 * Desarrollado por
 * Software:
 * @author Cesar Carbonara <cesar.carbonara@gmail.com>
 * Documentos:
 * @author Leonardo Lobo <leonardolelectiva@gmail.com>
 * Asesoría y apoyo:
 * @author Leonardo Caballero <leonardocaballero@gmail.com>
 * Durante le proceso académico para la obtención del título
 * Ingeniero en Informática
 * Programa Nacional de Formación en Informática
 * Instituto Universitario Tecnológico de Ejido
 *
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @copyleft 2010
 *
 * @package SIOC
 */
/**
 * Widget extension for show a map using Google Map API
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2
 * as published by the Free Software Foundation.
 *
 * The latest version of GoogleMap can be obtained from:
 * {@link http://}
 *
 * @version 0.1.0
 *  * This Widget is based in the p4a_map version 0.1.2
 * @author Andrea Giardina <andrea.giardina@crealabs.it>
 * @author Galanti Alberto <alberto@abidev.net>
 * @author Spada Mario <mario@mariospada.net>
 */


class GoogleMap extends P4A_Widget
{

	// Move and zoom controls type
	const LARGE_CONTROLS = 'Large';
	const SMALL_CONTROLS = 'Small';

	// Map modes
	const NORMAL = 'G_NORMAL_MAP';
	const SATELLITE = 'G_SATELLITE_MAP';
	const HYBRID = 'G_HYBRID_MAP';

	// This is a address to thecer the map when a non valid address is passed
	const THECENTEROFTHEWORLD = 'Via Ghibellina, 17, Cortona, Italia';

	private $_SW_lat;
	private $_SW_lng;
	private $_NE_lat;
	private $_NE_lng;

/**
 * Coordinates of the Map Center
 * @var float
 * @access private
 */
	private $_center_lat; // latitude
	private $_center_lng; // longitude
	private $_msg; // Message status when getting coordinates

/**
 * Zoom of map (1-17)
 * @var int
 * @access private
 */
	private $_zoom = 14;

/**
 * View move and zoom Controls
 * @var boolean
 * @access private
 * @default true
 */
	private $_showmovezoom = true;

/**
 * Move and Zoom Controls
 * @var string
 * @access private
 * @default small
 */
	private $_movezoomcontrols = self::SMALL_CONTROLS;

/**
 * Continouszom zoom ?
 * @var boolean
 * @access private
 */
	private $_continuousZoom = false;

/**
 * Wheel zoom ?
 * @var boolean
 * @access private
 */
	private $_wheelZoom = false;

/**
 * Zoom when double click
 * @var boolean
 * @acces private
 */
	private $_doubleClickZoom = false;

/**
 * View map type selectors
 * @var boolean
 * @access private
 * @default true
 */
	private $_showmaptype = true;

/**
 * Map type
 * @var string
 * @access private
 * @default small
 */
	private $_maptype = self::NORMAL;

/**
 * Show Map Overview widget
 * @var boolean
 * @access private
 * @default false
 */
	private $_overview = false;

/**
 * Key for use Google Map: take one for free at http://www.google.com/apis/maps/signup.html
 * default value is a valid key for localhost urls
 * @var string
 * @access private
 */
/**
 * clave original, comentada para el taller con la comunidad
 *
 *	private $_k = "ABQIAAAA3EDrMgCj1Hj9BKdAa7p5fRQEE1bfVDLgrFdvSxkSOtnExQ3poxTrgfExWexlocxGGA56CEnuiYx9Ig";
 */
	private $_k = "ABQIAAAABnKUNAHcokdQGx3OWP1LsRRT41YKyiJ82KgcK-Dai8T6I93cWxTFt4WU_-EHnS7LRFoAt30uOO1WTA";

/**
 * Markers
 * @var array
 * @access private
 */
	private $_markers = array();

/**
 * Data source for the field.
 * @var data_source
 * @access private
 */
	private $data = NULL;

/**
 * The data source member that contains the id .
 * @var string
 * @access private
 */
	private $data_id_field = NULL ;

/**
 * The data source member that contains the values for this field.
 * @var string
 * @access private
 */
	private $data_address_field = NULL ;

/**
 * The data source member that contains the descriptions for this field.
 * @var string
 * @access private
 */
	private $data_description_field	= NULL ;

/** The data source member that contains the latitude and longitude.
 * @var array
 * @access private
 */
	private $data_coordinates_fields	= array() ;

/** The data source member that contains the info.
 * @var string
 * @access private
 */
	private $data_info_field = NULL;

/**
 * The width of the infowindows for marks
 *
 * @var unknown_type
 */
	private $_infowidth = 200;

/** The data source member that contains the icon.
 * @var string
 * @access private
 */
	private $data_icon_field = NULL;

/**
 * Constructor
 */
	public function __construct($name, $value = NULL )
	{
		parent::__construct($name);
		// default size
  		$this->setWidth(500,"px");
		$this->setHeight(300,"px");
		$this->setCenter($value);
	}

/**
 * If we use fields like combo box we have to set a data source.
 * By default we'll take the data source primary key as value field
 * and the first fiels (not pk) as description.
 * @param data_source		The data source.
 * @access public
 */
	public function setSource(&$data_source)
	{
		unset( $this->data ) ;
		$this->data =& $data_source;
	}

/**
 * Sets what data source member is the keeper the id of the marks.
 * @param string		The name of the data source member.
 * @access public
 */
	public function setSourceIdField( $name )
	{
		$this->data_id_field = $name ;
	}

/**
 * Sets what data source member is the keeper of the mark address .
 * @param string		The name of the data source member.
 * @access public
 */
	public function setSourceAddressField( $name )
	{
		$this->data_address_field = $name ;
	}

/**
 * Sets what data source member is the keeper of the mark description.
 * @param string		The name of the data source member.
 * @access public
 */
	public function setSourceDescriptionField( $name )
	{
		$this->data_description_field = $name ;
	}

/**
 * Sets what data source member is the keeper of the field's mark coordinates.
 * It must have the following format: "latitude value, longitude value"
 * @param string		The name of the data source member.
 * @access public
 */
	public function setSourceCoordinatesFields( $name_lat, $name_lng )
	{
		// No controls if $name exists...
		// too many controls may be too performance expensive
		$this->data_coordinates_field = array($name_lat, $name_lng) ;
	}

/**
 * Sets what data source member is the keeper the info of the mark.
 * @param string		The name of the data source member.
 * @access public
 */
	public function setSourceInfoField( $name )
	{
		// No controls if $name exists...
		// too many controls may be too performance expensive.
		$this->data_info_field = $name ;
	}

/**
 * Sets what data source member is the keeper the icon of the mark.
 * @param string		The name of the data source member.
 * @access public
 */
	public function setSourceIconField( $name )
	{
		// No controls if $name exists...
		// too many controls may be too performance expensive.
		$this->data_icon_field = $name ;
	}

/**
 * Returns the name of the data source member that keeps the field's value.
 * @return string
 * @access public
 */
	public function getSourceAddressField()
	{
		return $this->data_address_field ;
	}

/**
 * Returns the name of the data source member that keeps the field's description.
 * @return string
 * @access public
 */
	public function getSourceDescriptionField()
	{
		return $this->data_description_field ;
	}

/**
 * Returns the name of the data source member that keeps the field's latitude.
 * @return string
 * @access public
 */
	public function getSourceLatitudeField()
	{
		return $this->data_coordinates_field[0] ;
	}

/**
 * Returns the name of the data source member that keeps the field's longitude.
 * @return string
 * @access public
 */
	public function getSourceLongitudeField()
	{
		return $this->data_coordinates_field[1] ;
	}

/**
 * Returns the name of the data source member that keeps the field's icon.
 * @return string
 * @access public
 */
	public function getSourceIconField()
	{
		return $this->data_icon_field ;
	}

/**
 * Returns the name of the data source member that keeps the field's id.
 * @return string
 * @access public
 */
	public function getSourceIdField()
	{
		return $this->data_id_field;
	}

/**
 * Returns the name of the data source member that keeps the field's info.
 * @return string
 * @access public
 */
	public function getSourceInfoField()
	{
		return $this->data_info_field ;
	}

/**
 * Set the center of map.
 * @param string It must be a valid google map address like: Piazza del Colosseo, Roma, Italia
 * @param array  Latitude and Longitude
 * @access public
*/
	public function setCenter($address = null)
	{
		if (is_array($address)) {
			list($this->_center_lat,$this->_center_lng) = $address;
			$this->_msg = 'LTD '.$this->_center_lat.' LNG '.$this->_center_lng;
		} else {
			if (empty($address)) $address = self::THECENTEROFTHEWORLD;
			list($this->_center_lat,$this->_center_lng,$this->_msg) = $this->getLocationCoordinates($address);
		}
	}

/**
 * Return the center of map.
 * @return string
 * @access public
 */
	public function getCenter()
	{
		return array($this->_center_lat,$this->_center_lng,$this->_msg);
	}

/**
 * Set the zoom factor map.
 * @param int		zoom factor, between 1 (lowest detail) and 17 (bigger detail)
 * @access public
 */
	public function setZoom($zoom)
	{
		if ($zoom > 0 and $zoom < 17)
   		$this->_zoom = $zoom;
	}

/**
 * Return the zoom factor map.
 * @return int
 * @access public
 */
	public function getZoom()
	{
		return $this->_zoom ;
	}

/**
 * Enable map zoom and move controls
 */
	public function showMoveZoom($control_type = null)
	{
		$this->_showmovezoom = true;
		if (!empty($control_type))
			if (($control_type == self::SMALL_CONTROLS) || ($control_type == self::LARGE_CONTROLS))
				$this->_movezoomcontrols = $control_type;
	}

/**
 * Disable map zoom and move controls
 */
	public function hideMoveZoom()
	{
		$this->_showmovezoom = false;
	}

/**
 * Enable wheel zoom ?
 */
	public function enableWheelZoom( $enable = true)
	{
		$this->_wheelZoom = ($enable == true);
	}

/**
 * Disable wheel zoom
 */
	public function disableWheelZoom()
	{
		$this->_wheelZoom = false;
	}

/**
 * Enable Continous zoom ?
 */
	public function enableContinuousZoom( $enable = true)
	{
		$this->_continuousZoom = ($enable == true);
	}

/**
 * Disable Continous zoom
 */
	public function disableContinuousZoom()
	{
		$this->_continuousZoom = false;
	}

/**
 * Enable Zoom when double click
 */
	public function enableDoubleClickZoom( $enable = true)
	{
		$this->_doubleClickZoom = ($enable == true);
	}

/**
 * Disable Zoom when double click
 */
	public function disableDoubleClickZoom()
	{
		$this->_doubleClickZoom = false;
	}

/**
 * Enable map type selector
 */
	public function showMapType ( $show = true )
	{
		$this->_showmaptype = ($show == true);
	}

/**
 * Disable map type selector
 */
	public function hideMapType()
	{
		$this->_showmaptype = false;
	}

/**
 * Set the default map type, the user can change it if
 * Map type controls are showed
 */
	public function setMapType($type)
	{
		if (($type == self::NORMAL) || ($type == self::SATELLITE) || ($type == self::HYBRID))
			$this->_maptype = $type;
	}

/**
 * Enable Overview Control
 */
	public function showOverView($show=true)
	{
		$this->_overview = ($show == true);
	}

/**
 * Hide Overview Control
 */
	public function hideOverView()
	{
		$this->_overview = false;
	}

/**
 * Set the Google Api Key: take one for free at http://www.google.com/apis/maps/signup.html
 * @param str		key
 * @access public
 */
	public function setKey($key)
	{
 		$this->_k = $key;
	}

/**
 * Add a marker to map.
 * @param str	Address It must be a valid google map address like: Piazza del Colosseo, Roma, Italia
 * @param str  Info that must be show clicking on marker
 * @param str  Coords (optional) if present must be like this: "41.9, 12.4"
 * @access public
 */
	public function addMarkerByAddress($id,$address,$description =null, $info=null, $icon = null)
	{
		list($ltd,$lng,$msg) = $this->getLocationCoordinates($address);
		$info .="<br>{$msg}";
		if (strlen($description)==0) $description = $address;
		$this->addMarker($id,$ltd,$lng,$description,$info,$icon);
	}
	public function addMarker($id,$ltd,$lng,$description, $info = null, $icon = null)
	{
        $this->_markers[$id] = array("latitude"=>$ltd,"longitude"=>$lng, "description"=>$description, "info"=>$info,"icon"=>$icon);
	}

/**
 * Set the width of the mark info windows
 */
	public function setInfoWidth($width)
	{
		$this->_infowidth = $width;
	}
/**
 * Clear all marks
 */
	public function clearMarkers()
	{
		$this->_markers = array();
	}

/**
 * Removes a mark by id
 */
	public function dropMark($id)
	{
		if(isset($this->_markers[$id])){
			unset($this->_markers[$id]);
		}
	}

/**
 * This method returns the Coordinates of a Mark
 * it will work only with internal marks not
 * from ones that are in a data source
 *
 * @param unknown_type $id
 * @return unknown
 */
	public function getMarkCoordinates($id)
	{
		if(isset($this->_markers[$id])){
			$lat = $this->_markers[$id]['latitude'];
			$lng = $this->_markers[$id]['longitude'];
		} else {
			$lat = null;
			$lng = null;
		}
		return array($lat,$lng);
	}

/**
 * Return the coordinates of the location.
 * @param $location		valid Google Maps address of a location.
 * @return array  element[0] = status, element[1] = accuracy, element[2] = lat, element[3] = long
 **/
	public function getLocationCoordinates($location)
	{
      $base_url = "http://maps.google.com/maps/geo?output=csv&key=" .$this->_k;
      $request_url = $base_url . "&q=" . urlencode($location);

      $csv = file_get_contents($request_url);

      if ($csv) {
			list($status,$precision,$lat,$lon) = explode(",", $csv);
			if (strcmp($status, "200") == 0) {
				$msg = "Address $location located!";
			} elseif (strcmp($status, "620") == 0) {
        		$msg = 'Too fast';
        	} else {
        		$msg = "Address [$location] failed to geocoded";
			}
      } else {
			$lat = 0;
			$lon = 0;
			$msg = "No answer from Google Maps";
      }
      return array($lat,$lon,$msg,$precision);
	}

/**
 * This function returns the boundaries of the map
 * it will be useful to, for example, get the marks
 * in the showed area...
 * This value is aprox.
 */
	public function getBoundaries()
	{
		$Klat = 0.00000781;
		// width of 1 px in degrees for the zoom level
		$dglat = $Klat * pow(2,17-$this->_zoom);
	   // visible degress for this size
	   $dglatv = $dglat * $this->getHeight();
	   // calculate latitudes
	   $S = $this->_center_lat - ($dglatv/2);
	   $N = $this->_center_lat + ($dglatv/2);
	   $Klng = 0.00001072883605957;
		$dglng = $Klng * pow(2,17-$this->_zoom);
	   // visible degress for this size
	   $dglngv = $dglng * $this->getWidth();
	   // calculate latitudes
	   $W = $this->_center_lng - ($dglngv/2);
	   $E = $this->_center_lng + ($dglngv/2);

		return array($S,$W,$N,$E);
	}

/**
 * GM Boundaries are more acurates than Boundaries method
 * but only can be access after the first call
 *
 * @return unknown
 */
	public function getGMBoundaries()
	{
		return(array($this->_SW_lat,$this->_SW_lng,$this->_NE_lat,$this->_NE_lng));
	}
/**
 * Returns the distance between to points
 *
 * @param unknown_type $lat1
 * @param unknown_type $lng1
 * @param unknown_type $lat2
 * @param unknown_type $lng2
 * @return unknown
 */
	public static function distance($lat1, $lng1, $lat2, $lng2)
	{
		$R = 6371; // km
		$dLat = deg2rad($lat2-$lat1);
		$dLon = deg2rad($lng2-$lng1);
		$a = sin($dLat/2) * sin($dLat/2) +
	     cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
   	  sin($dLon/2) * sin($dLon/2);
		$c = 2 * atan2(sqrt($a), sqrt(1-$a));
		$d = $R * $c;
		return $d;
	}

/**
 * Wrapper used to add the handling of onMouseOver action
 * @return unknown
 * @see actionHandler()
 */
	public function OnMarkClick($params = null)
	{
		return $this->actionHandler('OnMarkClick', $params);
	}

/**
 * This method is used to pass the boundaries of the GoogleMap (the GM Boundaries)
 * the new center coordinates and the new zoom level and synchronizes the
 * google map with the widget
 * Finally raises the onChange event
 *
 * @param unknown_type $obj
 * @param unknown_type $params
 * @return unknown
 */
	public function syncMap($params = null)
	{
		list(
			$this->_SW_lat,$this->_SW_lng,
			$this->_NE_lat,$this->_NE_lng,
			$this->_center_lat, $this->_center_lng,
			$this->_zoom ) = explode('|',$params[0]);
		return $this->actionHandler('OnChange');
	}

/**
 * This method is used to synchronize the mark change
 * updating the new coordinates and raises the OnMarkChange event
 * Finally raises the onMarkChange event
 * Note. The mark is update
 *
 * @param unknown_type $obj
 * @param unknown_type $params
 * @return unknown
 */
	public function syncMark($params = null)
	{
		list($id, $lat, $lng) = explode('|',$params[0]);
		if (isset($this->_markers[$id])) {
			$this->_markers[$id]['latitude'] = $lat;
			$this->_markers[$id]['longitude'] = $lng;
		}
		return $this->actionHandler('OnMarkChange',array($id,  $lat, $lng));
	}

/**
 * Returns the HTML rendered map.
 * @access public
 */
	public function getAsString()
	{
		// load google api javascript with key parameter
		$p4a =& p4a::singleton();
		$js = "http://maps.google.com/maps?file=api&amp;v=2&amp;key=" . $this->_k;
		$p4a->addJavascript($js);

		$id = $this->getId();
		// if not visible hide the widget
		if (!$this->isVisible()) {
			return "<div id='$id' class='hidden'></div>";
		}

		// The JavaScript
		$script = <<<JAVASCRIPT
		<script type='text/javascript'>
			window.onunload = function() { GUnload(); }
			map = new GMap2(document.getElementById('{$id}'));
			map.clearOverlays();
			map.setCenter(new GLatLng({$this->_center_lat}, {$this->_center_lng}), {$this->_zoom},{$this->_maptype});

JAVASCRIPT;

		if ( $this->isEnabled() == true) {
			// Zoom control
			$script .= ($this->_showmovezoom == true) ? "\n map.addControl(new G{$this->_movezoomcontrols}MapControl()); " : null;
			$script .= ($this->_continuousZoom == true) ? "\n map.enableContinuousZoom();" : "\n map.disableContinuousZoom();";
			$script .= ($this->_wheelZoom == true) ? "\n map.enableScrollWheelZoom();" : "\n map.disableScrollWheelZoom();";
			$script .= ($this->_doubleClickZoom == true) ? "\n map.enableDoubleClickZoom();" : "\n map.disableDoubleClickZoom();";
  		    // Overview widget
			$script .= ($this->_overview == true ) ? "\n map.addControl(new GOverviewMapControl());" : null;
			$script .= $this->composeStringActions();
		} else {
			$script .= "\n map.disableDragging();";
		}
		// Map type selector
		$script .= ($this->_showmaptype == true) ? "\n map.addControl(new GMapTypeControl());" : null;

		// reading overlays data from source, if present, and compose javascript
		if ($this->data) {
			$external_data	= $this->data->getAll() ;
			$address_field	= $this->getSourceAddressField() ;
  			$description_field = $this->getSourceDescriptionField() ;
  			$id_field = $this->getSourceIdField();
  			$ltd_field = $this->getSourceLatitudeField();
  			$lng_field = $this->getSourceLongitudeField();
  			$icon_field = $this->getSourceIconField();
  			$info_field = $this->getSourceInfoField();

			foreach ($external_data as $key=>$current) {
      		// If not latidude or longitude but address we calculate the position
      		$msg = null;
      		if (( empty($ltd_field) or empty($lng_field)) and (!empty($address_field))) {
      			list($ltd,$lng,$msg) = $this->getLocationCoordinates($current[$address_field]);
      		} else {
      			$ltd = $current[$ltd_field];
      			$lng = $current[$lng_field];
      			// The Lat and Lon fields are defined but there aren't coordinates
	      		// if the address is set I will guess it !!
      			if ((empty($ltd) or empty($lng)) and (!empty($address_field))) {
						list($ltd,$lng,$msg) = $this->getLocationCoordinates($current[$address_field]);
      			}
      		}
      		$desc = isset($current[$description_field]) ? $current[$description_field]:null;
      		$info = (isset($current[$info_field]) ? $current[$info_field]:null).$msg;
      		$icon = isset($current[$icon_field]) ? $current[$icon_field]:null;
      		$script .= $this->composeMarkScript($ltd,$lng,$current[$id_field],$desc,$info,$icon);
  			}
  		}
		// reading manual overlays, if presents, and compose javascript
		foreach ($this->_markers as $mid=>$marker) {
			$info = $marker['info'];
			$ltd = $marker['latitude'];
			$lng = $marker['longitude'];
			$icon = $marker['icon'];
			$desc = $marker['description'];
			$script .= $this->composeMarkScript($ltd,$lng,$mid,$desc,$info,$icon);
		}

		$script .= "\n</script>\n";
		$properties = $this->composeStringProperties();

		$header = "<div id='$id' style='width: " . $this->getWidth() . "; height: " . $this->getHeight() . "' class='border_color1 font_normal' $properties";
		if (!$this->isEnabled()) {
			$header .= 'disabled="disabled" ';
		}
		$header .= '/>';

		$sReturn = null;
		if ($this->getLabel()) {
			$sReturn .= "<strong>" . __($this->getLabel()) . "</strong>";
		}
		$sReturn .= $header;
		$sReturn .= $script;

		return $sReturn;
	}

/**
 * Composes a string containing all the actions implemented by the widget.
 * These actions are events triggered from de map widget to p4a.js
 * @param array $params
 * @param boolean $check_enabled_state
 * @return string
 */
	public function composeStringActions($params = null, $check_enabled_state = true)
	{
		if ($check_enabled_state and !$this->isEnabled()) return '';

		$sParams = '';
		$sActions = '';
		if (is_string($params) or is_numeric($params)) {
			$sParams .=  ", '{$params}'";
		} elseif (is_array($params) and count($params)) {
			$sParams = ', ';
			foreach ($params as $param) {
				$sParams .= "'{$param}', ";
			}
			$sParams = substr($sParams, 0, -2);
		}

		$id = $this->getId();

		// $actions implemented
		foreach ($this->_map_actions as $action=>$action_data) {
			if (isset($action_data['ajax']) and $action_data['ajax'] == 1) {
				$execute = 'p4a_event_execute_ajax';
			} else {
				$execute = 'p4a_event_execute';
			}
			switch ($action) {
				case 'onclick':
					$sActions .= <<<JAVASCRIPT
					GEvent.addListener(map, 'click', function(overlay,point) {
			 			if (point) { $execute('{$id}', 'onclick' ,point.lat(), point.lng()); }
					});
JAVASCRIPT;
					break;
				case 'onchange':
					$sActions .= <<<JAVASCRIPT
					GEvent.addListener(map, 'moveend', function() {
						var params = map.getBounds().getSouthWest().lat() + '|' + map.getBounds().getSouthWest().lng() + '|' +  map.getBounds().getNorthEast().lat() + '|' + map.getBounds().getNorthEast().lng() + '|' +  map.getCenter().lat() + '|' +map.getCenter().lng() + '|' + map.getZoom();
			 			$execute('{$id}', 'syncMap', params);
					});
JAVASCRIPT;
					break;
				default:
					break;
			}
		}
		return $sActions;
	}

/**
 * Compose the JavaScript for add a Mark for a mark
 */
	private function composeMarkScript($ltd,$lng,$id,$title=null,$info=null,$icon=null)
	{
		if (empty($ltd) or empty($lng) or empty($id)) return null;

		$script = null;
		$options = null;
		// The title
		if (strlen($title) > 0) $options .= 'title: "'.addslashes($title).'"';

		// if mark can be changed then, it must be declared as draggeable
		if (($this->isEnabled() == true) and ($this->isActionTriggered('onmarkchange'))) {
			$options .= (strlen($options) > 0 ? ', ' : null)." draggable: true, dragCrossMove: true";
		}

		// The icon can be a customize image or a standard google mark
		if (strlen($icon) > 0 ) {
			// if icon name is one character it is mapped as and standard
			// google index mark
			if (($icon >='A') and ($icon <= 'Z')) {
				$imgurl = "www.google.com/mapfiles/marker{$icon}.png";
			} else {
				$imgurl = $_SERVER["HTTP_HOST"].P4A_ICONS_PATH . "/16/$icon." . P4A_ICONS_EXTENSION;
			}
			$script .= "\nvar mk{$id}Icon = new GIcon(G_DEFAULT_ICON,'http://{$imgurl}');";
			$options .= (strlen($options) > 0 ? ', ' : null)." icon: mk{$id}Icon";
		}

		if (strlen($options) > 0) {
			$script .= 	"\nvar options = {{$options}};\n";
			$optionvar = ',options';
		} else {
			$optionvar = null;
		}
		$objid = $this->getId();
		$script .= <<<JAVASCRIPT
      	var mark_{$id} = new GMarker(new GLatLng({$ltd}, {$lng}){$optionvar});
      	map.addOverlay(mark_{$id});
JAVASCRIPT;
		if (($this->isEnabled() == true) and ($this->isActionTriggered('onmarkclick'))){
			$script .= <<<JAVASCRIPT
			GEvent.addListener(mark_{$id},"click", function(){
				p4a_event_execute('{$objid}', 'onmarkclick', '{$id}');}
			);
JAVASCRIPT;
		}
		// The info window. The position is saved and the map return to
		// the original position when the info is hidden
		//  map.savePosition(); map.returnToSavedPosition();
		if ((strlen($info) > 0 ) and ($this->isEnabled() == true)) {
      	$info = addslashes($info);
      	$script .= <<<JAVASCRIPT
      	GEvent.addListener(mark_{$id}, "mouseover", function() {
				mark_{$id}.openInfoWindowHtml('{$info}', { maxWidth : {$this->_infowidth} } );
			});
      	GEvent.addListener(mark_{$id}, "mouseout", function() {
				mark_{$id}.closeInfoWindow();
      	});
JAVASCRIPT;
		}
		// If the mark can be moved, the onmarkchange is fired
		if (($this->isEnabled() == true) and ($this->isActionTriggered('onmarkchange'))) {
      	$script .= <<<JAVASCRIPT
      	GEvent.addListener(mark_{$id}, "dragstart", function() {
				mark_{$id}.closeInfoWindow();
      	});
      	GEvent.addListener(mark_{$id}, "dragend", function() {
      		var params = '{$id}' + '|' + mark_{$id}.getLatLng().lat() + '|' + mark_{$id}.getLatLng().lng() + '|';
				p4a_event_execute('{$objid}', 'syncMark', params);
      	});
JAVASCRIPT;
		}

		return $script;
	}

}
?>
