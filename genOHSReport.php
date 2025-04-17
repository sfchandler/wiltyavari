<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$clId = $_POST['clientId'];
$clientName = '';
$ohs_all = $_POST['ohs_all'];
if ($ohs_all == 'EVERYTHING') {
    $clientName = 'All';
    $sql = $mysqli->prepare("SELECT 
                                      employee_allocation.candidateId,
                                      employee_allocation.clientId,
                                      employee_allocation.stateId,
                                      employee_allocation.deptId,
                                      employee_allocation.ohs_sent_time,
                                      candidate.firstName,
                                      candidate.lastName,
                                      employee_allocation.ohsCheckStatus,
                                      employee_allocation.ohsCheckedBy,
                                      employee_allocation.ohsCheckedTime,
                                      client.client
                                    FROM
                                      employee_allocation
                                      INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                      INNER JOIN client ON (employee_allocation.clientId = client.clientId)
                                    WHERE
                                      employee_allocation.ohs_sent_time IS NULL
                                    ORDER BY client.client ASC") or die($mysqli->error);
}else {
    if ($clId == 'All') {
        $clientName = 'All';
        $sql = $mysqli->prepare("SELECT 
                                      employee_allocation.candidateId,
                                      employee_allocation.clientId,
                                      employee_allocation.stateId,
                                      employee_allocation.deptId,
                                      employee_allocation.ohs_sent_time,
                                      candidate.firstName,
                                      candidate.lastName,
                                      employee_allocation.ohsCheckStatus,
                                      employee_allocation.ohsCheckedBy,
                                      employee_allocation.ohsCheckedTime,
                                      client.client
                                    FROM
                                      employee_allocation
                                      INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                      INNER JOIN client ON (employee_allocation.clientId = client.clientId)
                                    WHERE
                                      employee_allocation.ohs_sent_time BETWEEN ? AND ?
                                    ORDER BY client.client ASC") or die($mysqli->error);
        $sql->bind_param("ss", $startDate, $endDate) or die($mysqli->error);
    } else {
        $sql = $mysqli->prepare("SELECT 
                                      employee_allocation.candidateId,
                                      employee_allocation.clientId,
                                      employee_allocation.stateId,
                                      employee_allocation.deptId,
                                      employee_allocation.ohs_sent_time,
                                      candidate.firstName,
                                      candidate.lastName,
                                      employee_allocation.ohsCheckStatus,
                                      employee_allocation.ohsCheckedBy,
                                      employee_allocation.ohsCheckedTime,
                                      client.client
                                    FROM
                                      employee_allocation
                                      INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                      INNER JOIN client ON (employee_allocation.clientId = client.clientId)
                                    WHERE
                                      employee_allocation.ohs_sent_time BETWEEN ? AND ? AND 
                                      employee_allocation.clientId = ?
                                    ORDER BY client.client ASC") or die($mysqli->error);
        $sql->bind_param("ssi", $startDate, $endDate, $clId) or die($mysqli->error);
    }
}
$sql->execute();
$sql->bind_result($candidateId, $clientId, $stateId,$deptId,$ohs_sent_time,$firstName,$lastName,$ohsCheckStatus,$ohsCheckedBy,$ohsCheckedTime,$client) or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
if ($num_of_rows > 0) {
    $doc_info = '';
    $doc_submitted_time = '';
    $doc_contact = '';
    while ($sql->fetch()) {
        $row = array('candidateId' => $candidateId,'firstName'=>$firstName,'lastName'=>$lastName,'clientId'=>$clientId,'stateId'=>$stateId,'deptId'=>$deptId,'ohs_sent_time'=>$ohs_sent_time,'ohsCheckStatus'=>$ohsCheckStatus,'ohsCheckedBy'=>$ohsCheckedBy,'ohsCheckedTime'=>$ohsCheckedTime,'client'=>$client);
        $dataArray[] = $row;
    }
} else {
    echo 'NODATA';
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
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATE ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'COMPANY NAME');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'STATE');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'DEPARTMENT');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'OHS SMS SENT TIME');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'OHS SUBMITTED TIME');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'NEED DISCUSSION');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'OHS CHECK STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'OHS CHECKED BY');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'OHS CHECKED AT');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'EMPLOYEE STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'AUDIT STATUS');

$objPHPExcel->getActiveSheet()->setTitle('OHS REPORT');

$rowCount = 1;

foreach ($dataSet as $data) {
    $clientName = $data['client'];
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getClientNameByClientId($mysqli,$data['clientId']));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getStateById($mysqli,$data['stateId']));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, getDepartmentById($mysqli,$data['deptId']));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['ohs_sent_time']);
    $doc_info = getOHSDocumentInfo($mysqli,$data['candidateId'],$data['clientId'],$data['stateId'],$data['deptId']);
    $doc_submitted_time = '';
    $feedback = '';
    if(!empty($doc_info)) {
        $doc = explode('@', $doc_info);
        $doc_submitted_time = $doc[0];
        if($doc[1] == '!!'){
            $feedback = 'ASAP';
        }
    }
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $doc_submitted_time);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $feedback);
    if(!empty($doc_submitted_time)){
        $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['ohsCheckStatus']);
    }
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['ohsCheckedBy']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['ohsCheckedTime']);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, getEmployeeStatus($mysqli,$data['candidateId']));
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, getAuditStatus($mysqli,$data['candidateId']));
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$time = time();
$fileName = './reports/' . $clientName . '_ohsReport_' . $time . '.xlsx';
$objWriter->save($fileName);
echo $fileName;