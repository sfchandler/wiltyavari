<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 7/06/2019
 * Time: 9:56 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionId'];
$jobCode = $_REQUEST['jobCode'];
$status = $_REQUEST['status'];
$year = $_REQUEST['year'];
if($status == 'list'){
    echo displayRateCardYears($mysqli, $clientId, $positionId, $jobCode);
}else if($status == 'display'){
    echo displayRateCardSnapshot($mysqli, $clientId, $positionId, $jobCode, $year);
}
?>