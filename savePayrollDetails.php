<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 22/09/2017
 * Time: 10:45 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*if($_REQUEST['get']==1){
    echo getPaySlipMessage($mysqli);
}*/
$payrollName = $_POST['payrollName'];
$profitCentre = $_POST['profitCentre'];
$yearStartDate = $_POST['yearStartDate'];
$yearEndDate = $_POST['yearEndDate'];
$frequency = $_POST['frequency'];
$periodEndDay = $_POST['periodEndDay'];
$paySlipMessage = $_POST['payslipmsg'];
$id = $_POST['id'];
if(isset($payrollName) && isset($profitCentre)&&isset($paySlipMessage) && !empty($paySlipMessage)){
    echo savePayrollNameDetails($mysqli,$payrollName,$profitCentre,$yearStartDate,$yearEndDate,$frequency,$periodEndDay,$paySlipMessage,$id);
}
?>