<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
error_reporting(E_ERROR | E_PARSE);
$clientId = $_REQUEST['clientId'] ?? null;
$positionId = $_REQUEST['positionId'] ?? null;
$award = $_REQUEST['award'] ?? null;
$breakdown = 'CHARGE RATE';
$hourly_rate = $_REQUEST[''] ?? null;
$superannuation = $_REQUEST['super_percentage'] ?? null;
$payroll_tax = $_REQUEST['payrollTax'] ?? null;
$mhws = $_REQUEST['mhws'] ?? null;
$workcover = $_REQUEST['workcover'] ?? null;
$margin_select = $_REQUEST['margin_select'] ?? null;
$margin = $_REQUEST['margin'] ?? null;
$hourly_rate = $_REQUEST['hourly_rate'] ?? null;
$increment_percentage = $_REQUEST['increment_percentage'] ?? null;

$client = getClientNameByClientId($mysqli,$clientId);
$position = getPositionByPositionId($mysqli,$positionId);

//echo $client.'- '.$position.'- '.$award.'- '.$breakdown.'- '.$hourly_rate.'- '.$superannuation.'- '.$payroll_tax.'- '.$mhws.'- '.$workcover.'- '.$margin_select.'- '.$margin.'- '.$increment_percentage;
$calculateRate = new RateCalculator($client,$position,$award,$breakdown,$hourly_rate,$superannuation,$payroll_tax,$mhws,$workcover,$margin_select,$margin,$increment_percentage);
echo $calculateRate->calculateRate();



