<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if(!empty($_REQUEST['client_id']) && !empty($_REQUEST['state_id']) && !empty($_REQUEST['dept_id']) && !empty($_REQUEST['position_id'])){
   echo json_encode(getAssignedEmployeesList($mysqli,$_REQUEST['client_id'],$_REQUEST['state_id'],$_REQUEST['dept_id'],$_REQUEST['position_id']));
}