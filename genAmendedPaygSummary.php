<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 8/08/2019
 * Time: 12:48 PM
 */
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
require_once('includes/empdupe/vendor/autoload.php');
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

use Swarnajith\Empdupe\EmpdupeGenerator;
use Swarnajith\Empdupe\model\Payee;
use Swarnajith\Empdupe\model\PayerIdentity;
use Swarnajith\Empdupe\model\Supplier;

$fromDate = $_REQUEST['startDate'];
$toDate = $_REQUEST['endDate'];
$fileExtType = '.DAT';
$year = date("Y", strtotime($toDate));
$payData = getAmendedPayrunDataForPayg($mysqli, $fromDate, $toDate);
$candidateId = '';
$row = '';
$totalGross = 0;
$paygTax = 0;
$len = sizeof($payData);
$k = 0;
$category = '';
$allowance = 0;
$canId = '';
$allowanceArray = array();
$totalAllowancArray = array();
$paygArray = array();
$payeeArray = array();

foreach ($payData as $data) {

    if (empty($candidateId)) {
        $candidateId = $data['candidateId'];
    }
    if (empty($category)) {
        $category = $data['category'];
    }
    if ($candidateId == $data['candidateId']) {
        if ($data['itemType'] == 14) {
            if ($category == $data['category']) {
                $allowance = $allowance + $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $totalAllowancArray[$data['candidateId']] + $data['amount'];
            }elseif ($category <> $data['category']){
                $category = '';
                $category = $data['category'];
                $allowance = $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $totalAllowancArray[$data['candidateId']] + $data['amount'];
            }
        }
        if ($data['itemType'] == 9) {
            $totalGross = $totalGross + $data['gross'];
            $paygArray[$data['candidateId']][$data['category']] = $totalGross;
        }
        if ($data['itemType'] == 11) {
            $paygTax = $paygTax + $data['paygTax'];
            $paygArray[$data['candidateId']][$data['category']] = $paygTax;
        }
    } else if ($candidateId <> $data['candidateId']) {
        $category = '';
        $allowance = 0;
        $totalGross = 0;
        $paygTax = 0;
        if ($data['itemType'] == 14) {
            if ($category == $data['category']) {
                $allowance = $allowance + $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            } elseif ($category <> $data['category']) {
                $category = '';
                $category = $data['category'];
                $allowance = $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            }
        }
        $candidateId = $data['candidateId'];
        if ($data['itemType'] == 9) {
            $totalGross = $data['gross'];
            $paygArray[$data['candidateId']][$data['category']] = $totalGross;
        }
        if ($data['itemType'] == 11) {
            $paygTax = $data['paygTax'];
            $paygArray[$data['candidateId']][$data['category']] = $paygTax;
        }
    }

    if ($k == $len - 1) {
        if ($data['itemType'] == 14) {
            if ($category == $data['category']) {
                $allowance = $allowance + $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            } elseif ($category <> $data['category']) {
                $category = '';
                $category = $data['category'];
                $allowance = $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            }
        }
        if ($data['itemType'] == 9) {
            $paygArray[$data['candidateId']][$data['category']] = $totalGross;
        }
        if ($data['itemType'] == 11) {
            $paygArray[$data['candidateId']][$data['category']] = $paygTax;
        }
    }
    $k++;
}
$htmlData = '';
$dataLength = sizeof($paygArray);
$itr = 0;
foreach ($paygArray as $key => $value) {
    $html = '';
    $htmlAllowance = '';
    $totalAllowance = '';
    foreach ($totalAllowancArray as $aKey => $aValue) {
        if ($aKey == $key) {
            $htmlAllowance = $htmlAllowance.$aValue;
            $totalAllowance = $aValue;
        }
    }
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
}
.subheading{
    font-size: 9pt;
    font-weight: bold;
}
</style><table border="0" cellspacing="5" cellpadding="5" width="100%">
  <tr>
    <td colspan="3" align="center"><strong><span class="heading">PAYG Payment summary - individual non-business</span></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center"><strong><span class="subheading">Payment Summary for the year ending 30 June ' . $year . '</span></strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="2" align="center"><strong>NOTICE TO PAYEE</strong></td>
  </tr>
  <tr>
    <td class="leftColumn"><strong>Payee Details</strong></td>
    <td class="middleColumn"></td>
    <td class="rightColumn">If this payment summary shows an amount in the total tax withheld box you must lodge a tax return. If no tax was with held you may still have to lodge a tax return. </td>
  </tr>
  <tr>
    <td class="leftColumn">'.strtoupper(getCandidateFullName($mysqli, $key)).'<br>'. getCandidateStreetById($mysqli, $key) . '&nbsp;' . getCandidateStreetNameById($mysqli, $key) . '<br>' . getCandidateSuburb($mysqli, $key) . ' ,' . getCandidateState($mysqli, $key) . '&nbsp;' . getCandidatePostcode($mysqli, $key) . ' </td>
    <td class="middleColumn"></td>
    <td  class="rightColumn"> For more information on whether you have to lodge, or about this payment and how it is taxed, you can; </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td align="left"><ul>
      <li style="list-style-type: square;">visit <strong>ato.gov.au</strong></li>
      <li style="list-style-type: square;">refer to Tax Packphone <strong>13 28 61</strong> between 8.00am and 6.00pm, Monday to Friday.</li>
    </ul></td>
  </tr>
  <tr>
    <td colspan="3" align="center">Day/Month/Year &nbsp;&nbsp; &nbsp;&nbsp; Day/Month/Year</td>
  </tr>
  <tr>
    <td> Period of Payment </td>
    <td colspan="2">&nbsp;' . date('d/m/Y', strtotime($fromDate)) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; to &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . date('d/m/Y', strtotime($toDate)) . '</td>
  </tr>
  <tr>
    <td colspan="2"> Payee\'s Tax File Number &nbsp;&nbsp;&nbsp;'. chunk_split(getCandidateTFN($mysqli, $key), 3, ' ') . '</td>
    <td class="boxBorder" align="right">
        <table align="right">
            <tr>
                <td align="right">
                    <br>
                        <strong>TOTAL TAX WITHHELD</strong>&nbsp;&nbsp;<strong>$</strong>
                </td>
                <td align="right">        
                    <table>
                        <tr>
                            <td class="boxBorderSmall" align="right">&nbsp;'. $value['PAYG Tax'].'&nbsp;&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="center">
    <table border="0" cellpadding="4" cellspacing="4" width="100%">
        <tr>
            <td class="payTitle"></td>
            <td class="dollarSign"></td>
            <td class="textTitle"></td>
            <td class="typeTitle">Type</td>
            <td></td>
            <td class="dollarSign"></td>
            <td class="dollarSign"></td>
            <td class="textTitle">Lump sum payments</td>
            <td class="typeTitle">Type</td>
        </tr>
        <tr>
            <td class="payTitle">Gross Payments</td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField" align="right">' . ($value['Gross'] - $totalAllowance). '</td>
            <td class="typeTitle"><table><tr><td class="typeBorder" align="center">S</td></tr></table></td>
            <td></td>
            <td class="dollarSign"><strong>A</strong></td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"><table><tr><td class="typeBorder" align="center"></td></tr></table></td>
        </tr>
        <tr>
            <td class="payTitle">CDEP Payments</td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"></td>
            <td></td>
            <td class="dollarSign"><strong>B</strong></td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"></td>
        </tr>
        <tr>
            <td class="payTitle">Reportable employer superannuation contributions</td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"></td>
            <td></td>
            <td class="dollarSign"><strong>D</strong></td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"></td>
        </tr>
        <tr>
            <td class="payTitle">Reportable fringe benefits amount</td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"></td>
            <td></td>
            <td class="dollarSign"><strong>E</strong></td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField"></td>
            <td class="typeTitle"></td>
        </tr>
        <tr>
            <td class="payTitle">Is the employer exempt from FBT under section 57A of the FBT AA1986?</td>
            <td></td>
            <td align="left"><table><tr><td>No &nbsp;<table><tr><td class="typeBorder" align="center"></td></tr></table></td><td> Yes &nbsp;<table><tr><td class="typeBorder" align="center"></td></tr></table></td></tr></table></td>
            <td class="typeTitle"></td>
            <td></td>
            <td class="dollarSign"></td>
            <td class="dollarSign"></td>
            <td></td>
            <td class="typeTitle"></td>
            </tr>
        <tr>
            <td class="payTitle">Total Allowances</td>
            <td class="dollarSign"><strong>$</strong></td>
            <td class="textField" align="right">&nbsp;';
    $html = $html.$htmlAllowance;
    $html = $html . '</td><td class="allowanceNote" colspan="6">Total Allowances are not included in Gross payments above. this amount needs to be shown separately in your tax return. </td>
        </tr>
        <tr>
            <td class="payTitle" colspan="9"><strong>Allowances:</strong></td>
        </tr>';
    $numAllowances = 0;
    foreach ($allowanceArray as $allKey => $allValue) {
        if ($allKey == $key) {
            $html = $html . '<tr><td colspan="9" align="left">';
            $numAllowances = sizeof($allValue);
            foreach ($allValue as $alk => $alv) {
                $html = $html . $alk . ' ' . $alv . '<br>';
            }
            $html = $html . '</td></tr>';
        }
    }
    $html = $html . '</table></td></tr>';
    if($numAllowances == 0) {
        $html = $html . '<tr>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>
  <tr>
    <td colspan="3"></td>
  </tr>';
    }else if($numAllowances > 0){
        $html = $html . '<tr>
    <td colspan="3"></td>
  </tr>';
    }
    $html = $html . '<tr>
    <td colspan="3"><hr></td>
  </tr>
  <tr>
    <td colspan="3" align="left"><strong>Payer Details</strong></td>
  </tr>
  <tr>
    <td colspan="3">Payer\'s ABN or withholding payer number ' . chunk_split(getCompanyABN($mysqli, 1), 4, ' ') . ' &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Branch number</td>
  </tr>
  <tr>
    <td colspan="3">Payer\'s name ' . getCompanyName($mysqli) . ' </td>
  </tr>
  <tr>
    <td colspan="3"><strong>Privacy</strong> - For information about your privacy, go to <strong>ato.gov.au/Privacy</strong></td>
  </tr>
  <tr>
    <td colspan="3" height="20px" class="fullBorder"> Signature of authorised person &nbsp; &nbsp; &nbsp; &nbsp;' . getClientAccountManager($mysqli, 1) . '
  Date &nbsp; ' . date('d/m/Y') . ' </td>
  </tr>
