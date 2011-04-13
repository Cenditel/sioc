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
 * Máscara: UsuariosMenu
 * Tabla:	usuario_menu
 * Tipo:	Administración
 * Tema:	Gestionar privilegios de usuarios por menú
 * <Descripcion: >
 */
class UsuariosMenu extends P4A_Base_Mask
{
	public $toolbar = null;

	public $source = null;
	protected $default_value = null;

	public function __construct()
	{
		parent::__construct();

		// Toolbar
		$this->build("p4a_full_toolbar", "toolbar")
			->setMask($this);

		$this->setTitle("Accesos de Usuarios a Menu");

		$this->camposObligatorios();
        $this->propiedadesCampos();

        /* definir origen de datos */
		$this->build("p4a_db_source","source")
			->setTable("usuarios")
			->load()
            ->firstRow();

		$this->setSource($this->source);
/*
		$this->setSource($this->origenDeDatos());
*/

		$this->build("p4a_field","lstUsuarios")
			  ->setType("select")
			  ->setSource($this->source)
			  ->setSourceDescriptionfield("usuario")
			  ->setsourceValueField("id")
			  ->setLabel("Usuario")
			  ->allowNull("Seleccione")
			  ->setWidth(300);

		$this->intercept($this->lstUsuarios, 'onChange', 'cargar_lista');

		$this->build("p4a_db_source","opcMenu")
			->setTable("menu")
//                 ->setFields(array("*" => "*", "(menu || ' ' || basica)" => "menu_compuesto"))
			->setWhere(" visible = 't' AND activo = 't'")
			->addOrder("menu")
			->load()
			->firstRow();

		$p4a =& p4a::singleton();

		$this->build("p4a_fieldset","flsUsers")
			  ->setLabel("Usuarios del Sistema")
			  ->anchor($this->lstUsuarios);

		$frm = $this->build("p4a_fieldset","flsMenu")
			->setLabel("Opciones de Menu del Sistema");

		for ($i = 1; $i <= $this->opcMenu->getNumRows(); $i++) {

			$mostrar = $this->opcMenu->fields->menu->getValue();
			$this->build("p4a_field","chk{$i}")
				->setType("checkbox")
				->setsource($this->opcMenu)
				->setSourceDescriptionField("menu")
				->setSourceValueField("id")
//                       ->setLabel($this->opcMenu->fields->menu->getValue())
				->setLabel($mostrar)
				->setValue($this->opcMenu->fields->id->getValue());

			$ancho = "\$this->chk{$i}->label->setWidth(350);";
			eval($ancho);
			$str = "\$frm->anchor(\$this->chk{$i});";
			eval($str);

			$this->opcMenu->nextRow();
		}

		// botón para grabar los cambios
		$this->build("p4a_button","btnGrabar")
			  ->setLabel("Grabar los Accesos");
		$this->btnGrabar->addAction("onClick");
		$this->intercept($this->btnGrabar, 'onClick', 'grabar_lista');

		$this->frame
			  ->anchor($this->flsUsers)
			  ->anchor($this->flsMenu)
			  ->anchorLeft($this->btnGrabar);

		$this->display("menu",P4A::singleton()->menu);
		$this->display("top",$this->toolbar);
      }

	private function origenDeDatos()
    {
    	$this->info("llega");return;
/*
    	$source = $this->build("p4a_db_source","source")
			->setTable("users")
			->load()
            ->firstRow();
		return $source;
*/
    }
    private function camposObligatorios()
    {
        /**
         * Campos Obligatorios del Formulario
         */
/*
        $this->setRequiredField("usuario");
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

        $this->toolbar->buttons->delete->disable();
        $this->toolbar->buttons->exit->setVisible(false);
        $this->toolbar->buttons->cancel->setVisible(false);
        $this->toolbar->buttons->print->disable();

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
    }

	public function cargar_lista()
	{
     	$idUsuario = $this->lstUsuarios->getNewValue();

		$this->build("p4a_db_source","opcMenuChk")
			  ->setTable("usuario_menu")
			  ->setWhere("usuario_menu.id_usuario = $idUsuario")
			  ->addOrder("id_menu")
			  ->load();

		for ($j = 1; $j <= $this->opcMenu->getNumRows(); $j++) {
			  $str = "\$val = \$this->chk{$j}->getValue();";
			  eval($str);
			  $this->opcMenuChk->firstRow();
			  $esta = 0;
			  for ($k=1; $k <= $this->opcMenuChk->getNumRows(); $k++) {
					if ( $this->opcMenuChk->fields->id_menu->getValue() == $val ) {
						  $esta = 1;
						  $str="\$this->chk{$j}->setNewValue(\"1\");";
						  eval($str);
						  break;
					}
			  $this->opcMenuChk->nextRow();
			  }

			  if ($esta == 0 ) {
					$str="\$this->chk{$j}->setNewValue(\"0\");";
					eval($str);
			  }
		}
    }

    public function grabar_lista()
    {
    	$this->build("P4A_DB_Source","qry");

		$idUsuario = $this->lstUsuarios->getNewValue();

		$qry = "DELETE FROM cc1.usuario_menu WHERE id_usuario = $idUsuario;";
		$this->qry->setQuery($qry)->load();

		for ($k=1; $k <= $this->opcMenu->getNumRows(); $k++) {
			  //saca el valor del ID del acceso
			  $str = "\$val = \$this->chk{$k}->getValue();";
			  eval($str);

			  //saca si esta marcado o no
			  $str = "\$val2 = \$this->chk{$k}->getNewValue();";
			  eval($str);

			  if ( $val2 == 1) {
					$qry = "INSERT INTO cc1.usuario_menu (id_usuario, id_menu) VALUES ($idUsuario, $val);";
					$this->qry->setQuery($qry)->load();
			  }
		}

        $this->info("Cambios Guardados");
      }
}
?>
