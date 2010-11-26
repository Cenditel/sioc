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
 * Máscara: cc1_14
 * Tabla:	tabla14
 * Tipo:
 * Tema:	Vivienda. Ubicación y georeferenciación
 * <Descripcion: tabla básica para registrar las viviendas con su
 * geolocalización>
 */
class tabla14 extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;

	public $mapa;
	public $marksource;

	private $markid = 0; // A counter for marker id
	public $marks = array();
	private $icons = array(null,'status/dialog-information');

    public function __construct()
    {
        parent::__construct();
        $p4a = p4a::singleton();

        $this->setTitle("Censo de Viviendas");

        $this->build("p4a_full_toolbar", "toolbar")
            ->setMask($this);
		/**
         * Propiedades de la Barra de Herramientas
         *
         * el botón <delete> la desactivamos
         * el botón <exit> la ocultamos
         * el botón <cancel> la ocultamos
         * el botón <print> la desactivamos
         */
        $this->toolbar->buttons->delete->disable();
        $this->toolbar->buttons->exit->setVisible(false);
        $this->toolbar->buttons->cancel->setVisible(false);
        $this->toolbar->buttons->print->disable();
		
        $this->build("p4a_db_source", "source")
            ->setTable("tabla14")
            ->addJoin("tabla13","tabla14.tabla14_campo2 = tabla13.tabla13_campo1", array("tabla13.tabla13_campo2"=>"tabla13_campo2"),"cc1")
            ->setWhere("tabla14_campo7 = 'true'")
            ->addOrder("tabla14_campo2","ASC")
			->setPk("tabla14_campo1")
            ->load();
			
		$this->setSource($this->source);
        $this->firstRow();


/*
        $this->camposObligatorios();
*/
        
    	$this->build('p4a_field','enable');
    	$this->build('GoogleMap','mapa');
    	$this->build('P4A_Array_source','marksource');
    	$this->build('p4a_field','triggerclick');
    	$this->build('p4a_field','triggerchange');
    	$this->build('p4a_field','triggermarkclick');
        $this->propiedadesCampos();

        $this->build("p4a_table", "table")
            ->setSource($this->source)
            ->setVisiblecols(array("tabla13_campo2","tabla14_campo3","tabla14_campo15","tabla14_campo4","tabla14_campo5","tabla14_campo11","tabla14_campo12"))
            ->setWidth(800)
            ->showNavigationBar();
        /**
         * Propiedades de las Columas de la Tabla
         *
         * la columna <tabla14_campo1> la ocultamos
         * la columna <tabla14_campo6> la ocultamos
         * la columna <tabla14_campo7> la ocultamos
         */
        $this->table->cols->tabla13_campo2->setLabel("Sector");
        $this->table->cols->tabla14_campo3->setLabel("Nombre de la Vivienda");
		$this->table->cols->tabla14_campo15->setLabel("Nº de la Vivienda")->setWidth(70);
        $this->table->cols->tabla14_campo4->setLabel("Latitud");
        $this->table->cols->tabla14_campo5->setLabel("Longitud");
        $this->table->cols->tabla14_campo11->setLabel("Nº de Familias que habitan")->setWidth(100);
        $this->table->cols->tabla14_campo12->setLabel("Requiere Reparación")->setWidth(80);

/*
        $this->RefreshMap();
*/

		$this->build('P4A_fieldset', 'fieldset_mapa')
        	->anchor($this->mapa);

        
		$this->build("p4a_fieldset", "fs_details")
			->setWidth(600)
			->anchor($this->fields->tabla14_campo2)
			->anchorLeft($this->fields->tabla14_campo3)
			->anchor($this->fields->tabla14_campo15)
			->anchor($this->fields->tabla14_campo4)
			->anchorLeft($this->fields->tabla14_campo5)
			->anchor($this->fields->tabla7_campo1)
			->anchorLeft($this->fields->tabla10_campo1)
			->anchor($this->fields->tabla8_campo1)
			->anchorLeft($this->fields->tabla9_campo1)
			->anchor($this->fields->tabla11_campo1)
			->anchorLeft($this->fields->tabla15_campo1)
			->anchor($this->fields->tabla14_campo8)
			->anchorLeft($this->fields->tabla14_campo9)
			->anchorLeft($this->fields->tabla14_campo10)
			->anchor($this->fields->tabla14_campo11)
			->anchorLeft($this->fields->tabla14_campo12)
			->anchorLeft($this->fields->tabla14_campo13);

		$this->frame
            ->anchor($this->table)
            ->anchor($this->fs_details)
            ;
        $this->frame->anchorLeft($this->fieldset_mapa);

        $this->display("menu", $p4a->menu)
        	->display("top", $this->toolbar)
            ->setFocus($this->fields->tabla14_campo2);
    }
    private function camposObligatorios()
    {
        /**
         * Campos Obligatorios del Formulario
         */
        $this->setRequiredField("tabla14_campo2");

        return;
    }
    private function propiedadesCampos()
    {
    	/**
         * Propiedades de la Barra de Herramientas
         *
         * el botón <delete> la desactivamos
         * el botón <exit> la ocultamos
         * el botón <cancel> la ocultamos
         * el botón <print> la desactivamos
         */
        $this->toolbar->buttons->delete->disable();
        $this->toolbar->buttons->exit->setVisible(false);
        $this->toolbar->buttons->cancel->setVisible(false);
        $this->toolbar->buttons->print->disable();

        /**
         * Propiedades de los Campos del Formulario
         *
         * el campo <tabla14_campo1> lo desactivamos y ocultamos
         * el campo <tabla14_campo6> lo desactivamos y ocultamos
         * el campo <tabla14_campo7> lo desactivamos y ocultamos
         * al campo <tabla14_campo2> lo validamos contra ...
         */
        $this->fields->tabla14_campo1
        	->disable()->setVisible(false);
        $this->fields->tabla14_campo6
        	->setVisible(false);
        $this->fields->tabla14_campo7
        	->setVisible(false);

		$this->build("p4a_db_source","source_sector")
        	->setTable("tabla13")
        	->setWhere("activo = 'true'")
        	->addOrder("tabla13_campo2")
        	->load();
        $this->fields->tabla14_campo2
        	->setType('select')
        	->setSource($this->source_sector)
        	->setLabel("Sectores")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla14_campo2->label->setWidth(120);

        $this->fields->tabla14_campo3
        	->setLabel("Nombre de la Vivienda")
        	->setWidth(150)
        	->setProperty('maxlength',"25")
/*
        	->addValidator(new P4A_Validate_StringLength(0,25),true)
*/
        	->addValidator(new P4A_Validate_Alpha(true),true);
        $this->fields->tabla14_campo3->label->setWidth(120);

		$this->fields->tabla14_campo15
        	->setLabel("Nº de la Vivienda")
        	->setWidth(50)
        	->setProperty('maxlength',"6")
/*
        	->addValidator(new P4A_Validate_StringLength(0,25),true)
        	->addValidator(new P4A_Validate_Alpha(true),true)
*/
			;
		$this->fields->tabla14_campo15->label->setWidth(120);

        $this->fields->tabla14_campo4
        	->setLabel("Latitud")
        	->disable();
        $this->fields->tabla14_campo4->label->setWidth(120);

        $this->fields->tabla14_campo5
        	->setLabel("Longitud")
        	->disable();
        $this->fields->tabla14_campo5->label->setWidth(120);

        $this->build("p4a_db_source","source_tipo_vivienda")
        	->setTable("tabla7")
        	->setWhere("activo = 'true' AND tabla7_campo3 = 'true'")
        	->addOrder("tabla7_campo2")
        	->load();
        $this->fields->tabla7_campo1
        	->setType('select')
        	->setSource($this->source_tipo_vivienda)
        	->setLabel("Tipo de Vivienda")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla7_campo1->label->setWidth(120);

        $this->build("p4a_db_source","source_tenencia_vivienda")
        	->setTable("tabla10")
        	->setWhere("activo = 'true' AND tabla10_campo3 = 'true'")
        	->addOrder("tabla10_campo2")
        	->load();
        $this->fields->tabla10_campo1
        	->setType('select')
        	->setSource($this->source_tenencia_vivienda)
        	->setLabel("Tenencia de la Vivienda")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla10_campo1->label->setWidth(120);

        $this->build("p4a_db_source","source_material_piso")
        	->setTable("tabla8")
        	->setWhere("activo = 'true' AND tabla8_campo3 = 'true'")
        	->addOrder("tabla8_campo2")
        	->load();
        $this->fields->tabla8_campo1
        	->setType('select')
        	->setSource($this->source_material_piso)
        	->setLabel("Material predominante del piso")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla8_campo1->label->setWidth(120);

        $this->build("p4a_db_source","source_material_techo")
        	->setTable("tabla9")
        	->setWhere("activo = 'true' AND tabla9_campo3 = 'true'")
        	->addOrder("tabla9_campo2")
        	->load();
        $this->fields->tabla9_campo1
        	->setType('select')
        	->setSource($this->source_material_techo)
        	->setLabel("Material predominante en el Techo")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla9_campo1->label->setWidth(120);

        $this->build("p4a_db_source","source_material_paredes")
        	->setTable("tabla11")
        	->setWhere("activo = 'true' AND tabla11_campo3 = 'true'")
        	->addOrder("tabla11_campo2")
        	->load();
        $this->fields->tabla11_campo1
        	->setType('select')
        	->setSource($this->source_material_paredes)
        	->setLabel("Material predominante en las paresde exteriores")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla11_campo1->label->setWidth(120);

        $this->fields->tabla14_campo8
        	->setLabel("Número de habitaciones")
        	->setWidth(50)
        	->setProperty('maxlength',"2")
/*
        	->addValidator(new P4A_Validate_StringLength(0,25),true)
*/
        	->addValidator(new P4A_Validate_Int,true);
        $this->fields->tabla14_campo8->label->setWidth(120);

        $this->fields->tabla14_campo9
        	->setLabel("Posee sala");
        $this->fields->tabla14_campo9->label->setWidth(120);

        $this->fields->tabla14_campo10
        	->setLabel("Posee cocina");
        $this->fields->tabla14_campo10->label->setWidth(120);

        $this->fields->tabla14_campo11
        	->setLabel("Número de familias que residen")
        	->setWidth(50)
        	->setProperty('maxlength',"2")
        	->addValidator(new P4A_Validate_Int,true);
        $this->fields->tabla14_campo11->label->setWidth(120);

        $this->fields->tabla14_campo12
        	->setLabel("Requiere de algún arreglo o modificación");
        $this->fields->tabla14_campo12->implement('onclick',$this,'activar_tabla14_campo13');
        $this->fields->tabla14_campo12->label->setWidth(120);

        $this->fields->tabla14_campo13
        	->setLabel("Explique")
        	->disable()
        	->setWidth(150)
        	->setHeight(40)
        	->setTooltip("Explique de forma resuminda qué tipo de arreglo o modificación requiere la vivienda")
/*
        	->setProperty('maxlength',"25")
*/
/*
        	->addValidator(new P4A_Validate_StringLength(0,25),true)
*/
        	->addValidator(new P4A_Validate_Alpha(true),true);
        $this->fields->tabla14_campo13->label->setWidth(50);

        $this->build("p4a_db_source","source_disposicion_aguas")
        	->setTable("tabla15")
        	->setWhere("activo = 'true' AND tabla15_campo3 = 'true'")
        	->addOrder("tabla15_campo2")
        	->load();
        $this->fields->tabla15_campo1
        	->setType('select')
        	->setSource($this->source_disposicion_aguas)
        	->setLabel("Tipo de Disposición de las aguas servidas")
        	->setWidth(150)
        	->allowNull("Seleccione");
        $this->fields->tabla15_campo1->label->setWidth(120);

        /**
         * Propiedades del Mapa
         *
         * el campo <tabla14_campo1> lo desactivamos y ocultamos
         */
        $this->enable
            ->setType('checkbox')
            ->setValue(true);
        $this->triggerclick
			->setType('checkbox')
			->setValue(true);

        $this->mapa->setLabel("Pedregosa Alta - Sector ...");
        $this->marks[] = array(
								'id' => 'Center',
								'address' => GoogleMap::THECENTEROFTHEWORLD,
								'description' => 'The map was centered here at start',
								'latitude' => null,
								'longitude' => null,
/*
								'latitude' => $ltd, 'longitude' => $lng,
*/
								'info' => 'The center of the map<br>',
								'icon' => 'status/dialog-error',
								'times' => 1
								 );
		$this->marksource
			->setPageLimit(8)
			->load($this->marks);

		$this->mapa->setCenter(array(8.604134294989091,-71.19107902050018));

		$this->mapa->setSource($this->marksource);
		$this->mapa->setSourceIdField('id');
		$this->mapa->setSourceAddressField('address');
		$this->mapa->setSourceDescriptionField('description');
		$this->mapa->setSourceCoordinatesFields('latitude','longitude');
		$this->mapa->setSourceInfoField('info');
		$this->mapa->setSourceIconField('icon');

		$this->mapa->setWidth(250);
		$this->mapa->setHeight(250);
		$this->mapa->setZoom(500);
		$this->mapa->setMapType(GoogleMap::SATELLITE);
		$this->mapa->showMoveZoom(GoogleMap::SMALL_CONTROLS);
		$this->mapa->hideMapType();

        return;
    }
    public function activar_tabla14_campo13()
    {
    	if ($this->fields->tabla14_campo12->getNewValue()==true)
    	{
    		$this->fields->tabla14_campo13->enable();
		}else{
			$this->fields->tabla14_campo13->disable()->setNewValue(null);
		}
	}

    public function RefreshMap()
    {
        if ($this->enable->getNewValue() == true) {
            $this->mapa->enable();
        }
        $this->mapa->setZoom(19);

        if ($this->triggerclick->getNewValue() == true) {
			$this->mapa->implement('onclick',$this,'addMarkHere');
		} else {
			$this->mapa->dropImplement('onclick');
		}
		if ($this->triggerchange->getNewValue() == true) {
			$this->mapa->implement('onchange',$this,'mapChanged');
		} else {
			$this->mapa->dropImplement('onchange');
		}
		if ($this->triggermarkclick->getNewValue() == true) {
			$this->mapa->implement('onmarkclick',$this,'selectMark');
		} else {
			$this->mapa->dropImplement('onmarkclick');
		}
/*
		if ($this->triggermarkchange->getNewValue() == true) {
			$this->mapa->implement('onmarkchange',$this,'showMarkCoordinates');
		} else {
			$this->mapa->dropImplement('onmarkchange');
		}
*/

        //
        $this->mapa->redesign();

/*
		$this->RefreshInfo();
*/
    }

    public function RefreshInfo()
	{
		list($longitude,$latitude,$status) = $this->mapa->getCenter();
/*
		$this->longitude->setValue($longitude);
		$this->latitude->setValue($latitude);
		$this->status->setValue($status);
*/

		list($S,$W, $N, $E) = $this->mapa->getBoundaries();
/*
		$this->boundariesSW->setValue("South {$S} West {$W}");
		$this->boundariesNE->setValue("North {$N} East {$E}");
*/

		list($GMS,$GMW, $GMN, $GME) = $this->mapa->getGMBoundaries();
/*
		if (strlen($GMS)>0) {
			$dsw = round(GoogleMap::distance($S,$W,$GMS,$GMW),5);
			$dne = round(GoogleMap::distance($N,$E,$GMN,$GME),5);
			$this->more->setValue("SW Error: $dsw Km./NE Error: $dne Km.");
		} else {
			$this->more->setValue("No boundaries error calculated");
		}
*/
		//$this->currentzoom->setValue(19); /* NO FURULA */

	}

	public function addMarkHere($mapa,$point)
	{
		$this->markid++;
		$ltd = $point[0];
		$lng = $point[1];

		$description = "Mark #{$this->markid}";
		$info = "<strong>marker number {$this->markid}</strong><hr>Latitud {$ltd}<br />Longitud {$lng}";
		$icon = $this->icons[$this->markid%8];

		$this->marks[] = array(
										'id'=>$this->markid,
										'address'=>null,
										'description'=>$description,
										'latitude' => $ltd, 'longitude' => $lng,
										'info' => $info,
										'icon' => $icon,
										'times' => 1
									 );

		$this->marksource->load($this->marks);

		$this->fields->tabla14_campo4->setValue($ltd);
		$this->fields->tabla14_campo5->setValue($lng);

		$this->mapa->redesign();
		$this->infoframe->redesign();
	}
