<?php
define('FPDF_FONTPATH','fpdf/font/');

//above line is import to define, otherwise it gives an error : Could not include font metric file

require('fpdf/fpdf.php');
require("ez_sql_core.php");
require("ez_sql_postgresql.php");

class PDF extends FPDF
{
// Cabecera de página
function Header()
{
$this->Image('banner.png', 10,8,150,20);
$this->SetFont('Arial','B',11);
$this->Cell(330,-6,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
$this->Ln(2);
$this->Cell(180,1,'Fecha:' .date('d-m-y'). "", 0, 0, 'R', false);
$this->Ln(2);
$this->Cell(180,8,'Hora:' .  gettimeofday(). "", 0, 0, 'R', false);

$this->Ln(20);
$this->SetFont('Arial','B',11);
$this->Cell(0,25,utf8_decode('Reporte de Solicitudes de servicio por estado segun dependencia X'), 0, 0, 'C', false);
$this->Ln(20);
//$this->Cell(15,7,utf8_decode('Numd'),1,0,'C');
$this->Cell(85,7,utf8_decode('Estados'),1,0,'C');
$this->Cell(75,7,utf8_decode('Num. de solicitudes'),1,0,'C');
//$this->Cell(35,7,utf8_decode('Fecha requiere'),1,0,'C');
//$this->Cell(35,7,utf8_decode('Fecha recepción'),1,0,'C');
$this->Ln(7);
//$this->SetFont('Arial','',11);
}

// Pie de página
function Footer()
{
    // Posición: a 1,5 cm del final
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Número de página
    $this->Cell(0,10,'Ministerio de Salud',0,0,'C');
}

}

$pdf = new PDF('P','mm','Letter');
//$pdf->AddPage("P","Letter");
$pdf->AliasNbPages();
//pag 1
$pdf->SetMargins(20,18);
$pdf->AddPage("P","Letter");
$pdf->SetFont('Arial','',11);

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda31012015', 'localhost');
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
//$datos = $conexion->get_row("SELECT descripcion FROM public.sifda_solicitud_servicio where descripcion = 'test1'");
//$datos = $conexion->get_results("SELECT descripcion,fecha_requiere,fech                                                                                                                                                   a_recepcion FROM public.sifda_solicitud_servicio"); 
//$datos = $conexion->get_results("SELECT id,descripcion, fecha_recepcion, fecha_requiere FROM public.sifda_solicitud_servicio where id_estado =2");                
if ($temp_ff ==0 and $temp_fi ==0)
    {$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1");
     $asig = $conexion->get_results("SELECT count(id) as casig FROM public.sifda_solicitud_servicio where id_estado = 2");
     $recha = $conexion->get_results("SELECT count(id) as crecha FROM public.sifda_solicitud_servicio where id_estado = 3");
     $fin = $conexion->get_results("SELECT count(id) as cfin FROM public.sifda_solicitud_servicio where id_estado = 4");
    }
else
    {$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1 and fecha_recepcion between '$temp_fi' and '$temp_ff'");
     $asig = $conexion->get_results("SELECT count(id) as casig FROM public.sifda_solicitud_servicio where id_estado = 2 and fecha_recepcion between '$temp_fi' and '$temp_ff'");
     $recha = $conexion->get_results("SELECT count(id) as crecha FROM public.sifda_solicitud_servicio where id_estado = 3 and fecha_recepcion between '$temp_fi' and '$temp_ff'");
     $fin = $conexion->get_results("SELECT count(id) as cfin FROM public.sifda_solicitud_servicio where id_estado = 4 and fecha_recepcion between '$temp_fi' and '$temp_ff'");
    }
 foreach ($ing as $value1) {
  $item = $item +1;
    $pdf->Cell(85,7,utf8_decode('Ingresadas'),1);
    $pdf->Cell(75,7,utf8_decode($value1->cing),1,0,'C');
    $pdf->Ln(7);
 }
 foreach ($asig as $value2) {
    $pdf->Cell(85,7,utf8_decode('Asignadas'),1);
    $pdf->Cell(75,7,utf8_decode($value2->casig),1,0,'C');
    $pdf->Ln(7);
}
foreach ($recha as $value3) {
    $pdf->Cell(85,7,utf8_decode('Rechazadas'),1);
    $pdf->Cell(75,7,utf8_decode($value3->crecha),1,0,'C');
    $pdf->Ln(7);
}
foreach ($fin as $value4) {
    $pdf->Cell(85,7,utf8_decode('Finalizadas'),1);
    $pdf->Cell(75,7,utf8_decode($value4->cfin),1,0,'C');
    $pdf->Ln(7);
}
    $pdf->Cell(85,7,utf8_decode('Total de solicitudes'),1);
    $total = $value1->cing + $value2->casig + $value3->crecha + $value4->cfin;
    $pdf->Cell(75,7,utf8_decode($total),1,0,'C');
   
$pdf->Output();
/*
$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1");

foreach ($ing as $value1) {
  $item = $item +1;
    //$row = $row -1;
    $pdf->Cell(85,7,utf8_decode('Ingresadas'),1);
    $pdf->Cell(75,7,utf8_decode($value1->cing),1,0,'C');
    //$pdf->Cell(75,7,utf8_decode($value->descripcion),1,0,'C');
    //$pdf->Cell(35,7,utf8_decode($value->fecha_requiere),1,0,'C');
    //$pdf->Cell(35,7,utf8_decode($value->fecha_recepcion),1,0,'C');
    $pdf->Ln(7);  
}
$asig = $conexion->get_results("SELECT count(id) as casig FROM public.sifda_solicitud_servicio where id_estado = 2");
//$asigval=  array_pop($asig);
foreach ($asig as $value2) {
    //$asigval= $value2->cuenta;
    $pdf->Cell(85,7,utf8_decode('Asignadas'),1);
    $pdf->Cell(75,7,utf8_decode($value2->casig),1,0,'C');
    $pdf->Ln(7);
}
$recha = $conexion->get_results("SELECT count(id) as crecha FROM public.sifda_solicitud_servicio where id_estado = 3");
//$rechaval=  array_shift($recha[0]);
foreach ($recha as $value3) {
    $pdf->Cell(85,7,utf8_decode('Rechazadas'),1);
    $pdf->Cell(75,7,utf8_decode($value3->crecha),1,0,'C');
    $pdf->Ln(7);
}
$fin = $conexion->get_results("SELECT count(id) as cfin FROM public.sifda_solicitud_servicio where id_estado = 4");
//$finval=  array_shift($fin[0]);
foreach ($fin as $value4) {
    $pdf->Cell(85,7,utf8_decode('Finalizadas'),1);
    $pdf->Cell(75,7,utf8_decode($value4->cfin),1,0,'C');
    $pdf->Ln(7);
}
    $pdf->Cell(85,7,utf8_decode('Total de solicitudes'),1);
    $total = $value1->cing + $value2->casig + $value3->crecha + $value4->cfin;
    $pdf->Cell(75,7,utf8_decode($total),1,0,'C');
   
$pdf->Output();
 *
 */
?>