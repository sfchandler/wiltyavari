<?php
session_start();
include "./includes/db_conn.php";
include "./includes/functions.php";
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$clid = $_REQUEST['clientId'];
$std = $_REQUEST['stateId'];
$deptid = $_REQUEST['deptId'];
$positionid = $_REQUEST['positionid'];
$num_th = $_REQUEST['num_th'];
$startDate = date('Y-m-d',strtotime($_REQUEST['startDate']));
$endDate = date('Y-m-d',strtotime($_REQUEST['endDate']));
$shiftStatusCheck = $_REQUEST['status'];
$boldArray = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00D704'))
);
$boldText = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFFFFF'))
);
$styleBorders = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000000'),
        ),
    ),
);
$doubleBorders = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
            'color' => array('argb' => '000000'),
        ),
    ),
);
$headingArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00D704')));
$maleArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '9ED6FF')));
$femaleArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFB8F8')));
$confirmedArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'B0FFA4')));
$unconfirmedArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFc977')));
if($shiftStatusCheck == 'EXPORTEV'){
    $std = 0;
    $deptid = 0;
    $positionid = 0;
}
try {
    if (isset($clid) && isset($std) && isset($deptid) && isset($num_th)) {
        /*$ps = explode('-', $param);
        $clid = $ps[0];
        $std = $ps[1];
        $deptid = $ps[2];*/

        if ($shiftStatusCheck == 'EVERYONE') {
            $dataSet = generateEmployeesAllocated($mysqli, $clid, $std, $deptid, $positionid, $shiftStatusCheck);

            if (!empty($dataSet)) {
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NICKNAME');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'GENDER');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'MOBILE');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'EMAIL');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CLIENT');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', 'STATE');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'DEPARTMENT');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'POSITION');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'LAST SHIFT WORKED');
                $objPHPExcel->getActiveSheet()->setTitle('Employees Allocated Export');

                $rowCount = 1;
                foreach ($dataSet as $data) {
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName'] . ' ' . $data['lastName']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['nickname']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['gender']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['mobileNo']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['email']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['client']);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['state']);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['department']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['position']);
                    $shiftInfo = explode(':',getLastShiftInfoByCandidateId($mysqli,$data['candidateId']));
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $shiftInfo[0]);
                }
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterSheduleEveryone- ' . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterSheduleEveryone- ' . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        }if ($shiftStatusCheck == 'ALLPERCLIENT') {
            $dataSet = generateEmployeesAllocatedPerClient($mysqli, $clid, $shiftStatusCheck);

            if (!empty($dataSet)) {
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'NICKNAME');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'GENDER');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'MOBILE');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'EMAIL');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CLIENT');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', 'LAST SHIFT WORKED');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'VISA TYPE');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'VISA EXPIRY');
                $objPHPExcel->getActiveSheet()->setTitle('Employees Allocated To Client');

                $rowCount = 1;
                foreach ($dataSet as $data) {
                    $rowCount++;
                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName'] . ' ' . $data['lastName']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['nickname']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['gender']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['mobileNo']);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['email']);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['client']);
                    $shiftInfo = explode(':',getLastShiftInfoByCandidateId($mysqli,$data['candidateId']));
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $shiftInfo[0]);
                    $visaInfo = explode('#',getEmployeeVisaInformation($mysqli,$data['candidateId']));
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount,$visaInfo[0]);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount,$visaInfo[1]);
                }
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterShedulePerClient- ' . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterShedulePerClient- ' . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        } elseif ($shiftStatusCheck == 'ROSTER') {
            $dataSet = generateRosterData($mysqli, $clid, $std, $deptid, $positionid, $startDate, $endDate, $shiftStatusCheck);

            if (!empty($dataSet)) {
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'WEEK START:Mon ' . $startDate . '                                 WEEKENDING: ' . $endDate);
                $objPHPExcel->getActiveSheet()->getStyle('A1:U1')->applyFromArray($headingArray);
                $objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('O1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('Q1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('R1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A1:J1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A2:U2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A3:U3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', getDepartmentById($mysqli, $deptid));
                $objPHPExcel->getActiveSheet()->getStyle('A2:U2')->applyFromArray($boldArray);
                $objPHPExcel->getActiveSheet()->setCellValue('B2', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('C2', 'MONDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('D2', 'TUESDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('E2', 'WEDNESDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('F2', 'THURSDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('G2', 'FRIDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('H2', 'SATURDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('I2', 'SUNDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('J2', 'NOTES');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'STREET NUMBER');
                $objPHPExcel->getActiveSheet()->setCellValue('L1', 'STREET NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('M1', 'SUBURB');
                $objPHPExcel->getActiveSheet()->setCellValue('N1', 'POST CODE');
                $objPHPExcel->getActiveSheet()->setCellValue('O1', 'STATE');
                $objPHPExcel->getActiveSheet()->setCellValue('P1', 'INDUCTION COMPLETED');
                if ($clid != '1') {
                    $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'COVID19 VACCINATION');
                }
                $objPHPExcel->getActiveSheet()->setCellValue('R1', 'RITEQ ID');
                $objPHPExcel->getActiveSheet()->setCellValue('S1', 'CHRONUS ID');
                $objPHPExcel->getActiveSheet()->setCellValue('T1','MISSION FOODS DOCUMENTS');
                $objPHPExcel->getActiveSheet()->setCellValue('U1','DRISCOLLS HEALTH SAFETY INDUCTION');
                $objPHPExcel->getActiveSheet()->setTitle('Roster Export');
                $objPHPExcel->getActiveSheet()->setCellValue('C3', $startDate);
                $objPHPExcel->getActiveSheet()->setCellValue('D3', date('Y-m-d', strtotime($startDate . ' + 1 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('E3', date('Y-m-d', strtotime($startDate . ' + 2 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('F3', date('Y-m-d', strtotime($startDate . ' + 3 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('G3', date('Y-m-d', strtotime($startDate . ' + 4 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('H3', date('Y-m-d', strtotime($startDate . ' + 5 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('I3', date('Y-m-d', strtotime($startDate . ' + 6 day')));

                $objPHPExcel->getActiveSheet()->getStyle('K1:Q1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleBorders);
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
                //if ($clid != '1') {
                    $objPHPExcel->getActiveSheet()->getStyle('Q2')->applyFromArray($styleBorders);
                //}
                $objPHPExcel->getActiveSheet()->getStyle('R2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('S2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('T2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('U2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('J3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('K3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('M3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('O3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('P3')->applyFromArray($styleBorders);
                //if ($clid != '1') {
                    $objPHPExcel->getActiveSheet()->getStyle('Q3')->applyFromArray($styleBorders);
                //}
                $objPHPExcel->getActiveSheet()->getStyle('R3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('S3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('T3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('U3')->applyFromArray($styleBorders);
                $rowCount = 3;
                $sameRow = 3;
                $canId = '';
                foreach ($dataSet as $data) {
                    $rowCount = $rowCount + 1;
                    if (empty($canId)) {
                        $canId = $data['candidateId'];
                        $sameRow = $rowCount;
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $canId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $canId));
                        if (getGenderById($mysqli, $canId) == 'Male') {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($maleArray);
                        } else {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($femaleArray);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getCandidateStreetNumberById($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, getCandidateStreetNameById($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getCandidateSuburb($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->getStyle('M' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, getCandidatePostcode($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->getStyle('N' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, getCandidateState($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['inductionCode']);
                        $objPHPExcel->getActiveSheet()->getStyle('P' . $rowCount)->applyFromArray($styleBorders);
                        if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $vaccinations);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],76) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],77) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],78) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],79) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],80)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount,'All documents uploaded');
                        }
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],81)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount,'Induction document uploaded');
                        }
                    }
                    if ($canId != $data['candidateId']) {
                        $canId = $data['candidateId'];
                        $sameRow = $rowCount;
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $canId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $canId));
                        if (getGenderById($mysqli, $canId) == 'Male') {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($maleArray);
                        } else {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($femaleArray);
                        }
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
                        //if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->applyFromArray($styleBorders);
                        //}
                        $objPHPExcel->getActiveSheet()->getStyle('R' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('S' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('T' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('U' . $rowCount)->applyFromArray($styleBorders);
                        if ($data['shiftDay'] == 'Mon') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                                if($data['shiftStatus'] == 'CONFIRMED'){
                                    $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($confirmedArray);
                                }elseif($data['shiftStatus'] == 'OPEN'){
                                    $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($unconfirmedArray);
                                }
                            }
                        } elseif ($data['shiftDay'] == 'Tue') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Wed') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Thu') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Fri') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Sat') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Sun') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($unconfirmedArray);

                            }
                        }

                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getCandidateStreetNumberById($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, getCandidateStreetNameById($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getCandidateSuburb($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, getCandidatePostcode($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, getCandidateState($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['inductionCode']);
                        if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $vaccinations);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],76) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],77) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],78) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],79) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],80)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount,'All documents uploaded');
                        }
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],81)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount,'Induction document uploaded');
                        }
                    } elseif ($canId == $data['candidateId']) {
                        $canId = $data['candidateId'];
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('L' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('M' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('N' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('P' . $sameRow)->applyFromArray($styleBorders);
                        //if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->getStyle('Q' . $sameRow)->applyFromArray($styleBorders);
                        //}
                        $objPHPExcel->getActiveSheet()->getStyle('R' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $sameRow, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . $sameRow, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],76) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],77) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],78) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],79) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],80)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('T' . $sameRow,'All documents uploaded');
                        }
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],81)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('U' . $sameRow,'Induction document uploaded');
                        }
                        if ($data['shiftDay'] == 'Mon') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('C' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Tue') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('D' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Wed') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Thu') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('F' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Fri') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('G' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Sat') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('H' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        } elseif ($data['shiftDay'] == 'Sun') {
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('I' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($unconfirmedArray);

                            }
                        }

                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getCandidateStreetNumberById($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, getCandidateStreetNameById($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getCandidateSuburb($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, getCandidatePostcode($mysqli, $data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, getCandidateState($mysqli, $data['candidateId']));
                        if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $vaccinations);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],76) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],77) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],78) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],79) && validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],80)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount,'All documents uploaded');
                        }
                        if (validateCandidateDocumentByDocTypeId($mysqli, $data['candidateId'],81)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount,'Induction document uploaded');
                        }
                    }
                    $rowCount = $sameRow + 1;
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
                    //if ($clid != '1') {
                        $objPHPExcel->getActiveSheet()->getStyle('Q' . $rowCount)->applyFromArray($styleBorders);
                    //}
                    $objPHPExcel->getActiveSheet()->getStyle('R' . $rowCount)->applyFromArray($styleBorders);
                    $objPHPExcel->getActiveSheet()->getStyle('S' . $rowCount)->applyFromArray($styleBorders);
                    $objPHPExcel->getActiveSheet()->getStyle('T' . $rowCount)->applyFromArray($styleBorders);
                    $objPHPExcel->getActiveSheet()->getStyle('U' . $rowCount)->applyFromArray($styleBorders);
                }
                $note = $rowCount + 2;
                $objPHPExcel->getActiveSheet()->setCellValue('A' .$note, 'CLIENT DEPARTMENT NOTES');
                $objPHPExcel->getActiveSheet()->setCellValue('E' .$note, getClientDepartmentNote($mysqli,$deptid));
                $legend = $rowCount + 4;
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($maleArray);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $legend, 'MALE');
                $legend++;
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($femaleArray);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $legend, 'FEMALE');


                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterExport- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterExport- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        }else if($shiftStatusCheck == 'ALLROSTER'){
            $dataSet = generateRosterData($mysqli, $clid, $std, $deptid, $positionid, $startDate, $endDate, $shiftStatusCheck);
            if (!empty($dataSet)) {
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->mergeCells('A1:J1');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'WEEK START:Mon ' . $startDate . '                                 WEEKENDING: ' . $endDate);
                $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($headingArray);
                $objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A3:O3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', getDepartmentById($mysqli, $deptid));
                $objPHPExcel->getActiveSheet()->getStyle('A2:O2')->applyFromArray($boldArray);
                $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($boldArray);
                $objPHPExcel->getActiveSheet()->setCellValue('B2', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('C2', 'MONDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('D2', 'TUESDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('E2', 'WEDNESDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('F2', 'THURSDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('G2', 'FRIDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('H2', 'SATURDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('I2', 'SUNDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('J2', 'NOTES');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'INDUCTION COMPLETED');
                $objPHPExcel->getActiveSheet()->setCellValue('L1', 'COVID19 VACCINATION');
                $objPHPExcel->getActiveSheet()->setCellValue('M1', 'MOBILE NO');
                $objPHPExcel->getActiveSheet()->setCellValue('N1', 'RITEQ ID');
                $objPHPExcel->getActiveSheet()->setCellValue('O1', 'CHRONUS ID');
                $objPHPExcel->getActiveSheet()->setTitle('Roster Export');

                $objPHPExcel->getActiveSheet()->setCellValue('C3', $startDate);
                $objPHPExcel->getActiveSheet()->setCellValue('D3', date('Y-m-d', strtotime($startDate . ' + 1 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('E3', date('Y-m-d', strtotime($startDate . ' + 2 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('F3', date('Y-m-d', strtotime($startDate . ' + 3 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('G3', date('Y-m-d', strtotime($startDate . ' + 4 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('H3', date('Y-m-d', strtotime($startDate . ' + 5 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('I3', date('Y-m-d', strtotime($startDate . ' + 6 day')));
                $objPHPExcel->getActiveSheet()->getStyle('C3:I3')->applyFromArray($boldText);

                $objPHPExcel->getActiveSheet()->getStyle('K1:N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleBorders);
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

                $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('J3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('K3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('M3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('O3')->applyFromArray($styleBorders);

                $rowCount = 4;
                $sameRow = 4;
                $canId = '';
                $mondayCount = 0;
                $tuesdayCount = 0;
                $wednesdayCount = 0;
                $thursdayCount = 0;
                $fridayCount = 0;
                $saturdayCount = 0;
                $sundayCount = 0;
                $i = 0;
                $len = sizeof($dataSet);
                foreach ($dataSet as $data) {
                    //$rowCount = $rowCount + 1;
                    if (empty($canId)) {
                        $canId = $data['candidateId'];
                        $sameRow = $rowCount;
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $canId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $canId));
                        if (getGenderById($mysqli, $canId) == 'Male') {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($maleArray);
                        } else {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($femaleArray);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['inductionCode']);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->applyFromArray($styleBorders);
                        if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->setCellValue('L'. $rowCount, $vaccinations);
                            $objPHPExcel->getActiveSheet()->getStyle('L'. $rowCount)->applyFromArray($styleBorders);
                        }
                    }
                    if ($canId != $data['candidateId']) {
                        $openShiftCheck = array();
                        $canId = $data['candidateId'];
                        $sameRow = $rowCount;
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $canId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $canId));
                        if (getGenderById($mysqli, $canId) == 'Male') {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($maleArray);
                        } else {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($femaleArray);
                        }
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

                        if ($data['shiftDay'] == 'Mon') {
                            $mondayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($confirmedArray);
                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Tue') {
                            $tuesdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($confirmedArray);
                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Wed') {
                            $wednesdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Thu') {
                            $thursdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Fri') {
                            $fridayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sat') {
                            $saturdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sun') {
                            $sundayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        }

                        if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $vaccinations);
                        }
                        if(in_array(1,$openShiftCheck)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount,1);
                        }

                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['inductionCode']);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getCandidateMobileNoByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                    } elseif ($canId == $data['candidateId']) {
                        $openShiftCheck = array();
                        $canId = $data['candidateId'];
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('L' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('M' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('N' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $sameRow)->applyFromArray($styleBorders);
                        if ($data['shiftDay'] == 'Mon') {
                            $mondayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('C' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Tue') {
                            $tuesdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('D' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Wed') {
                            $wednesdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Thu') {
                            $thursdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('F' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Fri') {
                            $fridayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('G' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sat') {
                            $saturdayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('H' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sun') {
                            $sundayCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('I' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        }
                        if(in_array(1,$openShiftCheck)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('A' . $sameRow,1);
                        }

                        if ($clid != '1') {
                            $objPHPExcel->getActiveSheet()->setCellValue('L' . $sameRow, $vaccinations);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $sameRow, getCandidateMobileNoByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . $sameRow, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $sameRow, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                    }


                    $rowCount = $sameRow + 1;
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
                    //not last row
                    if($i == $len - 1){
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Totals');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $mondayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $tuesdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $wednesdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $thursdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $fridayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $saturdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $sundayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($boldText);
                    }
                    $i++;

                }
                $legend = $rowCount + 2;
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($maleArray);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $legend, 'MALE');
                $legend++;
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($femaleArray);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $legend, 'FEMALE');

                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterAllExport- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterAllExport- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        }else if($shiftStatusCheck == 'EXPORTEV'){
            $dataSet = generateRosterData($mysqli, $clid, $std, $deptid, $positionid, $startDate, $endDate, $shiftStatusCheck);

            if (!empty($dataSet)) {
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->mergeCells('A1:O1');
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'WEEK START:Mon ' . $startDate . '                                 WEEKENDING: ' . $endDate);
                $objPHPExcel->getActiveSheet()->getStyle('A1:O1')->applyFromArray($headingArray);
                $objPHPExcel->getActiveSheet()->getStyle('K1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('M1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('P1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A2:P2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('A3:P3')->applyFromArray($styleBorders);

                $objPHPExcel->getActiveSheet()->getStyle('A2:P2')->applyFromArray($boldArray);
                $objPHPExcel->getActiveSheet()->setCellValue('A2', 'DEPARTMENT');
                $objPHPExcel->getActiveSheet()->setCellValue('B2', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('C2', 'MONDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('D2', 'TUESDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('E2', 'WEDNESDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('F2', 'THURSDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('G2', 'FRIDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('H2', 'SATURDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('I2', 'SUNDAY');
                $objPHPExcel->getActiveSheet()->setCellValue('J2', 'NOTES');
                $objPHPExcel->getActiveSheet()->setCellValue('K2', 'INDUCTION COMPLETED');
                $objPHPExcel->getActiveSheet()->setCellValue('L2', 'COVID19 VACCINATION');
                $objPHPExcel->getActiveSheet()->setCellValue('M2', 'RITEQ ID');
                $objPHPExcel->getActiveSheet()->setCellValue('N2', 'OPEN SHIFT INDICATOR');
                $objPHPExcel->getActiveSheet()->setCellValue('O2', 'MOBILE NO');
                $objPHPExcel->getActiveSheet()->setCellValue('P2', 'CHRONUS ID');
                $objPHPExcel->getActiveSheet()->setTitle('Roster Export');
                $objPHPExcel->getActiveSheet()->setCellValue('C3', $startDate);
                $objPHPExcel->getActiveSheet()->setCellValue('D3', date('Y-m-d', strtotime($startDate . ' + 1 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('E3', date('Y-m-d', strtotime($startDate . ' + 2 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('F3', date('Y-m-d', strtotime($startDate . ' + 3 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('G3', date('Y-m-d', strtotime($startDate . ' + 4 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('H3', date('Y-m-d', strtotime($startDate . ' + 5 day')));
                $objPHPExcel->getActiveSheet()->setCellValue('I3', date('Y-m-d', strtotime($startDate . ' + 6 day')));
                $objPHPExcel->getActiveSheet()->getStyle('C3:I3')->applyFromArray($boldText);

                $objPHPExcel->getActiveSheet()->getStyle('K1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

                $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleBorders);
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

                $objPHPExcel->getActiveSheet()->getStyle('A3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('C3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('D3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('E3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('F3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('G3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('H3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('I3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('J3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('K3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('M3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('O3')->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('P3')->applyFromArray($styleBorders);

                $rowCount = 4;
                $sameRow = 4;
                $canId = '';
                $departmentId = 0;
                $mondayCount = 0;
                $tuesdayCount = 0;
                $wednesdayCount = 0;
                $thursdayCount = 0;
                $fridayCount = 0;
                $saturdayCount = 0;
                $sundayCount = 0;
                $mondayDeptCount = 0;
                $tuesdayDeptCount = 0;
                $wednesdayDeptCount = 0;
                $thursdayDeptCount = 0;
                $fridayDeptCount = 0;
                $saturdayDeptCount = 0;
                $sundayDeptCount = 0;
                $departmentChange = 0;
                $i = 0;
                $len = sizeof($dataSet);
                foreach ($dataSet as $data) {
                    if($departmentId == 0) {
                        $departmentId = $data['departmentId'];
                        if ($data['shiftDay'] == 'Mon') {
                            $mondayDeptCount = 0;
                        }elseif ($data['shiftDay'] == 'Tue') {
                            $tuesdayDeptCount = 0;
                        }elseif ($data['shiftDay'] == 'Wed') {
                            $wednesdayDeptCount = 0;
                        }elseif ($data['shiftDay'] == 'Thu') {
                            $thursdayDeptCount = 0;
                        }elseif ($data['shiftDay'] == 'Fri') {
                            $fridayDeptCount = 0;
                        }elseif ($data['shiftDay'] == 'Sat') {
                            $saturdayDeptCount = 0;
                        }elseif ($data['shiftDay'] == 'Sun') {
                            $sundayDeptCount = 0;
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, getDepartmentById($mysqli, $departmentId));
                    }
                    if($departmentId != $data['departmentId']){
                        $departmentId = $data['departmentId'];
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sub Total');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $mondayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $tuesdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $wednesdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $thursdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $fridayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $saturdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $sundayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($boldText);
                        $mondayDeptCount = 0;
                        $tuesdayDeptCount = 0;
                        $wednesdayDeptCount = 0;
                        $thursdayDeptCount = 0;
                        $fridayDeptCount = 0;
                        $saturdayDeptCount = 0;
                        $sundayDeptCount = 0;
                        $rowCount++;
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, getDepartmentById($mysqli, $departmentId));
                    }


                    //$rowCount = $rowCount + 1;
                    if (empty($canId)) {
                        $canId = $data['candidateId'];
                        $sameRow = $rowCount;
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $canId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $canId));
                        if (getGenderById($mysqli, $canId) == 'Male') {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($maleArray);
                        } else {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($femaleArray);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['inductionCode']);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $vaccinations);
                        $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                    }
                    if ($canId != $data['candidateId']) {
                        $openShiftCheck = array();
                        $canId = $data['candidateId'];
                        $sameRow = $rowCount;
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, getDepartmentById($mysqli, $departmentId));
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $canId) . ' ' . getCandidateLastNameByCandidateId($mysqli, $canId));
                        if (getGenderById($mysqli, $canId) == 'Male') {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($maleArray);
                        } else {
                            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($femaleArray);
                        }
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
                        if ($data['shiftDay'] == 'Mon') {
                            $mondayCount++;
                            $mondayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($confirmedArray);
                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Tue') {
                            $tuesdayCount++;
                            $tuesdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($confirmedArray);
                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Wed') {
                            $wednesdayCount++;
                            $wednesdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Thu') {
                            $thursdayCount++;
                            $thursdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Fri') {
                            $fridayCount++;
                            $fridayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sat') {
                            $saturdayCount++;
                            $saturdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sun') {
                            $sundayCount++;
                            $sundayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['inductionCode']);
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $vaccinations);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        if(in_array(1,$openShiftCheck)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount,1);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, getCandidateMobileNoByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                    }elseif ($canId == $data['candidateId']) {
                        $openShiftCheck = array();
                        $canId = $data['candidateId'];
                        $vaccinations = vaccinationIndicator($mysqli, $canId);
                        $objPHPExcel->getActiveSheet()->setCellValue('A'.$sameRow, getDepartmentById($mysqli, $departmentId));
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('J' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('K' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('L' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('M' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('N' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('O' . $sameRow)->applyFromArray($styleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('P' . $sameRow)->applyFromArray($styleBorders);

                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $sameRow, getRiteqIdByCandidateId($mysqli,$data['candidateId']));

                        if ($data['shiftDay'] == 'Mon') {
                            $mondayCount++;
                            $mondayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('C' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('C' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }

                        } elseif ($data['shiftDay'] == 'Tue') {
                            $tuesdayCount++;
                            $tuesdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('D' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('D' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Wed') {
                            $wednesdayCount++;
                            $wednesdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('E' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Thu') {
                            $thursdayCount++;
                            $thursdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('F' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('F' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Fri') {
                            $fridayCount++;
                            $fridayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('G' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('G' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sat') {
                            $saturdayCount++;
                            $saturdayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('H' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('H' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        } elseif ($data['shiftDay'] == 'Sun') {
                            $sundayCount++;
                            $sundayDeptCount++;
                            if (!empty($data['shiftStart']) && !empty($data['shiftEnd'])) {
                                $objPHPExcel->getActiveSheet()->setCellValue('I' . $sameRow, $data['shiftStart'] . ' - ' . $data['shiftEnd']);
                            }
                            if($data['shiftStatus'] == 'CONFIRMED'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($confirmedArray);

                            }elseif($data['shiftStatus'] == 'OPEN'){
                                $objPHPExcel->getActiveSheet()->getStyle('I' . $sameRow)->applyFromArray($unconfirmedArray);
                                $openShiftCheck[] = 1;
                            }
                        }
                        if(in_array(1,$openShiftCheck)) {
                            $objPHPExcel->getActiveSheet()->setCellValue('N' . $sameRow,1);
                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . $sameRow, $vaccinations);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . $sameRow, getRiteqIdByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('O' . $sameRow, getCandidateMobileNoByCandidateId($mysqli,$data['candidateId']));
                        $objPHPExcel->getActiveSheet()->setCellValue('P' . $sameRow, getChronusIdByCandidateId($mysqli,$data['candidateId']));
                    }
                    $rowCount = $sameRow + 1;
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

                    if($i == $len - 1){
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sub Total');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $mondayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $tuesdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $wednesdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $thursdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $fridayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $saturdayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $sundayDeptCount);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->applyFromArray($boldText);

                        $lastRow = $rowCount + 1;
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $lastRow, 'Totals');
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->getStyle('B' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('C' . $lastRow, $mondayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('C' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . $lastRow, $tuesdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('D' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . $lastRow, $wednesdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('E' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . $lastRow, $thursdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('F' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . $lastRow, $fridayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('G' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . $lastRow, $saturdayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('H' . $lastRow)->applyFromArray($doubleBorders);
                        $objPHPExcel->getActiveSheet()->setCellValue('I' . $lastRow, $sundayCount);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $lastRow)->applyFromArray($boldText);
                        $objPHPExcel->getActiveSheet()->getStyle('I' . $lastRow)->applyFromArray($doubleBorders);

                    }
                    $i++;
                }
                $legend = $rowCount + 4;
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $legend, 'LEGEND');
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($maleArray);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $legend, 'MALE');
                $legend++;
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($femaleArray);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $legend)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $legend, 'FEMALE');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterExportEv- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterExportEv- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        }else if ($shiftStatusCheck == 'LASTSHIFTWORKED') {
            $empStatus = 'ACTIVE';
            $auditStatus = 1;
            $estatus = 1;
            $sql = $mysqli->prepare('SELECT 
                                  employee_allocation.candidateId,
                                  employee_allocation.clientId,
                                  employee_allocation.stateId,
                                  employee_allocation.deptId,
                                  employee_positions.positionid
                                FROM
                                  employee_allocation
                                  INNER JOIN employee_positions ON (employee_allocation.candidateId = employee_positions.candidateId)
                                  INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                WHERE
                                  employee_allocation.clientId = ? AND
                                  employee_allocation.stateId = ? AND
                                  employee_allocation.deptId = ? AND
                                  employee_allocation.status = ? AND
                                  employee_positions.positionid = ? AND 
                                  candidate.empStatus = ? AND
                                  candidate.auditStatus = ?
                                ORDER BY candidate.firstName ASC');
            $sql->bind_param("iiiiisi", $clid, $std, $deptid, $estatus, $positionid,$empStatus, $auditStatus) or die($mysqli->error);
            $sql->execute();
            $sql->bind_result($candidateId, $clientId, $stateId, $deptId,$positionid) or die($mysqli->error);
            $sql->store_result();
            $nrows = $sql->num_rows;
            $row = '';

            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'MOBILE');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'EMAIL');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'DEPARTMENT');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CLIENT');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'STATE');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'POSITION');
            $objPHPExcel->getActiveSheet()->setCellValue('I1', 'LAST SHIFT WORKED');
            $objPHPExcel->getActiveSheet()->setCellValue('J1', 'RITEQ ID');
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'CHRONUS ID');
            $objPHPExcel->getActiveSheet()->setTitle('Employees Last Shift Export');

            if ($nrows > 0) {
                $rowCount = 1;
                while ($sql->fetch()) {
                        $rowCount++;
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $candidateId);
                        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli,$candidateId));
                        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateMobileNoByCandidateId($mysqli,$candidateId));
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getEmployeeEmail($mysqli,$candidateId));
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getDepartmentById($mysqli,$deptId));
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getClientNameByClientId($mysqli,$clientId));
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, getStateById($mysqli,$stateId));
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, getPositionByPositionId($mysqli,$positionid));
                        $shiftInfo = explode(':',getLastConfirmedShiftInfoByCandidateId($mysqli,$candidateId));
                        $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $shiftInfo[0]);
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, getRiteqIdByCandidateId($mysqli,$candidateId));
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getChronusIdByCandidateId($mysqli,$candidateId));
                }
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterLastshift- ' . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterLastshift- ' . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        }else if($shiftStatusCheck == 'EXPORTNOANSWER'){
            $dataSet = generateRosterData($mysqli, $clid, $std, $deptid, $positionid, $startDate, $endDate, $shiftStatusCheck);

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
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'SHIFT LOG INFO');

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
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, getNoAnswerShiftLogInfoByShiftId($mysqli,$data['shiftId'],'NOANSWER'));
                }
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterNoAnswer- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                echo './roster/rosterNoAnswer- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        } else {
            $dataSet = generateRosterData($mysqli, $clid, $std, $deptid, $positionid, $startDate, $endDate, $shiftStatusCheck);
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
                $objPHPExcel->getActiveSheet()->setCellValue('Z1', 'SMARTPHONE STATUS');
                $objPHPExcel->getActiveSheet()->setTitle('Roster Schedule Export');

                /* $row = 1;
                 $lastColumn = $objPHPExcel->getActiveSheet()->getHighestColumn();
                 $lastColumn++;

                 for ($column = 'A'; $column != $lastColumn; $column++) {
                     $cell = $objPHPExcel->getActiveSheet()->getCell($column.$row);
                 }*/
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
                    if (displayNoPhoneIndicator($mysqli, $data['candidateId'])) {
                        $noPhoneIndicator = 'NO';
                    } else {
                        $noPhoneIndicator = 'YES';
                    }
                    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $rowCount, $noPhoneIndicator);
                }
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save('./roster/rosterShedule- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx');
                /*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="rosterShedule.xlsx"');
                header('Cache-Control: max-age=0');
                // If you're serving to IE 9, then the following may be needed
                header('Cache-Control: max-age=1');
                // If you're serving to IE over SSL, then the following may be needed
                header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
                header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header ('Pragma: public'); // HTTP/1.0
                $objWriter->save('php://output');*/
                echo './roster/rosterShedule- ' . $startDate . ' to ' . $endDate . getClientNameByClientId($mysqli, $clid) . '.xlsx';
            }
        }
    }
}catch (Exception $e){
    //echo 'Err'.$e->getMessage();
}
?>