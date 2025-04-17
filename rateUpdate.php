<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'] ?? null;
$positionId = $_REQUEST['positionId'] ?? null;
$jobCode = $_REQUEST['jobCode'] ?? null;
$action = $_REQUEST['action'] ?? null;
switch ($action){
    case 'AWARD':
        echo getAwardById($mysqli,getRateCardAwardInfo($mysqli,$clientId,$positionId));
        break;
    case 'CLIENTRATES':
        echo getClientRates($mysqli,$clientId);
        break;
}

