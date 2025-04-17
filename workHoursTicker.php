<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
echo getAllTimeSheetTotals($mysqli,'2022-05-23','2022-06-05');
echo workHoursTicker($mysqli);

