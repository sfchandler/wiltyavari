<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/01/2019
 * Time: 1:41 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];

$sql = $mysqli->prepare("SELECT 
                                  candidate.firstName,
                                  candidate.nickname,
                                  candidate.lastName,
                                  timesheet.candidateId,
                                  client.client,
                                  department.department,
                                  candidate_position.positionName,
                                  timesheet.jobCode,
                                  timesheet.shiftDate,
                                  timesheet.shiftStart,
                                  timesheet.shiftEnd,
                                  timesheet.wrkHrs,
                                  timesheet.weekendingDate,                                
                                  timesheet.supervisorEdit
                                FROM
                                  timesheet
                                  INNER JOIN client ON (timesheet.clientId = client.clientId)
                                  INNER JOIN candidate_position ON (timesheet.positionId = candidate_position.positionid)
                                  INNER JOIN department ON (timesheet.deptId = department.deptId)
                                  INNER JOIN candidate ON (timesheet.candidateId = candidate.candidateId)
                                WHERE
                                  timesheet.shiftDate BETWEEN ? AND ?
                                ORDER BY
                                  timesheet.candidateId,
                                  timesheet.shiftDate,
                                  timesheet.weekendingDate")or die($mysqli->error);
$sql->bind_param("ss",$startDate,$endDate)or die($mysqli->error);
$sql->execute();
$sql->bind_result($firstName,$nickname,$lastName,$candidateId,$client,$department,$positionName,$jobCode,$shiftDate,$shiftStart,$shiftEnd,$wrkHrs,$weekendingDate,$supervisorEdit)or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
if($num_of_rows>0) {
    $canId = '';
    $workHours = 0;
    $grandTotal = 0;
    $k = 0;
    $len = $num_of_rows;
    while ($sql->fetch()) {
        if(empty($canId)){
            $canId = $candidateId;
        }else if($canId == $candidateId){

        }else if($canId != $candidateId){
            $dataArray[] = array('firstName'=> 'Total hours','nickname'=> '','lastName'=> '','employeeID'=> '','client'=> '','department'=> '','positionName'=> '','jobCode'=>'','shiftDate'=>'','shiftStart'=>'','shiftEnd'=>'','wrkHrs'=>$workHours,'weekendingDate'=>'','supervisorEditStatus'=>'');
            $workHours = 0;
            $canId = $candidateId;
        }
        $workHours = $workHours + $wrkHrs;
        $grandTotal = $grandTotal + $wrkHrs;
        $dataArray[] = array('firstName'=>$firstName,'nickname'=>$nickname,'lastName'=>$lastName,'employeeID'=>$candidateId,'client'=>$client,'department'=>$department,'positionName'=>$positionName,'jobCode'=>$jobCode,'shiftDate'=>$shiftDate,'shiftStart'=>$shiftStart,'shiftEnd'=>$shiftEnd,'wrkHrs'=>$wrkHrs,'weekendingDate'=>$weekendingDate,'supervisorEditStatus'=>$supervisorEdit);
        if ($k == $len - 1) {
            $dataArray[] = array('firstName'=> 'Total hours','nickname'=> '','lastName'=> '','employeeID'=> '','client'=> '','department'=> '','positionName'=> '','jobCode'=>'','shiftDate'=>'','shiftStart'=>'','shiftEnd'=>'','wrkHrs'=>$workHours,'weekendingDate'=>'','supervisorEditStatus'=>'');
        }
        $k++;
    }
    $dataArray[] = array('firstName'=> 'Grand Total','nickname'=> '','lastName'=> '','employeeID'=> '','client'=> '','department'=> '','positionName'=> '','jobCode'=>'','shiftDate'=>'','shiftStart'=>'','shiftEnd'=>'','wrkHrs'=>$grandTotal,'weekendingDate'=>'','supervisorEditStatus'=>'');
}else{
    echo 'No data '.$num_of_rows;
}
$dataSet = $dataArray;
$objPHPExcel = new PHPExcel();
$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '666666'),
        'size'  => 11,
        'name'  => 'Calibri'
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'f2f2f2')
    )
);

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1:N1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'NICKNAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'EMPLOYEE ID');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'CLIENT');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'DEPARTMENT');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'POSITION');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'JOB CODE');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'SHIFT DATE');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'SHIFT START');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'SHIFT END');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'WORK HOURS');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'WEEKENDING DATE');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'SUPERVISOR EDIT STATUS');

$objPHPExcel->getActiveSheet()->setTitle('TIME SHEET HOURS REPORT');

$rowCount = 1;
foreach ($dataSet as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $data['nickname']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $data['employeeID']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $data['client']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $data['department']);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $data['positionName']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $data['jobCode']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $data['shiftDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowCount, $data['shiftStart']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, $data['shiftEnd']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowCount, $data['wrkHrs']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowCount, $data['weekendingDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowCount, $data['supervisorEditStatus']);
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$time = time();
$fileName = './reports/'.$time.'-timesheetHoursReport.xlsx';
$objWriter->save($fileName);
echo $fileName;
