<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH . '/third_party/fpdf/fpdf.php';
class fpdf_general extends FPDF{
 private $data = array();
function __construct($orientation='L', $unit='cm',$size=array(21,30))
  {

    parent::__construct($orientation,$unit,$size);

}


function Header(){    
   
    // $this->SetTopMargin(0.1);
    $this->SetFont('Arial','',10);
    // $this->Image('logo.png',10,6,30);
    // $this->Image(base_url('assets/img/a.jpg'),1,1,2,2);
    $this->Cell(0,0.6,'Program Keluarga Harapan ',0,1,'C');
    $this->Cell(0,0.6,'Ds. Sukatani Kec. Cisoka - Kabupaten Tangerang - Banten',0,1,'C');
    // $this->Cell(0,0.6,'',0,0,'C');
    $this->Cell(0,0.6,'Hal : '.$this->PageNo(),0,1,'R');
 //    $this->Line(1,3.1,20.5,3.1);
	// $this->SetLineWidth(0.1);      
	// $this->Line(1,3.2,20.5,3.2);   
	// $this->SetLineWidth(0);
	$this->cell(0,0.5,'________________________________________________________________________________________________________',0,1,'C');
	$this->ln(0.5);
	
}                 

// Page footer
function Footer()
{
    // // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // // Arial italic 8
    $this->SetFont('Arial','I',10);
    // // Page number
    // $this->Cell(0,10,'created by sri repiyanti ',0,0,'L');
}


    private $widths;
    private $aligns;

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
        $h=0.5*$nb;
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
            $this->MultiCell($w,0.5,$data[$i],0,1);
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
