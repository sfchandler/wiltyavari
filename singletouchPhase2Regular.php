<?php

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
        'phone'=>'',
        'tax_treatment_code'=>'',
        'totalOvertime'=>'',
        'totalAllowance'=>'');
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
        $empArray[$rs['candidateId']]['tax_treatment_code'] = getTaxTreatmentCode($mysqli,getEmployeeTaxCode($mysqli,$rs['candidateId']));
        $empArray[$rs['candidateId']]['Payroll number'] = $rs['candidateId'];
        $empArray[$rs['candidateId']]['Hired date'] = getEmployeeWorkStartDate($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['Country'] = 'au';
        $empArray[$rs['candidateId']]['email'] = getEmployeeEmail($mysqli, $rs['candidateId']);
        $empArray[$rs['candidateId']]['phone'] = getCandidateMobileNoByCandidateId($mysqli, $rs['candidateId']);
        $yearToDate = getYearToDateDataSingletouchPhase2($mysqli, $rs['candidateId'], $yearStartDate, $wkendDateEnd);
        $superCalculatedAmount = calculateSuperToDateRange($mysqli,$rs['candidateId'],$yearStartDate,$wkendDateEnd);

        foreach ($yearToDate as $yearData) {
            $employeeTax = $yearData['totalTax'];
            $employeeGross = $yearData['totalGross'];
            $employeeSuper = $yearData['totalSuper'];
            $employeeOvertime = $yearData['totalOvertime'];
            $employeeAllowance = $yearData['totalAllowance'];
            $employeeCSDeduction = $yearData['totalCSDeduction'];
            $employeeSalSacrifice = $yearData['totalSalarySacrifice'];
            $empArray[$rs['candidateId']]['EmployeeTaxYTD'] = number_format($employeeTax,2);
            $empArray[$rs['candidateId']]['EmployeeGrossPay'] = number_format($employeeGross,2);
            $empArray[$rs['candidateId']]['totalOvertime'] = number_format($employeeOvertime,2);
            $empArray[$rs['candidateId']]['totalAllowance'] = number_format($employeeAllowance,2);
            $empArray[$rs['candidateId']]['totalCSDeduction'] = number_format($employeeCSDeduction,2);
            $empArray[$rs['candidateId']]['totalSalarySacrifice'] = number_format($employeeSalSacrifice,2);
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
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Period CS deduction Total');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Period CS garnishee Total');
$objPHPExcel->getActiveSheet()->setCellValue('F1','Employee TFN');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Employee ABN');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Payroll number');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Family name');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Given name');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Middle name');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'Date of birth');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'Hired date');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'Termination date');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'Basis of employment code');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'Termination type');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Address 1');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'Address 2');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'Suburb');
$objPHPExcel->getActiveSheet()->setCellValue('T1', 'State/territory');
$objPHPExcel->getActiveSheet()->setCellValue('U1', 'Postcode');
$objPHPExcel->getActiveSheet()->setCellValue('V1', 'Country');
$objPHPExcel->getActiveSheet()->setCellValue('W1', 'Phone');
$objPHPExcel->getActiveSheet()->setCellValue('X1', 'Email');
$objPHPExcel->getActiveSheet()->setCellValue('Y1', 'Tax treatment code');
$objPHPExcel->getActiveSheet()->setCellValue('Z1', 'Pay period start date');
$objPHPExcel->getActiveSheet()->setCellValue('AA1', 'Pay period end date');
$objPHPExcel->getActiveSheet()->setCellValue('AB1', 'Final EOY pay indicator');
$objPHPExcel->getActiveSheet()->setCellValue('AC1', 'Income stream code');
$objPHPExcel->getActiveSheet()->setCellValue('AD1', 'Income stream country code');
$objPHPExcel->getActiveSheet()->setCellValue('AE1', 'Employee gross pay');
$objPHPExcel->getActiveSheet()->setCellValue('AF1', 'Employee tax');
$objPHPExcel->getActiveSheet()->setCellValue('AG1', 'Overtime');
$objPHPExcel->getActiveSheet()->setCellValue('AH1', 'Transport allowance');
$objPHPExcel->getActiveSheet()->setCellValue('AI1', 'Tasks allowance');
$objPHPExcel->getActiveSheet()->setCellValue('AJ1', 'Salsac Super');
$objPHPExcel->getActiveSheet()->setCellValue('AK1', 'CS Deduction');
$objPHPExcel->getActiveSheet()->setCellValue('AL1', 'Super guarantee amount');

