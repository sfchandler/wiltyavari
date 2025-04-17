<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$subject = $_REQUEST['mmsSubject'];
$mms_text = $_REQUEST['mmsText'];
$smsText = $_REQUEST['smsText'];
$mms_file = $_REQUEST['mmsFile'];
$mobile = $_REQUEST['mobile'];
$numbers = array($mobile);
if($_REQUEST['action'] == 'SMS'){
    echo sendTSMS($smsText,$mobile);
}else {
    echo sendMMS($subject, $mms_text, $mms_file, $numbers);
}