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
 * Máscara: cc1_20
 * Tabla:	tabla20
 * Tipo:	Detalle
 * Tema:	Habitantes
 * <Descripcion: tabla detalle para los habitantes del sector>
 */
class tabla20 extends P4A_Base_Mask
{
    public $toolbar = null;
    public $table = null;
    public $fs_details = null;

    public $source = null;

    public function __construct()
    {
        parent::__construct();
        $p4a = p4a::singleton();

        $this->setTitle("Censo de Habitantes");

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
//             ->setSchema("cc1")
            ->setTable("tabla20")
            ->setFields(array("tabla20.*" => "*","extract(years from age(now(),tabla20_campo6))"=>"edad", "tabla13.tabla13_campo2" => "tabla13_campo2", "tabla2.tabla2_campo2" => "tabla2_campo2"))
            ->addJoinLeft("tabla13", "tabla13.tabla13_campo1 = tabla20.id_sector", array("tabla13.tabla13_campo2"=>"tabla13_campo2"), "cc1")
            ->addJoinLeft("tabla2", "tabla2.tabla2_campo1 = tabla20.id_ocupacion", array("tabla2.tabla2_campo2"=>"tabla2_campo2"), "cc1")
            ->setPk("id")
            ->load();
        $this->setSource($this->source);
        $this->firstRow();

        $this->build("p4a_table", "table")
            ->setSource($this->source)
            ->setVisiblecols(array("tabla20_campo2","tabla20_campo4","tabla20_campo3","edad","tabla20_campo7","tabla13_campo2"))
            ->setWidth(500)
            ->showNavigationBar();
        $this->table->cols->tabla20_campo2->setLabel("Cédula de Identidad")->setWidth(80);
        $this->table->cols->tabla20_campo4->setLabel("Apellido(s)");
        $this->table->cols->tabla20_campo3->setLabel("Nombre(s)");
        $this->table->cols->tabla20_campo7->setLabel("Sexo");
        $this->table->cols->tabla13_campo2->setLabel("Ocupación");

        $this->build("p4a_tab_pane","tabs");
            $this->tabs->setwidth(860);
            $this->tabs->pages->build("p4a_frame","flsPersona");
            $this->tabs->pages->build("p4a_frame","flsSalud");
            $this->tabs->pages->build("p4a_frame","flsRiesgo");
            $this->tabs->pages->build("p4a_frame","flsGeneral");

        $this->tabs->pages->flsPersona
            ->setLabel("Personas")
            ->anchor($this->fields->tabla20_campo1)
            ->anchorLeft($this->fields->tabla20_campo2)
            ->anchorLeft($this->fields->tabla20_campo3)
            ->anchorLeft($this->fields->tabla20_campo4)
            ->anchor($this->fields->tabla20_campo5)
            ->anchorLeft($this->fields->tabla20_campo6)
            ->anchorLeft($this->fields->tabla20_campo7)
            ->anchorLeft($this->fields->tabla20_campo8)
            ->anchorLeft($this->fields->id_sector)
            ->anchorLeft($this->fields->id_ocupacion)
            ->anchorLeft($this->fields->id_profesion)
            ->anchor($this->fields->tabla20_campo9)
            ->anchorLeft($this->fields->tabla20_campo10)
            ->anchorLeft($this->fields->tabla20_campo13)
            ->anchorLeft($this->fields->tabla20_campo14)
            ->anchor($this->fields->tabla20_campo15)
            ->anchorLeft($this->fields->tabla20_campo16)
            ->anchorLeft($this->fields->tabla20_campo17)
            ->anchorLeft($this->fields->tabla20_campo18)
            ->anchorLeft($this->fields->tabla20_campo19);

//         $this->tabs->pages->fsSalud
//             ->setLabel("Salud")
//             ->anchor($this->fields->tabla20_campo20)
//             ->anchorLeft($this->fields->tabla20_campo21)
//             ->anchorLeft($this->fields->tabla20_campo22)
//             ->anchorLeft($this->fields->tabla20_campo23)
//             ->anchor($this->fields->tabla20_campo24)
//             ->anchorLeft($this->fields->tabla20_campo25)
//             ->anchorLeft($this->fields->tabla20_campo26)
//             ->anchorLeft($this->fields->tabla20_campo27)
//             ->anchor($this->fields->tabla20_campo28)
//             ->anchorLeft($this->fields->tabla20_campo29)
//             ->anchorLeft($this->fields->tabla20_campo30);
//
//         $this->tabs->pages->fsRiesgo
//             ->setLabel("Riesgo")
//             ->anchor($this->fields->tabla20_campo31)
//             ->anchorLeft($this->fields->tabla20_campo32)
//             ->anchorLeft($this->fields->tabla20_campo33)
//             ->anchor($this->fields->tabla20_campo34)
//             ->anchorLeft($this->fields->tabla20_campo35)
//             ->anchorLeft($this->fields->tabla20_campo36)
//             ->anchor($this->fields->tabla20_campo37)
//             ->anchorLeft($this->fields->tabla20_campo38)
//             ->anchorLeft($this->fields->tabla20_campo39)
//             ->anchorLeft($this->fields->tabla20_campo40);
//
//         $this->tabs->pages->fsGeneral
//             ->setLabel("Varios")
//             ->anchor($this->fields->tabla20_campo41)
//             ->anchorLeft($this->fields->tabla20_campo42)
//             ->anchorLeft($this->fields->tabla20_campo43)
//             ->anchorLeft($this->fields->tabla20_campo44)
//             ->anchor($this->fields->tabla20_campo45)
//             ->anchorLeft($this->fields->tabla20_campo46)
//             ->anchor($this->fields->tabla20_campo47)
//             ->anchorLeft($this->fields->tabla20_campo48)
//             ->anchorLeft($this->fields->tabla20_campo49)
//             ->anchor($this->fields->tabla20_campo50)
//             ->anchorLeft($this->fields->tabla20_campo51)
//             ->anchorLeft($this->fields->tabla20_campo52)
//             ->anchorLeft($this->fields->tabla20_campo53)
//             ->anchor($this->fields->tabla20_campo54)
//             ->anchorLeft($this->fields->tabla20_campo55)
//             ->anchorLeft($this->fields->tabla20_campo56)
//             ->anchorLeft($this->fields->tabla20_campo57)
//             ->anchor($this->fields->tabla20_campo58)
//             ->anchorLeft($this->fields->tabla20_campo59)
//             ->anchorLeft($this->fields->tabla20_campo60)
//             ->anchorLeft($this->fields->tabla20_campo61)
//             ->anchor($this->fields->tabla20_campo62)
//             ->anchorLeft($this->fields->tabla20_campo64)
//             ->anchorLeft($this->fields->tabla20_campo65)
//             ->anchor($this->fields->tabla20_campo66)
//             ->anchorLeft($this->fields->tabla20_campo67)
//             ->anchorLeft($this->fields->tabla20_campo68)
//             ->anchorLeft($this->fields->tabla20_campo69);

        $this->propiedadesCampos();

        $this->frame
            ->anchor($this->table)
            ->anchor($this->tabs)
            ;

        $this
            ->display("menu", $p4a->menu)
            ->display("top", $this->toolbar)
            ->setFocus($this->fields->tabla20_campo2)
            ;
    }

