<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$filePath = $_REQUEST['fpath'];
echo removeClientDocument($mysqli,$clientId,$filePath);
?>