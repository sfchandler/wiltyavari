<?php
namespace App;
use TCPDF;

class RegistrationPDF extends TCPDF
{
    protected $candidateId;

    public function setCandidateId($candidateId){
        $this->candidateId = $candidateId;
    }

    public function Header(){
        $this->Image('public/images/logo.jpg', 5, 5, 50, '', 'JPG', '', 'L', true, 300, '', false, false, 0, false, false, false);
        $this->Ln(15);
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(0, 10, 'Chandler Personnel - Labourbank Casual Registration : Mobile', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(15);
        $this->Line(5, 25, 205, 25);
    }

    public function Footer()
    {
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $this->Line(5, 280, 205, 280);
        $this->write2DBarcode('https://www.labourbank.com.au?canId='.$this->candidateId, 'QRCODE,H', 5, 282, 14, 14, $style, 'N');
        $this->SetFont('helvetica', 'R', 8);
        $this->SetY(-19);
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }

}
