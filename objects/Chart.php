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
 * Chart Widget for P4A 3
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2
 * as published by the Free Software Foundation.
 *
 * The regression method has been copied from the mariospada.net web site
 * You can read the entire article at http://www.mariospada.net/2008/08/php-calcolare-la-retta-di-regr.html
 * And, here, I want thanks for it
 *
 * The latest version of Calendar can be obtained from:
 * {@link http://p4aenespanol.googlegroups.com/web/Chart.zip}

 * @version 0.1.0
 * @author Blas Lopez <blas_lg@yahoo.es>
 */
class Chart extends P4A_Widget
{
/**
 * Chart type
 *
 */
	const DOTS = 1;
	const BAR = 2;
	const LINE = 3;
	const PIE = 4;

	const AXIS_ITEM = 'I';
	const AXIS_LEFT = 'L';
	const AXIS_RIGHT = 'R';

/**
 * Legend position
 * UL - UC - UR
 * CL -    - CR
 * DL - DC - DR
 */
	const LEGEND_UP = 'U';
	const LEGEND_DOWN = 'D';
	const LEGEND_LEFT = 'L';
	const LEGEND_RIGHT = 'R';
	const LEGEND_CENTER = 'C';

	// Chart configuration

// image resource
	private $_chart;

// Areas description
	private $_areas;

/**
 * Legend position
 */
	private $_legendpos = array();
	private $_correctpos = array('UL','CL','DL','UC','DC','UR','CR','DR');
	private $_legendmargin = 10;

/**
 * Show lengend
 *
 * @var unknown_type
 */
	private $_showlegend = true;
/**
 * Legend font
 *
 * @var unknown_type
 */
	private $_legendfont = 2;

/**
 * Legend contents
 * 'x'
 * 'y'
 * 'width'
 * 'height'
 * 'data'
 *    'label' : datalabel
 *    'color' : color
 */
	private $_legend = array();

/**
 * Data colors used for any observation
 *
 * Initialized with a set of color can be
 * changed using setColors method
 * @var unknown_type
 */
	private $_initcolors = array('3333CC','990066','FFCC00','006633','660000','CCFF00','FF0000','660066','009900','FF6600','99FFFF','CC99FF','CC6666','336600');
	private $_datacolors = array();

/**
 * Fonts
 */
	private $_titlefont = 5;

/**
 * Background color
 *
 * @var unknown_type
 */
	private $_bgcolor = array();

/**
 * Foreground color (used for text)
 *
 * @var unknown_type
 */
	private $_fgcolor = array();

/**
 * Shadow width (when applied)
 *
 * @var number
 */
	private $_shading = 10;

/**
 * Axis escale
 */
	public $axis = array();

/**
 * Step, limits and used for any axis
 *
 * @var unknown_type
 */
	private $_step = array();
	private $_max = array();
	private $_min = array();
	private $_used = array();
	private $_czonetop = 0;
	private $_czonebot = 0;
	private $_czoneleft = 0;
	private $_czoneright = 0;

// IE doesn't support data URL. Workaround vars
/**
 * Directory for working when creating tmp images
 *
 * @var string
 */
	private $_working_dir = P4A_UPLOADS_DIR;
	private $_working_path = P4A_UPLOADS_PATH;

// Data
/**
 * Set of data represented in the chart
 * and properties
 * values - Data object
 * axis reference
 */
	private $_data = array();

/**
 * Using a datasource in chart (not in data) can
 * reach a better performance when defining several
 * data from a datasource
 * ex: select type, count(*) as items, sum(total) as total, max(total) as higher
 *       from products
 *      group by type
 *
 * sets the source to the graph and indicating what are the column for each data
 * will be better that making a setsource for each data
 * @var unknown_type
 */
	private $_datasource = null;
	private $_edata;
	private $_loaded = false;

/**
 * ChartZone coordinates used to mapping
 * between image coord and (item,value) coord
 */

	private $_top;
	private $_left;
	private $_right;
	private $_bottom;

/**
 * Build method check if GD is instaled
 *
 * @param unknown_type $name
 */
	public function __construct($name)
	{
		parent::__construct($name);
		if (!P4A_GD) {
			throw new P4A_Exception("GD module not installed", P4A_FILESYSTEM_ERROR);
		}
		$this->reset();

		return $this;
	}

	public function reset()
	{
		// Default values
		$this->setHeight(250);
		$this->setWidth(500);
		$this->setBackgroundColor();
		$this->setForegroundColor();
		$this->setTitleFont();
		$this->setShadow();
		$this->setColors();
		$this->showLegend();
		$this->setLegendPos();
		$this->setLegendFont();

		$this->axis[self::AXIS_ITEM] = new ChartAxis(ChartAxis::TYPE_X);
		$this->axis[self::AXIS_LEFT] = new ChartAxis(ChartAxis::TYPE_Y);
		$this->axis[self::AXIS_RIGHT] = new ChartAxis(ChartAxis::TYPE_Y);
		$this->_data = array();
		$this->_areas = null;

		return $this;
	}

/**
 * Renders the html code for the graphic
 * The chart image is generate as data URL base64 encoded
 * but if IE is detected (IE doesn't support data URLs)
 * the image is generated in the tmp path and is sended
 * as href URL.
 *
 * @return unknown
 */
	public function getAsString()
	{
		$id = $this->getId();
		if ((!$this->isVisible()) or (empty($this->_data)) ) {
			return "<div id='$id' class='hidden'></div>";
		}
		$this->_drawit();

		$map = "<map id='{$id}map' name='{$id}map'>$this->_areas</map>";
		// Internet Explorer doesn't accepts data URLs
		if (P4A::singleton()->isInternetExplorer()) {
			$f = P4A_Get_Unique_File_Name(P4A_Get_Valid_File_Name('chartwidget'),$this->_working_dir).'.png';
			imagepng($this->_chart,$this->_working_dir.DIRECTORY_SEPARATOR.$f);
			$src = $this->_working_path.'/'.$f;
		} else {
			ob_start();
			imagepng($this->_chart);
			$strchart = ob_get_contents();
			ob_end_clean();
			$inline = base64_encode($strchart);
			$src ="data:image/png;base64,{$inline}";
		}
		imagedestroy($this->_chart);

		$properties = $this->composeStringProperties();

		$html = "<div id='$id' $properties>";
		$html .= $map;
		$html .= '<img style="z-index:-1;width:'.$this->getWidth().';height='.$this->getHeight().'" src="'.$src.'" usemap="#'.$id.'map" />';
		$html .= '</div>';
		return $html;
	}

/**
 * Draws the chart and generates it in $filename
 *
 * Optionally the file format can be set
 *
 * @param string $filename
 * @param string $format = IMG_PNG
 */
	public function getImage($filename = null, $format = IMG_PNG)
	{
		$formats = array(IMG_PNG=>'imagepng',IMG_GIF=>'imagegif',IMG_JPG=>'imagejpg',IMG_WBMP=>'imagewbmp');
		if (array_key_exists($format,$formats)) {
			$this->_drawit();
			if (strlen($filename)> 0) {
				$formats[$format]($this->_chart,$filename);
			} else {
				ob_start();
				$formats[$format]($this->_chart);
				$strchart = ob_get_contents();
				ob_end_clean();
				return $strchart;
			}
		}
	}

/**
 * Creates the image resource ($this->_chart) and
 * its areas description ($this->_areas)
 *
 */
	private function _drawit()
	{
		if (!$this->_loaded) $this->load();

		$this->_prepareChart();
		// The image is created and the background color is set
		$this->_chart = imagecreate($this->getWidth(),$this->getHeight());
		$color = imagecolorallocate($this->_chart, $this->_bgcolor[0], $this->_bgcolor[1],$this->_bgcolor[2]);
		$charttitle = $this->getLabel();
		if (strlen($charttitle) > 0) {
			$color = imagecolorallocate($this->_chart, $this->_fgcolor[0], $this->_fgcolor[1],$this->_fgcolor[2]);
			$x = $this->_center($charttitle,$this->_titlefont,$this->getWidth());
			imagestring($this->_chart,$this->_titlefont,$x,$this->_czonebot,utf8_decode($charttitle),$color);
		}

		$barcount = 0;
		reset($this->_datacolors);
		$this->_areas = null;
		foreach ($this->_data as $data) {
			switch($data->getType()) {
				case self::BAR:
					$barcount++;
					$this->_barChart($data,$barcount);
					break;
				case self::LINE:
					$this->_lineChart($data);
					break;
				case self::DOTS:
					$this->_dotsChart($data);
					break;
				case self::PIE:
					$this->_pieChart($data);
					break;
			}
		}
		if (($this->_used[self::AXIS_LEFT]) or ($this->_used[self::AXIS_RIGHT])) $this->_drawAxis();
		if ($this->_showlegend == true) $this->_drawLegend();
	}

/**
 * Calculates the centered coordinate for a string
 * using a font
 */
	private function _center($string, $font, $size)
	{
		return ($size - (imagefontwidth($font) * strlen($string))) / 2;
	}

/**
 * Calculates the hegiht of a string
 */
	private function _h($string,$font)
	{
		if (strlen($string) == 0)
			return 0;
		else
			return imagefontheight($font);
	}
	private function _w($string,$font)
	{
		return (imagefontwidth($font) * strlen($string));
	}

/**
 * Makes a darkest color
 *
 * @param number $r
 * @param number $g
 * @param number $b
 * @return array
 */
	private function _dark($r,$g,$b)
	{
		$r = max(0, $r - 0x30);
		$g = max(0, $g - 0x30);
		$b = max(0, $b - 0x30);
		return array($r,$g,$b);
	}

/**
 * Converts HTML color codes to rgb
 *
 * @param mixed $color
 * @return array
 */
	public static function _to_rgb($color)
	{
		if (is_array($color)) {
			return $color;
		} else {
			if ($color[0] == '#') $color = substr($color, 1);
			if (strlen($color) == 6)
				list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
    		elseif (strlen($color) == 3)
				list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
			else
				return array(0,0,0);

			$rgb = array(hexdec($r), hexdec($g),hexdec($b));
		}
		return $rgb;
	}

/**
 * Changes the coordinates from natural to image
 */
	private function _x($x)
	{
		return round($this->_left + (($x-$this->_min[self::AXIS_ITEM]) * $this->_step[self::AXIS_ITEM]),0);
	}
	private function _y($y, $dataaxis = self::AXIS_LEFT)
	{
		return round($this->_top - (($y-$this->_min[$dataaxis] )* $this->_step[$dataaxis]),0);
	}
	private function _xy($x,$y, $dataaxis = self::AXIS_LEFT)
	{
		$x1 = $this->_x($x);
		if ($this->_bars > 0) $x1 = $x1 + ($this->_step[self::AXIS_ITEM]/2);
		$y1 = $this->_y($y,$dataaxis);
		return array($x1,$y1);
	}

/**
 * Calculate the initial values acoording to the data
 * the dimensions and other params.
 *
 */
	private function _prepareChart()
	{
		// Search in any data max and min values and calculates the boundaries
		$this->_bars = 0;
		foreach ($this->axis as $kaxis => $axis) {
			$this->_max[$kaxis] = null;
			$this->_min[$kaxis] = null;
			$this->_used[$kaxis] = false;
		}
		// The automatic limits of axis
		foreach ($this->_data as $data) {
			// axis limits vs data
			$axis = $data->getAxis();
			$v = $data->getMax();
			if (($v > $this->_max[$axis]) or ($this->_max[$axis] == null)) $this->_max[$axis] = $v;
			$v = $data->getMin();
			if (($v < $this->_min[$axis]) or ($this->_min[$axis] == null)) $this->_min[$axis] = $v;
			if ($this->axis[self::AXIS_ITEM]->isDiscrete()) {
				$v = $data->getCount();
				if ($v > $this->_max[self::AXIS_ITEM]) $this->_max[self::AXIS_ITEM] = $v;
			} else {
				$v = $data->getLast();
				if (($v > $this->_max[self::AXIS_ITEM]) or ($this->_max[self::AXIS_ITEM] == null)) $this->_max[self::AXIS_ITEM] = $v;
				$v = $data->getFirst();
				if (($v < $this->_min[self::AXIS_ITEM]) or ($this->_min[self::AXIS_ITEM] == null) ) $this->_min[self::AXIS_ITEM] = $v;
			}

			// how many bars chats
			if ($data->getType() == self::BAR) $this->_bars++;
		}

		foreach ($this->axis as $kaxis => $axis) {
			$v = $axis->getMax();
			if (($v > $this->_max[$kaxis]) and (strlen($v) != 0)) $this->_max[$kaxis] = $v;
			$v = $axis->getMin();
			if (($v < $this->_min[$kaxis]) and (strlen($v) != 0)) $this->_min[$kaxis] = $v;
			if ($this->_max[$kaxis] <> $this->_min[$kaxis]) {
				$this->_step[$kaxis] = 1 / ($this->_max[$kaxis] - $this->_min[$kaxis]);
			} else {
				$this->_step[$kaxis] = 0;
			}
		}
		$this->_czonetop = $this->getHeight() - 1;
		$this->_czonebot = 1;
		$this->_czoneleft = 1;
		$this->_titlepos = 0;
		$this->_czoneright = $this->getWidth() - 1;
		// Legend space
		$this->_legend = array('x'=>0,'y'=>0,'width'=>0,'height'=>0);
		if ($this->_showlegend == true) {
			$labelwidth = 0;
			foreach ($this->_data as $name => $data) {
				if ($data->hasSubdata()) {
					foreach ($data->getSubLabels() as $k => $label)	{
						$this->_legend['data'][$name.$k]['label'] = $label;
						$lw = $this->_w($label,$this->_legendfont);
						$labelwidth = ($lw > $labelwidth) ? $lw : $labelwidth;
					}
				} else {
					if ($data->getType() == Chart::PIE) {
						foreach ($data->getItemLabels() as $k => $label)	{
							$this->_legend['data'][$name.$k]['label'] = $label;
							$lw = $this->_w($label,$this->_legendfont);
							$labelwidth = ($lw > $labelwidth) ? $lw : $labelwidth;
						}
					} else {
						$label = $data->getLabel();
						$this->_legend['data'][$name]['label'] = $label;
						$lw = $this->_w($label,$this->_legendfont);
						$labelwidth = ($lw > $labelwidth) ? $lw : $labelwidth;
					}
				}
			}
			if ((($this->_legendpos[0] == self::LEGEND_UP) or ($this->_legendpos[0] == self::LEGEND_DOWN)) and ($this->_legendpos[1] == self::LEGEND_CENTER)){
				$this->_legend['step'] = $labelwidth + imagefontwidth($this->_legendfont) + 4;
				$this->_legend['height'] = imagefontheight($this->_legendfont)+ 8;
				$this->_legend['width'] = (count($this->_legend['data']) * $this->_legend['step']) + imagefontwidth($this->_legendfont) + 4 ;
				$this->_legend['mode'] = 'row';
			} else {
				$this->_legend['width'] = $labelwidth + 15;
				$this->_legend['step'] = imagefontheight($this->_legendfont)* 1.4;
				$this->_legend['height'] = (count($this->_legend['data']) * $this->_legend['step']) + 4;
				$this->_legend['mode'] = 'col';
			}
			// The chart zone must be modified
			switch ($this->_legendpos[0]) {
				case self::LEGEND_UP:
					$this->_legend['y'] = 0;
					if ($this->_legendpos[1] == self::LEGEND_CENTER) $this->_czonebot = $this->_czonebot + ($this->_legend['height'] + $this->_legendmargin);
					break;
				case self::LEGEND_DOWN:
					$this->_legend['y'] = $this->getHeight() - $this->_legend['height'];
					if ($this->_legendpos[1] == self::LEGEND_CENTER) $this->_czonetop = $this->_czonetop - ($this->_legend['height'] + $this->_legendmargin);
					break;
				default:
					$this->_legend['y'] = ($this->getHeight() - $this->_legend['height'])/2;
			}
			switch ($this->_legendpos[1]) {
				case self::LEGEND_LEFT:
					$this->_legend['x'] = 0;
					$this->_czoneleft += $this->_legend['width'] + $this->_legendmargin;
					break;
				case self::LEGEND_RIGHT:
					$this->_legend['x'] = $this->getWidth()-$this->_legend['width'];
					$this->_czoneright -= ($this->_legend['width'] + $this->_legendmargin);
					break;
				default:
					$this->_legend['x'] = ($this->getWidth() - $this->_legend['width'])/2;
			}
		}
		// The chartzone excludes labels
		$h = $this->_h($this->axis[self::AXIS_ITEM]->getLabel(),$this->axis[self::AXIS_ITEM]->getLabelFont());
		if ($this->axis[self::AXIS_ITEM]->labeled()) {
			$h += imagefontheight($this->axis[self::AXIS_ITEM]->getTickLabelsFont());
		}

		$this->_top = $this->_czonetop - $h - 1 ;
		$this->_bottom = $this->_czonebot + $this->_h($this->getLabel(),$this->_titlefont) + $this->_titlepos + $this->_shading + 15;
		$this->_left = $this->_czoneleft + $this->axis[self::AXIS_LEFT]->getTickLabelSize() + $this->_h($this->axis[self::AXIS_LEFT]->getLabel(),$this->axis[self::AXIS_LEFT]->getLabelFont())+ 1;
		$this->_right = $this->_czoneright - $this->axis[self::AXIS_RIGHT]->getTickLabelSize() - $this->_h($this->axis[self::AXIS_RIGHT]->getLabel(),$this->axis[self::AXIS_RIGHT]->getLabelFont())- 10 ;

		// Apply the dimensions
		$this->_step[self::AXIS_ITEM] = $this->_step[self::AXIS_ITEM] * ($this->_right - $this->_left);
		$this->_step[self::AXIS_LEFT] = $this->_step[self::AXIS_LEFT] * ($this->_top - $this->_bottom);
		$this->_step[self::AXIS_RIGHT] = $this->_step[self::AXIS_RIGHT] * ($this->_top - $this->_bottom);
	}

	private function _drawAxis()
	{
		// 4 axis coord, direction, limit
		$lzone[self::AXIS_ITEM] = array($this->_left,$this->_top,$this->_right,$this->_top,1,$this->_left,$this->_czonetop);
		$lzone[self::AXIS_LEFT] = array($this->_left,$this->_bottom,$this->_left,$this->_top,1,$this->_czoneleft,$this->_top);
		$lzone[self::AXIS_RIGHT] = array($this->_right,$this->_bottom,$this->_right,$this->_top,-1,$this->_czoneright,$this->_top);
		// Draw Item Axis
		foreach ($this->axis as $ka => $axis)
		{
			if ( (!$axis->isVisible()) or (!$this->_used[$ka])) break;
			// colors
			$color = $axis->getColor();
			if (!isset($color)) $color = $this->_fgcolor;
			list($r,$g,$b) = $this->_to_rgb($color);
			$axiscolor = imagecolorallocate($this->_chart, $r,$g,$b);
			$dot = array($axiscolor,$axiscolor,IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT);
			$solid = array($axiscolor,$axiscolor,$axiscolor,$axiscolor);
			// params
			list($x1,$y1,$x2,$y2,$dir,$extrax,$extray) = $lzone[$ka];
			// Axis line
			imageline($this->_chart,$x1,$y1,$x2,$y2,$axiscolor);

			// Axis Label
			$lbl = $axis->getLabel();
			if (strlen($lbl) > 0) {
				$lx1 = min($x1,$extrax);
				$lx2 = max($x2,$extrax);
				$ly1 = min($y1,$extray);
				$ly2 = max($y2,$extray);
				$lfont = $axis->getLabelFont();
				if ($axis->getType() == ChartAxis::TYPE_X) {
					$lx1 = $this->_center($lbl,$lfont,$lx2 - $lx1);
					$ly1 = $ly2 - imagefontheight($lfont);
					imagestring($this->_chart,$lfont,$lx1,$ly1,utf8_decode($lbl),$axiscolor);
				} else {
					$lx1 = $extrax - (($dir == 1) ? 0 : imagefontheight($lfont));
					$ly1 = $ly2 - $this->_center($lbl,$lfont,$ly2 - $ly1);
					imagestringup($this->_chart,$lfont,$lx1,$ly1,utf8_decode($lbl),$axiscolor);
				}
			}

			// Axis ticks: ticks, tick labels and grid
			$tlfont = $axis->getTickLabelsFont();
			$th = imagefontheight($tlfont) + 2; // t
			foreach ($axis->getTickLabels() as $value => $wtext) {
				// tick label
				$text = __($wtext);
				$w = $this->_w($text,$tlfont);
				// tick
				if ($axis->getType() == ChartAxis::TYPE_X) {
					list($x0,$y0) = $this->_xy($value,0); // axis cut point
					$dy = 2; $dx = 0; // shift for tick
					$lx = $x0 - ($w / 2); $ly = $y0 + $dy; // tick label pos
					$xg1 = $x0; $yg1 = $this->_top; // grid start point
					$xg2 = $x0; $yg2 = $this->_bottom; // grid end point
				} else {
					$x0 = $x1; $y0 = $this->_y($value,$ka); // axis cut point
					$dx = 2; $dy = 0; // shift for tick
					$lx = $x0 - ($dir * $dx) - ($dir == 1 ? $w : 0); $ly = $y0 - ($th / 2); // tick label pos
					$xg1 = $this->_left ; $yg1 = $y0; // grid start point
					$xg2 = $this->_right; $yg2 = $y0; // grid end point
				}
				imageline($this->_chart,$x0-$dx,$y0-$dy,$x0+$dx,$y0+$dy,$axiscolor);
				imagestring($this->_chart,$tlfont,$lx,$ly,utf8_decode($text),$axiscolor);
				// grid
				if ($axis->gridVisible()) {
					imagesetstyle($this->_chart,$dot);
					imageline($this->_chart,$xg1,$yg1,$xg2,$yg2,IMG_COLOR_STYLED);
					imagesetstyle($this->_chart,$solid);
				}
			}

			// Additional lines
			foreach ($axis->getLines() as $line) {
				// line label
				$value = $line[0];
				$text = __($line[1]);
				if (strlen($line[2]) == 0) {
					$linecolor = $axiscolor;
				} else {
					list($r,$g,$b) = $this->_to_rgb($line[2]);
					$linecolor = imagecolorallocate($this->_chart, $r,$g,$b);
					$dot = array($linecolor,$linecolor,IMG_COLOR_TRANSPARENT, IMG_COLOR_TRANSPARENT);
				}
				// tick
				if ($axis->getType() == ChartAxis::TYPE_X) {
					list($x0,$y0) = $this->_xy($value,0); // axis cut point
					$xg1 = $x0; $yg1 = $this->_top; // grid start point
					$xg2 = $x0; $yg2 = $this->_bottom; // grid end point
					$lx = $xg1 - imagefontheight($tlfont) - 2;
					$ly = $this->_top - $this->_center($text,$tlfont,$yg1-$yg2);
					imagestringup($this->_chart,$lfont,$lx,$ly,utf8_decode($text),$linecolor);
				} else {
					$x0 = $x1; $y0 = $this->_y($value,$ka); // axis cut point
					$xg1 = $this->_left ; $yg1 = $y0; // grid start point
					$xg2 = $this->_right; $yg2 = $y0; // grid end point
					$ly = $yg1 - imagefontheight($tlfont) - 2;
					$lx = $xg1 + $this->_center($text,$tlfont,$xg2-$xg1);
					imagestring($this->_chart,$lfont,$lx,$ly,utf8_decode($text),$linecolor);
				}
				imagesetstyle($this->_chart,$dot);
				imageline($this->_chart,$xg1,$yg1,$xg2,$yg2,IMG_COLOR_STYLED);
				imagesetstyle($this->_chart,$solid);
			}
		}
	}

	private function _drawLegend()
	{
		list($r,$g,$b) = $this->_to_rgb($this->_fgcolor);
		$color = imagecolorallocate($this->_chart, $r,$g,$b);
		$x1 = $this->_legend['x'];
		$y1 = $this->_legend['y'];
		$x2 = $x1 + $this->_legend['width'] - 1;
		$y2 = $y1 + $this->_legend['height'] - 1;
		imagerectangle($this->_chart,$x1,$y1,$x2,$y2,$color);
		$x1 += 4;
		$y1 += 4;
		$w = imagefontwidth($this->_legendfont);
		$h = imagefontheight($this->_legendfont);
		foreach ($this->_legend['data'] as $name => $def) {
			if (isset($def['color']) and (isset($def['label']))) {
				imagefilledrectangle($this->_chart,$x1,$y1,$x1+$w-1,$y1 + $h,$def['color']);
				imagestring($this->_chart,$this->_legendfont,$x1+$w+3,$y1 ,utf8_decode($def['label']),$color);
				if ($this->_legend['mode'] == 'row') {
					$x1 += $this->_legend['step'];
				} else {
					$y1 += $this->_legend['step'];
				}
			}
		}
	}

/**
 * Plot a line Chart
 */
	private function _lineChart($data)
	{
		// Draws any not empty observation
		if ($data->getCount() == 0) return ;
		$name = $data->getName();

		$x = 0;
		$color = $data->getColor();
		if (!isset($color)) {
			$color = current($this->_datacolors);
			next($this->_datacolors);
		}
		list($r,$g,$b) = $this->_to_rgb($color);
		$datacolor = imagecolorallocate($this->_chart, $r, $g, $b);
		if ($this->_showlegend) $this->_legend['data'][$name]['color'] = $datacolor;
		$xp = null;
		$yp = null;
		$datalabel = $data->getLabel();
		$dataaxis = $data->getAxis();
		$discrete = $this->axis[self::AXIS_ITEM]->isDiscrete();
		$this->_used[$dataaxis] = true;
		$this->_used[self::AXIS_ITEM] = true;
		$vfont = $data->getValuesFont();
		if ($this->isEnabled()) {
			if (isset($action_data['ajax']) and $action_data['ajax'] == 1) {
				$execute = 'p4a_event_execute_ajax';
			} else {
				$execute = 'p4a_event_execute';
			}
			$id = $this->getId();
			$style = 'style="cursor:pointer;"';
		} else {
			$onclick = null;
			$style = null;
		}
		foreach ($data->getValues() as $key => $value) {
			if ($value != null) {
				$wx = $discrete ? $x : $key;
				list($x1,$y1) = $this->_xy($wx,$value,$dataaxis);
				imagesetpixel($this->_chart,$x1,$y1,$datacolor);
				if (($xp != null) and ($yp != null)) {
					imageline($this->_chart,$xp,$yp,$x1,$y1,$datacolor);
				}

				if ($this->isEnabled()) {
					$onclick = 'onclick="'."{$execute}('{$id}','onclick','{$name}','{$wx}');".'"';
				}
				$this->_areas .= <<<HTML
<area shape="circle" coords="{$x1},{$y1},4" title="{$datalabel} [$wx]: $value" {$onclick} {$style}/>
HTML;
				imagefilledellipse($this->_chart,$x1,$y1,4,4,$datacolor);
				if ($data->valuesVisibles()) {
					$w = $this->_w($value,$vfont)/2;
					$h = $this->_h($value,$vfont)+5;
					imagestring($this->_chart,$vfont,$x1-$w,$y1-$h,utf8_decode($value),$datacolor);
				}
				$xp = $x1;
				$yp = $y1;
			} else {
				$xp = null;
				$yp = null;
			}
			$x++;
		}
	}

/**
 * Plot a dots Chart
 */
	private function _dotsChart($data)
	{
		// Draws any not empty observation
		if ($data->getCount() == 0) return ;

		$name = $data->getName();
		$x = 0;
		$color = $data->getColor();
		if (!isset($color)) {
			$color = current($this->_datacolors);
			next($this->_datacolors);
		}
		list($r,$g,$b) = $this->_to_rgb($color);
		$datacolor = imagecolorallocate($this->_chart, $r, $g, $b);
		if ($this->_showlegend) $this->_legend['data'][$name]['color'] = $datacolor;
		$xp = null;
		$yp = null;
		$datalabel = $data->getLabel();
		$dataaxis = $data->getAxis();
		$discrete = $this->axis[self::AXIS_ITEM]->isDiscrete();
		$this->_used[$dataaxis] = true;
		$this->_used[self::AXIS_ITEM] = true;
		$vfont = $data->getValuesFont();
		if ($this->isEnabled()) {
			if (isset($action_data['ajax']) and $action_data['ajax'] == 1) {
				$execute = 'p4a_event_execute_ajax';
			} else {
				$execute = 'p4a_event_execute';
			}
			$id = $this->getId();
			$style = 'style="cursor:pointer;"';
		} else {
			$onclick = null;
			$style = null;
		}
		foreach ($data->getValues() as $key => $value) {
			if ($value != null) {
				$wx = $discrete?$x:$key;
				if ($data->hasSubdata()) {
					foreach ($data->getSubdata($key) as $v) {
						list($x1,$y1) = $this->_xy($wx,$v,$dataaxis);
						imagefilledrectangle($this->_chart,$x1-2,$y1-2,$x1+2,$y1+2,$datacolor);
						if ($this->isEnabled()) {
							$onclick = 'onclick="'."{$execute}('{$id}','onclick','{$name}','{$wx}', '{$v}');".'"';
						}
						$this->_areas .= <<<HTML
<area shape="circle" coords="{$x1},{$y1},5" title="{$datalabel} [$wx]: {$v}" {$onclick} {$style}/>
HTML;
					}
				} else {
					list($x1,$y1) = $this->_xy($wx,$value,$dataaxis);
					imagefilledrectangle($this->_chart,$x1-2,$y1-2,$x1+2,$y1+2,$datacolor);
					if ($this->isEnabled()) {
						$onclick = 'onclick="'."{$execute}('{$id}','onclick','{$name}','{$wx}');".'"';
					}
					$this->_areas .= <<<HTML
<area shape="circle" coords="{$x1},{$y1},5" title="{{$datalabel}} [{$wx}]: {$value}" {$onclick} {$style} />
HTML;
					if ($data->valuesVisibles()) {
						$w = $this->_w($value,$vfont)/2;
						$h = $this->_h($value,$vfont)+5;
						imagestring($this->_chart,$vfont,$x1-$w,$y1-$h,utf8_decode($value),$datacolor);
					}
				}
			}
			$x++;
		}
		$regression = $data->getRegression();
		if (!empty($regression)) {
			$color = $data->getRegressionColor();
			list($r,$g,$b) = $this->_to_rgb($color);
			$linecolor = imagecolorallocate($this->_chart, $r,$g,$b);

			list($x1,$y1) = $this->_xy($regression[0],$regression[1]);
			list($x2,$y2) = $this->_xy($regression[2],$regression[3]);
			imageline($this->_chart,$x1,$y1,$x2,$y2,$linecolor);
		}
	}

/**
 * Plot a bar chart
 */
	private function _barChart($data,$count)
	{

		if ($data->getCount() == 0) return ;
		$name = $data->getName();

		// Draws any observation

		$datalabel = $data->getLabel();
		$dataaxis = $data->getAxis();
		$this->_used[$dataaxis] = true;
		$this->_used[self::AXIS_ITEM] = true;

		$width = ($this->_step[self::AXIS_ITEM]/($this->_bars+1))-$this->_shading;
		$shiftl =  ($width * ($count-1)) - ($width / 2) ;
		if ($data->hasSubdata()) {
			$subcolor = array();
			$sublabels = $data->getSubLabels();
		} else {
			$color = $data->getColor();
			if (!isset($color)) {
				$color = current($this->_datacolors);
				next($this->_datacolors);
			}
			list($r,$g,$b) = $this->_to_rgb($color);
			$datacolor = imagecolorallocate($this->_chart, $r, $g, $b);
			if ($this->_showlegend) $this->_legend['data'][$name]['color'] = $datacolor;
			if ($this->_shading > 0) {
				list ($r,$g,$b) = $this->_dark($r,$g,$b);
				$shadowcolor = imagecolorresolve($this->_chart, $r, $g, $b);
			}
		}
		$x = 0;
		$vfont = $data->getValuesFont();
		$h = imagefontheight($vfont);
		if ($this->isEnabled()) {
			if (isset($action_data['ajax']) and $action_data['ajax'] == 1) {
				$execute = 'p4a_event_execute_ajax';
			} else {
				$execute = 'p4a_event_execute';
			}
			$id = $this->getId();
			$style = 'style="cursor:pointer;"';
		} else {
			$onclick = null;
			$style = null;
		}
		foreach ($data->getValues() as $key => $value) {
			if ($data->hasSubdata()) {
				$basey = 0;
				$y2 = $this->_top;
				foreach($data->getSubdata($key) as $k => $v) {
					if (isset($v)) {
						if (!isset($subcolor[$k])) {
							$color = current($this->_datacolors);
							next($this->_datacolors);
							list($r,$g,$b) = $this->_to_rgb($color);
							$subcolor[$k] = imagecolorallocate($this->_chart, $r, $g, $b);
							if ($this->_showlegend) $this->_legend['data'][$name.$k]['color'] = $subcolor[$k];
						}
						list($x1,$y1) = $this->_xy($x,$v+$basey,$dataaxis);
						$x1 = $x1 + $shiftl;
						$x2 = $x1 + $width;
						imagefilledrectangle($this->_chart,$x1,$y1,$x2,$y2,$subcolor[$k]);
						$sl = isset($sublabels[$k]) ? $sublabels[$k] : $k;
						if ($this->isEnabled()) {
							$onclick = 'onclick="'."{$execute}('{$id}','onclick','{$name}','{$sl}','{$key}');".'"';
						}
						$this->_areas .= <<<HTML
<area shape ="rect" coords ="{$x1},{$y1},{$x2},{$y2}" title="{$key} {$datalabel} - {$sl} : $v" {$style} {$onclick} />
HTML;
						$basey += $v;
						$y2 = $y1;
					}
				}
			} else {
				list($x1,$y1) = $this->_xy($x,$value,$dataaxis);
				$x1 = $x1 + $shiftl;
				$x2 = $x1 + $width;
				$y2 = $this->_top;
				if ($this->isEnabled()) {
					$onclick = 'onclick="'."{$execute}('{$id}','onclick','{$name}','{$key}');".'"';
				}

				$this->_areas .= <<<HTML
<area shape ="rect" coords ="{$x1},{$y1},{$x2},{$y2}" title="{$key} {$datalabel}: $value" {$style} {$onclick}/>
HTML;
				imagefilledrectangle($this->_chart,$x1,$y1,$x2,$y2,$datacolor);
				if ($this->_shading > 0)
					imagefilledpolygon($this->_chart, array($x1, $y1,
                                                       $x1 + $this->_shading, $y1 - $this->_shading,
                                                       $x2 + $this->_shading, $y1 - $this->_shading,
                                                       $x2 + $this->_shading, $y2 - $this->_shading,
                                                       $x2, $y2,
                                                       $x2, $y1),
                                           6, $shadowcolor);
				if ($data->valuesVisibles()) {
					$w = $this->_center($value,$vfont,$x2-$x1);
					imagestring($this->_chart,$vfont,$x1+$w+$this->_shading,$y1-$h-2-$this->_shading,utf8_decode($value),$datacolor);
				}
			}
			$x++;
		}
	}


/**
 * Plot a pie Chart
 */
	private function _pieChart($data)
	{
		// Draws any not empty observation
		if ($data->getCount() == 0) return ;

		$name = $data->getName();

 		$color = $this->_fgcolor;
		list($r,$g,$b) = $this->_to_rgb($color);
		$fgcolor = imagecolorallocate($this->_chart, $r,$g,$b);

		$width = ($this->_right - $this->_left);
		$height = ($this->_top - $this->_bottom);

		$xcenter = round($this->_left + ($width / 2),0);
		$ycenter = round($this->_bottom + ($height / 2),0);

		// 3d projection
		$diameter = min($width,$height);

		$width = $diameter;
		$height = ($this->_shading == 0) ? $diameter : ($diameter / 2);

		$startangle = 360;
		$endangle = 0;
		$vfont = $data->getValuesFont();
		$step = 360 / array_sum($data->getValues()) ;
		$datacolor = array();
		$order = 0;
		foreach ($data->getValues() as $key => $value) {
			if ($value != null) {
				$color = current($this->_datacolors);
				next($this->_datacolors);
				list($r,$g,$b) = $this->_to_rgb($color);
				$datacolor[$key] = imagecolorallocate($this->_chart, $r, $g, $b);
				if ($this->_showlegend) $this->_legend['data'][$name.$order]['color'] = $datacolor[$key];
				$order++;
				$endangle = $startangle - ($value * $step);
				if ($this->_shading > 0) {
					list($dr,$dg,$db) = $this->_dark($r,$g,$b);
					$shadowcolor = imagecolorallocate($this->_chart, $dr, $dg, $db);
					for ($i = $this->_shading; $i > 0; $i--) {
						imagefilledarc($this->_chart, $xcenter, $ycenter + $i, $width, $height, $endangle, $startangle, $shadowcolor, IMG_ARC_PIE);
					}
				}
				$startangle = $endangle;
			}
		}
		$startangle = 360;
		if ($this->isEnabled()) {
			if (isset($action_data['ajax']) and $action_data['ajax'] == 1) {
				$execute = 'p4a_event_execute_ajax';
			} else {
				$execute = 'p4a_event_execute';
			}
			$id = $this->getId();
			$style = 'style="cursor:pointer;"';
		} else {
			$onclick = null;
			$style = null;
		}
		foreach ($data->getValues() as $key => $value) {
			if ($value != null) {
				$endangle = $startangle - ($value * $step);
				imagefilledarc($this->_chart,$xcenter,$ycenter,$width,$height,$endangle,$startangle,$datacolor[$key],IMG_ARC_PIE );
				$coords = "{$xcenter},{$ycenter}";
				$wangle = $startangle;
				$polystep = ($startangle - $endangle)/20;
				while ($wangle >= $endangle) {
					$x1 = round($xcenter + (cos(deg2rad(360 - $wangle)) * $width / 2 ),0);
					$y1 = round($ycenter - (sin(deg2rad(360 - $wangle)) * $height / 2),0);
					$coords .= ",{$x1},{$y1}";
					$wangle -= $polystep;
				}
				if ($this->isEnabled()) {
					$onclick = 'onclick="'."{$execute}('{$id}','onclick','{$name}','{$key}');".'"';
				}
				$this->_areas .= <<<HTML
<area shape="poly" coords="{$coords}" title="{$key}: {$value}" {$style} {$onclick} />
HTML;
				if ($data->valuesVisibles()) {
					$centerangle = deg2rad(360 - ($endangle + (($startangle - $endangle)/2)));
					$x1 = $xcenter + (cos($centerangle) * ($width * 0.4));
					$y1 = $ycenter - (sin($centerangle) * ($height * 0.4));
					$x2 = $xcenter + (cos($centerangle) * ($width * 0.7));
					$y2 = $ycenter - (sin($centerangle) * ($height * 0.7));
					imageline($this->_chart,$x1,$y1,$x2,$y2,$fgcolor);
					$x1 = $x2; $y1 = $y2;
					$x2 += ($x1 > $xcenter)? 10 : -10;
					imageline($this->_chart,$x1,$y1,$x2,$y2,$fgcolor);
					$text = "{$key} : {$value}";
					$x2 += ($x1 > $xcenter) ? 2 : - (2 + $this->_w($text,$vfont));
					$y2 -= ($this->_h($text,$vfont)/2);
					imagestring($this->_chart,$vfont,$x2,$y2,utf8_decode($text),$fgcolor);
				}
				$startangle = $endangle;
			}
		}
	}

/**
 * Sets a working dir and path used when a IE browser is
 * detected and the chart image must be genereted in
 * the filesystem and linked with href.
 *
 * Others browser support url-data and no files are
 * generated.
 *
 * @param string $dir
 * @param string $path
 * @return unknown
 */
	public function setWorkingDir($dir,$path = P4A_UPLOADS_PATH)
	{
		$this->_working_dir = $dir;
		$this->_working_path = $path;
		return $this;
	}

/**
 * Sets the background color of the chart
 *
 * @param unknown_type $color
 * @return unknown
 */
	public function setBackgroundColor($color = P4A_THEME_BG)
	{
		$this->_bgcolor = $this->_to_rgb($color);
		return $this;
	}

/**
 * Sets the ForeGround color of the chart
 *
 * @param unknown_type $color
 * @return unknown
 */
	public function setForegroundColor($color = P4A_THEME_FG)
	{
		$this->_fgcolor = $this->_to_rgb($color);
		return $this;
	}

/**
 * Sets a new map of colors.
 *
 * This map will be used to assing automatically
 * the colors to each data representation
 *
 * The colors can be defined as HTML code (eg #332AC6) or
 * and array with the r,g,b values (eg array(45,18,128))
 * @param array  $colors
 * @return unknown
 */
	public function setColors($colors = null)
	{
		if (is_array($colors))
			$this->_datacolors = $colors;
		else
			$this->_datacolors = $this->_initcolors;
		return $this;
	}

/**
 * Sets the shadow size
 * image
 *
 * @param number $shadow
 */
	public function setShadow($shadowsize = 10)
	{
		$this->_shading = $shadowsize;
		return $this;
	}

/**
 * Add a new serie and return the representation order
 *
 * @param ChartSerie $serie
 */
	public function addData($data)
	{
		if (is_a($data,'ChartData')) {
			$name = $data->getName();
			$this->_data[$name] =& $data;
		} else {
			$name = 's'.count($this->data);
			$wdata = new ChartData($name,$data);
			$this->_data[$name] =& $wdata;
		}
		return $this;
	}

/**
 * Sets a source and save the data to fill
 * with this source
 */
	public function setSource(&$data_source, $data)
	{
		unset($this->_datasource);
		$this->_datasource =& $data_source;
		$this->_edata = array();
		if (is_array($data)) {
			foreach ($data as $d) {
				$this->_edata[] = $d->getName();
				$this->addData($d);
			}
		} else {
			$this->_edata[] = $data->getName();
			$this->addData($data);
		}
		$this->_loaded = false;
		return $this;
	}

/**
 * Loads datas from a datasource
 *
 */
	public function load()
	{
		$this->_loaded = true;
		if ( $this->_datasource == null ) return $this;

		$rows = $this->_datasource->getAll();
		$values = array();
		foreach ($rows as $row) {
			foreach ($this->_edata as $dataname) {
				if (isset($this->_data[$dataname])) {
					$labelcol = $this->_data[$dataname]->getSourceLabelColumn();
					$datacol = $this->_data[$dataname]->getSourceDataColumn();
					$item = $row[$labelcol];
					$values[$dataname][$item] = $row[$datacol];
				}
			}
		}
		foreach ($this->_edata as $dataname) {
			if (isset($this->_data[$dataname]) and isset($values[$dataname])) {
				$this->_data[$dataname]->addValue($values[$dataname]);
			}
		}
		return $this;

	}

/**
 * Sets the title font
 *
 * @param integer $font
 * @return unknown
 */
	public function setTitleFont($font = 5)
	{
		$this->_titlefont = $font;
		return $this;
	}

/**
 * Show and hide legend
 */
	public function showLegend($show = true)
	{
		$this->_showlegend = ($show == true);
		return $this;
	}
	public function hideLegend()
	{
		$this->_showlegend = false;
		return $this;
	}

/**
 * The legend's position can be Up, Down, Right.. and combined
 *
 * @param string $position
 * @return unknown
 */
	public function setLegendPos($vertical = self::LEGEND_DOWN, $horizontal = self::LEGEND_RIGHT)
	{
		if (in_array($vertical.$horizontal,$this->_correctpos)) {
			$this->_legendpos[0] = $vertical;
			$this->_legendpos[1] = $horizontal;
		}
		return $this;
	}
/**
 * Sets the legend font
 *
 * @param integer $font
 * @return unknown
 */
	public function setLegendFont($font = 2)
	{
		$this->_legendt = $font;
		return $this;
	}

}

/**
 * ChartData Class
 *
 * ChartData represents a set of tabular data that
 * can be draw in a Chart Widget
 *
 * How the data is draw can be set using Chart method addData
 */

class ChartData
{
	private $_name = null;

/**
 * name of the serie
 */
	private $_label;
	private $_sublabels = array();

/**
 * Values
 *
 * @var unknown_type
 */
	private $_values = array();
	private $_subdata = array();

	private $_color = null;
	private $_type = Chart::LINE;
	private $_axis = Chart::AXIS_LEFT;

	private $_showvalues = false;
	private $_valuesfont = 3;
	private $_hassubdata = false;

	private $_itemlabels = array();
	private $_sort = false;

	private $_regression = false;
	private $_regressioncolor = P4A_THEME_FG;

/**
 * Data source to fill this ChartData
 */
	private $_datasource = null;
	private $_loaded = false;

/**
 * Private data columns used to get the values
 */
	private $_datacol = null;
	private $_datalabel = null;
	private $_subdatalabel = null;

/**
 * Builds a serie with the given name
 *
 * @param string $name
 * @return unknown
 */
	public function __construct($name,$values=null)
	{
		$this->_name = $name;
		if (is_array($values)) $this->addValue($values);
		return $this;
	}

/**
 * Gets the data set name
 */
	public function getName()
	{
		return $this->_name;
	}

/**
 * Set the label for the observation
 *
 * @param unknown_type $label
 * @return unknown
 */
	public function setLabel($label)
	{
		$this->_label = $label;
		return $this;
	}

/**
 * Returns the label of the serie
 *
 * @return string
 */
	public function getLabel()
	{
		return __($this->_label);
	}

/**
 * Subdata Labels
 */
	public function setSubLabels($sublabels)
	{
		$this->_sublabels = $sublabels;
		return $this;
	}

	public function getSubLabels()
	{
		return $this->_sublabels;
	}

/**
 * Adds a value to the data
 *
 * @param unknown_type $value
 * @return unknown
 */
	private function _addvalue($value,$key = null)
	{
		if (is_array($value)) {
			$total = 0;
			$this->_hassubdata = true;
			foreach ($value as $k => $v) {
				$this->_subdata[$key][$k] = $v;
				$total = $total + $v;
				$this->_sublabels[$k] = $k;
			}
			ksort($this->_subdata[$key]);
			$this->_values[$key] = $total;
		} else {
			$this->_values[$key] = $value;
		}
		$this->_itemlabels[] = $key;

		return $this;
	}

/**
 * Adds a value (or array of values) to the
 * observations data
 * @param unknown_type $values
 */
	public function addValue($values,$key = null)
	{
		if (is_array($values))
			foreach ($values as $k => $v)
				$this->_addvalue($v,$k);
		else
			$this->_addvalue($values,$key);
		return $this;
	}

/**
 * Returns the max value
 *
 * @return number
 */
	public function getMax()
	{
		if (isset($this->_datasource) and (!$this->_loaded)) $this->load();
		if ($this->hasSubdata()) {
			if ($this->getType() == Chart::BAR) {
				$max = count($this->_values) > 0 ? max($this->_values) : 0;
			} else {
				$max = null;
				foreach ($this->_subdata as $k => $sbd) {
					$nm = count($this->_values) > 0 ? max($sbd) : 0;
					if ((strlen($max) == 0) or ($nm > $max)) $max = $nm;
				}
			}
		} else {
			$max = count($this->_values) > 0 ? max($this->_values) : 0;
		}
		return $max;
	}
	public function getLast()
	{
		if (isset($this->_datasource) and (!$this->_loaded)) $this->load();
		return(max(array_keys($this->_values)));
	}

/**
 * Returns the min value
 *
 * @return number
 */
	public function getMin()
	{
		if (isset($this->_datasource) and (!$this->_loaded)) $this->load();
		if ($this->hasSubdata()) {
			if ($this->getType() == Chart::BAR) {
				$min = count($this->_values) > 0 ? min($this->_values) : 0;
			} else {
				$min = null;
				foreach ($this->_subdata as $k => $sbd) {
					$nm = count($this->_values) > 0 ? min($sbd) : 0;
					if ((strlen($min) == 0) or ($nm < $min)) $min = $nm;
				}
			}
		} else {
			$min = count($this->_values) > 0 ? min($this->_values) : 0;
		}
		return $min;
	}
	public function getFirst()
	{
		if (isset($this->_datasource) and (!$this->_loaded)) $this->load();
		return(min( array_keys($this->_values)));
	}

/**
 * Returns the number of items
 *
 * @return int
 */
	public function getCount()
	{
		if (isset($this->_datasource) and (!$this->_loaded)) $this->load();
		return count($this->_values);
	}

/**
 * Gets all the values as an array
 *
 * @return unknown
 */
	public function getValues()
	{
		if (isset($this->_datasource) and (!$this->_loaded)) $this->load();
		if ($this->_sort) ksort($this->_values);
		return $this->_values;
	}

	public function getSubdata($key)
	{
		$subdata = array();
		if (isset($this->_subdata[$key])) {
			$subdata = $this->_subdata[$key];
			ksort($subdata);
		}
		return $subdata;
	}

	public function hasSubdata()
	{
		return $this->_hassubdata;
	}

/**
 * Sets the data color
 */
	public function setColor($color)
	{
		$this->_color = $color;
		return $this;
	}

/**
 * Returns the data color
 */
	public function getColor()
	{
		return $this->_color;
	}

/**
 * Sets the data type representation
 */
	public function setType($type = Chart::LINE)
	{
		if (($type == Chart::DOTS) or ($type == Chart::BAR) or
			($type == Chart::LINE) or ($type == Chart::PIE))
			$this->_type = $type;
		return $this;
	}

/**
 * Returns the data color
 */
	public function getType()
	{
		return $this->_type;
	}

/**
 * Sets the data type representation
 */
	public function setAxis($axis = Chart::AXIS_LEFT)
	{
		if (($axis == Chart::AXIS_LEFT) or ($axis == Chart::AXIS_RIGHT))
			$this->_axis = $axis;
		return $this;
	}

/**
 * Returns the data color
 */
	public function getAxis()
	{
		return $this->_axis;
	}

/**
 * Shows the value label when represented ?
 */
	public function showValues($show = true)
	{
		$this->_showvalues = ($show == true);
		return $this;
	}
	public function hideValues()
	{
		$this->_showvalues = false;
		return $this;
	}
	public function valuesVisibles()
	{
		return $this->_showvalues;
	}

/**
 * Values font
 */
	public function setValuesFont($font)
	{
		$this->_valuesfont = $font;
	}
	public function getValuesFont()
	{
		return $this->_valuesfont;
	}

/**
 * Item labels generated automatically when
 * values are loaded
 *
 * @return unknown
 */
	public function getItemLabels()
	{
		return $this->_itemlabels;
	}

/**
 * Regression management
 */
	public function setRegression($color = PA4_THEME_FG)
	{
		$this->_regression = true;
		$this->_regressioncolor = $color;
		return $this;
	}
	public function unsetRegression()
	{
		$this->_regression = false;
		return $this;
	}
	public function getRegression()
	{
		if ($this->_regression) {
			$allx = array(); $ally = array();
			$values = $this->getValues();
			ksort($values);
			foreach ( $values as $x=>$y) {
				if ($this->hasSubdata()) {
					foreach ($this->getSubdata($x) as $v) {
						$allx[] = $x;
						$ally[] = $v;
					}
				} else {
					$allx[] = $x;
					$ally[] = $y;
				}
			}
			$reg = $this->regressione($allx,$ally);
			return array(reset($allx),reset($reg),end($allx),end($ally));
		} else {
			return false;
		}
	}
	public function getRegressionColor()
	{
		return $this->_regressioncolor;
	}

/**
 * Remove values
 */
	public function reset()
	{
		$this->_values = array();
		return $this;
	}

/**
 * This function has been published in the mariospada.net
 * Read the complete article at http://www.mariospada.net/2008/08/php-calcolare-la-retta-di-regr.html
 *
 * @param unknown_type $X
 * @param unknown_type $Y
 * @return unknown
 */
	private function regressione($X,$Y)
	{
		if (!is_array($X) && !is_array($Y)) return false;
		if (count($X) <> count($Y)) return false;
		if (empty($X) || empty($Y)) return false;

		$regres = array();
		$n = count($X);
		$mx = array_sum($X)/$n; // media delle x
		$my = array_sum($Y)/$n; // media delle y
		$sxy = 0;
		$sxsqr = 0;

		for ($i=0;$i<$n;$i++){
			$sxy += ($X[$i] - $mx) * ($Y[$i] - $my);
			$sxsqr += pow(($X[$i] - $mx),2); // somma degli scarti quadratici medi
		}

		$m = $sxy / $sxsqr; // coefficiente angolare
		$q = $my - $m * $mx; // termine noto

		for ($i=0;$i<$n;$i++){
			$regres[$i] = $m * $X[$i] + $q;
		}
		return $regres;
	}

/**
 * Sets the datasource
 */
	public function setSource(&$data_source)
	{
		unset($this->_datasource);
		$this->_datasource =& $data_source;
		return $this;
	}

/**
 * Data cols can be a single column or an array
 * When using an array it means that there are subdata
 *
 * @param unknown_type $datacol
 */
	public function setSourceDataColumn($datacol)
	{
		$this->_datacol = $datacol;
		return $this;
	}
	public function getSourceDataColumn()
	{
		return $this->_datacol;
	}

/**
 * Data cols can be a single column or an array
 * When using an array it means that there are subdata
 *
 * @param unknown_type $datacol
 */
	public function setSourceSubLabelColumn($subdatalabel)
	{
		$this->_subdatalabel = $subdatalabel;
		return $this;
	}
	public function setSourceLabelColumn($datalabel)
	{
		$this->_datalabel = $datalabel;
		return $this;
	}

	public function getSourceSubLabelColumn()
	{
		return $this->_subdatalabel;
	}
	public function getSourceLabelColumn()
	{
		return $this->_datalabel;
	}

	public function load()
	{
		if (isset($this->_datasource)) {
			$this->_values = array();
			$rows = $this->_datasource->getAll();
			$previous = null;
			$subdata = array();
			$sublabel = array();
			foreach ($rows as $row) {
				$data = $row[$this->_datacol];
				$label = $row[$this->_datalabel];
				if (strlen($this->_subdatalabel) == 0) {
					$this->addValue($data,$label);
				} else {
					$sublabel[$row[$this->_subdatalabel]] = $row[$this->_subdatalabel];
					if (($previous == null) or ($previous != $data)) {
/*
						if ($previous != null) {
							$this->addValue($subdata);
						}
						$subdata = array();
*/
						$previous = $data;
					}
					$subdata[$data][] = $row[$sublabel];
				}
			}
			if (strlen($this->_subdatalabel) == 0) {
				$this->addValue($subdata,$sublabel);
			}
			$this->_loaded = true;
		}
		return $this;
	}

}



class ChartAxis
{
	const TYPE_X = 'X';
	const TYPE_Y = 'Y';
	const MODE_DISCRETE = 1;
	const MODE_CONTINUUS = 2;

	// Tick labels is an array with two components: value and label
	private $_ticklabels = array();
	private $_type = self::TYPE_X;
	private $_labelsize = 0;
	private $_showgrid = false;
	private $_color = null;
	private $_visible = true;
	private $_label = null; // the axis label
	private $_labelfont = 4;
	private $_ticklabelfont = 3;
	private $_mode;
	private $_max = null;
	private $_min = 0;

	// array lines can be set indicating:
	// value, label, color
	private $_lines = array();

/**
 * The axis can be a item axis (X) or values axis (Y)
 *
 * by default a X axis is discrete and a Y continuus
 *
 * @param unknown_type $type
 * @return unknown
 */
	public function __construct($type)
	{
		if (($type == self::TYPE_X) or ($type == self::TYPE_Y)) {
			$this->_type = $type;
			if ($type == self::TYPE_X) {
				$this->setDiscrete();
			} else {
				$this->setContinuus();
			}
			$this->setMin(0);
		}
		return $this;
	}

/**
 * Gets the axis type
 */
	public function getType()
	{
		return $this->_type;
	}

/**
 * Axis unit mode
 */
	public function setDiscrete()
	{
		$this->_mode = self::MODE_DISCRETE;
		return $this;
	}
	public function setContinuus()
	{
		$this->_mode = self::MODE_CONTINUUS;
		return $this;
	}
	public function isDiscrete()
	{
		return ($this->_mode == self::MODE_DISCRETE);
	}

/**
 * The labels that can be show
 *
 * The X value can be a label or the value, but the Y axis labels the label
 * would be the value
 * @param unknown_type $labels
 * @return unknown
 */
	public function setTickLabels($labels)
	{
		if ($this->isDiscrete()) {
			if (is_a($labels,'ChartData')) {
				$this->_ticklabels = $labels->getItemLabels();
			} else {
				$this->_ticklabels = $labels;
			}
		} else {
			foreach ($labels as $v) {
				$this->_ticklabels[$v] = $v;
			}
		}
		$this->setTickLabelSize();
		return $this;
	}

/**
 * Returns the axis labels
 *
 * @return unknown
 */
	public function getTickLabels()
	{
		return $this->_ticklabels;
	}

/**
 * Determines if the axis is labeled
 *
 * @return unknown
 */
	public function labeled()
	{
		return count($this->_ticklabels) != 0;
	}

/**
 * Set a label for this data
 *
 * @param unknown_type $size
 */
	public function setTickLabelSize($size = 15)
	{
		$this->_labelsize = $size;
	}

/**
 * Returns the label of this data
 *
 * @return string
 */
	public function getTickLabelSize()
	{
		return ($this->_labelsize);
	}

/**
 * Tick Label Font
 *
 * @param unknown_type $font
 */
	public function setTickLabelsFont($font)
	{
		$this->_ticklabelfont = $font;
		return $this;
	}
	public function getTickLabelsFont()
	{
		return $this->_ticklabelfont;
	}

	public function setLabel($label)
	{
		$this->_label = $label;
		return $this;
	}

/**
 * Returns the label of the serie
 *
 * @return string
 */
	public function getLabel()
	{
		return __($this->_label);
	}

/**
 * Label Font
 *
 * @param unknown_type $font
 */
	public function setLabelFont($font)
	{
		$this->_labelfont = $font;
		return $this;
	}
	public function getLabelFont()
	{
		return $this->_labelfont;
	}

/**
 * Max value in labels can be the value or the item
 */
	public function getMax()
	{
		if ($this->_max === null) {
			$last = end($this->_ticklabels);
			if ($this->isDiscrete()) {
				$max = key($this->_ticklabels);
			} else {
				$max = current($this->_ticklabels);
			}
		} else {
			$max = $this->_max;
		}
		return $max;
	}
	public function setMax($max = null)
	{
		$this->_max = $max;
		return $this;
	}

	public function getMin()
	{
		if ($this->_min === null) {
			$first = reset($this->_ticklabels);
			if ($this->isDiscrete()) {
				$min = key($this->_ticklabels);
			} else {
				$min = current($this->_ticklabels);
			}
		} else {
			$min = $this->_min;
		}
		return $min;
	}
	public function setMin($min = null)
	{
		$this->_min = $min;
		return $this;
	}

	public function setColor($color)
	{
		$this->_color = $color;
		return $this;
	}

	public function getColor()
	{
		return $this->_color;
	}

/**
 * Shows the value label when represented ?
 */
	public function showGrid($show = true)
	{
		$this->_showgrid = ($show == true);
		return $this;
	}
	public function hideGrid()
	{
		$this->_showgrid = false;
		return $this;
	}
	public function gridVisible()
	{
		return $this->_showgrid;
	}

/**
 * Axis visibility
 */
	public function setVisible($visible = true)
	{
		$this->_visible = $visible;
		return $this;
	}
	public function setInvisible()
	{
		$this->_visible = false;
		return $this;
	}
	public function isVisible()
	{
		return $this->_visible;
	}

/**
 * Axis extra lines
 */
	public function addLine($value, $label = null, $color = null)
	{
		$this->_lines[] = array($value, $label, $color);
		return $this;
	}

	public function getLines()
	{
		return $this->_lines;
	}
}
?>
