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

$unidad = $conexion->get_results("SELECT dep.nombre FROM ctl_dependencia_establecimiento det 
  join ctl_dependencia dep on (dep.id = det.id_dependencia) where det.id = $temp_tdest");

$depest = $conexion->get_results("SELECT est.nombre
  FROM ctl_dependencia_establecimiento det 
  join ctl_dependencia dep on (dep.id = det.id_dependencia)
  join ctl_establecimiento est on (det.id_establecimiento = est.id)
  where det.id = $temp_tdest");

$ingresadas = $conexion->get_results("SELECT sts.nombre,count(sts.nombre) as cuenta
  FROM public.sifda_solicitud_servicio ss
    inner join public.fos_user_user us on (us.id = ss.user_id)
inner join public.ctl_dependencia_establecimiento dep on (dep.id = us.id_dependencia_establecimiento)
inner join public.sifda_tipo_servicio sts on (sts.id = ss.id_tipo_servicio)
inner join public.ctl_dependencia de on (de.id = 23)
where id_estado=1 
and fecha_recepcion >= '2015-02-01' and fecha_recepcion <='2015-05-01'
group by (sts.nombre)");

$nombre =array();
$cuenta =array();

//$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1");
//$ingval= array_pop($ing);
foreach ($ingresadas as $value1) {
    $nombre[]=$value1->nombre;
    $cuenta[]=$value1->cuenta;
}


$graph = new PieGraph(700,500,'auto');

//$graph->img->SetAntiAliasing();

$graph->SetMarginColor('white');

//$graph->SetShadow();

// Setup margin and titles

//$graph->title->Set('Estados de solicitudes',$temp_udep);
$graph->title->Set($temp_udep);
$p1 = new PiePlot3D($cuenta);

$p1->SetSize(0.35);

$p1->SetCenter(0.5);

// Setup slice labels and move them into the plot
/*
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
 * */
 

$p1->value->SetFont(FF_FONT1,FS_BOLD);

$p1->value->SetColor('black');

$p1->SetLabelPos(0.2);

//$nombres=array('Ingresados','Asignado','Rechazado','Finalizado');

$p1->SetLegends($nombre);

// Explode all slices

$p1->ExplodeAll();

$graph->Add($p1);

$graph->Stroke();

?>
