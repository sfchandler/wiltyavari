<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$shiftid = $_REQUEST['shiftid'];
$currentUser = $_SESSION['userSession'];
echo deleteShift($mysqli,$shiftid,$_SESSION['userSession']);
?>