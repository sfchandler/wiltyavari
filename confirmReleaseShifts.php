<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$rel_shift_id = $_POST['rel_shift_id'];
$candidate_id = $_POST['candidate_id'];
$action = $_POST['action'];
if(!empty($rel_shift_id) && !empty($action) && ($action == 'REMOVE')){
    echo removeReleasedShift($mysqli,$rel_shift_id);
}elseif(!empty($candidate_id) && !empty($rel_shift_id) && !empty($action)) {
    echo createReleasedShiftOnRoster($mysqli, $rel_shift_id, $candidate_id, $action, $_SESSION['userSession']);
}