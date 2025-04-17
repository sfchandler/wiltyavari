<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientCode = $_REQUEST['clCode'];
$industryId = $_REQUEST['industryId'];
$industrySector = $_REQUEST['industrySector'];
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$clientid = $_REQUEST['clid'];
$client = $_REQUEST['cl'];
$clientaddress = $_REQUEST['claddress'];
$street_number_1 = $_REQUEST['street_number_1'];
$street_name = $_REQUEST['street_name'];
$suburb = $_REQUEST['suburb'];
$state = $_REQUEST['state'];
$postcode = $_REQUEST['postcode'];
$clientReference = $_REQUEST['clientReference'];
$clientNote = $_REQUEST['clientNote'];
$phone = $_REQUEST['phone'];
$abn = $_REQUEST['abn'];
$classification = $_REQUEST['classification'];
$terms = $_REQUEST['terms'];
$invoiceType = $_REQUEST['invoiceType'];
$paymentMethod = $_REQUEST['paymentMethod'];
$gstPayable = $_REQUEST['gstPayable'];
$termsOfBusinessSigned = $_REQUEST['termsOfBusinessSigned'];
$payrollTaxSigned = $_REQUEST['payrollTaxSigned'];
$payrolltax = $_REQUEST['payrolltax'];
$workcover = $_REQUEST['workcover'];
$super_percentage = $_REQUEST['super_percentage'];
$wic = $_REQUEST['wic'];
$mhws = $_REQUEST['mhws'];
echo updateClient($mysqli,$clientCode,$industryId,$industrySector,$clientid,$client,$clientaddress,$street_number_1,$street_name,$suburb,$state,$postcode,$clientReference,$clientNote,$phone,$abn,$classification,$terms,$invoiceType,$paymentMethod,$gstPayable,$termsOfBusinessSigned,$payrollTaxSigned,$payrolltax,$workcover,$super_percentage,$mhws,$wic);
?>