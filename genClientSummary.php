<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$fromDate = $_REQUEST['weekEndingDateStart'];
$toDate = $_REQUEST['weekEndingDateEnd'];
$lastYearStartDate = $_REQUEST['lastYearStartDate'];
$lastYearEndDate = $_REQUEST['lastYearEndDate'];
$action = $_REQUEST['action'];

$boldArray = array('font' => array('bold' => true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFDAB9'))
);
$headerBackgroundArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFDAB9')));
$styleBorders = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000000'),
        ),
    ),
);
$dataArray = getClientSummaryData($mysqli, $fromDate, $toDate);
$clientId = '';
foreach ($dataArray as $data){
    if(empty($clientId)){
        $clientId = $data['clientId'];
        $summaryArray[$data['clientId']]['payUnits'] = $data['payUnits'];
        $summaryArray[$data['clientId']]['payAmount'] = $data['payAmount'];
        $summaryArray[$data['clientId']]['billUnits'] = $data['billUnits'];
        $summaryArray[$data['clientId']]['billAmount'] = $data['billAmount'];
        $summaryArray[$data['clientId']]['tax'] = $data['tax'];
        $summaryArray[$data['clientId']]['net'] = $data['net'];
        $summaryArray[$data['clientId']]['gross'] = $data['gross'];
        $summaryArray[$data['clientId']]['superUnits'] = $data['superUnits'];
        $summaryArray[$data['clientId']]['superAmount'] = $data['superAmount'];
        $summaryArray[$data['clientId']]['weekendingDate'] = $data['weekendingDate'];
        $summaryArray[$data['clientId']]['wic'] = $data['wic'];
    }elseif($clientId == $data['clientId']){
        $clientId = $data['clientId'];
        $summaryArray[$data['clientId']]['payUnits'] = $summaryArray[$data['clientId']]['payUnits'] + $data['payUnits'];
        $summaryArray[$data['clientId']]['payAmount'] = $summaryArray[$data['clientId']]['payAmount'] + $data['payAmount'];
        $summaryArray[$data['clientId']]['billUnits'] = $summaryArray[$data['clientId']]['billUnits'] + $data['billUnits'];
        $summaryArray[$data['clientId']]['billAmount'] = $summaryArray[$data['clientId']]['billAmount'] + $data['billAmount'];
        $summaryArray[$data['clientId']]['tax'] = $summaryArray[$data['clientId']]['tax'] + $data['tax'];
        $summaryArray[$data['clientId']]['net'] = $summaryArray[$data['clientId']]['net'] + $data['net'];
        $summaryArray[$data['clientId']]['gross'] = $summaryArray[$data['clientId']]['gross'] + $data['gross'];
        $summaryArray[$data['clientId']]['superUnits'] = $summaryArray[$data['clientId']]['superUnits'] + $data['superUnits'];
        $summaryArray[$data['clientId']]['superAmount'] = $summaryArray[$data['clientId']]['superAmount'] + $data['superAmount'];
        $summaryArray[$data['clientId']]['weekendingDate'] = $data['weekendingDate'];
        $summaryArray[$data['clientId']]['wic'] = $data['wic'];
    }elseif($clientId != $data['clientId']){
        $clientId = $data['clientId'];
        $summaryArray[$data['clientId']]['payUnits'] = $data['payUnits'];
        $summaryArray[$data['clientId']]['payAmount'] = $data['payAmount'];
        $summaryArray[$data['clientId']]['billUnits'] = $data['billUnits'];
        $summaryArray[$data['clientId']]['billAmount'] = $data['billAmount'];
        $summaryArray[$data['clientId']]['tax'] = $data['tax'];
        $summaryArray[$data['clientId']]['net'] = $data['net'];
        $summaryArray[$data['clientId']]['gross'] = $data['gross'];
        $summaryArray[$data['clientId']]['superUnits'] = $data['superUnits'];
        $summaryArray[$data['clientId']]['superAmount'] = $data['superAmount'];
        $summaryArray[$data['clientId']]['weekendingDate'] = $data['weekendingDate'];
        $summaryArray[$data['clientId']]['wic'] = $data['wic'];
    }
}



