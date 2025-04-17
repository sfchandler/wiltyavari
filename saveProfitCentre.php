<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 9/10/2017
 * Time: 4:51 PM
 */

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$id = $_POST['centreId'];
$centreName = $_POST['centreName'];
$clientId = $_POST['clientId'];
$address1 = $_POST['address1'];
$address2 = $_POST['address2'];
$address3 = $_POST['address3'];
$stateId = $_POST['stateId'];
$phone = $_POST['phone'];
$manager = $_POST['manager'];
$taxCalc = $_POST['taxCalc'];
$taxPercentage = $_POST['taxPercentage'];
$remittanceAddress = $_POST['remittanceAddress'];

if($_REQUEST['get']==1){
    echo getProfitCentre($mysqli);
}
if(isset($centreName) && !empty($phone)){
    echo saveProfitCentre($mysqli,$id,$centreName,$clientId,$address1,$address2,$address3,$stateId,$phone,$manager,$taxCalc,$taxPercentage,$remittanceAddress);
}
?>