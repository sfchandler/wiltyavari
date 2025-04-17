<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 10/07/2019
 * Time: 2:02 PM
 */
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$fromDate = $_REQUEST['startDate'];
$toDate = $_REQUEST['endDate'];
$year = date("Y", strtotime($toDate));
$payData = getPayrunDataForPayg($mysqli, $fromDate, $toDate, 0);
$catArray = getCategoriesForPayrunDataByDate($mysqli,$fromDate,$toDate);
$payCategories = createPayCategoryArray($mysqli);
$otherArray[] = array('transCode'=>'','amount'=>0);

$candidateId = '';
$row = '';
$totalGross = 0;
$paygTax = 0;
$totalDeduction = 0;
$len = sizeof($payData);
$k = 0;
$category = '';
$allowance = 0;
$canId = '';
$allowanceArray = array();
$totalAllowancArray = array();
$paygArray = array();
$payeeArray = array();

foreach ($catArray as $cats) {
    foreach ($payData as $data) {
        if ($cats['category'] == $data['category']) {
            foreach ($otherArray as $key => $value) {
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
                    } elseif ($data['gross'] != 0.00) {
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
    if (strtoupper($key) != 'GROSS') {
        if (!empty($value['amount'])) {
            if (transCodeCheck($mysqli, $value['transCode'], 8)) {
                $totalDeduction = $totalDeduction + $value['amount'];
            }
        }
    }
}
$count = 0;
foreach ($payData as $data) {
    if ($data['itemType'] == 9) {
        $totalGross = $totalGross + $data['gross'];
        $paygArray[$data['candidateId']][$data['category']] = $totalGross;
    }
    if ($data['itemType'] == 11) {
        $paygTax = $paygTax + $data['paygTax'];
        $paygArray[$data['candidateId']][$data['category']] = $paygTax;
    }
    if ($data['itemType'] == 14) {
        if ($category == $data['category']) {
            $allowance = $allowance + $data['amount'];
            $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
        } elseif ($category <> $data['category']) {
            $allowance = $allowance + $data['amount'];
            $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
        }
    }
    $count++;
}



$html = '';
$html = $html.'<style>
.leftColumn{ width: 40%}
.middleColumn{ width: 10%}
.rightColumn{ width: 50%; text-align: justify}
.fullBorder{border: 1px solid black}
hr{ height: 1px;}
.boxBorder{ width: 50%; height: 20px; border: 1px solid black}
.boxBorderSmall{ width: 150px; height: 15px; border: 1px solid black; padding-top: 4px}
.textField{ width: 100px; height: 0px; border: 1px solid black}
.textTitle{ width: 100px; height: 0px; }
.payTitle{
    width: 250px;
    text-align: left;
}
.dollarSign{
    width: 15px;
}
.typeBorder{
    width: 10px;
    height: 10px;
    border: 1px solid black;
}
.typeColumn{
    width: 40px;
    height: 5px; border: 1px solid black
}
.typeTitle{
    width: 40px;
}
.allowanceNote{
    font-size: 5pt;
}
.heading{
    font-size: 11pt;
    font-weight: bold;
    padding: 20px 20px 20px 20px;
}
.subheading{
    font-size: 9pt;
    font-weight: bold;
}
.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}
.total{
    text-align: right;
}
table
</style>';

$html = $html.'<div class="heading">PAYMENT SUMMARY TOTALS</div><br><br><br><br>';
$html = $html.'<table width="50%"><tr class="zebra' . ($i++ & 1) . '"><td>TOTAL GROSS</td><td class="total">'.$totalGross.'</td></tr>';
$html = $html.'<tr class="zebra' . ($i++ & 1) . '"><td>TOTAL DEDUCTIONS</td><td class="total">'.$totalDeduction.'</td></tr>';
$html = $html.'<tr class="zebra' . ($i++ & 1) . '"><td>TOTAL ALLOWANCES</td><td class="total">'.$allowance.'</td></tr>';
$html = $html.'<tr class="zebra' . ($i++ & 1) . '"><td>TOTAL TAX</td><td class="total">'.$paygTax.'</td></tr>';
$html = $html.'</table><br><br>';
$html = $html.'<div class="subheading">TOTAL PAYMENT SUMMARIES   &nbsp; '.sizeof($paygArray).'</div>';

$pdfAll = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdfAll->setHeaderTemplateAutoreset(true);
$pdfAll->SetCreator(PDF_CREATOR);
$pdfAll->SetAuthor(' ');
$pdfAll->SetTitle('PAYG Payment Summary Totals');
$pdfAll->SetSubject('PAYG Payment Summary Totals');
$pdfAll->SetKeywords('PAYG Payment Summary Totals');
$pdfAll->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
$pdfAll->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
define('PDF_CUSTOM_HEADER_STRING', ' PAYG Payment Summary Totals     From:' . $fromDate . ' to:' . $toDate . '      User:' . $_SESSION['userSession'] . '    Printed: ' . date("Y-m-d H:i:s"));
$pdfAll->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
$pdfAll->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdfAll->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdfAll->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdfAll->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdfAll->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdfAll->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
    require_once(dirname(__FILE__) . '/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdfAll->SetFont('helvetica', '', 7);
$pdfAll->AddPage();
$fileName = 'paygSummaryTotal_'.$fromDate.'to'.$toDate;
$filePathPDF = './payg/' . $fileName . '.pdf';
$pdfAll->writeHTML($html, true, false, false, false, '');
$pdfAll->lastPage();
//ob_clean();
$pdfAll->Output(__DIR__ . '/payg/' . $fileName . '.pdf', 'F');

echo $filePathPDF;




