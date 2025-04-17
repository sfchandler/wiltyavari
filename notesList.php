<?php 
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
$consId = getConsultantId($mysqli,$_SESSION['userSession']);
$null = '1970-01-01 00:00:00.000000';

if(!empty($consId)){
	if($_REQUEST['status'] == 'Actioned'){
			$notesList = $mysqli->prepare("SELECT 
							  diaryNoteId,
							  firstName,
							  lastName,
							  candidateId,
							  axiomno,
							  activityId,
							  priorityId,
							  consultantId,
							  subject,
							  todoDate,
							  todoTime,
							  todoDuration,
							  todoNote,
							  actionDate,
							  actionTime,
							  actionDuration,
							  actionNote,
							  createdDate,
							  modifiedDate,
							  createdBy,
							  lastmodBy
							FROM
							  diarynote 
							WHERE consultantId = ? AND actionDate != ? 
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("ss",$consId,$null)or die($mysqli->error);
		  $notesList->execute();
		  $notesList->bind_result($diaryNoteId,
							  $firstName,
							  $lastName,
							  $candidateId,
							  $axiomno,
							  $activityId,
							  $priorityId,
							  $consultantId,
							  $subject,
							  $todoDate,
							  $todoTime,
							  $todoDuration,
							  $todoNote,
							  $actionDate,
							  $actionTime,
							  $actionDuration,
							  $actionNote,
							  $createdDate,
							  $modifiedDate,
							  $createdBy,
							  $lastmodBy) or die($mysqli->error);
		  $notesList->store_result();
		  $numRows = $notesList->num_rows;
	      
		  while($notesList->fetch()){
		  $table = $table.'<tr>
						  <td>'.getPriorityLevel($mysqli,$priorityId).'</td>
						  <td>'.$todoDate.'</td>
						  <td>'.$todoTime.'</td>
						  <td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'?>&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
						  <td>'.$subject.'</td>
						  <td>'.$lastName.' '.$firstName.' '.$candidateId.'</td>
					  </tr>';
		   }
	  echo $table;									
	}else if($_REQUEST['status'] == 'ToDo'){
		  $notesList = $mysqli->prepare("SELECT 
							  diaryNoteId,
							  firstName,
							  lastName,
							  candidateId,
							  axiomno,
							  activityId,
							  priorityId,
							  consultantId,
							  subject,
							  todoDate,
							  todoTime,
							  todoDuration,
							  todoNote,
							  actionDate,
							  actionTime,
							  actionDuration,
							  actionNote,
							  createdDate,
							  modifiedDate,
							  createdBy,
							  lastmodBy
							FROM
							  diarynote 
							WHERE consultantId = ? 
							AND actionDate = ?
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("ss",$consId,$null)or die($mysqli->error);
		  $notesList->execute();
		  $notesList->bind_result($diaryNoteId,
							  $firstName,
							  $lastName,
							  $candidateId,
							  $axiomno,
							  $activityId,
							  $priorityId,
							  $consultantId,
							  $subject,
							  $todoDate,
							  $todoTime,
							  $todoDuration,
							  $todoNote,
							  $actionDate,
							  $actionTime,
							  $actionDuration,
							  $actionNote,
							  $createdDate,
							  $modifiedDate,
							  $createdBy,
							  $lastmodBy) or die($mysqli->error);
		  $notesList->store_result();
		  $numRows = $notesList->num_rows;
	 
		  while($notesList->fetch()){
		  $table = $table.'<tr>
						  <td>'.getPriorityLevel($mysqli,$priorityId).'</td>
						  <td>'.$todoDate.'</td>
						  <td>'.$todoTime.'</td>
						  <td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'?>&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
						  <td>'.$subject.'</td>
						  <td>'.$lastName.' '.$firstName.' '.$candidateId.'</td>
					  </tr>';
		   }
	echo $table;							
	}else if($_REQUEST['status'] == 'OverDue'){
		$notesList = $mysqli->prepare("SELECT 
							  diaryNoteId,
							  firstName,
							  lastName,
							  candidateId,
							  axiomno,
							  activityId,
							  priorityId,
							  consultantId,
							  subject,
							  todoDate,
							  todoTime,
							  todoDuration,
							  todoNote,
							  actionDate,
							  actionTime,
							  actionDuration,
							  actionNote,
							  createdDate,
							  modifiedDate,
							  createdBy,
							  lastmodBy
							FROM
							  diarynote 
							WHERE consultantId = ?
							AND todoDate <= NOW() 
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("s",$consId)or die($mysqli->error);
		  $notesList->execute();
		  $notesList->bind_result($diaryNoteId,
							  $firstName,
							  $lastName,
							  $candidateId,
							  $axiomno,
							  $activityId,
							  $priorityId,
							  $consultantId,
							  $subject,
							  $todoDate,
							  $todoTime,
							  $todoDuration,
							  $todoNote,
							  $actionDate,
							  $actionTime,
							  $actionDuration,
							  $actionNote,
							  $createdDate,
							  $modifiedDate,
							  $createdBy,
							  $lastmodBy) or die($mysqli->error);
		  $notesList->store_result();
		  $numRows = $notesList->num_rows;
	      
		  while($notesList->fetch()){
		  $table = $table.'<tr>
						  <td>'.getPriorityLevel($mysqli,$priorityId).'</td>
						  <td>'.$todoDate.'</td>
						  <td>'.$todoTime.'</td>
						  <td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'?>&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
						  <td>'.$subject.'</td>
						  <td>'.$lastName.' '.$firstName.' '.$candidateId.'</td>
					  </tr>';
		   }
	echo $table;
	}else{
		$notesList = $mysqli->prepare("SELECT 
							  diaryNoteId,
							  firstName,
							  lastName,
							  candidateId,
							  axiomno,
							  activityId,
							  priorityId,
							  consultantId,
							  subject,
							  todoDate,
							  todoTime,
							  todoDuration,
							  todoNote,
							  actionDate,
							  actionTime,
							  actionDuration,
							  actionNote,
							  createdDate,
							  modifiedDate,
							  createdBy,
							  lastmodBy
							FROM
							  diarynote 
							WHERE consultantId = ?
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("s",$consId)or die($mysqli->error);
		  $notesList->execute();
		  $notesList->bind_result($diaryNoteId,
							  $firstName,
							  $lastName,
							  $candidateId,
							  $axiomno,
							  $activityId,
							  $priorityId,
							  $consultantId,
							  $subject,
							  $todoDate,
							  $todoTime,
							  $todoDuration,
							  $todoNote,
							  $actionDate,
							  $actionTime,
							  $actionDuration,
							  $actionNote,
							  $createdDate,
							  $modifiedDate,
							  $createdBy,
							  $lastmodBy) or die($mysqli->error);
		  $notesList->store_result();
		  $numRows = $notesList->num_rows;

		  while($notesList->fetch()){
		  $table = $table.'<tr>
						  <td>'.getPriorityLevel($mysqli,$priorityId).'</td>
						  <td>'.$todoDate.'</td>
						  <td>'.$todoTime.'</td>
						  <td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'?>&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
						  <td>'.$subject.'</td>
						  <td>'.$lastName.' '.$firstName.' '.$candidateId.'</td>
					  </tr>';
		   }
	echo $table;	
			
	}
}
?>