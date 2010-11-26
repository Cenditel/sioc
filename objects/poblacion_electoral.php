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
class poblacionElectoral extends P4A_Base_Mask
{
    public function __construct()
    {
        parent::__construct();
        $p4a = p4a::singleton();

        $edad = $this->build('P4A_Array_Source',"edad");
        $z1 = array();
        $MinMaxAgno = $this->obtenerMinMaxAgno();
        $inicial = 15;
        $final = $MinMaxAgno[1];

        for ($l1=$inicial; $l1<=$final; $l1++)
        {
              $z1[]["value"] = $l1;
        }
        $edad->load($z1);
        $edad->setPk("value");
        $this->build("p4a_field", "fldEdad")
            ->setType("select")
            ->setSource($this->edad)
            ->setLabel("Edad")
            ->allowNull("Seleccione");

        $this->build('P4A_Field',"fldFecha");
        $this->fldFecha
        	->setType('date')
        	->setLabel("Fecha")
        	->setWidth(80)
        	->setTooltip("Fecha límite para la lista");


        $this->build('P4A_Button',"btn0");
        $this->btn0->setLabel("Consultar")->implement('onClick', $this, "imprimirConsulta0");

        $this->build("p4a_fieldset","fs_edad")
            ->setLabel("Población electoral")
            ->setWidth(900)
            ->anchor($this->fldEdad)
            ->anchorLeft($this->fldFecha)
            ->anchorRight($this->btn0)
            ;

        $this->frame
            ->anchor($this->fs_edad)
            ;

        $this
            ->display("menu", $p4a->menu);
    }

    public function obtenerMinMaxAgno()
    {
        $this->user_data = P4A_DB::singleton()->fetchRow("SELECT MIN(edad) AS minimo, MAX(edad) AS maximo FROM encuestas.habitantes");
        $min = $this->user_data['minimo'];
        $max = $this->user_data['maximo'];

        $min_max_agno = array($min, $max);

        return $min_max_agno;
    }

    public function imprimirConsulta0($object)
    {
        $edad = $this->fldEdad->getNewValue();

        if ((!$edad))
        {
            $this->info("Debe seleccionar la Edad");
            return;
        }

        P4A_Redirect_To_Url("./reportes/poblacion_electoral.php?e={$edad}");
    }
}
