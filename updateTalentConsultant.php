<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");

$autoId = $_POST['autoId'];
$consultant = $_POST['consultant'];
$action = $_POST['action'];
if($action == 'UPDATE'){
    updateTalentConsultant($mysqli,$autoId,$consultant);
}
