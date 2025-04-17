<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 23/11/2018
 * Time: 10:50 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

$payDateInfo = $_POST['payDateInfo'];
$companyId = $_POST['companyId'];
$logoPath = getCompanyLogoById($mysqli,$companyId);
$pData = explode('|',$payDateInfo);
$weekendingDate = $pData[1];
$payrunId = $pData[0];
$payrollName = getPayrollNameById($mysqli,$_POST['payrollName']);
$abn = getCompanyABN($mysqli,$companyId);
$acn = getCompanyACN($mysqli,$companyId);
$companyFax = getCompanyFax($mysqli,$companyId);
$companyPhone = getCompanyPhone($mysqli,$companyId);
$website = getCompanyWebsite($mysqli,$companyId);
$companyName = getCompanyNameById($mysqli,$companyId);
$companyAddress = getCompanyAddress($mysqli,$companyId);
if(strtotime('july', strtotime($weekendingDate)) > strtotime($weekendingDate)){
    $currentJuly = date('Y-m-d',strtotime('1st july', strtotime($weekendingDate)));
    $yearStartDate = date('Y-m-d',strtotime('-1 year', strtotime($currentJuly)));
}else{
    $yearStartDate = date('Y-m-d',strtotime('1st july', strtotime($weekendingDate)));
}
$payrunData = getPayrunDataByDate($mysqli,$weekendingDate,$payrunId);
$empArray = array();
function processCSV($mysqli,$empId,$wkendDate,$runId) //,$logoPath,$abn,$companyName,$acn,$companyPhone,$companyFax,$website,$companyAddress,$payDate
{
    return $payData = getPayrunDataByEmployee($mysqli,$empId,$wkendDate,$runId);
    /*foreach($payData as $data) {
        if ($data['itemType'] == 10) {
            $empArray[$data['candidateId']]['deduction'] = number_format($data['deduction'],2);
        }else if($data['itemType']==14){
            $empArray[$data['candidateId']]['allowance'] = number_format($data['amount'],2);
        }else if($data['itemType']==12) {
            $empArray[$data['candidateId']]['super'] = number_format($data['super'],2);
        }else if($data['itemType']==11) {
            $empArray[$data['candidateId']]['PAYG'] = number_format($data['paygTax'],2);
        }else if($data['itemType']==9) {
            $empArray[$data['candidateId']]['Gross'] = number_format($data['gross'],2);
        }else if($data['itemType']==13) {
            $empArray[$data['candidateId']]['Net'] = number_format($data['net'],2);
        }
    }*/
}
foreach($payrunData as $data){
    $empArray[$data['candidateId']] = array('Entity ABN'=>'',
                                            'BMS ID'=>'',
                                            'Branch ID'=>'',
                                            'Product ID'=>'',
                                            'Period W1 value'=>'',
                                            'Period W2 value'=>'',
                                            'Payment Date'=>'',
                                            'Payroll number'=>'',
                                            'Employee TFN'=>'',
                                            'Family name'=>'',
                                            'Given name'=>'',
                                            'Date of birth'=>'',
                                            'Address 1'=>'',
                                            'Suburb'=>'',
                                            'State'=>'',
                                            'Postcode'=>'',
                                            'PayPeriodStart'=>'',
                                            'PayPeriodEnd'=>'',
                                            'EOYIndicator'=>'',
                                            'EmployeeGrossPay'=>'',
                                            'EmployeeTaxYTD'=>'',
                                            'SuperAmount'=>'',
                                            'SGEarning'=>'',
                                            'TransportAllowance'=>'',
                                            'MealAllowance'=>'',
                                            'TravelAllowance'=>'',
                                            'UnionFee'=>'',
                                            'WorkplaceGiving'=>'',
                                            'WorkingHolidayMakerGross'=>'',
                                            'WorkingHolidayMakerPAYG'=>'');
    $csvData = processCSV($mysqli,$data['candidateId'],$weekendingDate,$payrunId); //$logoPath,$abn,$companyName,$acn,$companyPhone,$companyFax,$website,$companyAddress,$payDate
    foreach($csvData as $rs) {
        $empArray[$rs['candidateId']]['Employee TFN'] = getCandidateTFN($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['Family name'] = getCandidateLastNameByCandidateId($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['Given name'] = getCandidateFirstNameByCandidateId($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['Date of birth'] = trim(getCandidateDOBById($mysqli,$rs['candidateId']));//date('d/M/y',strtotime(getCandidateDOBById($mysqli,$rs['candidateId'])));
        $empArray[$rs['candidateId']]['Address 1'] = getCandidateStreetById($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['Suburb'] = getCandidateSuburb($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['State'] = getCandidateState($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['Postcode'] = getCandidatePostcode($mysqli,$rs['candidateId']);
        $empArray[$rs['candidateId']]['Payroll number'] = $payrunId;
        $yearToDate = getYearToDateData($mysqli,$rs['candidateId'],$yearStartDate,$weekendingDate);
        foreach ($yearToDate as $yearData) {
            $employeeTax = $yearData['totalTax'];
            $empArray[$rs['candidateId']]['EmployeeTaxYTD'] = number_format($employeeTax,2);
        }
        if ($rs['itemType'] == 10) {
            $empArray[$rs['candidateId']]['EmployeeDeduction'] = number_format($rs['deduction'],2);
            //$empArray[$rs['candidateId']]['EmployeeTax'] = number_format($rs['deduction'],2);
        }else if($rs['itemType']==14){
            $empArray[$rs['candidateId']]['TravelAllowance'] = number_format($rs['amount'],2);
        }else if($rs['itemType']==12) {
            $empArray[$rs['candidateId']]['SuperAmount'] = number_format($rs['super'],2);
        }else if($rs['itemType']==11) {
            $empArray[$rs['candidateId']]['WorkingHolidayMakerPAYG'] = number_format($rs['paygTax'],2);
        }else if($rs['itemType']==9) {
            $empArray[$rs['candidateId']]['EmployeeGrossPay'] = number_format($rs['gross'],2);
        }else if($rs['itemType']==13) {
            //$empArray[$rs['candidateId']]['Net'] = number_format($rs['net'],2);
        }
    }
}
//echo var_dump($empArray);
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Entity ABN');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'BMS ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Branch ID');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Product ID');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Period W1 value');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Period W2 value');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Payment Date');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Payroll number');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Employee TFN');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Family name');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Given name');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Date of birth');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Address 1');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Suburb');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'State/territory');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Postcode');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Pay period start date');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Pay period end date');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Final EOY pay indicator');
$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Employee gross pay');
$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Employee tax');
$objPHPExcel->getActiveSheet()->setCellValue('V1', 'Super guarantee amount');
$objPHPExcel->getActiveSheet()->setCellValue('W1', 'SG earnings amount');
$objPHPExcel->getActiveSheet()->setCellValue('X1', 'Transport allowance');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Meal allowance');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Travel allowance');
$objPHPExcel->getActiveSheet()->setCellValue('AA1', 'Union fees');
$objPHPExcel->getActiveSheet()->setCellValue('AB1', 'Workplace giving');
$objPHPExcel->getActiveSheet()->setCellValue('AC1', 'Working holiday maker gross');
$objPHPExcel->getActiveSheet()->setCellValue('AD1', 'Working holiday maker PAYG');

