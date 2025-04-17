<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 24/08/2017
 * Time: 2:59 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";

$weekendingDate = $_POST['weekEndingDate'];
$payDate = $_POST['payDate'];
$timeSheetData = getTimeSheetTotals($mysqli,$weekendingDate);

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Swarnajith Fernando');
$pdf->SetTitle('TimeSheet Audit Report Calculations');
$pdf->SetSubject('Chandler TimeSheet Audit Report');
$pdf->SetKeywords('Chandler TimeSheet Audit Report');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

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
</style>
<table border="1" >
    <thead>
        <tr>
            <th>Week Ending Date</th>
            <th>Pay Date</th>
            <th class="thDesc">Employee ID</th>
            <th>T/S Code</th>
            <th class="thDesc">Description</th>
            <th>Units</th>
            <th>Pay Rate</th>
            <th>Amount</th>
            <th>Units</th>
            <th>Charge Rate</th>
            <th>Amount</th>
        </tr>    
    </thead>
    <tbody>';
$transCodeArray = createTransCodeArray($mysqli);


foreach($timeSheetData as $data) {

    $totalDayUnits;
    $totalAftUnits;
    $totalNightUnits;
    $totalOvertimeUnits;
    $totalDoubleTimeUnits;
    $totalSatUnits;
    $totalSunUnits;
    $totalHolidayUnits;

    $totalDaySuperAmount;
    $totalAftSuperAmount;
    $totalNightSuperAmount;
    $totalOvertimeSuperAmount;
    $totalDoubletimeSuperAmount;
    $totalSatSuperAmount;
    $totalSunSuperAmount;
    $totalHolidaySuperAmount;

    $totalDayPayAmount;
    $totalAftPayAmount;
    $totalNightPayAmount;
    $totalOvertimePayAmount;
    $totalDoubletimePayAmount;
    $totalSatPayAmount;
    $totalSunPayAmount;
    $totalHolidayPayAmount;

    $totalDayChargeAmount;
    $totalAftChargeAmount;
    $totalNightChargeAmount;
    $totalOvertimeChargeAmount;
    $totalDoubletimeChargeAmount;
    $totalSatChargeAmount;
    $totalSunChargeAmount;
    $totalHolidayChargeAmount;

    $superPayAmount = 0;
    $overallTotal = 0;

    $superCode;
    $superTypeCount = 1;
    $superFundDesc;

    $netWages = 0;
    $PAYGTax = 0;

    $candidateId = $data['candidateId'];
    $clientName = getClientNameByClientId($mysqli,$data['clientId']);
    $employeeName = getCandidateFirstNameByCandidateId($mysqli,$candidateId).' '.getCandidateLastNameByCandidateId($mysqli,$candidateId);
    $transCode = candidateSuperFundTransCode($mysqli,$candidateId);
    $candidateSuperFundDesc = getTransCodeDescByTransCode($mysqli,$transCode);
    $dayPayCatCode = getPayCatCode($mysqli,'DAY');
    $aftPayCatCode = getPayCatCode($mysqli,'AFTERNOON');
    $nightPayCatCode = getPayCatCode($mysqli,'NIGHT');
    $satPayCatCode = getPayCatCode($mysqli,'SATURDAY');
    $sunPayCatCode = getPayCatCode($mysqli,'SUNDAY');
    $overtimePayCatCode = getPayCatCode($mysqli,'OVERTIME');
    $doubletimePayCatCode = getPayCatCode($mysqli,'DOUBLETIME');
    $holidayPayCatCode = getPayCatCode($mysqli,'HOLIDAY');

    $html = $html.'<tr><td class="title">'.$clientName.'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
    $html = $html.'<tr><td class="title">'.$employeeName.'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

    if($data['ordTotal'].length>0){
        $dayTotal = number_format($data['ordTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$dayPayCatCode);
        $payTotal = calculatePayAmount($dayTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$dayPayCatCode);
        $chargeTotal = calculateChargeAmount($dayTotal,$chargeRate);

        $totalDayUnits = $totalDayUnits + $dayTotal;
        $totalDayPayAmount = $totalDayPayAmount + $payTotal;
        $totalDaySuperAmount = $totalDaySuperAmount + $superAnnuation;
        $totalDayChargeAmount = $totalDayChargeAmount + $chargeTotal;
        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }
        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'DAY SHIFT';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$dayTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$dayTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($dayTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($dayTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['aftTotal'].length>0){
        $aftTotal = number_format($data['aftTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$aftPayCatCode);
        $payTotal = calculatePayAmount($aftTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$aftPayCatCode);
        $chargeTotal = calculateChargeAmount($aftTotal,$chargeRate);

        $totalAftUnits = $totalAftUnits + $aftTotal;
        $totalAftPayAmount = $totalAftPayAmount + $payTotal;
        $totalAftSuperAmount = $totalAftSuperAmount + $superAnnuation;
        $totalAftChargeAmount = $totalAftChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'AFTERNOON SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$aftTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$aftTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($aftTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($aftTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['nightTotal'].length>0){
        $nightTotal = number_format($data['nightTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$nightPayCatCode);
        $payTotal = calculatePayAmount($nightTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$nightPayCatCode);
        $chargeTotal = calculateChargeAmount($nightTotal,$chargeRate);

        $totalNightUnits = $totalNightUnits + $nightTotal;
        $totalNightPayAmount = $totalNightPayAmount + $payTotal;
        $totalNightSuperAmount = $totalNightSuperAmount + $superAnnuation;
        $totalNightChargeAmount = $totalNightChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'NIGHT SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$nightTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$nightTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($nightTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($nightTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['satTotal'].length>0){
        $satTotal = number_format($data['satTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$satPayCatCode);
        $payTotal = calculatePayAmount($satTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$satPayCatCode);
        $chargeTotal = calculateChargeAmount($satTotal,$chargeRate);

        $totalSatUnits = $totalSatUnits + $satTotal;
        $totalSatPayAmount = $totalSatPayAmount + $payTotal;
        $totalSatSuperAmount = $totalSatSuperAmount + $superAnnuation;
        $totalSatChargeAmount = $totalSatChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'SATURDAY SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$satTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$satTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($satTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($satTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['sunTotal'].length>0){
        $sunTotal = number_format($data['sunTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$sunPayCatCode);
        $payTotal = calculatePayAmount($sunTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$sunPayCatCode);
        $chargeTotal = calculateChargeAmount($sunTotal,$chargeRate);

        $totalSunUnits = $totalSunUnits + $sunTotal;
        $totalSunPayAmount = $totalSunPayAmount + $payTotal;
        $totalSunSuperAmount = $totalSunSuperAmount + $superAnnuation;
        $totalSunChargeAmount = $totalSunChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'SUNDAY SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$sunTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$sunTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($sunTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($sunTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['ovtTotal'].length>0){
        $ovtTotal = number_format($data['ovtTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$overtimePayCatCode);
        $payTotal = calculatePayAmount($ovtTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$overtimePayCatCode);
        $chargeTotal = calculateChargeAmount($ovtTotal,$chargeRate);

        $totalOvertimeUnits = $totalOvertimeUnits + $ovtTotal;
        $totalOvertimePayAmount = $totalOvertimePayAmount + $payTotal;
        $totalOvertimeSuperAmount = $totalOvertimeSuperAmount + $superAnnuation;
        $totalOvertimeChargeAmount = $totalOvertimeChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'OVERTIME SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$ovtTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$ovtTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($ovtTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($ovtTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['dblTotal'].length>0){
        $dblTotal = number_format($data['dblTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$doubletimePayCatCode);
        $payTotal = calculatePayAmount($dblTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$doubletimePayCatCode);
        $chargeTotal = calculateChargeAmount($dblTotal,$chargeRate);

        $totalDoubleTimeUnits = $totalDoubleTimeUnits + $dblTotal;
        $totalDoubletimePayAmount = $totalDoubletimePayAmount + $payTotal;
        $totalDoubletimeSuperAmount = $totalDoubletimeSuperAmount + $superAnnuation;
        $totalDoubletimeChargeAmount = $totalDoubletimeChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'DOUBLE TIME SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$dblTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$dblTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($dblTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($dblTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    if($data['hldTotal'].length>0){
        $hldTotal = number_format($data['hldTotal'],2);
        $payRate = getPayRate($mysqli,$data['clientId'],$data['positionId'],$holidayPayCatCode);
        $payTotal = calculatePayAmount($hldTotal,$payRate);
        $superAnnuation = calculateSuperAnnuation($mysqli,$payTotal,$transCode);
        $chargeRate = getChargeRate($mysqli,$data['clientId'],$data['positionId'],$holidayPayCatCode);
        $chargeTotal = calculateChargeAmount($hldTotal,$chargeRate);

        $totalHolidayUnits= $totalHolidayUnits + $hldTotal;
        $totalHolidayPayAmount = $totalHolidayPayAmount + $payTotal;
        $totalHolidaySuperAmount = $totalHolidaySuperAmount + $superAnnuation;
        $totalHolidayChargeAmount = $totalHolidayChargeAmount + $chargeTotal;

        foreach ($transCodeArray as $key => $value) {
            if($key == $transCode){
                $transCodeArray[$key]['units'] = $value['units'] + 1;
                $transCodeArray[$key]['payAmount'] = $value['payAmount'] + $superAnnuation;
            }
        }

        $superCode = $transCode;
        $superFundDesc = $candidateSuperFundDesc;
        $superPayAmount = $superPayAmount + $superAnnuation;
        $overallTotal = $overallTotal + $payTotal;
        $html = $html.'<tr>';
        $html = $html.'<td>'.$weekendingDate.'</td>';
        $html = $html.'<td>'.$payDate.'</td>';
        $html = $html.'<td>'.$candidateId.'</td>';
        $html = $html.'<td></td>';
        $html = $html.'<td class="desc">';
        $html = $html.'HOLIDAY SHIFT (CASUAL)';
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$hldTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$payRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($payTotal,2);
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$hldTotal;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.$chargeRate;
        $html = $html.'</td>';
        $html = $html.'<td>';
        $html = $html.number_format($chargeTotal,2);
        $html = $html.'</td>';
        $html = $html.'</tr>';
        //$html = $html.'<tr><td></td><td></td><td></td><td>'.$transCode.'</td><td class="desc">'.$candidateSuperFundDesc.'</td><td></td><td></td><td>'.number_format($superAnnuation,2).'</td><td></td><td></td><td></td></tr>';
        $html = $html.'<tr class="totalRow">';
        $html = $html.'<td></td><td></td><td></td><td></td><td></td><td>'.number_format($hldTotal,2).'</td><td></td><td>'.number_format($payTotal,2).'</td><td>'.number_format($hldTotal,2).'</td><td></td><td>'.number_format($chargeTotal,2).'</td>';
        $html = $html.'</tr>';
    }
    $netWages = $overallTotal - $PAYGTax;
    $html = $html.'<tr><td></td><td></td><td></td><td></td><td class="desc">Net Wages</td><td></td><td></td><td>'.number_format($netWages,2).'</td><td></td><td></td><td></td></tr>';
    $html = $html.'<tr><td></td><td></td><td></td><td>'.$superCode.'</td><td class="desc">'.$superFundDesc.'</td><td>'.number_format($superTypeCount,2).'</td><td></td><td>'.number_format($superPayAmount,2).'</td><td></td><td></td><td></td></tr>';
    $html = $html.'<tr>';
    $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
    $html = $html.'</tr>';
}

$fileName = 'timesheetAuditReport_'.time().'.pdf';
$filePath = './auditreport/'.$fileName;
$html = $html.'</tbody></table><span class="filePath" data-filePath="'.$filePath.'">&nbsp;</span>';

$html = $html.'<div class="pageTitle">Time Sheet Audit Report Summary</div><table border="1"><thead><tr><th>Code</th><th>Description</th><th>Pay Units</th><th>Pay Amount</th><th>Bill Units</th><th>Bill Amount</th></tr></thead>';
$html = $html.'<tbody>';

if($totalDayUnits <>'' || $totalDayUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">DAY SHIFT</td><td>'.number_format($totalDayUnits,2).'</td><td>'.number_format($totalDayPayAmount,2).'</td><td>'.number_format($totalDayUnits,2).'</td><td>'.number_format($totalDayChargeAmount,2).'</td></tr>';
}
if ($totalAftUnits <>'' || $totalAftUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">AFTERNOON SHIFT</td><td>'.number_format($totalAftUnits,2).'</td><td>'.number_format($totalAftPayAmount,2).'</td><td>'.number_format($totalAftUnits,2).'</td><td>'.number_format($totalAftChargeAmount,2).'</td></tr>';
}
if ($totalNightUnits <>'' || $totalNightUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">NIGHT SHIFT</td><td>'.number_format($totalNightUnits,2).'</td><td>'.number_format($totalNightPayAmount,2).'</td><td>'.number_format($totalNightUnits,2).'</td><td>'.number_format($totalNightChargeAmount,2).'</td></tr>';
}
if ($totalOvertimeUnits <>'' || $totalOvertimeUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">OVERTIME SHIFT</td><td>'.number_format($totalOvertimeUnits,2).'</td><td>'.number_format($totalOvertimePayAmount,2).'</td><td>'.number_format($totalOvertimeUnits,2).'</td><td>'.number_format($totalOvertimeChargeAmount,2).'</td></tr>';
}
if ($totalDoubleTimeUnits <>'' || $totalDoubleTimeUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">DOUBLETIME SHIFT</td><td>'.number_format($totalDoubleTimeUnits,2).'</td><td>'.number_format($totalDoubletimePayAmount,2).'</td><td>'.number_format($totalDoubleTimeUnits,2).'</td><td>'.number_format($totalDoubletimeChargeAmount,2).'</td></tr>';
}
if ($totalSatUnits <>'' || $totalSatUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">SATURDAY SHIFT</td><td>'.number_format($totalSatUnits,2).'</td><td>'.number_format($totalSatPayAmount,2).'</td><td>'.number_format($totalSatUnits,2).'</td><td>'.number_format($totalSatChargeAmount,2).'</td></tr>';
}
if ($totalSunUnits <>'' || $totalSunUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">SUNDAY SHIFT</td><td>'.number_format($totalSunUnits,2).'</td><td>'.number_format($totalSunPayAmount,2).'</td><td>'.number_format($totalSunUnits,2).'</td><td>'.number_format($totalSunChargeAmount,2).'</td></tr>';
}
if ($totalHolidayUnits  <>'' || $totalHolidayUnits <> 0){
    $html = $html.'<tr><td></td><td class="desc">HOLIDAY SHIFT</td><td>'.number_format($totalHolidayUnits,2).'</td><td>'.number_format($totalHolidayPayAmount,2).'</td><td>'.number_format($totalHolidayUnits,2).'</td><td>'.number_format($totalHolidayChargeAmount,2).'</td></tr>';
}

foreach($transCodeArray as $pKey => $pValue){
    $html = $html.'<tr><td>'.$pKey.'</td><td class="desc">'.getTransCodeDescByTransCode($mysqli,$pKey).'</td><td>'.number_format($transCodeArray[$pKey]['units'],2).'</td><td>'.number_format($transCodeArray[$pKey]['payAmount'],2).'</td><td></td><td></td></tr>';
}

$html = $html.'</tbody></table>';
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$pdf->Output(__DIR__.'/auditreport/'.$fileName, 'F');

echo $html;
?>