$objPHPExcel->getActiveSheet()->setTitle('SINGLE TOUCH PHASE 2 REPORT');

$rowCount = 1;
$totalGross = getPayrunTotalGrossForDateRange($mysqli,$wkendDateStart,$wkendDateEnd);
$totalPaygTax = getPayrunTotalPaygTaxByDateRange($mysqli,$wkendDateStart,$wkendDateEnd);
$totalChildSupportDeduction = getPayrunTotalChildSupportDateRange($mysqli,$wkendDateStart,$wkendDateEnd,16);

$payDate = $wkendDateEnd;
$payPeriodStartDate = $wkendDateStart;
$payPeriodEndDate = $wkendDateEnd;
$companyABN = getCompanyABN($mysqli,$companyId);

foreach ($empArray as $data) {
    $rowCount++;
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $companyABN);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $totalGross);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $totalPaygTax);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $totalChildSupportDeduction);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $totalChildSupportDeduction);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, trim($data['Employee TFN']));
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $data['Payroll number']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $data['Family name']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowCount, $data['Given name']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowCount, $data['Date of birth']);
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowCount, $data['Hired date']);
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowCount, 'C');
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowCount, $data['Address 1']);
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$rowCount, $data['Address 2']);
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$rowCount, $data['Suburb']);
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$rowCount, $data['State']);
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$rowCount, $data['Postcode']);
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$rowCount, $data['Country']);
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$rowCount, $data['phone']);
    $objPHPExcel->getActiveSheet()->setCellValue('X'.$rowCount, $data['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('Y'.$rowCount, $data['tax_treatment_code']);
    $objPHPExcel->getActiveSheet()->setCellValue('Z'.$rowCount, date('d/m/Y',strtotime($payPeriodStartDate)));
    $objPHPExcel->getActiveSheet()->setCellValue('AA'.$rowCount, date('d/m/Y',strtotime($payPeriodEndDate)));
    $objPHPExcel->getActiveSheet()->setCellValue('AB'.$rowCount,'FALSE');
    $objPHPExcel->getActiveSheet()->setCellValue('AC'.$rowCount, 'SAW');
    $objPHPExcel->getActiveSheet()->setCellValue('AD'.$rowCount, 0);
    $objPHPExcel->getActiveSheet()->setCellValue('AE'.$rowCount, $data['EmployeeGrossPay']);
    $objPHPExcel->getActiveSheet()->setCellValue('AF'.$rowCount, $data['EmployeeTaxYTD']);
    $objPHPExcel->getActiveSheet()->setCellValue('AG'.$rowCount, $data['totalOvertime']);
    $objPHPExcel->getActiveSheet()->setCellValue('AH'.$rowCount, $data['totalAllowance']);
    $objPHPExcel->getActiveSheet()->setCellValue('AI'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('AJ'.$rowCount, $data['totalSalarySacrifice']);
    $objPHPExcel->getActiveSheet()->setCellValue('AK'.$rowCount, $data['totalCSDeduction']);
    $objPHPExcel->getActiveSheet()->setCellValue('AL'.$rowCount, $data['SuperAmount']);
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
$filePath = './singletouch/singletouchPhase2Regular'.time().'.csv';
$objWriter->save($filePath);
echo $filePath;

//echo var_dump($empArray);