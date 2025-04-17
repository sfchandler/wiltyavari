<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$tableAttahment = getTableAttachment($mysqli,$_SESSION['accountName']);
$tableEmail = getTableEmail($mysqli,$_SESSION['accountName']);
if(isset($_REQUEST['subjectSearchTxt'])){
	//$searchTxt = "%{$_REQUEST['searchTxt']}%";
	$searchTxt = $_REQUEST['subjectSearchTxt'];
	$searchTerms = explode(',', $searchTxt);
	$searchTermsBits = array();
	foreach($searchTerms as $term){
		$term = trim($term);
		$excapedString = $mysqli->real_escape_string($term);
		if(!empty($excapedString)){
			//$searchTermsBits[] = "{$tableAttahment}.contents RLIKE '%{$excapedString}%'";
			//$searchTermsBits[] = "{$tableAttahment}.contents LIKE '%{$excapedString}%' OR {$tableEmail}.subject LIKE '%{$excapedString}%'";
			$searchTermsBits[] = "{$tableEmail}.subject LIKE '%{$excapedString}%'";
		}
	}
	$searchString = implode(' AND ', $searchTermsBits);
	
	if(empty($searchString)){
		$searchClause = "SELECT 
							{$tableEmail}.messageid,
							{$tableEmail}.uid,
							{$tableEmail}.msgno,
							{$tableEmail}.mailfrom,
							{$tableEmail}.mailto,
							{$tableEmail}.subject,
							{$tableEmail}.msgbody,
							{$tableEmail}.date,
							{$tableAttahment}.filepath,
							{$tableAttahment}.contents
						  FROM
							{$tableEmail}
							INNER JOIN {$tableAttahment} ON ({$tableEmail}.messageid = {$tableAttahment}.messageid)
						  ORDER BY
							{$tableEmail}.date DESC	  
							";
	}else{
		$searchClause = "SELECT 
							{$tableEmail}.messageid,
							{$tableEmail}.uid,
							{$tableEmail}.msgno,
							{$tableEmail}.mailfrom,
							{$tableEmail}.mailto,
							{$tableEmail}.subject,
							{$tableEmail}.msgbody,
							{$tableEmail}.date,
							{$tableAttahment}.filepath,
							{$tableAttahment}.contents
						  FROM
							{$tableEmail}
							INNER JOIN {$tableAttahment} ON ({$tableEmail}.messageid = {$tableAttahment}.messageid)
						  WHERE
							".$searchString."
						  ORDER BY
							{$tableEmail}.date DESC	  
							";
	}
	//attachment.contents LIKE ?
	$matchingList = $mysqli->prepare($searchClause)or die($mysqli->error);
	//$matchingList->bind_param("s", $searchTermsBits) or die($mysqli->error);
	$matchingList->execute();
	$matchingList->bind_result($messageid, $uid, $msgno, $mailfrom, $mailto, $subject, $msgbody, $date,$filepath,$contents) or die($mysqli->error);
	$matchingList->store_result();
	//$matchingList->fetch();// don't use it
	$numRows = $matchingList->num_rows;
	$mailArray = array();
	$row;
	while($matchingList->fetch()){
		$row = $row.'<tr><td class="messageid" data-messageid="'.$messageid.'"><div>'.$mailfrom.'</div><div>'.substr($subject,0,60).'...</div><div>'.$mailto.'</div></td><td align="right">'.$date.'</td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td><button id="deleteBtn" class="deleteBtn btn btn-xs btn-danger" type="button"><i class="fa fa-trash"></i> Delete</button></td></tr>';
		//$row = array('messageid' =>$messageid, 'uid' =>$uid, 'msgno' =>$msgno, 'mailfrom' =>$mailfrom, 'mailto' =>$mailto,'subject' =>substr($subject,0,15).'...', 'msgbody' =>html_entity_decode($msgbody), 'date'=>$date, 'filepath'=>$filepath, 'contents'=>$contents,'numRows'=>$numRows);
		//$mailArray[] = $row;		
	}
	echo $row.'<input type="hidden" id="rowCount" value="'.$numRows.'"/>';
}else{
	echo "none";
}
?>