    private function propiedadesCampos()
    {
        /**
         * Arreglos
         */
        $sexo = array();
        $sexo[] = array("id" => "F", "desc" => "Femenino");
        $sexo[] = array("id" => "M", "desc" => "Masculino");
        $this->build("p4a_array_source", "arr_sexo")
            ->load($sexo)
            ->setPk("id");

        /**
         * Orígenes de datos
         */
        $this->build("p4a_db_source", "source_grado_instruccion")
            ->setSchema("cc1")
            ->setTable("tabla1")
            ->setWhere("activo = 'true' AND tabla1_campo3 = 'true'")
            ->addOrder("tabla1_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_ocupacion")
            ->setSchema("cc1")
            ->setTable("tabla2")
            ->setWhere("activo = 'true' AND tabla2_campo3 = 'true'")
            ->addOrder("tabla2_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_profesion")
            ->setSchema("cc1")
            ->setTable("tabla3")
            ->setWhere("activo = 'true' AND tabla3_campo3 = 'true'")
            ->addOrder("tabla3_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_sector")
            ->setSchema("cc1")
            ->setTable("tabla13")
            ->setWhere("activo = 'true' AND tabla13_campo3 = 'true'")
            ->addOrder("tabla13_campo2","ASC")
            ->load();

        $this->build("p4a_db_source", "source_salud")
            ->setSchema("cc1")
            ->setTable("tabla6")
            ->setWhere("activo = 'true' AND tabla6_campo3 = 'true'")
            ->addOrder("tabla6_campo2","ASC")
            ->load();

        $this->build("p4a_db_source", "source_deporte")
            ->setSchema("cc1")
            ->setTable("tabla5")
            ->setWhere("activo = 'true' AND tabla5_campo3 = 'true'")
            ->addOrder("tabla5_campo2","ASC")
            ->load();

        $this->build("p4a_db_source", "source_comites")
            ->setSchema("cc1")
            ->setTable("tabla12")
            ->setWhere("activo = 'true' AND tabla12_campo3 = 'true'")
            ->addOrder("tabla12_campo2","ASC")
            ->load();

        $this->build("p4a_db_source", "source_mision")
            ->setSchema("cc1")
            ->setTable("tabla19")
            ->setWhere("activo = 'true' AND tabla19_campo3 = 'true'")
            ->addOrder("tabla19_campo2","ASC")
            ->load();

        /**
         * Propiedades de los Campos del Formulario
         * Pestaña: Personas
         *
         * el campo <tabla20_campo1> lo desactivamos
         * el campo <fregistro> lo desactivamos
         * el campo <activo> lo desactivamos
         * al campo <tabla20_campo2> lo validamos contra ...
         */
        $this->fields->tabla20_campo1
            ->disable()
            ->setLabel("Nº")
            ->setWidth(50);
//         $this->fields->fregistro
//             ->setVisible(false);
//         $this->fields->activo->setVisible(false);
//
        $this->fields->tabla20_campo2
            ->setLabel("C.I.")
            ->setTooltip("Cédula de Identidad. Sin separador de miles")
            ->setWidth(100)
            ->setProperty('maxlength',"9")
            ->addValidator(new P4A_Validate_Int,true);

        $this->fields->tabla20_campo3
            ->setLabel("Nombre(s)")
            ->setWidth(150)
            ->setProperty('maxlength',"30")
            ->addValidator(new P4A_Validate_Alpha(true),true);
        $this->fields->tabla20_campo4
            ->setLabel("Apellido(s)")
            ->setWidth(150)
            ->setProperty('maxlength',"30")
            ->addValidator(new P4A_Validate_Alpha(true),true);
        $this->fields->tabla20_campo5
            ->setLabel("Edad")
            ->setTooltip("Edad cumplida en Años")
            ->setWidth(50)
            ->setProperty('maxlength',"2")
//             ->addValidator(new P4A_Validate_Int,true)
            ;
        $this->fields->tabla20_campo6
            ->setLabel("Fecha de Nacimiento")
            ->setType("date")
            ->setYearRange(date('Y')-100,date('Y'))
            ->setWidth(80)
//             ->addValidator(new P4A_Validate_Date(true),true)
            ;
        $this->fields->tabla20_campo7
            ->setLabel("Sexo")
            ->setType("select")
            ->setSource($this->arr_sexo)
            ->allowNull("Seleccione")
            ->setWidth(100);
        $this->fields->tabla20_campo8
            ->setLabel("Grado de Instrucción")
            ->setType("select")
            ->setSource($this->source_grado_instruccion)
            ->allowNull("Seleccione")
            ->setWidth(100);
        $this->fields->id_ocupacion
            ->setLabel("Ocupación")
            ->setType("select")
            ->setSource($this->source_ocupacion)
            ->allowNull("Seleccione")
            ->setWidth(100);
        $this->fields->id_profesion
            ->setLabel("Profesión")
            ->setType("select")
            ->setSource($this->source_profesion)
            ->allowNull("Seleccione")
            ->setWidth(100);
        $this->fields->id_sector
            ->setLabel("Sector donde Vive")
            ->setType("select")
            ->setSource($this->source_sector)
            ->allowNull("Seleccione")
            ->setWidth(100);
        $this->fields->tabla20_campo9
            ->setLabel("Sin Documentos")
            ->setTooltip("Sin documentos de identificación (Partida de Nacimienti, Cédula, etc)");
        $this->fields->tabla20_campo10
            ->setLabel("Estudia Actualmente");
        $this->fields->tabla20_campo13
            ->setLabel("Trabaja Actualmente");
        $this->fields->tabla20_campo14
            ->setLabel("Correo Electrónico")
            ->setWidth(150)
            ->setProperty('maxlength',"40")
            ->addValidator(new P4A_Validate_EmailAddress(true),true)
            ;
        $this->fields->tabla20_campo15
            ->setLabel("Posee algún terreno")
            ->implement('onclick',$this,"activar_campo16");
        $this->fields->tabla20_campo16
            ->setLabel("Ubicación")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Ubicación donde se encuentra el terreno que posee")
            ->setProperty('maxlength',"250")
//             ->addValidator(new P4A_Validate_Alnum(true),true)
            ;
        $this->fields->tabla20_campo17
            ->setLabel("Tiene Ley de Política Habitacional");
        $this->fields->tabla20_campo18
            ->setLabel("Tiene Seguro");
        $this->fields->tabla20_campo19
            ->setLabel("Tiene Vehículo");
    }

