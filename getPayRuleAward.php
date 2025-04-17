<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
try {
    $clientid = $_REQUEST['clientid'];
    $positionid = $_REQUEST['positionid'];
    $deptId = $_REQUEST['deptId'];
    $jobCode = getJobCodeByClientPosition($mysqli, $clientid, $positionid, $deptId);
    echo getPayruleAwardByJobCode($mysqli,$jobCode);
}catch (Exception $e){
    $e->getMessage();
}
