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
class graficar_habitanes extends P4A_Base_Mask
{
    public $source = null;
    public $chart = null;

    public function __construct()
    {
        $p4a = p4a::singleton();
        parent::__construct();

        $this->setTitle("Gráfico de ...");
/*
        $this->toolbar->buttons->exit->setVisible(false);
*/

        $this->crearCampos();
        $this->propiedadesCampos();
        $this->loadsample();

        $this
            ->display("main", $this->frame);

    }

    public function consulta()
    {
        $p4a =& p4a::singleton();

        $temp = $p4a->masks->indicadores_habitantes->fld_campos->getNewValue();

        $temp1 = $p4a->masks->indicadores_habitantes->fld_campos->getLabel();
		$this->campo->setValue($temp1);

		switch($temp)
		{
			case 1:
				$titulo = "por Sexo";
$sql = <<<SQL
SELECT sexo as category, count(sexo) as qty
FROM encuestas.habitantes
WHERE sexo IS NOT NULL
GROUP BY sexo
ORDER BY sexo;
SQL;
				break;
			case 2:
				$titulo = "por Rangos de Edad (de 5 años)";
$sql = <<<SQL
 SELECT min(edad) || '-' || max(edad) AS category, count((edad+5)/5) as qty
FROM encuestas.habitantes
WHERE edad IS NOT NULL
GROUP BY (edad+5)/5
ORDER BY min(edad);
SQL;
			break;
			case 3:
				$titulo = "por Nivel Educativo";
$sql = <<<SQL
SELECT instruccion as category, count(instruccion) as qty
FROM encuestas.habitantes
WHERE instruccion IS NOT NULL
GROUP BY instruccion
ORDER BY instruccion;
SQL;
			break;
		}

        $this->build('p4a_db_source','source')
            ->setQuery($sql)
            ->load();

        // tabla de datos
        $this->build("p4a_table", "table")
            ->setSource($this->source)
            ->setWidth(800)
            ->showNavigationBar();

        $category = new ChartData('category');
        $category
            ->setSource($this->source)
            ->setType(Chart::BAR)
            ->setSourceLabelColumn('category')
            ->setSourceDataColumn('qty')
            ->showValues()
            ->setLabel('Categories');
        $this->chart
            ->setWidth(800)
            ->addData($category)
            ->showLegend()
            ->setLabel('Número de items '.$titulo);

        $this->build('p4a_fieldset', 'ventana')
            ->setLabel("Gráfico 2")
            ->anchor($this->campo)
            ->anchor($this->chart)
            ->anchor($this->table)
            ->anchor($this->apply);

        $this->frame
            ->anchor($this->ventana);
    }

    public function crearCampos()
    {
        // infoframe
        $this->build('p4a_field','enable');
        $this->build('p4a_field','campo');
        $this->build('Chart','chart');
        $this->build("P4A_Button", "apply");
    }

    private function propiedadesCampos()
    {
        // infoframe
        $this->enable
            ->setType('checkbox')
            ->setValue(true);

/*
		$temp = p4a::singleton()->masks->indicadores_habitantes->fld_campos->getNewValue();
        $this->campo->setValue($temp);
*/

        $this->apply
            ->setLabel("Cerrar Gráfico")
            ->implement("onclick", $this, "apply");
    }

    public function loadsample()
    {
        $this->chart->reset();
        $this->RefreshChart();
    }

    public function RefreshChart()
    {
        if ($this->enable->getNewValue() == true) {
            $this->chart->enable();
        }
        //
        $this->chart->redesign();
    }

    public function apply()
    {
        $p4a = P4A::singleton();
        $p4a->showPrevMask(true);
    }
}
