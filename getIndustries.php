<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 26/07/2018
 * Time: 11:08 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
echo getIndustries($mysqli,$clientId);