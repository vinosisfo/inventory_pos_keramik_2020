<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once APPPATH . '/third_party/TCPDF-master/tcpdf.php';


class tc_pdf_a extends TCPDF{
var $widths;
var $aligns;
function __construct($orientation='L', $unit='cm',$size='A4')
  {

    parent::__construct($orientation,$unit,$size);

}


function Header(){    
   
    $CI =& get_instance();
    // $this->SetFont('Arial','',9);
   
}                 


function Footer() {   
 // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');             
        
  }     
}
