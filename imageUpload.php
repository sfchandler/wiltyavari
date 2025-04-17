<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$empId = $_POST['empId'];
$file = addslashes(base64_encode(file_get_contents($_FILES['employeeImage']['tmp_name'])));
$msg = employeeImageUpdate($mysqli,$file,$empId);
$message = base64_encode($msg);
$employeeId = base64_encode($empId);
header("Location:candidateMain.php?errorImage=$message&canId=$employeeId");
