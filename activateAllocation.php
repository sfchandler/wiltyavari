<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

if($_REQUEST['action'] == 'remove'){
	echo removeAllocation($mysqli,$_REQUEST['allid'],$_REQUEST['candidateId']);
}
if($_REQUEST['action'] == 'riteq'){
    echo updateRiteqId($mysqli,$_REQUEST['riteq_id'],$_REQUEST['candidateId']);
}elseif($_REQUEST['action'] == 'chronus'){
    echo updateChronusId($mysqli,$_REQUEST['chronus_id'],$_REQUEST['candidateId']);
}elseif ($_REQUEST['action'] == 'casualStatus'){
    echo updateCasualStatus($mysqli,$_REQUEST['candidateId'],$_REQUEST['casual_status'],$_SESSION['userSession']);
}else{
	echo updateAllocation($mysqli,$_REQUEST['status'], $_REQUEST['allid'],$_REQUEST['candidateId']);
}
?>