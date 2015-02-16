<?php
define('FPDF_FONTPATH','fpdf/font/');

//above line is import to define, otherwise it gives an error : Could not include font metric file

require('fpdf/fpdf.php');
require("ez_sql_core.php");
require("ez_sql_postgresql.php");


$pdf = new FPDF('P','cm','Letter');
//$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(1,1.5,1);
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,utf8_decode('¡Prueba Reporte Solicitud!'));

//$cadconex="dbname=sifda08122014 host=127.0.0.1 port=5432 user=sifda password=sifda";
//$conexion = pg_connect($cadconex);

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda08122014', 'localhost');
 
//$datos = pg_query ($conexion, "SELECT descripcion FROM sifda_solicitud_servicio where descripcion = 'test1'");
//$datos = pg_query ($conexion, "SELECT * FROM sifda_solicitud_servicio");
//$totales = pg_num_rows($datos);
//$pdf->Cell(40,10,utf8_decode('¡Prueba Reporte Solicitud!'));
//$columnas=pg_num_fields($datos);

$datos = $conexion->get_row("SELECT descripcion FROM public.sifda_solicitud_servicio where descripcion = 'test1'");
 
//$pdf->SetFont('Helvetica', 'B', 9);
//$pdf->Cell(2.3, 0.5, 'Resultado:', 0, 0, 'R', false);
//$pdf->SetFont('Helvetica', '', 10);
//$pdf->Cell(9.6, 0.5, utf8_decode($datos->descripcion), 0, 0, 'L', false);
//$valor= pg_field_name($datos,0);
//while($fila=pg_fetch_array($datos)){
//for($i=0 ; $i < $columnas ; $i++) {
//$pdf->Cell(50,20,$valor,1,0);
$pdf->SetXY(2, 4.5);
//$pdf->Cell(40,10,$valor);
$pdf->Cell(50,15,$datos->descripcion);


   // }
//}
$pagright = 5;
$heightBase = 6.5;
$interline = 0.6;
$pdf->Image('indice.jpg', $pagright+3.4,1.5,5);
$pdf->setXY($pagright,$heightBase);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(11.9, 6, utf8_decode('Prueba'), 0, 0, 'C', false);
$pdf->setXY($pagright,$heightBase+$interline);
$pdf->Cell(11.9, 6, utf8_decode($datos->descripcion . ' Report'), 0, 0, 'C', false);
$pdf->setXY($pagright,($pdf->GetY()+$interline));
$pdf->Cell(11.9, 6, date('F Y'), 0, 0, 'C', false);
$pdf->setXY($pagright,($pdf->GetY()+$interline));

$pdf->SetFont('Helvetica', '', 8);
$pdf->MultiCell(2.3, 0.4, 'Esto es un multicell', 0, 'R', false);
//$pdf->Cell(11.9, 6, $datos->descripcion', 0, 0, 'C', false);
$pdf->Output();
?>