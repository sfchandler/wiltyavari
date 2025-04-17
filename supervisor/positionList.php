<?php
session_start();

require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
$clientId = $_SESSION['supervisorClient'];
$deptId = $_REQUEST['deptId'];
echo getPositionsBySupervisorClient($mysqli,$clientId,$deptId);