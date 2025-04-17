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
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$action = $_POST['action'];

class INVPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';//K_PATH_IMAGES.'ChandlerPersonnel.jpg'
        $this->Image($image_file, 10, 5, 60, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
    }
}

if(!empty($startDate) && !empty($endDate)) {
    try {
        $invoiceData = getInvoiceDetailsWithGeneratedDate($mysqli,$startDate,$endDate);
    }catch (Exception $e){
        echo $e->getMessage();
    }
    if ($action == 'pdf'){
        try{
            $pdf = new INVPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setHeaderTemplateAutoreset(true);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(' ');
            $pdf->SetTitle('INVOICE REPORT');
            $pdf->SetSubject('INVOICE REPORT');
            $pdf->SetKeywords('INVOICE REPORT');
            $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
            $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetMargins(5, 30, 2);
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
            $html = $html.'<div align="left" style="text-align:left;font-weight: bold; font-size: 12pt">&nbsp;&nbsp;&nbsp;&nbsp;Date Range : '.$startDate.' to '.$endDate.'</div>';
            $html = $html.'<div style="width: 100%"><style>td{ text-align: center;border: 1px solid dimgrey;}.client{text-align: left;}.amount{text-align: right;}.totalAmount{text-align: right;font-weight: bold;border-bottom:1px solid #000;text-decoration:underline;}.rowTitle{text-align: left;}th{ text-align: center; font-weight: bold; border: 1px solid dimgrey}.zebra0{background-color: #f1f1f1;}.zebra1{background-color: white;}</style>';
            $html = $html.'<div align="center" style="text-align:center;font-weight: bold; font-size: 20pt">INVOICE&nbsp;&nbsp;&nbsp;REPORT</div>';
            $html = $html.'<div align="center"><table cellspacing="1" cellpadding="1" width="980px"><thead><tr><th width="10%">InvoiceNo</th><th width="10%">Invoice Date</th><th width="10%">Weekending Date</th><th width="25%">Client</th><th width="5%">PayrunId</th><th width="8%">Generated Date</th><th width="7%">Generated Time</th><th width="10%">Net Amount</th><th width="10%">GST</th><th width="10%">Total</th></tr></thead><tbody>';
            $totalNet = 0;
            $totalGst = 0;
            $totalGross = 0;
            foreach ($invoiceData as $data) {
                $totalNet = $totalNet + $data['netAmount'];
                $totalGst = $totalGst + $data['gst'];
                $totalGross = $totalGross + $data['gross'];
                $splitTimeStamp = explode(" ",getInvoiceGeneratedDate($mysqli,$data['weekendingDate']));
                $genDate = $splitTimeStamp[0];
                $genTime = $splitTimeStamp[1];
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td width="10%">'.$data['invoiceId'].'</td><td width="10%">'.$data['invoiceDate'].'</td><td width="10%">'.$data['weekendingDate'].'</td><td width="25%" class="client">'.getClientNameByClientId($mysqli, $data['clientId']).'</td><td width="5%">'.getPayrunIdByWeekendingDate($mysqli,$data['weekendingDate']).'</td><td width="8%">'.$genDate.'</td><td width="7%">'.$genTime.'</td><td width="10%" class="amount">'.$data['netAmount'].'</td><td width="10%" class="amount">'.$data['gst'].'</td><td width="10%" class="amount">'.$data['gross'].'</td></tr>';
            }
            $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td width="75%" colspan="7" class="client"><strong>Totals</strong></td><td width="10%" class="totalAmount">'.number_format($totalNet,2).'</td><td width="10%" class="totalAmount">'.number_format($totalGst,2).'</td><td width="10%" class="totalAmount">'.number_format($totalGross,2).'</td></tr>';
            $html = $html.'</tbody></table></div>';
            $html = $html.'<span style="height: 30px;">&nbsp;</span>';
            $fileName = 'invoiceReport_'.date('Y-m-d');
            $filePath = './reports/'.$fileName.'.pdf';
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->lastPage();
            ob_clean();
            $pdf->Output(__DIR__.'/reports/'.$fileName.'.pdf', 'F');
            echo $filePath;
        }catch (Exception $e){
            echo  $e->getMessage();
        }
    }elseif ($action == 'excel'){
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Invoice #');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Invoice Date');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Weekending');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Client');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'PayrunId');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Generated Date');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'Generated Time');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Net Amount');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'GST');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Total');

        $objPHPExcel->getActiveSheet()->setTitle('Invoice Report');
        $rowCount = 1;
        $totalNet = 0;
        $totalGst = 0;
        $totalGross = 0;
        foreach ($invoiceData as $data) {
            $rowCount++;
            $totalNet = $totalNet + $data['netAmount'];
            $totalGst = $totalGst + $data['gst'];
            $totalGross = $totalGross + $data['gross'];
            $splitTimeStamp = explode(" ",getInvoiceGeneratedDate($mysqli,$data['weekendingDate']));
            $genDate = $splitTimeStamp[0];
            $genTime = $splitTimeStamp[1];

            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['invoiceId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['invoiceDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['weekendingDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getPayrunIdByWeekendingDate($mysqli,$data['weekendingDate']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $genDate);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $genTime);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['netAmount']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['gst']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['gross']);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filePath = './reports/invoiceReport' . time() . '.xlsx';
        $objWriter->save($filePath);
        echo $filePath;
    }
}else{
    echo 'Please select/fill all inputs';
}


