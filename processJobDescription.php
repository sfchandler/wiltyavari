<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$action = $_REQUEST['action'];
$client_id = $_REQUEST['client_id'];
$position_id = $_REQUEST['position_id'];
$job_description = $_REQUEST['job_description'];
if ($action == 'ADD') {
    echo addRecruitmentJobDescription($mysqli,$client_id,$position_id,$job_description);
}elseif ($action == 'DISPLAY'){
    echo getRecruitmentJobDescription($mysqli);
}elseif ($action == 'UPDATE'){
    echo updateRecruitmentJobDescription($mysqli,$client_id,$position_id,$job_description);
}