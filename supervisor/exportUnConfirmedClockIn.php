<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('memory_limit', '3072M');
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
require_once("../includes/TCPDF-master/clockInConfig/tcpdf_include.php");
require_once("../includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$empSelected = $_REQUEST['empSelected'];
$deptId = $_REQUEST['deptId'];
$positionId = $_REQUEST['positionId'];
$supervisorClient = $_SESSION['supervisorClient'];
$supervisorDepartment = $_SESSION['supervisorDepartment'];
define('REPORT_HEADER_TITLE',getClientNameByClientId($mysqli,$supervisorClient).'                                                                                                               '.$startDate.' to '.$endDate);
$supervisorId = $_SESSION['supervisorId'];
$supervisorCheck = 'N';
$clockInData = getUnConfirmedClockInData($mysqli,$startDate,$endDate,$empSelected,$supervisorClient,$supervisorCheck,$deptId,$positionId);
$action = $_REQUEST['action'];

$superCheckINArray = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FF9999'))
);
$superCheckOUTArray = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '66B2FF'))
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

if($action == 'EXCEL') {
    if (!empty($clockInData)) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'SHIFT DAY');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMPLOYER');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'POSITION');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CONFIRMED CHECK IN BY SUPERVISOR');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'CONFIRMED CHECK OUT BY SUPERVISOR');
        $objPHPExcel->getActiveSheet()->getStyle('G1' )->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('G1' )->applyFromArray($superCheckINArray);
        $objPHPExcel->getActiveSheet()->getStyle('H1' )->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('H1' )->applyFromArray($superCheckOUTArray);
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'CONFIRMED CHECK IN BY PAYROLL');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'CONFIRMED CHECK OUT BY PAYROLL');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'WORK BREAK');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'WORK HOURS');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'SUPERVISOR');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'APPROVED TIME');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'ROSTER CHECKIN');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'ROSTER CHECK OUT');
        $objPHPExcel->getActiveSheet()->setCellValue('Q1', 'ACTUAL CHECK IN TIME');
        $objPHPExcel->getActiveSheet()->setCellValue('R1', 'ACTUAL CHECK OUT');
        $objPHPExcel->getActiveSheet()->setTitle('Casuals UnConfirmed Timesheet');
        $rowCount = 1;
        $canId = '';
        $totalWrkHrs = 0;
        $len = sizeof($clockInData);
        $k = 0;
        foreach ($clockInData as $data) {
            $rowCount++;
            if(empty($canId)){
                $canId = $data['candidateId'];
                $totalWrkHrs = $totalWrkHrs + $data['wrkhrs'];
            }elseif ($canId != $data['candidateId']){
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->getStyle('G'. $rowCount )->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('G'. $rowCount)->applyFromArray($superCheckINArray);
                $objPHPExcel->getActiveSheet()->getStyle('H'. $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('H'. $rowCount)->applyFromArray($superCheckOUTArray);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount,'');
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, '');
                $totalWrkHrs = 0;
                $canId = $data['candidateId'];
                $totalWrkHrs = $totalWrkHrs + $data['wrkhrs'];
                $rowCount++;
            }elseif ($canId == $data['candidateId']){
                $totalWrkHrs = $totalWrkHrs + $data['wrkhrs'];
            }
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftDay']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getPositionByPositionId($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['supervisorCheckIn']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['supervisorCheckOut']);
            $objPHPExcel->getActiveSheet()->getStyle('G'. $rowCount )->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('G'. $rowCount)->applyFromArray($superCheckINArray);
            $objPHPExcel->getActiveSheet()->getStyle('H'. $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('H'. $rowCount)->applyFromArray($superCheckOUTArray);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['payrollCheckIn']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['payrollCheckOut']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['workBreak']);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['wrkhrs']);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getSupervisorNameById($mysqli, $supervisorId));
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['approvalTime']);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, timeChecker($data['checkIn'], $data['rosterStart']));
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, checkoutTimeChecker($data['checkOut'], $data['rosterEnd']));
            $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['checkIn']);
            $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['checkOut']);
            $k++;
        }
        $fileName = 'unconfirmedClockinReport_' . time() . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('../reports/' . $fileName);
        $filePath = '../reports/' . $fileName;
        echo $filePath;
    }
}
