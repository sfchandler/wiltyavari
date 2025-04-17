<?php
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once ('includes/fpdf182/fpdf.php');
require_once ('includes/FPDI-2.3.2/src/autoload.php');
require_once ('includes/FPDI-2.3.2/src/FpdfTpl.php');
date_default_timezone_set('Australia/Melbourne');
use setasign\Fpdi\Fpdi;
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/

$empId = $_REQUEST['emp_id'];
$stateId = $_REQUEST['state_id'];
$deptId = $_REQUEST['dept_id'];
$clientId = $_REQUEST['client_id'];
$positionId = $_REQUEST['position_id'];
$firstName = getCandidateFirstNameByCandidateId($mysqli,$empId);
$lastName = getCandidateLastNameByCandidateId($mysqli,$empId);
$fullName = getCandidateFullName($mysqli,$empId);
$mobileNo = getCandidateMobileNoByCandidateId($mysqli,$empId);
$empEmail = getEmployeeEmail($mysqli,$empId);
$conEmail = getConsultantEmail($mysqli,getConsultantId($mysqli, $_SESSION['userSession']));
$dob = getCandidateDOBById($mysqli,$empId);
$address = getCandidateAddressById($mysqli,$empId);
$position = getCandidatePositionNameById($mysqli,$positionId);
$industrySector = getIndustrySectorByClient($mysqli,$clientId);

$startDate = $_REQUEST['start_date'];
$endDate = $_REQUEST['end_date'];
$sql = $mysqli->prepare("SELECT shiftDay,shiftStart,shiftEnd,addressId FROM shift WHERE shiftDate BETWEEN ? AND ? AND candidateId = ? AND clientId = ? AND stateId = ? AND departmentId = ? AND positionId = ?") or die($mysqli->error);
$sql->bind_param("sssiiii",$startDate,$endDate,$empId,$clientId,$stateId,$deptId,$positionId) or die($mysqli->error);
$sql->execute();
$sql->bind_result($shiftDay,$shiftStart,$shiftEnd,$addressId) or die($mysqli->error);
$sql->store_result();
$monday = '';
$tuesday = '';
$wednesday = '';
$thursday = '';
$friday = '';
$saturday = '';
$sunday = '';
$address_id = '';
while($sql->fetch()){
    if($shiftDay == 'Mon'){
        $monday = $shiftStart.'-'.$shiftEnd;
    }elseif ($shiftDay == 'Tue'){
        $tuesday = $shiftStart.'-'.$shiftEnd;
    }elseif ($shiftDay == 'Wed'){
        $wednesday = $shiftStart.'-'.$shiftEnd;
    }elseif ($shiftDay == 'Thu'){
        $thursday = $shiftStart.'-'.$shiftEnd;
    }elseif ($shiftDay == 'Fri'){
        $friday = $shiftStart.'-'.$shiftEnd;
    }elseif ($shiftDay == 'Sat'){
        $saturday = $shiftStart.'-'.$shiftEnd;
    }elseif ($shiftDay == 'Sun'){
        $sunday = $shiftStart.'-'.$shiftEnd;
    }
    $address_id = $addressId;
}
$sql->free_result();
$workLocation = getClientShiftAddress($mysqli,$address_id);
/*$period = new DatePeriod(
    new DateTime($startDate),
    new DateInterval('P1D'),
    new DateTime($endDate)
);
foreach ($period as $key => $value) {
    $value->format('Y-m-d');
}*/
/*$monday = '06:00-15:00';
$tuesday = '07:00-16:00';
$wednesday = '08:00-17:00';
$thursday = '09:00-19:00';
$friday = '09:00-14:00';
$saturday = '10:15-19:00';
$sunday = '05:00-11:00';*/

$pdf = new Fpdi();
$pdf->AddPage();
$new_pdf = "permit/Permitted_Worker_Scheme.pdf";

$pdf->setSourceFile($new_pdf);
$page1 = $pdf->importPage(1);
$pdf->useTemplate($page1);

$pdf->SetFont("arial", "", 8);
$fontSize = '8';
$fontColor = '0,0,0';
$pdf->SetTextColor($fontColor);
$fullDate = date('d/m/Y');
$month = date('m');
$day = date('d');
$pdf->Text(22,193,$fullDate);

$pdf->Text(80,106,$fullName);
$pdf->Text(80,112,$dob);
$pdf->Text(80,118,$address);
$pdf->Text(80,124,$position);
$pdf->Text(22,143,$workLocation);


$pdf->AddPage();
$page2 = $pdf->importPage(2);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($page2);
$pdf->SetFont("arial", "", 8);
$fontSize = '8';
$fontColor = '0,0,0';
$pdf->SetTextColor($fontColor);
$pdf->Text(34,140,$startDate);

$pdf->Text(56,140,$monday);
$pdf->Text(75,140,$tuesday);
$pdf->Text(95,140,$wednesday);
$pdf->Text(114,140,$thursday);
$pdf->Text(134,140,$friday);
$pdf->Text(153,140,$saturday);
$pdf->Text(173,140,$sunday);

$pdf->Text(22,200,$industrySector);

$newFileName = 'work_permit_'.time().'.pdf';
$filePath = __DIR__.'/permit/'.$newFileName;
try {
    $pdf->Output(__DIR__.'/permit/'.$newFileName, 'F');
}catch (Exception $e1){
    $e1->getMessage();
}
try {
    $canId = $empId;
    if(!file_exists('documents/'.$canId)){
        mkdir('documents/'.$canId, 0777);
        chown('./documents/' . $canId,'chandler');
    }
    if(!empty($canId)) {
        copy($filePath, './documents/' . $canId . '/' . $newFileName);
        updateCandidateDocs($mysqli, $canId, 51, $newFileName, './documents/' . $canId . '/'.$newFileName, '', '', '', '');
    }
}catch(Exception $e2){
     $e2->getMessage();
}
try {
    $mailStatus = generateWorkPermitEmail($firstName,$lastName,$conEmail,$empEmail,__DIR__.'/permit/'.$newFileName);
    if($mailStatus == 'SUCCESS'){
        echo 'Work Permit sent Successfully';
    }else{
        echo 'Error sending work permit';
    }
}catch (Exception $e3){
    $e3->getMessage();
}
?>

