<?php
include "./includes/db_conn.php";
include "./includes/functions.php";
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");

$startDate = date('Y-m-d',strtotime($_REQUEST['startDate']));
$endDate = date('Y-m-d',strtotime($_REQUEST['endDate']));
$shiftStatusCheck = $_REQUEST['status'];
$boldArray = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00D704'))
);
$styleBorders = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000000'),
        ),
    ),
);
$headingArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00D704')));
$maleArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '9ED6FF')));
$femaleArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFB8F8')));
if($shiftStatusCheck == 'OPEN') {
    $dataSet = generateOpenShifts($mysqli, $startDate, $endDate, $shiftStatusCheck);
    if (!empty($dataSet)) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFTID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SHIFT DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DAY');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'CLIENT');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'STATE');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'DEPARTMENT');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'EMPLOYEE ID');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'EMPLOYEE NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'NICKNAME');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'GENDER');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'EMPLOYEE MOBILE');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'EMPLOYEE EMAIL');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'POSITION');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'SHIFT START');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'SHIFT END');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'WORK BREAK');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'SHIFT NOTE');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', 'SHIFT HOURS');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', 'SHIFT STATUS');
        $objPHPExcel->getActiveSheet()->setCellValue('T1', 'EMPLOYEE TYPE');
        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'STREET NUMBER');
        $objPHPExcel->getActiveSheet()->setCellValue('V1', 'STREET NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('W1', 'SUBURB');
        $objPHPExcel->getActiveSheet()->setCellValue('X1', 'POST CODE');
        $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'STATE');

        $objPHPExcel->getActiveSheet()->setTitle('Schedule Open Shift Export');

        $rowCount = 1;
        foreach ($dataSet as $data) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['shiftDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDay']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getStateById($mysqli, $data['stateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getDepartmentById($mysqli, $data['departmentId']));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']) . ' ' . getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, getNickNameById($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, strtoupper(getGenderById($mysqli, $data['candidateId'])));
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getCandidateMobileNoByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, getEmployeeEmail($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getPositionByPositionId($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['shiftStart']);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['shiftEnd']);
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['workBreak']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['shiftNote']);
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['hrsWorked']);
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $data['shiftStatus']);
            if (displayStudentIndicator($mysqli, $data['candidateId'])) {
                $studentIndicator = 'STUDENT';
                $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, $studentIndicator);
            }
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, getCandidateStreetNumberById($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, getCandidateStreetNameById($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, getCandidateSuburb($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $rowCount, getCandidatePostcode($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('Y' . $rowCount, getCandidateState($mysqli, $data['candidateId']));

        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $time = time();
        $objWriter->save('./roster/rosterOpenShifts- ' . $startDate . ' to ' . $endDate . $time . '.xlsx');
        echo './roster/rosterOpenShifts- ' . $startDate . ' to ' . $endDate . $time . '.xlsx';
    }
}else{
    $dataSet = generateClockInShifts($mysqli, $startDate, $endDate, $shiftStatusCheck);
    if (!empty($dataSet)) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFTID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SHIFT DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DAY');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'CLIENT');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'STATE');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'DEPARTMENT');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'EMPLOYEE ID');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'EMPLOYEE NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'NICKNAME');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'GENDER');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'EMPLOYEE MOBILE');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'EMPLOYEE EMAIL');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'POSITION');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'SHIFT START');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'SHIFT END');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'WORK BREAK');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'SHIFT NOTE');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', 'EMPLOYEE TYPE');
        $objPHPExcel->getActiveSheet()->setCellValue('S1', 'STREET NUMBER');
        $objPHPExcel->getActiveSheet()->setCellValue('T1', 'STREET NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('U1', 'SUBURB');
        $objPHPExcel->getActiveSheet()->setCellValue('V1', 'POST CODE');
        $objPHPExcel->getActiveSheet()->setCellValue('W1', 'STATE');

        $objPHPExcel->getActiveSheet()->setTitle('Schedule Open Shift Export');

        $rowCount = 1;
        foreach ($dataSet as $data) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['shiftDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDay']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getStateById($mysqli, $data['stateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getDepartmentById($mysqli, $data['departmentId']));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']) . ' ' . getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, getNickNameById($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, strtoupper(getGenderById($mysqli, $data['candidateId'])));
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getCandidateMobileNoByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, getEmployeeEmail($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getPositionByPositionId($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['shiftStart']);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['shiftEnd']);
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['workBreak']);
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['shiftNote']);
            if (displayStudentIndicator($mysqli, $data['candidateId'])) {
                $studentIndicator = 'STUDENT';
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $studentIndicator);
            }
            $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, getCandidateStreetNumberById($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, getCandidateStreetNameById($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, getCandidateSuburb($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, getCandidatePostcode($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, getCandidateState($mysqli, $data['candidateId']));

        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $time = time();
        $objWriter->save('./roster/rosterClockInShifts- ' . $startDate . ' to ' . $endDate . $time . '.xlsx');
        echo './roster/rosterClockInShifts- ' . $startDate . ' to ' . $endDate . $time . '.xlsx';
    }
}