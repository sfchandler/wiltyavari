<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 13/07/2018
 * Time: 8:53 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('max_execution_time', 100000000000000);


$sql = $mysqli->prepare("SELECT shiftId FROM timesheet")or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($shiftId)or die($mysqli->error);
$numRows = $sql->num_rows;
echo 'ROWS'.$numRows;
while ($sql->fetch()){
    echo 'ShiftID>>'.$shiftId;
    updateTimesheetDept($mysqli,$shiftId);
    echo '<br>';

}
function updateTimesheetDept($mysqli,$shiftId){
    $deptId = getDepartmentIdByShiftId($mysqli,$shiftId);
    echo 'DEPTID'.$deptId;
    $up = $mysqli->prepare("UPDATE timesheet SET deptId = ? WHERE shiftId = ?")or die($mysqli->error);
    $up->bind_param("ii",$deptId,$shiftId)or die($mysqli->error);
    $up->execute();
}
