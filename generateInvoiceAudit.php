<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');

$creationNo = $_REQUEST['creationNo'];
$reprintDate = $_REQUEST['reprintDate'];

$invData = getInvoiceReprintData($mysqli,$creationNo,$reprintDate);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(DOMAIN_NAME);
$pdf->SetTitle('Invoice Generation Audit Trial');
$pdf->SetSubject('Invoice Generation Audit Trial');
$pdf->SetKeywords('Invoice Generation Audit Trial');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$html = $html.'<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
    text-transform: uppercase;
}
td.cellWidth{
    text-align: right;
    width: 8%;
}
td.shortWidth{
    text-align: right;
    width: 5%;
}
td.empId{
    text-align: left;
    width:12%;
}
td.desc{
    text-align: left;
    width:25%;
}
th{
    font-weight: bold;
}
.title{
    margin-top: 0;
    padding-top: 0;
    text-align: left;
    text-transform: uppercase;
    font-weight: bold;
}
.pageTitle{
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 11pt;
}

.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}


.totalRow{
    font-weight: bold;
}
</style>
<table>
    <thead>
        <tr>
            <th>Code</th>
            <th>Name</th>
            <th>Invoice No</th>
            <th>Date Raised</th>
            <th>Payroll Tax</th>
            <th>Net</th>
            <th>GST</th>
            <th>Gross</th>
        </tr>    
    </thead>
    <tbody>';
$netTotal = 0;
$gstTotal = 0;
$grossTotal = 0;
$customer;
$accManager;
$netAmount = 0;
$gst = 0;
$gross = 0;
$i = 0;
$len = count($invData);
foreach($invData as $data){
    $netTotal = $netTotal + $data['netAmount'];
    $gstTotal = $gstTotal + $data['gst'];
    $grossTotal = $grossTotal + $data['gross'];

    if(empty($accManager)){
        $accManager = $data['accountManager'];
        $html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td colspan="8" style="font-weight: bold">'.$data['accountManager'].'</td></tr>';
    }

    if($accManager == $data['accountManager']) {
        $netAmount = $netAmount + $data['netAmount'];
        $gst = $gst + $data['gst'];
        $gross = $gross + $data['gross'];
    }else{
        $html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td colspan="5"></td><td>'.number_format($netAmount,2).'</td><td>'.number_format($gst,2).'</td><td>'.number_format($gross,2).'</td></tr>';
        $accManager = $data['accountManager'];
        $netAmount = 0 + $data['netAmount'];
        $gst = 0 + $data['gst'];
        $gross = 0 + $data['gross'];
        $html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td colspan="8" style="font-weight: bold">'.$data['accountManager'].'</td></tr>';
    }
    $html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td>'.getClientCodeById($mysqli,$data['clientId']).'</td><td>'.$data['client'].'</td><td>'.$data['invoiceId'].'</td><td>'.$data['invoiceDate'].'</td><td></td><td>'.$data['netAmount'].'</td><td>'.$data['gst'].'</td><td>'.$data['gross'].'</td></tr>';
    if ($i == $len - 1) {
        $html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td colspan="5"></td><td>'.number_format($netAmount,2).'</td><td>'.number_format($gst,2).'</td><td>'.number_format($gross,2).'</td></tr>';
    }
    $i++;
}
$html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td colspan="5"></td><td>'.number_format($netTotal,2).'</td><td>'.number_format($gstTotal,2).'</td><td>'.number_format($grossTotal,2).'</td></tr>';

$fileName = 'invoiceCreationAudit_'.time().'.pdf';
$filePath = './invoice/'.$fileName;
$html = $html.'</tbody></table><span class="filePath" data-filePath="'.$filePath.'">&nbsp;</span>';
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
//$pdf->Output(__DIR__.'/invoice/'.$fileName, 'F');
$pdf->Output(__DIR__ . '/invoice/' . $fileName . '.pdf', 'F');

echo $filePath;
?>