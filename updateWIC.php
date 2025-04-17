<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$wicCode = $_REQUEST['wicCode'] ?? null;
$year = $_REQUEST['year'] ?? null;
$classification = $_REQUEST['classification'] ?? null;
$rate = $_REQUEST['rate'] ?? null;
$action = $_REQUEST['action'] ?? null;
$wic_id = $_REQUEST['wic_id'] ?? null;
switch ($action){
    case 'ADD':
        echo saveWorkcoverIndustryClassification($mysqli,$year,$wicCode,$classification,$rate);
        break;
    case 'LOAD':
        echo displayWorkcoverIndustryClassifications($mysqli);
        break;
    case 'DELETE':
        echo deleteWorkcoverIndustryClassification($mysqli,$wic_id);
        break;
    default:
        break;
}