<?php
 require_once ('jpgraph/src/jpgraph.php');
 require_once ('jpgraph/src/jpgraph_bar.php');
 
// Creamos el grafico
$datos=array(6,5,8,6);
$labels=array("pepe","juanita","Maria","Luis");

$grafico = new Graph(500, 400, 'auto');
$grafico->SetScale("textint");
$grafico->title->Set("Ejemplo de Grafica");
$grafico->xaxis->title->Set("trabajadores");
$grafico->xaxis->SetTickLabels($labels);
$grafico->yaxis->title->Set("Horas Trabajadas");
$barplot1 =new BarPlot($datos);
// Un gradiente Horizontal de morados
$barplot1->SetFillGradient("#BE81F7", "#E3CEF6", GRAD_HOR);
// 30 pixeles de ancho para cada barra
$barplot1->SetWidth(30);
$grafico->Add($barplot1);
$grafico->Stroke();
?>