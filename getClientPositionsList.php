<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$clientId = $_REQUEST['clientid'];
if($_REQUEST['dropSelect'] == 'N'){
    echo getClientBasedPositionList($mysqli,$clientId);
}elseif($_REQUEST['action'] == 'scheduling'){
    echo getClientPositionsForScheduling($mysqli,$_REQUEST['clientId']);
}else{
    echo getClientPositionsList($mysqli, $clientId);
}