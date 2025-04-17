<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionId'];
$deptId = $_REQUEST['deptId'];
echo getJobCodeByClientPosition($mysqli,$clientId,$positionId,$deptId);