    public function activar_campo16()
    {
        $tabla20_campo15 = $this->fields->tabla20_campo15->getNewValue();
        if($tabla20_campo15)
            { $this->fields->tabla20_campo16->enable(); }
        else
            { $this->fields->tabla20_campo16->disable(); }
    }

    public function saveRow()
    {
        try
        {
            $tabla20_campo1 = $this->fields->tabla20_campo1->getSQLNewValue();
            $tabla20_campo2 = $this->fields->tabla20_campo2->getSQLNewValue();
            $tabla20_campo3 = $this->fields->tabla20_campo3->getSQLNewValue();
            $tabla20_campo4 = $this->fields->tabla20_campo4->getSQLNewValue();
            $tabla20_campo5 = $this->fields->tabla20_campo5->getSQLNewValue();
            $tabla20_campo6 = $this->fields->tabla20_campo6->getSQLNewValue();
            $tabla20_campo7 = $this->fields->tabla20_campo7->getSQLNewValue();
            $tabla20_campo8 = $this->fields->tabla20_campo8->getSQLNewValue();
            $tabla20_campo9 = $this->fields->tabla20_campo9->getSQLNewValue();
            $tabla20_campo10 = $this->fields->tabla20_campo10->getSQLNewValue();
            if ($tabla20_campo10 == 0) $tabla20_campo10 = 'f'; else $tabla20_campo10 = 't';
            $tabla20_campo11 = $this->fields->tabla20_campo11->getSQLNewValue();
            $tabla20_campo12 = $this->fields->tabla20_campo12->getSQLNewValue();
            if ($tabla20_campo12 == 0) $tabla20_campo12 = 'f'; else $tabla20_campo12 = 't';

            $tabla20_campo13 = $this->fields->tabla20_campo13->getSQLNewValue();
            if ($tabla20_campo13 == 0) $tabla20_campo13 = 'false'; else $tabla20_campo13 = 'true';
            $tabla20_campo14 = $this->fields->tabla20_campo14->getSQLNewValue();
            $tabla20_campo15 = $this->fields->tabla20_campo15->getSQLNewValue();
            if ($tabla20_campo15 == 0) $tabla20_campo15 = 'f'; else $tabla20_campo15 = 't';
            $tabla20_campo16 = $this->fields->tabla20_campo16->getSQLNewValue();
            $tabla20_campo17 = $this->fields->tabla20_campo17->getSQLNewValue();
            if ($tabla20_campo17 == 0) $tabla20_campo17 = 'f'; else $tabla20_campo17 = 't';
            $tabla20_campo18 = $this->fields->tabla20_campo18->getSQLNewValue();
            if ($tabla20_campo18 == 0) $tabla20_campo18 = 'f'; else $tabla20_campo18 = 't';
            $tabla20_campo19 = $this->fields->tabla20_campo19->getSQLNewValue();
            if ($tabla20_campo19 == 0) $tabla20_campo19 = 'f'; else $tabla20_campo19 = 't';
            $sector = $this->fields->id_sector->getSQLNewValue();
            $ocupacion = $this->fields->id_ocupacion->getSQLNewValue();
            $profesion = $this->fields->id_profesion->getSQLNewValue();
            $parentesco = $this->fields->id_parentesco->getSQLNewValue();

            $this->warning("entra 1:$tabla20_campo1 - 2:$tabla20_campo2 - 3:$tabla20_campo3 - 4:$tabla20_campo4 - 5:$tabla20_campo5 - 6:$tabla20_campo6");
            $this->warning("entra 7:$tabla20_campo7 - 8:$tabla20_campo8 - 9:$tabla20_campo9 - 10:$tabla20_campo10 - 11:$tabla20_campo11 - 12:$tabla20_campo12");
            $this->warning("entra 13:$tabla20_campo13 - 14:$tabla20_campo14 - 15:$tabla20_campo15 - 16:$tabla20_campo16 - 17:$tabla20_campo17 - 18:$tabla20_campo18");
            $this->warning("entra 19:$tabla20_campo19 - sector:$sector - ocupac:$ocupacion - prof:$profesion - parent:$parentesco");
            parent::saveRow();
//             return;
        } catch (Exception $e)
        {
//             $this->error('mensaje:'.$e->getMessage().' codigo:'.$e->getCode());
            $this->error('mensaje:'.$e->getMessage().' código:'.$e->getCode().' trace:'.$e->getTrace().' traceString:'.$e->getTraceAsString());
            $this->error("Imposible guardar");
        }
    }
}
