<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$clientId = $_POST['clientId'];
$positionId = $_POST['positionId'];
$jobCode = $_POST['jobCode'];
$empId = $_POST['empId'];
$emTotal = $_POST['emTotal'];
$ordTotal = $_POST['ordTotal'];
$aftTotal = $_POST['aftTotal'];
$nightTotal = $_POST['nightTotal'];
$rdoTotal = $_POST['rdoTotal'];
$satTotal = $_POST['satTotal'];
$sunTotal = $_POST['sunTotal'];
$ovtTotal = $_POST['ovtTotal'];
$dblTotal = $_POST['dblTotal'];
$holTotal = $_POST['holTotal'];
$hol_total = $_POST['hol_total'];
$satOvertimeTotal = $_POST['satOvertimeTotal'];
$sunOvertimeTotal = $_POST['sunOvertimeTotal'];
$periodOvertimeTotal = $_POST['periodOvertimeTotal'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$weekendingDate = $_POST['weekendingDate'];
$action = $_REQUEST['action'];
$department = $_REQUEST['department'];
$deptId = $_REQUEST['deptId'];
$totId = $_REQUEST['totId'];
$workDate = $_REQUEST['workDate'];
if (!empty($clientId) && !empty($positionId) && !empty($jobCode) && !empty($empId) && !empty($startDate) && !empty($endDate) && !empty($weekendingDate)) {
    if ($action == 'verify') {
        if(validateTimeSheetTotals($mysqli,$clientId,$positionId,$deptId,$empId,$startDate,$endDate,$weekendingDate)){
            echo getTimesheetTotalByEmployee($mysqli,$clientId,$positionId,$jobCode,$empId,$startDate,$endDate,$weekendingDate);
        }else{
            echo '<tr>
                                    <td><input type="text" name="candidateId1" id="candidateId1" value="'.$empId.'" size="18" readonly/></td>
                                    <td><input type="text" name="emTotal1" class="emTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="ordTotal1" class="ordTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="aftTotal1" class="aftTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="nightTotal1" class="nightTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="rdoTotal1" class="rdoTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="satTotal1" class="satTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="sunTotal1" class="sunTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="ovtTotal1" class="ovtTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="dblTotal1" class="dblTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="holTotal1" class="holTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="hol_total1" class="hol_total1" value="" size="3"/></td>
                                    <td><input type="text" name="satOvertimeTotal1" class="satOvertimeTotal1" value="" size="3"/></td>
                                    <td><input type="text" name=sunOvertimeTotal1" class="sunOvertimeTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="periodOvertimeTotal1" class="periodOvertimeTotal1" value="" size="3"/></td>
                                    <td><button type="submit" name="addTotalEntryBtn" id="addTotalEntryBtn"  class="addTotalEntryBtn btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> Add</button></td>
                                </tr>';
        }
    }elseif($action == 'update'){
        try {
            echo updateTimesheetTotals($mysqli, $totId,$clientId, $positionId, $jobCode, $emTotal, $ordTotal, $aftTotal, $nightTotal,$rdoTotal, $satTotal, $sunTotal, $ovtTotal, $dblTotal, $holTotal,$hol_total, $satOvertimeTotal, $sunOvertimeTotal, $periodOvertimeTotal, $startDate, $endDate, $empId, $weekendingDate);
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }elseif($action == 'Department'){
        try {
            echo updateDepartmentWithTimesheetTotals($mysqli,$clientId,$positionId,$empId,$workDate,$weekendingDate,$department, $jobCode);
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }else {
       echo saveTimeSheetCalculation($mysqli, $clientId, $positionId,$deptId, $jobCode, $emTotal, $ordTotal, $aftTotal, $nightTotal,$rdoTotal, $satTotal, $sunTotal, $ovtTotal, $dblTotal, $holTotal,$hol_total, $satOvertimeTotal, $sunOvertimeTotal, $periodOvertimeTotal, $startDate, $endDate, $empId, $weekendingDate);
    }
}