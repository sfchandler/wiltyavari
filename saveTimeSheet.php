<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
/*if ($_POST['submitBtn'] == "DELETE") {
    foreach ($_POST['shiftId'] as $cnt => $shiftId) {
        if (isset($_POST['timeSheetTick'][$cnt])) {
            deleteTimeSheet($mysqli, $_POST['shiftId'][$cnt]);
            updateShiftTimeSheetStatus($mysqli, $_POST['shiftId'][$cnt], NULL);
        }
    }
    echo 'DELETED';
} else*/
    try {
        if (!empty($_POST['shiftId'])) {
            $shiftArray = array();
            foreach ($_POST['shiftId'] as $cnt => $shiftId) {
                $deptId = $_POST['deptId'][$cnt];
                $row = array('shiftId' => $_POST['shiftId'][$cnt], 'shiftDay' => $_POST['shiftDay'][$cnt], 'shiftDate' => $_POST['shiftDate'][$cnt], 'candidateId' => $_POST['candidateId'][$cnt], 'clientId' => $_POST['clid'][$cnt], 'positionId' => $_POST['posid'][$cnt], 'deptId' => $_POST['deptId'][$cnt], 'jobCode' => $_POST['jobCode'][$cnt], 'shiftStart' => $_POST['shiftStart'][$cnt], 'shiftEnd' => $_POST['shiftEnd'][$cnt], 'workBreak' => $_POST['workBreak'][$cnt], 'wrkhrs' => $_POST['wrkhrs'][$cnt], 'wkendingDate' => $_POST['wkendingDate'], 'transport' => $_POST['transport'][$cnt]);
                $shiftArray[] = $row;
                $status = saveTimeSheet($mysqli, $_POST['shiftId'][$cnt], $_POST['tandaShiftId'][$cnt], $_POST['shiftDay'][$cnt], $_POST['shiftDate'][$cnt], $_POST['candidateId'][$cnt], $_POST['clid'][$cnt], $_POST['posid'][$cnt], $_POST['deptId'][$cnt], $_POST['jobCode'][$cnt], $_POST['shiftStart'][$cnt], $_POST['shiftEnd'][$cnt], $_POST['workBreak'][$cnt], $_POST['wrkhrs'][$cnt], $_POST['wkendingDate'], $_POST['transport'][$cnt], $_POST['timeSheetTick'][$cnt]);
            }
            if (!empty($shiftArray)) {
                $html = $html . '<style>
                table {
                    table-layout: fixed;
                    width: 100%;
                    white-space: nowrap;
                    border-collapse: collapse;
                    font-size: 8pt;
                    word-wrap:break-word;
                }
                td.cellWidth{
                    text-align: right;
                    width: 5%;
                }
                td.cellDate{
                    text-align: right;
                    width: 6%;
                }
                td.cellClient{
                    text-align: left;
                    width: 10%;
                }
                td.desc{
                    text-align: left;
                    width:15%;
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
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Shift ID</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Shift Day</th>
                    <th class="cellDate" style="text-align: center;width: 6%;text-transform: uppercase;">Shift Date</th>
                    <th class="cellClient" style="text-align: center;width: 10%;text-transform: uppercase;">Employee ID</th>
                    <th class="desc" style="text-align: center;width: 15%;text-transform: uppercase;">Employee Name</th>
                    <th class="cellClient" style="text-align: center;width: 10%;text-transform: uppercase;">Client</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Position</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Department</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">JobCode</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Shift Start</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Shift End</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Work Break</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Work Hours</th>
                    <th class="cellWidth" style="text-align: center;width: 5%;text-transform: uppercase;">Weekending</th>
                    <th class="cellDate" style="text-align: center;width: 6%;text-transform: uppercase;">Transport</th>
                </tr>    
            </thead>
            <tbody>';
                $objPHPExcel = new PHPExcel();
                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->setCellValue('A1', 'SHIFT ID');
                $objPHPExcel->getActiveSheet()->setCellValue('B1', 'SHIFT DAY');
                $objPHPExcel->getActiveSheet()->setCellValue('C1', 'SHIFT DATE');
                $objPHPExcel->getActiveSheet()->setCellValue('D1', 'EMPLOYEE ID');
                $objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMPLOYEE NAME');
                $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CLIENT');
                $objPHPExcel->getActiveSheet()->setCellValue('G1', 'POSITION');
                $objPHPExcel->getActiveSheet()->setCellValue('H1', 'DEPARTMENT');
                $objPHPExcel->getActiveSheet()->setCellValue('I1', 'JOBCODE');
                $objPHPExcel->getActiveSheet()->setCellValue('J1', 'SHIFT START');
                $objPHPExcel->getActiveSheet()->setCellValue('K1', 'SHIFT END');
                $objPHPExcel->getActiveSheet()->setCellValue('L1', 'WORK BREAK');
                $objPHPExcel->getActiveSheet()->setCellValue('M1', 'WORK HOURS');
                $objPHPExcel->getActiveSheet()->setCellValue('N1', 'WEEKENDING');
                $objPHPExcel->getActiveSheet()->setCellValue('O1', 'TRANSPORT');
                $objPHPExcel->getActiveSheet()->setTitle('Employees Allocated Export');
                $rowCount = 1;
                foreach ($shiftArray as $data) {
                    $rowCount++;
                    $fullName = getCandidateFullName($mysqli, $data['candidateId']) . '(' . getNickNameById($mysqli, $data['candidateId']) . ')';
                    $clientName = getClientNameByClientId($mysqli, $data['clientId']);
                    $empPosition = getPositionByPositionId($mysqli, $data['positionId']);
                    $empDepartment = getDepartmentById($mysqli, $data['deptId']);

                    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['shiftId']);
                    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['shiftDay']);
                    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['shiftDate']);
                    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['candidateId']);
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $fullName);
                    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $clientName);
                    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $empPosition);
                    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $empDepartment);
                    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['jobCode']);
                    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['shiftStart']);
                    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['shiftEnd']);
                    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['workBreak']);
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['wrkhrs']);
                    $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['wkendingDate']);
                    $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['transport']);

                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
                    $html = $html . '<td class="cellWidth">' . $data['shiftId'] . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['shiftDay'] . '</td>';
                    $html = $html . '<td class="cellDate">' . $data['shiftDate'] . '</td>';
                    $html = $html . '<td class="cellClient">' . $data['candidateId'] . '</td>';
                    $html = $html . '<td class="desc">' . $fullName . '</td>';
                    $html = $html . '<td class="cellClient">' . $clientName . '</td>';
                    $html = $html . '<td class="cellWidth">' . $empPosition . '</td>';
                    $html = $html . '<td class="cellWidth">' . $empDepartment . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['jobCode'] . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['shiftStart'] . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['shiftEnd'] . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['workBreak'] . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['wrkhrs'] . '</td>';
                    $html = $html . '<td class="cellWidth">' . $data['wkendingDate'] . '</td>';
                    $html = $html . '<td class="cellDate">' . $data['transport'] . '</td>';
                    $html = $html . '</tr>';
                }
                $html = $html . '</tbody></table>';
                $time = time();
                $fullPath = './timesheet/timesheetSaving-' . $time . '.xlsx';
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                $objWriter->save($fullPath);
                echo $fullPath;
            } else {
                echo 'CHECKBOX';
            }
        }
    }catch (Exception $e){
        echo $e->getMessage();
    }

?>