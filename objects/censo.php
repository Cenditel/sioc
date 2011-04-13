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
class habitantes extends P4A_Base_Mask
{
	public $toolbar = null;
	public $table = null;
	public $fs_details = null;

	public function __construct()
	{
		parent::__construct();
		$p4a = p4a::singleton();

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

        $this->build("p4a_db_source", "censo")
            ->setTable("censo")
            ->setFields(array("censo.*" => "*","extract(years from age(now(),fecha_nacimiento))"=>"edad", "tabla1.tabla1_campo2" => "tabla1_campo2"))
            ->addJoinLeft("tabla1", "tabla1.tabla1_campo1 = censo.grado_instruccion", array("tabla1.tabla1_campo2"=>"tabla1_campo2"), "cc1")
            ->addOrder("apellido")
            ->setPk("id")
            ->load();

		$this->setSource($this->censo);
		$this->firstRow();

		$this->build("p4a_table", "table")
			->setSource($this->censo)
            ->setVisibleCols(array("cedula","apellido","nombre","sexo","edad","tabla1_campo2","estudia","trabaja","padece_enfermedad","tabla20_campo65"))
// 			->setWidth(500)
			->showNavigationBar();

        $this->table->cols->fecha_nacimiento->setLabel("Fecha de Nacimiento")->setWidth(100);
        $this->table->cols->tabla1_campo2->setLabel("Grado de Instrucción");
		$this->table->cols->tabla20_campo65->setLabel("Misionero");

// 		$this->setRequiredField("description");
        $sexo = array();
        $sexo[] = array("id"=>"M", "desc"=>"Masculino");
        $sexo[] = array("id"=>"F", "desc"=>"Femenino");
        $this->build('P4A_Array_Source', "arr_sexo")
                  ->load($sexo)
                  ->setPk("id");
        $agno = $this->build('P4A_Array_Source',"array_agno");
        $b = array();
        $actual = date('Y');
        $inicial = "1950";
        for ($j=$actual; $j>=$inicial; $j--)
        {
            $b[]["value"] = $j;
        }
        $agno->load($b);
        $agno->setPk("value");

        $this->build("p4a_db_source", "source_grado_instruccion")
            ->setSchema("cc1")
            ->setTable("tabla1")
            ->setWhere("activo = 'true' AND tabla1_campo3 = 'true'")
            ->addOrder("tabla1_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_parentesco")
            ->setSchema("cc1")
            ->setTable("tabla4")
            ->setWhere("activo = 'true' AND tabla4_campo3 = 'true'")
            ->addOrder("tabla4_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_profesion")
            ->setSchema("cc1")
            ->setTable("tabla3")
            ->setWhere("activo = 'true' AND tabla3_campo3 = 'true'")
            ->addOrder("tabla3_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_ocupacion")
            ->setSchema("cc1")
            ->setTable("tabla2")
            ->setWhere("activo = 'true' AND tabla2_campo3 = 'true'")
            ->addOrder("tabla2_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_salario")
            ->setSchema("cc1")
            ->setTable("tabla21")
            ->setWhere("activo = 'true' AND tabla21_campo3 = 'true'")
            ->addOrder("tabla21_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_situacion_laboral")
            ->setSchema("cc1")
            ->setTable("tabla18")
            ->setWhere("activo = 'true' AND tabla18_campo3 = 'true'")
            ->addOrder("tabla18_campo2","ASC")
            ->load();
        $this->build("p4a_db_source", "source_estado_civil")
            ->setSchema("cc1")
            ->setTable("tabla22")
            ->setWhere("activo = 'true' AND tabla22_campo3 = 'true'")
            ->addOrder("tabla22_campo2","ASC")
            ->load();
		$this->build("p4a_db_source", "source_sector")
            ->setSchema("cc1")
            ->setTable("tabla13")
            ->setWhere("activo = 'true' AND tabla13_campo3 = 'true'")
            ->addOrder("tabla13_campo2","ASC")
            ->load();
		$this->build("p4a_db_source", "source_vivienda")
            ->setSchema("cc1")
            ->setTable("tabla14")
			->setFields(array("tabla14.*" => "*","(tabla14_campo3||' '||tabla14_campo15)"=>"desc_vivienda"))
            ->setWhere("tabla14_campo7 = 'true'")
            ->addOrder("tabla14_campo2","ASC")
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
            ->setWhere("tabla12_campo3 = 'true'")
            ->addOrder("tabla12_campo2","ASC")
            ->load();

        /**
        * Propiedades de los campso del formulario
        */
		$this->fields->id->disable()->setLabel("ID")->setWidth(50);
        $this->fields->cedula->setLabel("C.I.")->setWidth(80);
        $this->fields->sexo
            ->setType("select")
            ->setSource($this->arr_sexo)
            ->setTooltip("Seleccione una categoría de la lista")
            ->allowNull("Seleccione");
        $this->fields->fecha_nacimiento
        	->setLabel("Fecha de Nacimiento")
        	->setYearRange(date('Y')-90,date('Y'))
        	->setWidth(80);
        $this->fields->grado_instruccion
            ->setType("select")
            ->setSource($this->source_grado_instruccion)
            ->setLabel("Grado de Instrucción")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->parentesco
            ->setType("select")
            ->setSource($this->source_parentesco)
            ->setLabel("Parentesco")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->profesion
            ->setType("select")
            ->setSource($this->source_profesion)
            ->setLabel("Profesión")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->ocupacion
            ->setType("select")
            ->setSource($this->source_ocupacion)
            ->setLabel("Ocupación")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->salario
            ->setType("select")
            ->setSource($this->source_salario)
            ->setLabel("Salario")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->situacion_laboral
            ->setType("select")
            ->setSource($this->source_situacion_laboral)
            ->setLabel("Situación Laboral")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->estado_civil
            ->setType("select")
            ->setSource($this->source_estado_civil)
            ->setLabel("Estado Civil")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
        $this->fields->correo->setLabel("Correo Electrónico")->setWidth(150);
        $this->fields->telefono->setLabel("Teléfono")->setWidth(100)->setInputMask("(999)9999999");
        $this->fields->agno_comunidad
            ->setType("select")
            ->setSource($this->array_agno)
            ->setLabel("Año en que llegó a la Comunidad")
            ->setTooltip("Seleccione una categoría de la lista")
            ->allowNull("Seleccione");
		$this->fields->sector
            ->setType("select")
            ->setSource($this->source_sector)
            ->setLabel("Sector")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");
		$this->fields->vivienda
            ->setType("select")
            ->setSource($this->source_vivienda)
			->setSourceDescriptionField("tabla14_campo3")
// 			->setSourceDescriptionField("desc_vivienda")
            ->setLabel("Vivienda")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione");

        $this->fields->terreno
            ->implement('onclick',$this,"activar_campo16");
		$this->fields->ubicacion_terreno
			->setLabel("Ubicación")
            ->disable()
            ->setWidth(200)
            ->setHeight(40)
            ->setTooltip("Ubicación donde se encuentra el terreno que posee")
            ->setProperty('maxlength',"250");

		/**
		* Salud
		*/
		$this->fields->cual_enfermedad
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
		$this->fields->cual_tratamiento
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
		$this->fields->cual_material_medico
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
		$this->fields->cual_operacion
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
		$this->fields->cual_servicio_salud
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");

        /**
        * Riesgo
        */
        $this->fields->tabla20_campo31
            ->setLabel("Sabe cómo actuar en caso de una emergencia");
        $this->fields->tabla20_campo32
            ->setLabel("Conoce los organismos a quien acudir en caso de una emergencia");
        $this->fields->tabla20_campo33
            ->setLabel("Cuáles")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo34
            ->setLabel("Conoce los números de emergencia");
        $this->fields->tabla20_campo35
            ->setLabel("Indíquelos")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo36
            ->setlabel("A dónde se dirigría en caso de una emergencia")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo37
            ->setLabel("Ha recibido entrenamiento para casos de emergencia");
        $this->fields->tabla20_campo38
            ->setLabel("Indíquelos")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo39
            ->setLabel("Le gustaría tomar talleres para casos de emergencia");
        $this->fields->tabla20_campo40
            ->setLabel("Indíquelos")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");

		/**
        * Riesgo
        */
        $this->fields->tabla20_campo41
            ->setLabel("Posee alguna habilidad artesanal");
        $this->fields->tabla20_campo42
            ->setLabel("Indíquelas")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo43
            ->setLabel("Desea integrar algún grupo cultural");
        $this->fields->tabla20_campo44
            ->setLabel("Indíquelos")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo45
            ->setLabel("Qué clase de talleres le gusaría que el INCES dictara")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo46
            ->setLabel("Qué otros talleres le gustaría que se dictaran")
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo47
            ->setLabel("Cuál considera que es la necesidad prioritaria")
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo48
            ->setLabel("Existe algún problema ambiental");
        $this->fields->tabla20_campo49
            ->setLabel("Indíquelo(s)")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo50
            ->setLabel("Le gustaría un espacio de esparcimiento");
        $this->fields->tabla20_campo51
            ->setLabel("Indíquelos")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo52
            ->setLabel("Existe algún problema de seguridad");
        $this->fields->tabla20_campo53
            ->setLabel("Indíquelos")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");
        $this->fields->tabla20_campo54
            ->setLabel("Practica algún deporte");
        $this->fields->tabla20_campo55
            ->setType("select")
            ->setSource($this->source_deporte)
            ->setLabel("Disciplinas")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione")
			;
        $this->fields->tabla20_campo56
            ->setLabel("Le gustaría practicar algún deporte");
        $this->fields->tabla20_campo57
            ->setType("select")
            ->setSource($this->source_deporte)
            ->setLabel("Disciplinas")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione")
			;
        $this->fields->tabla20_campo58
            ->setLabel("Percibe problemas de drogas");
        $this->fields->tabla20_campo59
            ->setLabel("Percibe problemas de alcoholismo");
        $this->fields->tabla20_campo60
            ->setLabel("Percibe problemas de delincuencia");
        $this->fields->tabla20_campo61
            ->setLabel("Percibe otro problema");
        $this->fields->tabla20_campo62
            ->setLabel("Le gustaría participar en el Consejo Comunal");
        $this->fields->tabla20_campo63
            ->setType("select")
            ->setSource($this->source_comites)
            ->setLabel("Comités")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione")
			;
        $this->fields->tabla20_campo64
            ->setLabel("Sabe sí se está implementando alguna Misión en la comunidad");
        $this->fields->tabla20_campo65
            ->setLabel("Se beneficia de alguna Misión");
        $this->fields->tabla20_campo66
            ->setType("select")
            ->setSource($this->source_comites)
            ->setLabel("Cuál")
            ->setTooltip("Seleccione una categoría de la lista")
            ->setWidth(150)
            ->allowNull("Seleccione")
			;
        $this->fields->tabla20_campo67
            ->setLabel("Desea un crédito Socioproductivo");
        $this->fields->tabla20_campo68
            ->setLabel("Explique")
            ->disable()
            ->setWidth(150)
            ->setHeight(40)
            ->setTooltip("Especifique");

        $this->fields->id->label->setWidth(50);
        $this->fields->cedula->label->setWidth(50);
		$this->fields->nombre->label->setWidth(80);
		$this->fields->apellido->label->setWidth(80);
		$this->fields->fecha_nacimiento->label->setWidth(142);
		$this->fields->estado_civil->label->setWidth(130);
		$this->fields->correo->label->setWidth(75);
		$this->fields->telefono->label->setWidth(80);
		$this->fields->estudia->label->setWidth(70);
		$this->fields->trabaja->label->setWidth(70);
		$this->fields->ocupacion->label->setWidth(75);
		$this->fields->profesion->label->setWidth(80);
		$this->fields->agno_comunidad->label->setWidth(170);
		$this->fields->sector->label->setWidth(75);
		$this->fields->vivienda->label->setWidth(80);
		$this->fields->politica_habitacional->label->setWidth(130);
		$this->fields->seguro->label->setWidth(60);
		$this->fields->vehiculo->label->setWidth(60);
		$this->fields->terreno->label->setWidth(60);

		$this->build("p4a_tab_pane","tabs");
		$this->tabs->setwidth(860);
		$this->tabs->pages->build("p4a_frame","flsPersona");
		$this->tabs->pages->build("p4a_frame","flsSalud");
		$this->tabs->pages->build("p4a_frame","flsRiesgo");
		$this->tabs->pages->build("p4a_frame","flsGeneral");

		$this->tabs->pages->flsPersona
			->setLabel("Detalles")
			->anchor($this->fields->id)
			->anchorLeft($this->fields->cedula)
			->anchorLeft($this->fields->nombre)
            ->anchorLeft($this->fields->apellido)
            ->anchor($this->fields->sexo)
            ->anchorLeft($this->fields->fecha_nacimiento)
            ->anchorLeft($this->fields->estado_civil)
            ->anchor($this->fields->parentesco)
            ->anchorLeft($this->fields->correo)
            ->anchorLeft($this->fields->telefono)
            ->anchor($this->fields->grado_instruccion)
            ->anchorLeft($this->fields->estudia)
            ->anchorLeft($this->fields->trabaja)
            ->anchor($this->fields->situacion_laboral)
            ->anchorLeft($this->fields->ocupacion)
            ->anchorLeft($this->fields->profesion)
            ->anchor($this->fields->salario)
            ->anchor($this->fields->agno_comunidad)
			->anchorLeft($this->fields->sector)
			->anchorLeft($this->fields->vivienda)
			->anchor($this->fields->politica_habitacional)
			->anchorLeft($this->fields->seguro)
			->anchorLeft($this->fields->vehiculo)
			->anchorLeft($this->fields->terreno)
			->anchorLeft($this->fields->ubicacion_terreno)
			;

		$this->tabs->pages->flsSalud
            ->setLabel("Salud")
            ->anchor($this->fields->padece_enfermedad)
            ->anchorLeft($this->fields->cual_enfermedad)
            ->anchorLeft($this->fields->requiere_medicamento_porvida)
            ->anchorLeft($this->fields->cual_tratamiento)
            ->anchor($this->fields->requiere_material_medico)
            ->anchorLeft($this->fields->cual_material_medico)
            ->anchorLeft($this->fields->requiere_operacion)
            ->anchorLeft($this->fields->cual_operacion)
            ->anchor($this->fields->requiere_servicio_salud)
            ->anchorLeft($this->fields->cual_servicio_salud)
            ->anchorLeft($this->fields->embarazada);

        $this->tabs->pages->flsRiesgo
            ->setLabel("Riesgo")
            ->anchor($this->fields->tabla20_campo31)
            ->anchorLeft($this->fields->tabla20_campo32)
            ->anchorLeft($this->fields->tabla20_campo33)
            ->anchor($this->fields->tabla20_campo34)
            ->anchorLeft($this->fields->tabla20_campo35)
            ->anchorLeft($this->fields->tabla20_campo36)
            ->anchor($this->fields->tabla20_campo37)
            ->anchorLeft($this->fields->tabla20_campo38)
            ->anchorLeft($this->fields->tabla20_campo39)
            ->anchorLeft($this->fields->tabla20_campo40);

        $this->tabs->pages->flsGeneral
            ->setLabel("Varios")
            ->anchor($this->fields->tabla20_campo41)
            ->anchorLeft($this->fields->tabla20_campo42)
            ->anchorLeft($this->fields->tabla20_campo43)
            ->anchorLeft($this->fields->tabla20_campo44)
            ->anchor($this->fields->tabla20_campo45)
            ->anchorLeft($this->fields->tabla20_campo46)
            ->anchor($this->fields->tabla20_campo47)
            ->anchorLeft($this->fields->tabla20_campo48)
            ->anchorLeft($this->fields->tabla20_campo49)
            ->anchor($this->fields->tabla20_campo50)
            ->anchorLeft($this->fields->tabla20_campo51)
            ->anchorLeft($this->fields->tabla20_campo52)
            ->anchorLeft($this->fields->tabla20_campo53)
            ->anchor($this->fields->tabla20_campo54)
            ->anchorLeft($this->fields->tabla20_campo55)
            ->anchorLeft($this->fields->tabla20_campo56)
            ->anchorLeft($this->fields->tabla20_campo57)
            ->anchor($this->fields->tabla20_campo58)
            ->anchorLeft($this->fields->tabla20_campo59)
            ->anchorLeft($this->fields->tabla20_campo60)
            ->anchorLeft($this->fields->tabla20_campo61)
            ->anchor($this->fields->tabla20_campo62)
            ->anchorLeft($this->fields->tabla20_campo63)
            ->anchorLeft($this->fields->tabla20_campo64)
            ->anchor($this->fields->tabla20_campo65)
            ->anchorLeft($this->fields->tabla20_campo66)
            ->anchorLeft($this->fields->tabla20_campo67)
            ->anchorLeft($this->fields->tabla20_campo68)
            ;

		$this->frame
			->anchor($this->table)
 			->anchor($this->tabs);

		$this
			->display("menu", $p4a->menu)
			->display("top", $this->toolbar)
			->setFocus($this->fields->cedula);
	}

