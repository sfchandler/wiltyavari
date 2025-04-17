<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(isset($_REQUEST['clientId']) && isset($_REQUEST['department']) && isset($_REQUEST['stateId'])){
	try{
        $clientId = $_REQUEST['clientId'];
        $department = $_REQUEST['department'];
        $stateId = $_REQUEST['stateId'];
        $phone = $_REQUEST['phone'];
        $note = $_REQUEST['note'];
        //$job_description = $_REQUEST['job_description'];
        if(validateExistingDepartment($mysqli,$clientId,$stateId,$department)){
            echo '<tr><td colspan="4">Area of Work/department/Role Already Exists</td></tr>';
        }else{
            echo addDepartment($mysqli,$clientId,$stateId,$department,$phone,$note);
        }
    }catch (Exception $e){
        echo $e->getMessage();
    }
}
?>