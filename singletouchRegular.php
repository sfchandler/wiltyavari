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

$companyId = $_POST['companyId'];
$logoPath = getCompanyLogoById($mysqli,$companyId);
$payrollName = getPayrollNameById($mysqli,1);
$abn = getCompanyABN($mysqli,$companyId);
$acn = getCompanyACN($mysqli,$companyId);
$companyFax = getCompanyFax($mysqli,$companyId);
$companyPhone = getCompanyPhone($mysqli,$companyId);
$website = getCompanyWebsite($mysqli,$companyId);
$companyName = getCompanyNameById($mysqli,$companyId);
$companyAddress = getCompanyAddress($mysqli,$companyId);
$wkendDateStart = $_POST['financialYearStart'];
$wkendDateEnd = $_POST['financialYearEnd'];
$payrunData = getPayrunDataByDateRange($mysqli,$wkendDateStart,$wkendDateEnd);
if(strtotime('july', strtotime($wkendDateStart)) > strtotime($wkendDateStart)){
    $currentJuly = date('Y-m-d',strtotime('1st july', strtotime($wkendDateStart)));
    $yearStartDate = date('Y-m-d',strtotime('-1 year', strtotime($currentJuly)));
}else{
    $yearStartDate = date('Y-m-d',strtotime('1st july', strtotime($wkendDateStart)));
}

$empArray = array();
function processCSV($mysqli,$empId,$wkendDateStart,$wkendDateEnd)
{
    return $payData = getPayrunDataByEmployeeForRange($mysqli,$empId,$wkendDateStart,$wkendDateEnd);
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
        'WorkingHolidayMakerPAYG'=>'',
        'Hired date'=>'',
        'Country'=>'',
        'email'=>'',
        'phone'=>'');
    $csvData = processCSV($mysqli,$data['candidateId'],$wkendDateStart,$wkendDateEnd);
    foreach($csvData as $rs) {
        $empArray[$rs['candidateId']]['Employee TFN'] = getCandidateTFN($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Family name'] = getCandidateLastNameByCandidateId($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Given name'] = getCandidateFirstNameByCandidateId($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Date of birth'] = trim(getCandidateDOBById($mysqli, $rs['candidateId']));
        $empArray[$rs['candidateId']]['Address 1'] = getCandidateStreetById($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Address 2'] = getCandidateStreetNameById($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Suburb'] = getCandidateSuburb($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['State'] = getCandidateState($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Postcode'] = getCandidatePostcode($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Payroll number'] = $rs['candidateId'];
        $empArray[$rs['candidateId']]['Hired date'] = getEmployeeWorkStartDate($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Country'] = '';
        $empArray[$rs['candidateId']]['email'] = getEmployeeEmail($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['phone'] = getCandidateMobileNoByCandidateId($mysqli, $rs['candidateId']);
        $yearToDate = getYearToDateData($mysqli, $rs['candidateId'], $yearStartDate, $wkendDateEnd);
        $superCalculatedAmount = calculateSuperToDateRange($mysqli,$rs['candidateId'],$yearStartDate,$wkendDateEnd);

        foreach ($yearToDate as $yearData) {
            $employeeTax = $yearData['totalTax'];
            $employeeGross = $yearData['totalGross'];
            $employeeSuper = $yearData['totalSuper'];
            $empArray[$rs['candidateId']]['EmployeeTaxYTD'] = number_format($employeeTax,2);
            $empArray[$rs['candidateId']]['EmployeeGrossPay'] = number_format($employeeGross,2);
            //$empArray[$rs['candidateId']]['SuperAmount'] = number_format($employeeSuper,2);
        }
        $empArray[$rs['candidateId']]['SuperAmount'] = number_format($superCalculatedAmount,2);
        if ($rs['itemType'] == 10) {
            $empArray[$rs['candidateId']]['EmployeeDeduction'] = number_format($rs['deduction'],2);
        }else if($rs['itemType']==14){
            $empArray[$rs['candidateId']]['TravelAllowance'] = number_format($rs['amount'],2);
        }else if($rs['itemType']==12) {
        }else if($rs['itemType']==11) {
        }else if($rs['itemType']==9) {
        }else if($rs['itemType']==13) {
        }
    }
}
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Entity ABN');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Period W1 value');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Period W2 value');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Pay period start date');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Pay period end date');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Employee TFN');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Employee ABN');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Payroll number');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Family name');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Given name');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Middle name');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Date of birth');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Hired date');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Termination date');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Address 1');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Address 2');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Suburb');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'State/territory');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Postcode');
$objPHPExcel->getActiveSheet()->setCellValue('T1', 'Country');
$objPHPExcel->getActiveSheet()->setCellValue('U1', 'email');
$objPHPExcel->getActiveSheet()->setCellValue('V1', 'phone');
$objPHPExcel->getActiveSheet()->setCellValue('W1', 'Final EOY pay indicator');
$objPHPExcel->getActiveSheet()->setCellValue('X1', 'Employee gross pay');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Employee tax');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Super guarantee amount');

$objPHPExcel->getActiveSheet()->setTitle('SINGLE TOUCH REPORT - FIRST');

$rowCount = 1;
$totalGross = getPayrunTotalGrossForDateRange($mysqli,$wkendDateStart,$wkendDateEnd);
$totalPaygTax = getPayrunTotalPaygTaxByDateRange($mysqli,$wkendDateStart,$wkendDateEnd);
$payDate = $wkendDateEnd;
$payPeriodStartDate = $wkendDateStart;
$payPeriodEndDate = $wkendDateEnd;
$companyABN = getCompanyABN($mysqli,$companyId);

foreach ($empArray as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $companyABN);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $totalGross);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $totalPaygTax);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, date('d/m/Y',strtotime($payPeriodStartDate)));
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, date('d/m/Y',strtotime($payPeriodEndDate)));
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, trim($data['Employee TFN']));
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $data['Payroll number']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $data['Family name']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowCount, $data['Given name']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowCount, $data['Date of birth']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowCount, $data['Hired date']);
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowCount, $data['Address 1']);
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowCount, $data['Address 2']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowCount, $data['Suburb']);
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$rowCount, $data['State']);
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$rowCount, $data['Postcode']);
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$rowCount, $data['Country']);
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$rowCount, $data['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$rowCount, $data['phone']);
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$rowCount, $data['EmployeeGrossPay']);
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowCount, $data['EmployeeTaxYTD']);
    //$objPHPExcel->getActiveSheet()->setCellValue('Z'.$rowCount, $data['SuperAmount']);
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$rowCount, $data['SuperAmount']);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
$filePath = './singletouch/singletouchRegular'.time().'.csv';
$objWriter->save($filePath);
echo $filePath;

//echo var_dump($empArray);