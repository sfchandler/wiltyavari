<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 2/09/2019
 * Time: 1:39 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
require_once("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
$myobacc = $_POST['myobacc'];
$weekendingDate = $_POST['weekendingDate'];
$invoiceDate = $_POST['invoiceDate'];
$action = $_POST['action'];

class MYOBPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';//K_PATH_IMAGES.'ChandlerPersonnel.jpg'
        $this->Image($image_file, 10, 5, 60, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
    }
}

if(!empty($myobacc) && !empty($weekendingDate) && !empty($invoiceDate)) {
    try {
        $invoiceData = getInvoiceDetails($mysqli, $weekendingDate, $invoiceDate);
    }catch (Exception $e){
        echo $e->getMessage();
    }
   if($action == 'csv'){
       $objPHPExcel = new PHPExcel();
       $objPHPExcel->setActiveSheetIndex(0);
       $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Card ID');
       $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Invoice #');
       $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Amount');
       $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Inc-Tax Amount');
       $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Date');
       $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Description');
       $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Account #');
       $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Tax Code');
       $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Journal Memo');
       $objPHPExcel->getActiveSheet()->setTitle('MYOB CSV UPLOAD');

       $rowCount = 1;
       foreach ($invoiceData as $data) {
           //$rowCount++;
           $rowCount = $rowCount + 2;
           $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, getClientCodeById($mysqli, $data['clientId']));
           $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['invoiceId']);
           $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['netAmount']);
           $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['gross']);
           $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['invoiceDate']);
           $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, 'W/E '.$data['weekendingDate']);
           $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $myobacc);
           $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, 'GST');
           $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, getClientCodeById($mysqli, $data['clientId']).' - '.getClientNameByClientId($mysqli,$data['clientId']));
       }

       $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
       $filePath = './invoice/MYOB' . time() . '.csv';
       $objWriter->save($filePath);
       echo $filePath;
   }elseif ($action == 'pdf'){
       try{
           $pdf = new MYOBPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
           $pdf->setHeaderTemplateAutoreset(true);
           $pdf->SetCreator(PDF_CREATOR);
           $pdf->SetAuthor(' ');
           $pdf->SetTitle('MYOB');
           $pdf->SetSubject('MYOB');
           $pdf->SetKeywords('MYOB');
           $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
           $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
           $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
           $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
           $pdf->SetMargins(5, PDF_MARGIN_TOP, 2);
           $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
           $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
           $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
           $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
           if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
               require_once(dirname(__FILE__).'/lang/eng.php');
               $pdf->setLanguageArray($l);
           }
           $pdf->SetFont('helvetica', '', 8);
           $pdf->AddPage();
           $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));
           $pdf->Line(0,0,$pdf->getPageWidth(),0);
           $pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
           $pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
           $pdf->Line(0,0,0,$pdf->getPageHeight());

           $html = $html.'<div style="width: 100%"><style>td{ text-align: right;}.rowTitle{text-align: left;}th{ text-align: center; font-weight: bold; border: 1px solid dimgrey}.zebra0{background-color: #f1f1f1;}.zebra1{background-color: white;}</style>';
           $html = $html.'<div align="center" style="text-align:center;font-weight: bold; font-size: 20pt">MYOB&nbsp;&nbsp;&nbsp;REPORT</div>';
           $html = $html.'<div align="center"><table cellspacing="1" cellpadding="1" width="980px"><thead><tr><th width="10%">Card ID</th><th width="10%">Invoice #</th><th width="10%">Amount</th><th width="10%">Inc-Tax Amount</th><th width="10%">Date</th><th width="15%">Description</th><th width="10%">Account #</th><th width="5%">Tax Code</th><th width="25%">Journal Memo</th></tr></thead><tbody>';
           foreach ($invoiceData as $data) {
               $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td width="10%">'.getClientCodeById($mysqli, $data['clientId']).'</td><td width="10%">'.$data['invoiceId'].'</td><td width="10%">'.$data['netAmount'].'</td><td width="10%">'.$data['gross'].'</td><td width="10%">'.$data['invoiceDate'].'</td><td width="15%">W/E '.$data['weekendingDate'].'</td><td width="10%">'.$myobacc.'</td><td width="5%">GST</td><td width="25%">'.getClientCodeById($mysqli, $data['clientId']).' - '.getClientNameByClientId($mysqli,$data['clientId']).'</td></tr>';
           }
           $html = $html.'</tbody></table></div>';
           $html = $html.'<span style="height: 30px;">&nbsp;</span>';
           $fileName = 'myob_'.date('Y-m-d');
           $filePath = '/invoice/'.$fileName.'.pdf';
           $pdf->writeHTML($html, true, false, false, false, '');
           $pdf->lastPage();
           ob_clean();
           $pdf->Output(__DIR__.'/invoice/'.$fileName.'.pdf', 'F');
           echo $filePath;
       }catch (Exception $e){
          echo  $e->getMessage();
       }
   }
}else{
    echo 'Please select/fill all inputs';
}


