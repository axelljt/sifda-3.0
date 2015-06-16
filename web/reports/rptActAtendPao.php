<?php define('FPDF_FONTPATH','fpdf/font/');

//above line is import to define, otherwise it gives an error : Could not include font metric file

require('fpdf/fpdf.php');
require("ez_sql_core.php");
require("ez_sql_postgresql.php");

class PDF extends FPDF
{

    // Cabecera de página
function Header()
{
    
$temp_le = $_REQUEST['le'];
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_a = $_REQUEST['a'];

$fechafi = date("d-m-Y", strtotime($temp_fi));
$fechaff = date("d-m-Y", strtotime($temp_ff));

    
$this->Image('banner.png', 10,8,150,20);
$this->SetFont('Arial','B',10);
$this->Cell(330,-6,'Pagina '.$this->PageNo().'/{nb}',0,0,'C');
$this->Ln(2);
$this->Cell(178,1,'Fecha:' .date('d-m-y'). "", 0, 0, 'R', false);
$this->Ln(2);
$this->Cell(174,6,'Hora:' .date('h:m'). "", 0, 0, 'R', false);
$this->Ln(2);
$this->Cell(175,11,$temp_un, 0, 0, 'R', false);

$this->Ln(2);
//$this->Cell(180,8,'Hora:' .  gettimeofday(). "", 0, 0, 'R', false);

$this->Ln(20);
$this->SetFont('Arial','B',11);
$this->Cell(0,-7,utf8_decode($temp_dep), 0, 0, 'C', false);
$this->Ln(2);
$this->Cell(0,5,utf8_decode('Reporte de Solicitudes Atendidas por Actividad de PAO'), 0, 0, 'C', false);
if($temp_fi != 0 and $temp_ff !=0){
  $this->SetFont('Arial','',11);
  $this->Cell(-229,15,utf8_decode(' Período del'), 0, 0, 'C', false);
  $this->Cell(275,15,$fechafi, 0, 0, 'C', false); 
  $this->Cell(-250,15,' al', 0, 0, 'C', false);
  $this->Cell(280,15,$fechaff, 0, 0, 'C', false); 
}
$this->Ln(20);
$this->SetFont('Arial','B',8);
$this->SetWidths(array(5, 60,20,20,40,40));
$this->Row(array('N',utf8_decode('Descripcion'),utf8_decode('Meta'),utf8_decode('Realizadas'),utf8_decode('Indicador'),utf8_decode('Generado')));

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

function SetWidths($w)
{
    //Set the array of column widths
    $this->widths=$w;
}

function SetAligns($a)
{
    //Set the array of column alignments
    $this->aligns=$a;
}

function Row($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
}
function Row2($data)
{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak(5);
    //Draw the cells of the row
    
            $w=185;
        $a=isset($this->aligns[0]) ? $this->aligns[0] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,5);
        //Print the text
        $this->SetFillColor(200);
        $this->MultiCell($w,5,$data[0],0,'C',true);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    
    //Go to the next line
    $this->Ln(5);
}

function CheckPageBreak($h)
{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
    //Computes the number of lines a MultiCell of width w will take
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
}

}

$pdf = new PDF('P','mm','Letter');
//$pdf->AddPage("P","Letter");
$pdf->AliasNbPages();
//pag 1
$pdf->SetMargins(20,18);
$pdf->AddPage("P","Letter");
$pdf->SetFont('Arial','',06);

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda24022015', 'localhost');
$temp_le = $_REQUEST['le'];
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_a = $_REQUEST['a'];
//$temp_us = $_REQUEST['user'];
    $SQL = "select id,descripcion,codigo,CASE WHEN activo = 't' THEN 'ACTIVO' ELSE 'INACTIVO' END as activo,recurrente "
            . "from sidpla_linea_estrategica a where anio =2015";
    if ($temp_le != 0){
        $SQL.=" and id = ".$temp_le;
        }
    $SQL.=";";
    //$datos = $conexion->get_results("SELECT descripcion,fecha_requiere,fecha_recepcion FROM public.sifda_solicitud_servicio where id_estado = 1 "); 
    $datos = $conexion->get_results($SQL);
    
foreach ($datos as $value) {
    $SQL2 = "select a.id as id,a.descripcion as descripcion,a.meta_anual as meta_anual,a.indicador as indicador,CASE WHEN a.generado = 't' THEN 'Si' ELSE 'No' END as generado,count(ss.id) as finalizadas
                from sidpla_actividad a 
                left outer join sifda_tipo_servicio s on a.id = s.id_actividad
                left outer join sifda_solicitud_servicio ss on ss.id_tipo_servicio = s.id and ss.id_estado = 4";
            if ( $temp_fi != null && $temp_ff != null){
        $SQL2.=" and fecha_finaliza between '".$temp_fi."' and '".$temp_ff."'";
        }
            $SQL2.=" where id_linea_estrategica = ".$value -> id;
            if ( $temp_a != 0){
        $SQL2.= " and a.id = ".$temp_a;
        }
               $SQL2.=" group by a.id,a.descripcion,a.meta_anual,a.indicador,a.generado,s.id,s.nombre;";
    $datos2 = $conexion->get_results($SQL2);
    $dsc = "Linea Estrategica: ".$value->descripcion."       Codigo:".$value->codigo."       Estado: ".$value->activo;
    $pdf->Row2(array(utf8_decode($dsc)));
    $item = 0;
   foreach ($datos2 as $value2) {
  $item = $item +1;
  $pdf->Row(array($item,utf8_decode($value2->descripcion),utf8_decode($value2->meta_anual),
			utf8_decode($value2->finalizadas),utf8_decode($value2->indicador),
                        utf8_decode($value2->generado)
          ));  
   }
}

$pdf->Output();
?>
