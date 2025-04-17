<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jobCode = htmlspecialchars($_REQUEST['jobCode'],ENT_QUOTES, 'UTF-8');
echo removeJobCode($mysqli,$jobCode);

?>