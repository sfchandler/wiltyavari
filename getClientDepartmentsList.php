<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 12/07/2018
 * Time: 11:52 AM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['action'] == 'DEPT'){
    echo getClientDepartmentsManualTimesheet($mysqli, $_REQUEST['clientid'],$_REQUEST['positionId'],$_REQUEST['jobCode'],$_REQUEST['empId'],$_REQUEST['weekendingDate'],$_REQUEST['dateRange']);
}elseif ($_REQUEST['action'] == 'ASSIGNEDDEPT'){
    echo getTimesheetDepartments($mysqli,$_REQUEST['startDate'],$_REQUEST['endDate'],$_REQUEST['clientId'],$_REQUEST['positionId'],$_REQUEST['jobCode'],$_REQUEST['empId'],$_REQUEST['weekendingDate']);
}elseif($_REQUEST['action'] == 'DEPARTMENTSFORJOBCODE'){
    echo getClientDeptsForJobCode($mysqli,$_REQUEST['clientid']);
}else{
    echo getClientDepartmentsList($mysqli, $_REQUEST['clientid']);
}
?>