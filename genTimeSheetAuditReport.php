<?php

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
require_once "includes/TCPDF-main/tcpdf.php";
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
date_default_timezone_set('Australia/Melbourne');

$weekendingDate = $_POST['weekEndingDateStart'];
$weekEndingDateEnd = $_POST['weekEndingDateEnd'];
$payDate = $_POST['payDate'];
$rateYear = $_POST['rateYear'];
$payrollName = getPayrollNameById($mysqli,$_POST['payrollName']);
$empId = $_POST['candidateId'];
$jbCode = $_POST['jobcode'];
$profitCentre = $_POST['profitCentre'];
$clCode = $_POST['clientCode'];
$clientArray = array();
try {
    $timeSheetData = getTimeSheetTotals($mysqli, $weekendingDate, $empId, $jbCode, $profitCentre, $clCode);
}catch (Exception $e){
    echo $e->getMessage();
}

if(!empty($timeSheetData)) {
    // create new PDF document
    $pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('TimeSheet Audit Report');
    $pdf->SetSubject('TimeSheet Audit Report');
    $pdf->SetKeywords('TimeSheet Audit Report');
    define('PDF_CUSTOM_HEADER_STRING', 'Week Ending Worked:' . $weekendingDate . '                          User:' . $_SESSION['userSession'] . '               Printed: ' . date("Y-m-d H:i:s"));
    // set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);

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
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }

    // set font
    $pdf->SetFont('helvetica', '', 10);

    // add a page
    $pdf->AddPage();

    $html = $html . '<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
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
<table border="1">
    <thead>
        <tr>
            <th class="wkdate" style="text-align: center;width: 8%;text-transform: uppercase;">Week Ending Date</th>
            <th class="paydate" style="text-align: center;width: 8%;text-transform: uppercase;">Pay Date</th>
            <th class="empid" style="text-align: center;width: 12%;text-transform: uppercase;">Employee ID</th>
            <th class="tscode" style="text-align: center;width: 5%;text-transform: uppercase;">T/S Code</th>
            <th class="desc" style="text-align: center;width: 25%;text-transform: uppercase;">Description</th>
            <th class="units" style="text-align: center;width: 5%;text-transform: uppercase;">Units</th>
            <th class="payrate" style="text-align: center;width: 8%;text-transform: uppercase;">Pay Rate</th>
            <th class="amount" style="text-align: center;width: 8%;text-transform: uppercase;">Amount</th>
            <th class="chunits" style="text-align: center;width: 5%;text-transform: uppercase;">Units</th>
            <th class="chrate" style="text-align: center;width: 8%;text-transform: uppercase;">Charge Rate</th>
            <th class="chamount" style="text-align: center;width: 8%;text-transform: uppercase;">Amount</th>
        </tr>    
    </thead>
    <tbody>';
    if (!empty($timeSheetData)) {

        $transCodeArray = createTransCodeArray($mysqli);
        $clientArray = array();
        $rowCount = 1;
        $superClient;
        $superPosition;
        $totalUnits;
        $client;
        $positionId;
        $candidateId;
        /*$allSubTotalUnits;
        $allTotalUnits;
        $allTotalChargeAmount;
        $allTotalPayAmount;
        $allTotalSuperPayAmount;
        $allTotalEarlyMorningUnits;
        $allTotalDayUnits;
        $allTotalSuperUnits;
        $allGrossPerClient;
        $totalEMGUnits;
        $totalDayUnits;
        $totalAftUnits;
        $totalNightUnits;
        $totalOvertimeUnits;
        $totalSatOvertimeUnits;
        $totalSunOvertimeUnits;
        $totalPeriodOvertimeUnits;
        $totalDoubletimeUnits;
        $totalSatUnits;
        $totalSunUnits;
        $totalHolidayUnits;

        $totalEMGSuperAmount;
        $totalDaySuperAmount;
        $totalAftSuperAmount;
        $totalNightSuperAmount;
        $totalOvertimeSuperAmount;
        $totalSatOvertimeSuperAmount;
        $totalSunOvertimeSuperAmount;
        $totalPeriodOvertimeSuperAmount;
        $totalDoubletimeSuperAmount;
        $totalSatSuperAmount;
        $totalSunSuperAmount;
        $totalHolidaySuperAmount;

        $totalEMGPayAmount;
        $totalDayPayAmount;
        $totalAftPayAmount;
        $totalNightPayAmount;
        $totalOvertimePayAmount;
        $totalSatOvertimePayAmount;
        $totalSunOvertimePayAmount;
        $totalPeriodOvertimePayAmount;
        $totalDoubletimePayAmount;
        $totalSatPayAmount;
        $totalSunPayAmount;
        $totalHolidayPayAmount;
        $totalSuperPayAmount;

        $totalEMGChargeAmount;
        $totalDayChargeAmount;
        $totalAftChargeAmount;
        $totalNightChargeAmount;
        $totalOvertimeChargeAmount;
        $totalSatOvertimeChargeAmount;
        $totalSunOvertimeChargeAmount;
        $totalPeriodOvertimeChargeAmount;
        $totalDoubletimeChargeAmount;
        $totalSatChargeAmount;
        $totalSunChargeAmount;
        $totalHolidayChargeAmount;*/

        $superCode;
        $superFundDesc;
        $totalSuperPay;
        $totalNetAmount=0;
        $totalTaxAmount=0;
        $counter = 0;
        $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $payrollName . '</td></tr>';

        foreach ($timeSheetData as $data) {
            if(empty($candidateId)){
                $candidateId = $data['candidateId'];
                $transCode = candidateSuperFundTransCode($mysqli, $candidateId);
                $candidateSuperFundDesc = getTransCodeDescByTransCode($mysqli, $transCode);
            }
            $rowCount++;
            if (empty($client)) {
                $client = $data['clientId'];
                $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                $clientCode = getClientCodeById($mysqli, $data['clientId']);
                $position = getPositionByPositionId($mysqli, $data['positionId']);
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $clientName .' - '. $clientCode .' - ' . $position.'</td></tr>';
                $clientArray[$data['clientId']] = array('payUnits' => '', 'payAmount' => '', 'billUnits' => '', 'billAmount' => '', 'superTypeCount' => '', 'superPayAmount' => '', 'tax'=>'', 'net'=>'', 'gross' => '');
            }
            if($client != $data['clientId']){
                $client = $data['clientId'];
                $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                $clientCode = getClientCodeById($mysqli, $data['clientId']);
                $position = getPositionByPositionId($mysqli, $data['positionId']);
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $clientName .' - '. $clientCode .' - ' . $position . '</td></tr>';
                $clientArray[$data['clientId']] = array('payUnits' => '', 'payAmount' => '', 'billUnits' => '', 'billAmount' => '', 'superTypeCount' => '', 'superPayAmount' => '',  'tax'=>'', 'net'=>'', 'gross' => '');
            }
            /*elseif($client == $data['clientId']){
                $client = $data['clientId'];
                $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                if($positionId != $data['positionId']){
                    $position = getPositionByPositionId($mysqli, $data['positionId']);
                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $clientName . ' - ' . $position . '</td></tr>';
                    $clientArray[$data['clientId']] = array('payUnits' => '', 'payAmount' => '', 'billUnits' => '', 'billAmount' => '', 'superTypeCount' => '', 'superPayAmount' => '', 'tax' => '', 'net' => '', 'gross' => '');
                }
            }*/
            if (empty($positionId)) {
                $positionId = $data['positionId'];
                $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                $position = getPositionByPositionId($mysqli, $data['positionId']);
                //$html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $clientName . ' - ' . $position . '</td></tr>';
                //$clientArray[$data['clientId']] = array('payUnits' => '', 'payAmount' => '', 'billUnits' => '', 'billAmount' => '', 'superTypeCount' => '', 'superPayAmount' => '', 'tax' => '', 'net' => '', 'gross' => '');

            }
            /*if($positionId != $data['positionId']){
                $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                $position = getPositionByPositionId($mysqli, $data['positionId']);
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $clientName . ' - ' . $position . '</td></tr>';
                $clientArray[$data['clientId']] = array('payUnits' => '', 'payAmount' => '', 'billUnits' => '', 'billAmount' => '', 'superTypeCount' => '', 'superPayAmount' => '',  'tax'=>'', 'net'=>'', 'gross' => '');
            }*/
            /*if ($client != $data['clientId']) {
                $client = $data['clientId'];
                $allSubTotalUnits = $subTotalUnits;
                $allTotalUnits = $totalUnits;
                $allTotalPayAmount = $totalPayAmount;
                $allTotalChargeAmount = $totalChargeAmount;
                $allTotalSuperPayAmount = $totalSuperPayAmount;
                $allTotalSuperUnits = $superUnitCount;

                $allTotalEMGUnits = $totalEMGUnits;
                $allTotalEMGPayAmount = $totalEMGPayAmount;
                $allTotalEMGChargeAmount = $totalEMGChargeAmount;
                $allTotalEMGSuperAmount = $totalEMGSuperAmount;

                $allTotalDayUnits = $totalDayUnits;
                $allTotalDayPayAmount = $totalDayPayAmount;
                $allTotalDayChargeAmount = $totalDayChargeAmount;
                $allTotalDaySuperAmount = $totalDaySuperAmount;
                $allGrossPerClient = $overallTotal;

                $allTotalAftUnits = $totalAftUnits;
                $allTotalAftPayAmount = $totalAftPayAmount;
                $allTotalAftChargeAmount = $totalAftChargeAmount;
                $allTotalAftSuperAmount = $totalAftSuperAmount;

                $allTotalNightUnits = $totalNightUnits;
                $allTotalNightPayAmount = $totalNightPayAmount;
                $allTotalNightChargeAmount = $totalNightChargeAmount;
                $allTotalNightSuperAmount = $totalNightSuperAmount;

                $allTotalOvertimeUnits = $totalOvertimeUnits;
                $allTotalOvertimePayAmount = $totalOvertimePayAmount;
                $allTotalOvertimeChargeAmount = $totalOvertimeChargeAmount;
                $allTotalOvertimeSuperAmount = $totalOvertimeSuperAmount;

                $allTotalSatOvertimeUnits = $totalSatOvertimeUnits;
                $allTotalSatOvertimePayAmount = $totalSatOvertimePayAmount;
                $allTotalSatOvertimeChargeAmount = $totalSatOvertimeChargeAmount;
                $allTotalSatOvertimeSuperAmount = $totalSatOvertimeSuperAmount;

                $allTotalSunOvertimeUnits = $totalSunOvertimeUnits;
                $allTotalSunOvertimePayAmount = $totalSunOvertimePayAmount;
                $allTotalSunOvertimeChargeAmount = $totalSunOvertimeChargeAmount;
                $allTotalSunOvertimeSuperAmount = $totalSunOvertimeSuperAmount;

                $allTotalPeriodOvertimeUnits = $totalPeriodOvertimeUnits;
                $allTotalPeriodOvertimePayAmount = $totalPeriodOvertimePayAmount;
                $allTotalPeriodOvertimeChargeAmount = $totalPeriodOvertimeChargeAmount;
                $allTotalPeriodOvertimeSuperAmount = $totalPeriodOvertimeSuperAmount;

                $allTotalDoubletimeUnits = $totalDoubletimeUnits;
                $allTotalDoubletimePayAmount = $totalDoubletimePayAmount;
                $allTotalDoubletimeChargeAmount = $totalDoubletimeChargeAmount;
                $allTotalDoubletimeSuperAmount = $totalDoubletimeSuperAmount;

                $allTotalSatUnits = $totalSatUnits;
                $allTotalSatPayAmount = $totalSatPayAmount;
                $allTotalSatChargeAmount = $totalSatChargeAmount;
                $allTotalSatSuperAmount = $totalSatSuperAmount;

                $allTotalSunUnits = $totalSunUnits;
                $allTotalSunPayAmount = $totalSunPayAmount;
                $allTotalSunChargeAmount = $totalSunChargeAmount;
                $allTotalSunSuperAmount = $totalSunSuperAmount;

                $allTotalHolidayUnits = $totalHolidayUnits;
                $allTotalHolidayPayAmount = $totalHolidayPayAmount;
                $allTotalHolidayChargeAmount = $totalHolidayChargeAmount;
                $allTotalHolidaySuperAmount = $totalHolidaySuperAmount;


                $totalEMGUnits = 0;
                $totalDayUnits = 0;
                $totalAftUnits = 0;
                $totalNightUnits = 0;
                $totalOvertimeUnits = 0;
                $totalSatOvertimeUnits = 0;
                $totalSunOvertimeUnits = 0;
                $totalPeriodOvertimeUnits = 0;
                $totalDoubletimeUnits = 0;
                $totalSatUnits = 0;
                $totalSunUnits = 0;
                $totalHolidayUnits = 0;

                $totalEMGPayAmount = 0;
                $totalDayPayAmount = 0;
                $totalAftPayAmount = 0;
                $totalNightPayAmount = 0;
                $totalOvertimePayAmount = 0;
                $totalSatOvertimePayAmount = 0;
                $totalSunOvertimePayAmount = 0;
                $totalPeriodOvertimePayAmount = 0;
                $totalDoubletimePayAmount = 0;
                $totalSatPayAmount = 0;
                $totalSunPayAmount = 0;
                $totalHolidayPayAmount = 0;

                $totalEMGChargeAmount = 0;
                $totalDayChargeAmount = 0;
                $totalAftChargeAmount = 0;
                $totalNightChargeAmount = 0;
                $totalOvertimeChargeAmount = 0;
                $totalSatOvertimeChargeAmount = 0;
                $totalSunOvertimeChargeAmount = 0;
                $totalPeriodOvertimeChargeAmount = 0;
                $totalDoubletimeChargeAmount = 0;
                $totalSatChargeAmount = 0;
                $totalSunChargeAmount = 0;
                $totalHolidayChargeAmount = 0;

                $totalEMGSuperAmount = 0;
                $totalDaySuperAmount = 0;
                $totalAftSuperAmount = 0;
                $totalNightSuperAmount = 0;
                $totalOvertimeSuperAmount = 0;
                $totalSatOvertimeSuperAmount = 0;
                $totalSunOvertimeSuperAmount = 0;
                $totalPeriodOvertimeSuperAmount = 0;
                $totalDoubletimeSuperAmount = 0;
                $totalSatSuperAmount = 0;
                $totalSunSuperAmount = 0;
                $totalHolidaySuperAmount = 0;
                //$totalSuperPayAmount = 0;
                //$superPayAmount = 0;

                $superUnitCount = 0;
                $counter = 0;
                $allGrossPerClient = 0;

            }*/
            if ($positionId != $data['positionId']) {
                $positionId = $data['positionId'];
                $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                $position = getPositionByPositionId($mysqli, $data['positionId']);
                //$html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $clientName . ' - ' . $position . '</td></tr>';
            }

            $grossWages = 0;
            $emgTotal = 0;
            $dayTotal = 0;
            $aftTotal = 0;
            $nightTotal = 0;
            $rdoTotal = 0;
            $satTotal = 0;
            $sunTotal = 0;
            $ovtTotal = 0;
            $satovtTotal = 0;
            $sunovtTotal = 0;
            $dblTotal = 0;
            $hldTotal = 0;
            $povtTotal = 0;
            $hld_total = 0;
            $hldWithoutSuperTotal = 0;
            $payTotal = 0;
            $chargeTotal = 0;

            $subTotalUnits = 0;
            $totalUnits = 0;
            $totalPayAmount = 0;

            $totalChargeAmount = 0;
            $totalEMGChargeAmount = 0;
            $totalDayChargeAmount = 0;
            $totalAftChargeAmount = 0;
            $totalNightChargeAmount = 0;
            $totalRDOChargeAmount = 0;
            $totalSatChargeAmount = 0;
            $totalSatChargeAmountWithSuper = 0;
            $totalSunChargeAmount = 0;
            $totalSunChargeAmountWithSuper = 0;
            $totalOvertimeChargeAmount = 0;
            $totalSatOvertimeChargeAmount = 0;
            $totalSunOvertimeChargeAmount = 0;
            $totalPeriodOvertimeChargeAmount = 0;
            $totalDoubletimeChargeAmount = 0;
            $totalHolidayChargeAmount= 0;
            $totalHoliday2ChargeAmount= 0;
            $totalHolidayChargeAmountWithSuper = 0;


            $overallTotal = 0;
            $overallSuperTotal = 0;
            $overallChargeTotal = 0;
            $superTypeCount = 1;
            $superUnitCount = 0;
            $totalEMGPayAmount=0;
            $totalDayPayAmount=0;
            $totalAftPayAmount=0;
            $totalNightPayAmount=0;
            $totalRDOPayAmount = 0;
            $totalHoliday2PayAmount = 0;
            $totalSatPayAmount=0;
            $totalSatPayAmountWithSuper = 0;
            $totalSunPayAmount=0;
            $totalSunPayAmountWithSuper = 0;
            $totalHolidayPayAmount=0;
            $totalHolidayPayAmountWithSuper = 0;
            $totalHoliday2PayAmount = 0;
            $totalOvertimePayAmount = 0;
            $totalSatOvertimePayAmount = 0;
            $totalSunOvertimePayAmount = 0;
            $totalDoubletimePayAmount = 0;
            $totalPeriodOvertimePayAmount = 0;

            $clientName = getClientNameByClientId($mysqli, $data['clientId']);
            $position = getPositionByPositionId($mysqli, $data['positionId']);


            $emgPayCatCode = getPayCatCode($mysqli, 'EARLY MORNING');
            $dayPayCatCode = getPayCatCode($mysqli, 'ORDINARY');
            $aftPayCatCode = getPayCatCode($mysqli, 'AFTERNOON');
            $nightPayCatCode = getPayCatCode($mysqli, 'NIGHT');
            $rdoPayCatCode = getPayCatCode($mysqli, 'RDO');
            $satPayCatCode = getPayCatCode($mysqli, 'SATURDAY');
            $sunPayCatCode = getPayCatCode($mysqli, 'SUNDAY');
            $overtimePayCatCode = getPayCatCode($mysqli, 'OVERTIME');
            $satOvertimePayCatCode = getPayCatCode($mysqli, 'SATURDAY OVERTIME');
            $sunOvertimePayCatCode = getPayCatCode($mysqli, 'SUNDAY OVERTIME');
            $periodOvertimePayCatCode = getPayCatCode($mysqli, 'PERIOD OVERTIME');
            $doubletimePayCatCode = getPayCatCode($mysqli, 'DOUBLETIME');
            $holidayPayCatCode = getPayCatCode($mysqli, 'PUBLIC HOLIDAY');
            $holiday2PayCatCode = getPayCatCode($mysqli, 'PUBLIC HOLIDAY 2');
            $hldSuperPayCatCode = getPayCatCode($mysqli, 'HOLIDAY WITH SUPER');
            $satSuperPayCatCode = getPayCatCode($mysqli, 'SATURDAY WITH SUPER');
            $sunSuperPayCatCode = getPayCatCode($mysqli, 'SUNDAY WITH SUPER');

            if($candidateId != $data['candidateId']) {
                $candidateId = $data['candidateId'];
                $transCode = candidateSuperFundTransCode($mysqli, $candidateId);
                $candidateSuperFundDesc = getTransCodeDescByTransCode($mysqli, $transCode);
                $counter = 0;
                $employeeName = getCandidateFirstNameByCandidateId($mysqli, $candidateId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $candidateId);
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $employeeName .' '.$data['jobcode'].' '.$position.' '.getDepartmentById($mysqli,$data['deptId']).'</td></tr>';

            }else if($candidateId == $data['candidateId']) {
                $transCode = candidateSuperFundTransCode($mysqli, $candidateId);
                $candidateSuperFundDesc = getTransCodeDescByTransCode($mysqli, $transCode);
                $employeeName = getCandidateFirstNameByCandidateId($mysqli, $candidateId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $candidateId);
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td colspan="11" class="title">' . $employeeName .' '.$data['jobcode'].' '.$position.' '.getDepartmentById($mysqli,$data['deptId']).'</td></tr>';
            }

            if (($data['ordTotal']) > 0) {
                $rowCount++;
                $dayTotal = $data['ordTotal'];
                if(!empty($rateYear)){
                    $dayPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode,$data['jobcode'],$rateYear);
                    $dayChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $dayPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode, $data['jobcode']);
                    $dayChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode, $data['jobcode']);
                }
                $dayPayTotal = calculatePayAmount($dayTotal, $dayPayRate);
                $dayChargeTotal = calculateChargeAmount($dayTotal, $dayChargeRate);

                $totalDayUnits = $totalDayUnits + $dayTotal;
                $totalDayPayAmount = round(($totalDayPayAmount + $dayPayTotal),2);
                $totalDayChargeAmount = round(($totalDayChargeAmount + $dayChargeTotal),2);

                $overallTotal = round(($overallTotal + $dayPayTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalDayPayAmount),2);
                $overallChargeTotal = round(($overallChargeTotal + $dayChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'DAY SHIFT';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . number_format($dayTotal,2);
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $dayPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($dayPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . number_format($dayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $dayChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($dayChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';
            }
            if (($data['emgTotal']) > 0) {
                $rowCount++;
                $emgTotal = $data['emgTotal'];
                if(!empty($rateYear)){
                    $emgPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode,$data['jobcode'], $rateYear);
                    $emgChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode,$data['jobcode'], $rateYear);
                }else {
                    $emgPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode, $data['jobcode']);
                    $emgChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode, $data['jobcode']);
                }
                $emgPayTotal = calculatePayAmount($emgTotal, $emgPayRate);
                $emgChargeTotal = calculateChargeAmount($emgTotal, $emgChargeRate);

                $totalEMGUnits = $totalEMGUnits + $emgTotal;
                $totalEMGPayAmount = round(($totalEMGPayAmount + $emgPayTotal),2);
                $totalEMGChargeAmount = round(($totalEMGChargeAmount + $emgChargeTotal),2);

                $overallTotal = round(($overallTotal + $emgPayTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalEMGPayAmount),2);
                $overallChargeTotal = round(($overallChargeTotal + $emgChargeTotal),2);

                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'EARLY MORNING';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $emgTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $emgPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($emgPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $emgTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $emgChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($emgChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';
            }
            if (($data['aftTotal']) > 0) {
                $rowCount++;
                $aftTotal = $data['aftTotal'];
                if(!empty($rateYear)){
                    $aftPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobcode'],$rateYear);
                    $aftChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $aftPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobcode']);
                    $aftChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobcode']);
                }
                $aftPayTotal = calculatePayAmount($aftTotal, $aftPayRate);
                $aftChargeTotal = calculateChargeAmount($aftTotal, $aftChargeRate);

                $totalAftUnits = $totalAftUnits + $aftTotal;
                $totalAftPayAmount = round(($totalAftPayAmount + $aftPayTotal),2);
                $totalAftChargeAmount = round(($totalAftChargeAmount + $aftChargeTotal),2);

                $overallTotal = round(($overallTotal + $aftPayTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalAftPayAmount),2);
                $overallChargeTotal = round(($overallChargeTotal + $aftChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'AFTERNOON SHIFT';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $aftTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $aftPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($aftPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $aftTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $aftChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($aftChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';

            }
            if (($data['nightTotal']) > 0) {
                $rowCount++;

                $nightTotal = $data['nightTotal'];
                if(!empty($rateYear)){
                    $nightPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobcode'],$rateYear);
                    $nightChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $nightPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobcode']);
                    $nightChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobcode']);
                }
                $nightPayTotal = calculatePayAmount($nightTotal, $nightPayRate);
                $nightChargeTotal = calculateChargeAmount($nightTotal, $nightChargeRate);

                $totalNightUnits = $totalNightUnits + $nightTotal;
                $totalNightPayAmount = round(($totalNightPayAmount + $nightPayTotal),2);
                $totalNightChargeAmount =round(( $totalNightChargeAmount + $nightChargeTotal),2);

                $overallTotal = round(($overallTotal + $nightPayTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalNightPayAmount),2);
                $overallChargeTotal = round(($overallChargeTotal + $nightChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'NIGHT SHIFT';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . number_format($nightTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $nightPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($nightPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . number_format($nightTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $nightChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($nightChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';

            }
            if (($data['rdoTotal']) > 0) {
                $rowCount++;

                $rdoTotal = $data['rdoTotal'];
                if(!empty($rateYear)){
                    $rdoPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobcode'],$rateYear);
                    $rdoChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $rdoPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobcode']);
                    $rdoChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobcode']);
                }
                $rdoPayTotal = calculatePayAmount($rdoTotal, $rdoPayRate);
                $rdoChargeTotal = calculateChargeAmount($rdoTotal, $rdoChargeRate);

                $totalRDOUnits = $totalRDOUnits + $rdoTotal;
                $totalRDOPayAmount = round(($totalRDOPayAmount + $rdoPayTotal),2);
                $totalRDOChargeAmount = round(($totalRDOChargeAmount + $rdoChargeTotal),2);

                $overallTotal = round(($overallTotal + $rdoPayTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalRDOPayAmount),2);
                $overallChargeTotal = round(($overallChargeTotal + $rdoChargeTotal),2);

                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'RDO';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . number_format($rdoTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $rdoPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($rdoPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . number_format($rdoTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $rdoChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($rdoChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';

            }
            if($data['clientId'] == 82){
                if(($data['hol_total']) > 0){
                    $rowCount++;

                    $hld_total = $data['hol_total'];
                    if(!empty($rateYear)){
                        $hld2PayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $holiday2PayCatCode,$data['jobcode'],$rateYear);
                        $hld2ChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $holiday2PayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $hld2PayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holiday2PayCatCode,$data['jobcode']);
                        $hld2ChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holiday2PayCatCode,$data['jobcode']);
                    }
                    $hld2PayTotal = calculatePayAmount($hld_total, $hld2PayRate);
                    $hld2ChargeTotal = calculateChargeAmount($hld_total, $hld2ChargeRate);

                    $totalHoliday2Units = $totalHoliday2Units + $hld_total;
                    $totalHoliday2PayAmount = round(($totalHoliday2PayAmount + $hld2PayTotal),2);
                    $totalHoliday2ChargeAmount = round(($totalHoliday2ChargeAmount + $hld2ChargeTotal),2);

                    $overallTotal = round(($overallTotal + $hld2PayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalHoliday2PayAmount),2);
                    $overallChargeTotal = round(($overallChargeTotal + $hld2ChargeTotal),2);

                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'PUBLIC HOLIDAY 2';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($hld_total, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $hld2PayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($hld2PayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($hld_total, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $hld2ChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($hld2ChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                }
                if(($data['hldTotal']) > 0){
                    $rowCount++;
                    $hldTotal = $data['hldTotal'];
                    if(!empty($rateYear)){
                        $hldPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode'],$rateYear);
                        $hldChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $hldPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode']);
                        $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode']);
                    }
                    $hldPayTotal = calculatePayAmount($hldTotal, $hldPayRate);
                    $hldChargeTotal = calculateChargeAmount($hldTotal, $hldChargeRate);

                    $totalHolidayUnits = $totalHolidayUnits + $hldTotal;
                    $totalHolidayPayAmount = round(($totalHolidayPayAmount + $hldPayTotal), 2);
                    $totalHolidayChargeAmount = round(($totalHolidayChargeAmount + $hldChargeTotal), 2);

                    $overallTotal = round(($overallTotal + $hldPayTotal), 2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmount), 2);
                    $overallChargeTotal = round(($overallChargeTotal + $hldChargeTotal), 2);

                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'PUBLIC HOLIDAY';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($hldTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $hldPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($hldPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($hldTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $hldChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($hldChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                }

            }else {
                if (($data['hldTotal']) > 0) {
                    $wkTotal = $dayTotal + $emgTotal + $aftTotal + $nightTotal + $satTotal + $sunTotal;
                    $rowCount++;
                    $hldTotal = $data['hldTotal'];
                    $avgNormalHrs = getAverageNormalHours($mysqli, $data['jobcode']);
                    $hldWithoutSuperTotal = 0.00;
                    if (($wkTotal + $hldTotal) > $avgNormalHrs) {
                        $hldWithoutSuperTotal = $hldTotal - ($avgNormalHrs - $wkTotal);
                        $hldTotal = ($avgNormalHrs - $wkTotal);

                        if(!empty($rateYear)){
                            $hldPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode'],$rateYear);
                            $hldChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode'],$rateYear);
                        }else {
                            $hldPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode']);
                            $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode']);
                        }
                        $hldPayTotal = calculatePayAmount($hldTotal, $hldPayRate);
                        $hldChargeTotal = calculateChargeAmount($hldTotal, $hldChargeRate);

                        $totalHolidayUnitsWithSuper = $totalHolidayUnitsWithSuper + $hldTotal;
                        $totalHolidayPayAmountWithSuper = round(($totalHolidayPayAmountWithSuper + $hldPayTotal), 2);
                        $totalHolidayChargeAmountWithSuper = round(($totalHolidayChargeAmountWithSuper + $hldChargeTotal), 2);

                        $overallTotal = round(($overallTotal + $hldPayTotal), 2);
                        $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmountWithSuper), 2);
                        $overallChargeTotal = round(($overallChargeTotal + $hldChargeTotal), 2);
                        if($hldTotal != '0.00') {
                            $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                            $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                            $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                            $html = $html . '<td class="empId">' . $candidateId . '</td>';
                            $html = $html . '<td class="shortWidth"></td>';
                            $html = $html . '<td class="desc">';
                            $html = $html . 'HOLIDAY WITH SUPER';
                            $html = $html . '</td>';
                            $html = $html . '<td class="shortWidth">';
                            $html = $html . number_format($hldTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . $hldPayRate;
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . number_format($hldPayTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '<td class="shortWidth">';
                            $html = $html . number_format($hldTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . $hldChargeRate;
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . number_format($hldChargeTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '</tr>';
                        }
                    }elseif(($wkTotal + $hldTotal) <= $avgNormalHrs){
                        if(!empty($rateYear)){
                            $hldPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode'],$rateYear);
                            $hldChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode'],$rateYear);
                        }else {
                            $hldPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode']);
                            $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $hldSuperPayCatCode,$data['jobcode']);
                        }
                        $hldPayTotal = calculatePayAmount($hldTotal, $hldPayRate);
                        $hldChargeTotal = calculateChargeAmount($hldTotal, $hldChargeRate);

                        $totalHolidayUnitsWithSuper = $totalHolidayUnitsWithSuper + $hldTotal;
                        $totalHolidayPayAmountWithSuper = round(($totalHolidayPayAmountWithSuper + $hldPayTotal), 2);
                        $totalHolidayChargeAmountWithSuper = round(($totalHolidayChargeAmountWithSuper + $hldChargeTotal), 2);

                        $overallTotal = round(($overallTotal + $hldPayTotal), 2);
                        $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmountWithSuper), 2);
                        $overallChargeTotal = round(($overallChargeTotal + $hldChargeTotal), 2);
                        if($hldTotal != '0.00') {
                            $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                            $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                            $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                            $html = $html . '<td class="empId">' . $candidateId . '</td>';
                            $html = $html . '<td class="shortWidth"></td>';
                            $html = $html . '<td class="desc">';
                            $html = $html . 'HOLIDAY WITH SUPER';
                            $html = $html . '</td>';
                            $html = $html . '<td class="shortWidth">';
                            $html = $html . number_format($hldTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . $hldPayRate;
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . number_format($hldPayTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '<td class="shortWidth">';
                            $html = $html . number_format($hldTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . $hldChargeRate;
                            $html = $html . '</td>';
                            $html = $html . '<td class="cellWidth">';
                            $html = $html . number_format($hldChargeTotal, 2);
                            $html = $html . '</td>';
                            $html = $html . '</tr>';
                        }
                    } else {
                        if(!empty($rateYear)){
                            $hldPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode'],$rateYear);
                            $hldChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode'],$rateYear);
                        }else {
                            $hldPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode']);
                            $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode']);
                        }
                        $hldPayTotal = calculatePayAmount($hldTotal, $hldPayRate);
                        $hldChargeTotal = calculateChargeAmount($hldTotal, $hldChargeRate);

                        $totalHolidayUnits = $totalHolidayUnits + $hldTotal;
                        $totalHolidayPayAmount = round(($totalHolidayPayAmount + $hldPayTotal), 2);
                        $totalHolidayChargeAmount = round(($totalHolidayChargeAmount + $hldChargeTotal), 2);

                        $overallTotal = round(($overallTotal + $hldPayTotal), 2);
                        $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmount), 2);
                        $overallChargeTotal = round(($overallChargeTotal + $hldChargeTotal), 2);

                        $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                        $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                        $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                        $html = $html . '<td class="empId">' . $candidateId . '</td>';
                        $html = $html . '<td class="shortWidth"></td>';
                        $html = $html . '<td class="desc">';
                        $html = $html . 'PUBLIC HOLIDAY';
                        $html = $html . '</td>';
                        $html = $html . '<td class="shortWidth">';
                        $html = $html . number_format($hldTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . $hldPayRate;
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . number_format($hldPayTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '<td class="shortWidth">';
                        $html = $html . number_format($hldTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . $hldChargeRate;
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . number_format($hldChargeTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '</tr>';
                    }
                    if ($hldWithoutSuperTotal != 0) {
                        if(!empty($rateYear)){
                            $hldPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode'],$rateYear);
                            $hldChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode'],$rateYear);
                        }else {
                            $hldPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode']);
                            $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobcode']);
                        }
                        $hldPayTotal = calculatePayAmount($hldWithoutSuperTotal, $hldPayRate);
                        $hldChargeTotal = calculateChargeAmount($hldWithoutSuperTotal, $hldChargeRate);

                        $totalHolidayUnits = $totalHolidayUnits + $hldWithoutSuperTotal;
                        $totalHolidayPayAmount = round(($totalHolidayPayAmount + $hldPayTotal), 2);
                        $totalHolidayChargeAmount = round(($totalHolidayChargeAmount + $hldChargeTotal), 2);

                        $overallTotal = round(($overallTotal + $hldPayTotal), 2);
                        $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmount), 2);
                        $overallChargeTotal = round(($overallChargeTotal + $hldChargeTotal), 2);


                        $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                        $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                        $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                        $html = $html . '<td class="empId">' . $candidateId . '</td>';
                        $html = $html . '<td class="shortWidth"></td>';
                        $html = $html . '<td class="desc">';
                        $html = $html . 'PUBLIC HOLIDAY';
                        $html = $html . '</td>';
                        $html = $html . '<td class="shortWidth">';
                        $html = $html . number_format($hldWithoutSuperTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . $hldPayRate;
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . number_format($hldPayTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '<td class="shortWidth">';
                        $html = $html . number_format($hldWithoutSuperTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . $hldChargeRate;
                        $html = $html . '</td>';
                        $html = $html . '<td class="cellWidth">';
                        $html = $html . number_format($hldChargeTotal, 2);
                        $html = $html . '</td>';
                        $html = $html . '</tr>';
                    }
                }
            }


            if (($data['satTotal']) > 0) {
                $wkTotal = $dayTotal + $emgTotal + $aftTotal + $nightTotal + $hldTotal + $sunTotal;
                $rowCount++;
                $satTotal = $data['satTotal'];
                $avgNormalHrs = getAverageNormalHours($mysqli, $data['jobcode']);
                $satWithoutSuperTotal = 0.00;
                if (($wkTotal + $satTotal) <= $avgNormalHrs) {
                    /*$satWithoutSuperTotal = $satTotal - ($avgNormalHrs - $wkTotal);
                    $satTotal = ($avgNormalHrs - $wkTotal);*/
                    if(!empty($rateYear)){
                        $satPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $satSuperPayCatCode,$data['jobcode'],$rateYear);
                        $satChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $satSuperPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $satPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $satSuperPayCatCode,$data['jobcode']);
                        $satChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $satSuperPayCatCode,$data['jobcode']);
                    }
                    $satPayTotal = calculatePayAmount($satTotal, $satPayRate);
                    $satChargeTotal = calculateChargeAmount($satTotal, $satChargeRate);

                    $totalSatUnitsWithSuper = $totalSatUnitsWithSuper + $satTotal;
                    $totalSatPayAmountWithSuper = round(($totalSatPayAmountWithSuper + $satPayTotal),2);
                    $totalSatChargeAmountWithSuper = round(($totalSatChargeAmountWithSuper + $satChargeTotal),2);

                    $overallTotal = round(($overallTotal + $satPayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalSatPayAmountWithSuper),2);
                    $overallChargeTotal = round(($overallChargeTotal + $satChargeTotal),2);


                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'SATURDAY WITH SUPER';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($satTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $satPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($satPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . $satTotal;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $satChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($satChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                } else {
                    if(!empty($rateYear)){
                        $satPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode'],$rateYear);
                        $satChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $satPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode']);
                        $satChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode']);
                    }
                    $satPayTotal = calculatePayAmount($satTotal, $satPayRate);
                    $satChargeTotal = calculateChargeAmount($satTotal, $satChargeRate);

                    $totalSatUnits = $totalSatUnits + $satTotal;
                    $totalSatPayAmount = round(($totalSatPayAmount + $satPayTotal),2);
                    $totalSatChargeAmount = round(($totalSatChargeAmount + $satChargeTotal),2);

                    $overallTotal = round(($overallTotal + $satPayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalSatPayAmount),2);
                    $overallChargeTotal = round(($overallChargeTotal + $satChargeTotal),2);


                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'SATURDAY';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($satTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $satPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($satPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . $satTotal;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $satChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($satChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                }
                if ($satWithoutSuperTotal != 0) {
                    if(!empty($rateYear)){
                        $satPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode'],$rateYear);
                        $satChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $satPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode']);
                        $satChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobcode']);
                    }
                    $satPayTotal = calculatePayAmount($satWithoutSuperTotal, $satPayRate);

                    $satChargeTotal = calculateChargeAmount($satWithoutSuperTotal, $satChargeRate);

                    $totalSatUnits = $totalSatUnits + $satWithoutSuperTotal;
                    $totalSatPayAmount = round(($totalSatPayAmount + $satPayTotal),2);
                    $totalSatChargeAmount = round(($totalSatChargeAmount + $satChargeTotal),2);

                    $overallTotal = round(($overallTotal + $satPayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalSatPayAmount),2);
                    $overallChargeTotal = round(($overallChargeTotal + $satChargeTotal),2);


                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'SATURDAY';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($satWithoutSuperTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $satPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($satPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . $satWithoutSuperTotal;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $satChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($satChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                }
            }
            if (($data['sunTotal']) > 0) {
                $wkTotal = $dayTotal + $emgTotal + $aftTotal + $nightTotal + $hldTotal + $satTotal;
                $rowCount++;
                $sunTotal = $data['sunTotal'];

                $avgNormalHrs = getAverageNormalHours($mysqli, $data['jobcode']);
                $sunWithoutSuperTotal = 0.00;

                if (($wkTotal + $sunTotal) <= $avgNormalHrs) {
                    /*$sunWithoutSuperTotal = $sunTotal - ($avgNormalHrs - $wkTotal);
                    $sunTotal = ($avgNormalHrs - $wkTotal);*/
                    if(!empty($rateYear)){
                        $sunPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunSuperPayCatCode,$data['jobcode'],$rateYear);
                        $sunChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunSuperPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $sunPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $sunSuperPayCatCode,$data['jobcode']);
                        $sunChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $sunSuperPayCatCode,$data['jobcode']);
                    }
                    $sunPayTotal = calculatePayAmount($sunTotal, $sunPayRate);
                    $sunChargeTotal = calculateChargeAmount($sunTotal, $sunChargeRate);

                    $totalSunUnitsWithSuper = $totalSunUnitsWithSuper + $sunTotal;
                    $totalSunPayAmountWithSuper = round(($totalSunPayAmountWithSuper + $sunPayTotal),2);
                    $totalSunChargeAmountWithSuper = round(($totalSunChargeAmountWithSuper + $sunChargeTotal),2);

                    $overallTotal = round(($overallTotal + $sunPayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalSunPayAmountWithSuper),2);
                    $overallChargeTotal = round(($overallChargeTotal + $sunChargeTotal),2);


                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'SUNDAY WITH SUPER';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($sunTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $sunPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($sunPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($sunTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $sunChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($sunChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                } else {
                    if(!empty($rateYear)){
                        $sunPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode'],$rateYear);
                        $sunChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $sunPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode']);
                        $sunChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode']);
                    }
                    $sunPayTotal = calculatePayAmount($sunTotal, $sunPayRate);
                    $sunChargeTotal = calculateChargeAmount($sunTotal, $sunChargeRate);

                    $totalSunUnits = $totalSunUnits + $sunTotal;
                    $totalSunPayAmount = round(($totalSunPayAmount + $sunPayTotal),2);
                    $totalSunChargeAmount = round(($totalSunChargeAmount + $sunChargeTotal),2);


                    $overallTotal = round(($overallTotal + $sunPayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalSunPayAmount),2);
                    $overallChargeTotal = round(($overallChargeTotal + $sunChargeTotal),2);


                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'SUNDAY';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($sunTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $sunPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($sunPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($sunTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $sunChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($sunChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                }
                if ($sunWithoutSuperTotal != 0) {
                    if(!empty($rateYear)){
                        $sunPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode'],$rateYear);
                        $sunChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode'],$rateYear);
                    }else {
                        $sunPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode']);
                        $sunChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobcode']);
                    }
                    $sunPayTotal = calculatePayAmount($sunWithoutSuperTotal, $sunPayRate);

                    $sunChargeTotal = calculateChargeAmount($sunWithoutSuperTotal, $sunChargeRate);

                    $totalSunUnits = $totalSunUnits + $sunWithoutSuperTotal;
                    $totalSunPayAmount = round(($totalSunPayAmount + $sunPayTotal),2);
                    $totalSunChargeAmount = round(($totalSunChargeAmount + $sunChargeTotal),2);

                    $overallTotal = round(($overallTotal + $sunPayTotal),2);
                    $overallSuperTotal = round(($overallSuperTotal + $totalSunPayAmount),2);
                    $overallChargeTotal = round(($overallChargeTotal + $sunChargeTotal),2);


                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                    $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                    $html = $html . '<td class="empId">' . $candidateId . '</td>';
                    $html = $html . '<td class="shortWidth"></td>';
                    $html = $html . '<td class="desc">';
                    $html = $html . 'SUNDAY';
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($sunWithoutSuperTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $sunPayRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($sunPayTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="shortWidth">';
                    $html = $html . number_format($sunWithoutSuperTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . $sunChargeRate;
                    $html = $html . '</td>';
                    $html = $html . '<td class="cellWidth">';
                    $html = $html . number_format($sunChargeTotal, 2);
                    $html = $html . '</td>';
                    $html = $html . '</tr>';
                }
            }
            if (($data['ovtTotal'])> 0) {
                $rowCount++;
                $ovtTotal = $data['ovtTotal'];
                if(!empty($rateYear)){
                    $ovtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobcode'],$rateYear);
                    $ovtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $ovtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobcode']);
                    $ovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobcode']);
                }
                $ovtPayTotal = calculatePayAmount($ovtTotal, $ovtPayRate);
                $ovtChargeTotal = calculateChargeAmount($ovtTotal, $ovtChargeRate);

                $totalOvertimeUnits = $totalOvertimeUnits + $ovtTotal;
                $totalOvertimePayAmount = round(($totalOvertimePayAmount + $ovtPayTotal),2);
                $totalOvertimeChargeAmount = round(($totalOvertimeChargeAmount + $ovtChargeTotal),2);

                $overallTotal = round(($overallTotal + $ovtPayTotal),2);
                $overallChargeTotal = round(($overallChargeTotal + $ovtChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'OVERTIME SHIFT';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $ovtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $ovtPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($ovtPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $ovtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $ovtChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($ovtChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';
            }
            if (($data['satovtTotal']) > 0) {
                $rowCount++;
                $satovtTotal = $data['satovtTotal'];
                if(!empty($rateYear)){
                    $satOvtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobcode'],$rateYear);
                    $satOvtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $satOvtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobcode']);
                    $satOvtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobcode']);
                }
                $satOvtPayTotal = calculatePayAmount($satovtTotal, $satOvtPayRate);
                $satOvtChargeTotal = calculateChargeAmount($satovtTotal, $satOvtChargeRate);

                $totalSatOvertimeUnits = $totalSatOvertimeUnits + $satovtTotal;
                $totalSatOvertimePayAmount = round(($totalSatOvertimePayAmount + $satOvtPayTotal),2);
                $totalSatOvertimeChargeAmount = round(($totalSatOvertimeChargeAmount + $satOvtChargeTotal),2);


                $overallTotal = round(($overallTotal + $satOvtPayTotal),2);
                $overallChargeTotal = round(($overallChargeTotal + $satOvtChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'SATURDAY OVERTIME';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $satovtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $satOvtPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($satOvtPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $satovtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $satOvtChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($satOvtChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';
            }
            if (($data['sunovtTotal']) > 0) {
                $rowCount++;
                $sunovtTotal = $data['sunovtTotal'];
                if(!empty($rateYear)){
                    $sunPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobcode'],$rateYear);
                    $chargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $sunPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobcode']);
                    $chargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobcode']);
                }
                $sunPayTotal = calculatePayAmount($sunovtTotal, $sunPayRate);
                $chargeTotal = calculateChargeAmount($sunovtTotal, $chargeRate);

                $totalSunOvertimeUnits = $totalSunOvertimeUnits + $sunovtTotal;
                $totalSunOvertimePayAmount = round(($totalSunOvertimePayAmount + $sunOvtPayTotal),2);
                $totalSunOvertimeChargeAmount = round(($totalSunOvertimeChargeAmount + $sunOvtChargeTotal),2);

                $overallTotal = round(($overallTotal + $sunOvtPayTotal),2);
                $overallChargeTotal = round(($overallChargeTotal + $sunOvtChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'SUNDAY OVERTIME';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $sunovtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $sunOvtPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($sunOvtPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $sunovtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $sunOvtChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($sunOvtChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';
            }
            if (($data['povtTotal']) > 0) {
                $rowCount++;
                $povtTotal = $data['povtTotal'];
                if(!empty($rateYear)){
                    $periodOvtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobcode'],$rateYear);
                    $periodOvtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $periodOvtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobcode']);
                    $periodOvtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobcode']);
                }
                $periodOvtPayTotal = calculatePayAmount($povtTotal, $periodOvtPayRate);
                $periodOvtChargeTotal = calculateChargeAmount($povtTotal, $periodOvtChargeRate);

                $totalPeriodOvertimeUnits = $totalPeriodOvertimeUnits + $povtTotal;
                $totalPeriodOvertimePayAmount = round(($totalPeriodOvertimePayAmount + $periodOvtPayTotal),2);
                $totalPeriodOvertimeChargeAmount = round(($totalPeriodOvertimeChargeAmount + $periodOvtChargeTotal),2);

                $overallTotal = round(($overallTotal + $periodOvtPayTotal),2);
                $overallChargeTotal = round(($overallChargeTotal + $periodOvtChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'PERIOD OVERTIME';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $povtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $periodOvtPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($periodOvtPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $povtTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $periodOvtChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($periodOvtChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';
            }
            if (($data['dblTotal']) > 0) {
                $rowCount++;
                $dblTotal = $data['dblTotal'];
                if(!empty($rateYear)){
                    $dblPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobcode'],$rateYear);
                    $dblChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobcode'],$rateYear);
                }else {
                    $dblPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobcode']);
                    $dblChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobcode']);
                }
                $dblPayTotal = calculatePayAmount($dblTotal, $dblPayRate);

                $dblChargeTotal = calculateChargeAmount($dblTotal, $dblChargeRate);

                $totalDoubletimeUnits = $totalDoubletimeUnits + $dblTotal;
                $totalDoubletimePayAmount = round(($totalDoubletimePayAmount + $dblPayTotal),2);
                $totalDoubletimeChargeAmount = round(($totalDoubletimeChargeAmount + $dblChargeTotal),2);

                $overallTotal = round(($overallTotal + $dblPayTotal),2);
                $overallChargeTotal = round(($overallChargeTotal + $dblChargeTotal),2);


                $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                $html = $html . '<td class="cellWidth">' . $weekendingDate . '</td>';
                $html = $html . '<td class="cellWidth">' . $payDate . '</td>';
                $html = $html . '<td class="empId">' . $candidateId . '</td>';
                $html = $html . '<td class="shortWidth"></td>';
                $html = $html . '<td class="desc">';
                $html = $html . 'DOUBLE TIME SHIFT';
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $dblTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $dblPayRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($dblPayTotal, 2);
                $html = $html . '</td>';
                $html = $html . '<td class="shortWidth">';
                $html = $html . $dblTotal;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . $dblChargeRate;
                $html = $html . '</td>';
                $html = $html . '<td class="cellWidth">';
                $html = $html . number_format($dblChargeTotal, 2);
                $html = $html . '</td>';
                $html = $html . '</tr>';

            }

            $subTotalUnits = $emgTotal + $dayTotal + $aftTotal + $nightTotal + $rdoTotal + $satTotal + $satWithoutSuperTotal + $sunTotal + $sunWithoutSuperTotal + $ovtTotal + $satovtTotal + $sunovtTotal + $povtTotal + $dblTotal + $hldTotal + $hldWithoutSuperTotal;
            $totalUnitsForSuper = $emgTotal + $dayTotal + $aftTotal + $nightTotal + $rdoTotal + $satTotal + $sunTotal + $hldTotal;
            $totalWeekDayUnits = $emgTotal + $dayTotal + $aftTotal + $nightTotal;
            $totalUnits = $totalEMGUnits + $totalDayUnits + $totalAftUnits + $totalNightUnits + $totalRDOUnits + $totalSatUnits + $totalSunUnits + $totalOvertimeUnits + $totalSatOvertimeUnits + $totalSunOvertimeUnits + $totalPeriodOvertimeUnits + $totalDoubletimeUnits + $totalHolidayUnits + $totalHoliday2Units + $totalHolidayUnitsWithSuper + $totalSatUnitsWithSuper + $totalSunUnitsWithSuper;
            $totalPayAmount = round(($totalEMGPayAmount + $totalDayPayAmount + $totalAftPayAmount + $totalNightPayAmount + $totalRDOPayAmount + $totalSatPayAmount + $totalSunPayAmount + $totalOvertimePayAmount + $totalSatOvertimePayAmount + $totalSunOvertimePayAmount + $totalPeriodOvertimePayAmount + $totalDoubletimePayAmount + $totalHolidayPayAmount + $totalHoliday2PayAmount + $totalHolidayPayAmountWithSuper + $totalSatPayAmountWithSuper + $totalSunPayAmountWithSuper),2);
            $totalChargeAmount = round(($totalEMGChargeAmount + $totalDayChargeAmount + $totalAftChargeAmount + $totalNightChargeAmount + $totalRDOChargeAmount + $totalSatChargeAmount + $totalSunChargeAmount + $totalOvertimeChargeAmount + $totalSatOvertimeChargeAmount + $totalSunOvertimeChargeAmount + $totalPeriodOvertimeChargeAmount + $totalDoubletimeChargeAmount + $totalHolidayChargeAmount + $totalHoliday2ChargeAmount + $totalHolidayChargeAmountWithSuper + $totalSatChargeAmountWithSuper + $totalSunChargeAmountWithSuper),2);


            $avgNormalHrs = getAverageNormalHours($mysqli, $data['jobcode']);


            $grossWages = $overallTotal;

            $allTotalPayAmount = $allTotalPayAmount + $grossWages;
            $allTotalUnits = $allTotalUnits + $subTotalUnits;

            $subTotalForSuper = $emgTotal + $dayTotal + $aftTotal + $nightTotal + $rdoTotal + $satTotal + $satWithoutSuperTotal + $sunTotal + $sunWithoutSuperTotal + $dblTotal + $hldTotal + $hldWithoutSuperTotal;
            if($subTotalForSuper <= $avgNormalHrs){
                $totalSuperPayAmount = round(($totalEMGPayAmount + $totalDayPayAmount + $totalAftPayAmount + $totalNightPayAmount + $totalRDOPayAmount + $totalSatPayAmount + $totalSatPayAmountWithSuper + $totalSunPayAmount + $totalSunPayAmountWithSuper + $totalHolidayPayAmount + $totalHoliday2PayAmount + $totalHolidayPayAmountWithSuper), 2);
            }elseif($subTotalForSuper > $avgNormalHrs){
                $totalSuperPayAmount = round(($totalEMGPayAmount + $totalDayPayAmount + $totalAftPayAmount + $totalNightPayAmount + $totalRDOPayAmount + $totalSatPayAmount + $totalSatPayAmountWithSuper + $totalSunPayAmount + $totalSunPayAmountWithSuper + $totalHoliday2PayAmount + $totalHolidayPayAmountWithSuper), 2);
            }

            $allTotalChargeAmount = $allTotalChargeAmount + $totalChargeAmount;
            $allTotalEMGPayAmount = $allTotalEMGPayAmount + $totalEMGPayAmount;
            $allTotalEMGChargeAmount = $allTotalEMGChargeAmount + $totalEMGChargeAmount;
            $allTotalDayPayAmount = $allTotalDayPayAmount + $totalDayPayAmount;
            $allTotalDayChargeAmount = $allTotalDayChargeAmount + $totalDayChargeAmount;
            $allTotalAftPayAmount = $allTotalAftPayAmount + $totalAftPayAmount;
            $allTotalAftChargeAmount = $allTotalAftChargeAmount + $totalAftChargeAmount;
            $allTotalNightPayAmount = $allTotalNightPayAmount + $totalNightPayAmount;
            $allTotalNightChargeAmount = $allTotalNightChargeAmount + $totalNightChargeAmount;
            $allTotalRDOPayAmount = $allTotalRDOPayAmount + $totalRDOPayAmount;
            $allTotalRDOChargeAmount = $allTotalRDOChargeAmount + $totalRDOChargeAmount;
            $allTotalHolidayPayAmount = $allTotalHolidayPayAmount + $totalHolidayPayAmount;
            $allTotalHolidayChargeAmount = $allTotalHolidayChargeAmount + $totalHolidayChargeAmount;
            $allTotalHoliday2PayAmount = $allTotalHoliday2PayAmount + $totalHoliday2PayAmount;
            $allTotalHoliday2ChargeAmount = $allTotalHoliday2ChargeAmount + $totalHoliday2ChargeAmount;
            $allTotalHolidayPayAmountWithSuper = $allTotalHolidayPayAmountWithSuper + $totalHolidayPayAmountWithSuper;
            $allTotalHolidayChargeAmountWithSuper = $allTotalHolidayChargeAmountWithSuper + $totalHolidayChargeAmountWithSuper;
            $allTotalSatPayAmount = $allTotalSatPayAmount + $totalSatPayAmount;
            $allTotalSatChargeAmount = $allTotalSatChargeAmount + $totalSatChargeAmount;
            $allTotalSatPayAmountWithSuper = $allTotalSatPayAmountWithSuper + $totalSatPayAmountWithSuper;
            $allTotalSatChargeAmountWithSuper = $allTotalSatChargeAmountWithSuper + $totalSatChargeAmountWithSuper;
            $allTotalSunPayAmount = $allTotalSunPayAmount + $totalSunPayAmount;
            $allTotalSunChargeAmount = $allTotalSunChargeAmount + $totalSunChargeAmount;
            $allTotalSunPayAmountWithSuper = $allTotalSunPayAmountWithSuper + $totalSunPayAmountWithSuper;
            $allTotalSunChargeAmountWithSuper = $allTotalSunChargeAmountWithSuper + $totalSunChargeAmountWithSuper;
            $allTotalOvertimePayAmount = $allTotalOvertimePayAmount + $totalOvertimePayAmount;
            $allTotalOvertimeChargeAmount = $allTotalOvertimeChargeAmount + $totalOvertimeChargeAmount;
            $allTotalSatOvertimePayAmount = $allTotalSatOvertimePayAmount + $totalSatOvertimePayAmount;
            $allTotalSatOvertimeChargeAmount = $allTotalSatOvertimeChargeAmount + $totalSatOvertimeChargeAmount;
            $allTotalSunOvertimePayAmount = $allTotalSunOvertimePayAmount + $totalSunOvertimePayAmount;
            $allTotalSunOvertimeChargeAmount = $allTotalSunOvertimeChargeAmount + $totalSunOvertimeChargeAmount;
            $allTotalPeriodOvertimePayAmount = $allTotalPeriodOvertimePayAmount + $totalPeriodOvertimePayAmount;
            $allTotalPeriodOvertimeChargeAmount = $allTotalPeriodOvertimeChargeAmount + $totalPeriodOvertimeChargeAmount;
            $allTotalDoubletimePayAmount = $allTotalDoubletimePayAmount + $totalDoubletimePayAmount;
            $allTotalDoubletimeChargeAmount = $allTotalDoubletimeChargeAmount + $totalDoubletimeChargeAmount;

                /*if($allTotalUnits > $avgNormalHrs) {
                    $superPayAmount = 0;
                }
                if($allTotalUnits <= $avgNormalHrs) {*/
                    $superPayAmount = calculateSuperAnnuation($mysqli, $totalSuperPayAmount, $transCode);
                /*}*/
                if ($totalUnitsForSuper <= $avgNormalHrs) {
                    $counter++;
                }
                foreach ($transCodeArray as $key => $value) {
                    if ($key == $transCode) {
                        $transCodeArray[$key]['units'] = $value['units'] + 1;
                        $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superPayAmount;
                    }
                }
                /*foreach ($transCodeArray as $key => $value) {
                    if ($key == $transCode) {
                        $transCodeArray[$key]['payAmount'] = number_format($value['payAmount'] + $superPayAmount, 2);//($value['payAmount'] + $superAmount)
                    }
                }*/
           
            $allSuperTypeCount = $counter;
            $allTotalSuperPayAmount = $allTotalSuperPayAmount + $superPayAmount;
            $paygTax = getCalculatedWeeklyPAYG($mysqli, $candidateId, $totalPayAmount);
            $net = $totalPayAmount - $paygTax;
            /*if($client==$data['clientId']) {
                if ($totalUnitsForSuper <= $avgNormalHrs) {
                    $superPayAmount = calculateSuperAnnuation($mysqli, $totalSuperPayAmount, $transCode);
                    $counter++;
                    foreach ($transCodeArray as $key => $value) {
                        if ($key == $transCode) {
                            $transCodeArray[$key]['units'] = $value['units'] + 1;
                        }
                    }
                    foreach ($transCodeArray as $key => $value) {
                        if ($key == $transCode) {
                            $transCodeArray[$key]['payAmount'] = number_format($value['payAmount'] + $superPayAmount, 2);//($value['payAmount'] + $superAmount)
                        }
                    }

                }
                $allSuperTypeCount = $counter;
                $allTotalSuperPayAmount = $allTotalSuperPayAmount + $superPayAmount;
            }else{
                if ($totalUnitsForSuper <= $avgNormalHrs) {
                    $superPayAmount = calculateSuperAnnuation($mysqli, $totalSuperPayAmount, $transCode);
                    foreach ($transCodeArray as $key => $value) {
                        if ($key == $transCode) {
                            $transCodeArray[$key]['units'] = $value['units'] + 1;
                        }
                    }
                    foreach ($transCodeArray as $key => $value) {
                        if ($key == $transCode) {
                            $transCodeArray[$key]['payAmount'] = number_format($value['payAmount'] + $superPayAmount, 2);//($value['payAmount'] + $superAmount)
                        }
                    }

                }

                $allSuperTypeCount = $counter;
                $allTotalSuperPayAmount = $superPayAmount;
            }*/

            /*if ($totalUnitsForSuper <= $avgNormalHrs) {*/
            /*if($superPayAmount > 0) {*/
                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellWidth"></td><td class="cellWidth"></td><td class="empId"></td><td class="shortWidth">' . $transCode.'</td><td class="desc">' . $candidateSuperFundDesc . '</td><td class="shortWidth">' . number_format($superTypeCount, 2) . '</td><td class="cellWidth"></td><td class="cellWidth">' . number_format($superPayAmount, 2) . '</td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            /*}*/
            /*}*/

            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="cellWidth"></td><td class="cellWidth"></td><td class="empId"></td><td class="shortWidth"></td><td class="desc"><strong>Gross Wages</strong></td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth"><strong>' . number_format($grossWages, 2) . '</strong></td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth">' . number_format($overallChargeTotal, 2) . '</td></tr>';
            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="cellWidth"></td><td class="cellWidth"></td><td class="empId"></td><td class="shortWidth"></td><td class="desc"><strong>PAYG Tax</strong></td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth"><strong>' . number_format($paygTax, 2) . '</strong></td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="cellWidth"></td><td class="cellWidth"></td><td class="empId"></td><td class="shortWidth"></td><td class="desc"><strong>Net Wages</strong></td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth"><strong>' . number_format($net, 2) . '</strong></td><td class="shortWidth"></td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="cellWidth"></td><td class="cellWidth"></td><td class="empId"></td><td class="shortWidth"></td><td class="desc"><strong>Unit Total</strong></td><td class="shortWidth">' . number_format($subTotalUnits, 2) . '</td><td class="cellWidth"></td><td class="cellWidth"></td><td class="shortWidth">' . number_format($subTotalUnits, 2) . '</td><td class="cellWidth"></td><td class="cellWidth">' . number_format($overallChargeTotal,2) . '</td></tr>';

            $rowCount++;

            if ($client == $data['clientId']) {

                $allSubTotalUnits = $subTotalUnits;
                $allTotalUnits = $allTotalUnits;
                $allTotalPayAmount = $allTotalPayAmount;
                $allTotalChargeAmount = $allTotalChargeAmount;
                $allSuperTypeCount = $allSuperTypeCount;
                $allTotalSuperPayAmount = $allTotalSuperPayAmount;

                $allTotalEMGUnits = $totalEMGUnits;
                $allTotalEMGPayAmount = $allTotalEMGPayAmount;
                $allTotalEMGChargeAmount = $allTotalEMGChargeAmount;
                $allTotalEMGSuperAmount = $totalEMGSuperAmount;

                $allTotalDayUnits = $totalDayUnits;
                $allTotalDayPayAmount = $allTotalDayPayAmount;
                $allTotalDayChargeAmount = $allTotalDayChargeAmount;
                $allTotalDaySuperAmount = $totalDaySuperAmount;

                $allTotalAftUnits = $totalAftUnits;
                $allTotalAftPayAmount = $allTotalAftPayAmount;
                $allTotalAftChargeAmount = $allTotalAftChargeAmount;
                $allTotalAftSuperAmount = $totalAftSuperAmount;

                $allTotalNightUnits = $totalNightUnits;
                $allTotalNightPayAmount = $allTotalNightPayAmount;
                $allTotalNightChargeAmount = $allTotalNightChargeAmount;
                $allTotalNightSuperAmount = $totalNightSuperAmount;

                $allTotalRDOUnits = $totalRDOUnits;
                $allTotalRDOPayAmount = $allTotalRDOPayAmount;
                $allTotalRDOChargeAmount = $allTotalRDOChargeAmount;
                $allTotalRDOSuperAmount = $totalRDOSuperAmount;

                $allTotalSatUnits = $totalSatUnits;
                $allTotalSatPayAmount = $allTotalSatPayAmount;
                $allTotalSatChargeAmount = $allTotalSatChargeAmount;
                $allTotalSatSuperAmount = $totalSatSuperAmount;

                $allTotalSatUnitsWithSuper = $totalSatUnitsWithSuper;
                $allTotalSatPayAmountWithSuper = $allTotalSatPayAmountWithSuper;
                $allTotalSatChargeAmountWithSuper = $allTotalSatChargeAmountWithSuper;
                $allTotalSatSuperAmountWithSuper = $totalSatSuperAmountWithSuper;

                $allTotalSunUnits = $totalSunUnits;
                $allTotalSunPayAmount = $allTotalSunPayAmount;
                $allTotalSunChargeAmount = $allTotalSunChargeAmount;
                $allTotalSunSuperAmount = $totalSunSuperAmount;

                $allTotalSunUnitsWithSuper = $totalSunUnitsWithSuper;
                $allTotalSunPayAmountWithSuper = $allTotalSunPayAmountWithSuper;
                $allTotalSunChargeAmountWithSuper = $allTotalSunChargeAmountWithSuper;
                $allTotalSunSuperAmountWithSuper = $totalSunSuperAmountWithSuper;

                $allTotalOvertimeUnits = $totalOvertimeUnits;
                $allTotalOvertimePayAmount = $allTotalOvertimePayAmount;
                $allTotalOvertimeChargeAmount = $allTotalOvertimeChargeAmount;
                $allTotalOvertimeSuperAmount = $totalOvertimeSuperAmount;

                $allTotalSatOvertimeUnits = $totalSatOvertimeUnits;
                $allTotalSatOvertimePayAmount = $allTotalSatOvertimePayAmount;
                $allTotalSatOvertimeChargeAmount = $allTotalSatOvertimeChargeAmount;
                $allTotalSatOvertimeSuperAmount = $totalSatOvertimeSuperAmount;

                $allTotalSunOvertimeUnits = $totalSunOvertimeUnits;
                $allTotalSunOvertimePayAmount = $allTotalSunOvertimePayAmount;
                $allTotalSunOvertimeChargeAmount = $allTotalSunOvertimeChargeAmount;
                $allTotalSunOvertimeSuperAmount = $totalSunOvertimeSuperAmount;

                $allTotalPeriodOvertimeUnits = $totalPeriodOvertimeUnits;
                $allTotalPeriodOvertimePayAmount = $allTotalPeriodOvertimePayAmount;
                $allTotalPeriodOvertimeChargeAmount = $allTotalPeriodOvertimeChargeAmount;
                $allTotalPeriodOvertimeSuperAmount = $totalPeriodOvertimeSuperAmount;

                $allTotalDoubletimeUnits = $totalDoubletimeUnits;
                $allTotalDoubletimePayAmount = $allTotalDoubletimePayAmount;
                $allTotalDoubletimeChargeAmount = $allTotalDoubletimeChargeAmount;
                $allTotalDoubletimeSuperAmount = $totalDoubletimeSuperAmount;

                $allTotalHolidayUnits = $totalHolidayUnits;
                $allTotalHolidayPayAmount = $allTotalHolidayPayAmount;
                $allTotalHolidayChargeAmount = $allTotalHolidayChargeAmount;
                $allTotalHolidaySuperAmount = $totalHolidaySuperAmount;

                $allTotalHoliday2Units = $totalHoliday2Units;
                $allTotalHoliday2PayAmount = $allTotalHoliday2PayAmount;
                $allTotalHoliday2ChargeAmount = $allTotalHoliday2ChargeAmount;
                $allTotalHoliday2SuperAmount = $totalHoliday2SuperAmount;

                $allTotalHolidayUnitsWithSuper = $totalHolidayUnitsWithSuper;
                $allTotalHolidayPayAmountWithSuper = $allTotalHolidayPayAmountWithSuper;
                $allTotalHolidayChargeAmountWithSuper = $allTotalHolidayChargeAmountWithSuper;
                $allTotalHolidaySuperAmountWithSuper = $totalHolidaySuperAmountWithSuper;

                /*$allTotalHolidayOvertimeUnits = $totalHolidayOvertimeUnits;
                $allTotalHolidayOvertimePayAmount = $allTotalHolidayOvertimePayAmount;
                $allTotalHolidayOvertimeChargeAmount = $allTotalHolidayOvertimeChargeAmount;
                $allTotalHolidayOvertimeSuperAmount = $totalHolidayOvertimeSuperAmount;*/

                $allGrossPerClient = $overallTotal;
                /*$paygTax = round(getCalculatedWeeklyPAYG($mysqli, $candidateId, $totalPayAmount));
                $net = $totalPayAmount - $paygTax;*/
                foreach ($clientArray as $key => $value) {
                    if ($key == $client) {
                        $clientArray[$key]['payUnits'] = (float)$value['payUnits'] + $subTotalUnits;
                        $clientArray[$key]['payAmount'] = (float)$value['payAmount'] + $totalPayAmount;
                        $clientArray[$key]['billUnits'] = (float)$value['billUnits'] + $subTotalUnits;
                        $clientArray[$key]['billAmount'] = (float)$value['billAmount'] + $totalChargeAmount;
                        $clientArray[$key]['superTypeCount'] = (float)$value['superTypeCount'] + $allSuperTypeCount;
                        $clientArray[$key]['superPayAmount'] = (float)$value['superPayAmount'] + $superPayAmount;
                        $clientArray[$key]['tax'] = (float)$value['tax'] + $paygTax;
                        $clientArray[$key]['net'] = (float)$value['net'] + $net;
                        $clientArray[$key]['gross'] = (float)$value['gross'] + $allGrossPerClient;
                    }
                }
            }else{
                $allTotalChargeAmount = $totalChargeAmount;
                $allTotalEMGPayAmount = $totalEMGPayAmount;
                $allTotalEMGChargeAmount = $totalEMGChargeAmount;
                $allTotalDayPayAmount = $totalDayPayAmount;
                $allTotalDayChargeAmount = $totalDayChargeAmount;
                $allTotalAftPayAmount = $totalAftPayAmount;
                $allTotalAftChargeAmount = $totalAftChargeAmount;
                $allTotalNightPayAmount = $totalNightPayAmount;
                $allTotalNightChargeAmount = $totalNightChargeAmount;
                $allTotalRDOPayAmount = $totalRDOPayAmount;
                $allTotalRDOChargeAmount = $totalRDOChargeAmount;
                $allTotalHolidayPayAmount = $totalHolidayPayAmount;
                $allTotalHolidayChargeAmount = $totalHolidayChargeAmount;
                $allTotalHoliday2PayAmount = $totalHoliday2PayAmount;
                $allTotalHoliday2ChargeAmount = $totalHoliday2ChargeAmount;
                $allTotalSatPayAmount = $totalSatPayAmount;
                $allTotalSatChargeAmount = $totalSatChargeAmount;
                $allTotalSatPayAmountWithSuper = $totalSatPayAmountWithSuper;
                $allTotalSatChargeAmountWithSuper = $totalSatChargeAmountWithSuper;
                $allTotalSunPayAmount = $totalSunPayAmount;
                $allTotalSunChargeAmount = $totalSunChargeAmount;
                $allTotalSunPayAmountWithSuper = $totalSunPayAmountWithSuper;
                $allTotalSunChargeAmountWithSuper = $totalSunChargeAmountWithSuper;
                $allTotalOvertimePayAmount = $totalOvertimePayAmount;
                $allTotalOvertimeChargeAmount = $totalOvertimeChargeAmount;
                $allTotalSatOvertimePayAmount = $totalSatOvertimePayAmount;
                $allTotalSatOvertimeChargeAmount = $totalSatOvertimeChargeAmount;
                $allTotalSunOvertimePayAmount = $totalSunOvertimePayAmount;
                $allTotalSunOvertimeChargeAmount = $totalSunOvertimeChargeAmount;
                $allTotalPeriodOvertimePayAmount = $totalPeriodOvertimePayAmount;
                $allTotalPeriodOvertimeChargeAmount = $totalPeriodOvertimeChargeAmount;
                $allTotalDoubletimePayAmount = $totalDoubletimePayAmount;
                $allTotalDoubletimeChargeAmount = $totalDoubletimeChargeAmount;
            }
        }
        $rowCount++;
        $html = $html . '<tr class="totalRow zebra' . ($i++ & 1) . '">';
        $html = $html . '<td class="cellWidth"></td><td class="cellWidth"></td><td class="empId"></td><td class="shortWidth"></td><td class="desc"></td><td class="shortWidth" style="border-top:2px solid #000;">' . number_format($allTotalUnits, 2) . '</td><td class="cellWidth"></td><td class="cellWidth" style="border-top:2px solid #000;">' . number_format($allTotalPayAmount, 2) . '</td><td class="shortWidth" style="border-top:2px solid #000;">' . number_format($allTotalUnits, 2) . '</td><td class="cellWidth"></td><td class="cellWidth" style="border-top:2px solid #000;">' . number_format($allTotalChargeAmount, 2) . '</td>';
        $html = $html . '</tr>';


    }
    $fileName = 'timesheetAuditReport_' . time();
    $filePathPDF = './auditreport/' . $fileName . '.pdf';
    $filePathXLSX = './auditreport/' . $fileName . '.xlsx';
    $html = $html . '</tbody></table><span class="filePath" data-filePathPDF="' . $filePathPDF . '" data-filePathXLSX="' . $filePathXLSX . '">&nbsp;</span><br pagebreak="true"/>';
// output the HTML content
    $pdf->writeHTML($html, true, false, false, false, '');
    $lastPage = $pdf->getPage();
    $pdf->deletePage($lastPage);
    define('PDF_CUSTOM_HEADER_STRING1', 'Week Ending Worked:' . $weekendingDate . '   User:' . $_SESSION['userSession'] . '   Printed: ' . date("Y-m-d H:i:s"));
// set default header data
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING1);
    $pdf->AddPage('P', 'A4');
    foreach ($clientArray as $key => $value) {
        removeClientSummary($mysqli,$key,$weekendingDate);
    }
    $clientSum = $clientSum . '<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
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
td.tscode{
    text-align: right;
    width: 10%;
}
td.sumDesc{
    text-align: left;
    width: 50%;
}
td.sumUnit{
    text-align: right;
    width: 8%;
}
td.sumAmount{
    text-align: right;
    width: 12%;
}</style>';
    $clientSum = $clientSum . '<div class="pageTitle">Client Summary</div>';
    $clientSum = $clientSum . '<table class="sumTable" border="1"><thead><tr>';
    $clientSum = $clientSum . '<th style="width: 10%;text-align: center;text-transform: uppercase;">Code</th><th style="width: 50%;text-align: center;text-transform: uppercase;">Description</th><th style="width: 8%;text-align: center;text-transform: uppercase;">Pay Units</th><th style="width: 12%;text-align: center;text-transform: uppercase;">Pay Amount</th><th style="width: 8%;text-align: center;text-transform: uppercase;">Bill Units</th><th style="width: 12%;text-align: center;text-transform: uppercase;">Bill Amount</th>';
    $clientSum = $clientSum . '</tr></thead><tbody>';

    $rowCnt = 1;
    $comma=',';
    $k = 0;
    $len=sizeof($clientArray);
    $clientString ='';
    foreach ($clientArray as $key => $value) {
        $clientSum = $clientSum . '<tr><td colspan="6" class="title">' . getClientNameByClientId($mysqli, $key) . '</td></tr>';
        $clientSum = $clientSum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">Hourly</td><td class="sumUnit">' . number_format(floatval($clientArray[$key]['payUnits']), 2) . '</td><td class="sumAmount">' . number_format(floatval($clientArray[$key]['payAmount']), 2) . '</td><td class="sumUnit">' . number_format(floatval($clientArray[$key]['billUnits']), 2) . '</td><td class="sumAmount">' . number_format(floatval($clientArray[$key]['billAmount']),2) . '</td></tr>';
        $clientSum = $clientSum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">Tax</td><td class="sumUnit">0.00</td><td class="sumAmount">' . number_format(floatval($clientArray[$key]['tax']), 2) . '</td><td class="sumUnit"></td><td class="sumAmount"></td></tr>';
        $clientSum = $clientSum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">Net</td><td class="sumUnit">0.00</td><td class="sumAmount">' . number_format(floatval($clientArray[$key]['net']), 2) . '</td><td class="sumUnit"></td><td class="sumAmount"></td></tr>';
        $clientSum = $clientSum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">Gross</td><td class="sumUnit"></td><td class="sumAmount">' . number_format(floatval($clientArray[$key]['gross']), 2) . '</td><td class="sumUnit"></td><td class="sumAmount"></td></tr>';

        $clientSum = $clientSum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SuperAnnuation</td><td class="sumUnit">' . number_format(floatval($clientArray[$key]['superTypeCount']), 2) . '</td><td class="sumAmount">' . number_format(floatval($clientArray[$key]['superPayAmount']), 2) . '</td><td class="sumUnit"></td><td class="sumAmount"></td></tr>';
        $totalNetAmount = $totalNetAmount + $clientArray[$key]['net'];
        $totalTaxAmount = $totalTaxAmount + $clientArray[$key]['tax'];
        $insertId = saveClientSummary($mysqli,$key,$clientArray[$key]['payUnits'],$clientArray[$key]['payAmount'],$clientArray[$key]['billUnits'],$clientArray[$key]['billAmount'],$clientArray[$key]['tax'],$clientArray[$key]['net'],$clientArray[$key]['gross'],$clientArray[$key]['superTypeCount'],$clientArray[$key]['superPayAmount'],$weekendingDate);
        $clientString = $clientString.$insertId;
        if ($k == $len - 1) {
            $comma = '';
            $clientString = $clientString.$comma;
        }
        $k++;
        $clientString = $clientString.$comma;

        $rowCnt++;
    }
//$clientSum = $clientSum.'<tr class="zebra'.($i++ & 1 ).'"><td class="tscode"></td><td class="sumDesc"></td><td class="sumUnit" style="border-bottom:double; border-top: solid;">'.number_format($allTotalUnits,2).'</td><td class="sumAmount" style="border-bottom:double; border-top: solid;">'.number_format($allTotalPayAmount,2).'</td><td class="sumUnit" style="border-bottom:double; border-top: solid;">'.number_format($allTotalUnits,2).'</td><td class="sumAmount" style="border-bottom:double; border-top: solid;">'.number_format($allTotalChargeAmount,2).'</td></tr>';
    $clientSum = $clientSum . '</tbody></table>';

    $pdf->writeHTML($clientSum, true, false, false, false, '');

    $pdf->AddPage('P', 'A4');

    $rowCount = 1;

    $sum = $sum . '<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
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
td.tscode{
    text-align: right;
    width: 10%;
}
td.sumDesc{
    text-align: left;
    width: 50%;
}
td.sumUnit{
    text-align: right;
    width: 8%;
}
td.sumAmount{
    text-align: right;
    width: 12%;
}</style>';
    $sum = $sum . '<div class="pageTitle">Transaction Code Summary</div>';
    $sum = $sum . '<table class="sumTable" border="1"><thead><tr>';
    $sum = $sum . '<th style="width: 10%;text-align: center;text-transform: uppercase;">Code</th><th style="width: 50%;text-align: center;text-transform: uppercase;">Description</th><th style="width: 8%;text-align: center;text-transform: uppercase;">Pay Units</th><th style="width: 12%;text-align: center;text-transform: uppercase;">Pay Amount</th><th style="width: 8%;text-align: center;text-transform: uppercase;">Bill Units</th><th style="width: 12%;text-align: center;text-transform: uppercase;">Bill Amount</th>';
    $sum = $sum . '</tr></thead><tbody>';
    $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc"><strong>Net & Tax Totals</strong></td><td class="sumUnit"><strong>' . number_format($totalNetAmount, 2) . '</strong></td><td class="sumAmount"><strong>' . number_format($totalTaxAmount, 2) . '</strong></td><td class="sumUnit"></td><td class="sumAmount"></td></tr>';

    if ($allTotalDayUnits <> '' || $allTotalDayUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">DAY</td><td class="sumUnit">' . number_format($allTotalDayUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalDayPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalDayUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalDayChargeAmount, 2) . '</td></tr>';
    }
    if ($allTotalEMGUnits <> '' || $allTotalEMGUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">EARLY MORNING</td><td class="sumUnit">' . number_format($allTotalEMGUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalEMGPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalEMGUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalEMGChargeAmount, 2) . '</td></tr>';
    }
    if ($allTotalAftUnits <> '' || $allTotalAftUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">AFTERNOON</td><td class="sumUnit">' . number_format($allTotalAftUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalAftPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalAftUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalAftChargeAmount, 2) . '</td></tr>';
    }
    if ($allTotalNightUnits <> '' || $allTotalNightUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">NIGHT</td><td class="sumUnit">' . number_format($allTotalNightUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalNightPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalNightUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalNightChargeAmount, 2) . '</td></tr>';
    }
    if ($allTotalRDOUnits <> '' || $allTotalRDOUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">RDO</td><td class="sumUnit">' . number_format($allTotalRDOUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalRDOPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalRDOUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalRDOChargeAmount, 2) . '</td></tr>';
    }
    if ($allTotalOvertimeUnits <> '' || $allTotalOvertimeUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">OVERTIME</td><td class="sumUnit">' . number_format($allTotalOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalOvertimePayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalOvertimeChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalSatOvertimeUnits <> '' || $allTotalSatOvertimeUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SATURDAY OVERTIME</td><td class="sumUnit">' . number_format($allTotalSatOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSatOvertimePayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalSatOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSatOvertimeChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalSunOvertimeUnits <> '' || $allTotalSunOvertimeUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SUNDAY OVERTIME</td><td class="sumUnit">' . number_format($allTotalSunOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSunOvertimePayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalSunOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSunOvertimeChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalPeriodOvertimeUnits <> '' || $allTotalPeriodOvertimeUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">PERIOD OVERTIME</td><td class="sumUnit">' . number_format($allTotalPeriodOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalPeriodOvertimePayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalPeriodOvertimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalPeriodOvertimeChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalDoubletimeUnits <> '' || $allTotalDoubletimeUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">DOUBLETIME</td><td class="sumUnit">' . number_format($allTotalDoubletimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalDoubletimePayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalDoubletimeUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalDoubletimeChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalSatUnits <> '' || $allTotalSatUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SATURDAY</td><td class="sumUnit">' . number_format($allTotalSatUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSatPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalSatUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSatChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalSatUnitsWithSuper <> '' || $allTotalSatUnitsWithSuper <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SATURDAY WITH SUPER</td><td class="sumUnit">' . number_format($allTotalSatUnitsWithSuper, 2) . '</td><td class="sumAmount">' . number_format($allTotalSatPayAmountWithSuper, 2) . '</td><td class="sumUnit">' . number_format($allTotalSatUnitsWithSuper, 2) . '</td><td class="sumAmount">' . number_format($allTotalSatChargeAmountWithSuper, 2) . '</td></tr>';

    }
    if ($allTotalSunUnits <> '' || $allTotalSunUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SUNDAY</td><td class="sumUnit">' . number_format($allTotalSunUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSunPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalSunUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalSunChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalSunUnitsWithSuper <> '' || $allTotalSunUnitsWithSuper <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">SUNDAY WITH SUPER</td><td class="sumUnit">' . number_format($allTotalSunUnitsWithSuper, 2) . '</td><td class="sumAmount">' . number_format($allTotalSunPayAmountWithSuper, 2) . '</td><td class="sumUnit">' . number_format($allTotalSunUnitsWithSuper, 2) . '</td><td class="sumAmount">' . number_format($allTotalSunChargeAmountWithSuper, 2) . '</td></tr>';

    }
    if ($allTotalHolidayUnits <> '' || $allTotalHolidayUnits <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">PUBLIC HOLIDAY</td><td class="sumUnit">' . number_format($allTotalHolidayUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalHolidayPayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalHolidayUnits, 2) . '</td><td class="sumAmount">' . number_format($allTotalHolidayChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalHoliday2Units <> '' || $allTotalHoliday2Units <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">PUBLIC HOLIDAY 2</td><td class="sumUnit">' . number_format($allTotalHoliday2Units, 2) . '</td><td class="sumAmount">' . number_format($allTotalHoliday2PayAmount, 2) . '</td><td class="sumUnit">' . number_format($allTotalHoliday2Units, 2) . '</td><td class="sumAmount">' . number_format($allTotalHoliday2ChargeAmount, 2) . '</td></tr>';

    }
    if ($allTotalHolidayUnitsWithSuper <> '' || $allTotalHolidayUnitsWithSuper <> 0) {
        $rowCount++;
        $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc">HOLIDAY WITH SUPER</td><td class="sumUnit">' . number_format($allTotalHolidayUnitsWithSuper, 2) . '</td><td class="sumAmount">' . number_format($allTotalHolidayPayAmountWithSuper, 2) . '</td><td class="sumUnit">' . number_format($allTotalHolidayUnitsWithSuper, 2) . '</td><td class="sumAmount">' . number_format($allTotalHolidayChargeAmountWithSuper, 2) . '</td></tr>';

    }

    foreach ($transCodeArray as $pKey => $pValue) {
        if($transCodeArray[$pKey]['units']>0) {
            $rowCount++;
            $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode">' . $pKey . '</td><td class="sumDesc">' . getTransCodeDescByTransCode($mysqli, $pKey) . '</td><td class="sumUnit">' . number_format($transCodeArray[$pKey]['units'], 2) . '</td><td class="sumAmount">' . number_format($transCodeArray[$pKey]['payAmount'], 2) . '</td><td class="sumUnit"></td><td class="sumAmount"></td></tr>';
        }
    }

    $sum = $sum . '<tr class="zebra' . ($i++ & 1) . '"><td class="tscode"></td><td class="sumDesc"></td><td class="sumUnit" style="border-bottom:double; border-top: solid;">' . number_format($allTotalUnits, 2) . '</td><td class="sumAmount" style="border-bottom:double; border-top: solid;">' . number_format($allTotalPayAmount, 2) . '</td><td class="sumUnit" style="border-bottom:double; border-top: solid;">' . number_format($allTotalUnits, 2) . '</td><td class="sumAmount" style="border-bottom:double; border-top: solid;">' . number_format($allTotalChargeAmount, 2) . '</td></tr>';
    $sum = $sum . '</tbody></table>';
    $pdf->writeHTML($sum, true, false, false, false, '');
    // reset pointer to the last page
    $pdf->lastPage();
    // Close and output PDF document
    $reportPath = './auditreport/' . $fileName . '.pdf';
    $pdf->Output(__DIR__ . '/auditreport/' . $fileName . '.pdf', 'F');
    echo $html . $clientSum . $sum.'<input type="hidden" name="clientArray" id="clientArray" value="'.$clientString.'"><input type="hidden" name="reportPath" id="reportPath" value="'.$reportPath.'">';
}else{
    echo 'No records to display';
}
?>