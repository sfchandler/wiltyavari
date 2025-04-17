<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/10/2017
 * Time: 5:27 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$addressId = $_REQUEST['addressId'];
echo getShiftLocationsDropDown($mysqli,$clientId,$addressId);
?>