<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 6/06/2019
 * Time: 4:36 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionId'];
$jobCode = $_REQUEST['jobCode'];
$snapYear = $_REQUEST['snapYear'];
$action = $_REQUEST['action'];
if($action == 'SAVE') {
    echo generateRateCardSnapshot($mysqli, $clientId, $positionId, $jobCode, $snapYear);
}elseif ($action == 'VIEW'){
    echo getSavedRateCardSnapshot($mysqli,$clientId,$positionId,$jobCode,$snapYear);
}
?>