<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', '800M');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$weekendingDate = $_POST['weekEndingDate'];
$empId = $_POST['candidateId'];
$jbCode = $_POST['jobcode'];
$profitCentre = $_POST['profitCentre'];
$clientCode = $_POST['clientCode'];
$timeSheetData = getTimeSheetTotalsForPayroll($mysqli,$weekendingDate,$empId,$jbCode,$profitCentre,$clientCode);
$payrollName = getPayrollNameById($mysqli,$_POST['payrollName']);
$action = $_POST['action'];
if($action == 'GENERATE') {
    $payRunId = genPayrunId($mysqli, $payrollName, $weekendingDate);
}else{
    $payRunId = 0;
}
$canId;
$canIdSuper;
$candidateId;
$candidateHours=0;
$totalDayUnits=0;
$totalEMGUnits=0;
$totalAftUnits=0;
$totalNightUnits=0;
$totalRDOUnits=0;
$totalSatUnits=0;
$totalSunUnits=0;
$totalOvtUnits=0;
$totalSatOvtUnits=0;
$totalSunOvtUnits=0;
$totalPeriodOvtUnits=0;
$totalDblUnits=0;
$totalHolUnits=0;
$totalHol2Units=0;
$totalNetWages=0;
$totalAfterTaxTotal = 0;
$totalAfterDeductionTotal = 0;
$totalAfterAllowanceTaxTotal = 0;
$finalUnits=0;
$finalAmount=0;
$finalNetAmount=0;
$candidateArray = array();
$allUnitsPayArray = createUnitPayArray($mysqli);
$dataArray = array();
foreach($timeSheetData as $keys){
    $dataArray[] = $keys['candidateId'];
}
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderTemplateAutoreset(true);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor(' ');
$pdf->SetTitle('Payroll Calculation Report');
$pdf->SetSubject('Payroll Calculation Report');
$pdf->SetKeywords('Payroll Calculation Report');
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
define('PDF_CUSTOM_HEADER_STRING','Payrun No :'.$payRunId.'    Week Ending Worked:'.$weekendingDate.'    User:'.$_SESSION['userSession'].'    Printed: '.date("Y-m-d H:i:s"));
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('helvetica', '', 8);
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
}
thead{
    background-color: #9EA8B1;
}
th{
    text-align: center;
    font-size: 8pt;
}
td.cellWidth{
    text-align: right;
    width: 10%;
}
td.boldFigure{
    text-align: right;
    font-weight: bold;
}
td.cellCenter{
    text-align: center;
    width: 10%;
}
td.shortWidth{
    text-align: right;
}
td.empId{
    text-align: left;
}
td.desc{
    text-align: left;
    width: 30%;
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
}</style>';
$html = $html.'<div class="pageTitle">Payroll Calculation Report</div><table border="1"><thead>
    <tr>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">JobCode</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Transaction Code</th>
        <th style="text-align: center;width: 30%;text-transform: uppercase;">Description</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Units</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Rate</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Taxable</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Non-Taxable</th>
        <th style="text-align: center;width: 10%;text-transform: uppercase;">Accruals</th>
    </tr>
