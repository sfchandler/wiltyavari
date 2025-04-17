<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getShiftCountByClient($mysqli,$_POST['clientId'],$_POST['startDate'],$_POST['endDate']);