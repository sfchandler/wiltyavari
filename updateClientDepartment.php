<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$departmentId = $_REQUEST['departmentId'];
$department = $_REQUEST['department'];
$phone = $_REQUEST['phone'];
$note = $_REQUEST['note'];
$candidateId = $_REQUEST['candidateId'];
if(updateClientDepartment($mysqli,$departmentId, $department, $phone,$note)){
    echo listDepartments($mysqli,'');
}else{
    echo '<tr><td colspan="3"></td></tr>';
}

?>