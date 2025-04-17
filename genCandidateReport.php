<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
$candidateInfo = getActiveCandidateInfo($mysqli);


$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATE ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'NICKNAME');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'MOBILENO');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'EMAIL');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'GENDER');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'DOB');
$objPHPExcel->getActiveSheet()->setCellValue('I1','TFN');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'ADDRESS');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'STREET NUMBER');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'STREET NAME');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'SUBURB');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'STATE');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'POSTCODE');
$objPHPExcel->getActiveSheet()->setCellValue('P1','WORK START DATE');
$objPHPExcel->getActiveSheet()->setCellValue('Q1','SUPER MEMBER NO');
$objPHPExcel->getActiveSheet()->setCellValue('R1','SUPER FUND NAME');
$objPHPExcel->getActiveSheet()->setCellValue('S1','TAX CODE DESCRIPTION');
$objPHPExcel->getActiveSheet()->setCellValue('T1','BANK ACCOUNT NAME');
$objPHPExcel->getActiveSheet()->setCellValue('U1','BANK ACCOUNT NUMBER');
$objPHPExcel->getActiveSheet()->setCellValue('V1','BSB');
$objPHPExcel->getActiveSheet()->setCellValue('W1','POSITION');
$objPHPExcel->getActiveSheet()->setCellValue('X1','REGPACK STATUS');
$objPHPExcel->getActiveSheet()->setCellValue('Y1','REGPACK SENT TIME');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'FOUND US BY');


$rowCount = 1;
foreach ($candidateInfo as $data){
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['nickname']);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['mobileNo']);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['sex']);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['dob']);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['tfn']);
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['address']);
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['street_number']);
    $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['street_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['suburb']);
    $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $data['state']);
    $objPHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $data['postcode']);
    $objPHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $data['workStartDate']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $data['superMemberNo']);
    $objPHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $data['transCodeDesc']);
    $objPHPExcel->getActiveSheet()->setCellValue('S' . $rowCount, $data['taxcodeDesc']);
    $objPHPExcel->getActiveSheet()->setCellValue('T' . $rowCount, $data['accountName']);
    $objPHPExcel->getActiveSheet()->setCellValue('U' . $rowCount, $data['accountNumber']);
    $objPHPExcel->getActiveSheet()->setCellValue('V' . $rowCount, $data['bsb']);

    $positionList = getEmployeePositionList($mysqli,$data['candidateId']);
    //$objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, var_dump($positionList));
    $positions = '';
    $comma=', ';
    $k=0;
    $len=sizeof($positionList);
    foreach($positionList as $pos){
        if ($k == $len - 1) {
            $comma = '';
            $k++;
        }
        $positions = $positions.$pos['positionName'].$comma;
    }
    $objPHPExcel->getActiveSheet()->setCellValue('W' . $rowCount, $positions);

    if($data['reg_pack_status'] == 1){
        $regpack = 'RECEIVED';
    }else{
        $regpack = '';
    }
    $objPHPExcel->getActiveSheet()->setCellValue('X' . $rowCount, $regpack);
    $objPHPExcel->getActiveSheet()->setCellValue('Y' . $rowCount, getRegPackSentTime($mysqli,$data['candidateId']));
    $objPHPExcel->getActiveSheet()->setCellValue('Z' . $rowCount, getCandidateFoundHow($mysqli,$data['candidateId']));
}
$time = time();
$filePath = './reports/candidateReport-'.$time.'.xlsx';
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save($filePath);
echo $filePath;
