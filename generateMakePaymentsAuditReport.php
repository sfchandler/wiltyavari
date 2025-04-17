<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 20/10/2017
 * Time: 5:08 PM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
require_once('includes/aba-file-generator-master/vendor/autoload.php');
//ini_set('display_errors', 1);
ini_set('memory_limit', '1024M');
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);*/

use AbaFileGenerator\Model\Transaction;
use AbaFileGenerator\Generator\AbaFileGenerator;
use AbaFileGenerator\Model\TransactionCode;

$payrunId = $_REQUEST['payrunId'];
$corporateBankAccount =$_REQUEST['bankAccount'];

$category = 'NetWages';
$payrunData = getPayrunDataById($mysqli,$payrunId,$category);

$bsbStatus = true;
foreach ($payrunData as $pr){
    foreach (getEmployeeBankAccount($mysqli,$pr['candidateId']) as $bnk){
        if(!bsb_validator($bnk['bsb'])){
            echo $pr['candidateId'].'-->'.$bnk['bsb'].'<br>';
            $bsbStatus = false;
        }
    }
}
if($bsbStatus) {
// create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('');
    $pdf->SetTitle('Make Payments Report');
    $pdf->SetSubject('Make Payments Report');
    $pdf->SetKeywords('Make Payments Report');
// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
// set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
//define('PDF_CUSTOM_HEADER_STRING','Payrun No :'.$payrunId.'                      User:'.$_SESSION['userSession'].'    Printed: '.date("Y-m-d H:i:s"));
// set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, '');
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
// set margins
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
// set some language-dependent strings (optional)
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

// set font
    $pdf->SetFont('helvetica', '', 8);

// add a page
    $pdf->AddPage();

    $html = $html . '<style>
tr{ 
    text-transform: uppercase;
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
td.cellShort{
    width: 10%;
}
td.cellMedium{
    width: 15%;
    text-align: left;
}
td.boldFigure{
    text-align: right;
    font-weight: bold;
}
td.cellLarge{
    width: 25%;
}
td.cellNet{
    width: 10%;
    text-align: right;
}
td.cellTotal{
    text-align:right;
    width:10%;
    border-top:2px solid #000;
    font-weight:bold;
    border-bottom: 1px double #000;
}
td.shortWidth{
    text-align: right;
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
    padding: 0 0 0 0;
}</style><div class="pageTitle">Make Payments Audit Report</div><div class="subHeading"><strong>Payrun No :</strong>' . $payrunId . '</div><div class="subHeading"><strong>Bank Account: </strong>' . getCorporateBankAccountById($mysqli, $corporateBankAccount) . '</div><div class="subHeading"><strong>User:</strong>' . $_SESSION['userSession'] . '</div><div class="subHeading"><strong>Printed: </strong>' . date("Y-m-d H:i:s") . '</div><br/><table border="0"><thead>
    <tr class="rowHeader">
        <th style="text-align: center;width: 15%;text-transform: uppercase;">Code</th>
        <th style="text-align: center;width: 15%;text-transform: uppercase;">Surname</th>
        <th style="text-align: center;width: 15%;text-transform: uppercase;">Given Name</th>
        <th style="text-align: center;width: 25%;text-transform: uppercase;">Account Name</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">BSB</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Account</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Amount</th>
    </tr>
</thead>
<tbody>';

    $corporateBankTradeCode = getCorporateBankTradeCode($mysqli, $corporateBankAccount);
    $corporateBankDetails = getCorporateBankAccountDetails($mysqli, $corporateBankAccount);

    $payTotal = 0;
    $transactionArray = array();
    foreach ($payrunData as $data) {
        $payTotal = $payTotal + $data['net'];
        $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellMedium">' . $data['candidateId'] . '</td><td class="cellMedium">' . getCandidateLastNameByCandidateId($mysqli, $data['candidateId']) . '</td><td class="cellMedium">' . getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']) . '</td>';
        $empBankAccount = getEmployeeBankAccount($mysqli, $data['candidateId']);
        if (!empty($empBankAccount)) {
            foreach ($empBankAccount as $bnk) {
                $html = $html . '<td class="cellLarge">' . $bnk['accountName'] . '</td><td class="cellShort">' . $bnk['bsb'] . '</td><td class="cellShort">' . $bnk['accountNumber'] . '</td>';
                $bnkName = $bnk['accountName'];
                $bnkNumber = $bnk['accountNumber'];
                $bnkBSB = $bnk['bsb'];
            }
        } else {
            $html = $html . '<td class="cellLarge"></td><td class="cellShort"></td><td class="cellShort"></td>';
            $bnkName = 'NOTFOUND';
            $bnkNumber = '000000000';
            $bnkBSB = '000-000';
        }
        /*
            if (preg_match('/^\d{10}$/', $bnkNumber)) {
                echo $data['candidateId'].'PASS'.$bnkNumber.'<br>';
            } else {
                echo $data['candidateId'].'FAIL'.$bnkNumber.'<br>';
            }*/
        if (strlen($bnkName) > 32) {
            $bnkName = text_truncate($bnkName, 32);
        }

        $transaction = new Transaction();
        $transaction->setAccountName($bnkName);
        $transaction->setAccountNumber(trim($bnkNumber));
        $transaction->setBsb($bnkBSB);
        $transaction->setTransactionCode(TransactionCode::PAYROLL_PAYMENT);
        $transaction->setReference($data['candidateId']);
        $transaction->setAmount(str_replace(".", "", $data['net']));
        $transactionArray[] = $transaction;
        $html = $html . '<td class="cellNet">' . $data['net'] . '</td></tr>';
    }
    $curDate = date('d-M-Y');
    foreach ($corporateBankDetails as $detail) {
        $generator = new AbaFileGenerator($detail['bsb'], $detail['accountNumber'], $detail['tradeCode'], $detail['userName'], 'Chandler Re', $detail['userCode'], 'PAYROLL');
        $transaction = new Transaction();
        $transaction->setAccountName($detail['accountName']);
        $transaction->setAccountNumber($detail['accountNumber']);
        $transaction->setBsb($detail['bsb']);
        $transaction->setTransactionCode(TransactionCode::EXTERNALLY_INITIATED_DEBIT);
        $transaction->setReference($curDate);
        $transaction->setAmount(str_replace(",", "", str_replace(".", "", number_format($payTotal, 2))));//str_replace(".","",$payTotal)
        $transactionArray[] = $transaction;
    }
    $generator->setProcessingDate(date('d-M-Y'));

    $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="6"></td><td class="cellTotal"><u>' . number_format($payTotal, 2) . '</u></td></tr>';
    $fileName = 'makePayment - ' . time();
    $filePathPDF = './makepayment/' . $fileName . '.pdf';
    if (!file_exists('./makepayment/' . $corporateBankTradeCode)) {
        $bankFile = './makepayment/' . $corporateBankTradeCode . time() . '.ABA';
    } else {
        $bankFile = './makepayment/' . $corporateBankTradeCode . '.ABA';
    }
    //$bankFile = './makepayment/'.$corporateBankTradeCode.'.ABA';
    $abaString = $generator->generate($transactionArray);
    file_put_contents($bankFile, $abaString);
    $html = $html . '</tbody></table><span class="filePath" data-filePathPDF="' . $filePathPDF . '" data-bankFile="' . $bankFile . '">&nbsp;</span></html>';
    // output the HTML content
    $pdf->writeHTML($html, true, false, false, false, '');
    // reset pointer to the last page
    $pdf->lastPage();
    // Close and output PDF document
    $pdf->Output(__DIR__ . '/makepayment/' . $fileName . '.pdf', 'F');
    $status = 'CLOSED';
    updatePayrunDetails($mysqli,$payrunId,$status); //save payrun closing
    updateTimesheetPayrun($mysqli,$payrunId,1);
echo $html;
}else{
echo 'bsb'.$bsbStatus;
}
?>