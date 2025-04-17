<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('max_execution_time', 0);
set_time_limit(0);
ini_set('memory_limit', '800M');

$startWkDate = $_POST['startWkDate'];
$endWkDate = $_POST['endWkDate'];
$rateYear = $_POST['rateYear'];
$clId = $_POST['clientId'];
$empId = $_POST['empId'];
$action = $_REQUEST['action'];

if($action == 'CLIENT') {
    if ($clId == 'All') {
        $paysheeetData = getPaysheetData($mysqli, $startWkDate, $endWkDate);
    }else {
        $paysheeetData = getClientBasedPaysheetData($mysqli, $startWkDate, $endWkDate, $clId);
    }
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
    $totalsBorder = array('font' => array('bold' => true),
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => '000000'),
            ),
        ),
    );
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->getActiveSheet()->mergeCells('A1:A2');
    $objPHPExcel->getActiveSheet()->mergeCells('B1:B2');
    $objPHPExcel->getActiveSheet()->mergeCells('C1:C2');
    $objPHPExcel->getActiveSheet()->mergeCells('D1:D2');
    $objPHPExcel->getActiveSheet()->mergeCells('E1:P1');
    $objPHPExcel->getActiveSheet()->mergeCells('Q1:AA1');
    $objPHPExcel->getActiveSheet()->mergeCells('AB1:AB2');
    $objPHPExcel->getActiveSheet()->mergeCells('AN1:AN2');
    $objPHPExcel->getActiveSheet()->mergeCells('AC1:AM1');
    $objPHPExcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('B1:B2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('C1:C2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('D1:D2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('E1:Q1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('Q1:Y1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AA1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AM1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AC1:AN1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('S1:S2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('O1:O2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('I2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('J2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('K2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('L2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('M2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('N2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('O2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('P2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('R2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('S2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('T2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('U2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('V2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('W2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('X2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('Y2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('Z2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AA2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AB2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AC2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AD2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AE2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AF2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AG2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AH2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AI2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AJ2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AK2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AL2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AM2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AN2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AO2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AP2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AQ2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AR2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AS2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AT2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AU2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AV2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AW2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AX2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AY2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('AZ2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('BA2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->getStyle('BB2')->applyFromArray($styleBorders);

    $objPHPExcel->getActiveSheet()->getCell('A1')->setValue('Level/Position');
    $objPHPExcel->getActiveSheet()->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('T1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('E1:L1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('E2:O2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('Q1:AA1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('P2:Y2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('AC1:AM1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('Z2:AN2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('AA1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AA1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('AB1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AB1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('AN1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AN1')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('AO1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AO2:BB2')->applyFromArray($headerBackgroundArray);
    $objPHPExcel->getActiveSheet()->getStyle('AP1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AQ1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AR1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AS1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AT1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AU1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AV1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AW1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AX1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AY1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('AZ1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('BA1')->applyFromArray($boldArray);
    $objPHPExcel->getActiveSheet()->getStyle('BB1')->applyFromArray($boldArray);

    $objPHPExcel->getActiveSheet()->getCell('B1')->setValue('Candidate Name');
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('C1')->setValue('Candidate ID');
    $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('C1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('D1')->setValue('Date(WE)');
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('D1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('E1')->setValue('HOURS');
    $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('E1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->getCell('Q1')->setValue('PAY RATES');
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('Q1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AB1')->setValue('Pay Rate(exl. GST)');
    $objPHPExcel->getActiveSheet()->getStyle('AB1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AB1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->getCell('AC1')->setValue('CHARGE RATES');
    $objPHPExcel->getActiveSheet()->getStyle('AC1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AC1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AN1')->setValue('Charge Rate(exl. GST)');
    $objPHPExcel->getActiveSheet()->getStyle('AN1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AN1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->getCell('AO1')->setValue('CLIENT');
    $objPHPExcel->getActiveSheet()->getStyle('AO1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AO1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AP1')->setValue('JOBCODE');
    $objPHPExcel->getActiveSheet()->getStyle('AP1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AP1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AQ1')->setValue('WORK START WEEK');
    $objPHPExcel->getActiveSheet()->getStyle('AQ1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AQ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AR1')->setValue('CONSULTANT');
    $objPHPExcel->getActiveSheet()->getStyle('AR1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AR1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AS1')->setValue('VISATYPE');
    $objPHPExcel->getActiveSheet()->getStyle('AS1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AS1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AT1')->setValue('FOUND HOW');
    $objPHPExcel->getActiveSheet()->getStyle('AT1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AT1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AU1')->setValue('COVID VACCINATION 1');
    $objPHPExcel->getActiveSheet()->getStyle('AU1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AU1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AV1')->setValue('COVID VACCINATION 2');
    $objPHPExcel->getActiveSheet()->getStyle('AV1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AV1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AW1')->setValue('COVID VACCINATION 3');
    $objPHPExcel->getActiveSheet()->getStyle('AW1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AW1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AX1')->setValue('WORK COVER');
    $objPHPExcel->getActiveSheet()->getStyle('AX1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AX1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AY1')->setValue('PAYROLL TAX');
    $objPHPExcel->getActiveSheet()->getStyle('AY1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AY1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
    $objPHPExcel->getActiveSheet()->getCell('AZ1')->setValue('SUPER AMOUNT');
    $objPHPExcel->getActiveSheet()->getStyle('AZ1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('AZ1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

    $objPHPExcel->getActiveSheet()->getStyle('C2:D2:E2:F2:G2:H2:I2:J2:K2:L2:O2:P2:Q2:R2:S2:T2:U2:V2:W2:X2:Y2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('M2:N2:O2:P2:Q2:S2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('T2:U2:V2:W2:X2:Y2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('E2', 'Day Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('F2', 'Early Morning Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('G2', 'Afternoon Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('H2', 'Night Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('I2', 'Saturday Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('J2', 'Sunday Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('K2', 'Overtime');
    $objPHPExcel->getActiveSheet()->setCellValue('L2', 'Doubletime');
    $objPHPExcel->getActiveSheet()->setCellValue('M2', 'RDO');
    $objPHPExcel->getActiveSheet()->setCellValue('N2', 'Holiday Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('O2', 'Meal Allowance');
    $objPHPExcel->getActiveSheet()->setCellValue('P2', 'Total');
    $objPHPExcel->getActiveSheet()->setCellValue('Q2', 'Day Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('R2', 'Early Morning Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('S2', 'Afternoon Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('T2', 'Night Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('U2', 'Saturday Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('V2', 'Sunday Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('W2', 'Overtime');
    $objPHPExcel->getActiveSheet()->setCellValue('X2', 'Doubletime');
    $objPHPExcel->getActiveSheet()->setCellValue('Y2', 'RDO');
    $objPHPExcel->getActiveSheet()->setCellValue('Z2', 'Holiday Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('AA2', 'Meal Allowance');
    $objPHPExcel->getActiveSheet()->setCellValue('AB2', '');

    $objPHPExcel->getActiveSheet()->setCellValue('AC2', 'Day Shift Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AD2', 'Early Morning Shift Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AE2', 'Afternoon Shift Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AF2', 'Night Shift Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AG2', 'Saturday Shift Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AH2', 'Sunday Shift Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AI2', 'Overtime Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AJ2', 'Doubletime Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AK2', 'RDO Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AL2', 'Holiday Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('AM2', 'Meal Allowance Charge Rate');
    $objPHPExcel->getActiveSheet()->setCellValue('BA2', 'Police Clearance');
    $objPHPExcel->getActiveSheet()->setCellValue('BB2', 'Police Check Deduction');
    $objPHPExcel->getActiveSheet()->setTitle('PAY SHEET REPORT');
    $rowCount = 2;

    $clientId = '';
    $candidateId = '';
    $positionId = '';
    $jobCode = '';
    $emgTotal = 0.00;
    $ordTotal = 0.00;
    $aftTotal = 0.00;
    $nightTotal = 0.00;
    $rdoTotal = 0.00;
    $satTotal = 0.00;
    $sunTotal = 0.00;
    $ovtTotal = 0.00;
    $dblTotal = 0.00;
    $hldTotal = 0.00;
    $satovtTotal = 0.00;
    $sunovtTotal = 0.00;
    $povtTotal = 0.00;
    $allHoursTotal = 0.00;
    $chargeTotal = 0.00;
    $allChargeTotal = 0.00;
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
    $hldSuperPayCatCode = getPayCatCode($mysqli, 'HOLIDAY WITH SUPER');
    $satSuperPayCatCode = getPayCatCode($mysqli, 'SATURDAY WITH SUPER');
    $sunSuperPayCatCode = getPayCatCode($mysqli, 'SUNDAY WITH SUPER');

    foreach ($paysheeetData as $data) {
        $rowCount++;
        $transCode = candidateSuperFundTransCode($mysqli, $data['candidateId']);
        $ordTotal = $ordTotal + $data['ordTotal'];
        $emgTotal = $emgTotal + $data['emgTotal'];
        $aftTotal = $aftTotal + $data['aftTotal'];
        $nightTotal = $nightTotal + $data['nightTotal'];
        $rdoTotal = $rdoTotal + $data['rdoTotal'];
        $satTotal = $satTotal + $data['satTotal'];
        $sunTotal = $sunTotal + $data['sunTotal'];
        $ovtTotal = $ovtTotal + $data['ovtTotal'];
        $dblTotal = $dblTotal + $data['dblTotal'];
        $hldTotal = $hldTotal + $data['hldTotal'];
        $satovtTotal = $satovtTotal + $data['satovtTotal'];
        $sunovtTotal = $sunovtTotal + $data['sunovtTotal'];
        $povtTotal = $povtTotal + $data['povtTotal'];

        $fullName = getCandidateFullName($mysqli, $data['candidateId']) . '(' . getNickNameById($mysqli, $data['candidateId']) . ')';
        $position = getPositionByPositionId($mysqli, $data['positionId']);
        $totalHours = $data['ordTotal'] + $data['emgTotal'] + $data['aftTotal'] + $data['nightTotal'] + $data['rdoTotal'] + $data['satTotal'] + $data['sunTotal'] + $data['ovtTotal'] + $data['dblTotal'] + $data['hldTotal'] + $data['satovtTotal'] + $data['sunovtTotal'] + $data['povtTotal'];
        if (!empty($rateYear)) {
            $dayPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode,$data['jobCode'], $rateYear);
            $dayChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode,$data['jobCode'], $rateYear);

            $emgPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode,$data['jobCode'], $rateYear);
            $emgChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode,$data['jobCode'], $rateYear);

            $aftPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobCode'], $rateYear);
            $aftChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobCode'], $rateYear);

            $nightPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobCode'], $rateYear);
            $nightChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobCode'], $rateYear);

            $rdoPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobCode'], $rateYear);
            $rdoChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobCode'], $rateYear);

            $satPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobCode'], $rateYear);
            $satChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobCode'], $rateYear);

            $sunPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobCode'], $rateYear);
            $sunChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobCode'], $rateYear);

            $ovtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobCode'], $rateYear);
            $ovtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobCode'], $rateYear);

            $dblPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobCode'], $rateYear);
            $dblChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobCode'], $rateYear);

            $hldPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobCode'], $rateYear);
            $hldChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobCode'], $rateYear);

            $satovtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobCode'], $rateYear);
            $satovtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobCode'], $rateYear);

            $sunovtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobCode'], $rateYear);
            $sunovtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobCode'], $rateYear);

            $povtPayRate = getPayRateByYear($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobCode'], $rateYear);
            $povtChargeRate = getChargeRateByYear($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobCode'], $rateYear);
        } else {
            $dayPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode,$data['jobCode']);
            $dayChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $dayPayCatCode,$data['jobCode']);

            $emgPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode,$data['jobCode']);
            $emgChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $emgPayCatCode,$data['jobCode']);

            $aftPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobCode']);
            $aftChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $aftPayCatCode,$data['jobCode']);

            $nightPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobCode']);
            $nightChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $nightPayCatCode,$data['jobCode']);

            $rdoPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobCode']);
            $rdoChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $rdoPayCatCode,$data['jobCode']);

            $satPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobCode']);
            $satChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $satPayCatCode,$data['jobCode']);

            $sunPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobCode']);
            $sunChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $sunPayCatCode,$data['jobCode']);

            $ovtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobCode']);
            $ovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $overtimePayCatCode,$data['jobCode']);

            $dblPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobCode']);
            $dblChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $doubletimePayCatCode,$data['jobCode']);

            $hldPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobCode']);
            $hldChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $holidayPayCatCode,$data['jobCode']);

            $satovtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobCode']);
            $satovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $satOvertimePayCatCode,$data['jobCode']);

            $sunovtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobCode']);
            $sunovtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $sunOvertimePayCatCode,$data['jobCode']);

            $povtPayRate = getPayRate($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobCode']);
            $povtChargeRate = getChargeRate($mysqli, $data['clientId'], $data['positionId'], $periodOvertimePayCatCode,$data['jobCode']);
        }

        $dayPayTotal = calculatePayAmount($data['ordTotal'], $dayPayRate);
        $dayChargeTotal = calculateChargeAmount($data['ordTotal'], $dayChargeRate);

        $nightPayTotal = calculatePayAmount($data['nightTotal'], $nightPayRate);
        $nightChargeTotal = calculateChargeAmount($data['nightTotal'], $nightChargeRate);

        $rdoPayTotal = calculatePayAmount($data['rdoTotal'], $rdoPayRate);
        $rdoChargeTotal = calculateChargeAmount($data['rdoTotal'], $rdoChargeRate);

        $emgPayTotal = calculatePayAmount($data['emgTotal'], $emgPayRate);
        $emgChargeTotal = calculateChargeAmount($data['emgTotal'], $emgChargeRate);

        $aftPayTotal = calculatePayAmount($data['aftTotal'], $aftPayRate);
        $aftChargeTotal = calculateChargeAmount($data['aftTotal'], $aftChargeRate);

        $satPayTotal = calculatePayAmount($data['satTotal'], $satPayRate);
        $satChargeTotal = calculateChargeAmount($data['satTotal'], $satChargeRate);

        $sunPayTotal = calculatePayAmount($data['sunTotal'], $sunPayRate);
        $sunChargeTotal = calculateChargeAmount($data['sunTotal'], $sunChargeRate);

        $ovtPayTotal = calculatePayAmount($data['ovtTotal'], $ovtPayRate);
        $ovtChargeTotal = calculateChargeAmount($data['ovtTotal'], $ovtChargeRate);

        $dblPayTotal = calculatePayAmount($data['dblTotal'], $dblPayRate);
        $dblChargeTotal = calculateChargeAmount($data['dblTotal'], $dblChargeRate);

        $hldPayTotal = calculatePayAmount($data['hldTotal'], $hldPayRate);
        $hldChargeTotal = calculateChargeAmount($data['hldTotal'], $hldChargeRate);

        $satovtPayTotal = calculatePayAmount($data['satovtTotal'], $satovtPayRate);
        $satovtChargeTotal = calculateChargeAmount($data['satovtTotal'], $satovtChargeRate);

        $sunovtPayTotal = calculatePayAmount($data['sunovtTotal'], $sunovtPayRate);
        $sunovtChargeTotal = calculateChargeAmount($data['sunovtTotal'], $sunovtChargeRate);

        $povtPayTotal = calculatePayAmount($data['povtTotal'], $povtPayRate);
        $povtChargeTotal = calculateChargeAmount($data['povtTotal'], $povtChargeRate);

        $payTotal = $dayPayTotal + $emgPayTotal + $aftPayTotal + $nightPayTotal + $rdoPayTotal + $satPayTotal + $sunPayTotal + $ovtPayTotal + $dblPayTotal + $hldPayTotal + $satovtPayTotal + $sunovtPayTotal + $povtPayTotal;
        $chargeTotal = $dayChargeTotal + $emgChargeTotal + $aftChargeTotal + $nightChargeTotal + $rdoChargeTotal + $satChargeTotal + $sunChargeTotal + $ovtChargeTotal + $dblChargeTotal + $hldChargeTotal + $satovtChargeTotal + $sunovtChargeTotal + $povtChargeTotal;

        $allHoursTotal = round(($allHoursTotal + $totalHours), 2);
        $allPayTotal = round(($allPayTotal + $payTotal), 2);
        $allChargeTotal = round(($allChargeTotal + $chargeTotal), 2);

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $position);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $fullName);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, date('d/m/Y', strtotime($data['wkendDate'])));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['ordTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['emgTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['aftTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['nightTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['satTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['sunTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['ovtTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['dblTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['rdoTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['hldTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $totalHours);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $dayPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $emgPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $aftPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, $nightPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, $satPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, $sunPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, $ovtPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('X' . $rowCount, $dblPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('Y' . $rowCount, $rdoPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('Z' . $rowCount, $hldPayRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AA' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->setCellValue('AB' . $rowCount, round($payTotal, 2));
        $objPHPExcel->getActiveSheet()->setCellValue('AC' . $rowCount, $dayChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AD' . $rowCount, $emgChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AE' . $rowCount, $aftChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AF' . $rowCount, $nightChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AG' . $rowCount, $satChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AH' . $rowCount, $sunChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AI' . $rowCount, $ovtChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AJ' . $rowCount, $dblChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AK' . $rowCount, $rdoChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AL' . $rowCount, $hldChargeRate);
        $objPHPExcel->getActiveSheet()->setCellValue('AM' . $rowCount, '');
        $objPHPExcel->getActiveSheet()->setCellValue('AN' . $rowCount, round($chargeTotal, 2));
        $objPHPExcel->getActiveSheet()->setCellValue('AO' . $rowCount, $data['client']);
        $objPHPExcel->getActiveSheet()->setCellValue('AP' . $rowCount, $data['jobCode']);
        $objPHPExcel->getActiveSheet()->setCellValue('AQ' . $rowCount, $data['firstWorkDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('AR' . $rowCount, $data['consultant']);
        $objPHPExcel->getActiveSheet()->setCellValue('AS' . $rowCount, getEmployeeVisaType($mysqli, $data['candidateId']));
        $objPHPExcel->getActiveSheet()->setCellValue('AT' . $rowCount, getCandidateFoundHow($mysqli, $data['candidateId']));
        $objPHPExcel->getActiveSheet()->setCellValue('AU' . $rowCount, getCandidateVaccinationDocType($mysqli, $data['candidateId'], 57));
        $objPHPExcel->getActiveSheet()->setCellValue('AV' . $rowCount, getCandidateVaccinationDocType($mysqli, $data['candidateId'], 58));
        $objPHPExcel->getActiveSheet()->setCellValue('AW' . $rowCount, getCandidateVaccinationDocType($mysqli, $data['candidateId'], 59));
        $objPHPExcel->getActiveSheet()->setCellValue('AX' . $rowCount, getClientWorkcover($mysqli, $data['clientId']));
        $objPHPExcel->getActiveSheet()->setCellValue('AY' . $rowCount, getClientPayrollTax($mysqli, $data['clientId']));
        $objPHPExcel->getActiveSheet()->setCellValue('AZ' . $rowCount, calculateSuperDateRangePaysheet($mysqli, $data['candidateId'], $startWkDate, $endWkDate, $transCode));
        $objPHPExcel->getActiveSheet()->setCellValue('BA' . $rowCount, getFinanceCheckByCandidate($mysqli, $data['candidateId']));
        $polChk = getPoliceCheckDeductionSinceStarted($mysqli,$data['candidateId'],1);
        $refund = ' ';
            foreach ($polChk as $pch) {
                $refund = $refund.' '.$pch['deduction'].'-'.$pch['weekendingDate'].', ';
            }
        $objPHPExcel->getActiveSheet()->setCellValue('BB' . $rowCount, $refund);

        $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('J' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('M' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('N' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('O' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('P' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('R' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('S' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('T' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('U' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('V' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('X' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('Y' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('Z' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AA' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AB' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AC' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AD' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AE' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AF' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AG' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AH' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AI' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AJ' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AK' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AL' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AM' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AN' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AO' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AP' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AQ' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AR' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AS' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AT' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AU' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AV' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AW' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AX' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AY' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('AZ' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('BA' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        $objPHPExcel->getActiveSheet()->getStyle('BB' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('J' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('M' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('N' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('O' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('P' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('R' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('S' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('T' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('U' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('V' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('W' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('X' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('Y' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('Z' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AA' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AB' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AC' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AD' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AE' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AF' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AG' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AH' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AI' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AJ' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AK' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AL' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AM' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AN' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AO' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AP' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AQ' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AR' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AS' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AT' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AU' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AV' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AW' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AX' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AY' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('AZ' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('BA' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('BB' . $rowCount)->applyFromArray($styleBorders);
    }
    $totalsRow = $rowCount + 1;
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $totalsRow, $ordTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $totalsRow, $emgTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $totalsRow, $aftTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $totalsRow, $nightTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $totalsRow, $satTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $totalsRow, $sunTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $totalsRow, $ovtTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $totalsRow, $dblTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $totalsRow, $rdoTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $totalsRow, $hldTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $totalsRow, $allHoursTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('AB' . $totalsRow, $allPayTotal);
    $objPHPExcel->getActiveSheet()->setCellValue('AN' . $totalsRow, $allChargeTotal);


    $objPHPExcel->getActiveSheet()->getStyle('E' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('F' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('G' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('H' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('I' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('J' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('K' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('L' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('P' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('AB' . $totalsRow)->applyFromArray($totalsBorder);
    $objPHPExcel->getActiveSheet()->getStyle('AN' . $totalsRow)->applyFromArray($totalsBorder);

    $time = time();
    $filePath = './reports/paysheetReport-' . $time . '.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filePath);
    echo $filePath;
}else{
    $emgTotal = 0.00;
    $ordTotal = 0.00;
    $aftTotal = 0.00;
    $nightTotal = 0.00;
    $rdoTotal = 0.00;
    $satTotal = 0.00;
    $sunTotal = 0.00;
    $ovtTotal = 0.00;
    $dblTotal = 0.00;
    $hldTotal = 0.00;
    $satovtTotal = 0.00;
    $sunovtTotal = 0.00;
    $povtTotal = 0.00;
    $holTotal = 0.00;
    $workHoursData = getTimesheetWorkHoursData($mysqli,$startWkDate,$endWkDate);

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Employee ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'Employee Name');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'Early Morning Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'Day Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'Afternoon Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'Night Shift');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'RDO');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'Saturday');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'Sunday');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'Overtime');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'Doubletime');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'Holiday 1');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'Holiday 2');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'Saturday Overtime');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'Sunday Overtime');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'Period Overtime');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Total');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', 'Start Date');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', 'End Date');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', 'Weekending Date');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', 'Client');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', 'First Weekending Date');
    $objPHPExcel->getActiveSheet()->setCellValue('W1', 'Consultant');
    $objPHPExcel->getActiveSheet()->setCellValue('X1', 'Found how');
    $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Customer Survey');
    $rowCount = 1;
    foreach ($workHoursData as $data) {
        $rowCount++;
        $ordTotal = $ordTotal + $data['ordTotal'];
        $emgTotal = $emgTotal + $data['emgTotal'];
        $aftTotal = $aftTotal + $data['aftTotal'];
        $nightTotal = $nightTotal + $data['nightTotal'];
        $rdoTotal = $rdoTotal + $data['rdoTotal'];
        $satTotal = $satTotal + $data['satTotal'];
        $sunTotal = $sunTotal + $data['sunTotal'];
        $ovtTotal = $ovtTotal + $data['ovtTotal'];
        $dblTotal = $dblTotal + $data['dblTotal'];
        $hldTotal = $hldTotal + $data['hldTotal'];
        $holTotal = $holTotal + $data['hol_total'];
        $satovtTotal = $satovtTotal + $data['satovtTotal'];
        $sunovtTotal = $sunovtTotal + $data['sunovtTotal'];
        $povtTotal = $povtTotal + $data['povtTotal'];
        $fullName = getCandidateFullName($mysqli, $data['candidateId']) . '(' . getNickNameById($mysqli, $data['candidateId']) . ')';
        $position = getPositionByPositionId($mysqli, $data['positionId']);
        $totalHours = $data['ordTotal'] + $data['emgTotal'] + $data['aftTotal'] + $data['nightTotal'] + $data['rdoTotal'] + $data['satTotal'] + $data['sunTotal'] + $data['ovtTotal'] + $data['dblTotal'] + $data['hldTotal'] + $data['hol_total'] + $data['satovtTotal'] + $data['sunovtTotal'] + $data['povtTotal'];

        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $fullName);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['emgTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['ordTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['aftTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['nightTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['rdoTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['satTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['sunTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['ovtTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['dblTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['hldTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['hol_total']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['satovtTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['sunovtTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['povtTotal']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $totalHours);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['startDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $data['endDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, $data['wkendDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, $data['client']);
        $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, $data['firstWorkDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, $data['consultant']);
        $objPHPExcel->getActiveSheet()->setCellValue('X' . $rowCount, getCandidateFoundHow($mysqli,$data['candidateId']));
        $objPHPExcel->getActiveSheet()->setCellValue('Y' . $rowCount, customerSurveyDocumentStatus($mysqli,$data['candidateId']));
    }
        $time = time();
        $filePath = './reports/payWorkhoursReport-' . $time . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($filePath);
        echo $filePath;
}