<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'] ?? null;
$positionId = $_REQUEST['positionId'] ?? null;
$jobCode = $_REQUEST['jobCode'] ?? null;
$action = $_REQUEST['action'] ?? null;
if($action == 'VIEW'){
    echo getRateCardView($mysqli,$clientId,$positionId,$jobCode);
}elseif($action == 'NEWFINANCIALYEAR'){
    echo generateNewFinancialYearRateCardTable($mysqli,$clientId,$positionId,$jobCode);
}else{
    echo generateRateCardTable($mysqli,$clientId,$positionId,$jobCode);
}

?>