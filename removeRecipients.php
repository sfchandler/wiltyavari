<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$canId = $_REQUEST['cid'];
$sessId = $_REQUEST['sessId'];

$rmRec = $mysqli->prepare("DELETE FROM tmpsmslist WHERE candidateId = ? AND sessionid = ?")or die($mysqli->error);
$rmRec->bind_param("ss", $canId,$sessId) or die($mysqli->error);
$rmRec->execute();
$nr = $rmRec->affected_rows;  
if($nr > 0){
	echo getTmpSMSList($mysqli,$sessId);
}else{
	echo getTmpSMSList($mysqli,$sessId);
}
?>