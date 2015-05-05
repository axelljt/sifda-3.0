<?php

require('jpgraph/src/jpgraph.php');
require('jpgraph/src/jpgraph_pie.php');
require('jpgraph/src/jpgraph_pie3d.php');
require("ez_sql_core.php");
require("ez_sql_postgresql.php");

//$data = array(40,60,21,33);

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda24022015', 'localhost');
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_tdest = $_REQUEST['tdest'];
$temp_udep = $_REQUEST['dep'];
if ($temp_ff ==0 and $temp_fi ==0)
    {$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1 and id_dependencia_establecimiento = $temp_tdest");
     $asig = $conexion->get_results("SELECT count(id) as casig FROM public.sifda_solicitud_servicio where id_estado = 2 and id_dependencia_establecimiento = $temp_tdest");
     $recha = $conexion->get_results("SELECT count(id) as crecha FROM public.sifda_solicitud_servicio where id_estado = 3 and id_dependencia_establecimiento = $temp_tdest");
     $fin = $conexion->get_results("SELECT count(id) as cfin FROM public.sifda_solicitud_servicio where id_estado = 4 and id_dependencia_establecimiento = $temp_tdest");
    }
else
    {$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1 and fecha_recepcion between '$temp_fi' and '$temp_ff' and id_dependencia_establecimiento = $temp_tdest");
     $asig = $conexion->get_results("SELECT count(id) as casig FROM public.sifda_solicitud_servicio where id_estado = 2 and fecha_recepcion between '$temp_fi' and '$temp_ff' and id_dependencia_establecimiento = $temp_tdest");
     $recha = $conexion->get_results("SELECT count(id) as crecha FROM public.sifda_solicitud_servicio where id_estado = 3 and fecha_recepcion between '$temp_fi' and '$temp_ff' and id_dependencia_establecimiento = $temp_tdest");
     $fin = $conexion->get_results("SELECT count(id) as cfin FROM public.sifda_solicitud_servicio where id_estado = 4 and fecha_recepcion between '$temp_fi' and '$temp_ff' and id_dependencia_establecimiento = $temp_tdest");
    }

//$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1");
//$ingval= array_pop($ing);
foreach ($ing as $value1) {
    $value1->cing;
}
//ladybug_dump("$ingval");
//$asig = $conexion->get_results("SELECT count(id) as casig FROM public.sifda_solicitud_servicio where id_estado = 2");
//$asigval=  array_pop($asig);
foreach ($asig as $value2) {
    $value2->casig;
}
//$recha = $conexion->get_results("SELECT count(id) as crecha FROM public.sifda_solicitud_servicio where id_estado = 3");
//$rechaval=  array_shift($recha[0]);
foreach ($recha as $value3) {
    $value3->crecha;
}
//$fin = $conexion->get_results("SELECT count(id) as cfin FROM public.sifda_solicitud_servicio where id_estado = 4");
//$finval=  array_shift($fin[0]);
foreach ($fin as $value4) {
    $value4->cfin;
}
$data = array($value1->cing,$value2->casig,$value3->crecha,$value4->cfin);
//$data = array(28,35,99,99);
//print_r($value1);
//print_r($data);
//echo "foo es $ingval";
/*  $max = $data[0]; 
  
  for ($i = 0; $i < 3; $i++) 
    if ($data[i] > max) 
	  $max = $data[i];*/
//print_r(max($data));



//$data = array($value1->cuenta,$value2->cuenta,$value3->cuenta,$value4->cuenta);
//$data = array($ing,$asig,$recha,$fin);
$graph = new PieGraph(700,500,'auto');

//$graph->img->SetAntiAliasing();

$graph->SetMarginColor('white');

//$graph->SetShadow();

// Setup margin and titles

//$graph->title->Set('Estados de solicitudes',$temp_udep);
$graph->title->Set($temp_udep);
$p1 = new PiePlot3D($data);

$p1->SetSize(0.35);

$p1->SetCenter(0.5);

// Setup slice labels and move them into the plot

//$p1->SetSliceColors(array('red','green','blue','pink')); 
$max = max($data);
//if($value1->cing == $max){
if($value1->cing == $max){
    $p1->SetSliceColors(array('red','green','blue','pink'));
}
else if($value2->casig == $max){
    $p1->SetSliceColors(array('green','red','blue','pink'));
}
else if($value3->crecha == $max){
    $p1->SetSliceColors(array('green','blue','red','pink'));
}
else {
    $p1->SetSliceColors(array('green','blue','pink','red'));
}

$p1->value->SetFont(FF_FONT1,FS_BOLD);

$p1->value->SetColor('black');

$p1->SetLabelPos(0.2);

$nombres=array('Ingresados','Asignado','Rechazado','Finalizado');

$p1->SetLegends($nombres);

// Explode all slices

$p1->ExplodeAll();

$graph->Add($p1);

$graph->Stroke();

?>
