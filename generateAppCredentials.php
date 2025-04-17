<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$canId = $_REQUEST['srchCanId'];
$consId = getConsultantId($mysqli,$_SESSION['userSession']);
$consultantEmail = getConsultantEmail($mysqli,$consId);
if(isset($canId)){
    echo generateAppLogin($mysqli,$canId,$consultantEmail);
}

?>