</thead>
<tbody>';
foreach($timeSheetData as $emp){
    $candidateArray[$emp['candidateId']]['clientId'] = '';
    $candidateArray[$emp['candidateId']]['positionId'] = '';
    $candidateArray[$emp['candidateId']]['ordTotal'] = '';
    $candidateArray[$emp['candidateId']]['emgTotal'] = '';
    $candidateArray[$emp['candidateId']]['aftTotal'] = '';
    $candidateArray[$emp['candidateId']]['nightTotal'] = '';
    $candidateArray[$emp['candidateId']]['rdoTotal'] = '';
    $candidateArray[$emp['candidateId']]['satTotal'] = '';
    $candidateArray[$emp['candidateId']]['sunTotal'] = '';
    $candidateArray[$emp['candidateId']]['ovtTotal'] = '';
    $candidateArray[$emp['candidateId']]['satovtTotal'] = '';
    $candidateArray[$emp['candidateId']]['sunovtTotal'] = '';
    $candidateArray[$emp['candidateId']]['povtTotal'] = '';
    $candidateArray[$emp['candidateId']]['dblTotal'] = '';
    $candidateArray[$emp['candidateId']]['hldTotal'] = '';
    $candidateArray[$emp['candidateId']]['hol_total'] = '';
    $candidateArray[$emp['candidateId']]['totalHours'] = '';
    $candidateArray[$emp['candidateId']]['superFundCode'] = '';
    $candidateArray[$emp['candidateId']]['superFundDesc'] = '';
    $candidateArray[$emp['candidateId']]['jobcode'] = '';
    $candidateArray[$emp['candidateId']]['totalGross'] = '';
    $candidateArray[$emp['candidateId']]['totalTax'] = '';
    $candidateArray[$emp['candidateId']]['net'] = '';
    $candidateArray[$emp['candidateId']]['deductAmount'] = '';
}
$count = 0;
$check;
$k = 0;
$len = sizeof($timeSheetData);
$candidateGross = 0;
$candidateTax = 0;
$candidateCount = 0;
$jcode;
foreach($timeSheetData as $data){
    $counter = 0;
    foreach ($timeSheetData as $key=>$value) {
        if ($value ['candidateId'] == $data['candidateId']) {
            $counter++;
        }
    }
    $currentCandidateCount = $counter;
    $emgTotal = 0;
    $dayTotal = 0;
    $aftTotal = 0;
    $nightTotal = 0;
    $rdoTotal = 0;
    $satTotal = 0;
    $sunTotal = 0;
    $ovtTotal = 0;
    $dblTotal = 0;
    $hldTotal = 0;
    $hld2Total = 0;
    $povtTotal = 0;
    $satWithoutSuperTotal = 0;
    $satWithSuperTotal = 0;
    $emgPayCatCode = getPayCatCode($mysqli,'EARLY MORNING');
    $dayPayCatCode = getPayCatCode($mysqli,'ORDINARY');
    $aftPayCatCode = getPayCatCode($mysqli,'AFTERNOON');
    $nightPayCatCode = getPayCatCode($mysqli,'NIGHT');
    $rdoPayCatCode = getPayCatCode($mysqli,'RDO');
    $satPayCatCode = getPayCatCode($mysqli,'SATURDAY');
    $satSuperPayCatCode = getPayCatCode($mysqli,'SATURDAY WITH SUPER');
    $sunPayCatCode = getPayCatCode($mysqli,'SUNDAY');
    $sunSuperPayCatCode = getPayCatCode($mysqli,'SUNDAY WITH SUPER');
    $ovtPayCatCode = getPayCatCode($mysqli,'OVERTIME');
    $satovtPayCatCode = getPayCatCode($mysqli,'SATURDAY OVERTIME');
    $sunovtPayCatCode = getPayCatCode($mysqli,'SUNDAY OVERTIME');
    $povtPayCatCode = getPayCatCode($mysqli,'PERIOD OVERTIME');
    $dblPayCatCode = getPayCatCode($mysqli,'DOUBLETIME');
    $holPayCatCode = getPayCatCode($mysqli,'PUBLIC HOLIDAY');
    $holPay2CatCode = getPayCatCode($mysqli,'PUBLIC HOLIDAY 2');
    $hldSuperPayCatCode = getPayCatCode($mysqli, 'HOLIDAY WITH SUPER');

    $check = $data['candidateId'];
    $jobCode = getJobCodeByClientPosition($mysqli,$data['clientId'],$data['positionId'],$data['deptId']);
    $jcode = $jobCode;
    if(empty($candidateId)){
        $candidateId = $data['candidateId'];
    }
    if($candidateId == $data['candidateId']){
        if($jcode <> $jobCode) {
            $totalEMGUnits = $totalEMGUnits + $data['emgTotal'];
            $totalDayUnits = $totalDayUnits + $data['ordTotal'];
            $totalAftUnits = $totalAftUnits + $data['aftTotal'];
            $totalNightUnits = $totalNightUnits + $data['nightTotal'];
            $totalRDOUnits = $totalRDOUnits + $data['rdoTotal'];
            $totalSatUnits = $totalSatUnits + $data['satTotal'];
            $totalSunUnits = $totalSunUnits + $data['sunTotal'];
            $totalOvtUnits = $totalOvtUnits + $data['ovtTotal'];
            $totalSatOvtUnits = $totalSatOvtUnits + $data['satovtTotal'];
            $totalSunOvtUnits = $totalSunOvtUnits + $data['sunovtTotal'];
            $totalPeriodOvtUnits = $totalPeriodOvtUnits + $data['povtTotal'];
            $totalDblUnits = $totalDblUnits + $data['dblTotal'];
            $totalHolUnits = $totalHolUnits + $data['hldTotal'];
            $totalHol2Units = $totalHol2Units + $data['hol_total'];
        }else{
            $totalEMGUnits = $data['emgTotal'];
            $totalDayUnits = $data['ordTotal'];
            $totalAftUnits = $data['aftTotal'];
            $totalNightUnits = $data['nightTotal'];
            $totalRDOUnits = $data['rdoTotal'];
            $totalSatUnits = $data['satTotal'];
            $totalSunUnits = $data['sunTotal'];
            $totalOvtUnits = $data['ovtTotal'];
            $totalSatOvtUnits = $data['satovtTotal'];
            $totalSunOvtUnits = $data['sunovtTotal'];
            $totalPeriodOvtUnits = $data['povtTotal'];
            $totalDblUnits = $data['dblTotal'];
            $totalHolUnits = $data['hldTotal'];
            $totalHol2Units = $data['hol_total'];
        }
    }
    elseif($candidateId <> $data['candidateId']){
        $candidateCount = 0;
        $candidateId = $data['candidateId'];
        $totalEMGUnits = 0;
        $totalDayUnits = 0;
        $totalAftUnits = 0;
        $totalNightUnits = 0;
        $totalRDOUnits = 0;
        $totalSatUnits = 0;
        $totalSatUnitsWithSuper = 0;
        $totalSunUnits = 0;
        $totalSunUnitsWithSuper = 0;
        $totalOvtUnits = 0;
        $totalSatOvtUnits = 0;
        $totalSunOvtUnits= 0;
        $totalPeriodOvtUnits = 0;
        $totalDblUnits = 0;
        $totalHolUnits = 0;
        $totalHol2Units = 0;
        $totalHolUnitsWithSuper = 0;
        $emgPayTotal = 0;
        $dayPayTotal = 0;
        $aftPayTotal = 0;
        $nightPayTotal = 0;
        $rdoPayTotal = 0;
        $satPayTotal = 0;
        $sunPayTotal = 0;
        $ovtPayTotal = 0;
        $satovtPayTotal = 0;
        $sunovtPayTotal = 0;
        $povtPayTotal = 0;
        $dblPayTotal = 0;
        $holPayTotal = 0;
        $hld2PayTotal = 0;
        /*$satPayAmount=0;*/
        $satPayAmountWithSuper = 0;
        $sunPayAmountWithSuper = 0;
        //$hldPayAmountWithSuper = 0;
        $satPayTotalWithSuper = 0;
        $sunPayTotalWithSuper = 0;
        $holPayTotalWithSuper = 0;

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

        $totalEMGUnits = $data['emgTotal'];
        $totalDayUnits = $data['ordTotal'];
        $totalAftUnits = $data['aftTotal'];
        $totalNightUnits = $data['nightTotal'];
        $totalRDOUnits = $data['rdoTotal'];
        $totalSatUnits = $data['satTotal'];
        $totalSunUnits = $data['sunTotal'];
        $totalOvtUnits = $data['ovtTotal'];
        $totalSatOvtUnits = $data['satovtTotal'];
        $totalSunOvtUnits = $data['sunovtTotal'];
        $totalPeriodOvtUnits = $data['povtTotal'];
        $totalDblUnits = $data['dblTotal'];
        $totalHolUnits = $data['hldTotal'];
        $totalHol2Units = $data['hol_total'];
    }

    if($canId <> $data['candidateId']) {
        $canId = $data['candidateId'];
        $candidateGross = 0;
        $superAnnuation = 0;
        $html = $html .'<tr class="rowHeader"><td colspan="8">'. getCandidateLastNameByCandidateId($mysqli, $data['candidateId']) . ' ' . getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $data['candidateId'] . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . getCandidateDOBById($mysqli, $data['candidateId']) . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . getCandidateTFN($mysqli, $data['candidateId']) . '</td></tr>';
    }
    if(($data['ordTotal']) > 0){
        $dayTotal = number_format($data['ordTotal'],2);
        $dayRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$dayPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($data['ordTotal'],$dayRate);
        $dayAmount = $payTotal;
        $dayChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$dayPayCatCode,$jobCode);
        $dayChargeTotal = calculateChargeAmount($data['ordTotal'],$dayChargeRate);
        $dayChargeAmount = $dayChargeTotal;
        $dayPayAmount = round(($dayPayAmount + $payTotal),2);
        $dayPayTotal = round(($dayPayTotal + $payTotal),2);

        $totalDayPayAmount = round(($totalDayPayAmount + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $overallSuperTotal = round(($overallSuperTotal + $totalDayPayAmount),2);

        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('ordinary').'</td><td class="cellWidth" style="text-align: right">'.number_format($data['ordTotal'],2).'</td><td class="cellWidth" style="text-align: right">'.number_format($dayRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'ORDINARY', 1, $jobCode, 0, $data['ordTotal'], $dayRate, $dayAmount, $dayChargeRate, $dayChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['emgTotal']) > 0){
        $emgTotal = number_format($data['emgTotal'],2);
        $emgRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$emgPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($data['emgTotal'],$emgRate);
        $emgAmount = $payTotal;
        $emgChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$emgPayCatCode,$jobCode);
        $emgChargeTotal = calculateChargeAmount($data['emgTotal'],$emgChargeRate);
        $emgChargeAmount = $emgChargeTotal;
        $emgPayAmount = round(($emgPayAmount + $payTotal),2);
        $emgPayTotal = round(($emgPayTotal + $payTotal),2);

        $totalEMGPayAmount = round(($totalEMGPayAmount + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $overallSuperTotal = round(($overallSuperTotal + $totalEMGPayAmount),2);

        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('early morning').'</td><td class="cellWidth" style="text-align: right">'.number_format($data['emgTotal'],2).'</td><td class="cellWidth" style="text-align: right">'.number_format($emgRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'EARLY MORNING', 1, $jobCode, 0, $data['emgTotal'], $emgRate, $emgAmount, $emgChargeRate, $emgChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['aftTotal']) > 0){
        $aftTotal = number_format($data['aftTotal'],2);
        $aftRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$aftPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($data['aftTotal'],$aftRate);
        $aftAmount = $payTotal;
        $aftChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$aftPayCatCode,$jobCode);
        $aftChargeTotal = calculateChargeAmount($data['aftTotal'],$aftChargeRate);
        $aftChargeAmount = $aftChargeTotal;
        $aftPayAmount = round(($aftPayAmount + $payTotal),2);
        $aftPayTotal = round(($aftPayTotal + $payTotal),2);

        $totalAftPayAmount = round(($totalAftPayAmount + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $overallSuperTotal = round(($overallSuperTotal + $totalAftPayAmount),2);

        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('afternoon').'</td><td class="cellWidth" style="text-align: right">'.number_format($data['aftTotal'],2).'</td><td class="cellWidth" style="text-align: right">'.number_format($aftRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'AFTERNOON', 2, $jobCode, 0, $data['aftTotal'], $aftRate, $aftAmount, $aftChargeRate, $aftChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['nightTotal']) > 0){
        $nightTotal = number_format($data['nightTotal'],2);
        $nightRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$nightPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($data['nightTotal'],$nightRate);
        $nightAmount = $payTotal;
        $nightChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$nightPayCatCode,$jobCode);
        $nightChargeTotal = calculateChargeAmount($data['nightTotal'],$nightChargeRate);
        $nightChargeAmount = $nightChargeTotal;
        $nightPayAmount = round(($nightPayAmount + $payTotal),2);
        $nightPayTotal = round(($nightPayTotal + $payTotal),2);

        $totalNightPayAmount = round(($totalNightPayAmount + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $overallSuperTotal = round(($overallSuperTotal + $totalNightPayAmount),2);

        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('night').'</td><td class="cellWidth" style="text-align: right">'.number_format($data['nightTotal'],2).'</td><td class="cellWidth" style="text-align: right">'.number_format($nightRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'NIGHT', 3, $jobCode, 0, $data['nightTotal'], $nightRate, $nightAmount, $nightChargeRate, $nightChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['rdoTotal']) > 0){
        $rdoTotal = number_format($data['rdoTotal'],2);
        $rdoRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$rdoPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($data['rdoTotal'],$rdoRate);
        $rdoAmount = $payTotal;
        $rdoChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$rdoPayCatCode,$jobCode);
        $rdoChargeTotal = calculateChargeAmount($data['rdoTotal'],$rdoChargeRate);
        $rdoChargeAmount = $rdoChargeTotal;
        $rdoPayAmount = round(($rdoPayAmount + $payTotal),2);
        $rdoPayTotal = round(($rdoPayTotal + $payTotal),2);

        $totalRDOPayAmount = round(($totalRDOPayAmount + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $overallSuperTotal = round(($overallSuperTotal + $totalRDOPayAmount),2);

        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('rdo').'</td><td class="cellWidth" style="text-align: right">'.number_format($data['rdoTotal'],2).'</td><td class="cellWidth" style="text-align: right">'.number_format($rdoRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'RDO', 3, $jobCode, 0, $data['rdoTotal'], $rdoRate, $rdoAmount, $rdoChargeRate, $rdoChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }

    if($data['clientId'] == 82) {
        if (($data['hol_total']) > 0) {
            $hld2Total = number_format($data['hol_total'], 2);
            $hld2Rate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holPay2CatCode,$jobCode);
            $payTotal = calculatePayAmount($hld2Total, $hld2Rate);
            $hld2Amount = $payTotal;
            $hld2ChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holPay2CatCode,$jobCode);
            $hld2ChargeTotal = calculateChargeAmount($hld2Total, $hld2ChargeRate);
            $hld2ChargeAmount = $hld2ChargeTotal;
            $hld2PayAmount = round(($hld2PayAmount + $payTotal), 2);
            $hld2PayTotal = round(($hld2PayTotal + $payTotal), 2);

            $totalHoliday2PayAmount = round(($totalHoliday2PayAmount + $payTotal),2);
            $overallTotal = round(($overallTotal + $payTotal),2);
            $overallSuperTotal = round(($overallSuperTotal + $totalHoliday2PayAmount),2);

            $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellCenter">' . $jobCode . '</td><td class="cellWidth"></td><td class="desc">' . strtoupper('public holiday 2') . '</td><td class="cellWidth" style="text-align: right">' . number_format($data['hol_total'], 2) . '</td><td class="cellWidth" style="text-align: right">' . number_format($hld2Rate, 2) . '</td><td class="cellWidth" style="text-align: right">' . number_format($payTotal, 2) . '</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            if($action == 'GENERATE') {
                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'PUBLIC HOLIDAY 2', 8, $jobCode, 0, $hld2Total, $hld2Rate, $hld2Amount, $hld2ChargeRate, $hld2ChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
            }
        }
        if (($data['hldTotal']) > 0) {
            $hldTotal = number_format($data['hldTotal'], 2);
            $hldRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holPayCatCode,$jobCode);
            $payTotal = calculatePayAmount($hldTotal, $hldRate);
            $holAmount = $payTotal;
            $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holPayCatCode,$jobCode);
            $hldChargeTotal = calculateChargeAmount($hldTotal, $hldChargeRate);
            $hldChargeAmount = $hldChargeTotal;
            $hldPayAmount = round(($hldPayAmount + $payTotal), 2);
            $holPayTotal = round(($holPayTotal + $payTotal), 2);

            $totalHolidayPayAmount = round(($totalHolidayPayAmount + $payTotal), 2);
            $overallTotal = round(($overallTotal + $payTotal),2);
            $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmount), 2);
            $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td class="cellCenter">' . $jobCode . '</td><td class="cellWidth"></td><td class="desc">' . strtoupper('public holiday') . '</td><td class="cellWidth" style="text-align: right">' . number_format($hldTotal, 2) . '</td><td class="cellWidth" style="text-align: right">' . number_format($hldRate, 2) . '</td><td class="cellWidth" style="text-align: right">' . number_format($payTotal, 2) . '</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            if($action == 'GENERATE') {
                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'PUBLIC HOLIDAY', 8, $jobCode, 0, $hldTotal, $hldRate, $holAmount, $hldChargeRate, $hldChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
            }
        }
    }else{
        if(($data['hldTotal']) > 0){
            $wkTotal = $dayTotal + $emgTotal + $aftTotal + $nightTotal + $satTotal + $sunTotal;
            $hldTotal = number_format($data['hldTotal'],2);
            $avgNormalHrs = getAverageNormalHours($mysqli,$jobCode);
            $hldWithoutSuperTotal = 0;
            if(($wkTotal + $hldTotal) > $avgNormalHrs) {
                $hldWithoutSuperTotal = $hldTotal - ($avgNormalHrs - $wkTotal);
                $hldTotalWithSuper = ($avgNormalHrs - $wkTotal);
                $hldRateWithSuper = getPayRate($mysqli,$data['clientId'],$data['positionId'],$hldSuperPayCatCode,$jobCode);
                $payTotal = calculatePayAmount($hldTotalWithSuper,$hldRateWithSuper);
                $holAmount = $payTotal;
                $hldChargeRateWithSuper = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$hldSuperPayCatCode,$jobCode);
                $hldChargeTotal = calculateChargeAmount($hldTotalWithSuper,$hldChargeRateWithSuper);
                $hldChargeAmountWithSuper = $hldChargeTotal;
                $hldPayAmountWithSuper = round(($hldPayAmountWithSuper + $payTotal),2);
                $holPayTotalWithSuper = round(($holPayTotalWithSuper + $payTotal),2);

                $totalHolidayPayAmountWithSuper = round(($totalHolidayPayAmountWithSuper + $payTotal), 2);
                $overallTotal = round(($overallTotal + $payTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmountWithSuper), 2);

                $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('holiday with super').'</td><td class="cellWidth" style="text-align: right">'.number_format($hldTotalWithSuper,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($hldRateWithSuper,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
                if($action == 'GENERATE') {
                    savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'HOLIDAY WITH SUPER', 8, $jobCode, 0, $hldTotalWithSuper, $hldRateWithSuper, $payTotal, $hldChargeRateWithSuper, $hldChargeAmountWithSuper, 0.00, 0.00, 0.00, 0.00, 0.00);
                }
            }elseif(($wkTotal + $hldTotal) <= $avgNormalHrs){
                $hldRateWithSuper = getPayRate($mysqli,$data['clientId'],$data['positionId'],$hldSuperPayCatCode,$jobCode);
                $payTotal = calculatePayAmount($hldTotal,$hldRateWithSuper);
                $holAmount = $payTotal;
                $hldChargeRateWithSuper = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$hldSuperPayCatCode,$jobCode);
                $hldChargeTotal = calculateChargeAmount($hldTotal,$hldChargeRateWithSuper);
                $hldChargeAmountWithSuper = $hldChargeTotal;
                $hldPayAmountWithSuper = round(($hldPayAmountWithSuper + $payTotal),2);
                $holPayTotalWithSuper = round(($holPayTotalWithSuper + $payTotal),2);

                $totalHolidayPayAmountWithSuper = round(($totalHolidayPayAmountWithSuper + $payTotal), 2);
                $overallTotal = round(($overallTotal + $payTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmountWithSuper), 2);

                $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('holiday with super').'</td><td class="cellWidth" style="text-align: right">'.number_format($hldTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($hldRateWithSuper,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
                if($action == 'GENERATE') {
                    savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'HOLIDAY WITH SUPER', 8, $jobCode, 0, $hldTotal, $hldRateWithSuper, $payTotal, $hldChargeRateWithSuper, $hldChargeAmountWithSuper, 0.00, 0.00, 0.00, 0.00, 0.00);
                }
            }else{
                $hldRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$holPayCatCode,$jobCode);
                $payTotal = calculatePayAmount($hldTotal,$hldRate);
                $holAmount = $payTotal;
                $hldChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$holPayCatCode,$jobCode);
                $hldChargeTotal = calculateChargeAmount($hldTotal,$hldChargeRate);
                $hldChargeAmount = $hldChargeTotal;
                $hldPayAmount = round(($hldPayAmount + $payTotal),2);
                $holPayTotal = round(($holPayTotal + $payTotal),2);

                $totalHolidayPayAmount = round(($totalHolidayPayAmount + $payTotal), 2);
                $overallTotal = round(($overallTotal + $payTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmount), 2);

                $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('public holiday').'</td><td class="cellWidth" style="text-align: right">'.number_format($hldTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($hldRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
                if($action == 'GENERATE') {
                    savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'PUBLIC HOLIDAY', 8, $jobCode, 0, $hldTotal, $hldRate, $holAmount, $hldChargeRate, $hldChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
                }
            }
            if($hldWithoutSuperTotal != 0){
                $hldRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$holPayCatCode,$jobCode);
                $payTotal = calculatePayAmount($hldWithoutSuperTotal,$hldRate);
                $holAmount = $payTotal;
                $hldChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$holPayCatCode,$jobCode);
                $hldChargeTotal = calculateChargeAmount($hldWithoutSuperTotal,$hldChargeRate);
                $hldChargeAmount = $hldChargeTotal;
                $hldPayAmount = round(($hldPayAmount + $payTotal),2);
                $holPayTotal = round(($holPayTotal + $payTotal),2);

                $totalHolidayPayAmount = round(($totalHolidayPayAmount + $payTotal), 2);
                $overallTotal = round(($overallTotal + $payTotal),2);
                $overallSuperTotal = round(($overallSuperTotal + $totalHolidayPayAmount), 2);

                $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('public holiday').'</td><td class="cellWidth" style="text-align: right">'.number_format($hldWithoutSuperTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($hldRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
                if($action == 'GENERATE') {
                    savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'PUBLIC HOLIDAY', 8, $jobCode, 0, $hldWithoutSuperTotal, $hldRate, $holAmount, $hldChargeRate, $hldChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
                }
            }
        }
    }
    if(($data['satTotal']) > 0){
        $wkTotal = $dayTotal + $emgTotal + $aftTotal + $nightTotal + $hldTotal + $sunTotal;
        $avgNormalHrs = getAverageNormalHours($mysqli,$data['jobcode']);
        $satWithoutSuperTotal = 0;
        $satWithSuperTotal = 0;
        $satTotal = number_format($data['satTotal'],2);
        if(($wkTotal + $satTotal) <= $avgNormalHrs){
            $satWithSuperTotal = $satTotal;
            $satRateWithSuper = getPayRate($mysqli,$data['clientId'],$data['positionId'],$satSuperPayCatCode,$jobCode);
            $payTotal = calculatePayAmount($satWithSuperTotal,$satRateWithSuper);
            $satWithSuperAmount = $payTotal;
            $satChargeRateWithSuper = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$satSuperPayCatCode,$jobCode);
            $satChargeTotal = calculateChargeAmount($satWithSuperTotal,$satChargeRateWithSuper);
            $satChargeAmountWithSuper = $satChargeTotal;
            $satPayAmountWithSuper = round(($satPayAmountWithSuper + $payTotal),2);
            $satPayTotalWithSuper = round(($satPayTotalWithSuper + $payTotal),2);

            $totalSatPayAmountWithSuper = round(($totalSatPayAmountWithSuper + $payTotal),2);
            $overallTotal = round(($overallTotal + $payTotal),2);
            $overallSuperTotal = round(($overallSuperTotal + $totalSatPayAmountWithSuper),2);

            $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('saturday with super').'</td><td class="cellWidth" style="text-align: right">'.number_format($satTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($satRateWithSuper,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            if($action == 'GENERATE') {
                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'SATURDAY WITH SUPER', 4, $jobCode, 0, $satWithSuperTotal, $satRateWithSuper, $satWithSuperAmount, $satChargeRateWithSuper, $satChargeAmountWithSuper, 0.00, 0.00, 0.00, 0.00, 0.00);
            }
        }else{
            $satWithoutSuperTotal = $satTotal;
            $satRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$satPayCatCode,$jobCode);
            $payTotal = calculatePayAmount($satWithoutSuperTotal,$satRate);
            $satAmount = $payTotal;
            $satChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$satPayCatCode,$jobCode);
            $satChargeTotal = calculateChargeAmount($satWithoutSuperTotal,$satChargeRate);
            $satChargeAmount = $satChargeTotal;
            $satPayAmount = round(($satPayAmount + $payTotal),2);
            $satPayTotal = round(($satPayTotal + $payTotal),2);

            $totalSatPayAmount = round(($totalSatPayAmount + $payTotal),2);
            $overallTotal = round(($overallTotal + $payTotal),2);
            $overallSuperTotal = round(($overallSuperTotal + $totalSatPayAmount),2);

            $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('saturday').'</td><td class="cellWidth" style="text-align: right">'.number_format($satTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($satRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            if($action == 'GENERATE') {
                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'SATURDAY', 4, $jobCode, 0, $satWithoutSuperTotal, $satRate, $satAmount, $satChargeRate, $satChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
            }
        }
    }
    if(($data['sunTotal']) > 0){
        $wkTotal = $dayTotal + $emgTotal + $aftTotal + $nightTotal + $hldTotal + $satTotal;
        $avgNormalHrs = getAverageNormalHours($mysqli,$data['jobcode']);
        $sunWithoutSuperTotal = 0;
        $sunWithSuperTotal = 0;
        $sunTotal = number_format($data['sunTotal'],2);
        if(($wkTotal + $sunTotal)<= $avgNormalHrs) {
            $sunWithSuperTotal = $sunTotal;
            $sunRateWithSuper = getPayRate($mysqli,$data['clientId'],$data['positionId'],$sunSuperPayCatCode,$jobCode);
            $payTotal = calculatePayAmount($sunWithSuperTotal,$sunRateWithSuper);
            $sunAmount = $payTotal;
            $sunChargeRateWithSuper = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$sunSuperPayCatCode,$jobCode);
            $sunChargeTotal = calculateChargeAmount($sunWithSuperTotal,$sunChargeRateWithSuper);
            $sunChargeAmountWithSuper = $sunChargeTotal;
            $sunPayAmountWithSuper = round(($sunPayAmountWithSuper + $payTotal),2);
            $sunPayTotalWithSuper = round(($sunPayTotalWithSuper + $payTotal),2);

            $totalSunPayAmount = round(($totalSunPayAmount + $payTotal),2);
            $overallTotal = round(($overallTotal + $payTotal),2);
            $overallSuperTotal = round(($overallSuperTotal + $totalSunPayAmount),2);

            $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('sunday with super').'</td><td class="cellWidth" style="text-align: right">'.number_format($sunTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($sunRateWithSuper,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            if($action == 'GENERATE') {
                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'SUNDAY WITH SUPER', 5, $jobCode, 0, $sunTotal, $sunRateWithSuper, $sunAmount, $sunChargeRateWithSuper, $sunChargeAmountWithSuper, 0.00, 0.00, 0.00, 0.00, 0.00);
            }
        }else{
            $sunWithoutSuperTotal = $sunTotal;
            $sunRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$sunPayCatCode,$jobCode);
            $payTotal = calculatePayAmount($sunWithoutSuperTotal,$sunRate);
            $sunAmount = $payTotal;
            $sunChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$sunPayCatCode,$jobCode);
            $sunChargeTotal = calculateChargeAmount($sunWithoutSuperTotal,$sunChargeRate);
            $sunChargeAmount = $sunChargeTotal;
            $sunPayAmount = round(($sunPayAmount + $payTotal),2);
            $sunPayTotal = round(($sunPayTotal + $payTotal),2);

            $totalSunPayAmount = round(($totalSunPayAmount + $payTotal),2);
            $overallTotal = round(($overallTotal + $payTotal),2);
            $overallSuperTotal = round(($overallSuperTotal + $totalSunPayAmount),2);

            $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('sunday').'</td><td class="cellWidth" style="text-align: right">'.number_format($sunTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($sunRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
            if($action == 'GENERATE') {
                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'SUNDAY', 5, $jobCode, 0, $sunTotal, $sunRate, $sunAmount, $sunChargeRate, $sunChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
            }
        }
    }
    if(($data['ovtTotal']) > 0){
        $ovtTotal = number_format($data['ovtTotal'], 2);
        $ovtRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$ovtPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($ovtTotal,$ovtRate);
        $ovtAmount = $payTotal;
        $ovtChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$ovtPayCatCode,$jobCode);
        $ovtChargeTotal = calculateChargeAmount($ovtTotal,$ovtChargeRate);
        $ovtChargeAmount = $ovtChargeTotal;
        $ovtPayAmount = round(($ovtPayAmount + $payTotal),2);
        $ovtPayTotal = round(($ovtPayTotal + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('overtime').'</td><td class="cellWidth" style="text-align: right">'.number_format($ovtTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($ovtRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'OVERTIME', 6, $jobCode, 0, $ovtTotal, $ovtRate, $ovtAmount, $ovtChargeRate, $ovtChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['satovtTotal']) > 0){
        $satovtTotal = number_format($data['satovtTotal'],2);
        $satovtRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$satovtPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($satovtTotal,$satovtRate);
        $satovtAmount = $payTotal;
        $satovtChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$satovtPayCatCode,$jobCode);
        $satovtChargeTotal = calculateChargeAmount($satovtTotal,$satovtChargeRate);
        $satovtChargeAmount = $satovtChargeTotal;
        $satovtPayAmount = round(($satovtPayAmount + $payTotal),2);
        $satovtPayTotal = round(($satovtPayTotal + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('saturday overtime').'</td><td class="cellWidth" style="text-align: right">'.number_format($satovtTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($satovtRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'SATURDAY OVERTIME', 6, $jobCode, 0, $satovtTotal, $satovtRate, $satovtAmount, $satovtChargeRate, $satovtChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['sunovtTotal']) > 0){
        $sunovtTotal = number_format($data['sunovtTotal'],2);
        $sunovtRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$sunovtPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($sunovtTotal,$sunovtRate);
        $sunovtAmount = $payTotal;
        $sunovtChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$sunovtPayCatCode,$jobCode);
        $sunovtChargeTotal = calculateChargeAmount($sunovtTotal,$sunovtChargeRate);
        $sunovtChargeAmount = $sunovtChargeTotal;
        $sunovtPayAmount = round(($sunovtPayAmount + $payTotal),2);
        $sunovtPayTotal = round(($sunovtPayTotal + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('sunday overtime').'</td><td class="cellWidth" style="text-align: right">'.number_format($sunovtTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($sunovtRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'SUNDAY OVERTIME', 6, $jobCode, 0, $sunovtTotal, $sunovtRate, $sunovtAmount, $sunovtChargeRate, $sunovtChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['povtTotal']) > 0){
        $povtTotal = number_format($data['povtTotal'],2);
        $povtRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$povtPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($povtTotal,$povtRate);
        $povtAmount = $payTotal;
        $povtChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$povtPayCatCode,$jobCode);
        $povtChargeTotal = calculateChargeAmount($povtTotal,$povtChargeRate);
        $povtChargeAmount = $povtChargeTotal;
        $povtPayAmount = round(($povtPayAmount + $payTotal),2);
        $povtPayTotal = round(($povtPayTotal + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('period overtime').'</td><td class="cellWidth" style="text-align: right">'.number_format($povtTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($povtRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'PERIOD OVERTIME', 6, $jobCode, 0, $povtTotal, $povtRate, $povtAmount, $povtChargeRate, $povtChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    if(($data['dblTotal']) > 0){
        $dblTotal = number_format($data['dblTotal'],2);
        $dblRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$dblPayCatCode,$jobCode);
        $payTotal = calculatePayAmount($dblTotal,$dblRate);
        $dblAmount = $payTotal;
        $dblChargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$dblPayCatCode,$jobCode);
        $dblChargeTotal = calculateChargeAmount($dblTotal,$dblChargeRate);
        $dblChargeAmount = $dblChargeTotal;
        $dblPayAmount = round(($dblPayAmount + $payTotal),2);
        $dblPayTotal = round(($dblPayTotal + $payTotal),2);
        $overallTotal = round(($overallTotal + $payTotal),2);
        $html = $html.'<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$jobCode.'</td><td class="cellWidth"></td><td class="desc">'.strtoupper('double time').'</td><td class="cellWidth" style="text-align: right">'.number_format($dblTotal,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($dblRate,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($payTotal,2).'</td><td class="cellWidth"></td><td class="cellWidth"></td></tr>';
        if($action == 'GENERATE') {
            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], 'DOUBLETIME', 7, $jobCode, 0, $dblTotal, $dblRate, $dblAmount, $dblChargeRate, $dblChargeAmount, 0.00, 0.00, 0.00, 0.00, 0.00);
        }
    }
    $totalUnits = (float)$totalEMGUnits + (float)$totalDayUnits + (float)$totalAftUnits + (float)$totalNightUnits + (float)$totalRDOUnits + (float)$totalSatUnits + (float)$totalSatUnitsWithSuper + (float)$totalSunUnits + (float)$totalSunUnitsWithSuper + (float)$totalOvtUnits + (float)$totalSatOvtUnits + (float)$totalSunOvtUnits + (float)$totalPeriodOvtUnits +(float)$totalDblUnits + (float)$totalHolUnits + (float)$totalHol2Units + (float)$totalHolUnitsWithSuper;
    $candidateHours = (float)$data['emgTotal'] + (float)$data['ordTotal'] + (float)$data['aftTotal'] + (float)$data['nightTotal'] + (float)$data['rdoTotal'] + (float)$data['satTotal'] + (float)$data['sunTotal'] + (float)$data['ovtTotal'] + (float)$data['satovtTotal'] + (float)$data['sunovtTotal'] + (float)$data['povtTotal'] + (float)$data['dblTotal'] + (float)$data['hldTotal'] + (float)$data['hol_total'];
    //$totalPayable = round(($emgPayTotal + $dayPayTotal + $aftPayTotal + $nightPayTotal + $rdoPayTotal + $satPayTotal + $satPayTotalWithSuper + $sunPayTotal + $sunPayTotalWithSuper + $ovtPayTotal + $satovtPayTotal + $sunovtPayTotal + $povtPayTotal + $dblPayTotal + $holPayTotal + $hld2PayTotal + $holPayTotalWithSuper),2);
    $totalPayable = $overallTotal;
    $transCode = candidateSuperFundTransCode($mysqli, $data['candidateId']);
    $candidateSuperFundDesc = getTransCodeDescByTransCode($mysqli, $transCode);
    $avgNormalHrs = getAverageNormalHours($mysqli,$data['jobcode']);
    //$totalForSuper = $dayPayTotal + $emgPayTotal + $rdoPayTotal + $aftPayTotal + $nightPayTotal + $satPayTotal + $satPayTotalWithSuper + $sunPayTotal + $sunPayTotalWithSuper + $holPayTotal + $hld2PayTotal + $holPayTotalWithSuper;
    $totalUnitsForSuper = (float)$totalEMGUnits + (float)$totalDayUnits + (float)$totalAftUnits + (float)$totalNightUnits + (float)$totalSatUnits + (float)$totalSunUnits;
    $fUnits = $fUnits + $totalUnits;
    $totalSuperUnits = $totalEMGUnits + $totalDayUnits + $totalAftUnits + $totalNightUnits + $totalRDOUnits + $totalSatUnits + $totalSatUnitsWithSuper + $totalSunUnits + $totalSunUnitsWithSuper +$totalDblUnits + $totalHolUnits + $totalHol2Units + $totalHolUnitsWithSuper;
    if($fUnits > $avgNormalHrs){
        $totalForSuper = $dayPayTotal + $emgPayTotal + $rdoPayTotal + $aftPayTotal + $nightPayTotal + $satPayTotal + $satPayTotalWithSuper + $sunPayTotal + $sunPayTotalWithSuper + $hld2PayTotal + $holPayTotalWithSuper;
    }elseif($fUnits <= $avgNormalHrs){
        $totalForSuper = $dayPayTotal + $emgPayTotal + $rdoPayTotal + $aftPayTotal + $nightPayTotal + $satPayTotal + $satPayTotalWithSuper + $sunPayTotal + $sunPayTotalWithSuper + $holPayTotal + $hld2PayTotal + $holPayTotalWithSuper;
    }
    $superAnnuation = calculateSuperAnnuation($mysqli, $totalForSuper, $transCode);

    $totalAllEMGUnits = (float)$totalAllEMGUnits + (float)$data['emgTotal'];
    $totalAllDayUnits = (float)$totalAllDayUnits + (float)$data['ordTotal'];
    $totalAllAftUnits = (float)$totalAllAftUnits + (float)$data['aftTotal'];
    $totalAllNightUnits = (float)$totalAllNightUnits + (float)$data['nightTotal'];
    $totalAllRDOUnits = (float)$totalAllRDOUnits + (float)$data['rdoTotal'];

    $totalAllSatUnits = (float)$totalAllSatUnits + (float)$satWithoutSuperTotal;
    $totalAllSatUnitsWithSuper = (float)$totalAllSatUnitsWithSuper + (float)$satWithSuperTotal;

    $totalAllSunUnits = (float)$totalAllSunUnits + (float)$data['sunTotal'];
    $totalAllSunUnitsWithSuper = (float)$totalAllSunUnitsWithSuper + (float)$data['sunTotal'];

    $totalAllOvtUnits = (float)$totalAllOvtUnits + (float)$data['ovtTotal'];
    $totalAllSatOvtUnits = (float)$totalAllSatOvtUnits + (float)$data['satovtTotal'];
    $totalAllSunOvtUnits = (float)$totalAllSunOvtUnits + (float)$data['sunovtTotal'];
    $totalAllPeriodOvtUnits = (float)$totalAllPeriodOvtUnits + (float)$data['povtTotal'];
    $totalAllDblUnits = (float)$totalAllDblUnits + (float)$data['dblTotal'];
    $totalAllHolUnits = (float)$totalAllHolUnits + (float)$data['hldTotal'];
    $totalAllHol2Units = (float)$totalAllHol2Units + (float)$data['hol_total'];
    $totalAllHolUnitsWithSuper = $hldTotalWithSuper;
    $totalAllEMGPay = $emgPayAmount;
    $totalAllDayPay = $dayPayAmount;
    $totalAllAftPay = $aftPayAmount;
    $totalAllNightPay = $nightPayAmount;
    $totalAllRDOPay = $rdoPayAmount;
    $totalAllSatPay = $satPayAmount;
    $totalAllSatPayWithSuper = $satPayAmountWithSuper;
    $totalAllSunPay = $sunPayAmount;
    $totalAllSunPayWithSuper = $sunPayAmountWithSuper;
    $totalAllOvtPay = $ovtPayAmount;
    $totalAllSatOvtPay = $satovtPayAmount;
    $totalAllSunOvtPay = $sunovtPayAmount;
    $totalAllPeriodOvtPay = $povtPayAmount;
    $totalAllDblPay = $dblPayAmount;
    $totalAllHolPay = $hldPayAmount;
    $totalAllHol2Pay = $hld2PayAmount;
    $totalAllHolPayWithSuper = $hldPayAmountWithSuper;
    if($check == $data['candidateId']){
        foreach (array_count_values($dataArray) as $key => $value) {
            if ($data['candidateId'] == $key) {
                $count++;
                //if($count == 1) {
                $allowanceCodes = getEmployeeAllowances($mysqli,$data['candidateId'],$weekendingDate,$jobCode);
                $allowanceAmount = 0;
                foreach ($allowanceCodes as $allowance) {
                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td></td><td class="shortWidth">' . $allowance['transCode'] . '</td><td>' . $allowance['transCodeDesc'] . '</td><td class="cellWidth" style="text-align: right">' . number_format(1, 2) . '</td><td class="cellWidth" style="text-align: right">' . number_format($allowance['transCodeAmount'], 2) . '</td><td class="cellWidth" style="text-align: right"></td><td class="cellWidth" style="text-align: right">' . number_format($allowance['transCodeAmount'], 2) . '</td><td></td></tr>';
                    $allowanceAmount = $allowanceAmount + $allowance['transCodeAmount'];
                    if($action == 'GENERATE') {
                        savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], $allowance['transCodeDesc'], 14, $jobCode, $allowance['transCode'], 0, 0.00, $allowance['transCodeAmount'], 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00);
                    }
                    foreach ($allUnitsPayArray as $key => $value) {
                        if ($key == $allowance['transCode']) {
                            $allUnitsPayArray[$key]['code'] = $allowance['transCode'];
                            $allUnitsPayArray[$key]['desc'] = $allowance['transCodeDesc'];
                            $allUnitsPayArray[$key]['units'] = $value['units'] + 1;
                            $allUnitsPayArray[$key]['amount'] = $value['amount'] + $allowance['transCodeAmount'];
                        }
                    }
                }
                //}
                $netWages = $netWages + $allowanceAmount;
                $deductCodes = getEmployeeDeductions($mysqli, $data['candidateId'], $weekendingDate,$jobCode);
                $taxOrder = '';
                $add_deduction_to_net = 0;
                foreach ($deductCodes as $deduct) {
                    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td></td><td class="shortWidth">'.$deduct['transCode'].'</td><td>'.$deduct['transCodeDesc'].'</td><td class="cellWidth" style="text-align: right">'.number_format(1,2).'</td><td class="cellWidth" style="text-align: right">'.number_format($deduct['transCodeAmount'],2).'</td><td class="cellWidth" style="text-align: right">';
                    if($deduct['taxorder'] == 'before') {
                        $taxOrder = 'before';
                        //$gross = $gross - $deduct['transCodeAmount'];
                        //$totalPayable = $totalPayable - $deduct['transCodeAmount'];
                        //$totalAfterDeductionTotal = $totalAfterDeductionTotal + $deduct['transCodeAmount'];
                        $add_deduction_to_net = 1;
                        $totalPayable = $totalPayable - $deduct['transCodeAmount'];
                        $html = $html.'-'.number_format($deduct['transCodeAmount'],2);
                        $html = $html.'</td><td class="cellWidth" style="text-align: right">';
                    }elseif($deduct['taxorder'] == 'after') {
                        $taxOrder = 'after';
                        $html = $html.'-'.number_format($deduct['transCodeAmount'],2);
                        $candidateArray[$data['candidateId']]['afterTaxDeductTotal'] = (float)$candidateArray[$data['candidateId']]['afterTaxDeductTotal'] + (float)$deduct['transCodeAmount'];
                        $totalAfterDeductionTotal = $totalAfterDeductionTotal + $deduct['transCodeAmount'];
                        //$netWages = ($gross - $paygTax) - $deduct['transCodeAmount'];
                        $html = $html.'</td><td class="cellWidth" style="text-align: right">';
                    }else{
                        $html = $html.'&nbsp;';
                    }
                    $html = $html.'</td><td></td></tr>';
                    if($jcode == $jobCode) {
                        $deductAmount = round(($deductAmount + $deduct['transCodeAmount']),2);
                        $candidateArray[$data['candidateId']]['deductAmount'] = (float)$candidateArray[$data['candidateId']]['deductAmount'] + (float)$deductAmount;
                    }
                    /*$deductAmount = round(($deductAmount + $deduct['transCodeAmount']),2);
                    $candidateArray[$data['candidateId']]['deductAmount'] = $candidateArray[$data['candidateId']]['deductAmount'] + $deductAmount;*/
                    //$candidateArray[$data['candidateId']]['totalGross'] = $candidateArray[$data['candidateId']]['totalGross'] + $deductAmount;
                    if($action == 'GENERATE') {
                        savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], $deduct['transCodeDesc'], 10, $jobCode, $deduct['transCode'], 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, $deduct['transCodeAmount'], 0.00);
                    }
                    foreach ($allUnitsPayArray as $key => $value) {
                        if ($key == $deduct['transCode']) {
                            $allUnitsPayArray[$key]['code'] = $deduct['transCode'];
                            $allUnitsPayArray[$key]['desc'] = $deduct['transCodeDesc'];
                            $allUnitsPayArray[$key]['units'] = $value['units'] + 1;
                            $allUnitsPayArray[$key]['amount'] = $value['amount'] + $deduct['transCodeAmount'];
                        }
                    }
                }
                /*if($taxOrder == 'before') {
                    $totalPayable = $totalPayable - $deductAmount;
                }elseif ($taxOrder == 'after'){
                    $totalPayable = $totalPayable +$deductAmount;
                }*/
                $no_after_tax = 0;
                $add_to_net = 0;
                if($allowanceAmount > 0){
                    $tr_codes = getEmployeeAllowances($mysqli,$data['candidateId'],$weekendingDate,$jobCode);
                    foreach ($tr_codes as $allowanceCode){
                        if($allowanceCode['transCode'] == 29){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['transCode'] == 26){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['transCode'] == 725){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['transCode'] == 695){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['transCode'] == 956){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['transCode'] == 1267){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['transCode'] == 1268){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        /* Allowance before */
                        if($allowanceCode['transCode'] == 1277){
                            $no_after_tax = 0;
                            $add_to_net = 0;
                        }
                        if($allowanceCode['transCode'] == 1290){
                            $no_after_tax = 0;
                            $add_to_net = 0;
                        }
                        if($allowanceCode['transCode'] == 1281){
                            $no_after_tax = 1;
                            $add_to_net = 1;
                        }
                        if($allowanceCode['taxorder'] == 'after'){
                            $totalAfterAllowanceTaxTotal = $totalAfterAllowanceTaxTotal + $allowanceAmount;
                            $candidateArray[$data['candidateId']]['afterTaxAllowanceTotal'] = (float)$candidateArray[$data['candidateId']]['afterTaxAllowanceTotal'] + (float)$allowanceAmount;
                        }
                    }
                    if($no_after_tax != 1) {
                        $totalPayable = $totalPayable + $allowanceAmount;
                    }
                    $paygTax = getCalculatedWeeklyPAYG($mysqli, $data['candidateId'], $totalPayable);
                }else {
                    $paygTax = getCalculatedWeeklyPAYG($mysqli, $data['candidateId'], $totalPayable);
                }



                $gross = $totalPayable;

                if($canId == $data['candidateId']) {
                    $netWages = ($gross - $paygTax) - $deductAmount;
                    if($currentCandidateCount>1) {
                        $candidateCount++;
                        if ($candidateCount < $currentCandidateCount) {
                            $candidateGross = $candidateGross + $gross;
                        }
                        if ($candidateCount == $currentCandidateCount) {
                            $candidateGross = $candidateGross + $gross;
                            $allTax = getCalculatedWeeklyPAYG($mysqli, $data['candidateId'], $candidateGross);
                            /*if($taxOrder == 'before') {
                                $netWages = $gross - $paygTax;
                            }elseif($taxOrder == 'after'){
                                if($canId != $data['candidateId']) {
                                    $netWages = ($gross - $paygTax) - $deductAmount;
                                }else{
                                    $netWages = ($gross - $paygTax) - $deductAmount;
                                }
                            }else{
                                $netWages = $gross - $paygTax;
                            }
                            foreach ($deductCodes as $deduct) {
                                if($deduct['taxorder'] == 'after') {
                                    $netWages = ($gross - $paygTax) - $deduct['transCodeAmount'];
                                }
                            }*/
                            //$netWages = ($gross - $paygTax) - $deductAmount;
                            if($add_to_net != 0){
                                $netWages = $netWages + $allowanceAmount;
                            }
                            if($add_deduction_to_net != 0){
                                $netWages = $netWages + $deductAmount;
                            }
                            //$netWages = $netWages + $allowanceAmount;
                            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td></td><td></td><td></td><td class="subHeading" colspan="2">Gross Wages</td><td  class="boldFigure">' . number_format($gross, 2) . '</td><td></td><td></td></tr>';
                            $html = $html . '<tr class="zebra'.($i++ & 1).'"><td></td><td></td><td>PAYG Tax</td><td></td><td></td><td class="cellWidth" style="text-align: right">-' . number_format($paygTax,2) . '</td><td></td><td></td></tr>';
                            $deductAmount = 0;
                            $finalAmount = $finalAmount + $gross;

                            $candidateArray[$data['candidateId']]['totalGross'] = $gross;
                            $candidateArray[$data['candidateId']]['totalTax'] = getCalculatedWeeklyPAYG($mysqli, $data['candidateId'], $gross);
                            $candidateArray[$data['candidateId']]['net'] = (float)($candidateArray[$data['candidateId']]['totalGross'] - (float)$candidateArray[$data['candidateId']]['totalTax']);
                            $candidateArray[$data['candidateId']]['net'] = $netWages;
                            if($netWages<0){
                                $html = $html . '<tr class="zebra' . ($i++ & 1) . '" style="background-color:red"><td></td><td></td><td></td><td class="subHeading" colspan="2">Net Wages</td><td class="boldFigure">' . number_format($netWages, 2) . '</td><td></td><td></td></tr>';
                            }else {
                                $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td></td><td></td><td></td><td class="subHeading" colspan="2">Net Wages</td><td class="boldFigure">' . number_format($netWages, 2) . '</td><td></td><td></td></tr>';
                            }
                            //$html = $html.'<tr class="zebra'.($i++ & 1).'" style="border-bottom: 2px solid black"><td class="cellCenter">' . $jobCode . '</td><td class="shortWidth">' . $transCode . '</td><td>' . $candidateSuperFundDesc . '</td><td class="shortWidth">1.00</td><td></td><td></td><td></td><td class="cellWidth" style="text-align: right">' . number_format($superAnnuation,2) . '</td></tr>';
                            if($action == 'GENERATE') {
                                savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], $candidateSuperFundDesc, 12, $jobCode, $transCode, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, $superAnnuation);
                            }
                            foreach ($allUnitsPayArray as $key => $value) {
                                if ($key == $transCode) {
                                    $allUnitsPayArray[$key]['code'] = $transCode;
                                    $allUnitsPayArray[$key]['desc'] = $candidateSuperFundDesc;
                                    $allUnitsPayArray[$key]['units'] = $value['units'] + 1;
                                    $allUnitsPayArray[$key]['amount'] = $value['amount'] + $superAnnuation;
                                }
                            }
                            $totalNetWages = $totalNetWages + round($netWages,2);
                            $totalTax = $totalTax + $paygTax;
                            $count = 0;
                            $candidateCount = 0;
                        }
                    }elseif ($currentCandidateCount == 1){
                        /*if($taxOrder == 'before') {
                            $netWages = $gross - $paygTax;
                        }elseif($taxOrder == 'after'){
                            if($canId != $data['candidateId']) {
                                $netWages = ($gross - $paygTax) - $deductAmount;
                            }else{
                                $netWages = ($gross - $paygTax) - $deductAmount;
                            }
                        }else{
                            $netWages = $gross - $paygTax;
                        }
                        foreach ($deductCodes as $deduct) {
                            if($deduct['taxorder'] == 'after') {
                                $netWages = ($gross - $paygTax) - $deduct['transCodeAmount'];
                            }
                        }*/
                        // $netWages = ($gross - $paygTax)-$deductAmount;
                        //$netWages = $netWages + $allowanceAmount;
                        if($add_to_net != 0){
                            $netWages = $netWages + $allowanceAmount;
                        }
                        if($add_deduction_to_net != 0){
                            $netWages = $netWages + $deductAmount;
                        }
                        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td></td><td></td><td></td><td class="subHeading" colspan="2">Gross Wages</td><td  class="boldFigure">' . number_format($gross, 2) . '</td><td></td><td></td></tr>';
                        $html = $html . '<tr class="zebra'.($i++ & 1).'"><td></td><td></td><td>PAYG Tax</td><td></td><td></td><td class="cellWidth" style="text-align: right">-' . number_format($paygTax,2) . '</td><td></td><td></td></tr>';
                        $deductAmount = 0;
                        $finalAmount = $finalAmount + $gross;
                        $candidateArray[$data['candidateId']]['totalGross'] = $gross;
                        $candidateArray[$data['candidateId']]['totalTax'] = getCalculatedWeeklyPAYG($mysqli, $data['candidateId'], $gross);
                        $candidateArray[$data['candidateId']]['net'] = (float)($candidateArray[$data['candidateId']]['totalGross'] - (float)$candidateArray[$data['candidateId']]['totalTax']);
                        $candidateArray[$data['candidateId']]['net'] = $netWages;
                        if($netWages<0){
                            $html = $html . '<tr class="zebra' . ($i++ & 1) . '" style="background-color:red"><td></td><td></td><td></td><td class="subHeading" colspan="2">Net Wages</td><td class="boldFigure">' . number_format($netWages, 2) . '</td><td></td><td></td></tr>';
                        }else {
                            $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td></td><td></td><td></td><td class="subHeading" colspan="2">Net Wages</td><td class="boldFigure">' . number_format($netWages, 2) . '</td><td></td><td></td></tr>';
                        }
                        //$html = $html.'<tr class="zebra'.($i++ & 1).'" style="border-bottom: 2px solid black"><td class="cellCenter">' . $jobCode . '</td><td class="shortWidth">' . $transCode . '</td><td>' . $candidateSuperFundDesc . '</td><td class="shortWidth">1.00</td><td></td><td></td><td></td><td class="cellWidth" style="text-align: right">' . number_format($superAnnuation,2) . '</td></tr>';
                        if($action == 'GENERATE') {
                            savePayRun($mysqli, $payRunId, $weekendingDate, $data['candidateId'], $data['clientId'], $data['positionId'], $candidateSuperFundDesc, 12, $jobCode, $transCode, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, $superAnnuation);
                        }
                        foreach ($allUnitsPayArray as $key => $value) {
                            if ($key == $transCode) {
                                $allUnitsPayArray[$key]['code'] = $transCode;
                                $allUnitsPayArray[$key]['desc'] = $candidateSuperFundDesc;
                                $allUnitsPayArray[$key]['units'] = $value['units'] + 1;
                                $allUnitsPayArray[$key]['amount'] = $value['amount'] + $superAnnuation;
                            }
                        }
                        $totalNetWages = $totalNetWages + round($netWages,2);
                        $totalTax = $totalTax + $paygTax;
                        $count = 0;
                    }
                }
            }
        }
    }

    $payCatCode = getPayCatCode($mysqli,'EARLY MORNING');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='EARLY MORNING';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllEMGUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllEMGPay;
    $payCatCode = getPayCatCode($mysqli,'ORDINARY');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='ORDINARY';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllDayUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllDayPay;
    $payCatCode = getPayCatCode($mysqli,'AFTERNOON');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='AFTERNOON';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllAftUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllAftPay;
    $payCatCode = getPayCatCode($mysqli,'NIGHT');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='NIGHT';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllNightUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllNightPay;
    $payCatCode = getPayCatCode($mysqli,'RDO');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='RDO';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllRDOUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllRDOPay;
    $payCatCode = getPayCatCode($mysqli,'SATURDAY');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='SATURDAY';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllSatUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllSatPay;
    $payCatCode = getPayCatCode($mysqli,'SATURDAY WITH SUPER');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='SATURDAY WITH SUPER';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllSatUnitsWithSuper;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllSatPayWithSuper;
    $payCatCode = getPayCatCode($mysqli,'SUNDAY');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='SUNDAY';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllSunUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllSunPay;
    $payCatCode = getPayCatCode($mysqli,'SUNDAY WITH SUPER');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='SUNDAY WITH SUPER';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllSunUnitsWithSuper;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllSunPayWithSuper;
    $payCatCode = getPayCatCode($mysqli,'OVERTIME');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='OVERTIME';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllOvtUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllOvtPay;
    $payCatCode = getPayCatCode($mysqli,'SATURDAY OVERTIME');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='SATURDAY OVERTIME';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllSatOvtUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllSatOvtPay;
    $payCatCode = getPayCatCode($mysqli,'SUNDAY OVERTIME');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='SUNDAY OVERTIME';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllSunOvtUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllSunOvtPay;
    $payCatCode = getPayCatCode($mysqli,'PERIOD OVERTIME');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='PERIOD OVERTIME';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllPeriodOvtUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllPeriodOvtPay;
    $payCatCode = getPayCatCode($mysqli,'DOUBLETIME');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='DOUBLETIME';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllDblUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllDblPay;
    $payCatCode = getPayCatCode($mysqli,'PUBLIC HOLIDAY');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='PUBLIC HOLIDAY';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllHolUnits;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllHolPay;
    $payCatCode = getPayCatCode($mysqli,'PUBLIC HOLIDAY 2');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='PUBLIC HOLIDAY 2';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllHol2Units;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllHol2Pay;
    $payCatCode = getPayCatCode($mysqli,'HOLIDAY WITH SUPER');
    $allUnitsPayArray[$payCatCode]['code']='';
    $allUnitsPayArray[$payCatCode]['desc']='HOLIDAY WITH SUPER';
    $allUnitsPayArray[$payCatCode]['units']=$totalAllHolUnitsWithSuper;
    $allUnitsPayArray[$payCatCode]['amount']=$totalAllHolPayWithSuper;
    $allUnitsPayArray[9997]['code']='9997';
    $allUnitsPayArray[9997]['desc']='Net Wages';
    $allUnitsPayArray[9997]['units']=0;
    $allUnitsPayArray[9997]['amount']=$totalNetWages;
    $allUnitsPayArray[9999]['code']='9999';
    $allUnitsPayArray[9999]['desc']='PAYG Tax';
    $allUnitsPayArray[9999]['units']=0;
    $allUnitsPayArray[9999]['amount']='-'.round($totalTax);
    $candidateArray[$data['candidateId']]['clientId'] = $data['clientId'];
    $candidateArray[$data['candidateId']]['positionId'] = $data['positionId'];
    $candidateArray[$data['candidateId']]['emgTotal'] = $totalEMGUnits;
    $candidateArray[$data['candidateId']]['ordTotal'] = $totalDayUnits;
    $candidateArray[$data['candidateId']]['aftTotal'] = $totalAftUnits;
    $candidateArray[$data['candidateId']]['nightTotal'] = $totalNightUnits;
    $candidateArray[$data['candidateId']]['rdoTotal'] = $totalRDOUnits;
    $candidateArray[$data['candidateId']]['satTotal'] = $totalSatUnits;
    $candidateArray[$data['candidateId']]['sunTotal'] = $totalSunUnits;
    $candidateArray[$data['candidateId']]['ovtTotal'] = $totalOvtUnits;
    $candidateArray[$data['candidateId']]['satovtTotal'] = $totalSatOvtUnits;
    $candidateArray[$data['candidateId']]['sunovtTotal'] = $totalSunOvtUnits;
    $candidateArray[$data['candidateId']]['povtTotal'] = $totalPeriodOvtUnits;
    $candidateArray[$data['candidateId']]['dblTotal'] = $totalDblUnits;
    $candidateArray[$data['candidateId']]['hldTotal'] = $totalHolUnits;
    $candidateArray[$data['candidateId']]['hol_total'] = $totalHol2Units;
    if($canId == $data['candidateId']){
        $candidateArray[$data['candidateId']]['totalHours'] = (float)$candidateArray[$data['candidateId']]['totalHours'] + (float)$totalUnits;
    }else{
        $candidateArray[$data['candidateId']]['totalHours'] = $totalUnits;
    }
    $candidateArray[$data['candidateId']]['superFundCode'] = $superFundCode;
    $candidateArray[$data['candidateId']]['superFundDesc'] = getTransCodeDescByTransCode($mysqli,candidateSuperFundTransCode($mysqli,$data['candidateId']));
    $candidateArray[$data['candidateId']]['jobcode'] = getJobCodeByClientPosition($mysqli,$data['clientId'],$data['positionId'],$data['deptId']);
    $html = $html.'<tr class="zebra'.($i++ & 1).'" style="border-bottom: 2px solid black"><td class="cellCenter">' . $jobCode . '</td><td class="shortWidth">' . $transCode . '</td><td>' . $candidateSuperFundDesc . '</td><td class="shortWidth">1.00</td><td></td><td></td><td></td><td class="cellWidth" style="text-align: right">'. number_format($superAnnuation,2).'</td></tr>';
    $html = $html.'<tr class="zebra'.($i++ & 1).'" style="border-bottom: 2px solid black"><td class="cellCenter"></td><td class="shortWidth"></td><td>Unit Total</td><td class="shortWidth">'.$totalUnits.'</td><td></td><td></td><td></td><td class="cellWidth" style="text-align: right"></td></tr>';
    $finalUnits = $finalUnits + $totalUnits;
    //$html = $html.'<tr class="zebra'.($i++ & 1).'" style="border-bottom: 2px solid black"><td class="cellCenter"></td><td class="shortWidth"></td><td>QQQQQQQQQQQQQQQQQ</td><td class="shortWidth">'.$finalUnits.'</td><td></td><td></td><td></td><td class="cellWidth" style="text-align: right"></td></tr>';

    // $finalAmount = $gross;
    $finalNetAmount = $totalNetWages;
    $k++;
    /* save timesheet total records to temporary table for closing*/
    if($action == 'GENERATE') {
        saveTimesheetPayruns($mysqli, $data['totId'], $payRunId);
    }
}
$html = $html.'<tr class="zebra'.($i++ & 1).'" style="border-bottom: 2px solid black"><td class="cellCenter"></td><td class="shortWidth"></td><td class="subHeading boldFigure">Totals</td><td class="shortWidth boldFigure">'.number_format($finalUnits,2).'</td><td></td><td class="shortWidth boldFigure">'.number_format($finalAmount,2).'</td><td></td><td class="cellWidth" style="text-align: right"></td></tr>';
$fileName = 'payrollCalculationReport'.time();
$filePathPDF = './payrollcalculation/'.$fileName.'.pdf';
$html = $html.'</tbody></table>';
$html = $html.'<span class="filePath" data-filePathPDF="'.$filePathPDF.'">&nbsp;</span><br pagebreak="true"/>';
$pdf->writeHTML($html, true, false, false, false, '');
$lastPage = $pdf->getPage();
$pdf->deletePage($lastPage);
define('PDF_CUSTOM_HEADER_STRING','Week Ending Worked:'.$weekendingDate.'  User:'.$_SESSION['userSession'].'   Printed: '.date("Y-m-d H:i:s"));
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
$pdf->AddPage('P', 'A4');
$unitTbl = $unitTbl.'<style>
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
}
thead{
    background-color: #9EA8B1;
}
th{
    text-align: center;
    font-size: 8pt;
}
td.cellWidth{
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
    width: 40%;
}
.title{
    margin-top: 0;
    padding-top: 0;
    text-align: left;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 10pt;
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
}</style>';
$unitTbl = $unitTbl.'<div class="title">Pay Units</div>';
$unitTbl = $unitTbl.'<table class="unitTbl" border="1" width="100%"><thead>';
$unitTbl = $unitTbl.'<tr><th style="width: 20%">Code</th>';
$unitTbl = $unitTbl.'<th style="width: 40%">Description</th>';
$unitTbl = $unitTbl.'<th style="width: 20%">Pay Units</th>';
$unitTbl = $unitTbl.'<th style="width: 20%">Pay Amount</th>';
$unitTbl = $unitTbl.'</tr></thead>';
$unitTbl = $unitTbl.'<tbody>';

