<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 16/04/2018
 * Time: 2:59 PM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getSMSAccountSupportInfo($mysqli, $_REQUEST['smsAccount']);
?>