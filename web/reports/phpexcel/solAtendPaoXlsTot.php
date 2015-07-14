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
$linest = $_REQUEST['le'];
$act = $_REQUEST['a'];



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
$objPHPExcel->getActiveSheet()->setTitle("Solicitudes Atendidas PAO"); //establecer titulo de hoja

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
$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", "Reporte de Solicitudes Atendidas por PAO");
$objPHPExcel->getActiveSheet()->mergeCells("A$fila:H$fila"); //unir celdas
$objPHPExcel->getActiveSheet()->setSharedStyle($titulo, "A$fila:H$fila"); //establecer estilo
if($temp_fi != 0 and $temp_ff !=0){
    $fila+=1;
//  $this->SetFont('Arial','',11);
  $objPHPExcel->getActiveSheet()->SetCellValue("A$fila", "Período del ".$temp_fi.' al '.$temp_ff );
  $objPHPExcel->getActiveSheet()->mergeCells("A$fila:H$fila"); //unir celdas
  $objPHPExcel->getActiveSheet()->getStyle("A$fila:H$fila")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
}
//titulos de columnas
$fila+=1;

$objPHPExcel->getActiveSheet()->SetCellValue("A$fila", 'N');
$objPHPExcel->getActiveSheet()->SetCellValue("B$fila", 'Descripcion');
$objPHPExcel->getActiveSheet()->SetCellValue("C$fila", 'Meta');
$objPHPExcel->getActiveSheet()->SetCellValue("D$fila", 'Realizados');
$objPHPExcel->getActiveSheet()->SetCellValue("E$fila", 'Porcentaje(%)');
$objPHPExcel->getActiveSheet()->SetCellValue("F$fila", 'Costo');
$objPHPExcel->getActiveSheet()->SetCellValue("G$fila", 'Indicador');
$objPHPExcel->getActiveSheet()->SetCellValue("H$fila", 'Generado');
$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "A$fila:H$fila"); //establecer estilo
//$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "C$fila"); 
//$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "D$fila"); 
//$objPHPExcel->getActiveSheet()->setSharedStyle($subtitulo, "E$fila"); 
$objPHPExcel->getActiveSheet()->getStyle("A$fila:H$fila")->getFont()->setBold(true); //negrita
//$objPHPExcel->getActiveSheet()->getStyle("C$fila")->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle("D$fila")->getFont()->setBold(true);
//$objPHPExcel->getActiveSheet()->getStyle("E$fila")->getFont()->setBold(true);
 $fila1 = $fila1 +8;
 $total1 = 0;
 ////////////////
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_le = $_REQUEST['le'];
$temp_a = $_REQUEST['a'];
//
//
$SQL = "select id,descripcion,codigo,CASE WHEN activo = 't' THEN 'ACTIVO' ELSE 'INACTIVO' END as activo,recurrente "
            . "from sidpla_linea_estrategica a where anio =2015";
    if ($temp_le != 0){
        $SQL.=" and id = ".$temp_le;
        }
    $SQL.=";";
    //$datos = $conexion->get_results("SELECT descripcion,fecha_requiere,fecha_recepcion FROM public.sifda_solicitud_servicio where id_estado = 1 "); 
    $datos = $conexion->get_results($SQL);
foreach ($datos as $value) {
    $SQL2 = "select a.id as id,a.descripcion as descripcion,to_char(a.meta_anual, '9,990') as meta_anual,a.indicador as indicador,
            CASE WHEN a.generado = 't' THEN 'Si' ELSE 'No' END as generado,count(ss.id) as finalizadas,
            CASE WHEN count(ss.id) = 0 THEN to_char(0, '9,990.00') WHEN count(ss.id) > 0 THEN to_char(100 / a.meta_anual * count(ss.id), '9,990.00') END AS porcentaje,
            (select to_char(coalesce(sum (sri.costo_total),0), '9,990.00') from sifda_solicitud_servicio sss
left outer join sifda_orden_trabajo sot on sss.id = sot.id_solicitud_servicio
left outer join sifda_tipo_servicio sts on sts.id = sss.id_tipo_servicio
left outer join sifda_informe_orden_trabajo sio on sot.id = sio.id_orden_trabajo
left outer join sifda_recurso_servicio sri on sio.id = sri.id_informe_orden_trabajo where sts.id =s.id ";
    if ($temp_fi != null && $temp_ff != null) {
        $SQL2.=" and sss.fecha_finaliza between '" . $temp_fi . "' and '" . $temp_ff . "'";
    }
    $SQL2.=" and sss.id_estado = 4) as costo
                from sidpla_actividad a 
                left outer join sifda_tipo_servicio s on a.id = s.id_actividad
                left outer join sifda_solicitud_servicio ss on ss.id_tipo_servicio = s.id and ss.id_estado = 4";
    if ($temp_fi != null && $temp_ff != null) {
        $SQL2.=" and fecha_finaliza between '" . $temp_fi . "' and '" . $temp_ff . "'";
    }
    $SQL2.=" where id_linea_estrategica = " . $value->id;
    if ($temp_a != 0) {
        $SQL2.= " and a.id = " . $temp_a;
    }
    $SQL2.=" group by a.id,a.descripcion,a.meta_anual,a.indicador,a.generado,s.id,s.nombre;";
    $datos2 = $conexion->get_results($SQL2);
    $dsc = "Linea Estrategica: ".$value->descripcion."       Codigo:".$value->codigo."       Estado: ".$value->activo;
    
    $item = 0;
    $fila1 = $fila1 +1;
    $objPHPExcel->getActiveSheet()->SetCellValue("A$fila1", "$dsc");
    $objPHPExcel->getActiveSheet()->mergeCells("A$fila1:H$fila1"); //unir celdas
   foreach ($datos2 as $value2) {
  $item = $item +1;
  $fila1 = $fila1 +1;
  $objPHPExcel->getActiveSheet()->SetCellValue("A$fila1","$item");
  $objPHPExcel->getActiveSheet()->SetCellValue("B$fila1", "$value2->descripcion");
  $objPHPExcel->getActiveSheet()->SetCellValue("C$fila1", "$value2->meta_anual");
  $objPHPExcel->getActiveSheet()->SetCellValue("D$fila1", "$value2->finalizadas");
  $objPHPExcel->getActiveSheet()->SetCellValue("E$fila1", "$value2->porcentaje");
  $objPHPExcel->getActiveSheet()->SetCellValue("F$fila1", "$value2->costo");
  $objPHPExcel->getActiveSheet()->SetCellValue("G$fila1", "$value2->indicador");
  $objPHPExcel->getActiveSheet()->SetCellValue("H$fila1", "$value2->generado");
  
   }
}
//autodimensionar las columnas
   for($col = 'A'; $col !== 'G'; $col++) {
    $objPHPExcel->getActiveSheet()
        ->getColumnDimension($col)
        ->setAutoSize(true);
}
//establecer pie de impresion en cada hoja
$objPHPExcel->getActiveSheet()->getHeaderFooter()->setOddFooter('&R&F página &P / &N');


// Guardar como excel 2007
$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel); //Escribir archivo

// Establecer formado de Excel 2007
header('Content-type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

// nombre del archivo
header('Content-Disposition: attachment; filename="Solicitudes Atendidas PAO.xlsx"');

//forzar a descarga por el navegador
$objWriter->save('php://output');

