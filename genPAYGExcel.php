<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 8/08/2019
 * Time: 11:25 AM
 */

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$fromDate = $_REQUEST['startDate'];
$toDate = $_REQUEST['endDate'];
$year = date("Y", strtotime($toDate));
$payData = getPayrunDataForPaygExcel($mysqli, $fromDate, $toDate, 0);

$candidateId = '';
$row = '';
$totalGross = 0;
$paygTax = 0;
$len = sizeof($payData);
$k = 0;
$category = '';
$allowance = 0;
$canId = '';
$allowanceArray = array();
$totalAllowancArray = array();
$paygArray = array();
$payeeArray = array();
foreach ($payData as $data) {
    if (empty($candidateId)) {
        $candidateId = $data['candidateId'];
    }
    if (empty($category)) {
        $category = $data['category'];
    }
    if ($candidateId == $data['candidateId']) {
        if ($data['itemType'] == 9) {
            $totalGross = $totalGross + $data['gross'];
            $paygArray[$data['candidateId']][$data['category']] = $totalGross;
        }
        if ($data['itemType'] == 11) {
            $paygTax = $paygTax + $data['paygTax'];
            $paygArray[$data['candidateId']][$data['category']] = $paygTax;
        }
        if ($data['itemType'] == 14) {
            if ($category == $data['category']) {
                $allowance = $allowance + $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $totalAllowancArray[$data['candidateId']] + $data['amount'];
            } elseif ($category <> $data['category']) {
                $category = '';
                $category = $data['category'];
                $allowance = $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $totalAllowancArray[$data['candidateId']] + $data['amount'];
            }
        }
    } else if ($candidateId <> $data['candidateId']) {
        $category = '';
        $allowance = 0;
        $totalGross = 0;
        $paygTax = 0;
        if ($data['itemType'] == 14) {
            if ($category == $data['category']) {
                $allowance = $allowance + $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            } elseif ($category <> $data['category']) {
                $category = '';
                $category = $data['category'];
                $allowance = $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            }
        }
        $candidateId = $data['candidateId'];
        if ($data['itemType'] == 9) {
            $totalGross = $data['gross'];
            $paygArray[$data['candidateId']][$data['category']] = $totalGross;
        }
        if ($data['itemType'] == 11) {
            $paygTax = $data['paygTax'];
            $paygArray[$data['candidateId']][$data['category']] = $paygTax;
        }
    }
    if ($k == $len - 1) {
        if ($data['itemType'] == 14) {
            if ($category == $data['category']) {
                $allowance = $allowance + $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            } elseif ($category <> $data['category']) {
                $category = '';
                $category = $data['category'];
                $allowance = $data['amount'];
                $allowanceArray[$data['candidateId']][$data['category']] = $allowance;
                $totalAllowancArray[$data['candidateId']] = $allowance;
            }
        }
        if ($data['itemType'] == 9) {
            $paygArray[$data['candidateId']][$data['category']] = $totalGross;
        }
        if ($data['itemType'] == 11) {
            $paygArray[$data['candidateId']][$data['category']] = $paygTax;
        }
    }
    $k++;
}
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE NAME');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'DATE OF BIRTH');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'TFN');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'GROSS');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'PAYGW');
$objPHPExcel->getActiveSheet()->setTitle('PAYG Excel Report');
$rowCount = 1;
foreach ($paygArray as $key => $value) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $key);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, strtoupper(getCandidateFullName($mysqli, $key)));
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, getCandidateDOBById($mysqli,$key));
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, chunk_split(getCandidateTFN($mysqli, $key), 3, ' '));
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $value['Gross']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $value['PAYG Tax']);
}
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('./payg/PAYGExcelReport.xlsx');
echo './payg/PAYGExcelReport.xlsx';