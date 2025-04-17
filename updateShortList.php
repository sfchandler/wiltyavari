<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
$consId = getConsultantId($mysqli,$_SESSION['userSession']);
$positions = $_REQUEST['positions'];
$concat = '';
if (!empty($positions)) {
    $len = count($positions);
    $i = 0;
    foreach ($positions as $rec) {
        if ($i == $len - 1) {
            $concat = $concat . $rec;
        } else {
            $concat = $concat . $rec . ',';
        }
        $i++;
    }
}
$auto_id = $_REQUEST['auto_id'];
$jb_id = $_REQUEST['jb_id'];
$state_id = $_REQUEST['state_id'];
$region = $_REQUEST['region'];
$gender = $_REQUEST['gender'];
$applied_date = $_REQUEST['applied_date'];
$ref_code = $_REQUEST['ref_code'];
$msg_id = $_REQUEST['msg_id'];
$account_name = $_REQUEST['account_name'];
$inbox_type = $_REQUEST['inbox_type'];
echo updateResumeShortList($mysqli, $auto_id,$jb_id,$msg_id, $account_name, $state_id, $region,$gender, $applied_date, $ref_code, $consId, $concat,$inbox_type);
