<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 30/10/2017
 * Time: 9:25 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$shiftLocId = $_REQUEST['id'];
echo getShiftLocation($mysqli,$shiftLocId);
?>