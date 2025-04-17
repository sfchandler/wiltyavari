<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$candidateId = $_REQUEST['canid'];
$filePath = $_REQUEST['fpath'];
try {
    echo removeDocument($mysqli, $candidateId, $filePath,$_SESSION['userType']);
}catch (Exception $e){
    echo $e->getMessage();
}
?>