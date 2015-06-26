<?php

//ajuntar la libreria excel
include "./lib/PHPExcel.php";

require("../ez_sql_core.php");
require("../ez_sql_postgresql.php");


$objPHPExcel = new PHPExcel(); //nueva instancia

$objPHPExcel->getProperties()->setCreator("SIFDA"); //autor
$objPHPExcel->getProperties()->setTitle("Prueba para generar excel"); //titulo

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda24022015', 'localhost');

$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_tipo = $_REQUEST['tp'];
$dep = $_REQUEST['dep'];



//inicio estilos
$titulo = new PHPExcel_Style(); //nuevo estilo
$titulo->applyFromArray(
  array('alignment' => array( //alineacion
      'wrap' => false,
      'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER
    ),
    'font' => array( //fuente
      'bold' => true,
      'size' => 20
    )
));

$subtitulo = new PHPExcel_Style(); //nuevo estilo

$subtitulo->applyFromArray(
  array('fill' => array( //relleno de color
      'type' => PHPExcel_Style_Fill::FILL_SOLID,
      //'color' => array('argb' => 'FFCCFFCC')
      'color' => array('argb' => 'CCFFFF')
    ),
    'borders' => array( //bordes
      'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
      'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
      'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
      'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    )
));

$bordes = new PHPExcel_Style(); //nuevo estilo

$bordes->applyFromArray(
  array('borders' => array(
      'top' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
      'right' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
      'bottom' => array('style' => PHPExcel_Style_Border::BORDER_THIN),
      'left' => array('style' => PHPExcel_Style_Border::BORDER_THIN)
    )
));
//fin estilos

$objPHPExcel->createSheet(0); //crear hoja
$objPHPExcel->setActiveSheetIndex(0); //seleccionar hora
$objPHPExcel->getActiveSheet()->setTitle("Solicitudes Rechazadas"); //establecer titulo de hoja

//orientacion hoja
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

//tipo papel
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);

//establecer impresion a pagina completa
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
$objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
//fin: establecer impresion a pagina completa

//establecer margenes
$margin = 0.5 / 2.54; // 0.5 centimetros
$marginBottom = 1.2 / 2.54; //1.2 centimetros
$objPHPExcel->getActiveSheet()->getPageMargins()->setTop($margin);
$objPHPExcel->getActiveSheet()->getPageMargins()->setBottom($marginBottom);
$objPHPExcel->getActiveSheet()->getPageMargins()->setLeft($margin);
$objPHPExcel->getActiveSheet()->getPageMargins()->setRight($margin);
//fin: establecer margenes

//incluir una imagen
$objDrawing = new PHPExcel_Worksheet_Drawing();
//$objDrawing->setPath('img/logo.png'); //ruta
$objDrawing->setPath('../banner.png'); //ruta
$objDrawing->setHeight(75); //altura
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet()); //incluir la imagen
//fin: incluir una imagen

//establecer titulos de impresion en cada hoja
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);


$fila=6;
$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", "Reporte de Solicitudes Rechazadas");
$objPHPExcel->getActiveSheet()->mergeCells("A$fila:I$fila"); //unir celdas
$objPHPExcel->getActiveSheet()->setSharedStyle($titulo, "A$fila:I$fila"); //establecer estilo

//titulos de columnas
$fila+=1;

$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", 'Num');
$objPHPExcel->getActiveSheet()->SetCellValue("B$fila", 'Razón de rechazo');
$objPHPExcel->getActiveSheet()->SetCellValue("C$fila", 'Total');
$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "A$fila:B$fila"); //establecer estilo
$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "C$fila"); 
$objPHPExcel->getActiveSheet()->getStyle("A$fila:B$fila:C$fila")->getFont()->setBold(true); //negrita
$objPHPExcel->getActiveSheet()->getStyle("C$fila")->getFont()->setBold(true);
 $fila1 = $fila1 +7;
 $total1 = 0;
 ////////////////
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_tipo = $_REQUEST['tp'];
$dep = $_REQUEST['dep'];


$datos0 = $conexion->get_results("SELECT DISTINCT(sts.id),sts.nombre 
  FROM public.sifda_solicitud_servicio ss
    inner join public.fos_user_user us on (us.id = ss.user_id)
inner join public.ctl_dependencia_establecimiento dep on (dep.id = us.id_dependencia_establecimiento)
inner join public.sifda_tipo_servicio sts on (sts.id = ss.id_tipo_servicio)
inner join public.ctl_dependencia de on (de.id = 23)
inner join sifda_solicitud_rechazada sr on (ss.id = sr.id_solicitud_servicio)
inner join catalogo_detalle cd on (sr.id_razon_rechazo = cd.id)
where id_estado=3 
and fecha_recepcion >= '2015-01-01' and fecha_recepcion <='2015-12-12'
");


 $suma = 0;
foreach ($datos0 as $value1) {
  $datos = $conexion->get_results("SELECT sts.nombre,cd.descripcion,count(*) as cuenta 
  FROM public.sifda_solicitud_servicio ss
    inner join public.fos_user_user us on (us.id = ss.user_id)
inner join public.ctl_dependencia_establecimiento dep on (dep.id = us.id_dependencia_establecimiento)
inner join public.sifda_tipo_servicio sts on (sts.id = ss.id_tipo_servicio)
inner join public.ctl_dependencia de on (de.id = 23)
inner join sifda_solicitud_rechazada sr on (ss.id = sr.id_solicitud_servicio)
inner join catalogo_detalle cd on (sr.id_razon_rechazo = cd.id)
where id_estado=3 
and fecha_recepcion >= '2015-01-01' and fecha_recepcion <='2015-12-12'
"." and sts.id=".$value1->id.
"group by sts.nombre,cd.descripcion
");
 $dsc = "                  Tipo de servicio:            ".$value1->nombre;
  $item = 0;
  $suma2 = 0;
 
 
//$fila=fila1;
  $fila1 = $fila1 +1;
$objPHPExcel->getActiveSheet()->SetCellValue("A$fila1", "$dsc");
$objPHPExcel->getActiveSheet()->mergeCells("A$fila1:B$fila1"); //unir celdas
 /////////////////////
 
 
 foreach ($datos as $value1) {
 
    $item = $item +1;
    $fila1 = $fila1 +1;
    $suma2 = $suma2 + $value1->cuenta;
    $objPHPExcel->getActiveSheet()->SetCellValue("A$fila1","$item");
    $objPHPExcel->getActiveSheet()->SetCellValue("B$fila1", "$value1->descripcion");
    $objPHPExcel->getActiveSheet()->SetCellValue("C$fila1", "$value1->cuenta");
    $total1 = $total1 + $value1->cuenta;
    //$pdf->Ln(7);
 }
}
 $fila1 = $fila1 +1;
 $objPHPExcel->getActiveSheet()->getStyle("B$fila1:C$fila1")->getFont()->setBold(true);
 $objPHPExcel->getActiveSheet()->SetCellValue("B$fila1", "TOTAL");
 $objPHPExcel->getActiveSheet()->SetCellValue("C$fila1", "$total1");
  
//recorrer las columnas
foreach (range('A', 'B', 'C') as $columnID) {
  //autodimensionar las columnas
  $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);  
}

//establecer pie de impresion en cada hoja
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&F página &P / &N');


// Guardar como excel 2007
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); //Escribir archivo

// Establecer formado de Excel 2007
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// nombre del archivo
header('Content-Disposition: attachment; filename="Solicitudes Rechazadas.xlsx"');

//forzar a descarga por el navegador
$objWriter->save('php://output');

