<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 10/10/2017
 * Time: 4:56 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jobCode = $_REQUEST['jobCode'];
$clientId = $_REQUEST['clientId'];
$clientCode = $_REQUEST['clientCode'];
$clientName = $_REQUEST['clientName'];
$contactFirstName = $_REQUEST['contactFirstName'];
$contactLastName = $_REQUEST['contactLastName'];
$description = $_REQUEST['description'];
$startDate = $_REQUEST['startDate'];
$payrollName = $_REQUEST['payrollName'];
$invoiceTo = $_REQUEST['invoiceTo'];
$workAddress = $_REQUEST['workAddress'];
$updateStatus = $_REQUEST['updateStatus'];

if(!empty($jobCode)&&!empty($clientId)&&!empty($clientCode)&&isset($clientName)&&isset($payrollName)) {
    if ($updateStatus == 'update') {
        echo updateJobDetail($mysqli, $jobCode, $clientId, $clientCode, $clientName, $contactFirstName, $contactLastName, $description, $startDate, $payrollName, $invoiceTo, $workAddress);
    } else {
        echo saveJobDetail($mysqli, $jobCode, $clientId, $clientCode, $clientName, $contactFirstName, $contactLastName, $description, $startDate, $payrollName, $invoiceTo, $workAddress);
    }
}
?>