$objPHPExcel->getActiveSheet()->setTitle('SINGLE TOUCH REPORT');

$rowCount = 1;
$totalGross = getPayrunTotalGrossByWeekending($mysqli,$weekendingDate,$payrunId);
$totalPaygTax = getPayrunTotalPaygTaxByWeekending($mysqli,$weekendingDate,$payrunId);
$payDate = getPayDateByPayrunId($mysqli,$payrunId);
$payPeriodStartDate = getPayPeriodStartDate($mysqli,$payrunId);
$payPeriodEndDate = getPayPeriodEndDate($mysqli,$payrunId);
$companyABN = getCompanyABN($mysqli,$companyId);
foreach ($empArray as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $companyABN);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, strtoupper($companyName));//'CHANDLER SOLUTIONS PTY LTD'
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, '1');
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, '10552');
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $totalGross);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $totalPaygTax);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $payDate);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $data['Payroll number']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, trim($data['Employee TFN']));
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowCount, $data['Family name']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, $data['Given name']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowCount, $data['Date of birth']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowCount, $data['Address 1']);
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowCount, $data['Suburb']);
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowCount, $data['State']);
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowCount, $data['Postcode']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowCount, date('d/m/Y',strtotime($payPeriodStartDate)));
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$rowCount, date('d/m/Y',strtotime($payPeriodEndDate)));
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$rowCount, $data['EmployeeGrossPay']);
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$rowCount, $data['EmployeeTaxYTD']);
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$rowCount, $data['SuperAmount']);
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$rowCount, $data['TravelAllowance']);
    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$rowCount, $data['WorkingHolidayMakerPAYG']);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
$filePath = './singletouch/singletouch'.time().'.csv';
$objWriter->save($filePath);
echo $filePath;

//echo var_dump($empArray);