    public function activar_campo16()
    {
        $terreno = $this->fields->terreno->getNewValue();
        if($terreno)
            { $this->fields->ubicacion_terreno->enable(); }
        else
            { $this->fields->ubicacion_terreno->disable(); }
    }

    public function saveRow()
    {
        $id = $this->fields->id->getSQLNewValue();
        $cedula = $this->fields->cedula->getSQLNewValue();
        $nombre = $this->fields->nombre->getSQLNewValue();
        $apellido = $this->fields->apellido->getSQLNewValue();
        $fecha_nacimiento = $this->fields->fecha_nacimiento->getSQLNewValue();
        $sexo = $this->fields->sexo->getSQLNewValue();
        $grado_instruccion = $this->fields->grado_instruccion->getSQLNewValue();
        $estudia = $this->fields->estudia->getSQLNewValue();
//             if ($estudia == 0) $estudia = 'f'; else $estudia = 't';

        $trabaja = $this->fields->trabaja->getSQLNewValue();
//             if ($trabaja == 0) $trabaja = 'false'; else $trabaja = 'true';
        $correo = $this->fields->correo->getSQLNewValue();
		$telefono= $this->fields->telefono->getSQLNewValue();
        $terreno = $this->fields->terreno->getSQLNewValue();
//             if ($terreno == 0) $terreno = 'f'; else $terreno = 't';
        $ubicacion_terreno = $this->fields->ubicacion_terreno->getSQLNewValue();
        $politica_habitacional = $this->fields->politica_habitacional->getSQLNewValue();
//             if ($politica_habitacional == 0) $politica_habitacional = 'f'; else $politica_habitacional = 't';
        $seguro = $this->fields->seguro->getSQLNewValue();
//             if ($seguro == 0) $seguro = 'f'; else $seguro = 't';
        $vehiculo = $this->fields->vehiculo->getSQLNewValue();
//             if ($vehiculo == 0) $vehiculo = 'f'; else $vehiculo = 't';
        $sector = $this->fields->sector->getSQLNewValue();
        $ocupacion = $this->fields->ocupacion->getSQLNewValue();
        $profesion = $this->fields->profesion->getSQLNewValue();
        $parentesco = $this->fields->parentesco->getSQLNewValue();
        $salario = $this->fields->salario->getSQLNewValue();
        $situacion_laboral = $this->fields->situacion_laboral->getSQLNewValue();
        $estado_civil = $this->fields->estado_civil->getSQLNewValue();
        $agno_comunidad = $this->fields->agno_comunidad->getSQLNewValue();
        $vivienda = $this->fields->vivienda->getSQLNewValue();

        /** Salud */
        $padece_enfermedad = $this->fields->padece_enfermedad->getSQLNewValue();
// 		if ($padece_enfermedad =)
        $cual_enfermedad = $this->fields->cual_enfermedad->getSQLNewValue();
        $requiere_medicamento_porvida = $this->fields->requiere_medicamento_porvida->getSQLNewValue();
        $cual_tratamiento = $this->fields->cual_tratamiento->getSQLNewValue();
        $requiere_material_medico = $this->fields->requiere_material_medico->getSQLNewValue();
        $cual_material_medico = $this->fields->cual_material_medico->getSQLNewValue();
        $requiere_operacion = $this->fields->requiere_operacion->getSQLNewValue();
        $cual_operacion = $this->fields->cual_operacion->getSQLNewValue();
        $requiere_servicio_salud = $this->fields->requiere_servicio_salud->getSQLNewValue();
        $cual_servicio_salud = $this->fields->cual_servicio_salud->getSQLNewValue();
        $embarazada = $this->fields->embarazada->getSQLNewValue();

        /** Riesgo */
        $tabla20_campo31 = $this->fields->tabla20_campo31->getSQLNewValue();
        $tabla20_campo32 = $this->fields->tabla20_campo32->getSQLNewValue();
        $tabla20_campo33 = $this->fields->tabla20_campo33->getSQLNewValue();
        $tabla20_campo34 = $this->fields->tabla20_campo34->getSQLNewValue();
        $tabla20_campo35 = $this->fields->tabla20_campo35->getSQLNewValue();
        $tabla20_campo36 = $this->fields->tabla20_campo36->getSQLNewValue();
        $tabla20_campo37 = $this->fields->tabla20_campo37->getSQLNewValue();
        $tabla20_campo38 = $this->fields->tabla20_campo38->getSQLNewValue();
        $tabla20_campo39 = $this->fields->tabla20_campo39->getSQLNewValue();
        $tabla20_campo40 = $this->fields->tabla20_campo40->getSQLNewValue();

        /** Varios */
        $tabla20_campo41 = $this->fields->tabla20_campo41->getSQLNewValue();
        $tabla20_campo42 = $this->fields->tabla20_campo42->getSQLNewValue();
        $tabla20_campo43 = $this->fields->tabla20_campo43->getSQLNewValue();
        $tabla20_campo44 = $this->fields->tabla20_campo44->getSQLNewValue();
        $tabla20_campo45 = $this->fields->tabla20_campo45->getSQLNewValue();
        $tabla20_campo46 = $this->fields->tabla20_campo46->getSQLNewValue();
        $tabla20_campo47 = $this->fields->tabla20_campo47->getSQLNewValue();
        $tabla20_campo48 = $this->fields->tabla20_campo48->getSQLNewValue();
        $tabla20_campo49 = $this->fields->tabla20_campo49->getSQLNewValue();
        $tabla20_campo50 = $this->fields->tabla20_campo50->getSQLNewValue();
        $tabla20_campo51 = $this->fields->tabla20_campo51->getSQLNewValue();
        $tabla20_campo52 = $this->fields->tabla20_campo52->getSQLNewValue();
        $tabla20_campo53 = $this->fields->tabla20_campo53->getSQLNewValue();
        $tabla20_campo54 = $this->fields->tabla20_campo54->getSQLNewValue();
        $tabla20_campo55 = $this->fields->tabla20_campo55->getSQLNewValue();
        $tabla20_campo56 = $this->fields->tabla20_campo56->getSQLNewValue();
        $tabla20_campo57 = $this->fields->tabla20_campo57->getSQLNewValue();
        $tabla20_campo58 = $this->fields->tabla20_campo58->getSQLNewValue();
        $tabla20_campo59 = $this->fields->tabla20_campo59->getSQLNewValue();
        $tabla20_campo60 = $this->fields->tabla20_campo60->getSQLNewValue();
        $tabla20_campo61 = $this->fields->tabla20_campo61->getSQLNewValue();
        $tabla20_campo62 = $this->fields->tabla20_campo62->getSQLNewValue();
        $tabla20_campo63 = $this->fields->tabla20_campo63->getSQLNewValue();
        $tabla20_campo64 = $this->fields->tabla20_campo64->getSQLNewValue();
        $tabla20_campo65 = $this->fields->tabla20_campo65->getSQLNewValue();
        $tabla20_campo66 = $this->fields->tabla20_campo66->getSQLNewValue();
        $tabla20_campo67 = $this->fields->tabla20_campo67->getSQLNewValue();
        $tabla20_campo68 = $this->fields->tabla20_campo68->getSQLNewValue();

		if (!$estudia) $estudia = '0';
		if (!$trabaja) $trabaja = '0';
		if (!$politica_habitacional) $politica_habitacional = '0';
		if (!$seguro) $seguro = '0';
		if (!$vehiculo) $vehiculo = '0';
		if (!$terreno) $terreno = '0';
		if (!$padece_enfermedad) $padece_enfermedad = '0';
		if (!$requiere_medicamento_porvida) $requiere_medicamento_porvida = '0';
		if (!$requiere_material_medico) $requiere_material_medico = '0';
		if (!$requiere_operacion) $requiere_operacion = '0';
		if (!$requiere_servicio_salud) $requiere_servicio_salud = '0';
		if (!$embarazada) $embarazada = '0';
		if (!$tabla20_campo31) $tabla20_campo31 = '0';
		if (!$tabla20_campo32) $tabla20_campo32 = '0';
		if (!$tabla20_campo34) $tabla20_campo34 = '0';
		if (!$tabla20_campo37) $tabla20_campo37 = '0';
		if (!$tabla20_campo39) $tabla20_campo39 = '0';
		if (!$tabla20_campo41) $tabla20_campo41 = '0';
		if (!$tabla20_campo43) $tabla20_campo43 = '0';
		if (!$tabla20_campo48) $tabla20_campo48 = '0';
		if (!$tabla20_campo50) $tabla20_campo50 = '0';
		if (!$tabla20_campo52) $tabla20_campo52 = '0';
		if (!$tabla20_campo54) $tabla20_campo54 = '0';
		if (!$tabla20_campo55) $tabla20_campo55 = '0';
		if (!$tabla20_campo56) $tabla20_campo56 = '0';
		if (!$tabla20_campo57) $tabla20_campo57 = '0';
		if (!$tabla20_campo58) $tabla20_campo58 = '0';
		if (!$tabla20_campo59) $tabla20_campo59 = '0';
		if (!$tabla20_campo60) $tabla20_campo60 = '0';
		if (!$tabla20_campo61) $tabla20_campo61 = '0';
		if (!$tabla20_campo62) $tabla20_campo62 = '0';
		if (!$tabla20_campo63) $tabla20_campo63 = '0';
		if (!$tabla20_campo64) $tabla20_campo64 = '0';
		if (!$tabla20_campo65) $tabla20_campo65 = '0';
		if (!$tabla20_campo66) $tabla20_campo66 = '0';
		if (!$tabla20_campo67) $tabla20_campo67 = '0';

        $queryInsert = "INSERT INTO encuestas.censo (id, cedula, nombre, apellido, sexo, fecha_nacimiento, estado_civil, parentesco, correo, telefono, grado_instruccion, estudia, trabaja, situacion_laboral, ocupacion, profesion, salario, agno_comunidad, sector, vivienda, politica_habitacional, seguro, vehiculo, terreno, ubicacion_terreno, padece_enfermedad, cual_enfermedad, requiere_medicamento_porvida, cual_tratamiento, requiere_material_medico, cual_material_medico, requiere_operacion, cual_operacion, requiere_servicio_salud, cual_servicio_salud, embarazada, tabla20_campo31, tabla20_campo32, tabla20_campo33, tabla20_campo34, tabla20_campo35, tabla20_campo36, tabla20_campo37, tabla20_campo38, tabla20_campo39, tabla20_campo40, tabla20_campo41, tabla20_campo42, tabla20_campo43, tabla20_campo44, tabla20_campo45, tabla20_campo46, tabla20_campo47, tabla20_campo48, tabla20_campo49, tabla20_campo50, tabla20_campo51, tabla20_campo52, tabla20_campo53, tabla20_campo54, tabla20_campo55, tabla20_campo56, tabla20_campo57, tabla20_campo58, tabla20_campo59, tabla20_campo60, tabla20_campo61, tabla20_campo62, tabla20_campo63, tabla20_campo64, tabla20_campo65, tabla20_campo66, tabla20_campo67, tabla20_campo68) VALUES($id, $cedula, $nombre, $apellido, $sexo, $fecha_nacimiento, $estado_civil, $parentesco, $correo, $telefono, $grado_instruccion, $estudia, $trabaja, $situacion_laboral, $ocupacion, $profesion, $salario, $agno_comunidad, $sector, $vivienda, $politica_habitacional, $seguro, $vehiculo, $terreno, $ubicacion_terreno, $padece_enfermedad, $cual_enfermedad, $requiere_medicamento_porvida, $cual_tratamiento, $requiere_material_medico, $cual_material_medico, $requiere_operacion, $cual_operacion, $requiere_servicio_salud, $cual_servicio_salud, $embarazada, $tabla20_campo31, $tabla20_campo32, $tabla20_campo33, $tabla20_campo34, $tabla20_campo35, $tabla20_campo36, $tabla20_campo37, $tabla20_campo38, $tabla20_campo39, $tabla20_campo40, $tabla20_campo41, $tabla20_campo42, $tabla20_campo43, $tabla20_campo44, $tabla20_campo45, $tabla20_campo46, $tabla20_campo47, $tabla20_campo48, $tabla20_campo49, $tabla20_campo50, $tabla20_campo51, $tabla20_campo52, $tabla20_campo53, $tabla20_campo54, $tabla20_campo55, $tabla20_campo56, $tabla20_campo57, $tabla20_campo58, $tabla20_campo59, $tabla20_campo60, $tabla20_campo61, $tabla20_campo62, $tabla20_campo63, $tabla20_campo64, $tabla20_campo65, $tabla20_campo66, $tabla20_campo67, $tabla20_campo68)";
		
        $queryUpdate = "UPDATE encuestas.censo SET cedula = '$cedula', nombre = '$nombre', apellido = '$apellido', sexo = '$sexo', fecha_nacimiento = '$fecha_nacimiento', estado_civil = $estado_civil, parentesco = $parentesco, correo = '$correo', telefono = '$terreno', grado_instruccion = $grado_instruccion, estudia = '$estudia', trabaja = '$trabaja', situacion_laboral = $situacion_laboral, ocupacion = $ocupacion, profesion = $profesion, salario = $salario, agno_comunidad = '$agno_comunidad', sector = $sector, vivienda = $vivienda, politica_habitacional = '$politica_habitacional', seguro = '$seguro', vehiculo = '$vehiculo', terreno = '$terreno', ubicacion_terreno = '$ubicacion_terreno', padece_enfermedad = '$padece_enfermedad', cual_enfermedad = '$cual_enfermedad', requiere_medicamento_porvida = '$requiere_medicamento_porvida', cual_tratamiento = '$cual_tratamiento', requiere_material_medico = '$requiere_material_medico', cual_material_medico = '$cual_material_medico', requiere_operacion = '$requiere_operacion', cual_operacion = '$cual_operacion', requiere_servicio_salud = '$requiere_servicio_salud', cual_servicio_salud = '$cual_servicio_salud', embarazada = '$embarazada', tabla20_campo31 = '$tabla20_campo31', tabla20_campo32 = '$tabla20_campo32', tabla20_campo33 = '$tabla20_campo33', tabla20_campo34 = '$tabla20_campo34', tabla20_campo35 = '$tabla20_campo35', tabla20_campo36 = '$tabla20_campo36', tabla20_campo37 = '$tabla20_campo37', tabla20_campo38 = '$tabla20_campo38', tabla20_campo39 = '$tabla20_campo39', tabla20_campo40 = '$tabla20_campo40', tabla20_campo41 = '$tabla20_campo41', tabla20_campo42 = '$tabla20_campo42', tabla20_campo43 = '$tabla20_campo43', tabla20_campo44 = '$tabla20_campo44', tabla20_campo45 = '$tabla20_campo45', tabla20_campo46 = '$tabla20_campo46', tabla20_campo47 = '$tabla20_campo47', tabla20_campo48 = '$tabla20_campo48', tabla20_campo49 = '$tabla20_campo49', tabla20_campo50 = '$tabla20_campo50', tabla20_campo51 = '$tabla20_campo51', tabla20_campo52 = '$tabla20_campo52', tabla20_campo53 = '$tabla20_campo53', tabla20_campo54 = '$tabla20_campo54', tabla20_campo55 = '$tabla20_campo55', tabla20_campo56 = '$tabla20_campo56', tabla20_campo57 = '$tabla20_campo57', tabla20_campo58 = '$tabla20_campo58', tabla20_campo59 = '$tabla20_campo59', tabla20_campo60 = '$tabla20_campo60', tabla20_campo61 = '$tabla20_campo61', tabla20_campo62 = '$tabla20_campo62', tabla20_campo63 = '$tabla20_campo63', tabla20_campo64 = '$tabla20_campo64', tabla20_campo65 = '$tabla20_campo65', tabla20_campo66 = '$tabla20_campo66', tabla20_campo67 = '$tabla20_campo67', tabla20_campo68 = '$tabla20_campo68' WHERE id = $id";
        try
        {
//             if (!$this->fields->destino->getNewValue()) {
//                 $this->error("El campo Destino es Obligatorio");
//                 return;
//             }
            $p4a_db = P4A_DB::singleton();
            /** verificar sí existe el registro **/
            $this->user_data = $p4a_db->fetchRow("SELECT count(*) AS existe FROM encuestas.censo WHERE cedula = '$cedula'");
//             if ($this->user_data['existe'] > 1) {
//                 $this->warning("la cédula ya está registrada ".$this->user_data['existe']);
//                 return;
//             }
            if (!$this->user_data['existe'])
            {
//                 $this->user_data = $p4a_db->fetchRow("SELECT nextval ('viatico_".$id_dependencia."_seq'::regclass)");
$this->info("Insert: $queryInsert");
//                 $p4a_db->query($queryInsert);
            }else
                $p4a_db->query($queryUpdate);
// $this->info("Update: $queryUpdate");

            $this->info("Registro Guardado");

            // auditoria
            $p4a_db->query("create temp table audit_tmp (usuario integer, ip inet, ocurrencia timestamp default now());");
            $p4a_db->query("insert into audit_tmp values ('".$_SESSION['id']."', '".$_SERVER['REMOTE_ADDR']."');");

//             $this->warning("entra 1:$id - 2:$cedula - 3:$nombre - 4:$apellido - 5:edad - 6:$fecha_nacimiento");
//             $this->warning("entra 7:$sexo - 8:$grado_instruccion - 9:sin_documentos- 10:$estudia - 11:fregistro - 12:activo");
//             $this->warning("entra 13:$trabaja - 14:$correo - 15:$terreno - 16:$ubicacion_terreno - 17:$politica_habitacional - 18:$seguro");
//             $this->warning("entra 19:$vehiculo - sector:$sector - ocupac:$ocupacion - prof:$profesion - parent:$parentesco");
//             $this->warning("entra 41:$tabla20_campo41 - 42: - 43:$tabla20_campo43- prof:$profesion - parent:$parentesco");
//             parent::saveRow();
//             return;
        } catch (Exception $e)
        {
            $this->error('mensaje:'.$e->getMessage().' codigo:'.$e->getCode());
//             $this->error('mensaje:'.$e->getMessage().' código:'.$e->getCode().' trace:'.$e->getTrace().' traceString:'.$e->getTraceAsString());
            $this->error("Imposible guardar");
        }
    }
}
