<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if(isset($_REQUEST['client']) && isset($_REQUEST['clientAddress'])){
    $client = $_REQUEST['client'];
    $clientCode = $_REQUEST['clientCode'];
    $industryId = $_REQUEST['industryId'];
    $industrySector = $_REQUEST['industrySector'];
    $latitude = $_REQUEST['latitude'];
    $longitude = $_REQUEST['longitude'];
    $clientAddress = $_REQUEST['clientAddress'];
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
    if(validateExistingClient($mysqli,$client)){
        echo '<tr><td colspan="2">Client Already Exists</td></tr>';
    }else{
        $status = addClient($mysqli,$clientCode,$industryId,$industrySector,$client,$clientAddress,$street_number_1,$street_name,$suburb,$state,$postcode,$clientReference,$clientNote,$phone,$abn,$classification,$terms,$invoiceType,$paymentMethod,$gstPayable,$termsOfBusinessSigned,$payrollTaxSigned,$payrolltax,$workcover,$super_percentage,$mhws,$wic,$_SESSION['userSession']);
        $clientId = getClientIdByClientCode($mysqli,$clientCode);
        //addClientShiftLocation($mysqli, $clientId,'',$clientAddress,'','','','','',$latitude,$longitude);
        echo $status;
    }
}
?>