</table>';
    if ($itr == $dataLength - 1) {
        $html = $html.'';
        $htmlData = $htmlData.$html;
    }else{
        //$html = $html.'<br pagebreak="true">';
        $htmlData = $htmlData.$html.'<br pagebreak="true">';
    }

    $payee = new Payee('DINB', 'A', ' ', ' ');//lump T fbt N
    $payee->setIncomeType('S');
    $payee->setFirstName(getCandidateFirstNameByCandidateId($mysqli,$key));
    $payee->setLastName(getCandidateLastNameByCandidateId($mysqli,$key));
    $payee->setMiddleName('');
    $payee->setDob(getCandidateDOBById($mysqli,$key));
    $payee->setResidentialAddressLine1(getCandidateStreetById($mysqli,$key));
    $payee->setResidentialAddressLine2(getCandidateStreetNameById($mysqli,$key));
    $payee->setSuburb(getCandidateSuburb($mysqli,$key));
    $payee->setState(getCandidateState($mysqli,$key));
    $payee->setCountry('');
    $payee->setCommunityDevelopmentEmploymentProject('');
    $payee->setZeroFiller(00000000);
    $payee->setReportableFringeBenefit('');
    $payee->setReportableEmployerSuperAnnuationContribution('');
    $payee->setWorkplaceGiving('');
    $payee->setUnionFess('');
    $payee->setForeignEmploymentIncome('');
    $payee->setDeductiblePurchasePriceAnnuity('');
    $payee->setPostCode(getCandidatePostcode($mysqli,$key));
    $payee->setTfn(getCandidateTFN($mysqli,$key));
    $payee->setTotalTaxWithHeld($value['PAYG Tax']);
    $payee->setGrossPayment(($value['Gross'] - $totalAllowance));
    $payee->setTotalAllowance($totalAllowance);
    $payee->setLumpsumA('');
    $payee->setLumpsumB('');
    $payee->setLumpsumD('');
    $payee->setLumpsumE('');
    $payeeArray[] = $payee;

    $itr++;
}

