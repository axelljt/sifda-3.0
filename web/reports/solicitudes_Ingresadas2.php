
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
$temp_un = $_REQUEST['un'];
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_dep = $_REQUEST['dep'];

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
//$this->Cell(0,25,utf8_decode('Reporte de Solicitudes Ingresadas'), 0, 0, 'C', false);
$this->Cell(0,-7,utf8_decode($temp_dep), 0, 0, 'C', false);
$this->Ln(2);
$this->Cell(0,5,utf8_decode('Reporte de Solicitudes Ingresadas'), 0, 0, 'C', false);

  $this->SetFont('Arial','',11);
  $this->Cell(-229,15,utf8_decode(' Período del'), 0, 0, 'C', false);
  $this->Cell(275,15,$fechafi, 0, 0, 'C', false); 
  $this->Cell(-250,15,' al', 0, 0, 'C', false);
  $this->Cell(280,15,$fechaff, 0, 0, 'C', false); 
  
  $this->Ln(20);
  $this->SetFont('Arial','B',11);
  $this->SetWidths(array(5, 140, 25));
  $this->SetAligns(array('L','L','C'));
  $this->Row(array('N',utf8_decode('TIPOS DE SERVICIO'),utf8_decode('TOTAL')));



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
    //$this->Cell(0,10,'Ministerio de Salud',0,0,'C');
    $this->Cell(0,10,'Ministerio de Salud SIFDA',0,0,'C');
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

//$pdf->Cell(0,25,'Generado:' .date('d-m-y'). "", 0, 0, 'R', false);
$pdf->AliasNbPages();
//pag 1
$pdf->SetMargins(20,18);
$pdf->AddPage("P","Letter");
$pdf->SetFont('Arial','',11);
//$pdf->Cell(15,15,$temp_un, 0, 0, 'R', false);

$conexion = new ezSQL_postgresql('sifda', 'sifda', 'sifda24022015', 'localhost');
$temp_fi = $_REQUEST['fi'];
$temp_ff = $_REQUEST['ff'];
$temp_tipo = $_REQUEST['tp'];


$datos = $conexion->get_results("SELECT sts.nombre,count(sts.nombre) as cuenta
  FROM public.sifda_solicitud_servicio ss
    inner join public.fos_user_user us on (us.id = ss.user_id)
inner join public.ctl_dependencia_establecimiento dep on (dep.id = us.id_dependencia_establecimiento)
inner join public.sifda_tipo_servicio sts on (sts.id = ss.id_tipo_servicio)
inner join public.ctl_dependencia de on (de.id = 23)
where id_estado=1 
and fecha_recepcion >= '$temp_fi' and fecha_recepcion <='$temp_ff'
group by (sts.nombre)");

    $suma = 0;
        
    foreach ($datos as $value) {
    $item = $item +1;
    $pdf->SetAligns(array('L','L','C'));
    $pdf->Row(array($item,
			utf8_decode($value->nombre),utf8_decode($value->cuenta)
                        ));
    
    $suma = $suma + ($value->cuenta);
    }//fin del foreach
    $pdf->SetFont('Arial','B',11);
    $pdf->Cell(145,7,utf8_decode('TOTAL DE SOLICITUDES'),1,0,'C');
    $pdf->SetFillColor(255,0,0);
    $pdf->SetTextColor(255,0,0);
    $pdf->Cell(25,7,utf8_decode($suma),1,0,'C');

$pdf->Output();
?>

