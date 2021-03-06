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

foreach ($unidad as $value2) {
    $nombre1=$value2->nombre;

}

$depest = $conexion->get_results("SELECT est.nombre
  FROM ctl_dependencia_establecimiento det 
  join ctl_dependencia dep on (dep.id = det.id_dependencia)
  join ctl_establecimiento est on (det.id_establecimiento = est.id)
  where det.id = $temp_tdest");

foreach ($depest as $value3) {
    $nombre2=$value3->nombre;

}

$rechazo = $conexion->get_results("SELECT cd.descripcion,count(*) as cuenta 
  FROM public.sifda_solicitud_servicio ss
    inner join public.fos_user_user us on (us.id = ss.user_id)
inner join public.ctl_dependencia_establecimiento dep on (dep.id = us.id_dependencia_establecimiento)
inner join public.sifda_tipo_servicio sts on (sts.id = ss.id_tipo_servicio)
inner join public.ctl_dependencia de on (de.id = 23)
inner join sifda_solicitud_rechazada sr on (ss.id = sr.id_solicitud_servicio)
inner join catalogo_detalle cd on (sr.id_razon_rechazo = cd.id)
where id_estado=3 
and fecha_recepcion >= '$temp_fi' and fecha_recepcion <='$temp_ff'
group by cd.descripcion");

$descripcion =array();
$cuenta =array();

//$ing = $conexion->get_results("SELECT count(id) as cing FROM public.sifda_solicitud_servicio where id_estado = 1");
//$ingval= array_pop($ing);
foreach ($rechazo as $value1) {
    $descripcion[]=$value1->descripcion;
    $cuenta[]=$value1->cuenta;
}


$graph = new PieGraph(700,500,'auto');

//$graph->img->SetAntiAliasing();

$graph->SetMarginColor('white');

//$graph->SetShadow();

// Setup margin and titles

//$graph->title->Set('Estados de solicitudes',$temp_udep);
$graph->title->Set($nombre1);
$graph->subtitle->Set('Periodo del ' .$temp_fi .' al ' .$temp_ff);
$graph->subsubtitle->Set($nombre2);

$p1 = new PiePlot3D($cuenta);

$p1->SetSize(0.35);

$p1->SetCenter(0.5);

$p1->value->SetFont(FF_FONT1,FS_BOLD);

$p1->value->SetColor('black');

$p1->SetLabelPos(0.2);

//$nombres=array('Ingresados','Asignado','Rechazado','Finalizado');

$p1->SetLegends($descripcion);

// Explode all slices

$p1->ExplodeAll();

$graph->Add($p1);

$graph->Stroke();

?>
