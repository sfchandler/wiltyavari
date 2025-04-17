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
$supervisorClient = $_SESSION['supervisorClient'];
$supervisorDepartment = $_SESSION['supervisorDepartment'];
define('REPORT_HEADER_TITLE',getClientNameByClientId($mysqli,$supervisorClient).'                                                                                                               '.$startDate.' to '.$endDate);
$supervisorId = $_SESSION['supervisorId'];
$supervisorCheck = 'Y';
$clockInData = getConfirmedClockInData($mysqli,$startDate,$endDate,$empSelected,$supervisorClient,$supervisorCheck,$deptId);
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
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'ROSTER CHECKIN');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'ROSTER CHECK OUT');
        $objPHPExcel->getActiveSheet()->getStyle('K1' )->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('K1' )->applyFromArray($superCheckINArray);
        $objPHPExcel->getActiveSheet()->getStyle('L1' )->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->getStyle('L1' )->applyFromArray($superCheckOUTArray);
        $objPHPExcel->getActiveSheet()->setCellValue('I1', 'ACTUAL CHECK IN TIME');
        $objPHPExcel->getActiveSheet()->setCellValue('J1', 'ACTUAL CHECK OUT');
        $objPHPExcel->getActiveSheet()->setCellValue('K1', 'CONFIRMED CHECK IN BY SUPERVISOR');
        $objPHPExcel->getActiveSheet()->setCellValue('L1', 'CONFIRMED CHECK OUT BY SUPERVISOR');
        $objPHPExcel->getActiveSheet()->setCellValue('M1', 'WORK BREAK');
        $objPHPExcel->getActiveSheet()->setCellValue('N1', 'WORK HOURS');
        $objPHPExcel->getActiveSheet()->setCellValue('O1', 'SUPERVISOR');
        $objPHPExcel->getActiveSheet()->setCellValue('P1', 'APPROVED TIME');


        $objPHPExcel->getActiveSheet()->setTitle('Casuals Confirmed Timesheet');

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
                $objPHPExcel->getActiveSheet()->getStyle('K'. $rowCount )->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('K'. $rowCount)->applyFromArray($superCheckINArray);
                $objPHPExcel->getActiveSheet()->getStyle('L'. $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L'. $rowCount)->applyFromArray($superCheckOUTArray);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->applyFromArray($headingArray);
                $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('L' . $rowCount)->applyFromArray($headingArray);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $totalWrkHrs);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount,'');


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
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, timeChecker($data['checkIn'], $data['rosterStart']));
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, checkoutTimeChecker($data['checkOut'], $data['rosterEnd']));
            $objPHPExcel->getActiveSheet()->getStyle('K'. $rowCount )->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('K'. $rowCount)->applyFromArray($superCheckINArray);
            $objPHPExcel->getActiveSheet()->getStyle('L'. $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('L'. $rowCount)->applyFromArray($superCheckOUTArray);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['checkIn']);
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['checkOut']);
            $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['supervisorCheckIn']);
            $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['supervisorCheckOut']);
            $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['workBreak']);
            $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['wrkhrs']);
            $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, getSupervisorNameById($mysqli, $supervisorId));
            $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['approvalTime']);


            if ($k == $len - 1) {
                $rowCount++;
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, '');
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $totalWrkHrs);
                $objPHPExcel->getActiveSheet()->getStyle('N' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('N' . $rowCount)->applyFromArray($headingArray);
                $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount,'');
                $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, '');
            }
            $k++;
        }
        $fileName = 'clockinReport_' . time() . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('../supervisor/' . $fileName);
        $filePath = '../supervisor/' . $fileName;
        echo $filePath;
    }
}elseif ($action == 'PDF') {

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    define('PDF_CUSTOM_HEADER_STRING', 'ELECTRONIC TIMESHEET                                                                                                                                                           '.date("Y-m-d H:i:s"));
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Chandler');
    $pdf->SetTitle('TimeSheet ClockIn from Report');
    $pdf->SetSubject('TimeSheet ClockIn Report');
    $pdf->SetKeywords('TimeSheet ClockIn Report');
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, REPORT_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $fontname = TCPDF_FONTS::addTTFfont("../includes/TCPDF-master/fonts/Roboto-Regular.ttf",'TrueTypeUnicode','',32);
    $pdf->SetFont($fontname, '', 10);
    $pdf->AddPage();
    $fileName = 'clockinReport_'.time().'.pdf';
    $filePath = '../supervisor/'.$fileName;
    $html = $html.'<style>
                        table {
                            table-layout: fixed;
                            width: 100%;
                            white-space: nowrap;
                            border-collapse: collapse;
                            font-size: 8pt;
                            text-transform: uppercase;
                        }
                        td.cellWidth{
                            text-align: right;
                            width: 8%;
                        }
                        td.shortWidth{
                            text-align: right;
                            width: 5%;
                        }
                        td.empname{
                            text-align: left;
                        }
                        td.apptime{
                        text-align: right;
                        }
                        td.short{
                            width:10%;
                        }
                        td.desc{
                            text-align: left;
                            width:25%;
                        }
                        th{
                            font-weight: bold;
                            color: white;
                            background-color: #0CBBC1;
                            text-align: center;
                        }
                        td{
                            text-align: center;
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
                        .totalHrs{
                            background: #0aa66e;
                            font-weight: bold;
                            border: 1px solid black;
                        }
                        </style>
                    <table>
                        <thead>
                            <tr>
                                <th class="empId">Employee Name</th>
                                <th>Shift Date</th>
                                <th class="short">Shift Day</th>
                                <th>Position</th>
                                <th>Actual Check In Time</th>
                                <th>Actual Check Out Time</th>
                                <th>Confirmed Check In By Supervisor</th>
                                <th>Confirmed Check Out By Supervisor</th>
                                <th>Work Break</th>
                                <th>Work Hours</th>
                                <th>Supervisor</th>
                                <th>Approved Time</th>
                            </tr>
                        </thead>
                        <tbody>';
    $canId = '';
    $totalWrkHrs = 0;
    $len = sizeof($clockInData);
    $k = 0;
    foreach ($clockInData as $data){
        if(empty($canId)){
            $canId = $data['candidateId'];
            $totalWrkHrs = $totalWrkHrs + $data['wrkhrs'];
        }elseif ($canId != $data['candidateId']){
            $html = $html.'<tr class="zebra'.($j++ & 1 ).'">';
            $html = $html.'<td class="empname"></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td class="totalHrs">'.$totalWrkHrs.'</td>';
            $html = $html.'<td></td>';
            $html = $html.'<td class="apptime"></td></tr>';
            $totalWrkHrs = 0;
            $canId = $data['candidateId'];
            $totalWrkHrs = $totalWrkHrs + $data['wrkhrs'];
        }elseif ($canId == $data['candidateId']){
            $totalWrkHrs = $totalWrkHrs + $data['wrkhrs'];
        }
        $html = $html.'<tr class="zebra'.($j++ & 1 ).'">';
        $html = $html.'<td class="empname">'.getCandidateFullName($mysqli,$data['candidateId']).'</td>';
        $html = $html.'<td>'.$data['shiftDate'].'</td>';
        $html = $html.'<td>'.$data['shiftDay'].'</td>';
        $html = $html.'<td>'.getPositionByPositionId($mysqli,$data['positionId']).'</td>';
        $html = $html.'<td>'.$data['checkIn'].'</td>';
        $html = $html.'<td>'.$data['checkOut'].'</td>';
        $html = $html.'<td>'.$data['supervisorCheckIn'].'</td>';
/*        $html = $html.'<td>'.$data['payrollCheckIn'].'</td>';*/
        $html = $html.'<td>'.$data['supervisorCheckOut'].'</td>';
/*        $html = $html.'<td>'.$data['payrollCheckOut'].'</td>';*/
        $html = $html.'<td>'.$data['workBreak'].'</td>';
        $html = $html.'<td>'.$data['wrkhrs'].'</td>';
        $html = $html.'<td>'.getSupervisorNameById($mysqli, $supervisorId).'</td>';
        $html = $html.'<td class="apptime">'.$data['approvalTime'].'</td></tr>';
        if ($k == $len - 1) {
            $html = $html.'<tr class="zebra'.($j++ & 1 ).'">';
            $html = $html.'<td class="empname"></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td></td>';
            $html = $html.'<td class="totalHrs">'.$totalWrkHrs.'</td>';
            $html = $html.'<td></td>';
            $html = $html.'<td class="apptime"></td></tr>';
        }
        $k++;
    }
    $html = $html.'</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__. '/' . $fileName , 'F');
    echo $filePath;
}elseif ($action == 'ROSTER') {
    $rosterData = getRosteredConfirmedCasualsByDateRange($mysqli,$startDate,$endDate,$supervisorClient);
    if (!empty($rosterData)) {
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);

        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DATE');
        $objPHPExcel->getActiveSheet()->setCellValue('D1', 'SHIFT START TIME');
        $objPHPExcel->getActiveSheet()->setCellValue('E1', 'SHIFT END TIME');
        $objPHPExcel->getActiveSheet()->setCellValue('F1', 'WORK BREAK');
        $objPHPExcel->getActiveSheet()->setCellValue('G1', 'DEPARTMENT');
        $objPHPExcel->getActiveSheet()->setCellValue('H1', 'CLIENT NAME');

        $objPHPExcel->getActiveSheet()->setTitle('Confirmed Roster Information');

        $rowCount = 1;
        foreach ($rosterData as $data) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDate']);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftStart']);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftEnd']);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['workBreak']);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['department']);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['client']);
        }
        $fileName = 'rosterReport_' . time() . '.xlsx';
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('../reports/' . $fileName);
        $filePath = '../reports/' . $fileName;
        echo $filePath;
    }
}