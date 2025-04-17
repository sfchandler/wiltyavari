<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='ACCOUNTS')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$candidateId = $_POST['candidateId'];
$payDateInfo = $_POST['paidDate'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$companyId = $_POST['companyId'];
$logoPath = getCompanyLogoById($mysqli,$companyId);
$pData = explode('|',$payDateInfo);
$weekendingDate = $pData[1];
$payrunId = $pData[0];
$emailStatus = $_POST['emailSlips'];
$previewPrint = $_POST['previewPrint'];
$payrollName = getPayrollNameById($mysqli,$_POST['payrollName']);
$abn = getCompanyABN($mysqli,$companyId);
$acn = getCompanyACN($mysqli,$companyId);
$companyFax = getCompanyFax($mysqli,$companyId);
$companyPhone = getCompanyPhone($mysqli,$companyId);
$website = getCompanyWebsite($mysqli,$companyId);
$companyName = getCompanyNameById($mysqli,$companyId);
$companyAddress = getCompanyAddress($mysqli,$companyId);
$startPayDate = date("d/m/Y",strtotime($_POST['startPayDate']));
$payDate = date("d/m/Y",strtotime($_POST['payDate']));
if(empty($startPayDate)){
    $startPayDate = date("d/m/Y");
}
updateUserActivityLog($mysqli,$_SESSION['userSession'],$_SERVER['REMOTE_ADDR'],'PAYSLIP','','PAYSLIP GENERATION','Pay Slip generation executed by '.$_SESSION['userSession'].' at '.date('Y-m-d H:i:s').'. Selection weekending '.$weekendingDate.' Pay run ID '.$payrunId.' period start '.$startDate.' period end '.$endDate.' Pay Slip date '.$startPayDate.' Pay date '.$payDate);


class PAYSLIPPDF extends TCPDF {

    public $logo;
    public $website;
    public $companyName;
    public $abn;
    public $companyAddress;
    public $companyPhone;
    public $companyFax;
    public $acn;
    public $payDate;

    /**
     * @return mixed
     */
    public function getPayDate()
    {
        return $this->payDate;
    }

    /**
     * @param mixed $payDate
     */
    public function setPayDate($payDate)
    {
        $this->payDate = $payDate;
    }

    /**
     * @return mixed
     */
    public function getAcn()
    {
        return $this->acn;
    }

    /**
     * @param mixed $acn
     */
    public function setAcn($acn)
    {
        $this->acn = $acn;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param mixed $companyName
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
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
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
    //Page header
    public function Header() {
        // Logo
        $image_file = $this->getLogo();//K_PATH_IMAGES.'ChandlerPersonnel.jpg'
        $this->Image($image_file, 5, 5, 60, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        // Title
        //$this->Cell(0, 15, '<<  >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        $fb_file = K_PATH_IMAGES.'fb.png';
        $this->Image($fb_file, 70, 275, 20, '', '', ' /', '', false, 300, '', false, false);
        $tw_file = K_PATH_IMAGES.'tw.gif';
        $this->Image($tw_file, 95, 275, 20, '', '', ' ', '', false, 300, '', false, false);
        $lnk_file = K_PATH_IMAGES.'linkedin.png';
        $this->Image($lnk_file, 120, 275, 20, '', '', ' ', '', false, 300, '', false, false);
        // set style for QR barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        /*$styleLine = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0));
        $this->Line(5, 10, 80, 30, $styleLine);*/
        $this->Line(5, 283, 205, 283);
        // QRCODE,H : QR-CODE Best error correction
        $this->write2DBarcode($this->getWebsite(), 'QRCODE,H', 5, 285, 10, 10, $style, 'N');
        $this->SetY(-20);
        $this->Cell(0, 10, '', 0, false, 'L', 0, '', 0, false, 'T', 'M');

        // Position at 15 mm from bottom
        $this->SetY(-16);
        // Set font
        $this->SetFont('helvetica', 'B', 8);
        // Page number
        $this->Cell(0, 10, '                '.$this->getCompanyName().' - ACN:'.$this->getAcn().'    ABN:'.$this->getAbn().' ', 0, false, 'L', 0, '', 0, false, 'T', 'M'); //Chandler Personnel Services P/L - ACN:007 386 138    ABN:25091298234
        $this->SetFont('helvetica', 'R', 8);
        $this->SetY(-12);
        $this->Cell(0, 10, '                '.$this->getCompanyAddress().' ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-8);
        $this->Cell(0, 10,'                Telephone: '.$this->getCompanyPhone().'   Facsimile:'.$this->getCompanyFax().' ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        //Telephone 96569777  Facsimile 96569799
    }
}

$mailArray = array();
$mailCount = 1;
if(empty($candidateId)){
    $payrunData = getPayrunDataByDate($mysqli,$weekendingDate,$payrunId);
    //$payrunData = getPayrunDataByDate($mysqli,$weekendingDate);
    foreach($payrunData as $data){
        $mails = generatePaySlip($mysqli,$data['candidateId'],$weekendingDate,$payrunId,$logoPath,$abn,$companyName,$acn,$companyPhone,$companyFax,$website,$companyAddress,$payDate,$startPayDate,$startDate,$endDate);
        $mailArray[] = $mails;
    }
    if($emailStatus == 'Send') {

        foreach ($mailArray as $mail){
            try {
                 echo generateEMails($mail['empEmail'], $mail['empFirstName'], $mail['payslipDate'], $mail['filePath']).' - '.$mailCount;
                 $mailCount++;
                 usleep(100000);
            }catch (Exception $e){
                echo 'Error.... '.$e->getMessage();
            }
        }
    }else{
        echo 'Send Email Not ticked';
    }
}else{
    $mails = generatePaySlip($mysqli,$candidateId,$weekendingDate,$payrunId,$logoPath,$abn,$companyName,$acn,$companyPhone,$companyFax,$website,$companyAddress,$payDate,$startPayDate,$startDate,$endDate);

    $mailArray[] = $mails;
    if($emailStatus == 'Send') {
        $emailId = '';
        foreach ($mailArray as $mail){
            $mailCount++;
            if(empty($emailId)){
                $emailId = $mail['empEmail'];
                try {
                    echo generateEMails($mail['empEmail'],$mail['empFirstName'],$mail['payslipDate'],$mail['filePath']).' - '.$mailCount;
                }catch (Exception $e){
                    echo 'Error.... '.$e->getMessage();
                }
            }
            if($emailId != $mail['empEmail']){
                $emailId = $mail['empEmail'];
                try {
                    echo generateEMails($mail['empEmail'],$mail['empFirstName'],$mail['payslipDate'],$mail['filePath']).' - '.$mailCount;
                }catch (Exception $e){
                    echo 'Error.... '.$e->getMessage();
                }
            }

        }
    }else{
        echo 'Send Email Not ticked';
    }
}


function generateEMails($empEmail,$empFirstName,$paySlipDate,$filePath){
    /*if(generatePaySlipEmail($empEmail,$empFirstName,$paySlipDate,$filePath) == 'SUCCESS'){
        return 'Mail Sent Successfully';
    }else{
        return 'Email Generation Error !';
    }*/
    return generatePaySlipEmail($empEmail,$empFirstName,$paySlipDate,$filePath);
}

function generatePaySlip($mysqli,$empId,$wkendDate,$runId,$logoPath,$abn,$companyName,$acn,$companyPhone,$companyFax,$website,$companyAddress,$payDate,$startPayDate,$startDate,$endDate){
// create new PDF document
    $pdf = new PAYSLIPPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
// set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('Pay Slip');
    $pdf->SetSubject('PaySlip');
    $pdf->SetKeywords('PaySlip');
    $pdf->setLogo($logoPath);
    $pdf->setCompanyName($companyName);
    $pdf->setAbn($abn);
    $pdf->setAcn($acn);
    $pdf->setCompanyPhone($companyPhone);
    $pdf->setCompanyFax($companyFax);
    $pdf->setWebsite($website);
    $pdf->setCompanyAddress($companyAddress);
// set header and footer fonts
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
// set default monospaced font
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
// set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
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
    $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));

    $pdf->Line(0,0,$pdf->getPageWidth(),0);
    $pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
    $pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
    $pdf->Line(0,0,0,$pdf->getPageHeight());


    $html = $html.'<div style="width: 100%"><style>td{ text-align: right;}.rowTitle{text-align: left;}.clientTitle{text-align: left; font-weight: bold;} th{ text-align: center; font-weight: bold}.bankRowContent{
text-align: left; width: 70%;}.bankRowTitle{text-align: left; font-weight: bold }th{text-align: center; font-weight: bold; border-bottom: 1px solid dimgrey}.zebra0{background-color: #f1f1f1;}.zebra1{background-color: white;}</style>';
    $html = $html.'<div align="center" style="text-align:center;font-weight: bold; font-size: 20pt">P A Y&nbsp;&nbsp;&nbsp;S L I P</div>';
    $html = $html.'<ul style="list-style-type: none;"><li class="rowTitle">'.strtoupper(getCandidateFirstNameByCandidateId($mysqli,$empId).' '.getCandidateLastNameByCandidateId($mysqli,$empId)).'</li>';
    $html = $html.'<li class="rowTitle">'.strtoupper(getCandidateAddressById($mysqli,$empId)).'</li>';
    $html = $html.'<li class="rowTitle" style="margin-bottom: 0;"><strong>Employee :</strong>&nbsp;'.$empId.'</li>';
    $html = $html.'<li class="rowTitle"><strong>Payslip Date :</strong>&nbsp;'.$startPayDate.'</li></ul>';

    $html = $html.'<table cellspacing="1" cellpadding="1" width="470px"><thead><tr><th width="60%">Client</th><th>Hours/Qty</th><th>Rate</th><th>Amount</th><th>Tax</th><th>After Tax</th><th width="18%">Superannuation Accruals</th></tr></thead><tbody>';
    $payData = getPayrunDataByEmployee($mysqli,$empId,$wkendDate,$runId);
    //$chDate = $wkendDate;//date('Y-m-d', strtotime('+2 days',strtotime($wkendDate)));
    //$startDate = date('Y-m-d', strtotime('-6 days', strtotime($chDate)));
    /* Shift Types */
    $clientId;
    foreach($payData as $data){
        if($data['itemType']<9) {
            if($clientId=='') {
                $clientId = $data['clientId'];
                $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="clientTitle" width="60%">'.strtoupper(getClientNameByClientId($mysqli,$data['clientId'])).' - for the period '.date('d/m/Y',strtotime($startDate)).' to '.date('d/m/Y',strtotime($endDate)).'</td><td></td><td></td><td></td><td></td><td></td><td width="18%">&nbsp;</td></tr>';
            }else if($clientId<>$data['clientId']){
                $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="clientTitle" width="60%">'.strtoupper(getClientNameByClientId($mysqli,$data['clientId'])).' - for the period '.date('d/m/Y',strtotime($startDate)).' to '.date('d/m/Y',strtotime($endDate)).'</td><td></td><td></td><td></td><td></td><td></td><td width="18%">&nbsp;</td></tr>';
                $clientId = $data['clientId'];
            }
            $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="rowTitle" width="60%">'.$data['category'].'</td><td>' . number_format($data['units'],2) . '</td><td>' . number_format($data['rate'],2) . '</td><td>' . number_format($data['amount'],2) . '</td><td>&nbsp;</td><td>&nbsp;</td><td width="18%">&nbsp;</td></tr>';
        }
    }
    /* Deduction */
    foreach($payData as $data){
        if($data['itemType']==10) {
            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle" width="60%">' . $data['category'] . '</td><td>'.number_format(1,2).'</td><td>' . number_format($data['deduction'],2) . '</td><td>&nbsp;</td><td>&nbsp;</td><td>-' . number_format($data['deduction'],2) . '</td><td width="18%">&nbsp;</td></tr>';
        }
    }
    /* Allowances */
    foreach($payData as $data){
        if($data['itemType']==14) {
            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle" width="60%">' . $data['category'] . '</td><td>'.number_format(1,2).'</td><td>' . number_format($data['amount'],2) . '</td><td>&nbsp;</td><td>&nbsp;</td><td>' . number_format($data['amount'],2) . '</td><td width="18%">&nbsp;</td></tr>';
        }
    }
    /* Superannuation */
    foreach($payData as $data){
        if($data['itemType']==12) {
            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle" width="60%">' . $data['category'] . '</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td width="18%">'.number_format($data['super'],2).'</td></tr>';
        }
    }
    /* PAYG Tax */
    $paygTax;
    foreach($payData as $data){
        if($data['itemType']==11) {
            $paygTax = $paygTax + $data['paygTax'];
        }
    }
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle" width="60%">PAYG Tax</td><td>&nbsp;</td><td>&nbsp;</td><td style="border-bottom: 1px solid black">&nbsp;</td><td style="border-bottom: 1px solid black">-$'.number_format(round($paygTax),2).'</td><td style="border-bottom: 1px solid black">&nbsp;</td><td width="18%">&nbsp;</td></tr>';

    /* Gross */
    $empGross = 0;
    foreach($payData as $data){
        if($data['itemType']==9) {
            $empGross = $empGross + $data['gross'];
        }
    }
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td width="60%">&nbsp;</td><td colspan="2" class="rowTitle" style="text-align: right"><strong>Gross:</strong></td><td><strong>$'.number_format($empGross,2).'</strong></td><td><strong>-$'.number_format(round($paygTax),2).'</strong></td><td>&nbsp;</td><td width="18%">&nbsp;</td></tr>';
    /* Net */
    $amountToPay;
    foreach($payData as $data){
        if($data['itemType']==13) {
            $amountToPay = $amountToPay + $data['net'];
        }
    }
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td width="60%">&nbsp;</td><td colspan="2" class="rowTitle" style="text-align: right"><strong>Net Amount:</strong></td><td style="border: 1px solid black"><strong>$'.number_format($amountToPay,2).'</strong></td><td>&nbsp;</td><td>&nbsp;</td><td width="18%">&nbsp;</td></tr>';

    $html = $html.'</tbody></table>';

    if(strtotime('july', strtotime($wkendDate)) > strtotime($wkendDate)){
        $currentJuly = date('Y-m-d',strtotime('1st july', strtotime($wkendDate)));
        $yearStartDate = date('Y-m-d',strtotime('-1 year', strtotime($currentJuly)));
    }else{
        $yearStartDate = date('Y-m-d',strtotime('1st july', strtotime($wkendDate)));
    }

    $bankAccount = getEmployeeBankAccount($mysqli,$empId);
    $html = $html.'<div>This pay has been deposited into:</div>';
    $html = $html.'<table align="left" width="45%" cellspacing="2"><tbody>';
    foreach($bankAccount as $bank){
        $html = $html.'<tr><td class="bankRowTitle" width="30%">Account Name:</td><td width="70%" class="rowTitle">'.$bank['accountName'].'</td></tr>';
        $html = $html.'<tr><td class="bankRowTitle" width="30%">Account Number:</td><td width="70%" class="rowTitle">'.$bank['accountNumber'].'</td></tr>';
        $html = $html.'<tr><td class="bankRowTitle" width="30%">BSB:</td><td width="70%" class="rowTitle">'.$bank['bsb'].'</td></tr>';
    }
    $html = $html.'<tr><td class="bankRowTitle" width="30%">Amount:</td><td width="70%" class="rowTitle">'.number_format($amountToPay,2).'</td></tr>';
    $html = $html.'<tr><td class="bankRowTitle" width="30%">Date Paid:</td><td width="70%" class="rowTitle">'.$payDate.'</td></tr>';
    $html = $html.'</tbody></table>';
    $html = $html.'<div style="height: 100px;">&nbsp;</div>';
    $yearToDate = getYearToDateData($mysqli,$empId,$yearStartDate,$wkendDate);
    $html = $html.'<table width="50%" cellpadding="1" cellspacing="1"><thead><tr><th></th><th>Hours/Qty</th><th>Amount</th></tr></thead><tbody>';
    $html = $html.'<tr><td colspan="3" class="rowTitle" style="font-weight: bold">This Year</td></tr>';

    foreach ($yearToDate as $yearData) {
        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Hourly</td><td>'.number_format($yearData['totalUnits'],2) . '</td><td>' . number_format($yearData['totalGross'],2) . '</td></tr>';
        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Tax</td><td></td><td>-'.number_format($yearData['totalTax'],2).'</td></tr>';
        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Net</td><td></td><td>'.number_format($yearData['totalNet'],2).'</td></tr>';
        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Post Tax Deduction</td><td></td><td>'.number_format($yearData['totalDedcution'],2).'</td></tr>';
        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Superannuation Accrual</td><td>'.number_format($yearData['superCount'],2).'</td><td>'.number_format($yearData['totalSuper'],2).'</td></tr>';
    }
    $html = $html.'</tbody></table>';
    $html = $html.'<span style="height: 30px;">&nbsp;</span>';
    $paySlipMessage = getPaySlipMessage($mysqli,$payrollName);
    $html = $html.'<div style="text-align: justify"><i>'.$paySlipMessage.'</i></div>';
    $html = $html.'<div style="text-align: justify"><i style="color: rgb(48,73,123)">**Kindly note that the hourly rate incorporates the relevant casual loading as per the Fairwork guidelines.  **</i></div>';

    $fileName = 'pay_slip_'.$empId.'_'.$runId.'_'.date('Y-m-d');
    $paySlipPath = './paySlip/'.$fileName.'.pdf';
    $filePath = __DIR__.'/paySlip/'.$fileName.'.pdf';
    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__.'/paySlip/'.$fileName.'.pdf', 'F');
    $empEmail = getEmployeeEmail($mysqli,$empId);
    $empFirstName = getCandidateFirstNameByCandidateId($mysqli,$empId);
    $formattedPayDate = date("Y-m-d",strtotime($payDate));
    savePaySlipPath($mysqli,$empId,$runId,$wkendDate,$paySlipPath,$formattedPayDate,$startDate,$endDate);
    $mailArray = array('empEmail'=>$empEmail,'empFirstName'=>$empFirstName,'payslipDate'=>$payDate,'filePath'=>$filePath);
    return $mailArray;
}
?>