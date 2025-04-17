<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$action = $_REQUEST['action'];
$clientId = $_REQUEST['clientId'];
if($action == 'CLIENTPOSITION'){
    echo getClientPositionsDropdown($mysqli,$clientId);
}elseif ($action == 'CLIENTBASED'){
    echo getClientBasedPositionList($mysqli,$clientId);
}else {
    echo getPositionsForDropdown($mysqli);
}
?>