<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$shiftDate = $_POST['shiftDate'];
$shiftStart = $_POST['shiftStart'];
$shiftEnd = $_POST['shiftEnd'];
$startDate = $_POST['startDate'];
$shiftBreak = $_POST['shiftBreak'];

echo calculateHoursWorked($shiftDate,$shiftStart,$shiftEnd,$shiftBreak);
?>