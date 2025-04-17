<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 4/09/2019
 * Time: 12:12 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$wkDate = $_POST['wkDate'];
$units = $_POST['units'];
$clientId = $_POST['clientId'];
$description = $_POST['description'];
$amount = $_POST['amount'];
$candidateId = $_POST['candidateId'];
$jobCode = $_POST['jobCode'];
$remove_id = $_POST['remove_id'];
$action = $_POST['action'];
if($action == 'get'){
    echo getInvoiceAdditionRows($mysqli);
}elseif($action == 'remove'){
    echo removeInvoiceAdditionRows($mysqli,$remove_id);
}elseif($action == 'path'){
    echo getInvoicePaths($mysqli);
}else{
    if(!empty($wkDate)&&!empty($units)&&!empty($clientId)&&!empty($description)&&!empty($amount)&&!empty($candidateId)&&!empty($jobCode)){
        echo saveInvoiceAddition($mysqli,$wkDate,$units,$clientId,$description,$amount,$candidateId,$jobCode);
    }
}
