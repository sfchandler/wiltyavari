<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/08/2017
 * Time: 1:45 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$transCode = $_POST['transCode'];
$transCodeDesc = $_POST['transCodeDesc'];
$transCodeType = $_POST['transCodeType'];
$taxorder = $_POST['taxorder'];
$payslipOrder = $_POST['payslipOrder'];
$groupCertFormat = $_POST['groupCertFormat'];
$printOnPaySlip = $_POST['printOnPaySlip'];
$printOnReports = $_POST['printOnReports'];
$defaultPercent = $_POST['defaultPercent'];
$defaultAmount = $_POST['defaultAmount'];
$addUnitsAsHours = $_POST['addUnitsAsHours'];
$autoReduceCode = $_POST['autoReduceCode'];
$autoBillPercent = $_POST['autoBillPercent'];
$autoBillCode = $_POST['autoBillCode'];
$superfundABN = $_POST['superfundABN'];
$superfundSPINID = $_POST['superfundSPINID'];
$usi = $_POST['usi'];
$product_name = $_POST['product_name'];

if(isset($transCode)&&isset($transCodeDesc)&&isset($defaultPercent)&&isset($autoBillPercent)&&isset($superfundABN)&&isset($superfundSPINID)){
    echo updateTransactionCodeDetails($mysqli,$transCode, $transCodeDesc, $transCodeType, $taxorder, $payslipOrder, $groupCertFormat, $printOnPaySlip, $printOnReports, $defaultPercent, $defaultAmount, $addUnitsAsHours, $autoReduceCode, $autoBillPercent, $autoBillCode, $superfundABN, $superfundSPINID,$usi,$product_name);
}