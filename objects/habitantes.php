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
 * Máscara: habitantes
 * Tabla:   habitantes
 * Tema:    Habitantes del sector
 * <Descripcion: tabla de pruebas con los datos del censo existente>
 */
class habitantes_prueba extends P4A_Simple_Edit_Mask
{
    public function __construct()
    {
        $p4a = p4a::singleton();
        parent::__construct($this->origenDeDatos());

        $this->setTitle("Censo de Habitantes");

        $this->toolbar->buttons->delete->disable();
        $this->toolbar->buttons->exit->setVisible(false);

        $this->camposObligatorios();
        $this->propiedadesCampos();

        $this->build('P4A_fieldset', 'ventanaMapa')
            ->setLabel("Mapa");
        $this->frame->anchorLeft($this->ventanaMapa);

        $this->build('p4a_button', 'boton')
            ->setLabel('Hacer')
            ->implement("onclick", $this, "abrirVentana");
        $this->build('P4A_fieldset', 'ventanaGrafico')
            ->setLabel("Gráfico")
            ->anchor($this->boton);
        $this->frame->anchorLeft($this->ventanaGrafico);

        $this->display("menu", $p4a->menu)
            ->setFocus($this->fields->apellidos);
    }
    public function origenDeDatos()
    {
        $source = $this->build("p4a_db_source", "source")
/*
            ->setDSN('pgsql://carbonara:carbonara@localhost/apoya_comunidad')
*/
/*
            ->setSchema("cc1")
*/
            ->setTable("habitantes")
            ->addOrder("apellidos","ASC")
            ->addOrder("nombres")
            ->load();
        return $source;
    }
    private function camposObligatorios()
    {
        /**
         * Campos Obligatorios del Formulario
         */
        $this->setRequiredField("apellidos");

        return;
    }
    private function propiedadesCampos()
    {
        /**
         * Propiedades de las Columas de la Tabla
         *
         * la columna <id> la ocultamos
         */
        $this->table->setWidth(1000);
        $this->table->cols->id->setVisible(false);
        $this->table->cols->cedula->setLabel("C.I.");
        $this->table->cols->instruccion->setLabel("Grado de Instrucción");
        $this->table->cols->profesion->setLabel("Ocupación");

        /**
         * Propiedades de los Campos del Formulario
         *
         * el campo <id> lo desactivamos
         * al campo <apellidos> lo validamos contra ...
         */
        $this->fields->id
            ->disable()->setVisible(false);
        $this->fields->apellidos
            ->setLabel("Apellido(s)")
            ->setWidth(150)
            ->setProperty('maxlength',"50")
/*
            ->addValidator(new P4A_Validate_StringLength(0,25),true)
*/
            ->addValidator(new P4A_Validate_Alpha(true),true);

        $this->fields->apellidos->label->setWidth(120);

        return;
    }
    public function saveRow()
    {
        try
        {
            parent::saveRow();
            if (parent::saveRow()) {
                $this->info("Registro Guardado");
            } else {
                $this->error("Imposible guardar");
            }
            /** verificar que se quede seleccionado el elemento que acabo de insertar */
        } catch (Exception $e)
        {
              $this->error('mensaje:'.$e->getMessage().' codigo:'.$e->getCode());
              $this->error("Imposible guardar");
        }
    }
    public function abrirVentana()
    {
        $p4a =& p4a::singleton();
        // abrir máscara en ventanaGrafico emergente
        $p4a->openPopup('graficar_habitanes');

        // cargar la consulta antes
        $p4a->masks->graficar_habitanes->consulta();
    }
}