/**
 * This method catch the MarkChanged event and show a
 * message with the new coordinates
 *
 * @param unknown_type $mapa
 * @param unknown_type $params
 */
	public function showMarkCoordinates($mapa,$params)
	{
		// The coordinates of a mark can be obtained, also, using
		// list($lat, $lng) = $mapa.getMarkCoordinates($id);
		// but this method doesn't work with marks in a data source
		$id = $params[0];
		$lat = $params[1];
		$lng = $params[2];
		// I update the source
		foreach($this->marks as $k=>$v) {
			if ($this->marks[$k]['id'] == $id) {
				$this->marks[$k]['latitude'] = $lat;
				$this->marks[$k]['longitude'] = $lng;
				$this->marksource->load($this->marks);
				break;
			}
		}
		$this->info("The mark {$id} is in the Latitude {$lat} and Longitude {$lng}");
	}
/**
 * When a mark is selected the envent is trapped, as sample,
 * here and the row corresponding with this mark is selected
 * as current row in the marks db_source
 *
 * @param unknown_type $mapa
 * @param unknown_type $id
 */
	public function selectMark($mapa,$params)
	{
		$id = $params[0];
		foreach ($this->marks as $k => $v) {
			if ($this->marks[$k]['id'] == $id) {
				$this->marks[$k]['times']++;
				if ($this->marks[$k]['times']> 4) $this->marks[$k]['times']= 1;
				break;
			}
		}
		$this->marksource->load($this->marks);
		$this->marktable->redesign();
		if ($this->clickmsg < 5) {
			$this->info("Look!! The time counter is increased -modulus 3- every time you click the mark");
			$this->clickmsg++;
		}
	}

    public function saveRow()
    {
        /** Varios */
        $tabla14_campo1 = $this->fields->tabla14_campo1->getSQLNewValue();
        $tabla14_campo2 = $this->fields->tabla14_campo2->getSQLNewValue();
        $tabla14_campo3 = $this->fields->tabla14_campo3->getSQLNewValue();
        $tabla14_campo4 = $this->fields->tabla14_campo4->getSQLNewValue();
        $tabla14_campo5 = $this->fields->tabla14_campo5->getSQLNewValue();
        $tabla14_campo6 = $this->fields->tabla14_campo6->getSQLNewValue();
        $tabla14_campo7 = $this->fields->tabla14_campo7->getSQLNewValue();
        $tabla14_campo8 = $this->fields->tabla14_campo8->getSQLNewValue();
        $tabla14_campo9 = $this->fields->tabla14_campo9->getSQLNewValue();
        $tabla14_campo10 = $this->fields->tabla14_campo10->getSQLNewValue();
        $tabla14_campo11 = $this->fields->tabla14_campo11->getSQLNewValue();
        $tabla14_campo12 = $this->fields->tabla14_campo12->getSQLNewValue();
        $tabla14_campo13 = $this->fields->tabla14_campo13->getSQLNewValue();
        $tabla14_campo14 = $this->fields->tabla14_campo14->getSQLNewValue();
        $tabla14_campo15 = $this->fields->tabla14_campo15->getSQLNewValue();
        $tabla7_campo1 = $this->fields->tabla7_campo1->getSQLNewValue();
        $tabla8_campo1 = $this->fields->tabla8_campo1->getSQLNewValue();
        $tabla9_campo1 = $this->fields->tabla9_campo1->getSQLNewValue();
        $tabla10_campo1 = $this->fields->tabla10_campo1->getSQLNewValue();
        $tabla11_campo1 = $this->fields->tabla11_campo1->getSQLNewValue();
        $tabla15_campo1 = $this->fields->tabla15_campo1->getSQLNewValue();

		if (!$tabla14_campo1) $tabla14_campo1 = '0';
		if (!$tabla14_campo2) $tabla14_campo2 = '0';
		if (!$tabla14_campo7) $tabla14_campo7 = '0';
		if (!$tabla14_campo8) $tabla14_campo8 = '0';
		if (!$tabla14_campo9) $tabla14_campo9 = '0';
		if (!$tabla14_campo10) $tabla14_campo10 = '0';
		if (!$tabla14_campo11) $tabla14_campo11 = '0';
		if (!$tabla14_campo12) $tabla14_campo12 = '0';
		if (!$tabla7_campo1) $tabla7_campo1 = '0';
		if (!$tabla8_campo1) $tabla8_campo1 = '0';
		if (!$tabla9_campo1) $tabla9_campo1 = '0';
		if (!$tabla10_campo1) $tabla10_campo1 = '0';
		if (!$tabla11_campo1) $tabla11_campo1 = '0';
		if (!$tabla15_campo1) $tabla15_campo1 = '0';

        $queryInsert = "INSERT INTO cc1.tabla14 (tabla14_campo1, tabla14_campo2, tabla14_campo3, tabla14_campo4, tabla14_campo5, tabla14_campo6, tabla14_campo7, tabla14_campo8, tabla14_campo9, tabla14_campo10, tabla14_campo11, tabla14_campo12, tabla14_campo13, tabla14_campo14, tabla14_campo15, tabla7_campo1, tabla8_campo1, tabla9_campo1, tabla10_campo1, tabla11_campo1, tabla15_campo1) VALUES($tabla14_campo1, $tabla14_campo2, $tabla14_campo3, $tabla14_campo4, $tabla14_campo5, $tabla14_campo6, $tabla14_campo7, $tabla14_campo8, $tabla14_campo9, $tabla14_campo10, $tabla14_campo11, $tabla14_campo12, $tabla14_campo13, $tabla14_campo14, $tabla14_campo15, $tabla7_campo1, $tabla8_campo1, $tabla9_campo1, $tabla10_campo1, $tabla11_campo1, $tabla15_campo1)";
		
        $queryUpdate = "UPDATE cc1.tabla14 SET tabla14_campo2 = '$tabla14_campo2', tabla14_campo3 = '$tabla14_campo3', tabla14_campo4 = '$tabla14_campo4', tabla14_campo5 = '$tabla14_campo5', tabla14_campo6 = '$tabla14_campo6', tabla14_campo7 = '$tabla14_campo7', tabla14_campo8 = '$tabla14_campo8', tabla14_campo9 = '$tabla14_campo9', tabla14_campo10 = '$tabla14_campo10', tabla14_campo11 = '$tabla14_campo11', tabla14_campo12 = '$tabla14_campo12', tabla14_campo13 = '$tabla14_campo13', tabla14_campo14 = '$tabla14_campo14', tabla14_campo15 = '$tabla14_campo15', tabla7_campo1 = '$tabla7_campo1', tabla8_campo1 = '$tabla8_campo1', tabla9_campo1 = '$tabla9_campo1', tabla10_campo1 = '$tabla10_campo1', tabla11_campo1 = '$tabla11_campo1', tabla15_campo1 = '$tabla15_campo1' WHERE tabla14_campo1 = $tabla14_campo1";
        try
        {
//             if (!$this->fields->destino->getNewValue()) {
//                 $this->error("El campo Destino es Obligatorio");
//                 return;
//             }
            $p4a_db = P4A_DB::singleton();
            /** verificar sí existe el registro **/
            $this->user_data = $p4a_db->fetchRow("SELECT count(*) AS existe FROM cc1.tabla14 WHERE tabla14_campo2 = $tabla14_campo2 AND (tabla14_campo3 LIKE '$tabla14_campo3' OR tabla14_campo15 = '$tabla14_campo15')");$this->info("SELECT count(*) AS existe FROM cc1.tabla14 WHERE tabla14_campo2 = $tabla14_campo2 AND (tabla14_campo3 LIKE '$tabla14_campo3' OR tabla14_campo15 = '$tabla14_campo15')");
            if (!$this->user_data['existe'])
            {
//                 $this->user_data = $p4a_db->fetchRow("SELECT nextval ('viatico_".$id_dependencia."_seq'::regclass)");
$this->info("Insert: $queryInsert");
//                 $p4a_db->query($queryInsert);
            }else
                $p4a_db->query($queryUpdate);
// $this->info("Update: $queryUpdate");

            $this->info("Registro Guardado");

            // auditoria
            $p4a_db->query("create temp table audit_tmp (usuario integer, ip inet, ocurrencia timestamp default now());");
            $p4a_db->query("insert into audit_tmp values ('".$_SESSION['id']."', '".$_SERVER['REMOTE_ADDR']."');");

        } catch (Exception $e)
        {
            $this->error('mensaje:'.$e->getMessage().' codigo:'.$e->getCode());
//             $this->error('mensaje:'.$e->getMessage().' código:'.$e->getCode().' trace:'.$e->getTrace().' traceString:'.$e->getTraceAsString());
            $this->error("Imposible guardar");
        }
    }

}