$currentDate = date('Y-m-d');
$financialYear = date('Y',strtotime($toDate));
$companyName = getCompanyName($mysqli);
$companyId = getCompanyIdByName($mysqli,$companyName);
$companyABN = getCompanyABN($mysqli,$companyId);
$companyAddressLine1 = getCompanyAddressLine1($mysqli,$companyId);
$companyAddressLine2 = getCompanyAddressLine2($mysqli,$companyId);
$companySuburb = getCompanySuburb($mysqli,$companyId);
$companyState = getCompanyState($mysqli,$companyId);
$companyPostCode = getCompanyPostCode($mysqli,$companyId);
$companyPhone = getCompanyPhone($mysqli,$companyId);
$companyFax = getCompanyFax($mysqli,$companyId);
$companyEmail = getRemittanceEmail($mysqli,$companyId);
$payerIdentity = new PayerIdentity();
$payerIdentity->setPayerName($companyName);
$payerIdentity->setContactEmail($companyEmail);
$payerIdentity->setPayerAbn($companyABN);
$payerIdentity->setBranchNumber('001');
$payerIdentity->setFinancialYear($financialYear);
$payerIdentity->setTradingName($companyName);
$payerIdentity->setPayerAddressLine1($companyAddressLine1);
$payerIdentity->setPayerAddressLine2($companyAddressLine2);
$payerIdentity->setPayerSuburb($companySuburb);
$payerIdentity->setPayerState($companyState);
$payerIdentity->setPayerPostCode($companyPostCode);
$payerIdentity->setPayerCountry('');
$payerIdentity->setPayerContactName('Perry');
$payerIdentity->setPayerContactPhone($companyPhone);
$payerIdentity->setPayerContactFacsimileNumber($companyFax);

