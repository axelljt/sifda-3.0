<?php

require ('jpgraph/src/jpgraph.php');

require ('jpgraph/src/jpgraph_pie.php');

require ('jpgraph/src/jpgraph_pie3d.php');

$data = array(40,60,21,33);

$graph = new PieGraph(400,400,'auto');

//$graph->img->SetAntiAliasing();

$graph->SetMarginColor('gray');

//$graph->SetShadow();

// Setup margin and titles

$graph->title->Set('Ejemplo: Horas de Trabajo');

$p1 = new PiePlot3D($data);

$p1->SetSize(0.35);

$p1->SetCenter(0.5);

// Setup slice labels and move them into the plot

$p1->value->SetFont(FF_FONT1,FS_BOLD);

$p1->value->SetColor('black');

$p1->SetLabelPos(0.2);

$nombres=array('pepe','luis','miguel','alberto');

$p1->SetLegends($nombres);

// Explode all slices

$p1->ExplodeAll();

$graph->Add($p1);

$graph->Stroke();

?>
