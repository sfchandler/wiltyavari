<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$stDate = $_REQUEST['stDate'];
$enDate = $_REQUEST['enDate'];
$canId = $_REQUEST['canId'];

echo getShiftDataForCandidate($mysqli,$stDate,$enDate,$canId);
