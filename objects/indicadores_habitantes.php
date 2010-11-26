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
 * Máscara: indicadores_habitantes
 * Vista:
 * Tema:    Indicadores de ...
 * <Descripcion: Vista de ...>
 */
class indicadores_habitantes extends P4A_Base_Mask
{
    public function __construct()
    {
        $p4a = p4a::singleton();
        parent::__construct();

        $this->setTitle("Gráficos del Censo de Habitantes");

        $this->crearCampos();
        $this->propiedadesCampos();

        $this->build('P4A_fieldset', 'ventanaGrafico')
            ->setLabel("Gráfico")
            ->anchor($this->fld_campos)
            ->anchor($this->boton);
        $this->frame
            ->anchorLeft($this->ventanaGrafico);

        $this->display("menu", $p4a->menu);
    }
    public function crearCampos()
    {
        $this->build('p4a_field',"fld_campos");
        $this->build('p4a_button', "boton");
    }
    public function origenDeDatos()
    {
        $source = $this->build("p4a_db_source", "source")
            //->setSchema("cc1")
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
         * Propiedades de los Campos del Formulario
         *
         * el campo <campos> muestra la lista de opciones ...
         * al campo <boton> ...
         */
        $campos = array();
        $campos[] = array("id" => 1, "desc" => "Sexo");
        $campos[] = array("id" => 2, "desc" => "Rangos de Edad (de 5 años)");
        $campos[] = array("id" => 3, "desc" => "Nivel Educativo");
        $this->build("p4a_array_source", "array_campos")
            ->load($campos)
            ->setPk("id");
        $this->fld_campos
            ->setType("radio")
            ->setLabel("Criterios")
            ->setSource($this->array_campos);

        $this->boton
            ->setLabel('Hacer')
            ->implement("onclick", $this, "abrirVentana");

        return;
    }
    public function abrirVentana($criterio)
    {
        if (!$this->fld_campos->getNewValue())
        {
            $this->warning("Debe seleccionar un campo");
            return;
        }
        $p4a =& p4a::singleton();
        // abrir máscara en ventanaGrafico emergente
        $p4a->openPopup('graficar_habitanes');

        // cargar la consulta antes
        $p4a->masks->graficar_habitanes->consulta();
    }
}