foreach($allUnitsPayArray as $key=>$value) {
    if(($allUnitsPayArray[$key]['amount']>0)) {//($allUnitsPayArray[$key]['amount']<>'') ||
        $unitTbl = $unitTbl . '<tr class="zebra'.($i++ & 1).'"><td class="cellCenter">'.$allUnitsPayArray[$key]['code'].'</td><td class="desc">'.$allUnitsPayArray[$key]['desc'].'</td><td class="cellWidth" style="text-align: right">'.number_format($allUnitsPayArray[$key]['units'],2).'</td><td class="cellWidth" style="text-align: right">';
        if($allUnitsPayArray[$key]['code'] == '2'){
            $unitTbl = $unitTbl.'-'.number_format($allUnitsPayArray[$key]['amount'],2).'</td></tr>';
        }else{
            $unitTbl = $unitTbl.number_format($allUnitsPayArray[$key]['amount'],2).'</td></tr>';
        }
    }
}
$unitTbl = $unitTbl.'</tbody></table>';
$pdf->writeHTML($unitTbl, true, false, false, false, '');
$pdf->AddPage('L', 'A4');
$hrsTbl = $hrsTbl.'<style>
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
    width: 20%;
}
td.boldFigure{
    text-align: right;
    font-weight: bold;
}
td.cellCenter{
    text-align: center;
    width: 25%;
}
td.shortWidth{
    text-align: right;
    width: 10%;
}
td.empId{
    text-align: left;
}
.title{
    margin-top: 0;
    padding-top: 0;
    text-align: left;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 10pt;
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
}</style>';
$hrsTbl = $hrsTbl.'<div class="title">Employee Hours/Gross/Tax/Net</div>';
$hrsTbl = $hrsTbl.'<table class="unitTbl" border="1" width="100%"><thead>';
$hrsTbl = $hrsTbl.'<tr><th style="width: 15%">EmployeeID</th>';
$hrsTbl = $hrsTbl.'<th style="width: 20%">Surname</th>';
$hrsTbl = $hrsTbl.'<th style="width: 20%">First Name</th>';
$hrsTbl = $hrsTbl.'<th style="width: 5%">Hours</th>';
$hrsTbl = $hrsTbl.'<th style="width: 10%">Gross</th>';
$hrsTbl = $hrsTbl.'<th style="width: 10%">Tax</th>';
$hrsTbl = $hrsTbl.'<th style="width: 10%">After Tax Total</th>';
$hrsTbl = $hrsTbl.'<th style="width: 10%">Net</th>';
$hrsTbl = $hrsTbl.'</tr></thead>';
$hrsTbl = $hrsTbl.'<tbody>';
foreach($candidateArray as $key=>$value){
    $hrsTbl = $hrsTbl.'<tr class="zebra'.($i++ & 1).'">
                            <td style="width: 15%">'.$key.'</td>
                            <td style="width: 20%">'.strtoupper(getCandidateLastNameByCandidateId($mysqli,$key)).'</td>
                            <td style="width: 20%">'.strtoupper(getCandidateFirstNameByCandidateId($mysqli,$key)).'</td>
                            <td  style="width: 5%; text-align: right" class="shortWidth">'.number_format($candidateArray[$key]['totalHours'],2).'</td>
                            <td class="shortWidth" style="text-align: right">'.number_format($candidateArray[$key]['totalGross'],2).'</td>
                            <td class="shortWidth" style="text-align: right">'.number_format($candidateArray[$key]['totalTax'],2).'</td>
                            <td class="shortWidth" style="text-align: right">'.number_format(($candidateArray[$key]['afterTaxAllowanceTotal'] + -($candidateArray[$key]['afterTaxDeductTotal'])),2).'</td>
                            <td class="shortWidth" style="text-align: right">'.number_format($candidateArray[$key]['net'],2).'</td></tr>';
    if($action == 'GENERATE') {
        savePayRun($mysqli, $payRunId, $weekendingDate, $key, $candidateArray[$key]['clientId'], $candidateArray[$key]['positionId'], 'Gross', 9, $candidateArray[$key]['jobcode'], 0, 0, 0.00, 0.00, 0.00, 0.00, $candidateArray[$key]['totalGross'], 0.00, 0.00, 0.00, 0.00);
        savePayRun($mysqli, $payRunId, $weekendingDate, $key, $candidateArray[$key]['clientId'], $candidateArray[$key]['positionId'], 'PAYG Tax', 11, $candidateArray[$key]['jobcode'], 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, $candidateArray[$key]['totalTax'], 0.00, 0.00);
        savePayRun($mysqli, $payRunId, $weekendingDate, $key, $candidateArray[$key]['clientId'], $candidateArray[$key]['positionId'], 'NetWages', 13, $candidateArray[$key]['jobcode'], 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, $candidateArray[$key]['net'], 0.00, 0.00, 0.00);
    }
}
$totalAfterTaxTotal = $totalAfterAllowanceTaxTotal - $totalAfterDeductionTotal;
$hrsTbl = $hrsTbl.'<tr class="zebra'.($i++ & 1).'">
                            <td style="width: 15%" class="subHeading boldFigure">Total Hours</td>
                            <td style="width: 20%"></td>
                            <td style="width: 20%"></td>
                            <td style="width: 5%; text-align: right" class="shortWidth boldFigure">'.number_format($finalUnits,2).'</td>
                            <td class="shortWidth boldFigure" style="text-align: right">'.number_format($finalAmount,2).'</td>
                            <td class="shortWidth boldFigure" style="text-align: right">'.number_format($totalTax,2).'</td>
                            <td class="shortWidth boldFigure" style="text-align: right">'.number_format($totalAfterTaxTotal,2).'</td>
                            <td class="shortWidth boldFigure" style="text-align: right">'.number_format($totalNetWages,2).'</td></tr>';

$hrsTbl = $hrsTbl.'</tbody></table>';
$pdf->writeHTML($hrsTbl, true, false, false, false, '');
$pdf->lastPage();
ob_clean();
if($action == 'GENERATE') {
    $pdf->Output(__DIR__ . '/payrollcalculation/' . $fileName . '.pdf', 'F');
    savePayrollReport($mysqli, $payRunId, $weekendingDate, $filePathPDF);
}
echo $html.$unitTbl.$hrsTbl;
?>