//$summaryArray[] = array('payUnits' => '', 'payAmount' => '', 'billUnits' => '', 'billAmount' => '', 'tax' => '', 'net' => '', 'gross' => '', 'superUnits' => '', 'superAmount' => '', 'weekendingDate' => '');
if($_REQUEST['action'] == 'LASTYEAR'){

    $lastYearDataArray = getClientSummaryData($mysqli, $lastYearStartDate, $lastYearEndDate);
    $lastYearClientId = '';
    foreach ($lastYearDataArray as $data){
        if(empty($lastYearClientId)){
            $lastYearClientId = $data['clientId'];
            $lastArray[$data['clientId']]['payUnits'] = $data['payUnits'];
            $lastArray[$data['clientId']]['payAmount'] = $data['payAmount'];
            $lastArray[$data['clientId']]['billUnits'] = $data['billUnits'];
            $lastArray[$data['clientId']]['billAmount'] = $data['billAmount'];
            $lastArray[$data['clientId']]['tax'] = $data['tax'];
            $lastArray[$data['clientId']]['net'] = $data['net'];
            $lastArray[$data['clientId']]['gross'] = $data['gross'];
            $lastArray[$data['clientId']]['superUnits'] = $data['superUnits'];
            $lastArray[$data['clientId']]['superAmount'] = $data['superAmount'];
            $lastArray[$data['clientId']]['weekendingDate'] = $data['weekendingDate'];
            $lastArray[$data['clientId']]['wic'] = $data['wic'];
        }elseif($lastYearClientId == $data['clientId']){
            $lastYearClientId = $data['clientId'];
            $lastArray[$data['clientId']]['payUnits'] = $lastArray[$data['clientId']]['payUnits'] + $data['payUnits'];
            $lastArray[$data['clientId']]['payAmount'] = $lastArray[$data['clientId']]['payAmount'] + $data['payAmount'];
            $lastArray[$data['clientId']]['billUnits'] = $lastArray[$data['clientId']]['billUnits'] + $data['billUnits'];
            $lastArray[$data['clientId']]['billAmount'] = $lastArray[$data['clientId']]['billAmount'] + $data['billAmount'];
            $lastArray[$data['clientId']]['tax'] = $lastArray[$data['clientId']]['tax'] + $data['tax'];
            $lastArray[$data['clientId']]['net'] = $lastArray[$data['clientId']]['net'] + $data['net'];
            $lastArray[$data['clientId']]['gross'] = $lastArray[$data['clientId']]['gross'] + $data['gross'];
            $lastArray[$data['clientId']]['superUnits'] = $lastArray[$data['clientId']]['superUnits'] + $data['superUnits'];
            $lastArray[$data['clientId']]['superAmount'] = $lastArray[$data['clientId']]['superAmount'] + $data['superAmount'];
            $lastArray[$data['clientId']]['weekendingDate'] = $data['weekendingDate'];
            $lastArray[$data['clientId']]['wic'] = $data['wic'];
        }elseif($lastYearClientId != $data['clientId']){
            $lastYearClientId = $data['clientId'];
            $lastArray[$data['clientId']]['payUnits'] = $data['payUnits'];
            $lastArray[$data['clientId']]['payAmount'] = $data['payAmount'];
            $lastArray[$data['clientId']]['billUnits'] = $data['billUnits'];
            $lastArray[$data['clientId']]['billAmount'] = $data['billAmount'];
            $lastArray[$data['clientId']]['tax'] = $data['tax'];
            $lastArray[$data['clientId']]['net'] = $data['net'];
            $lastArray[$data['clientId']]['gross'] = $data['gross'];
            $lastArray[$data['clientId']]['superUnits'] = $data['superUnits'];
            $lastArray[$data['clientId']]['superAmount'] = $data['superAmount'];
            $lastArray[$data['clientId']]['weekendingDate'] = $data['weekendingDate'];
            $lastArray[$data['clientId']]['wic'] = $data['wic'];
        }
    }

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
    $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('A1:E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CURRENT YEAR RECORDS');

    $objPHPExcel->getActiveSheet()->setCellValue('A2', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('B2', 'BILL UNITS');
    $objPHPExcel->getActiveSheet()->setCellValue('C2', 'BILL AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('D2', 'GP');
    $objPHPExcel->getActiveSheet()->setCellValue('E2', '%');

    $objPHPExcel->getActiveSheet()->setTitle('Client Summary Comparison');

    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $rowCount = 2;
    $totalPayUnits = 0;
    $totalPayAmount = 0;
    $totalBillUnits = 0;
    $totalBillAmount = 0;
    $totalTax = 0;
    $totalNet = 0;
    $totalGross = 0;
    $totalSuperUnits = 0;
    $totalSuperAmount = 0;
    $totalNumberOfCandidates = 0;
    $totalPayrollTax = 0;
    $totalPayrollTaxAmount = 0;
    $totalWorkCover = 0;
    $totalWorkCoverAmount = 0;
    $totalTotalCost = 0;
    $totalGP = 0;
    foreach ($summaryArray as $key => $value) {
        $rowCount++;

        $payrolltax = getClientPayrollTax($mysqli, $key);
        $workcover = getClientWorkcover($mysqli, $key);
        $payrolltaxAmount = calculatePayrollTaxAmount($value['payAmount'], $value['superAmount'], $payrolltax);
        $workcoverAmount = calculateWorkCoverAmount($value['payAmount'], $value['superAmount'], $workcover);
        $totalCost = ($value['payAmount'] + $value['superAmount'] + $payrolltaxAmount + $workcoverAmount);
        if ($value['billAmount'] > 0) {
            $gp = ($value['billAmount'] - $totalCost);
            $percentage = (($gp / $totalCost) * 100);
        } else {
            $gp = 0;
            $percentage = 0;
        }
        $numCandidates = getNumberOfCandidatesPerClient($mysqli, $fromDate, $toDate, $key);
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, getClientNameByClientId($mysqli, $key));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, number_format($value['billUnits'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, number_format($value['billAmount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, number_format($gp, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, number_format($percentage, 2, '.', ''));

        $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleBorders);

        $totalPayUnits = $totalPayUnits + $value['payUnits'];
        $totalPayAmount = $totalPayAmount + $value['payAmount'];
        $totalBillUnits = $totalBillUnits + $value['billUnits'];
        $totalBillAmount = $totalBillAmount + $value['billAmount'];
        $totalTax = $totalTax + $value['tax'];
        $totalNet = $totalNet + $value['net'];
        $totalGross = $totalGross + $value['gross'];
        $totalSuperUnits = $totalSuperUnits + $value['superUnits'];
        $totalSuperAmount = $totalSuperAmount + $value['superAmount'];
        $totalNumberOfCandidates = $totalNumberOfCandidates + $numCandidates;
        $totalPayrollTax = $totalPayrollTax + $payrolltax;
        $totalPayrollTaxAmount = $totalPayrollTaxAmount + $payrolltaxAmount;
        $totalWorkCover = $totalWorkCover + $workcover;
        $totalWorkCoverAmount = $totalWorkCoverAmount + $workcoverAmount;
        $totalTotalCost = $totalTotalCost + $totalCost;
        $totalGP = $totalGP + $gp;
    }

    $lastRow = $rowCount + 1;
    if ($totalBillAmount > 0) {
        $total_gp = ($totalBillAmount - $totalTotalCost);
        $total_percentage = (($total_gp / $totalTotalCost) * 100);
    } else {
        $total_gp = 0;
        $total_percentage = 0;
    }
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $lastRow, number_format($totalBillUnits, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $lastRow, number_format($totalBillAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $lastRow, number_format($totalGP, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $lastRow, number_format($total_percentage, 2, '.', ''));

    $objPHPExcel->getActiveSheet()->getStyle('B'.$lastRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('C'.$lastRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('D'.$lastRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('E'.$lastRow)->applyFromArray($styleBorders);

    /* ---------------- last year records start  -------------------------*/
    $lastRowTitle = 1;//$lastRow+4;
    $objPHPExcel->getActiveSheet()->mergeCells('G'.$lastRowTitle.':K'.$lastRowTitle);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$lastRowTitle.':K'.$lastRowTitle)->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$lastRowTitle.':K'.$lastRowTitle)->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$lastRowTitle.':K'.$lastRowTitle)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$lastRowTitle)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$lastRowTitle, 'LAST YEAR RECORDS - ('.$lastYearStartDate.' TO '.$lastYearEndDate.')');

    $pRow = $lastRowTitle + 1;

    $objPHPExcel->getActiveSheet()->setCellValue('G'.$pRow, 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$pRow, 'BILL UNITS');
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$pRow, 'BILL AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$pRow, 'GP');
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$pRow, '%');
    $objPHPExcel->getActiveSheet()->getStyle('G'.$pRow)->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$pRow)->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('G'.$pRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$pRow)->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$pRow)->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$pRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('I'.$pRow)->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('I'.$pRow)->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('I'.$pRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('J'.$pRow)->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('J'.$pRow)->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('J'.$pRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$pRow)->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$pRow)->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$pRow)->applyFromArray($styleBorders);

    $rowCount = $pRow;
    $totalPayUnits = 0;
    $totalPayAmount = 0;
    $totalBillUnits = 0;
    $totalBillAmount = 0;
    $totalTax = 0;
    $totalNet = 0;
    $totalGross = 0;
    $totalSuperUnits = 0;
    $totalSuperAmount = 0;
    $totalNumberOfCandidates = 0;
    $totalPayrollTax = 0;
    $totalPayrollTaxAmount = 0;
    $totalWorkCover = 0;
    $totalWorkCoverAmount = 0;
    $totalTotalCost = 0;
    $totalGP = 0;
    foreach ($lastArray as $key => $value) {
        $rowCount++;
        $payrolltax = getClientPayrollTax($mysqli, $key);
        $workcover = getClientWorkcover($mysqli, $key);
        $payrolltaxAmount = calculatePayrollTaxAmount($value['payAmount'], $value['superAmount'], $payrolltax);
        $workcoverAmount = calculateWorkCoverAmount($value['payAmount'], $value['superAmount'], $workcover);
        $totalCost = ($value['payAmount'] + $value['superAmount'] + $payrolltaxAmount + $workcoverAmount);
        if ($value['billAmount'] > 0) {
            $gp = ($value['billAmount'] - $totalCost);
            $percentage = (($gp / $totalCost) * 100);
        } else {
            $gp = 0;
            $percentage = 0;
        }
        $numCandidates = getNumberOfCandidatesPerClient($mysqli, $lastYearStartDate, $lastYearEndDate, $key);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, getClientNameByClientId($mysqli, $key));
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, number_format($value['billUnits'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, number_format($value['billAmount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, number_format($gp, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, number_format($percentage, 2, '.', ''));

        $objPHPExcel->getActiveSheet()->getStyle('G'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('H'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('I'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('J'.$rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('K'.$rowCount)->applyFromArray($styleBorders);

        $totalPayUnits = $totalPayUnits + $value['payUnits'];
        $totalPayAmount = $totalPayAmount + $value['payAmount'];
        $totalBillUnits = $totalBillUnits + $value['billUnits'];
        $totalBillAmount = $totalBillAmount + $value['billAmount'];
        $totalTax = $totalTax + $value['tax'];
        $totalNet = $totalNet + $value['net'];
        $totalGross = $totalGross + $value['gross'];
        $totalSuperUnits = $totalSuperUnits + $value['superUnits'];
        $totalSuperAmount = $totalSuperAmount + $value['superAmount'];
        $totalNumberOfCandidates = $totalNumberOfCandidates + $numCandidates;
        $totalPayrollTax = $totalPayrollTax + $payrolltax;
        $totalPayrollTaxAmount = $totalPayrollTaxAmount + $payrolltaxAmount;
        $totalWorkCover = $totalWorkCover + $workcover;
        $totalWorkCoverAmount = $totalWorkCoverAmount + $workcoverAmount;
        $totalTotalCost = $totalTotalCost + $totalCost;
        $totalGP = $totalGP + $gp;
    }
    $lastPRow = $rowCount + 1;

    if ($totalBillAmount > 0) {
        $totalgp = ($totalBillAmount - $totalTotalCost);
        $totalPercentage = (($totalgp / $totalTotalCost) * 100);
    } else {
        $totalgp = 0;
        $totalPercentage = 0;
    }

    $objPHPExcel->getActiveSheet()->setCellValue('H' . $lastPRow, number_format($totalBillUnits, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $lastPRow, number_format($totalBillAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $lastPRow, number_format($totalGP, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $lastPRow, number_format($totalPercentage, 2, '.', ''));


    $objPHPExcel->getActiveSheet()->getStyle('G'.$lastPRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('H'.$lastPRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('I'.$lastPRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('J'.$lastPRow)->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('K'.$lastPRow)->applyFromArray($styleBorders);
    /*--------------------------- last year records end  -------------------------------*/

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('./reports/clientSummaryLastYearReport.xlsx');
    echo './reports/clientSummaryLastYearReport.xlsx';
}else {
    $objPHPExcel = new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'PAY UNITS');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'PAY AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'BILL UNITS');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'BILL AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'TAX');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'NET');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'GROSS');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'SUPER UNITS');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'SUPER AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'NUMBER OF CANDIDATES');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'PT %');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'PT AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'WC %');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'WC AMOUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'TOTAL COST');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'GP');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', '%');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', 'SALESMAN');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', 'WIC');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', 'WIC_CODE');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', 'WIC_RATE');

    $objPHPExcel->getActiveSheet()->setTitle('Client Summary Report');

    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('G1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('H1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('I1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('J1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('J1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('K1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('L1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('M1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('O1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('S1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('T1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('U1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('U1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('V1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('V1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $rowCount = 1;
    $totalPayUnits = 0;
    $totalPayAmount = 0;
    $totalBillUnits = 0;
    $totalBillAmount = 0;
    $totalTax = 0;
    $totalNet = 0;
    $totalGross = 0;
    $totalSuperUnits = 0;
    $totalSuperAmount = 0;
    $totalNumberOfCandidates = 0;
    $totalPayrollTax = 0;
    $totalPayrollTaxAmount = 0;
    $totalWorkCover = 0;
    $totalWorkCoverAmount = 0;
    $totalTotalCost = 0;
    $totalGP = 0;
    foreach ($summaryArray as $key => $value) {
        $rowCount++;

        $payrolltax = getClientPayrollTax($mysqli, $key);
        $workcover = getClientWorkcover($mysqli, $key);
        $payrolltaxAmount = calculatePayrollTaxAmount($value['payAmount'], $value['superAmount'], $payrolltax);
        $workcoverAmount = calculateWorkCoverAmount($value['payAmount'], $value['superAmount'], $workcover);
        $totalCost = ($value['payAmount'] + $value['superAmount'] + $payrolltaxAmount + $workcoverAmount);
        if ($value['billAmount'] > 0) {
            $gp = ($value['billAmount'] - $totalCost);
            $percentage = (($gp / $totalCost) * 100);
        } else {
            $gp = 0;
            $percentage = 0;
        }
        $numCandidates = getNumberOfCandidatesPerClient($mysqli, $fromDate, $toDate, $key);
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, getClientNameByClientId($mysqli, $key));
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, number_format($value['payUnits'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, number_format($value['payAmount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, number_format($value['billUnits'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, number_format($value['billAmount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, number_format($value['tax'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, number_format($value['net'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, number_format($value['gross'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $value['superUnits']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, number_format($value['superAmount'], 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $numCandidates);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $payrolltax);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, number_format($payrolltaxAmount, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $workcover);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, number_format($workcoverAmount, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, number_format($totalCost, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, number_format($gp, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, number_format($percentage, 2, '.', ''));
        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, getSalesmanByClient($mysqli, $key));
        $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, getWorkcoverClassificationByCode($mysqli, $value['wic']));
        $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, $value['wic']);
        $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, getWorkCoverClassificationRateByCode($mysqli, $value['wic']));

        $totalPayUnits = $totalPayUnits + $value['payUnits'];
        $totalPayAmount = $totalPayAmount + $value['payAmount'];
        $totalBillUnits = $totalBillUnits + $value['billUnits'];
        $totalBillAmount = $totalBillAmount + $value['billAmount'];
        $totalTax = $totalTax + $value['tax'];
        $totalNet = $totalNet + $value['net'];
        $totalGross = $totalGross + $value['gross'];
        $totalSuperUnits = $totalSuperUnits + $value['superUnits'];
        $totalSuperAmount = $totalSuperAmount + $value['superAmount'];
        $totalNumberOfCandidates = $totalNumberOfCandidates + $numCandidates;
        $totalPayrollTax = $totalPayrollTax + $payrolltax;
        $totalPayrollTaxAmount = $totalPayrollTaxAmount + $payrolltaxAmount;
        $totalWorkCover = $totalWorkCover + $workcover;
        $totalWorkCoverAmount = $totalWorkCoverAmount + $workcoverAmount;
        $totalTotalCost = $totalTotalCost + $totalCost;
        $totalGP = $totalGP + $gp;
    }

    $lastRow = $rowCount + 1;

    $objPHPExcel->getActiveSheet()->setCellValue('B' . $lastRow, number_format($totalPayUnits, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $lastRow, number_format($totalPayAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $lastRow, number_format($totalBillUnits, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $lastRow, number_format($totalBillAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $lastRow, number_format($totalTax, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $lastRow, number_format($totalNet, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $lastRow, number_format($totalGross, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $lastRow, number_format($totalSuperUnits, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $lastRow, number_format($totalSuperAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $lastRow, number_format($totalNumberOfCandidates, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $lastRow, number_format($totalPayrollTax, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $lastRow, number_format($totalPayrollTaxAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $lastRow, number_format($totalWorkCover, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $lastRow, number_format($totalWorkCoverAmount, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $lastRow, number_format($totalTotalCost, 2, '.', ''));
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $lastRow, number_format($totalGP, 2, '.', ''));

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('./reports/clientSummaryReport.xlsx');
    echo './reports/clientSummaryReport.xlsx';
}