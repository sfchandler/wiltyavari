<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$messageid = htmlentities($_REQUEST['messageid']);
$tableEmail = getTableEmail($mysqli,'jobboard');
$mailList = $mysqli->prepare("SELECT messageid,uid,msgno,mailfrom,mailto,subject,msgbody,date FROM {$tableEmail} WHERE messageid = ?")or die($mysqli->error);
$mailList->bind_param("s",$messageid);
$mailList->execute();
$mailList->bind_result($messageid, $uid, $msgno, $mailfrom, $mailto, $subject, $msgbody, $date) or die($mysqli->error);
$mailArray = array();
while($mailList->fetch()){
    $row = array('messageid' =>$messageid, 'uid' =>$uid, 'msgno' =>$msgno, 'mailfrom' =>$mailfrom, 'mailto' =>$mailto,'subject' =>$subject, 'msgbody' =>$msgbody, 'date'=>$date);
    $mailArray[] = $row;
}
$mailList->free_result();
echo json_encode($mailArray);
?>