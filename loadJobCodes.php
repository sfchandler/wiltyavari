<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$clientSearch = $mysqli->real_escape_string($_REQUEST['clientSearch']);
$action = $_REQUEST['action'];
if(!empty($clientSearch)){
    echo getJobCodeListByClient($mysqli,$clientSearch);
}elseif ($action == 'display'){
    echo displayJobCodeList($mysqli);
}else {
    echo getJobCodeList($mysqli, $_REQUEST['clId']);
}
?>