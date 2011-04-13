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
 * Máscara: cc1_11
 * Tabla: tabla21
 * Tipo:	Básica
 * Tema: Salarios
 * <Descripcion: tabla básica para registrar los salarios aproximados
 * que puede recibir una persona>
 */
class tabla21 extends P4A_Simple_Edit_Mask
{
    public function __construct()
    {
        $p4a = p4a::singleton();
        parent::__construct($this->origenDeDatos());

        $this->setTitle("Salarios");

        $this->toolbar->buttons->delete->disable();
        $this->toolbar->buttons->exit->setVisible(false);

        $this->camposObligatorios();
        $this->propiedadesCampos();

        $this->display("menu", $p4a->menu)
            ->setFocus($this->fields->tabla21_campo2);
    }
    public function origenDeDatos()
    {
        $source = $this->build("p4a_db_source", "source")
        	//->setSchema("cc1")
            ->setTable("tabla21")
            ->setWhere("activo = 'true'")
            ->addOrder("tabla21_campo2","ASC")
            ->load();
		return $source;
    }
    private function camposObligatorios()
    {
        /**
         * Campos Obligatorios del Formulario
         */
        $this->setRequiredField("tabla21_campo2");

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
         * la columna <tabla21_campo1> la ocultamos
         * la columna <fregistro> la ocultamos
         * la columna <activo> la ocultamos
         */
        $this->table->cols->tabla21_campo1->setVisible(false);
        $this->table->cols->fregistro->setVisible(false);
        $this->table->cols->activo->setVisible(false);
        $this->table->cols->tabla21_campo2->setLabel("Nombre");
        $this->table->cols->tabla21_campo3
        	->setLabel("Visible")->setWidth(50);

        /**
         * Propiedades de los Campos del Formulario
         *
         * el campo <tabla21_campo1> lo desactivamos
         * el campo <fregistro> lo desactivamos
         * el campo <activo> lo desactivamos
         * al campo <tabla21_campo2> lo validamos contra ...
         */
        $this->fields->fregistro
        	->setVisible(false);
        $this->fields->activo->setVisible(false);

        $this->fields->tabla21_campo1
        	->disable()->setVisible(false);
        $this->fields->tabla21_campo2
        	->setLabel("Descripción")
        	->setWidth(200)
        	->setProperty('maxlength',"50")
        	->addValidator(new P4A_Validate_Alpha(true),true);
        $this->fields->tabla21_campo3->setLabel("Visible");

        $this->fields->tabla21_campo2->label->setWidth(120);
        $this->fields->tabla21_campo3->label->setWidth(120);

        return;
    }
    public function saveRow()
	{
/* utilizar requireConfirmation para avisar que el estatus de Activo
 * va a cambiar, cuando se la operación es Modificar
 */
		try
		{
			$p4a_db = P4A_DB::singleton();

            $tabla21_campo1 = $this->fields->tabla21_campo1->getSQLValue();
            $tabla21_campo2 = $this->fields->tabla21_campo2->getSQLNewValue();
            $tabla21_campo3 = $this->fields->tabla21_campo3->getSQLNewValue();

            $existe = "SELECT count(*) FROM cc1.tabla21 WHERE tabla21_campo3 = true AND activo = '$tabla21_campo3' AND tabla21_campo2 ILIKE('$tabla21_campo2'); ";
            $this->user_data = $p4a_db->fetchRow($existe);
            if ($this->user_data['count'] >= 1) {
                $this->warning("El registro ya existe");
                $this->setFocus($this->fields->tabla21_campo2);
                return;
            }

            $query = "INSERT INTO cc1.tabla21 (tabla21_campo2, tabla21_campo3) VALUES (INITCAP('$tabla21_campo2'), '$tabla21_campo3')";
            $p4a_db->query($query);
            if ($tabla21_campo1)
                P4A_DB::singleton()->query("UPDATE cc1.tabla21 SET activo = 'false' WHERE tabla21_campo1 = $tabla21_campo1");
			/** verificar que se quede seleccionado el elemento que acabo de insertar */
		} catch (Exception $e)
		{
			  $this->error('mensaje:'.$e->getMessage().' codigo:'.$e->getCode());
			  $this->error("Imposible guardar");
		}
	}

}
