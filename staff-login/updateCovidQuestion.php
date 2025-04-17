<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
$covidAnswer = $_POST['covidAnswer'];
$canId = $_POST['username'];
$shift_id = $_POST['shift_id'];
$shift_date = $_POST['shift_date'];
$client_id = $_POST['client_id'];

echo updateCovidAnswer($mysqli,$canId,$covidAnswer,$shift_id,$shift_date,$client_id);