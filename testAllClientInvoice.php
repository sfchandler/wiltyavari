<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/11/2017
 * Time: 12:05 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
require_once('includes/fpdf182/fpdf.php');
require_once('includes/FPDI-2.3.2/src/autoload.php');
require_once('includes/FPDI-2.3.2/src/FpdfTpl.php');
use setasign\Fpdi\Fpdi;
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
//ini_set('max_execution_time', 0);
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
/*$clientId = $_REQUEST['clientid'];
$clientCode = getClientCodeById($mysqli,$clientId);*/
$jobCode = $_REQUEST['jobCode'];
$weekendingDate = $_REQUEST['weekendingDate'];
$invDate = $_REQUEST['invoiceDate'];
$payrollName = $_REQUEST['payrollName'];
$invoiceDate = date('d M Y',strtotime($invDate));
$companyId = $_REQUEST['companyId'];
$logoFileName = getCompanyLogoById($mysqli,$companyId);
$remittanceEmail = getRemittanceEmail($mysqli,$companyId);
$companyAddress = getCompanyAddress($mysqli,$companyId);
$companyPhone = getCompanyPhone($mysqli,$companyId);
$companyFax = getCompanyFax($mysqli,$companyId);
$companyBankAccount = getCompanyBankAccountInfo($mysqli,$companyId);
$abn = getCompanyABN($mysqli,$companyId);
$website = getCompanyWebsite($mysqli,$companyId);
$resultBreak = $_REQUEST['resultBreak'];
$clId = $_REQUEST['clId'];
if(empty($clId)) {
    switch ($resultBreak) {
        case 5:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 0, 5);
            break;
        case 10:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 5, 5);
            break;
        case 15:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 10, 5);
            break;
        case 20:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 15, 5);
            break;
        case 25:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 20, 5);
            break;
        case 30:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 25, 5);
            break;
        case 35:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 30, 5);
            break;
        case 40:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 35, 5);
            break;
        case 45:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 40, 5);
            break;
        case 50:
            $allClients = getInvoiceClientsBetween($mysqli, $weekendingDate, $payrollName, 45, 5);
            break;
        default:
            $allClients = getAllInvoiceClients($mysqli, $weekendingDate, $payrollName);
            break;
        }
    }else{
        $allClients = getInvoiceClient($mysqli, $weekendingDate, $payrollName,$clId);
    }
