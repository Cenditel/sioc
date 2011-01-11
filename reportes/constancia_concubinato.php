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
ob_end_clean();
require_once("./fpdf/constancia_concubinato.class");

//$str_conexao='dbname=sioc_bd port=5432 user=usuario password=usuario';
$str_conexao='dbname='.P4A_DSN_DB.' port='.P4A_DSN_PORT.' user='.P4A_DSN_USER.' password='.P4A_DSN_PASSWORD.'';
$conexao=pg_connect($str_conexao) or die('Falló la conexión a BD!');

$queryFirmas = "SELECT (nombre||' '||apellido) AS persona, tabla12_campo2 AS unidad FROM cc1.tabla17 INNER JOIN encuestas.censo ON (tabla20_campo2=cedula) INNER JOIN cc1.tabla12 USING (tabla12_campo1) ORDER BY tabla12_campo2 ASC";
$consultaFirmas = pg_query($conexao, $queryFirmas);
$registrosFirmas = pg_fetch_object($consultaFirmas);
$vocero1 = $registrosFirmas->persona;
$registrosFirmas = pg_fetch_object($consultaFirmas);
$vocero2 = $registrosFirmas->persona;
$registrosFirmas = pg_fetch_object($consultaFirmas);
$vocero3 = $registrosFirmas->persona;

// variables
$ci = $_GET['ci'];
$ob = $_GET['ob'];
$query = "SELECT cedula, apellido, nombre, sector FROM encuestas.habitantes WHERE cedula = '$ci'";
$consulta = pg_query($conexao, $query);
$numrows = pg_num_rows($consulta);
/*
echo "sql: $query";
*/
//Creación del objeto de la clase heredada
$pdf=new cuerpoPDF('P','mm','Letter');
$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Times','',12);

if ($numrows > 0)
{
    $registros = pg_fetch_object($consulta);
    $cedula = $registros->cedula;
    $apellido = $registros->apellido;
    $nombre = $registros->nombre;
    $sector = $registros->sector;

    $texto = "Quienes suscriben miembros del Consejo Comunal \"\", ubicado en _______________, Parroquia _______________, Municipio _____________ del Estado Mérida, por medio de la presente certificamos que los Ciudadanos: $apellido, $nombre, de nacionalidad ______________, titular de la Cédula de Identidad $cedula y $apellido2, $nombre2, de nacionalidad ______________, titular de la Cédula de Identidad $cedula2 viven en Concubinato desde hace apróximadamente_________________, y residen en la Pedegosa Alta, sector: $sector.";

    $pdf->SetX($pdf->GetX()+10);
    $pdf->MultiCell(170,10,utf8_decode($texto),0);
    $pdf->Ln(5);

    $pdf->SetX($pdf->GetX()+10);
    $pdf->MultiCell(170,10,utf8_decode("Para fines: $ob"),0);
    $pdf->Ln(5);

    $pdf->SetX($pdf->GetX()+10);
    $pdf->MultiCell(170,10,utf8_decode("Certificación que se expide a solicitud de parte interesada a los ".date('d')." días del mes de ".date(F)." de ".date(Y)."."),0);
    $pdf->Ln(5);

    $x = $pdf->GetX();
    $y = $pdf->GetY();

    $pdf->Line($x+10,$y+20,$x+60,$y+20);
    $pdf->SetXY($x+10,$y+20);
    $pdf->Cell(50,5,$vocero3,0,1,'C');
    $pdf->SetXY($x+10,$y+25);
    $pdf->Cell(50,5,'Vocero Unidad Ejecutiva',0,0,'C');

    $pdf->Line($x+65,$y+50,$x+125,$y+50);
    $pdf->SetXY($x+70,$y+50);
    $pdf->Cell(50,5,$vocero1,0,1,'C');
    $pdf->SetXY($x+70,$y+55);
    $pdf->Cell(50,5,'Vocero Unidad Administrativa y Financiera',0,0,'C');

    $pdf->Line($x+130,$y+20,$x+180,$y+20);
    $pdf->SetXY($x+130,$y+20);
    $pdf->Cell(50,5,$vocero2,0,1,'C');
    $pdf->SetXY($x+130,$y+25);
    $pdf->Cell(50,5,utf8_decode('Vocero Unidad Contraloría Social'),0,0,'C');
}

$pdf->Output("Concubinato.pdf",'I');
?>
