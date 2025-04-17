<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$tableEmail = getTableEmail($mysqli,'jobboard');
$mailList = $mysqli->prepare("SELECT 
	  messageid,
	  mailfrom,
	  mailto,
	  subject,
	  date
	FROM
	  {$tableEmail}
	WHERE
	  date >= NOW() - INTERVAL 1 MONTH	  
	ORDER BY date DESC 
	")or die($mysqli->error);
	$mailList->execute();
	$mailList->bind_result($messageid, $mailfrom, $mailto, $subject, $date) or die($mysqli->error);
	$mailList->store_result();
	$numRows = $mailList->num_rows;
	$mailArray = array();
	while($mailList->fetch()){
		/*$row = array('messageid' =>$messageid, 'uid' =>$uid, 'msgno' =>$msgno, 'mailfrom' =>$mailfrom, 'mailto' =>$mailto,'subject' =>substr($subject,0,60).'...', 'msgbody' =>html_entity_decode($msgbody), 'date'=>date('d/m/Y g:i A',strtotime($date)), 'numRows'=>$numRows);
		$mailArray[] = $row;*/
		$row = $row.'<tr><td class="messageid" data-messageid="'.$messageid.'"><div>'.$mailfrom.'</div><div>'.substr($subject,0,150).'...'.'</div><div>'.$mailto.'</div></td><td align="right">'.date('d/m/Y g:i A',strtotime($date)).'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';		
	}
	echo $row.'<input type="hidden" id="rCount" value="'.$numRows.'"/>';
//echo json_encode($mailArray);
?>