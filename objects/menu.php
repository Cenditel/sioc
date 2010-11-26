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
 * Máscara: Menu
 * Tabla:	menu
 * Tipo:	Administración
 * Tema:	Crear elementos del menu del sistema
 * <Descripcion: >
 */
class crearMenu extends P4A_Base_Mask
{
	public $source = null;
	protected $default_value = null;

	public function __construct()
	{
		parent::__construct();

		$this->setTitle("Configuración del Menú");

		$this->setSource($this->origenDeDatos());
		$this->firstRow();

		/* construir tabla que muestra los datos */
		$this->build("p4a_table","table")
			  ->setSource($this->source)
			  ->setVisibleCols(array("menu","mascara","posicion", "nivel_acceso"))
			  ->setWidth(700);

        $this->camposObligatorios();
        $this->propiedadesCampos();

		$this->build("p4a_fieldset","fls")
			->setLabel("Detalles")
			->setWidth(600)
            ->anchor($this->fields->fregistro)
            ->anchor($this->fields->id)
			->anchorLeft($this->fields->id_padre)
			->anchor($this->fields->mascara)
			->anchorLeft($this->fields->etiqueta)
			->anchor($this->fields->posicion)
			->anchorLeft($this->fields->visible)
			->anchorLeft($this->fields->nivel_acceso)
			->anchor($this->fields->accion);

/*
            $this->build('p4a_frame',"frame");
*/
		$this->frame
			->setWidth(600)
                  ->anchor($this->table)
                  ->anchor($this->fls);

		$this
			->build("p4a_simple_toolbar","toolbar")
			->setMask($this);

		$this
			->display("menu",P4A::singleton()->menu)
			->display("top",$this->toolbar);
	}

	public function saveRow()
	{
		$this->fields->fregistro->setNewValue(date('Y-m-d'));
		$this->fields->activo->setNewValue(true);
		$this->fields->usr->setNewValue($_SESSION["id"]);
		$this->fields->ip->setNewValue(getenv("REMOTE_ADDR"));

		$etiqueta = trim($this->fields->etiqueta->getNewValue());
		if ($etiqueta == "") {
			$etiqueta = $this->fields->mascara->getNewValue();
			$etiqueta = $this->menuLabel($etiqueta);
			$this->fields->etiqueta->setValue($etiqueta);
		}
		$priId = $this->fields->id_padre->getNewValue();
		if (empty($priId)) {
			$this->fields->menu->setValue($etiqueta);
		} else {
			$myrow = $this->source->getPkRow($priId);
			$this->fields->mascara_padre->setNewValue($myrow['mascara']);
			$this->fields->posicion_padre->setNewValue($myrow['posicion']);
			$this->fields->menu->setNewValue($myrow['etiqueta'] . "->" . $etiqueta);
		}
		parent::saveRow();
	}

	public function menuLabel($value)
	{
	/**
	 * Función que reemplaza los guiones bajos por espacios en blanco
	 * para la etiqueta del campo menú, donde se hace referencia a una
	 * opción de menú padre
	*/
		$value = str_replace("_"," ",$value);
		$value = ucwords($value);
		return $value;
	}

	public function origenDeDatos()
	{
		$source = $this->build("p4a_db_source","source")
// 			->setSchema("cc1")
			->setTable("menu")
			->setWhere("activo = 'true'")
            ->addOrder("menu")
			->setPk("id")
			->load();

		return $source;
	}

	private function camposObligatorios()
    {
        /**
         * Campos Obligatorios del Formulario
         */
        $this->setRequiredField("mascara");
        $this->setRequiredField("etiqueta");
/*
        $this->setRequiredField("id_padre");
*/
        $this->setRequiredField("posicion");
        $this->setRequiredField("visible");
        $this->setRequiredField("nivel_acceso");
/*
        $this->setRequiredField("accion");
*/

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

/*
        $this->toolbar->buttons->delete->disable();
*/
/*
        $this->toolbar->buttons->exit->setVisible(false);
        $this->toolbar->buttons->cancel->setVisible(false);
        $this->toolbar->buttons->print->disable();
*/

        /**
         * Propiedades de las Columas de la Tabla
         *
         * la columna <fregistro> la ocultamos
         * la columna <activo> la ocultamos
         */


        /**
         * Propiedades de los Campos del Formulario
         *
         * el campo <fregistro> lo desactivamos
         * el campo <activo> lo desactivamos
         * al campo <cedula> lo validamos contra ...
         */
        $this->fields->fregistro->setVisible(false)->disable();
        $this->fields->activo->setVisible(false);
        $this->fields->usr->setVisible(false);
        $this->fields->ip->setVisible(false);
        $this->fields->id->setVisible(false)->disable();

        $positions = $this->build("p4a_array_source","positions");
		$a = array();
		for($i=1;$i<=100;$i++) {
			  $a[]["value"] = $i;
		}
		print_r($a);
		$positions->load($a);
		$positions->setPk("value");

		// definir un objeto para verificar los niveles de acceso
		$access_levels = $this->build("p4a_array_source","access_levels");
		$a = array();
		for($i=1;$i<=10;$i++) {
			$a[]["value"] = $i;
		}
		$access_levels->load($a);
		$access_levels->setPk("value");

 		$this->build("p4a_db_source","parents")
 			->setSchema("cc1")
			->setTable("menu")
			->setWhere("id_padre IS NULL")
            ->addOrder("menu")
			->addOrder("posicion")
			->addOrder("etiqueta")
			->setPk("id")
			->load();

		$this->fields->id_padre
			->setLabel("Padre")
			->setType("select")
			->allowNull("")
			->setSource($this->parents)
			->setSourceDescriptionField("etiqueta");
		$this->fields->posicion
			->setType("select")
			->setSource($this->positions);
		$this->fields->nivel_acceso
			->setType("select")
			->setSource($this->access_levels);


            /* valores por defecto para algunos campos del formulario */
/*
            $this->fields->nivel_acceso->data_field->setDefaultValue("1");
            $this->fields->visible->data_field->setDefaultValue("true");
            $this->fields->accion->data_field->setDefaultValue("openMask");
*/
	}
}
