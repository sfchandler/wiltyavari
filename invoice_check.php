<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$weekendingDate = $_REQUEST['weekendingDate'];
$payrollName = $_REQUEST['payrollName'];
$action = $_REQUEST['action'];
if($action == 'LENGTH') {
    echo getAllInvoiceClientsLength($mysqli, $weekendingDate, $payrollName);
}elseif($action == 'CLIENT'){
    echo getInvoiceClientsList($mysqli,$weekendingDate, $payrollName);
}