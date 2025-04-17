<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jobCode = $_REQUEST['jobCode'];

echo getJobInfoByJobCode($mysqli,$jobCode);


?>