$supplier = new Supplier();
$supplier->setSupplierName($companyName);
$supplier->setAbn($companyABN);
$supplier->setTradingName($companyName);
$supplier->setAddressLine1($companyAddressLine1);
$supplier->setAddressLine2($companyAddressLine2);
$supplier->setState($companyState);
$supplier->setPostCode($companyPostCode);
$supplier->setContactName('Contact name');
$supplier->setContactNumber($companyPhone);
$supplier->setFacsmileNumber($companyFax);
$supplier->setFileReferenceNumber('');
$supplier->setSuburb($companySuburb);
$supplier->setCountry('');//blank if Australia, 9999 if other country
$supplier->setPostalAddressLine1($companyAddressLine1);
$supplier->setPostalAddressLine2($companyAddressLine2);
$supplier->setPostalSuburb($companySuburb);
$supplier->setPostalState($companyState);
$supplier->setPostalPostCode($companyPostCode);
$supplier->setPostalCountry('');
$supplier->setEmailAddress($companyEmail);
$supplier->setFinancialYear($financialYear);

//echo var_dump($payeeArray);
$empdupeFilePath = generateEmpdupe($toDate,$fromDate,$toDate,$financialYear,$payerIdentity,$supplier,$payeeArray,$fileExtType);
echo $empdupeFilePath;
//echo $htmlData;

function generateEmpdupe($currentDate,$fromDate,$toDate,$financialYear,$payerIdentity,$supplier,$payeeArray,$fileExtType){
    $generator = new EmpdupeGenerator($currentDate,$fromDate,$toDate,$financialYear);
    //File breaking to be done here. If amended .A01 .A02 etc.
    $fileReferenceNumber = 'chdiska'.(sizeof($payeeArray)+6);
    $supplier->setFileReferenceNumber($fileReferenceNumber);
    $rawData = $generator->generate($payerIdentity,$supplier,$payeeArray);
    $paygFileName = 'EMPDUPEA'.time().$fileExtType;
    $paygFilePath = './payg/' . $paygFileName;
    if (!file_exists('./payg/' . $paygFilePath)) {
        $empdupeFile = $paygFilePath;
    } else {
        $empdupeFile = $paygFilePath;
    }
    $len = strlen($rawData);
    $remainder = fmod(strlen($rawData),628);
    if($remainder == 0) {
        file_put_contents($empdupeFile, $rawData);
        return $empdupeFile;
    }else{
        return 'File length Incorrect'.$len;
    }
}
