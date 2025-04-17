<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 7/05/2019
 * Time: 11:53 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$clientId = $_POST['clientId'];
$param1 = 'SICK';
$param2 = 'CANCELLATION WITH NOTICE';
$param3 = 'CANCELLATION WITHOUT NOTICE';
$param4 = 'NO SHOW';
$param5 = 'REJECTED';
$param6 = 'CANCELLED BY AGENCY';

if($clientId == 'All'){
        $sql = $mysqli->prepare('SELECT
                                          candidate.candidateId,   
                                          candidate.firstName,
                                          candidate.lastName,
                                          candidate_position.positionName,
                                          candidate.mobileNo,
                                          candidate.email,
                                          shift.shiftDate,
                                          shift.shiftStatus,
                                          client.client,
                                          department.department
                                        FROM
                                          shift
                                          INNER JOIN client ON (shift.clientId = client.clientId)
                                          INNER JOIN candidate_position ON (shift.positionId = candidate_position.positionid)
                                          INNER JOIN candidate ON (shift.candidateId = candidate.candidateId)
                                          INNER JOIN department ON (shift.departmentId = department.deptId)
                                        WHERE
                                          shift.shiftStatus IN("SICK","CANCELLATION WITH NOTICE","CANCELLATION WITHOUT NOTICE","NO SHOW","REJECTED","CANCELLED BY AGENCY") AND
                                          shift.shiftDate BETWEEN ? AND ?
                                        ORDER BY
                                          client.client,
                                          shift.shiftDate') or die($mysqli->error);
        $sql->bind_param("ss",  $startDate, $endDate) or die($mysqli->error);
}else {
        $sql = $mysqli->prepare("SELECT 
                                      candidate.candidateId,  
                                      candidate.firstName,
                                      candidate.lastName,
                                      candidate_position.positionName,
                                      candidate.mobileNo,
                                      candidate.email,
                                      shift.shiftDate,
                                      shift.shiftStatus,
                                      client.client,
                                      department.department
                                    FROM
                                      shift
                                      INNER JOIN client ON (shift.clientId = client.clientId)
                                      INNER JOIN candidate_position ON (shift.positionId = candidate_position.positionid)
                                      INNER JOIN candidate ON (shift.candidateId = candidate.candidateId)
                                      INNER JOIN department ON (shift.departmentId = department.deptId)
                                    WHERE
                                      shift.shiftStatus IN(?,?,?,?,?,?) AND
                                      shift.clientId = ? AND    
                                      shift.shiftDate BETWEEN ? AND ?
                                    ORDER BY
                                      client.client,
                                      shift.shiftDate") or die($mysqli->error);
    $sql->bind_param("ssssssiss",$param1,$param2,$param3,$param4,$param5,$param6,$clientId, $startDate, $endDate) or die($mysqli->error);
}
$sql->execute();
$sql->bind_result($candidateId,$firstName, $lastName, $positionName, $mobileNo, $email, $shiftDate, $shiftStatus, $client, $department) or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
if ($num_of_rows > 0) {
    while ($sql->fetch()) {
        $row = array('candidateId'=>$candidateId,'firstName' => $firstName, 'lastName' => $lastName, 'position' => $positionName, 'mobile' => $mobileNo, 'email' => $email, 'shiftDate' => $shiftDate, 'shiftStatus' => $shiftStatus, 'client' => $client, 'department'=>$department);
        $dataArray[] = $row;
    }
} else {
    echo 'NODATA';//'No data '.$num_of_rows;
}
$dataSet = $dataArray;
$objPHPExcel = new PHPExcel();
$styleArray = array(
    'font' => array(
        'bold' => true,
        'color' => array('rgb' => '666666'),
        'size' => 11,
        'name' => 'Calibri'
    ),
    'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array('rgb' => 'f2f2f2')
    )
);

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1:T1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'POSITION');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'MOBILE');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMAIL');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'SHIFT DATE');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'SHIFT STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'CLIENT');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'DEPARTMENT');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'MEDICAL CERTIFICATE UPLOADED TIME');

$objPHPExcel->getActiveSheet()->setTitle('SICK NO SHOW REPORT');

$rowCount = 1;
foreach ($dataSet as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['position']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['mobile']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['shiftStatus']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['client']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['department']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, getCandidateDocumentDateByDocTypeId($mysqli,$data['candidateId'],72));
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$time = time();
if(!empty($client)){
    $clientName = $client;
}else{
    $clientName = 'All_';
}
$fileName = './reports/' . $clientName . '_sickReport_' . $time . '.xlsx';
$objWriter->save($fileName);
echo $fileName;