//$allClients = getAllInvoiceClients($mysqli, $weekendingDate, $payrollName);
//$allClients = getInvoiceClient($mysqli, $weekendingDate, $payrollName,1);
class INVOICEPDF extends TCPDF
{
    public $invNumber;
    public $invoiceDate;
    public $remittanceEmail;
    public $companyAddress;
    public $companyPhone;
    public $companyFax;
    public $companyBankAccount;
    public $abn;
    public $website;
    public $logoFileName;
    public $companyId;
    public $companyEFTNotes;
    /**
     * @return mixed
     */
    public function getCompanyEFTNotes()
    {
        return $this->companyEFTNotes;
    }
    /**
     * @param mixed $companyEFTNotes
     */
    public function setCompanyEFTNotes($companyEFTNotes)
    {
        $this->companyEFTNotes =  'Bank Name : Chandler Recruitment  BSB: 012-366  Account Number : 838089782';
    }
    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }
    /**
     * @param mixed $companyId
     */
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }
    /**
     * @return mixed
     */
    public function getLogoFileName()
    {
        return $this->logoFileName;
    }
    /**
     * @param mixed $logoFileName
     */
    public function setLogoFileName($logoFileName)
    {
        $this->logoFileName = $logoFileName;
    }
    /**
     * @return mixed
     */
    public function getWebsite()
    {
        return $this->website;
    }
    /**
     * @param mixed $website
     */
    public function setWebsite($website)
    {
        $this->website = $website;
    }
    /**
     * @return mixed
     */
    public function getAbn()
    {
        return $this->abn;
    }
    /**
     * @param mixed $abn
     */
    public function setAbn($abn)
    {
        $this->abn = $abn;
    }
    /**
     * @return mixed
     */
    public function getCompanyBankAccount()
    {
        return $this->companyBankAccount;
    }
    /**
     * @param mixed $companyBankAccount
     */
    public function setCompanyBankAccount($companyBankAccount)
    {
        $this->companyBankAccount = $companyBankAccount;
    }
    /**
     * @return mixed
     */
    public function getCompanyAddress()
    {
        return $this->companyAddress;
    }
    /**
     * @param mixed $companyAddress
     */
    public function setCompanyAddress($companyAddress)
    {
        $this->companyAddress = $companyAddress;
    }
    /**
     * @return mixed
     */
    public function getCompanyPhone()
    {
        return $this->companyPhone;
    }
    /**
     * @param mixed $companyPhone
     */
    public function setCompanyPhone($companyPhone)
    {
        $this->companyPhone = $companyPhone;
    }
    /**
     * @return mixed
     */
    public function getCompanyFax()
    {
        return $this->companyFax;
    }
    /**
     * @param mixed $companyFax
     */
    public function setCompanyFax($companyFax)
    {
        $this->companyFax = $companyFax;
    }
    /**
     * @return mixed
     */
    public function getRemittanceEmail()
    {
        return $this->remittanceEmail;
    }
    /**
     * @param mixed $remittanceEmail
     */
    public function setRemittanceEmail($remittanceEmail)
    {
        $this->remittanceEmail = $remittanceEmail;
    }
    /**
     * @return mixed
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }
    /**
     * @param mixed $invoiceDate
     */
    public function setInvoiceDate($invoiceDate)
    {
        $this->invoiceDate = $invoiceDate;
    }
    /**
     * @return mixed
     */
    public function getInvNumber()
    {
        return $this->invNumber;
    }
    /**
     * @param mixed $invNumber
     */
    public function setInvNumber($invNumber)
    {
        $this->invNumber = $invNumber;
    }
    //Page header
    public function Header()
    {
        // Logo ChandlerPersonnel.jpg
        $image_file = $this->getLogoFileName();
        $this->Image($image_file, 5, 5, 0, 0, 'PNG', '', 'L', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'R', 8);
        $this->Ln(30);
        $this->Cell(0, '', $this->getInvoiceDate(), 0, $ln = 0, 'L', 0, '', 0, false, 'B', 'B');
        $this->Ln(5);
        $this->SetFont('helvetica', 'B', 25);
        $this->Cell(0, '', 'T A X   I N V O I C E', 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
        $this->Ln(4);
        $this->SetFont('helvetica', 'B', 7);
        $this->Cell(0, '', ' ', 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
        $this->Ln(4);
        $this->SetFont('helvetica', 'B', 7);
        $this->Cell(0, '', 'ABN: ' . $this->getAbn(), 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
        $this->Ln(5);
        $this->Cell(0, '', '        ', 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
        $this->Ln(5);
        $margin = $this->getMargins();
        /*$style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $this->Line(5, 50, 205, 50, $style);*/
        /*$this->SetY($margin['top']+20);*/
    }
    // Page footer
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
        $this->write2DBarcode($this->getWebsite(), 'QRCODE,H', 5, 282, 14, 14, $style, 'N');
        $this->SetFont('helvetica', 'R', 8);
        $this->SetY(-19);
        $this->Cell(0,10,'                   This debt has been assigned to  . All Payments must be made to   or Cheque as per details outlined.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-15);
        $this->Cell(0,10,'                   Payments made in any other way will not extinguish this debt.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-11);
        $this->Cell(0, 10,'                   Payment Preferred by EFT: '.$this->getCompanyBankAccount().'', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-7);
        $this->Cell(0, 10, '                   ' . $this->getCompanyAddress() . '  Telephone: ' . $this->getCompanyPhone() . '  Fax: ' . $this->getCompanyFax() . ' ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-4);
        //$this->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    }
}

function summaryData($html, $ratesArray)
{
    $html = $html . '<tr><td colspan="3"><br/></td></tr><tr><td colspan="3" class="lblBold">Totals for this invoice:</td></tr>';
    foreach ($ratesArray as $pKey => $rates) {
        if ($ratesArray[$pKey]['emgTotal'] > 0) {
            $html = $html . '<tr><td>EARLY MORNING(' . $ratesArray[$pKey]['emgTotal'] . ' Hours @ $' . $ratesArray[$pKey]['emgChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['ordTotal'] > 0) {
            $html = $html . '<tr><td>ORDINARY RATE(' . $ratesArray[$pKey]['ordTotal'] . ' Hours @ $' . $ratesArray[$pKey]['ordChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['aftTotal'] > 0) {
            $html = $html . '<tr><td>AFTERNOON RATE(' . $ratesArray[$pKey]['aftTotal'] . ' Hours @ $' . $ratesArray[$pKey]['aftChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['nightTotal'] > 0) {
            $html = $html . '<tr><td>NIGHT RATE(' . $ratesArray[$pKey]['nightTotal'] . ' Hours @ $' . $ratesArray[$pKey]['nightChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['satTotal'] > 0) {
            $html = $html . '<tr><td>SATURDAY RATE(' . $ratesArray[$pKey]['satTotal'] . ' Hours @ $' . $ratesArray[$pKey]['satChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['sunTotal'] > 0) {
            $html = $html . '<tr><td>SUNDAY RATE(' . $ratesArray[$pKey]['sunTotal'] . ' Hours @ $' . $ratesArray[$pKey]['sunChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['ovtTotal'] > 0) {
            $html = $html . '<tr><td>OVERTIME RATE(' . $ratesArray[$pKey]['ovtTotal'] . ' Hours @ $' . $ratesArray[$pKey]['ovtChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['satovtTotal'] > 0) {
            $html = $html . '<tr><td>SATURDAY OVERTIME RATE(' . $ratesArray[$pKey]['satovtTotal'] . ' Hours @ $' . $ratesArray[$pKey]['satovtChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['sunovtTotal'] > 0) {
            $html = $html . '<tr><td>SUNDAY OVERTIME RATE(' . $ratesArray[$pKey]['sunovtTotal'] . ' Hours @ $' . $ratesArray[$pKey]['sunovtChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['povtTotal'] > 0) {
            $html = $html . '<tr><td>PERIOD OVERTIME RATE(' . $ratesArray[$pKey]['povtTotal'] . ' Hours @ $' . $ratesArray[$pKey]['povtChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['dblTotal'] > 0) {
            $html = $html . '<tr><td>DOUBLETIME RATE(' . $ratesArray[$pKey]['dblTotal'] . ' Hours @ $' . $ratesArray[$pKey]['dblChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['hldTotal'] > 0) {
            $html = $html . '<tr><td>PUBLIC HOLIDAY RATE(' . $ratesArray[$pKey]['hldTotal'] . ' Hours @ $' . $ratesArray[$pKey]['hldChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['hol_total'] > 0) {
            $html = $html . '<tr><td>PUBLIC HOLIDAY 2 RATE(' . $ratesArray[$pKey]['hol_total'] . ' Hours @ $' . $ratesArray[$pKey]['hld2ChargeRate'] . ')</td><td></td><td></td></tr>';
        }
        if ($ratesArray[$pKey]['rdoTotal'] > 0) {
            $html = $html . '<tr><td>RDO RATE(' . $ratesArray[$pKey]['rdoTotal'] . ' Hours @ $' . $ratesArray[$pKey]['rdoChargeRate'] . ')</td><td></td><td></td></tr>';
        }
    }
    return $html;
}

function printTotals($html, $total)
{
    $total = round($total, 2);
    $totalAmount = round((($total * (10 / 100)) + $total), 2);
    $html = $html . '<tr>
                        <td align="right" width="60%" class="lblBold">Total</td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td align="right" width="20%" class="lblBold">$' . number_format($total, 2) . '</td>
                        <td width="10%"></td></tr>';
    $html = $html . '<tr>
                        <td align="right" width="60%" class="lblBold">GST</td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td align="right" width="20%" class="lblBold">$' . number_format(($total * 10 / 100), 2) . '</td>
                        <td width="10%"></td></tr>';
    $html = $html . '<tr>
                        <td align="right" width="60%" class="lblAmount">Total Amount</td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td align="right" width="20%" class="lblAmount">$' . number_format($totalAmount, 2) . '</td>
                        <td width="10%"></td></tr>';
    $html = $html . '<tr>
                        <td align="right" width="60%" class="lblBold"><i>(Including GST)</i></td>
                        <td width="5%"></td>
                        <td width="5%"></td>
                        <td width="20%"></td>
                        <td width="10%"></td></tr>';
    return $html;
}

function printTerms($html, $clientCode, $invoiceNo, $accountManager,$clientName,$clientAddress, $terms, $invoiceDate,$termGap)
{
    /*$html = $html . '<tr><td colspan="2" style="font-size: 6pt; font-weight: bold;">Terms:' . $terms . '</td><td></td></tr>';
    $html = $html . '<tr>
                         <td colspan="2" style="font-size: 6pt" width="60%"></td>
                         <td width="30%" align="left" style="font-size: 14pt; font-weight: bold">DUE DATE
                            <br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '
                         </td>
                   </tr>';
    return $html;*/
    $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .$clientCode. '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .$accountManager. '
                            <br>' .$clientName.'
                            <br>' .$clientAddress.'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
    return $html;
}

/*if(checkInvoiceGeneration($mysqli,$weekendingDate) == 'FALSE') {*/
    $fileArray = array();

    foreach ($allClients as $allClient) {
        $html = ' ';
        $invData = getInvoiceTimeSheetTotalsByClient($mysqli, $weekendingDate, $payrollName, $jobCode, $allClient);
        $clientCode = getClientCodeById($mysqli,$allClient);
        if (!empty($invData) || ($invData != '')) {

            $pdf = new INVOICEPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
            $pdf->setHeaderTemplateAutoreset(true);
            $pdf->setInvoiceDate($invoiceDate);
            $pdf->setCompanyId($companyId);
            $pdf->setRemittanceEmail($remittanceEmail);
            $pdf->setCompanyAddress($companyAddress);
            $pdf->setCompanyPhone($companyPhone);
            $pdf->setCompanyFax($companyFax);
            $pdf->setCompanyBankAccount($companyBankAccount);
            $pdf->setAbn($abn);
            $pdf->setWebsite($website);
            $pdf->setLogoFileName($logoFileName);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(' ');
            $pdf->SetTitle('Invoice');
            $pdf->SetSubject(' ');
            $pdf->SetKeywords(' ');
            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetMargins(PDF_MARGIN_LEFT, 55, PDF_MARGIN_RIGHT);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM+40);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            $pdf->SetFont('helvetica', '', 8);
            $pdf->startPageGroup();
            $pdf->setImageScale(1.53);
            $pdf->AddPage();

            $canId;
            $jbCode;
            $total = 0;
            $emgTot = 0;
            $ordTot = 0;
            $aftTot = 0;
            $nightTot = 0;
            $satTot = 0;
            $sunTot = 0;
            $ovtTot = 0;
            $satovtTot = 0;
            $sunovtTot = 0;
            $povtTot = 0;
            $dblTot = 0;
            $hldTot = 0;
            $hld2Tot = 0;
            $rdoTot = 0;

            $emgTotal = 0;
            $ordTotal = 0;
            $aftTotal = 0;
            $nightTotal = 0;
            $satTotal = 0;
            $sunTotal = 0;
            $ovtTotal = 0;
            $satovtTotal = 0;
            $sunovtTotal = 0;
            $povtTotal = 0;
            $dblTotal = 0;
            $hldTotal = 0;
            $hld2Total = 0;
            $rdoTotal = 0;

            $emgChargeRate = 0;
            $ordChargeRate = 0;
            $aftChargeRate = 0;
            $nightChargeRate = 0;
            $satChargeRate = 0;
            $sunChargeRate = 0;
            $ovtChargeRate = 0;
            $satovtChargeRate = 0;
            $sunovtChargeRate = 0;
            $povtChargeRate = 0;
            $dblChargeRate = 0;
            $hldChargeRate = 0;
            $hld2ChargeRate = 0;
            $rdoChargeRate = 0;

            $html = $html . '<style>table{border:1px solid black}.caps{text-transform: uppercase}hr{ text-decoration-style: solid; width: 860px; }.lblAmount{font-weight: bold;font-size: 11pt}.lblBold{font-weight: bold;}.empName{font-style: italic;}</style><div>
                            <br><br><table width="950px" nobr="true">
                            <thead></thead>
                            <tbody class="invBody">';
            $ratesArray = array();
            $jobCodeArray = array();
            foreach ($invData as $value) {
                $ratesArray[$value['jobcode']] = array('emgTotal' => '', 'emgChargeRate' => '', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '', 'hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal' => '', 'rdoChargeRate' => '');
            }
            $i = 0;
            $len = count($invData);
            $client = '';
            foreach ($invData as $data) {
                $termId = getTermIdByClientId($mysqli, $data['clientId']);
                $terms = getPaymentTermByTermId($mysqli, $termId);
                $termGap = getPaymentTermGapByTermId($mysqli, $termId);
                $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(175, 175, 175)));
                $invType = getClientInvoiceType($mysqli, $data['clientId']);
                if ($invType == 'Each Employee') {
                    $total = 0;
                    $emgTot = 0;
                    $ordTot = 0;
                    $aftTot = 0;
                    $nightTot = 0;
                    $satTot = 0;
                    $sunTot = 0;
                    $ovtTot = 0;
                    $satovtTot = 0;
                    $sunovtTot = 0;
                    $povtTot = 0;
                    $dblTot = 0;
                    $hldTot = 0;
                    $hld2Tot = 0;
                    $rdoTot = 0;

                    $emgTotal = 0;
                    $ordTotal = 0;
                    $aftTotal = 0;
                    $nightTotal = 0;
                    $satTotal = 0;
                    $sunTotal = 0;
                    $ovtTotal = 0;
                    $satovtTotal = 0;
                    $sunovtTotal = 0;
                    $povtTotal = 0;
                    $dblTotal = 0;
                    $hldTotal = 0;
                    $hld2Total = 0;
                    $rdoTotal = 0;

                    $emgChargeRate = 0;
                    $ordChargeRate = 0;
                    $aftChargeRate = 0;
                    $nightChargeRate = 0;
                    $satChargeRate = 0;
                    $sunChargeRate = 0;
                    $ovtChargeRate = 0;
                    $satovtChargeRate = 0;
                    $sunovtChargeRate = 0;
                    $povtChargeRate = 0;
                    $dblChargeRate = 0;
                    $hldChargeRate = 0;
                    $hld2ChargeRate = 0;
                    $rdoChargeRate = 0;

                    $invoiceNo = genNewInvoiceNo($mysqli);

                    if (empty($jbCode)) {
                        $jbCode = $data['jobcode'];
                    }
                    $client = $data['clientId'];

                    if (empty($canId)) {
                        $canId = $data['candidateId'];

                       /* $print_terms = printTerms($html,getClientCodeById($mysqli, $data['clientId']),$invoiceNo,getClientAccountManagerFromJobDetail($mysqli, $data['clientId'],$data['jobcode']),getClientNameByClientId($mysqli, $data['clientId']),getClientAddress($mysqli, $data['clientId']),$terms,$invoiceDate,$termGap);
                        $html = $html.$print_terms;
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $data['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $data['clientId']) . ', ' . getPositionByPositionId($mysqli, $data['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')
                        $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $data['candidateId'])) . '</td></tr>';*/

                        $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .getClientCodeById($mysqli, $client). '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .getClientAccountManagerFromJobDetail($mysqli, $client,$data['jobcode']). '
                            <br>' .getClientNameByClientId($mysqli, $client).'
                            <br>' .getClientAddress($mysqli, $client).'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $data['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $data['clientId']) . ', ' . getPositionByPositionId($mysqli, $data['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')

                    } else {

                        /*$print_terms = printTerms($html,getClientCodeById($mysqli, $data['clientId']),$invoiceNo,getClientAccountManagerFromJobDetail($mysqli, $data['clientId'],$data['jobcode']),getClientNameByClientId($mysqli, $data['clientId']),getClientAddress($mysqli, $data['clientId']),$terms,$invoiceDate,$termGap);
                        $html = $html.$print_terms;
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $data['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $data['clientId']) . ', ' . getPositionByPositionId($mysqli, $data['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')*/

                        $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .getClientCodeById($mysqli, $client). '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .getClientAccountManagerFromJobDetail($mysqli, $client,$data['jobcode']). '
                            <br>' .getClientNameByClientId($mysqli, $client).'
                            <br>' .getClientAddress($mysqli, $client).'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $data['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $data['clientId']) . ', ' . getPositionByPositionId($mysqli, $data['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')

                    }

                    $jobCodeArray[$data['jobcode']] = array();
                    if ($jbCode != $data['jobcode']) {
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $jbCode = $data['jobcode'];
                    }
                    if ($canId != $data['candidateId']) {
                        $canId = $data['candidateId'];
                        $html = $html . '<tr><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $data['candidateId'])) . '</td></tr>';
                    }

                    if ($data['emgTotal'] > 0) {
                        $emgTot = $emgTot + $data['emgTotal'];
                        $emgChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'EARLY MORNING'),$data['jobcode']);
                        $emgTotal = $data['emgTotal'] * $emgChargeRate;
                        $emgTotal = round($emgTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">EARLY MORNING(' . $data['emgTotal'] . ' Hours @ $' . $emgChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $emgTotal . '</td><td width="10%"></td></tr>';

                    }
                    if ($data['ordTotal'] > 0) {
                        $ordTot = $ordTot + $data['ordTotal'];
                        $ordChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'ORDINARY'),$data['jobcode']);
                        $ordTotal = $data['ordTotal'] * $ordChargeRate;
                        $ordTotal = round($ordTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">T1.0 ORDINARY TIME(' . $data['ordTotal'] . ' Hours @ $' . $ordChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $ordTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['aftTotal'] > 0) {
                        $aftTot = $aftTot + $data['aftTotal'];
                        $aftChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'AFTERNOON'),$data['jobcode']);
                        $aftTotal = $data['aftTotal'] * $aftChargeRate;
                        $aftTotal = round($aftTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">AFTERNOON RATE(' . $data['aftTotal'] . ' Hours @ $' . $aftChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $aftTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['nightTotal'] > 0) {
                        $nightTot = $nightTot + $data['nightTotal'];
                        $nightChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'NIGHT'),$data['jobcode']);
                        $nightTotal = $data['nightTotal'] * $nightChargeRate;
                        $nightTotal = round($nightTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">NIGHT RATE(' . $data['nightTotal'] . ' Hours @ $' . $nightChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $nightTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['satTotal'] > 0) {
                        $satTot = $satTot + $data['satTotal'];
                        $satChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SATURDAY'),$data['jobcode']);
                        $satTotal = $data['satTotal'] * $satChargeRate;
                        $satTotal = round($satTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SATURDAY RATE(' . $data['satTotal'] . ' Hours @ $' . $satChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $satTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['sunTotal'] > 0) {
                        $sunTot = $sunTot + $data['sunTotal'];
                        $sunChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SUNDAY'),$data['jobcode']);
                        $sunTotal = $data['sunTotal'] * $sunChargeRate;
                        $sunTotal = round($sunTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SUNDAY RATE(' . $data['sunTotal'] . ' Hours @ $' . $sunChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $sunTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['ovtTotal'] > 0) {
                        $ovtTot = $ovtTot + $data['ovtTotal'];
                        $ovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'OVERTIME'),$data['jobcode']);
                        $ovtTotal = $data['ovtTotal'] * $ovtChargeRate;
                        $ovtTotal = round($ovtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">OVERTIME RATE(' . $data['ovtTotal'] . ' Hours @ $' . $ovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $ovtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['satovtTotal'] > 0) {
                        $satovtTot = $satovtTot + $data['satovtTotal'];
                        $satovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SATURDAY OVERTIME'),$data['jobcode']);
                        $satovtTotal = $data['satovtTotal'] * $satovtChargeRate;
                        $satovtTotal = round($satovtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SATURDAY OVERTIME RATE(' . $data['satovtTotal'] . ' Hours @ $' . $satovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $satovtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['sunovtTotal'] > 0) {
                        $sunovtTot = $sunovtTot + $data['sunovtTotal'];
                        $sunovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SUNDAY OVERTIME'),$data['jobcode']);
                        $sunovtTotal = $data['sunovtTotal'] * $sunovtChargeRate;
                        $sunovtTotal = round($sunovtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SUNDAY OVERTIME RATE(' . $data['sunovtTotal'] . ' Hours @ $' . $sunovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $sunovtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['povtTotal'] > 0) {
                        $povtTot = $povtTot + $data['povtTotal'];
                        $povtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'PERIOD OVERTIME'),$data['jobcode']);
                        $povtTotal = $data['povtTotal'] * $povtChargeRate;
                        $povtTotal = round($povtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">PERIOD OVERTIME RATE(' . $data['povtTotal'] . ' Hours @ $' . $povtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $povtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['dblTotal'] > 0) {
                        $dblTot = $dblTot + $data['dblTotal'];
                        $dblChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'DOUBLETIME'),$data['jobcode']);
                        $dblTotal = $data['dblTotal'] * $dblChargeRate;
                        $dblTotal = round($dblTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">DOUBLETIME RATE(' . $data['dblTotal'] . ' Hours @ $' . $dblChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $dblTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['hldTotal'] > 0) {
                        $hldTot = $hldTot + $data['hldTotal'];
                        $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY'),$data['jobcode']);
                        $hldTotal = $data['hldTotal'] * $hldChargeRate;
                        $hldTotal = round($hldTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">PUBLIC HOLIDAY RATE(' . $data['hldTotal'] . ' Hours @ $' . $hldChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $hldTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['hol_total'] > 0) {
                        $hld2Tot = $hld2Tot + $data['hol_total'];
                        $hld2ChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2'),$data['jobcode']);
                        $hld2Total = $data['hol_total'] * $hld2ChargeRate;
                        $hld2Total = round($hld2Total, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">PUBLIC HOLIDAY 2 RATE(' . $data['hol_total'] . ' Hours @ $' . $hld2ChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $hld2Total . '</td><td width="10%"></td></tr>';
                    }
                    if ($data['rdoTotal'] > 0) {
                        $rdoTot = $rdoTot + $data['rdoTotal'];
                        $rdoChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'RDO'),$data['jobcode']);
                        $rdoTotal = $data['rdoTotal'] * $rdoChargeRate;
                        $rdoTotal = round($rdoTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">RDO RATE(' . $data['rdoTotal'] . ' Hours @ $' . $rdoChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $rdoTotal . '</td><td width="10%"></td></tr>';
                    }

                    $invoiceAdditionsEmployee = getInvoiceAddition($mysqli, $client, $weekendingDate, $data['candidateId'], $jbCode);
                    foreach ($invoiceAdditionsEmployee as $inAdd) {
                        $inAddAmount = $inAdd['amount'];
                        $html = $html . '<tr nobr="true"><td width="60%">' . $inAdd['description'] . '(' . $inAdd['units'] . ' Hours @ $' . $inAddAmount . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . number_format($inAddAmount, 2) . '</td><td width="10%"></td></tr>';
                        $total = $total + $inAddAmount;
                    }

                    $total = $total + ($emgTotal + $ordTotal + $aftTotal + $nightTotal + $satTotal + $sunTotal + $ovtTotal + $dblTotal + $hldTotal + $hld2Total + $rdoTotal);

                    $emgTotal = 0;
                    $ordTotal = 0;
                    $aftTotal = 0;
                    $nightTotal = 0;
                    $satTotal = 0;
                    $sunTotal = 0;
                    $ovtTotal = 0;
                    $dblTotal = 0;
                    $hldTotal = 0;
                    $hld2Total = 0;
                    $rdoTotal = 0;
                    if (!empty($ratesArray) && ($total != 0)) {
                        $y = $pdf->getY();
                        if (258 < $y) {
                            $span = 0;
                        } else if (258 > $y && 200 < $y) {
                            $span = 50;
                        } else {
                            $span = 50;
                        }
                        $html = $html . '<tr nobr="true"><td colspan="5" height="' . $span . '"></td></tr>';
                        $html = printTotals($html, $total);

                        $html = $html . '<br pagebreak="true">';
                    }

                }
                if ($i == $len - 1) {
                    $total = 0;
                    $emgTot = 0;
                    $ordTot = 0;
                    $aftTot = 0;
                    $nightTot = 0;
                    $satTot = 0;
                    $sunTot = 0;
                    $ovtTot = 0;
                    $satovtTot = 0;
                    $sunovtTot = 0;
                    $povtTot = 0;
                    $dblTot = 0;
                    $hldTot = 0;
                    $hld2Tot = 0;
                    $rdoTot = 0;

                    $emgTotal = 0;
                    $ordTotal = 0;
                    $aftTotal = 0;
                    $nightTotal = 0;
                    $satTotal = 0;
                    $sunTotal = 0;
                    $ovtTotal = 0;
                    $satovtTotal = 0;
                    $sunovtTotal = 0;
                    $povtTotal = 0;
                    $dblTotal = 0;
                    $hldTotal = 0;
                    $hld2Total = 0;
                    $rdoTotal = 0;

                    $emgChargeRate = 0;
                    $ordChargeRate = 0;
                    $aftChargeRate = 0;
                    $nightChargeRate = 0;
                    $satChargeRate = 0;
                    $sunChargeRate = 0;
                    $ovtChargeRate = 0;
                    $satovtChargeRate = 0;
                    $sunovtChargeRate = 0;
                    $povtChargeRate = 0;
                    $dblChargeRate = 0;
                    $hldChargeRate = 0;
                    $hld2ChargeRate = 0;
                    $rdoChargeRate = 0;
                }
                $i++;
            }
            $ratesArray = array();
            $jobCodeArray = array();
            foreach ($invData as $value) {
                $ratesArray[$value['jobcode']] = array('emgTotal' => '', 'emgChargeRate' => '', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '', 'hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal' => '', 'rdoChargeRate' => '');
            }
            $j = 0;
            $len = count($invData);
            $client = '';
            //$html = $html . '<tr nobr="true"><td colspan="5">'.$allClient.'</td></tr>';
            $eachInvData = getInvoiceTimeSheetTotalsByClient($mysqli, $weekendingDate, $payrollName, $jobCode, $allClient);
            $jbCode = '';
            foreach ($eachInvData as $eachJob) {
                    $termId = getTermIdByClientId($mysqli, $eachJob['clientId']);
                    $terms = getPaymentTermByTermId($mysqli, $termId);
                    $termGap = getPaymentTermGapByTermId($mysqli, $termId);
                    $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(175, 175, 175)));
                    $invType = getClientInvoiceType($mysqli, $eachJob['clientId']);
                    if ($invType == 'Each Job') {
                        if (empty($jbCode)) {
                            $total = 0;
                            $emgTot = 0;
                            $ordTot = 0;
                            $aftTot = 0;
                            $nightTot = 0;
                            $satTot = 0;
                            $sunTot = 0;
                            $ovtTot = 0;
                            $satovtTot = 0;
                            $sunovtTot = 0;
                            $povtTot = 0;
                            $dblTot = 0;
                            $hldTot = 0;
                            $hld2Tot = 0;
                            $rdoTot = 0;

                            $emgTotal = 0;
                            $ordTotal = 0;
                            $aftTotal = 0;
                            $nightTotal = 0;
                            $satTotal = 0;
                            $sunTotal = 0;
                            $ovtTotal = 0;
                            $satovtTotal = 0;
                            $sunovtTotal = 0;
                            $povtTotal = 0;
                            $dblTotal = 0;
                            $hldTotal = 0;
                            $hld2Total = 0;
                            $rdoTotal = 0;

                            $emgChargeRate = 0;
                            $ordChargeRate = 0;
                            $aftChargeRate = 0;
                            $nightChargeRate = 0;
                            $satChargeRate = 0;
                            $sunChargeRate = 0;
                            $ovtChargeRate = 0;
                            $satovtChargeRate = 0;
                            $sunovtChargeRate = 0;
                            $povtChargeRate = 0;
                            $dblChargeRate = 0;
                            $hldChargeRate = 0;
                            $hld2ChargeRate = 0;
                            $rdoChargeRate = 0;
                            $invoiceNo = genNewInvoiceNo($mysqli);
                            $client = $eachJob['clientId'];
                            $jbCode = $eachJob['jobcode'];

                            /*$print_terms = printTerms($html,getClientCodeById($mysqli, $eachJob['clientId']),$invoiceNo,getClientAccountManagerFromJobDetail($mysqli, $eachJob['clientId'],$eachJob['jobcode']),getClientNameByClientId($mysqli, $eachJob['clientId']),getClientAddress($mysqli, $eachJob['clientId']),$terms,$invoiceDate,$termGap);
                            $html = $html.$print_terms;
                            $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . ' ' . $eachJob['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $eachJob['clientId']) . ', ' . getPositionByPositionId($mysqli, $eachJob['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')*/

                            $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .getClientCodeById($mysqli, $client). '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .getClientAccountManagerFromJobDetail($mysqli, $client,$eachJob['jobcode']). '
                            <br>' .getClientNameByClientId($mysqli, $client).'
                            <br>' .getClientAddress($mysqli, $client).'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $eachJob['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $eachJob['clientId']);
                            if($eachJob['clientId'] == 294){
                                $html = $html.' '. getDepartmentById($mysqli,getDepartmentIdByJobCode($mysqli,$eachJob['jobcode']));
                            }
                            $html = $html . ', ' . getPositionByPositionId($mysqli, $eachJob['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')
                        }
                        if (empty($canId)) {
                            $canId = $eachJob['candidateId'];
                        }
                        if ($canId != $eachJob['candidateId']) {
                            $canId = $eachJob['candidateId'];
                            /*$html = $html . '<tr><td colspan="5"></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $eachJob['candidateId'])) . '</td></tr>';*/
                        }
                        /*$html = $html . '<tr><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $eachJob['candidateId'])) . '</td></tr>';*/

                        $jobCodeArray[$eachJob['jobcode']] = array();
                        if ($jbCode <> $eachJob['jobcode']) {
                            if (!empty($ratesArray)&& ($total != 0)) {
                                $html = summaryData($html, $ratesArray);
                                $y = $pdf->getY();
                                if (258 < $y) {
                                    $span = 0;
                                } else if (258 > $y && 200 < $y) {
                                    $span = 50;
                                } else {
                                    $span = 50;
                                }
                                $html = printTotals($html, $total);
                                $html = $html . '<br pagebreak="true">';
                            }
                            $total = 0;
                            $emgTot = 0;
                            $ordTot = 0;
                            $aftTot = 0;
                            $nightTot = 0;
                            $satTot = 0;
                            $sunTot = 0;
                            $ovtTot = 0;
                            $satovtTot = 0;
                            $sunovtTot = 0;
                            $povtTot = 0;
                            $dblTot = 0;
                            $hldTot = 0;
                            $hld2Tot = 0;
                            $rdoTot = 0;

                            $emgTotal = 0;
                            $ordTotal = 0;
                            $aftTotal = 0;
                            $nightTotal = 0;
                            $satTotal = 0;
                            $sunTotal = 0;
                            $ovtTotal = 0;
                            $satovtTotal = 0;
                            $sunovtTotal = 0;
                            $povtTotal = 0;
                            $dblTotal = 0;
                            $hldTotal = 0;
                            $hld2Total = 0;
                            $rdoTotal = 0;

                            $emgChargeRate = 0;
                            $ordChargeRate = 0;
                            $aftChargeRate = 0;
                            $nightChargeRate = 0;
                            $satChargeRate = 0;
                            $sunChargeRate = 0;
                            $ovtChargeRate = 0;
                            $satovtChargeRate = 0;
                            $sunovtChargeRate = 0;
                            $povtChargeRate = 0;
                            $dblChargeRate = 0;
                            $hldChargeRate = 0;
                            $hld2ChargeRate = 0;
                            $rdoChargeRate = 0;
                            $invoiceNo = genNewInvoiceNo($mysqli);


                            $jbCode = $eachJob['jobcode'];
                            $ratesArray = array();
                            foreach ($invData as $value) {
                                $ratesArray[$value['jobcode']] = array('emgTotal' => '', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '', 'hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal' => '', 'rdoChargeRate' => '');
                            }
                            $client = $eachJob['clientId'];
                            $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .getClientCodeById($mysqli, $client). '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .getClientAccountManagerFromJobDetail($mysqli, $client,$eachJob['jobcode']). '
                            <br>' .getClientNameByClientId($mysqli, $client).'
                            <br>' .getClientAddress($mysqli, $client).'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                            $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $eachJob['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $eachJob['clientId']);
                            if($eachJob['clientId'] == 294){
                                $html = $html.' '. getDepartmentById($mysqli,getDepartmentIdByJobCode($mysqli,$eachJob['jobcode']));
                            }
                            $html = $html. ', ' . getPositionByPositionId($mysqli, $eachJob['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';
                        }

                        $html = $html . '<tr><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $eachJob['candidateId'])) . '</td></tr>';

                        if ($eachJob['emgTotal'] > 0) {
                            $emgTot = $emgTot + $eachJob['emgTotal'];
                            $emgChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'EARLY MORNING'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['emgTotal'] = (float)$value['emgTotal'] + (float)$eachJob['emgTotal'];
                                    $ratesArray[$key]['emgChargeRate'] = $emgChargeRate;
                                }
                            }
                            $emgTotal = $eachJob['emgTotal'] * $emgChargeRate;
                            $emgTotal = round($emgTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">EARLY MORNING(' . $eachJob['emgTotal'] . ' Hours @ $' . $emgChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $emgTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['ordTotal'] > 0) {
                            $ordTot = $ordTot + $eachJob['ordTotal'];
                            $ordChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'ORDINARY'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['ordTotal'] = (float)$value['ordTotal'] + (float)$eachJob['ordTotal'];
                                    $ratesArray[$key]['ordChargeRate'] = $ordChargeRate;
                                }
                            }
                            $ordTotal = $eachJob['ordTotal'] * $ordChargeRate;
                            $ordTotal = round($ordTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">T1.0 ORDINARY TIME(' . $eachJob['ordTotal'] . ' Hours @ $' . $ordChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $ordTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['aftTotal'] > 0) {
                            $aftTot = $aftTot + $eachJob['aftTotal'];
                            $aftChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'AFTERNOON'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['aftTotal'] = (float)$value['aftTotal'] + (float)$eachJob['aftTotal'];
                                    $ratesArray[$key]['aftChargeRate'] = $aftChargeRate;
                                }
                            }
                            $aftTotal = $eachJob['aftTotal'] * $aftChargeRate;
                            $aftTotal = round($aftTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">AFTERNOON RATE(' . $eachJob['aftTotal'] . ' Hours @ $' . $aftChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $aftTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['nightTotal'] > 0) {
                            $nightTot = $nightTot + $eachJob['nightTotal'];
                            $nightChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'NIGHT'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['nightTotal'] = (float)$value['nightTotal'] + (float)$eachJob['nightTotal'];
                                    $ratesArray[$key]['nightChargeRate'] = $nightChargeRate;
                                }
                            }
                            $nightTotal = $eachJob['nightTotal'] * $nightChargeRate;
                            $nightTotal = round($nightTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">NIGHT RATE(' . $eachJob['nightTotal'] . ' Hours @ $' . $nightChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $nightTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['satTotal'] > 0) {
                            $satTot = $satTot + $eachJob['satTotal'];
                            $satChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SATURDAY'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['satTotal'] = (float)$value['satTotal'] + (float)$eachJob['satTotal'];
                                    $ratesArray[$key]['satChargeRate'] = $satChargeRate;
                                }
                            }
                            $satTotal = $eachJob['satTotal'] * $satChargeRate;
                            $satTotal = round($satTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">SATURDAY RATE(' . $eachJob['satTotal'] . ' Hours @ $' . $satChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $satTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['sunTotal'] > 0) {
                            $sunTot = $sunTot + $eachJob['sunTotal'];
                            $sunChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SUNDAY'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['sunTotal'] = (float)$value['sunTotal'] + (float)$eachJob['sunTotal'];
                                    $ratesArray[$key]['sunChargeRate'] = $sunChargeRate;
                                }
                            }
                            $sunTotal = $eachJob['sunTotal'] * $sunChargeRate;
                            $sunTotal = round($sunTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">SUNDAY RATE(' . $eachJob['sunTotal'] . ' Hours @ $' . $sunChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $sunTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['ovtTotal'] > 0) {
                            $ovtTot = $ovtTot + $eachJob['ovtTotal'];
                            $ovtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'OVERTIME'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['ovtTotal'] = (float)$value['ovtTotal'] + (float)$eachJob['ovtTotal'];
                                    $ratesArray[$key]['ovtChargeRate'] = $ovtChargeRate;
                                }
                            }
                            $ovtTotal = $eachJob['ovtTotal'] * $ovtChargeRate;
                            $ovtTotal = round($ovtTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">OVERTIME RATE(' . $eachJob['ovtTotal'] . ' Hours @ $' . $ovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $ovtTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['satovtTotal'] > 0) {
                            $satovtTot = $satovtTot + $eachJob['satovtTotal'];
                            $satovtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SATURDAY OVERTIME'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['satovtTotal'] = (float)$value['satovtTotal'] + (float)$eachJob['satovtTotal'];
                                    $ratesArray[$key]['satovtChargeRate'] = $satovtChargeRate;
                                }
                            }
                            $satovtTotal = $eachJob['satovtTotal'] * $satovtChargeRate;
                            $satovtTotal = round($satovtTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">SATURDAY OVERTIME RATE(' . $eachJob['satovtTotal'] . ' Hours @ $' . $satovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $satovtTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['sunovtTotal'] > 0) {
                            $sunovtTot = $sunovtTot + $eachJob['sunovtTotal'];
                            $sunovtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SUNDAY OVERTIME'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['sunovtTotal'] = (float)$value['sunovtTotal'] + (float)$eachJob['sunovtTotal'];
                                    $ratesArray[$key]['sunovtChargeRate'] = $sunovtChargeRate;
                                }
                            }
                            $sunovtTotal = $eachJob['sunovtTotal'] * $sunovtChargeRate;
                            $sunovtTotal = round($sunovtTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">SUNDAY OVERTIME RATE(' . $eachJob['sunovtTotal'] . ' Hours @ $' . $sunovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $sunovtTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['povtTotal'] > 0) {
                            $povtTot = $povtTot + $eachJob['povtTotal'];
                            $povtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'PERIOD OVERTIME'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['povtTotal'] = (float)$value['povtTotal'] + (float)$eachJob['povtTotal'];
                                    $ratesArray[$key]['povtChargeRate'] = $povtChargeRate;
                                }
                            }
                            $povtTotal = $eachJob['povtTotal'] * $povtChargeRate;
                            $povtTotal = round($povtTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">PERIOD OVERTIME RATE(' . $eachJob['povtTotal'] . ' Hours @ $' . $povtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $povtTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['dblTotal'] > 0) {
                            $dblTot = $dblTot + $eachJob['dblTotal'];
                            $dblChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'DOUBLETIME'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['dblTotal'] = (float)$value['dblTotal'] + (float)$eachJob['dblTotal'];
                                    $ratesArray[$key]['dblChargeRate'] = $dblChargeRate;
                                }
                            }
                            $dblTotal = $eachJob['dblTotal'] * $dblChargeRate;
                            $dblTotal = round($dblTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">DOUBLETIME RATE(' . $eachJob['dblTotal'] . ' Hours @ $' . $dblChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $dblTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['hldTotal'] > 0) {
                            $hldTot = $hldTot + $eachJob['hldTotal'];
                            $hldChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['hldTotal'] = (float)$value['hldTotal'] + (float)$eachJob['hldTotal'];
                                    $ratesArray[$key]['hldChargeRate'] = $hldChargeRate;
                                }
                            }
                            $hldTotal = $eachJob['hldTotal'] * $hldChargeRate;
                            $hldTotal = round($hldTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">PUBLIC HOLIDAY RATE(' . $eachJob['hldTotal'] . ' Hours @ $' . $hldChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $hldTotal . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['hol_total'] > 0) {
                            $hld2Tot = $hld2Tot + $eachJob['hol_total'];
                            $hld2ChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['hol_total'] = (float)$value['hol_total'] + (float)$eachJob['hol_total'];
                                    $ratesArray[$key]['hld2ChargeRate'] = $hld2ChargeRate;
                                }
                            }
                            $hld2Total = $eachJob['hol_total'] * $hld2ChargeRate;
                            $hld2Total = round($hld2Total, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">PUBLIC HOLIDAY 2 RATE(' . $eachJob['hol_total'] . ' Hours @ $' . $hld2ChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $hld2Total . '</td><td width="10%"></td></tr>';
                        }
                        if ($eachJob['rdoTotal'] > 0) {
                            $rdoTot = $rdoTot + $eachJob['rdoTotal'];
                            $rdoChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'RDO'),$eachJob['jobcode']);
                            foreach ($ratesArray as $key => $value) {
                                if ($key == $eachJob['jobcode']) {
                                    $ratesArray[$key]['rdoTotal'] = (float)$value['rdoTotal'] + (float)$eachJob['rdoTotal'];
                                    $ratesArray[$key]['rdoChargeRate'] = $rdoChargeRate;
                                }
                            }
                            $rdoTotal = $eachJob['rdoTotal'] * $rdoChargeRate;
                            $rdoTotal = round($rdoTotal, 2);
                            $html = $html . '<tr nobr="true"><td width="60%">RDO RATE(' . $eachJob['rdoTotal'] . ' Hours @ $' . $rdoChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $rdoTotal . '</td><td width="10%"></td></tr>';
                        }
                        $invoiceAdditionsJob = getInvoiceAddition($mysqli, $client, $weekendingDate, $eachJob['candidateId'], $jbCode);
                        foreach ($invoiceAdditionsJob as $inAdd) {
                            $inAddAmount = $inAdd['amount'];
                            $html = $html . '<tr nobr="true"><td width="60%">' . $inAdd['description'] . '(' . $inAdd['units'] . ' Hours @ $' . $inAddAmount . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . number_format($inAddAmount, 2) . '</td><td width="10%"></td></tr>';
                            $total = $total + $inAddAmount;
                        }
                        $total = $total + ($emgTotal + $ordTotal + $aftTotal + $nightTotal + $satTotal + $sunTotal + $ovtTotal + $dblTotal + $hldTotal + $hld2Total + $rdoTotal);
                        $emgTotal = 0;
                        $ordTotal = 0;
                        $aftTotal = 0;
                        $nightTotal = 0;
                        $satTotal = 0;
                        $sunTotal = 0;
                        $ovtTotal = 0;
                        $dblTotal = 0;
                        $hldTotal = 0;
                        $hld2Total = 0;
                        $rdoTotal = 0;
                    }
                    if ($j == $len - 1) {
                        if (!empty($ratesArray) && ($total != 0)) {
                            $html = summaryData($html, $ratesArray);
                            $y = $pdf->getY();
                            if (258 < $y) {
                                $span = 0;
                            } else if (258 > $y && 200 < $y) {
                                $span = 50;
                            } else {
                                $span = 50;
                            }
                            $html = printTotals($html, $total);
                            $html = $html . '<br pagebreak="true">';
                        }
                        $total = 0;
                        $emgTot = 0;
                        $ordTot = 0;
                        $aftTot = 0;
                        $nightTot = 0;
                        $satTot = 0;
                        $sunTot = 0;
                        $ovtTot = 0;
                        $satovtTot = 0;
                        $sunovtTot = 0;
                        $povtTot = 0;
                        $dblTot = 0;
                        $hldTot = 0;
                        $hld2Tot = 0;
                        $rdoTot = 0;

                        $emgTotal = 0;
                        $ordTotal = 0;
                        $aftTotal = 0;
                        $nightTotal = 0;
                        $satTotal = 0;
                        $sunTotal = 0;
                        $ovtTotal = 0;
                        $satovtTotal = 0;
                        $sunovtTotal = 0;
                        $povtTotal = 0;
                        $dblTotal = 0;
                        $hldTotal = 0;
                        $hld2Total = 0;
                        $rdoTotal = 0;

                        $emgChargeRate = 0;
                        $ordChargeRate = 0;
                        $aftChargeRate = 0;
                        $nightChargeRate = 0;
                        $satChargeRate = 0;
                        $sunChargeRate = 0;
                        $ovtChargeRate = 0;
                        $satovtChargeRate = 0;
                        $sunovtChargeRate = 0;
                        $povtChargeRate = 0;
                        $dblChargeRate = 0;
                        $hldChargeRate = 0;
                        $hld2ChargeRate = 0;
                        $rdoChargeRate = 0;
                    }
                    $j++;
            }

            $ratesArray = array();
            $jobCodeArray = array();
            foreach ($invData as $value) {
                $ratesArray[$value['jobcode']] = array('emgTotal' => '', 'emgChargeRate' => '', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '', 'hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal' => '', 'rdoChargeRate' => '');
            }
            $k = 0;
            $len = count($invData);
            $client = '';
            $total = 0;
            foreach ($invData as $allData) {
                $termId = getTermIdByClientId($mysqli, $allData['clientId']);
                $terms = getPaymentTermByTermId($mysqli, $termId);
                $termGap = getPaymentTermGapByTermId($mysqli, $termId);
                $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(175, 175, 175)));
                $invType = getClientInvoiceType($mysqli, $allData['clientId']);
                if ($invType == 'All Jobs') {
                    if (empty($jbCode)) {
                        $jbCode = $allData['jobcode'];
                    }
                    if (empty($client)) {
                        $total = 0;
                        $emgTot = 0;
                        $ordTot = 0;
                        $aftTot = 0;
                        $nightTot = 0;
                        $satTot = 0;
                        $sunTot = 0;
                        $ovtTot = 0;
                        $satovtTot = 0;
                        $sunovtTot = 0;
                        $povtTot = 0;
                        $dblTot = 0;
                        $hldTot = 0;
                        $hld2Tot = 0;
                        $rdoTot = 0;

                        $emgTotal = 0;
                        $ordTotal = 0;
                        $aftTotal = 0;
                        $nightTotal = 0;
                        $satTotal = 0;
                        $sunTotal = 0;
                        $ovtTotal = 0;
                        $satovtTotal = 0;
                        $sunovtTotal = 0;
                        $povtTotal = 0;
                        $dblTotal = 0;
                        $hldTotal = 0;
                        $hld2Total = 0;
                        $rdoTotal = 0;

                        $emgChargeRate = 0;
                        $ordChargeRate = 0;
                        $aftChargeRate = 0;
                        $nightChargeRate = 0;
                        $satChargeRate = 0;
                        $sunChargeRate = 0;
                        $ovtChargeRate = 0;
                        $satovtChargeRate = 0;
                        $sunovtChargeRate = 0;
                        $povtChargeRate = 0;
                        $dblChargeRate = 0;
                        $hldChargeRate = 0;
                        $hld2ChargeRate = 0;
                        $rdoChargeRate = 0;
                        $invoiceNo = genNewInvoiceNo($mysqli);
                        $client = $allData['clientId'];

                        /*$print_terms = printTerms($html,getClientCodeById($mysqli, $allData['clientId']),$invoiceNo,getClientAccountManagerFromJobDetail($mysqli, $allData['clientId'],$allData['jobcode']),getClientNameByClientId($mysqli, $allData['clientId']),getClientAddress($mysqli, $allData['clientId']),$terms,$invoiceDate,$termGap);
                        $html = $html.$print_terms;
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';*/
                        $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .getClientCodeById($mysqli, $client). '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .getClientAccountManagerFromJobDetail($mysqli, $client,$allData['jobcode']). '
                            <br>' .getClientNameByClientId($mysqli, $client).'
                            <br>' .getClientAddress($mysqli, $client).'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';

                    }
                    if (empty($canId)) {
                        $canId = $allData['candidateId'];
                    }
                    if (($client != $allData['clientId']) && (!empty($client))) {
                        if (!empty($ratesArray) && ($total != 0)) {
                            $html = summaryData($html, $ratesArray);
                            $y = $pdf->getY();
                            if (258 < $y) {
                                $span = 0;
                            } else if (258 > $y && 200 < $y) {
                                $span = 50;
                            } else {
                                $span = 50;
                            }
                            $html = $html . '<tr nobr="true"><td colspan="5" height="' . $span . '"></td></tr>';
                            $html = printTotals($html, $total);
                            try {
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }

                        $html = $html . '<br pagebreak="true">';
                        }
                        $invoiceNo = genNewInvoiceNo($mysqli);

                        $client = $allData['clientId'];

                        /*$print_terms = printTerms($html,getClientCodeById($mysqli, $allData['clientId']),$invoiceNo,getClientAccountManagerFromJobDetail($mysqli, $allData['clientId'],$allData['jobcode']),getClientNameByClientId($mysqli, $allData['clientId']),getClientAddress($mysqli, $allData['clientId']),$terms,$invoiceDate,$termGap);
                        $html = $html.$print_terms;
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';*/
                        $html = $html.'<tr><td width="60%">
                            <div align="left" class="caps">Client Code:  
                            <span class="lblBold">' .getClientCodeById($mysqli, $client). '</span>
                            <br>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span>
                            <br>' .getClientAccountManagerFromJobDetail($mysqli, $client,$allData['jobcode']). '
                            <br>' .getClientNameByClientId($mysqli, $client).'
                            <br>' .getClientAddress($mysqli, $client).'</div>
                         </td>
                         <td width="5%"></td>
                         <td width="5%"></td>
                         <td width="20%" style="font-size: 8pt; font-weight: bold;">
                            <div align="left" style="font-size: 8pt; font-weight: bold">Terms:' . $terms . '<br>DUE DATE <br>'.date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')).'</div>
                         </td>
                         <td width="10%"></td>
                   </tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5"><hr></td></tr>';
                        /*$html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $allData['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $allData['clientId']) . ', ' . getPositionByPositionId($mysqli, $allData['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')*/


                        $total = 0;
                        $emgTot = 0;
                        $ordTot = 0;
                        $aftTot = 0;
                        $nightTot = 0;
                        $satTot = 0;
                        $sunTot = 0;
                        $ovtTot = 0;
                        $satovtTot = 0;
                        $sunovtTot = 0;
                        $povtTot = 0;
                        $dblTot = 0;
                        $hldTot = 0;
                        $hld2Tot = 0;
                        $rdoTot = 0;

                        $emgTotal = 0;
                        $ordTotal = 0;
                        $aftTotal = 0;
                        $nightTotal = 0;
                        $satTotal = 0;
                        $sunTotal = 0;
                        $ovtTotal = 0;
                        $satovtTotal = 0;
                        $sunovtTotal = 0;
                        $povtTotal = 0;
                        $dblTotal = 0;
                        $hldTotal = 0;
                        $hld2Total = 0;
                        $rdoTotal = 0;

                        $emgChargeRate = 0;
                        $ordChargeRate = 0;
                        $aftChargeRate = 0;
                        $nightChargeRate = 0;
                        $satChargeRate = 0;
                        $sunChargeRate = 0;
                        $ovtChargeRate = 0;
                        $satovtChargeRate = 0;
                        $sunovtChargeRate = 0;
                        $povtChargeRate = 0;
                        $dblChargeRate = 0;
                        $hldChargeRate = 0;
                        $hld2ChargeRate = 0;
                        $rdoChargeRate = 0;

                        foreach ($jobCodeArray as $jobKey => $val) {
                            unset($ratesArray[$jobKey]);
                        }
                    }

                    $jobCodeArray[$allData['jobcode']] = array();
                    if ($jbCode != $allData['jobcode']) {
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $allData['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $allData['clientId']) . ', ' . getPositionByPositionId($mysqli, $allData['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';
                        $html = $html . '<tr><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $allData['candidateId'])) . '</td></tr>';
                        $jbCode = $allData['jobcode'];
                    } else {
                        $html = $html . '<tr nobr="true"><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="lblBold">JobCode:' . $allData['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $allData['clientId']) . ', ' . getPositionByPositionId($mysqli, $allData['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';
                        $html = $html . '<tr><td colspan="5"></td></tr>';
                        $html = $html . '<tr nobr="true"><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $allData['candidateId'])) . '</td></tr>';
                    }
                    if ($canId != $allData['candidateId']) {
                        $canId = $allData['candidateId'];
                    }

                    if ($allData['emgTotal'] > 0) {
                        $emgTot = $emgTot + $allData['emgTotal'];
                        $emgChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'EARLY MORNING'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['emgTotal'] = (float)$value['emgTotal'] + (float)$allData['emgTotal'];
                                $ratesArray[$key]['emgChargeRate'] = $emgChargeRate;
                            }
                        }
                        $emgTotal = $allData['emgTotal'] * $emgChargeRate;
                        $emgTotal = round($emgTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">EARLY MORNING(' . $allData['emgTotal'] . ' Hours @ $' . $emgChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $emgTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['ordTotal'] > 0) {
                        $ordTot = $ordTot + $allData['ordTotal'];
                        $ordChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'ORDINARY'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['ordTotal'] = (float)$value['ordTotal'] + (float)$allData['ordTotal'];
                                $ratesArray[$key]['ordChargeRate'] = $ordChargeRate;
                            }
                        }
                        $ordTotal = $allData['ordTotal'] * $ordChargeRate;
                        $ordTotal = round($ordTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">T1.0 ORDINARY TIME(' . $allData['ordTotal'] . ' Hours @ $' . $ordChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $ordTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['aftTotal'] > 0) {
                        $aftTot = $aftTot + $allData['aftTotal'];
                        $aftChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'AFTERNOON'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['aftTotal'] = (float)$value['aftTotal'] + (float)$allData['aftTotal'];
                                $ratesArray[$key]['aftChargeRate'] = $aftChargeRate;
                            }
                        }
                        $aftTotal = $allData['aftTotal'] * $aftChargeRate;
                        $aftTotal = round($aftTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">AFTERNOON RATE(' . $allData['aftTotal'] . ' Hours @ $' . $aftChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $aftTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['nightTotal'] > 0) {
                        $nightTot = $nightTot + $allData['nightTotal'];
                        $nightChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'NIGHT'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['nightTotal'] = (float)$value['nightTotal'] + (float)$allData['nightTotal'];
                                $ratesArray[$key]['nightChargeRate'] = $nightChargeRate;
                            }
                        }
                        $nightTotal = $allData['nightTotal'] * $nightChargeRate;
                        $nightTotal = round($nightTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">NIGHT RATE(' . $allData['nightTotal'] . ' Hours @ $' . $nightChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $nightTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['satTotal'] > 0) {
                        $satTot = $satTot + $allData['satTotal'];
                        $satChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SATURDAY'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['satTotal'] = (float)$value['satTotal'] + (float)$allData['satTotal'];
                                $ratesArray[$key]['satChargeRate'] = $satChargeRate;
                            }
                        }
                        $satTotal = $allData['satTotal'] * $satChargeRate;
                        $satTotal = round($satTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SATURDAY RATE(' . $allData['satTotal'] . ' Hours @ $' . $satChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $satTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['sunTotal'] > 0) {
                        $sunTot = $sunTot + $allData['sunTotal'];
                        $sunChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SUNDAY'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['sunTotal'] = (float)$value['sunTotal'] + (float)$allData['sunTotal'];
                                $ratesArray[$key]['sunChargeRate'] = $sunChargeRate;
                            }
                        }
                        $sunTotal = $allData['sunTotal'] * $sunChargeRate;
                        $sunTotal = round($sunTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SUNDAY RATE(' . $allData['sunTotal'] . ' Hours @ $' . $sunChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $sunTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['ovtTotal'] > 0) {
                        $ovtTot = $ovtTot + $allData['ovtTotal'];
                        $ovtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'OVERTIME'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['ovtTotal'] = (float)$value['ovtTotal'] + (float)$allData['ovtTotal'];
                                $ratesArray[$key]['ovtChargeRate'] = $ovtChargeRate;
                            }
                        }
                        $ovtTotal = $allData['ovtTotal'] * $ovtChargeRate;
                        $ovtTotal = round($ovtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">OVERTIME RATE(' . $allData['ovtTotal'] . ' Hours @ $' . $ovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $ovtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['satovtTotal'] > 0) {
                        $satovtTot = $satovtTot + $allData['satovtTotal'];
                        $satovtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SATURDAY OVERTIME'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['satovtTotal'] = (float)$value['satovtTotal'] + (float)$allData['satovtTotal'];
                                $ratesArray[$key]['satovtChargeRate'] = $satovtChargeRate;
                            }
                        }
                        $satovtTotal = $allData['satovtTotal'] * $satovtChargeRate;
                        $satovtTotal = round($satovtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SATURDAY OVERTIME RATE(' . $allData['satovtTotal'] . ' Hours @ $' . $satovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $satovtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['sunovtTotal'] > 0) {
                        $sunovtTot = $sunovtTot + $allData['sunovtTotal'];
                        $sunovtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SUNDAY OVERTIME'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['sunovtTotal'] = (float)$value['sunovtTotal'] + (float)$allData['sunovtTotal'];
                                $ratesArray[$key]['sunovtChargeRate'] = $sunovtChargeRate;
                            }
                        }
                        $sunovtTotal = $allData['sunovtTotal'] * $sunovtChargeRate;
                        $sunovtTotal = round($sunovtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">SUNDAY OVERTIME RATE(' . $allData['sunovtTotal'] . ' Hours @ $' . $sunovtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $sunovtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['povtTotal'] > 0) {
                        $povtTot = $povtTot + $allData['povtTotal'];
                        $povtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'PERIOD OVERTIME'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['povtTotal'] = (float)$value['povtTotal'] + (float)$allData['povtTotal'];
                                $ratesArray[$key]['povtChargeRate'] = $povtChargeRate;
                            }
                        }
                        $povtTotal = $allData['povtTotal'] * $povtChargeRate;
                        $povtTotal = round($povtTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">PERIOD OVERTIME RATE(' . $allData['povtTotal'] . ' Hours @ $' . $povtChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $povtTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['dblTotal'] > 0) {
                        $dblTot = $dblTot + $allData['dblTotal'];
                        $dblChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'DOUBLETIME'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['dblTotal'] = (float)$value['dblTotal'] + (float)$allData['dblTotal'];
                                $ratesArray[$key]['dblChargeRate'] = $dblChargeRate;
                            }
                        }
                        $dblTotal = $allData['dblTotal'] * $dblChargeRate;
                        $dblTotal = round($dblTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">DOUBLETIME RATE(' . $allData['dblTotal'] . ' Hours @ $' . $dblChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $dblTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['hldTotal'] > 0) {
                        $hldTot = $hldTot + $allData['hldTotal'];
                        $hldChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['hldTotal'] = (float)$value['hldTotal'] + (float)$allData['hldTotal'];
                                $ratesArray[$key]['hldChargeRate'] = $hldChargeRate;
                            }
                        }
                        $hldTotal = $allData['hldTotal'] * $hldChargeRate;
                        $hldTotal = round($hldTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">PUBLIC HOLIDAY RATE(' . $allData['hldTotal'] . ' Hours @ $' . $hldChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $hldTotal . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['hol_total'] > 0) {
                        $hld2Tot = $hld2Tot + $allData['hol_total'];
                        $hld2ChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['hol_total'] = (float)$value['hol_total'] + (float)$allData['hol_total'];
                                $ratesArray[$key]['hld2ChargeRate'] = $hld2ChargeRate;
                            }
                        }
                        $hld2Total = $allData['hol_total'] * $hld2ChargeRate;
                        $hld2Total = round($hld2Total, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">PUBLIC HOLIDAY 2 RATE(' . $allData['hol_total'] . ' Hours @ $' . $hld2ChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $hld2Total . '</td><td width="10%"></td></tr>';
                    }
                    if ($allData['rdoTotal'] > 0) {
                        $rdoTot = $rdoTot + $allData['rdoTotal'];
                        $rdoChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'RDO'),$allData['jobcode']);
                        foreach ($ratesArray as $key => $value) {
                            if ($key == $allData['jobcode']) {
                                $ratesArray[$key]['rdoTotal'] = (float)$value['rdoTotal'] + (float)$allData['rdoTotal'];
                                $ratesArray[$key]['rdoChargeRate'] = $rdoChargeRate;
                            }
                        }
                        $rdoTotal = $allData['rdoTotal'] * $rdoChargeRate;
                        $rdoTotal = round($rdoTotal, 2);
                        $html = $html . '<tr nobr="true"><td width="60%">RDO RATE(' . $allData['rdoTotal'] . ' Hours @ $' . $rdoChargeRate . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . $rdoTotal . '</td><td width="10%"></td></tr>';
                    }
                    $invoiceAdditionsAll = getInvoiceAddition($mysqli, $client, $weekendingDate, $allData['candidateId'], $jbCode);
                    foreach ($invoiceAdditionsAll as $inAdd) {
                        $inAddAmount = $inAdd['amount'];
                        $html = $html . '<tr nobr="true"><td width="60%">' . $inAdd['description'] . '(' . $inAdd['units'] . ' Hours @ $' . $inAddAmount . ')</td><td width="5%"></td><td width="5%"></td><td width="20%" align="right">$' . number_format($inAddAmount, 2) . '</td><td width="10%"></td></tr>';
                        $total = $total + $inAddAmount;
                    }
                    $total = $total + ($emgTotal + $ordTotal + $aftTotal + $nightTotal + $satTotal + $sunTotal + $ovtTotal + $dblTotal + $hldTotal + $hld2Total + $rdoTotal);

                    $emgTotal = 0;
                    $ordTotal = 0;
                    $aftTotal = 0;
                    $nightTotal = 0;
                    $satTotal = 0;
                    $sunTotal = 0;
                    $ovtTotal = 0;
                    $dblTotal = 0;
                    $hldTotal = 0;
                    $hld2Total = 0;
                    $rdoTotal = 0;
                }
                if ($k == $len - 1) {
                    if (!empty($ratesArray) && ($total != 0)) {
                        $html = summaryData($html, $ratesArray);
                        $y = $pdf->getY();
                        if (258 < $y) {
                            $span = 0;
                        } else if (258 > $y && 200 < $y) {
                            $span = 50;
                        } else {
                            $span = 50;
                        }
                        $html = $html . '<tr nobr="true"><td colspan="5" height="' . $span . '"></td></tr>';
                        $html = printTotals($html, $total);
                        try {
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                }
                $k++;
            }

            $html . '</tbody></table></div>';
            if (!file_exists('./invoice/' . $clientCode)) {
                mkdir('./invoice/' . $clientCode, 0777);
            }
            $fileName = 'test_invoice_' . $clientCode . '_' . $invoiceNo . '_' . date('Y-m-d') . '_' . time();
            $filePath = __DIR__ . '/invoice/' . $clientCode . '/' . $fileName . '.pdf';
            $downloadPath = './invoice/' . $clientCode . '/' . $fileName . '.pdf';
            $pdf->writeHTML($html, true, false, false, false, '');
            $pdf->lastPage();
            $pdf->Output(__DIR__ . '/invoice/' . $clientCode . '/' . $fileName . '.pdf', 'F');
            $invoice_path = './invoice/'.$clientCode.'/'. $fileName . '.pdf';
            array_push($fileArray, $invoice_path);
        }
    }

    $invPdf = new FPDI();
    foreach ($fileArray as $file) {
        $pageCount = $invPdf->setSourceFile($file);
        for ($i = 0; $i < $pageCount; $i++) {
            $tpl = $invPdf->importPage($i + 1, '/MediaBox');
            $invPdf->addPage();
            $invPdf->useTemplate($tpl);
        }
    }
    $mergedFile = './invoice/merged.pdf';
    $invPdf->Output(__DIR__ . '/invoice/merged.pdf','F');
    array_unshift($fileArray, $mergedFile);

    echo json_encode($fileArray);
/*}else{
    echo 'exists';
}*/
?>