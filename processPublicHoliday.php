<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 20/04/2018
 * Time: 11:12 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$publicHoliday = $_REQUEST['publicHoliday'];
$action = $_REQUEST['action'];
$publicHolidayId = $_REQUEST['publicHolidayId'];
$stateId = $_REQUEST['stateId'];
if($action == 'add'){
    echo savePublicHoliday($mysqli,$publicHoliday,$stateId);
}elseif ($action == 'delete'){
    echo removePublicHoliday($mysqli,$publicHolidayId);
}else{
    echo loadPublicHolidays($mysqli);
}