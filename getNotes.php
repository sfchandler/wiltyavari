<?php 
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
$canId = $_REQUEST['canId'];
$null = '1970-01-01 00:00:00.000000';
if(!empty($canId)){
    $table = '';
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
							WHERE candidateId = ? AND actionDate != ? 
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("ss",$canId,$null)or die($mysqli->error);
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
	$table = $table.'<table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
											  <thead>	
												<tr>		                
													<th data-class="expand">CONSULTANT</th>
													<th data-hide="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>DATE</th>
													<th data-class="phone,tablet"><i class="fa fa-fw fa-times-circle txt-color-blue hidden-md hidden-sm hidden-xs"></i> TIME</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> ACTIVITY</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> TODO NOTE</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> SUBJECT</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> PERSON</th>
													
												</tr>
											  </thead>
												<tbody>';
													  while($notesList->fetch()){
										$table = $table.'<tr>
														<td>'.getConsultantName($mysqli,$consultantId).'</td>
														<td>'.$createdDate.'</td>
														<td>'.$todoTime.'</td>
														<td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
														<td>'.$todoNote.'</td>
														<td>'.$subject.'</td>
														<td>'.$firstName.' '.$lastName.'</td>
													</tr>';
									   } 
									   $table = $table.'</tbody>
											</table>';
		echo $table;									
	}else if($_REQUEST['status'] == 'UnActioned'){
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
							WHERE candidateId = ? AND actionDate = ?
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("ss",$canId,$null)or die($mysqli->error);
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
	$table = $table.'<table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
											  <thead>	
												<tr>		                
													<th data-class="expand">CONSULTANT</th>
													<th data-hide="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>DATE</th>
													<th data-class="phone,tablet"><i class="fa fa-fw fa-times-circle txt-color-blue hidden-md hidden-sm hidden-xs"></i> TIME</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> ACTIVITY</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> TODO NOTE</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> SUBJECT</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> PERSON</th>
													
												</tr>
											  </thead>
												<tbody>';
													  while($notesList->fetch()){
										$table = $table.'<tr>
														<td>'.getConsultantName($mysqli,$consultantId).'</td>
														<td>'.$createdDate.'</td>
														<td>'.$todoTime.'</td>
														<td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
														<td>'.$todoNote.'</td>
														<td>'.$subject.'</td>
														<td>'.$firstName.' '.$lastName.'</td>
													</tr>';
									   } 
									   $table = $table.'</tbody>
											</table>';
			echo $table;								
	}else if($_REQUEST['status'] == 'All'){
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
							WHERE candidateId = ?
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("s",$canId)or die($mysqli->error);
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

	$table = $table.'<table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
											  <thead>	
												<tr>		                
													<th data-class="expand">CONSULTANT</th>
													<th data-hide="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>DATE</th>
													<th data-class="phone,tablet"><i class="fa fa-fw fa-times-circle txt-color-blue hidden-md hidden-sm hidden-xs"></i> TIME</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> ACTIVITY</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> TODO NOTE</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> SUBJECT</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> PERSON</th>
													
												</tr>
											  </thead>
												<tbody>';
													  while($notesList->fetch()){
										$table = $table.'<tr>
														<td>'.getConsultantName($mysqli,$consultantId).'</td>
														<td>'.$createdDate.'</td>
														<td>'.$todoTime.'</td>
														<td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
														<td>'.$todoNote.'</td>
														<td>'.$subject.'</td>
														<td>'.$firstName.' '.$lastName.'</td>
													</tr>';
									   } 
									   $table = $table.'</tbody></table>';
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
							WHERE candidateId = ?
							ORDER BY createdDate DESC")or die($mysqli->error);
		  $notesList->bind_param("s",$canId)or die($mysqli->error);
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
	$table = $table.'<table id="datatable_tabletools" class="table table-striped table-bordered table-hover" width="100%">
											  <thead>	
												<tr>		                
													<th data-class="expand">CONSULTANT</th>
													<th data-hide="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>DATE</th>
													<th data-class="phone,tablet"><i class="fa fa-fw fa-times-circle txt-color-blue hidden-md hidden-sm hidden-xs"></i> TIME</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> ACTIVITY</th>
													<th data-hide="phone"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> TODO NOTE</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i> SUBJECT</th>
													<th data-hide="phone,tablet"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> PERSON</th>
													
												</tr>
											  </thead>
												<tbody>';
													  while($notesList->fetch()){
										$table = $table.'<tr>
														<td>'.getConsultantName($mysqli,$consultantId).'</td>
														<td>'.$createdDate.'</td>
														<td>'.$todoTime.'</td>
														<td><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.$firstName.'&lname='.$lastName.'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td>
														<td>'.$todoNote.'</td>
														<td>'.$subject.'</td>
														<td>'.$firstName.' '.$lastName.'</td>
													</tr>';
									   } 
									   $table = $table.'</tbody></table>';
							echo $table;	
			
	}
}

/*<a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fN='.$firstName.'&lN='.$lastName.'&cId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a>*/
?>