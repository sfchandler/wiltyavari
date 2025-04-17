<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
require_once "includes/TCPDF-main/tcpdf.php";
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
//ini_set('display_errors', FALSE);
$html = '';
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$profitCentre = $_POST['profitCentre'];
$clientId = $_POST['clientId'];
$state = $_POST['state'];
$gross = 0;
$tax = 0;
$preTaxAllowance = 0;
$preTaxDeduction = 0;
$postTaxAllowance = 0;
$postTaxDeduction = 0;
$payrunData = getPayrunDataByDates($mysqli,$startDate,$endDate,$clientId,$state);
$catArray = getCategoriesForPayrunDataByDate($mysqli,$startDate,$endDate);
$payCategories = createPayCategoryArray($mysqli);
$otherArray[] = array('transCode'=>'','amount'=>0);
foreach ($catArray as $cats){
    foreach ($payrunData as $data) {
        if ($cats['category'] == $data['category']) {
            foreach ($otherArray as $key=>$value) {
                if ($key == $data['category']) {
                    if ($data['deduction'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['deduction'];
                    } elseif ($data['superAnnuation'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['superAnnuation'];
                    } elseif ($data['net'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['net'];
                    } elseif ($data['paygTax'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['paygTax'];
                    }elseif ($data['early morning'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['early morning'];
                    }elseif ($data['ordinary'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['ordinary'];
                    }elseif ($data['afternoon'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['afternoon'];
                    }elseif ($data['night'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['night'];
                    }elseif ($data['rdo'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['rdo'];
                    }elseif ($data['saturday'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['saturday'];
                    }elseif ($data['sunday'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['sunday'];
                    }elseif ($data['overtime'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['overtime'];
                    }elseif ($data['doubletime'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['doubletime'];
                    }elseif ($data['saturday with super'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['saturday with super'];
                    }elseif ($data['sunday with super'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['sunday with super'];
                    }elseif ($data['public holiday'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['public holiday'];
                    }elseif ($data['public holiday 2'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['public holiday 2'];
                    }elseif ($data['gross'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['gross'];
                    } elseif ($data['amount'] != 0.00) {
                        $otherArray[$data['category']]['transCode'] = $data['transCode'];
                        $otherArray[$data['category']]['amount'] = $value['amount'] + $data['amount'];
                    }
                }
            }
        }
    }
}
foreach($otherArray as $key=>$value) {
    if (strtoupper($key) == 'GROSS') {
        $gross = $value['amount'];
    }
    if (strtoupper($key) == 'PAYG TAX') {
        $tax = $value['amount'];
    }
    /*if (strtoupper($key) == 'EARLY MORNING') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'ORDINARY') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'AFTERNOON') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'NIGHT') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'RDO') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'SATURDAY') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'SUNDAY') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'OVERTIME') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'DOUBLETIME') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'SATURDAY WITH SUPER') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'SUNDAY WITH SUPER') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'PUBLIC HOLIDAY') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'PUBLIC HOLIDAY 2') {
        $gross = $gross + $value['amount'];
    }elseif (strtoupper($key) == 'HOLIDAY WITH SUPER') {
        $gross = $gross + $value['amount'];
    }*/
    /*echo 'GROSS --> '.$gross.'<br>';
    echo 'Tax --> '.$tax.'<br>';*/
    $taxOrder = getTaxOrderBasedOnTransactionCode($mysqli,$value['transCode']);


    if(transCodeCheck($mysqli,$value['transCode'],2)) { // deductions
        if($taxOrder == 'before'){
            if (($value['transCode'] != 28)) {
                $preTaxDeduction = $preTaxDeduction + $value['amount'];
            }
            if (($value['transCode'] == 1235)){
                $gross = $gross + $value['amount'];
                $preTaxDeduction = $value['amount'];
            }
        }elseif ($taxOrder == 'after'){
                $postTaxDeduction = $postTaxDeduction + $value['amount'];
        }
        /*if($value['transCode'] != 19){
            $postTaxDeduction = $postTaxDeduction + $value['amount'];
            echo 'POST TAX DEDUCTION '.$postTaxDeduction.'<br>';
        }else*/
        /*if (($value['transCode'] == 19)){ // overpayment recovery
            $preTaxDeduction = $preTaxDeduction + $value['amount'];
        }elseif (($value['transCode'] == 16)){ // child support deduction
            $postTaxDeduction = $postTaxDeduction + $value['amount'];
        }elseif (($value['transCode'] == 1)){ // police check deduction
            $postTaxDeduction = $postTaxDeduction + $value['amount'];
        }elseif (($value['transCode'] == 22)){ // Pay adjustment check deduction
            $postTaxDeduction = $postTaxDeduction + $value['amount'];
        }elseif (($value['transCode'] == 28)){
            $preTaxDeduction = $preTaxDeduction + $value['amount'];
        }*/
    }
    if(transCodeCheck($mysqli,$value['transCode'],1)) { // allowances
        if($taxOrder == 'before'){
            if (($value['transCode'] != 28)) {
                $preTaxAllowance = $preTaxAllowance + $value['amount'];
            }
        }elseif ($taxOrder == 'after'){
            $postTaxAllowance = $postTaxAllowance + $value['amount'];
        }
        /*if($value['transCode'] == 17){ //pay adjustment
        }elseif ($value['transCode'] == 27){ //pay adjustment before tax
        }elseif ($value['transCode'] == 26){ //police check refund
        }elseif ($value['transCode'] == 25){ //CRIB Allowance
        }
        $preTaxAllowance = $preTaxAllowance + $value['amount'];*/
    }
}

/*$gross = $gross + $postTaxDeduction;
$gross = $gross - $preTaxAllowance;*/

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderTemplateAutoreset(true);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(' ');
$pdf->SetTitle('Candidate Balance Report');
$pdf->SetSubject('Candidate Balance Report');
$pdf->SetKeywords('Candidate Balance Report');
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
define('PDF_CUSTOM_HEADER_STRING',' Candidate Balance Report     From:'.$startDate.' to:'.$endDate.'      User:'.$_SESSION['userSession'].'    Printed: '.date("Y-m-d H:i:s"));
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);

$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

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
$pdf->SetFont('helvetica', '', 8);

// add a page
$pdf->AddPage();

$html = $html.'<style>
tr{ text-transform: uppercase;
}
.rowHeader{ 
    font-weight: bold;
    background-color:#9EA8B1;
}
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
}
thead{
    background-color: #9EA8B1;
}
th{
    text-align: center;
    font-size: 8pt;
}
td.cellWidth{
    text-align: left;
    width: 60%;
}
td.cellRight{
    text-align: right;
    width: 20%;
}
td.boldFigure{
    text-align: right;
    font-weight: bold;
}
td.cellCenter{
    text-align: center;
    width: 20%;
}
td.shortWidth{
    text-align: right;
}
td.empId{
    text-align: left;
}
td.desc{
    text-align: left;
    text-transform: uppercase;
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
    font-size: 10pt;
}
.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}
.subHeading{
    text-transform: none;
    font-weight: bold;
}</style><div class="pageTitle">Candidate Balance Report</div>';
$html = $html.'<div align="center"><table style="width: 90%; font-weight: bold;" cellpadding="2" cellspacing="2"><tbody><tr><td>Gross: </td><td>'.number_format($gross,2).'</td><td>Pre Tax Allowance: </td><td>'.$preTaxAllowance.'</td><td>Post Tax Allowance: </td><td>'.$postTaxAllowance.'</td></tr>';
$html = $html.'<tr><td>Tax: </td><td>-'.number_format($tax,2).'</td><td>Pre Tax Deduction: </td><td>-'.$preTaxDeduction.'</td><td>Post Tax Deduction: </td><td>-'.$postTaxDeduction.'</td></tr></tbody></table></div>';
$html = $html.'<div align="center"><table border="1" style="width: 100%;"><thead>
    <tr>
        <th style="text-align: center;width: 20%;text-transform: uppercase;">Transaction Code</th>
        <th style="text-align: center;width: 60%;text-transform: uppercase;">Category</th>
        <th style="text-align: center;width: 20%;text-transform: uppercase;">Amount</th>
    </tr>
</thead>
<tbody>';
foreach($otherArray as $key=>$value) {
    if (strtoupper($key) != 'GROSS') {
        if (!empty($value['amount'])) {
            if(transCodeCheck($mysqli,$value['transCode'],2)){ // deduction
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellCenter">' . $value['transCode'] . '</td><td class="cellWidth">' . $key . '</td><td class="cellRight">-' . number_format($value['amount'], 2) . '</td></tr>';
            }else if (strtoupper($key) == 'PAYG TAX') {//tax
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellCenter">' . $value['transCode'] . '</td><td class="cellWidth">' . $key . '</td><td class="cellRight">-' . number_format($value['amount'], 2) . '</td></tr>';
            }else{
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellCenter">' . $value['transCode'] . '</td><td class="cellWidth">' . $key . '</td><td class="cellRight">' . number_format($value['amount'], 2) . '</td></tr>';
            }
        }
    }
}
$fileName = 'candidateBalanceReport'.time();
$filePathPDF = './reports/'.$fileName.'.pdf';
$html = $html.'</tbody></table></div><span class="filePath" data-filePathPDF="'.$filePathPDF.'">&nbsp;</span>';
$pdf->writeHTML($html, true, false, false, false, '');
$pdf->lastPage();
$pdf->Output(__DIR__.'/reports/'.$fileName.'.pdf', 'F');
echo $html;


