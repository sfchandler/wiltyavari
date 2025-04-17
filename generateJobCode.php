<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'] ?? null;
$positionId = $_REQUEST['positionId'] ?? null;
$deptId = $_REQUEST['deptId'] ?? null;
$awardId = $_REQUEST['awardId'] ?? null;

echo createJobCode($mysqli,$clientId,$positionId,$deptId,$awardId);
?>