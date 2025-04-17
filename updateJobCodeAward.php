<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jobcode = $_REQUEST['jobcode'] ?? null;
$awId = $_REQUEST['awId'] ?? null;

echo updateJobCodeAward($mysqli,$awId,$jobcode);
?>