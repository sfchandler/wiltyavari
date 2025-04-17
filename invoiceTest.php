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
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');

$jobCode = $_REQUEST['jobCode'];//'';
$weekendingDate = $_REQUEST['weekendingDate'];//'2020-01-09';
$invDate = $_REQUEST['invoiceDate'];//'2020-01-09';
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

if(!checkInvoiceGeneration($mysqli,$weekendingDate)) {

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
            $this->Image($image_file, 5, 5, 60, '', 'JPG', '', 'L', false, 300, '', false, false, 0, false, false, false);
            // Set font
            $this->SetFont('helvetica', 'R', 8);
            $this->Ln(25);
            $this->Cell(0, '', $this->getInvoiceDate(), 0, $ln = 0, 'L', 0, '', 0, false, 'B', 'B');
            $this->Ln(10);
            $this->SetFont('helvetica', 'B', 25);
            $this->Cell(0, '', 'T A X   I N V O I C E', 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
            $this->Ln(4);
            $this->SetFont('helvetica', 'B', 7);
            $this->Cell(0, '', 'Chandler Pacific Pty Ltd. T/A Chandler Personnel', 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
            $this->Ln(4);
            $this->SetFont('helvetica', 'B', 7);
            $this->Cell(0, '', 'ABN: ' . $this->getAbn(), 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');
            $this->Ln(40);
            $this->Cell(0, '', '     ', 0, $ln = 0, 'C', 0, '', 0, false, 'B', 'B');

        }
        // Page footer
        public function Footer()
        {
            /*$fb_file = K_PATH_IMAGES.'fb.png';
            $this->Image($fb_file, 70, 275, 20, '', '', 'https://www.facebook.com/ChandlerService/', '', false, 300, '', false, false);
            $tw_file = K_PATH_IMAGES.'tw.gif';
            $this->Image($tw_file, 95, 275, 20, '', '', 'https://twitter.com/ChandlerJobsAu', '', false, 300, '', false, false);
            $lnk_file = K_PATH_IMAGES.'linkedin.png';
            $this->Image($lnk_file, 120, 275, 20, '', '', 'https://www.linkedin.com/company/chandler-recruitment?trk=hb_tab_compy_id_1131626', '', false, 300, '', false, false);*/
            // set style for QR barcode
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
            // QRCODE,H : QR-CODE Best error correction
            $this->write2DBarcode($this->getWebsite(), 'QRCODE,H', 5, 282, 14, 14, $style, 'N');
            /*$this->SetY(-20);
            $this->Cell(0, 10, '', 0, false, 'L', 0, '', 0, false, 'T', 'M');*/
            // Position at 15 mm from bottom
            //  $this->SetY(-18);
            // Set font
            $this->SetFont('helvetica', 'R', 8);
            // Page number
            $this->SetY(-19);
            $this->Cell(0,10,'                   This debt has been assigned to Moneytech Finance PTY LTD. All Payments must be made to Moneytech via EFT or Cheque as per details outlined.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->SetY(-15);
            $this->Cell(0,10,'                   Payments made in any other way will not extinguish this debt.', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->SetY(-11);
            $this->Cell(0, 10,'                   Payment Preferred by EFT: '.$this->getCompanyBankAccount().'', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->SetY(-7);
            $this->Cell(0, 10, '                   ' . $this->getCompanyAddress() . '  Telephone: ' . $this->getCompanyPhone() . '  Fax: ' . $this->getCompanyFax() . ' ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $this->SetY(-4);
            // Position at 15 mm from bottom
            //$this->SetY(-15);
            // Set font
            //$this->SetFont('helvetica', 'I', 8);
            // Page number
            //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
        }
    }

    $invData = getInvoiceTimeSheetTotals($mysqli, $weekendingDate, $payrollName, $jobCode);

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
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8);
        $pdf->startPageGroup();
        $pdf->AddPage();
        /* $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));
        $pdf->Line(0,0,$pdf->getPageWidth(),0);
        $pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
        $pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
        $pdf->Line(0,0,0,$pdf->getPageHeight());*/
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

        $html = $html . '<style>.tblbr{background: #87939a}.brdr{border-style: solid; border-width: 1px; border-color: black;}.caps{text-transform: uppercase}hr{ text-decoration-style: solid; }.lblAmount{font-weight: bold;font-size: 11pt}.lblBold{font-weight: bold;}.empName{font-style: italic;}</style><div>
    <br><br><br><table class="tbl" width="950px" cellpadding="1" cellspacing="1">
        <thead></thead>
        <tbody class="invBody">';


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
            $total = round($total,2);
            $totalAmount = round((($total * (10 / 100)) + $total),2);
            $html = $html . '<tr><td align="right" width="60%" class="lblBold">Total</td><td align="right" width="10%" class="lblBold">$' . number_format($total,2) . '</td><td width="10%"></td></tr>';
            $html = $html . '<tr><td align="right" width="60%" class="lblBold">GST</td><td align="right" width="10%" class="lblBold">$' . number_format(($total * 10 / 100), 2) . '</td><td width="10%"></td></tr>';
            $html = $html . '<tr><td align="right" width="60%" class="lblAmount">Total Amount</td><td align="right" width="10%" class="lblAmount">$' . number_format($totalAmount,2) . '</td><td width="10%"></td></tr>';
            $html = $html . '<tr><td align="right" width="60%" class="lblBold"><i>(Including GST)</i></td><td width="10%"></td><td width="10%"></td></tr>';
            return $html;
        }

        function printTerms($html, $invoiceNo, $terms, $termGap, $invoiceDate)
        {
            $html = $html . '<tr><td colspan="2" style="font-size: 6pt; font-weight: bold;">Terms:' . $terms . '</td><td></td></tr>';
            $html = $html . '<tr><td colspan="2" style="font-size: 6pt" width="60%"></td><td width="30%" align="left" style="font-size: 14pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</td></tr>';
            return $html;
        }
        $ratesArray = array();
        $jobCodeArray;
        foreach ($invData as $value) {
            $ratesArray[$value['jobcode']] = array('emgTotal' => '','emgChargeRate'=>'', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '','hol_total'=>'','hld2ChargeRate'=>'', 'rdoTotal'=>'', 'rdoChargeRate'=>'');
        }
        $i = 0;
        $len = count($invData);
        $client='';
        foreach ($invData as $data) {
            $termId = getTermIdByClientId($mysqli, $data['clientId']);
            $terms = getPaymentTermByTermId($mysqli, $termId);
            $termGap = getPaymentTermGapByTermId($mysqli, $termId);
            $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(175, 175, 175)));
            $invType = getClientInvoiceType($mysqli, $data['clientId']);
            if($invType == 'Each Employee'){
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
                //saveInvoiceNo($mysqli, $invoiceNo);

                if (empty($jbCode)) {
                    $jbCode = $data['jobcode'];
                }
                $client = $data['clientId'];

                if (empty($canId)) {
                    $canId = $data['candidateId'];
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $data['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $data['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $data['clientId']) . '<br/>' . getClientAddress($mysqli, $data['clientId']) . '</div></td><td></td><td></td><td style="font-size: 8pt; font-weight: bold;">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="lblBold">JobCode:' . $data['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $data['clientId']) . ', ' . getPositionByPositionId($mysqli, $data['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')
                    $html = $html . '<tr><td colspan="5" class="empName">' .strtoupper(getCandidateFullName($mysqli, $data['candidateId'])) . '</td></tr>';
                }else{
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $data['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $data['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $data['clientId']) . '<br/>' . getClientAddress($mysqli, $data['clientId']) . '</div></td><td style="font-size: 8pt; font-weight: bold;">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="lblBold">JobCode:' . $data['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $data['clientId']) . ', ' . getPositionByPositionId($mysqli, $data['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')
                }

                $jobCodeArray[$data['jobcode']] = array();
                if ($jbCode != $data['jobcode']) {
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $jbCode = $data['jobcode'];
                }
                if ($canId != $data['candidateId']) {
                    $canId = $data['candidateId'];
                    $html = $html . '<tr><td colspan="5" class="empName">' . strtoupper(getCandidateFullName($mysqli, $data['candidateId'])) . '</td></tr>';
                }

                if ($data['emgTotal'] > 0) {
                    $emgTot = $emgTot + $data['emgTotal'];
                    $emgChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'EARLY MORNING'));
                    $emgTotal = $data['emgTotal'] * $emgChargeRate;
                    $emgTotal = round($emgTotal,2);
                    $html = $html . '<tr><td width="60%">EARLY MORNING(' . $data['emgTotal'] . ' Hours @ $' . $emgChargeRate . ')</td><td width="10%" align="right">$' . $emgTotal . '</td><td width="10%"></td></tr>';

                }
                if ($data['ordTotal'] > 0) {
                    $ordTot = $ordTot + $data['ordTotal'];
                    $ordChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'ORDINARY'));
                    $ordTotal = $data['ordTotal'] * $ordChargeRate;
                    $ordTotal = round($ordTotal,2);
                    $html = $html . '<tr><td width="60%">T1.0 ORDINARY TIME(' . $data['ordTotal'] . ' Hours @ $' . $ordChargeRate . ')</td><td width="10%" align="right">$' . $ordTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['aftTotal'] > 0) {
                    $aftTot = $aftTot + $data['aftTotal'];
                    $aftChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'AFTERNOON'));
                    $aftTotal = $data['aftTotal'] * $aftChargeRate;
                    $aftTotal = round($aftTotal,2);
                    $html = $html . '<tr><td width="60%">AFTERNOON RATE(' . $data['aftTotal'] . ' Hours @ $' . $aftChargeRate . ')</td><td width="10%" align="right">$' . $aftTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['nightTotal'] > 0) {
                    $nightTot = $nightTot + $data['nightTotal'];
                    $nightChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'NIGHT'));
                    $nightTotal = $data['nightTotal'] * $nightChargeRate;
                    $nightTotal = round($nightTotal,2);
                    $html = $html . '<tr><td width="60%">NIGHT RATE(' . $data['nightTotal'] . ' Hours @ $' . $nightChargeRate . ')</td><td width="10%" align="right">$' . $nightTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['satTotal'] > 0) {
                    $satTot = $satTot + $data['satTotal'];
                    $satChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SATURDAY'));
                    $satTotal = $data['satTotal'] * $satChargeRate;
                    $satTotal = round($satTotal,2);
                    $html = $html . '<tr><td width="60%">SATURDAY RATE(' . $data['satTotal'] . ' Hours @ $' . $satChargeRate . ')</td><td width="10%" align="right">$' . $satTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['sunTotal'] > 0) {
                    $sunTot = $sunTot + $data['sunTotal'];
                    $sunChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SUNDAY'));
                    $sunTotal = $data['sunTotal'] * $sunChargeRate;
                    $sunTotal = round($sunTotal,2);
                    $html = $html . '<tr><td width="60%">SUNDAY RATE(' . $data['sunTotal'] . ' Hours @ $' . $sunChargeRate . ')</td><td width="10%" align="right">$' . $sunTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['ovtTotal'] > 0) {
                    $ovtTot = $ovtTot + $data['ovtTotal'];
                    $ovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'OVERTIME'));
                    $ovtTotal = $data['ovtTotal'] * $ovtChargeRate;
                    $ovtTotal = round($ovtTotal,2);
                    $html = $html . '<tr><td width="60%">OVERTIME RATE(' . $data['ovtTotal'] . ' Hours @ $' . $ovtChargeRate . ')</td><td width="10%" align="right">$' . $ovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['satovtTotal'] > 0) {
                    $satovtTot = $satovtTot + $data['satovtTotal'];
                    $satovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SATURDAY OVERTIME'));
                    $satovtTotal = $data['satovtTotal'] * $satovtChargeRate;
                    $satovtTotal = round($satovtTotal,2);
                    $html = $html . '<tr><td width="60%">SATURDAY OVERTIME RATE(' . $data['satovtTotal'] . ' Hours @ $' . $satovtChargeRate . ')</td><td width="10%" align="right">$' . $satovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['sunovtTotal'] > 0) {
                    $sunovtTot = $sunovtTot + $data['sunovtTotal'];
                    $sunovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'SUNDAY OVERTIME'));
                    $sunovtTotal = $data['sunovtTotal'] * $sunovtChargeRate;
                    $sunovtTotal = round($sunovtTotal,2);
                    $html = $html . '<tr><td width="60%">SUNDAY OVERTIME RATE(' . $data['sunovtTotal'] . ' Hours @ $' . $sunovtChargeRate . ')</td><td width="10%" align="right">$' . $sunovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['povtTotal'] > 0) {
                    $povtTot = $povtTot + $data['povtTotal'];
                    $povtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'PERIOD OVERTIME'));
                    $povtTotal = $data['povtTotal'] * $povtChargeRate;
                    $povtTotal = round($povtTotal,2);
                    $html = $html . '<tr><td width="60%">PERIOD OVERTIME RATE(' . $data['povtTotal'] . ' Hours @ $' . $povtChargeRate . ')</td><td width="10%" align="right">$' . $povtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['dblTotal'] > 0) {
                    $dblTot = $dblTot + $data['dblTotal'];
                    $dblChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'DOUBLETIME'));
                    $dblTotal = $data['dblTotal'] * $dblChargeRate;
                    $dblTotal = round($dblTotal,2);
                    $html = $html . '<tr><td width="60%">DOUBLETIME RATE(' . $data['dblTotal'] . ' Hours @ $' . $dblChargeRate . ')</td><td width="10%" align="right">$' . $dblTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['hldTotal'] > 0) {
                    $hldTot = $hldTot + $data['hldTotal'];
                    $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY'));
                    $hldTotal = $data['hldTotal'] * $hldChargeRate;
                    $hldTotal = round($hldTotal,2);
                    $html = $html . '<tr><td width="60%">PUBLIC HOLIDAY RATE(' . $data['hldTotal'] . ' Hours @ $' . $hldChargeRate . ')</td><td width="10%" align="right">$' . $hldTotal . '</td><td width="10%"></td></tr>';
                }
                if ($data['hol_total'] > 0) {
                    $hld2Tot = $hld2Tot + $data['hol_total'];
                    $hld2ChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2'));
                    $hld2Total = $data['hol_total'] * $hld2ChargeRate;
                    $hld2Total = round($hld2Total,2);
                    $html = $html . '<tr><td width="60%">PUBLIC HOLIDAY 2 RATE(' . $data['hol_total'] . ' Hours @ $' . $hld2ChargeRate . ')</td><td width="10%" align="right">$' . $hld2Total . '</td><td width="10%"></td></tr>';
                }
                if ($data['rdoTotal'] > 0) {
                    $rdoTot = $rdoTot + $data['rdoTotal'];
                    $rdoChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], getPayCatCode($mysqli, 'RDO'));
                    $rdoTotal = $data['rdoTotal'] * $rdoChargeRate;
                    $rdoTotal = round($rdoTotal,2);
                    $html = $html . '<tr><td width="60%">RDO RATE(' . $data['rdoTotal'] . ' Hours @ $' . $rdoChargeRate . ')</td><td width="10%" align="right">$' . $rdoTotal . '</td><td width="10%"></td></tr>';
                }

                $invoiceAdditionsEmployee = getInvoiceAddition($mysqli, $client, $weekendingDate,$data['candidateId'],$jbCode);
                foreach ($invoiceAdditionsEmployee as $inAdd) {
                    $inAddAmount = $inAdd['amount'];
                    $html = $html . '<tr><td width="60%">' . $inAdd['description'] . '(' . $inAdd['units'] . ' Hours @ $' . $inAddAmount . ')</td><td width="10%" align="right">$' . number_format($inAddAmount, 2) . '</td><td width="10%"></td></tr>';
                    //updateInvoiceAddition($mysqli, $inAdd['id'], $invoiceNo);
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
                if(!empty($ratesArray) && ($total != 0)) {
                    $y = $pdf->getY();
                    if (258 < $y) {
                        $span = 0;
                    } else if (258 > $y && 200 < $y) {
                        $span = 100;
                    } else {
                        $span = 100;
                    }
                    $html = $html . '<tr><td colspan="5" height="' . $span . '"></td></tr>';
                    $html = printTotals($html, $total);
                    //$html = $html.'<tr><td colspan="5">'.round((($total * (10 / 100)) + $total),2).'</td></tr>';
                    //saveInvoice($mysqli, $invoiceNo, date('Y-m-d', strtotime($invDate)), $weekendingDate, $client, $total, ($total * 10 / 100), ($total * 10 / 100) + $total);
                    //if ($i != $len - 1) {

                    //}
                }
                $html = $html . '<br pagebreak="true">';
            }
            if($i == $len - 1){
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
        /* Extract data for each job */
        $ratesArray = array();
        $jobCodeArray;
        foreach ($invData as $value) {
            $ratesArray[$value['jobcode']] = array('emgTotal' => '','emgChargeRate'=>'', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '','hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal'=>'', 'rdoChargeRate'=>'');
        }
        $j = 0;
        $len = count($invData);
        $client = '';

        foreach ($invData as $eachJob) {
            $termId = getTermIdByClientId($mysqli, $eachJob['clientId']);
            $terms = getPaymentTermByTermId($mysqli, $termId);
            $termGap = getPaymentTermGapByTermId($mysqli, $termId);
            $pdf->SetLineStyle(array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(175, 175, 175)));
            $invType = getClientInvoiceType($mysqli, $eachJob['clientId']);
            if($invType == 'Each Job'){
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
                    //saveInvoiceNo($mysqli, $invoiceNo);
                    $client = $eachJob['clientId'];
                    $jbCode = $eachJob['jobcode'];
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $eachJob['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $eachJob['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $eachJob['clientId']) . '<br/>' . getClientAddress($mysqli, $eachJob['clientId']) . '</div></td><td></td><td></td><td style="font-size: 8pt; font-weight: bold;">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="lblBold">JobCode: '.$client.'' . $eachJob['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $eachJob['clientId']) . ', ' . getPositionByPositionId($mysqli, $eachJob['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')

                }
                /*if (empty($client)) {
                    $client = $data['clientId'];
                }*/
                if (empty($canId)) {
                    $canId = $eachJob['candidateId'];
                    $html = $html . '<tr><td colspan="5" class="empName">'.strtoupper(getCandidateFullName($mysqli, $eachJob['candidateId'])) . '</td></tr>';
                }

                $jobCodeArray[$eachJob['jobcode']] = array();
                if ($jbCode != $eachJob['jobcode']) {
                    if(!empty($ratesArray)) { //&& ($total != 0)
                        $html = summaryData($html, $ratesArray);
                        $y = $pdf->getY();
                        if (258 < $y) {
                            $span = 0;
                        } else if (258 > $y && 200 < $y) {
                            $span = 100;
                        } else {
                            $span = 100;
                        }
                        //$html = $html . '<tr><td colspan="5" height="' . $span . '"></td></tr>';
                        $html = printTotals($html, $total);
                        //$html = $html.'<tr><td colspan="5">'.round((($total * (10 / 100)) + $total),2).'</td></tr>';

                        //saveInvoice($mysqli, $invoiceNo, date('Y-m-d', strtotime($invDate)), $weekendingDate, $client, $total, ($total * 10 / 100), ($total * 10 / 100) + $total);
                        //$html = $html . '<tr><td colspan="5" height="300"></td></tr>';
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
                    //saveInvoiceNo($mysqli, $invoiceNo);
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $eachJob['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $eachJob['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $eachJob['clientId']) . '<br/>' . getClientAddress($mysqli, $eachJob['clientId']) . '</div></td><td style="font-size: 8pt; font-weight: bold;">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';
                    //$html = $html . '<tr><td colspan="3" class="lblBold">JobCode:' . $eachJob['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $eachJob['clientId']) . ', ' . getPositionByPositionId($mysqli, $eachJob['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')
                    $jbCode = $eachJob['jobcode'];
                    $html = $html . '<tr><td colspan="5" class="lblBold">JobCode:' . $eachJob['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $eachJob['clientId']) . ', ' . getPositionByPositionId($mysqli, $eachJob['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';//date('Y-m-d')
                    $ratesArray=array();
                    foreach ($invData as $value) {
                        $ratesArray[$value['jobcode']] = array('emgTotal' => '','emgChargeRate'=>'', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '','hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal'=>'', 'rdoChargeRate'=>'');
                    }
                    $client = $eachJob['clientId'];
                }

                if ($canId != $eachJob['candidateId']) {
                    $canId = $eachJob['candidateId'];
                    $html = $html . '<tr><td class="empName">' . strtoupper(getCandidateFullName($mysqli, $eachJob['candidateId'])) . '</td><td></td><td></td></tr>';
                }

                if ($eachJob['emgTotal'] > 0) {
                    $emgTot = $emgTot + $eachJob['emgTotal'];
                    $emgChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'EARLY MORNING'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['emgTotal'] = $value['emgTotal'] + $eachJob['emgTotal'];
                            $ratesArray[$key]['emgChargeRate'] = $emgChargeRate;
                        }
                    }
                    $emgTotal = $eachJob['emgTotal'] * $emgChargeRate;
                    $emgTotal = round($emgTotal,2);
                    $html = $html . '<tr><td width="60%">EARLY MORNING(' . $eachJob['emgTotal'] . ' Hours @ $' . $emgChargeRate . ')</td><td width="10%" align="right">$' . $emgTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['ordTotal'] > 0) {
                    $ordTot = $ordTot + $eachJob['ordTotal'];
                    $ordChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'ORDINARY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['ordTotal'] = $value['ordTotal'] + $eachJob['ordTotal'];
                            $ratesArray[$key]['ordChargeRate'] = $ordChargeRate;
                        }
                    }
                    $ordTotal = $eachJob['ordTotal'] * $ordChargeRate;
                    $ordTotal = round($ordTotal,2);
                    $html = $html . '<tr><td width="60%">T1.0 ORDINARY TIME(' . $eachJob['ordTotal'] . ' Hours @ $' . $ordChargeRate . ')</td><td width="10%" align="right">$' . $ordTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['aftTotal'] > 0) {
                    $aftTot = $aftTot + $eachJob['aftTotal'];
                    $aftChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'AFTERNOON'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['aftTotal'] = $value['aftTotal'] + $eachJob['aftTotal'];
                            $ratesArray[$key]['aftChargeRate'] = $aftChargeRate;
                        }
                    }
                    $aftTotal = $eachJob['aftTotal'] * $aftChargeRate;
                    $aftTotal = round($aftTotal,2);
                    $html = $html . '<tr><td width="60%">AFTERNOON RATE(' . $eachJob['aftTotal'] . ' Hours @ $' . $aftChargeRate . ')</td><td width="10%" align="right">$' .$aftTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['nightTotal'] > 0) {
                    $nightTot = $nightTot + $eachJob['nightTotal'];
                    $nightChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'NIGHT'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['nightTotal'] = $value['nightTotal'] + $eachJob['nightTotal'];
                            $ratesArray[$key]['nightChargeRate'] = $nightChargeRate;
                        }
                    }
                    $nightTotal = $eachJob['nightTotal'] * $nightChargeRate;
                    $nightTotal = round($nightTotal,2);
                    $html = $html . '<tr><td width="60%">NIGHT RATE(' . $eachJob['nightTotal'] . ' Hours @ $' . $nightChargeRate . ')</td><td width="10%" align="right">$' . $nightTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['satTotal'] > 0) {
                    $satTot = $satTot + $eachJob['satTotal'];
                    $satChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SATURDAY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['satTotal'] = $value['satTotal'] + $eachJob['satTotal'];
                            $ratesArray[$key]['satChargeRate'] = $satChargeRate;
                        }
                    }
                    $satTotal = $eachJob['satTotal'] * $satChargeRate;
                    $satTotal = round($satTotal,2);
                    $html = $html . '<tr><td width="60%">SATURDAY RATE(' . $eachJob['satTotal'] . ' Hours @ $' . $satChargeRate . ')</td><td width="10%" align="right">$' . $satTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['sunTotal'] > 0) {
                    $sunTot = $sunTot + $eachJob['sunTotal'];
                    $sunChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SUNDAY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['sunTotal'] = $value['sunTotal'] + $eachJob['sunTotal'];
                            $ratesArray[$key]['sunChargeRate'] = $sunChargeRate;
                        }
                    }
                    $sunTotal = $eachJob['sunTotal'] * $sunChargeRate;
                    $sunTotal = round($sunTotal,2);
                    $html = $html . '<tr><td width="60%">SUNDAY RATE(' . $eachJob['sunTotal'] . ' Hours @ $' . $sunChargeRate . ')</td><td width="10%" align="right">$' . $sunTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['ovtTotal'] > 0) {
                    $ovtTot = $ovtTot + $eachJob['ovtTotal'];
                    $ovtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['ovtTotal'] = $value['ovtTotal'] + $eachJob['ovtTotal'];
                            $ratesArray[$key]['ovtChargeRate'] = $ovtChargeRate;
                        }
                    }
                    $ovtTotal = $eachJob['ovtTotal'] * $ovtChargeRate;
                    $ovtTotal = round($ovtTotal,2);
                    $html = $html . '<tr><td width="60%">OVERTIME RATE(' . $eachJob['ovtTotal'] . ' Hours @ $' . $ovtChargeRate . ')</td><td width="10%" align="right">$' . $ovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['satovtTotal'] > 0) {
                    $satovtTot = $satovtTot + $eachJob['satovtTotal'];
                    $satovtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SATURDAY OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['satovtTotal'] = $value['satovtTotal'] + $eachJob['satovtTotal'];
                            $ratesArray[$key]['satovtChargeRate'] = $satovtChargeRate;
                        }
                    }
                    $satovtTotal = $eachJob['satovtTotal'] * $satovtChargeRate;
                    $satovtTotal = round($satovtTotal,2);
                    $html = $html . '<tr><td width="60%">SATURDAY OVERTIME RATE(' . $eachJob['satovtTotal'] . ' Hours @ $' . $satovtChargeRate . ')</td><td width="10%" align="right">$' . $satovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['sunovtTotal'] > 0) {
                    $sunovtTot = $sunovtTot + $eachJob['sunovtTotal'];
                    $sunovtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'SUNDAY OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['sunovtTotal'] = $value['sunovtTotal'] + $eachJob['sunovtTotal'];
                            $ratesArray[$key]['sunovtChargeRate'] = $sunovtChargeRate;
                        }
                    }
                    $sunovtTotal = $eachJob['sunovtTotal'] * $sunovtChargeRate;
                    $sunovtTotal = round($sunovtTotal,2);
                    $html = $html . '<tr><td width="60%">SUNDAY OVERTIME RATE(' . $eachJob['sunovtTotal'] . ' Hours @ $' . $sunovtChargeRate . ')</td><td width="10%" align="right">$' . $sunovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['povtTotal'] > 0) {
                    $povtTot = $povtTot + $eachJob['povtTotal'];
                    $povtChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'PERIOD OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['povtTotal'] = $value['povtTotal'] + $eachJob['povtTotal'];
                            $ratesArray[$key]['povtChargeRate'] = $povtChargeRate;
                        }
                    }
                    $povtTotal = $eachJob['povtTotal'] * $povtChargeRate;
                    $povtTotal = round($povtTotal,2);
                    $html = $html . '<tr><td width="60%">PERIOD OVERTIME RATE(' . $eachJob['povtTotal'] . ' Hours @ $' . $povtChargeRate . ')</td><td width="10%" align="right">$' . $povtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['dblTotal'] > 0) {
                    $dblTot = $dblTot + $eachJob['dblTotal'];
                    $dblChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'DOUBLETIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['dblTotal'] = $value['dblTotal'] + $eachJob['dblTotal'];
                            $ratesArray[$key]['dblChargeRate'] = $dblChargeRate;
                        }
                    }
                    $dblTotal = $eachJob['dblTotal'] * $dblChargeRate;
                    $dblTotal = round($dblTotal,2);
                    $html = $html . '<tr><td width="60%">DOUBLETIME RATE(' . $eachJob['dblTotal'] . ' Hours @ $' . $dblChargeRate . ')</td><td width="10%" align="right">$' . $dblTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['hldTotal'] > 0) {
                    $hldTot = $hldTot + $eachJob['hldTotal'];
                    $hldChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['hldTotal'] = $value['hldTotal'] + $eachJob['hldTotal'];
                            $ratesArray[$key]['hldChargeRate'] = $hldChargeRate;
                        }
                    }
                    $hldTotal = $eachJob['hldTotal'] * $hldChargeRate;
                    $hldTotal = round($hldTotal,2);
                    $html = $html . '<tr><td width="60%">PUBLIC HOLIDAY RATE(' . $eachJob['hldTotal'] . ' Hours @ $' . $hldChargeRate . ')</td><td width="10%" align="right">$' . $hldTotal . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['hol_total'] > 0) {
                    $hld2Tot = $hld2Tot + $eachJob['hol_total'];
                    $hld2ChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['hol_total'] = $value['hol_total'] + $eachJob['hol_total'];
                            $ratesArray[$key]['hld2ChargeRate'] = $hld2ChargeRate;
                        }
                    }
                    $hld2Total = $eachJob['hol_total'] * $hld2ChargeRate;
                    $hld2Total = round($hld2Total,2);
                    $html = $html . '<tr><td width="60%">PUBLIC HOLIDAY 2 RATE(' . $eachJob['hol_total'] . ' Hours @ $' . $hld2ChargeRate . ')</td><td width="10%" align="right">$' . $hld2Total . '</td><td width="10%"></td></tr>';
                }
                if ($eachJob['rdoTotal'] > 0) {
                    $rdoTot = $rdoTot + $eachJob['rdoTotal'];
                    $rdoChargeRate = getChargeRate($mysqli, $eachJob['clientId'], $eachJob['positionId'], getPayCatCode($mysqli, 'RDO'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $eachJob['jobcode']) {
                            $ratesArray[$key]['rdoTotal'] = $value['rdoTotal'] + $eachJob['rdoTotal'];
                            $ratesArray[$key]['rdoChargeRate'] = $hldChargeRate;
                        }
                    }
                    $rdoTotal = $eachJob['rdoTotal'] * $rdoChargeRate;
                    $rdoTotal = round($rdoTotal,2);
                    $html = $html . '<tr><td width="60%">RDO RATE(' . $eachJob['rdoTotal'] . ' Hours @ $' . $rdoChargeRate . ')</td><td width="10%" align="right">$' . $rdoTotal. '</td><td width="10%"></td></tr>';
                }
                $invoiceAdditionsJob = getInvoiceAddition($mysqli, $client, $weekendingDate,$eachJob['candidateId'],$jbCode);
                foreach ($invoiceAdditionsJob as $inAdd) {
                    $inAddAmount = $inAdd['amount'];
                    $html = $html . '<tr><td width="60%">' . $inAdd['description'] . '(' . $inAdd['units'] . ' Hours @ $' . $inAddAmount . ')</td><td width="10%" align="right">$' . number_format($inAddAmount, 2) . '</td><td width="10%"></td></tr>';
                    //updateInvoiceAddition($mysqli, $inAdd['id'], $invoiceNo);
                    $total = $total + $inAddAmount;
                }
                $total = $total + $emgTotal + $ordTotal + $aftTotal + $nightTotal + $satTotal + $sunTotal + $ovtTotal + $dblTotal + $hldTotal + $hld2Total + $rdoTotal;
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
                if(!empty($ratesArray) && ($total != 0)) {

                    $html = summaryData($html, $ratesArray);
                    $y = $pdf->getY();
                    if (258 < $y) {
                        $span = 0;
                    } else if (258 > $y && 200 < $y) {
                        $span = 100;
                    } else {
                        $span = 100;
                    }
                    $html = printTotals($html, $total);
                    //$html = $html.'<tr><td colspan="5">'.round((($total * (10 / 100)) + $total),2).'</td></tr>';

                    //saveInvoice($mysqli, $invoiceNo, date('Y-m-d', strtotime($invDate)), $weekendingDate, $client, $total, ($total * 10 / 100), ($total * 10 / 100) + $total);
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

                //$html = $html . '<br pagebreak="true">';
            }
            /*if ($j == $len - 1) {
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
                $rdoChargeRate = 0;
            }*/
            $j++;
        }


        $ratesArray = array();
        $jobCodeArray;
        foreach ($invData as $value) {
            $ratesArray[$value['jobcode']] = array('emgTotal' => '','emgChargeRate'=>'', 'ordTotal' => '', 'ordChargeRate' => '', 'aftTotal' => '', 'aftChargeRate' => '', 'nightTotal' => '', 'nightChargeRate' => '', 'satTotal' => '', 'satChargeRate' => '', 'sunTotal' => '', 'sunChargeRate' => '', 'ovtTotal' => '', 'ovtChargeRate' => '', 'satovtTotal' => '', 'satovtChargeRate' => '', 'sunovtTotal' => '', 'sunovtChargeRate' => '', 'povtTotal' => '', 'povtChargeRate' => '', 'dblTotal' => '', 'dblChargeRate' => '', 'hldTotal' => '', 'hldChargeRate' => '','hol_total' => '', 'hld2ChargeRate' => '', 'rdoTotal'=>'', 'rdoChargeRate'=>'');
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
            if($invType == 'All Jobs'){

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
                    //saveInvoiceNo($mysqli, $invoiceNo);
                    $client = $allData['clientId'];
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $allData['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $allData['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $allData['clientId']) . '<br/>' . getClientAddress($mysqli, $allData['clientId']) . '</div></td><td style="font-size: 8pt; font-weight: bold;">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';
                }
                if (empty($canId)) {
                    $canId = $allData['candidateId'];
                }
                if (($client != $allData['clientId']) && (!empty($client))) {
                    if(!empty($ratesArray) && ($total != 0)) {
                        $html = summaryData($html, $ratesArray);
                        $y = $pdf->getY();
                        if (258 < $y) {
                            $span = 0;
                        } else if (258 > $y && 200 < $y) {
                            $span = 50;
                        } else {
                            $span = 50;
                        }
                        $html = $html . '<tr><td colspan="5" height="' . $span . '"></td></tr>';
                        $html = printTotals($html, $total);
                        try {
                            //$html = $html.'<tr><td colspan="5">'.round((($total * (10 / 100)) + $total),2).'</td></tr>';

                            //saveInvoice($mysqli, $invoiceNo, date('Y-m-d', strtotime($invDate)), $weekendingDate, $client, $total, ($total * 10 / 100), ($total * 10 / 100) + $total);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                    $html = $html . '<br pagebreak="true">';
                    $invoiceNo = genNewInvoiceNo($mysqli);
                    //saveInvoiceNo($mysqli, $invoiceNo);
                    $client = $allData['clientId'];
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $allData['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $allData['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $allData['clientId']) . '<br/>' . getClientAddress($mysqli, $allData['clientId']) . '</div></td><td style="font-size: 8pt; font-weight: bold;">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">' . getCompanyNote($mysqli, $companyId) . '</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';

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
                    /*$html = $html . '<tr><td colspan=5><br/></td></tr>';
                    $html = $html . '<tr><td><div align="left" class="caps">Client Code:  <span class="lblBold">' . getClientCodeById($mysqli, $allData['clientId']) . '</span><br/>Invoice No:  <span class="lblBold">' . $invoiceNo . '</span><br/>' . getClientAccountManagerFromJobDetail($mysqli, $allData['clientId']) . '<br/>' . getClientNameByClientId($mysqli, $allData['clientId']) . '<br/>' . getClientAddress($mysqli, $allData['clientId']) . '</div></td><td align="left" style="font-size: 8pt; font-weight: bold ">Terms:' . $terms . '<br><span style="font-size: 8pt; font-weight: bold">DUE DATE<br/>' . date('d/m/Y', strtotime($invoiceDate . ' + ' . $termGap . ' days')) . '</span></td><td></td><td></td><td></td></tr>';
                    $html = $html . '<tr><td colspan="5"><br/><br/></td></tr>';
                    $html = $html . '<tr><td colspan="5"><span style="font-size: 6pt">If you have any queries regarding this invoice please contact CHANDLER RECRUITMENT ACCOUNTS on 03 9596 9777</span></td></tr>';
                    $html = $html . '<tr><td colspan="5"><hr></td></tr>';*/
                    foreach ($jobCodeArray as $jobKey => $val) {
                        unset($ratesArray[$jobKey]);
                    }
                }

                $jobCodeArray[$allData['jobcode']] = array();
                if ($jbCode != $allData['jobcode']) {
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="lblBold">JobCode:' . $allData['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $allData['clientId']) . ', ' . getPositionByPositionId($mysqli, $allData['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="empName">' .strtoupper(getCandidateFullName($mysqli, $allData['candidateId'])) . '</td></tr>';
                    $jbCode = $allData['jobcode'];
                }else{
                    $html = $html . '<tr><td colspan="5"></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="lblBold">JobCode:' . $allData['jobcode'] . ' - ' . getClientNameByClientId($mysqli, $allData['clientId']) . ', ' . getPositionByPositionId($mysqli, $allData['positionId']) . ' - ' . $weekendingDate . '<br></td></tr>';
                    $html = $html . '<tr><td colspan="5" class="empName">' .strtoupper(getCandidateFullName($mysqli, $allData['candidateId'])) . '</td></tr>';
                }
                if ($canId != $allData['candidateId']) {
                    $canId = $allData['candidateId'];
                }

                if ($allData['emgTotal'] > 0) {
                    $emgTot = $emgTot + $allData['emgTotal'];
                    $emgChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'EARLY MORNING'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['emgTotal'] = $value['emgTotal'] + $allData['emgTotal'];
                            $ratesArray[$key]['emgChargeRate'] = $emgChargeRate;
                        }
                    }
                    $emgTotal = $allData['emgTotal'] * $emgChargeRate;
                    $emgTotal = round($emgTotal,2);
                    $html = $html . '<tr><td width="60%">EARLY MORNING(' . $allData['emgTotal'] . ' Hours @ $' . $emgChargeRate . ')</td><td width="10%" align="right">$' . $emgTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['ordTotal'] > 0) {
                    $ordTot = $ordTot + $allData['ordTotal'];
                    $ordChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'ORDINARY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['ordTotal'] = $value['ordTotal'] + $allData['ordTotal'];
                            $ratesArray[$key]['ordChargeRate'] = $ordChargeRate;
                        }
                    }
                    $ordTotal = $allData['ordTotal'] * $ordChargeRate;
                    $ordTotal = round($ordTotal,2);
                    $html = $html . '<tr><td width="60%">T1.0 ORDINARY TIME(' . $allData['ordTotal'] . ' Hours @ $' . $ordChargeRate . ')</td><td width="10%" align="right">$' . $ordTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['aftTotal'] > 0) {
                    $aftTot = $aftTot + $allData['aftTotal'];
                    $aftChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'AFTERNOON'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['aftTotal'] = $value['aftTotal'] + $allData['aftTotal'];
                            $ratesArray[$key]['aftChargeRate'] = $aftChargeRate;
                        }
                    }
                    $aftTotal = $allData['aftTotal'] * $aftChargeRate;
                    $aftTotal = round($aftTotal,2);
                    $html = $html . '<tr><td width="60%">AFTERNOON RATE(' . $allData['aftTotal'] . ' Hours @ $' . $aftChargeRate . ')</td><td width="10%" align="right">$' . $aftTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['nightTotal'] > 0) {
                    $nightTot = $nightTot + $allData['nightTotal'];
                    $nightChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'NIGHT'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['nightTotal'] = $value['nightTotal'] + $allData['nightTotal'];
                            $ratesArray[$key]['nightChargeRate'] = $nightChargeRate;
                        }
                    }
                    $nightTotal = $allData['nightTotal'] * $nightChargeRate;
                    $nightTotal = round($nightTotal,2);
                    $html = $html . '<tr><td width="60%">NIGHT RATE(' . $allData['nightTotal'] . ' Hours @ $' . $nightChargeRate . ')</td><td width="10%" align="right">$' . $nightTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['satTotal'] > 0) {
                    $satTot = $satTot + $allData['satTotal'];
                    $satChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SATURDAY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['satTotal'] = $value['satTotal'] + $allData['satTotal'];
                            $ratesArray[$key]['satChargeRate'] = $satChargeRate;
                        }
                    }
                    $satTotal = $allData['satTotal'] * $satChargeRate;
                    $satTotal = round($satTotal,2);
                    $html = $html . '<tr><td width="60%">SATURDAY RATE(' . $allData['satTotal'] . ' Hours @ $' . $satChargeRate . ')</td><td width="10%" align="right">$' . $satTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['sunTotal'] > 0) {
                    $sunTot = $sunTot + $allData['sunTotal'];
                    $sunChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SUNDAY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['sunTotal'] = $value['sunTotal'] + $allData['sunTotal'];
                            $ratesArray[$key]['sunChargeRate'] = $sunChargeRate;
                        }
                    }
                    $sunTotal = $allData['sunTotal'] * $sunChargeRate;
                    $sunTotal = round($sunTotal,2);
                    $html = $html . '<tr><td width="60%">SUNDAY RATE(' . $allData['sunTotal'] . ' Hours @ $' . $sunChargeRate . ')</td><td width="10%" align="right">$' . $sunTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['ovtTotal'] > 0) {
                    $ovtTot = $ovtTot + $allData['ovtTotal'];
                    $ovtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['ovtTotal'] = $value['ovtTotal'] + $allData['ovtTotal'];
                            $ratesArray[$key]['ovtChargeRate'] = $ovtChargeRate;
                        }
                    }
                    $ovtTotal = $allData['ovtTotal'] * $ovtChargeRate;
                    $ovtTotal = round($ovtTotal,2);
                    $html = $html . '<tr><td width="60%">OVERTIME RATE(' . $allData['ovtTotal'] . ' Hours @ $' . $ovtChargeRate . ')</td><td width="10%" align="right">$' . $ovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['satovtTotal'] > 0) {
                    $satovtTot = $satovtTot + $allData['satovtTotal'];
                    $satovtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SATURDAY OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['satovtTotal'] = $value['satovtTotal'] + $allData['satovtTotal'];
                            $ratesArray[$key]['satovtChargeRate'] = $satovtChargeRate;
                        }
                    }
                    $satovtTotal = $allData['satovtTotal'] * $satovtChargeRate;
                    $satovtTotal = round($satovtTotal,2);
                    $html = $html . '<tr><td width="60%">SATURDAY OVERTIME RATE(' . $allData['satovtTotal'] . ' Hours @ $' . $satovtChargeRate . ')</td><td width="10%" align="right">$' . $satovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['sunovtTotal'] > 0) {
                    $sunovtTot = $sunovtTot + $allData['sunovtTotal'];
                    $sunovtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'SUNDAY OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['sunovtTotal'] = $value['sunovtTotal'] + $allData['sunovtTotal'];
                            $ratesArray[$key]['sunovtChargeRate'] = $sunovtChargeRate;
                        }
                    }
                    $sunovtTotal = $allData['sunovtTotal'] * $sunovtChargeRate;
                    $sunovtTotal = round($sunovtTotal,2);
                    $html = $html . '<tr><td width="60%">SUNDAY OVERTIME RATE(' . $allData['sunovtTotal'] . ' Hours @ $' . $sunovtChargeRate . ')</td><td width="10%" align="right">$' . $sunovtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['povtTotal'] > 0) {
                    $povtTot = $povtTot + $allData['povtTotal'];
                    $povtChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'PERIOD OVERTIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['povtTotal'] = $value['povtTotal'] + $allData['povtTotal'];
                            $ratesArray[$key]['povtChargeRate'] = $povtChargeRate;
                        }
                    }
                    $povtTotal = $allData['povtTotal'] * $povtChargeRate;
                    $povtTotal = round($povtTotal,2);
                    $html = $html . '<tr><td width="60%">PERIOD OVERTIME RATE(' . $allData['povtTotal'] . ' Hours @ $' . $povtChargeRate . ')</td><td width="10%" align="right">$' . $povtTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['dblTotal'] > 0) {
                    $dblTot = $dblTot + $allData['dblTotal'];
                    $dblChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'DOUBLETIME'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['dblTotal'] = $value['dblTotal'] + $allData['dblTotal'];
                            $ratesArray[$key]['dblChargeRate'] = $dblChargeRate;
                        }
                    }
                    $dblTotal = $allData['dblTotal'] * $dblChargeRate;
                    $dblTotal = round($dblTotal,2);
                    $html = $html . '<tr><td width="60%">DOUBLETIME RATE(' . $allData['dblTotal'] . ' Hours @ $' . $dblChargeRate . ')</td><td width="10%" align="right">$' . $dblTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['hldTotal'] > 0) {
                    $hldTot = $hldTot + $allData['hldTotal'];
                    $hldChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['hldTotal'] = $value['hldTotal'] + $allData['hldTotal'];
                            $ratesArray[$key]['hldChargeRate'] = $hldChargeRate;
                        }
                    }
                    $hldTotal = $allData['hldTotal'] * $hldChargeRate;
                    $hldTotal = round($hldTotal,2);
                    $html = $html . '<tr><td width="60%">PUBLIC HOLIDAY RATE(' . $allData['hldTotal'] . ' Hours @ $' . $hldChargeRate . ')</td><td width="10%" align="right">$' . $hldTotal . '</td><td width="10%"></td></tr>';
                }
                if ($allData['hol_total'] > 0) {
                    $hld2Tot = $hld2Tot + $allData['hol_total'];
                    $hld2ChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['hol_total'] = $value['hol_total'] + $allData['hol_total'];
                            $ratesArray[$key]['hld2ChargeRate'] = $hldChargeRate;
                        }
                    }
                    $hld2Total = $allData['hol_total'] * $hld2ChargeRate;
                    $hld2Total = round($hld2Total,2);
                    $html = $html . '<tr><td width="60%">PUBLIC HOLIDAY 2 RATE(' . $allData['hol_total'] . ' Hours @ $' . $hld2ChargeRate . ')</td><td width="10%" align="right">$' . $hld2Total . '</td><td width="10%"></td></tr>';
                }
                if ($allData['rdoTotal'] > 0) {
                    $rdoTot = $rdoTot + $allData['rdoTotal'];
                    $rdoChargeRate = getChargeRate($mysqli, $allData['clientId'], $allData['positionId'], getPayCatCode($mysqli, 'RDO'));
                    foreach ($ratesArray as $key => $value) {
                        if ($key == $allData['jobcode']) {
                            $ratesArray[$key]['rdoTotal'] = $value['rdoTotal'] + $allData['rdoTotal'];
                            $ratesArray[$key]['rdoChargeRate'] = $rdoChargeRate;
                        }
                    }
                    $rdoTotal = $allData['rdoTotal'] * $rdoChargeRate;
                    $rdoTotal = round($rdoTotal,2);
                    $html = $html . '<tr><td width="60%">RDO RATE(' . $allData['rdoTotal'] . ' Hours @ $' . $rdoChargeRate . ')</td><td width="10%" align="right">$' . $rdoTotal . '</td><td width="10%"></td></tr>';
                }
                $invoiceAdditionsAll = getInvoiceAddition($mysqli, $client, $weekendingDate,$allData['candidateId'],$jbCode);
                foreach ($invoiceAdditionsAll as $inAdd) {
                    $inAddAmount = $inAdd['amount'];
                    $html = $html . '<tr><td width="60%">' . $inAdd['description'] . '(' . $inAdd['units'] . ' Hours @ $' . $inAddAmount . ')</td><td width="10%" align="right">$' . number_format($inAddAmount, 2) . '</td><td width="10%"></td></tr>';
                    //updateInvoiceAddition($mysqli, $inAdd['id'], $invoiceNo);
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
                if(!empty($ratesArray) && ($total != 0)) {
                    $html = summaryData($html, $ratesArray);
                    $y = $pdf->getY();
                    if (258 < $y) {
                        $span = 0;
                    } else if (258 > $y && 200 < $y) {
                        $span = 50;
                    } else {
                        $span = 50;
                    }
                    $html = $html . '<tr><td colspan="5" height="' . $span . '"></td></tr>';
                    $html = printTotals($html, $total);
                    try {
                        //$html = $html.'<tr><td colspan="5">'.round((($total * (10 / 100)) + $total),2).'</td></tr>';

                        //saveInvoice($mysqli, $invoiceNo, date('Y-m-d', strtotime($invDate)), $weekendingDate, $client, $total, ($total * 10 / 100), ($total * 10 / 100) + $total);
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
            $k++;
        }

        $html . '</tbody></table></div>';
        $fileName = 'invoiceTest_' . $invoiceNo . '_' . date('Y-m-d');
        $filePath = __DIR__ . '/invoice/' . $fileName . '.pdf';
        $downloadPath = './invoice/' . $fileName . '.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->lastPage();
        $pdf->Output(__DIR__ . '/invoice/' . $fileName . '.pdf', 'F');//I
        //saveInvoicePath($mysqli,$weekendingDate,$downloadPath,$fileName.'.pdf');
        echo './invoice/' . $fileName . '.pdf';
    } else {
        echo $invData;
    }
}else{
    echo 'exists';
}
?>