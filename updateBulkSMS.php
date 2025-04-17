<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 26/09/2017
 * Time: 3:45 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$clientId = $_REQUEST['clientId'];
$stateId = $_REQUEST['stateId'];
$deptId = $_REQUEST['deptId'];
$num_th = $_REQUEST['num_th'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$candidateId = $_REQUEST['candidateId'];
if(!empty($clientId) && !empty($stateId) && !empty($deptId) && !empty($candidateId)){
    echo updateBulkShiftSMSDelivery($mysqli, $clientId,$stateId,$deptId,$startDate,$endDate,$candidateId);
}
?>