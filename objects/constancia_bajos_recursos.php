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
class constancia_bajos_recursos extends P4A_Base_Mask
{
    public function __construct()
    {
        parent::__construct();
        $p4a = p4a::singleton();

        $this->build('P4A_Field',"fldCedula");
        $this->build('P4A_Field',"fldObjeto");
        $this->build('P4A_Button',"btn0");

        $this->fldCedula
            ->setType('text')
            ->setLabel("Cédula")
            ->setWidth(100)
            ->setTooltip("Cédula");

        $this->fldObjeto
        	->setType("textarea")
        	->setLabel("Para fines de")
        	->setHeight(50)
        	->setWidth(400)
        	->setTooltip("Indicar para qué se expide la certificación");

        $this->btn0->setLabel("Consultar")->implement('onClick', $this, "imprimirConsulta0");

        $this->build("p4a_fieldset","fs_bajos_recursos")
            ->setLabel("Constancia de Bajos Recursos")
            ->setWidth(900)
            ->anchorLeft($this->fldCedula)
            ->anchor($this->fldObjeto)
            ->anchorRight($this->btn0);

        $this->frame
            ->anchor($this->fs_bajos_recursos);

        $this
            ->display("menu", $p4a->menu);
    }

    public function imprimirConsulta0($object)
    {
        $ci = $this->fldCedula->getNewValue();
        $ob = $this->fldObjeto->getNewValue();

        if ((!$ci))
        {
            $this->info("Debe ingresar el campo: Cédula");
            return;
        }
        if (!$ob)
        {
        	$this->warning("Debe ingresar el campo: Para fines de");
        	return;
		}

		/**
		* Verificar sí cumple
		*/
		$this->user_data = P4A_DB::singleton()->fetchRow("SELECT situacion_laboral, salario FROM encuestas.censo WHERE cedula = '$ci'");
        $situacion_laboral = $this->user_data['situacion_laboral'];
		$salario = $this->user_data['salario'];
		
		if (($salario > 1) and ($situacion_laboral > 1))
		{
			P4A_Redirect_To_Url("./reportes/constancia_bajos_recursos.php?ci={$ci}&ob={$ob}");
		}else
		{
			$this->error("No cumple con las condiciones para esta constancia. Verificar en la Base de Datos");
		}
    }
}
