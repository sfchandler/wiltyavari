<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");


$clientArray = explode(',',$_REQUEST['clientArray']);
$weekendingDate = $_REQUEST['weekendingDate'];
foreach ($clientArray as $key => $value) {
    if(!validateClientSummarySave($mysqli,$weekendingDate,$value)) {
        $response = updateClientSummary($mysqli, $value, $weekendingDate);
    }else{
        $response = 'Records are existing';
    }
}
echo $response;