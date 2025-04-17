<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$clientId = $_POST['clientId'];
$stateId = $_POST['stateId'];
$industryId = $_POST['industryId'];
$positionId = $_POST['positionId'];
$candidateId = $_POST['candidateId'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$shStatus = $_POST['shiftStatus'];
//echo json_encode($clientId.'S'.$stateId.'IN'.$industryId.'PO'.$positionId.'CAN'.$candidateId.$startDate.$endDate.$shStatus);
try {
    $dataSet = generateRosterReport($mysqli, $clientId, $stateId, $industryId, $positionId, $candidateId, $startDate, $endDate, $shStatus);

    $objPHPExcel = new PHPExcel();

    $objPHPExcel->setActiveSheetIndex(0);

    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFT DAY');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SHIFT DATE');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'INDUSTRY');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'STATE');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'DEPARTMENT');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'EMPLOYEE');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'EMPLOYEE MOBILE');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'EMPLOYEE EMAIL');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'SMARTPHONE STATUS');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'POSITION');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'SHIFT START');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'SHIFT END');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', 'WORK BREAK');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', 'SHIFT HOURS');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', 'SHIFT STATUS');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'EMPLOYEE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', 'EMPLOYEE TYPE');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', 'EMPLOYEE VISA TYPE');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', 'DOB');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', 'GENDER');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', 'CLOCKIN');
    $objPHPExcel->getActiveSheet()->setCellValue('W1', 'CLOCKOUT');
    $objPHPExcel->getActiveSheet()->setCellValue('X1', 'STUDENT');
    $objPHPExcel->getActiveSheet()->setCellValue('Y1', 'SUPERVISOR STATUS');
    $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'CONSULTANT');
    $objPHPExcel->getActiveSheet()->setCellValue('AA1', 'VACCINE 1');
    $objPHPExcel->getActiveSheet()->setCellValue('AB1', 'VACCINE 2');
    $objPHPExcel->getActiveSheet()->setCellValue('AC1', 'VACCINE 3');
    $objPHPExcel->getActiveSheet()->setCellValue('AD1', 'OHS SMS SENT TIME');
    $objPHPExcel->getActiveSheet()->setCellValue('AE1', 'OHS SUBMITTED DATETIME');
    $objPHPExcel->getActiveSheet()->setCellValue('AF1', 'OHS CHECK STATUS');
    $objPHPExcel->getActiveSheet()->setCellValue('AG1', 'OHS CHECKED BY');
    $objPHPExcel->getActiveSheet()->setCellValue('AH1', 'OHS CHECKED AT');
    $objPHPExcel->getActiveSheet()->setCellValue('AI1', 'NEED DISCUSSION');
    $objPHPExcel->getActiveSheet()->setCellValue('AJ1', 'CHRONUS ID');
    $objPHPExcel->getActiveSheet()->setCellValue('AK1', 'STUDENT SHIFT COUNT');
    $objPHPExcel->getActiveSheet()->setCellValue('AL1', 'EMPLOYEE VARIATION SUBMISSION');

    $objPHPExcel->getActiveSheet()->setTitle('Roster Schedule');

    $rowCount = 1;
    foreach ($dataSet as $data) {
        $rowCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftDay']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['shiftDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['client']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['industry']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['state']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['department']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['candidate']);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['candidatePhone']);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['email']);
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['noPhone']);
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['position']);
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['shiftStart']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['shiftEnd']);
        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['workBreak']);
        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['hrsWorked']);
        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['shiftStatus']);
        $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['employee_type']);
        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $data['visaType']);
        $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, $data['dob']);
        $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, $data['gender']);

        $clockIns = getClockInOut($mysqli, $data['shiftId']);
        foreach ($clockIns as $clock) {
            $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, $clock['checkIn']);
            $objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, $clock['checkOut']);
        }

        if (displayStudentIndicator($mysqli, $data['candidateId'])) {
            $studentIndicator = 'STUDENT';
            $objPHPExcel->getActiveSheet()->setCellValue('X' . $rowCount, $studentIndicator);
        }
        $objPHPExcel->getActiveSheet()->setCellValue('Y' . $rowCount, $data['supervisorEdit']);
        $objPHPExcel->getActiveSheet()->setCellValue('Z' . $rowCount, getConsultantName($mysqli, $data['consultantId']));
        $objPHPExcel->getActiveSheet()->setCellValue('AA' . $rowCount, getCandidateVaccinationDocType($mysqli, $data['candidateId'], 57));
        $objPHPExcel->getActiveSheet()->setCellValue('AB' . $rowCount, getCandidateVaccinationDocType($mysqli, $data['candidateId'], 58));
        $objPHPExcel->getActiveSheet()->setCellValue('AC' . $rowCount, getCandidateVaccinationDocType($mysqli, $data['candidateId'], 59));
        /* if (displayNoPhoneIndicator($mysqli, $data['candidateId'])) {
            $noPhoneIndicator = 'NO';
        } else {
            $noPhoneIndicator = 'YES';
        }*/
        //$ohsTime = getOHSSentTimeByCandidateId($mysqli, $data['candidateId'], $clientId, $stateId, $data['departmentId']);
        $objPHPExcel->getActiveSheet()->setCellValue('AD' . $rowCount, $data['ohsTime']);
        /* $doc_info = getOHSDocumentInfo($mysqli,$data['candidateId'],$data['clientId'],$data['stateId'],$data['departmentId']);
        $doc_submitted_time = '';
        $feedback = '';
        if(!empty($doc_info)) {
            $doc = explode('@', $doc_info);
            $doc_submitted_time = $doc[0];
            if($doc[1] == '!!'){
                $feedback = 'ASAP';
            }
        }*/
        $objPHPExcel->getActiveSheet()->setCellValue('AE' . $rowCount, $data['ohsSubmittedTime']);
        //if(!empty($doc_submitted_time)){
            $objPHPExcel->getActiveSheet()->setCellValue('AF' . $rowCount, $data['ohsCheckStatus']);
        //}
        $objPHPExcel->getActiveSheet()->setCellValue('AG' . $rowCount, $data['ohsCheckedBy']);
        $objPHPExcel->getActiveSheet()->setCellValue('AH' . $rowCount, $data['ohsCheckedTime']);
        $objPHPExcel->getActiveSheet()->setCellValue('AI' . $rowCount, $data['feedback']);
        $objPHPExcel->getActiveSheet()->setCellValue('AJ' . $rowCount, getChronusIdByCandidateId($mysqli,$data['candidateId']));
        $objPHPExcel->getActiveSheet()->setCellValue('AK' . $rowCount, getStudentEmployeeMaxShiftCount($mysqli,$data['candidateId'],$startDate,$endDate));
        $objPHPExcel->getActiveSheet()->setCellValue('AL' . $rowCount, $data['empVariationSubmission']);
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('./roster/rosterReport.xlsx');
    echo json_encode($dataSet);
}catch (Exception $e){
    echo $e->getMessage();
}
?>