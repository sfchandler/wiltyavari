<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 23/01/2019
 * Time: 4:41 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');

$clientCode = $_REQUEST['clientCode'];
$clientName = $_REQUEST['clientName'];
$weekendingStart = $_REQUEST['startDate'];
$weekendingEnd  = $_REQUEST['endDate'];
$transactionType = $_REQUEST['transactionType'];

$sql = $mysqli->prepare("SELECT 
                                  invoice_detail.creationNo,
                                  invoice_detail.invoiceId,
                                  invoice_detail.invoiceDate,
                                  invoice_detail.weekendingDate,
                                  invoice_detail.clientId,
                                  invoice_detail.netAmount,
                                  invoice_detail.gst,
                                  invoice_detail.gross,
                                  client.client,
                                  client.clientCode,
                                  client.accountManager
                                FROM
                                  invoice_detail
                                INNER JOIN client ON (invoice_detail.clientId = client.clientId)
                                WHERE
                                  invoice_detail.weekendingDate BETWEEN ? AND ?
                                ORDER BY client.client") or die($mysqli->error);

$sql->bind_param("ss",$weekendingStart,$weekendingEnd)or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($creationNo,$invoiceId,$invoiceDate,$weekendingDate,$clientId,$netAmount,$gst,$gross,$client,$clientCode,$accountManager);
$dataArray = array();
while($sql->fetch()) {
    $row = array('creationNo' => $creationNo, 'invoiceId' => $invoiceId, 'invoiceDate' => $invoiceDate,'weekendingDate'=>$weekendingDate,'clientId'=>$clientId,'client'=>$client,'clientCode'=>$clientCode,'accountManager'=>$accountManager,'tax'=>$netAmount,'gst'=>$gst,'gross'=>$gross);
    $dataArray[] = $row;
}
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('Invoice Transaction Report');
$pdf->SetSubject('Invoice Transaction Report');
$pdf->SetKeywords('Invoice Transaction Report');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, 'Invoice Transaction Listing', PDF_HEADER_STRING);

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
    table-layout: auto;
    border-collapse: collapse;
    width: 100%;
    font-size: 8pt;
    border: 1px dashed;
    /*table-layout:fixed;word-wrap:break-word;*/
}
td{
    text-align: right;
    white-space:normal;
}
.thDesc{
    white-space: nowrap;
}
.desc{
    text-align: justify;
    text-transform: uppercase;
    white-space: nowrap;
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
th{
    text-align: center;
    text-transform: uppercase;
}
tr:nth-child(even) {
  background-color: #e7eff8;
}
tr:nth-child(odd) {
  background-color: #FFFFFF;
}
.totalRow{
    font-weight: bold;
}
.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}
</style>
<table border="1" >
    <thead>
        <tr>
            <th>Doc No</th>
            <th>Type</th>
            <th>Date Raised</th>
            <th>Account Manager</th>
            <th>Printed</th>
            <th>Ex-Tax</th>
            <th>Payroll Tax</th>
            <th>GST</th>
            <th>Gross</th>
        </tr>    
    </thead>
    <tbody>';

$clientName = '';
$k = 0;
$len = count($dataArray);
$totalGross = 0;
$totalGST = 0;
$totalTax = 0;
foreach($dataArray as $data) {
    $totalGross = $totalGross + $data['gross'];
    $totalGST = $totalGST + $data['gst'];
    $totalTax = $totalTax + $data['tax'];
    if(empty($clientName)){
        $clientName = $data['client'];
    }
    if ($k == 0) {
        // first
        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td align="left" colspan="9">'.$data['client'].' '.$data['clientCode'].'</td></tr>';
    }else if ($k == $len - 1) {
        // last
    }/*else{*/
        // other
        if($data['client'] != $clientName) {
            $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td align="left" colspan="9">'.$data['client'].' '.$data['clientCode'].'</td></tr>';
            $clientName = $data['client'];
        }
        $html = $html.'<tr><td>'.$data['creationNo'].'</td><td>Invoice</td><td>'.$data['invoiceDate'].'</td><td>'.$data['accountManager'].'</td><td>'.$data['invoiceDate'].'</td><td>'.$data['tax'].'</td><td></td><td>'.$data['gst'].'</td><td>'.$data['gross'].'</td></tr>';
    /*}*/
    $k++;
}
$html = $html.'<tr><td></td><td></td><td></td><td></td><td></td><td><strong>'.$totalTax.'</strong></td><td></td><td><strong>'.$totalGST.'</strong></td><td><strong>'.$totalGross.'</strong></td></tr>';

$fileName = 'invoiceReport_'.time().'.pdf';
$filePath = './invoice/'.$fileName;
$html = $html.'</tbody></table><span class="filePath" data-filePath="'.$filePath.'">&nbsp;</span>';


// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output(__DIR__.'/invoice/'.$fileName, 'F');

echo '/invoice/'.$fileName;