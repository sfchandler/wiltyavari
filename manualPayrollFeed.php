<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 12/09/2019
 * Time: 3:19 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$action = $_POST['action'];
$weekEndingDate = $_POST['weekEndingDate'];
$payrollId = $_POST['payrollName'];
$candidateId = $_POST['candidateId'];
$jobCode = $_POST['jobCode'];
$clientId = $_POST['clientId'];
$positionId = $_POST['positionId'];
$wkending = $_POST['wkending'];
$category = $_POST['category'];
$transCode = $_POST['transCode'];
$units = $_POST['units'];
$rate = $_POST['rate'];
$amount = $_POST['amount'];
$chargeRate = $_POST['chargeRate'];
$chargeAmount = $_POST['chargeAmount'];
$gross = $_POST['gross'];
$net = $_POST['net'];
$tax = $_POST['tax'];
$deduction = $_POST['deduction'];
$superAnnuation = $_POST['superAnnuation'];
$payrunId = $_POST['payrunId'];

$exp = explode('-',$category);
$itemType = $exp[0];
$item_desc = $exp[1];

if($action == 'payrunId'){
    echo getNewPayrunId($mysqli);
}elseif ($action == 'category'){
    echo getCategoriesWithItemId($mysqli);
}elseif ($action == 'add') {
    echo saveManualPayroll($mysqli,$weekEndingDate,$payrollId,$candidateId,$jobCode,$clientId,$positionId,$wkending,$itemType,$item_desc,$transCode,$units,$rate,$amount,$chargeRate,$chargeAmount,$gross,$net,$tax,$deduction,$superAnnuation,$payrunId);
}

