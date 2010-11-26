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
 * Máscara: crearUsuario
 * Tabla:	usuarios
 * Tipo:	Administración
 * Tema:	Gestionar usuarios del sistema
 * <Descripcion: >
 */
class crearUsuario extends P4A_Simple_Edit_Mask
{
    public function __construct()
    {
        $p4a = p4a::singleton();
        parent::__construct($this->origenDeDatos());

        $this->setTitle("Módulo: Administración de Usuarios");

        $this->camposObligatorios();
        $this->propiedadesCampos();

        $this->display("menu", $p4a->menu)
            ->setFocus($this->fields->usuario);
    }
    public function origenDeDatos()
    {
        $source = $this->build("p4a_db_source", "source")
/*
        	->setSchema("cc1")
*/
            ->setTable("usuarios")
            ->setWhere("activo = 'true'")
/*
            ->addOrder("apellido","ASC")
            ->addOrder("nombre")
*/
            ->load();
		return $source;
    }
    private function camposObligatorios()
    {
        /**
         * Campos Obligatorios del Formulario
         */
        $this->setRequiredField("usuario");
        $this->setRequiredField("clave");
        $this->setRequiredField("default_mask");
        $this->setRequiredField("nivel_acceso");

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
/*
        $this->table->setVisibleCols(array("cedula","apellido","nombre"));
        $this->table->setVisibleCols(array("menu","nombre","posicion", "nivel_acceso", "basica"));
*/
/*
        $this->table->cols->fregistro->setVisible(false);
        $this->table->cols->activo->setVisible(false);
*/

        /**
         * Propiedades de los Campos del Formulario
         *
         * el campo <fregistro> lo desactivamos
         * el campo <activo> lo desactivamos
         * al campo <cedula> lo validamos contra ...
         */
        $this->fields->fregistro->setVisible(false);
        $this->fields->activo->setVisible(false);
        $this->fields->usr->setVisible(false);
        $this->fields->ip->setVisible(false);
        $this->fields->id->setVisible(false);
        $this->fields->clave->setType("password");
        return;
    }
    public function saveRow()
	{
		try
		{
			$id = $this->fields->id->getSQLNewValue();
			$tabla20_campo2 = $this->fields->tabla20_campo2->getSQLNewValue();
			$usuario = $this->fields->usuario->getSQLNewValue();
			$clave = $this->fields->clave->getSQLNewValue();
			$default_mask = $this->fields->default_mask->getSQLNewValue();
			$nivel_acceso = $this->fields->nivel_acceso->getSQLNewValue();

			$campos = "tabla20_campo2, usuario, clave, default_mask, nivel_acceso, usr, ip";
			$valores = "'$tabla20_campo2', LOWER('$usuario'), '$clave', '$default_mask', $nivel_acceso, $_SESSION[id], '$_SERVER[REMOTE_ADDR]'";

			$query = "INSERT INTO cc1.usuarios ($campos) VALUES ($valores)";
			$this->info("query: $query -- ID: $id");
			P4A_DB::singleton()->query($query);
			if ($id)
				P4A_DB::singleton()->query("UPDATE cc1.usuarios SET activo = 'false' WHERE id = $id");
			/** verificar que se quede seleccionado el elemento que acabo de insertar */
		} catch (Exception $e)
		{
			  $this->error('mensaje:'.$e->getMessage().' codigo:'.$e->getCode());
			  $this->error("Imposible guardar");
		}
	}
}
