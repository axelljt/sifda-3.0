<?php

//ajuntar la libreria excel
include "./lib/PHPExcel.php";

require("../ez_sql_core.php");
require("../ez_sql_postgresql.php");


$objPHPExcel = new PHPExcel(); //nueva instancia

$objPHPExcel->getProperties()->setCreator("SIFDA"); //autor
$objPHPExcel->getProperties()->setTitle("Prueba para generar excel"); //titulo

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda12022015', 'localhost');
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_tdest = $_REQUEST['tdest'];
if ($temp_ff ==0 and $temp_fi ==0)
    {$datos = $conexion->get_results("select distinct(e.id),e.nombre|| ' ' ||e.apellido as nombre,count(distinct id_orden) as atendidas,
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado) as pendientes,
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 4 and v.id_empleado = vw.id_empleado) as finalizadas
from ctl_empleado e left outer join vwetapassolicitud vw on e.id = vw.id_empleado
where e.id_dependencia_establecimiento = $temp_tdest
group by e.id,e.nombre|| ' ' ||e.apellido,(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado),
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 4 and v.id_empleado = vw.id_empleado)
order by (select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado) desc");
        
    }
else
    {$datos = $conexion->get_results("select distinct(e.id),e.nombre|| ' ' ||e.apellido as nombre,count(distinct id_orden) as atendidas,
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado) as pendientes,
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 4 and v.id_empleado = vw.id_empleado) as finalizadas
from ctl_empleado e left outer join vwetapassolicitud vw on e.id = vw.id_empleado
where fchcrea_orden >= $temp_fi and fchcrea_orden <= $temp_ff
and e.id_dependencia_establecimiento = $temp_tdest
group by e.id,e.nombre|| ' ' ||e.apellido,(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado),
(select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 4 and v.id_empleado = vw.id_empleado)
order by (select count(distinct v.id_orden) from vwetapassolicitud v where v.id_estado = 2 and v.id_empleado = vw.id_empleado) desc");
   
    }

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
$objPHPExcel->getActiveSheet()->setTitle("Carga laboral por técnico"); //establecer titulo de hoja

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
$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", "Reporte de Carga Laboral por Técnico");
$objPHPExcel->getActiveSheet()->mergeCells("A$fila:I$fila"); //unir celdas
$objPHPExcel->getActiveSheet()->setSharedStyle($titulo, "A$fila:I$fila"); //establecer estilo

//titulos de columnas
$fila+=1;

$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", 'Num');
$objPHPExcel->getActiveSheet()->SetCellValue("B$fila", 'Nombre');
$objPHPExcel->getActiveSheet()->SetCellValue("C$fila", 'Pendientes');
$objPHPExcel->getActiveSheet()->SetCellValue("D$fila", 'Finalizadas');
$objPHPExcel->getActiveSheet()->SetCellValue("E$fila", 'Atendidas');
$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "A$fila:B$fila"); //establecer estilo
$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "C$fila:D$fila");
$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "E$fila");
$objPHPExcel->getActiveSheet()->getStyle("A$fila:B$fila")->getFont()->setBold(true); //negrita
$objPHPExcel->getActiveSheet()->getStyle("C$fila:D$fila")->getFont()->setBold(true); //negrita
$objPHPExcel->getActiveSheet()->getStyle("E$fila")->getFont()->setBold(true); //negrita
//rellenar con contenido
/*for ($i = 0; $i < 10; $i++) {
  $fila+=1;
  $objPHPExcel->getActiveSheet()->SetCellValue("A$fila", 'Garabatos');
  $objPHPExcel->getActiveSheet()->SetCellValue("B$fila", 'Linux');

  //Establecer estilo
  $objPHPExcel->getActiveSheet()->setSharedStyle($bordes, "A$fila:B$fila");
  
}*/
foreach ($datos as $value) {
  $item = $item +1;
  $fila+=1;
    //$row = $row -1;
    //$pdf->Cell(15,7,utf8_decode($item),1);
    //$pdf->Cell(15,7,utf8_decode($value->id),1,0,'C');
    $objPHPExcel->getActiveSheet()->SetCellValue("A$fila","$item");
    $objPHPExcel->getActiveSheet()->SetCellValue("B$fila","$value->nombre");
    $objPHPExcel->getActiveSheet()->SetCellValue("C$fila","$value->pendientes");
    $objPHPExcel->getActiveSheet()->SetCellValue("D$fila","$value->finalizadas");
    $objPHPExcel->getActiveSheet()->SetCellValue("E$fila","$value->atendidas");
    //$objPHPExcel->getActiveSheet()->SetCellValue("F$fila","$value->fecha_recepcion");
      //Establecer estilo
  $objPHPExcel->getActiveSheet()->setSharedStyle($bordes, "A$fila:B$fila:C$fila:D$fila:E$fila"); 
}

//insertar formula
//$fila+=2;
//$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", 'SUMA');
//$objPHPExcel->getActiveSheet()->SetCellValue("B$fila", '=1+2');

//recorrer las columnas
foreach (range('A', 'B','C','D') as $columnID) {
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
header('Content-Disposition: attachment; filename="cargaPorTecnico.xlsx"');

//forzar a descarga por el navegador
$objWriter->save('php://output');
