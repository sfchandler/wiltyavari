<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$limitStart = $_POST['limitStart'];
$limitCount = 10; 
$consId = $_SESSION['userSession'];//$_POST['consId'];
$duration = $_POST['duration'];
$sortType = $_POST['sortType'];
$consultantId = $_POST['consultantId'];
$actSort = $_POST['actSort'];
if($consultantId <> ''){
	$consultantSelected = $consultantId;
}else{
	$consultantSelected = $consId;
}
if((isset($limitStart) || !empty($limitStart)) && !empty($consId) && ($duration == 'None') && empty($sortType)){
   if($actSort<>0){
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
								    AND activityId = ? 
								  AND todoDate IS NOT NULL
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
       $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
								  AND todoDate IS NOT NULL
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
       $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
   }
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
	  $row = $row.'<tr><td>'.getPriorityLevel($mysqli,$priorityId).'</td><td>'.$todoDate.'</td><td>'.$todoTime.'</td><td><div class="'.vevoIndicator($mysqli,$diaryNoteId).'">&nbsp;</div><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.getCandidateFirstNameByCandidateId($mysqli,$candidateId).'&lname='.getCandidateLastNameByCandidateId($mysqli,$candidateId).'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td><td>'.$subject.'</td><td><a href="candidateMain.php?canId='.base64_encode($candidateId).'&fname='.base64_decode(getCandidateFirstNameByCandidateId($mysqli,$candidateId)).'&lname='.base64_encode(getCandidateLastNameByCandidateId($mysqli,$candidateId)).'&eml='.base64_encode(getEmployeeEmail($mysqli,$candidateId)).'&mbl='.base64_encode(getCandidateMobileNoByCandidateId($mysqli,$candidateId)).'&address='.base64_encode(getCandidateAddressById($mysqli,$candidateId)).'&$conId='.$consultantId.'" target="_blank">'.getCandidateFullName($mysqli,$candidateId).'('.getNickNameById($mysqli,$candidateId).') '.$candidateId.'</a></td><td>'.getConsultantName($mysqli,$consultantId).'</td></tr>';
  }
  echo $row;
}else if((isset($limitStart ) || !empty($limitStart)) && !empty($consId) && ($duration == 'None') && ($sortType == 'Overdue')){
    if($actSort<>0){
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
								  AND activityId = ?
								  AND todoDate < NOW()
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount") or die($mysqli->error);
        $notesList->bind_param("ii", $consultantSelected,$actSort) or die($mysqli->error);
    }else {
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
								  AND todoDate < NOW()
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount") or die($mysqli->error);
        $notesList->bind_param("i", $consultantSelected) or die($mysqli->error);
    }
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
	  $row = $row.'<tr><td>'.getPriorityLevel($mysqli,$priorityId).'</td><td>'.$todoDate.'</td><td>'.$todoTime.'</td><td><div class="'.vevoIndicator($mysqli,$diaryNoteId).'">&nbsp;</div><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.getCandidateFirstNameByCandidateId($mysqli,$candidateId).'&lname='.getCandidateLastNameByCandidateId($mysqli,$candidateId).'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td><td>'.$subject.'</td><td><a href="candidateMain.php?canId='.base64_encode($candidateId).'&fname='.base64_encode(getCandidateFirstNameByCandidateId($mysqli,$candidateId)).'&lname='.base64_encode(getCandidateLastNameByCandidateId($mysqli,$candidateId)).'&eml='.base64_encode(getEmployeeEmail($mysqli,$candidateId)).'&mbl='.base64_encode(getCandidateMobileNoByCandidateId($mysqli,$candidateId)).'&address='.base64_encode(getCandidateAddressById($mysqli,$candidateId)).'&$conId='.base64_encode($consultantId).'" target="_blank">'.getCandidateFullName($mysqli,$candidateId).'('.getNickNameById($mysqli,$candidateId).') '.$candidateId.'</a></td><td>'.getConsultantName($mysqli,$consultantId).'</td></tr>';
  }
  echo $row;
}else if((isset($limitStart ) || !empty($limitStart)) && !empty($consId) && ($duration <> 'None') && ($sortType == 'ToDo')){
	if($duration == 'Week'){
        if($actSort<>0){
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
								  AND activityId = ?
								  AND todoDate BETWEEN NOW() AND NOW() + INTERVAL 7 DAY
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
								  AND todoDate BETWEEN NOW() AND NOW() + INTERVAL 7 DAY
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
        }
	}else if($duration == 'Fortnight'){
        if($actSort<>0){
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
								  AND activityId = ? 
								  AND todoDate BETWEEN NOW() AND NOW() + INTERVAL 14 DAY
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
								  AND todoDate BETWEEN NOW() AND NOW() + INTERVAL 14 DAY
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
        }
	}else if($duration == 'Month'){
        if($actSort<>0){
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
								  AND activityId = ?
								  AND todoDate BETWEEN NOW() AND NOW() + INTERVAL 1 MONTH
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
								  AND todoDate BETWEEN NOW() AND NOW() + INTERVAL 1 MONTH
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
        }
	}else if($duration == 'Today'){
        if($actSort<>0){
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
									  AND activityId = ? 
									  AND DATE(todoDate) = CURDATE()
									  AND actionDate IS NULL
									  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
									  AND DATE(todoDate) = CURDATE()
									  AND actionDate IS NULL
									  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
        }
	}else if($duration == 'Tomorrow'){
        if($actSort<>0){
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
								  AND activityId = ?
								  AND DATE(todoDate) = CURDATE() + INTERVAL 1 DAY
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
								  AND DATE(todoDate) = CURDATE() + INTERVAL 1 DAY
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
            $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
        }
	}
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
	  $row = $row.'<tr><td>'.getPriorityLevel($mysqli,$priorityId).'</td><td>'.$todoDate.'</td><td>'.$todoTime.'</td><td><div class="'.vevoIndicator($mysqli,$diaryNoteId).'">&nbsp;</div><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.getCandidateFirstNameByCandidateId($mysqli,$candidateId).'&lname='.getCandidateLastNameByCandidateId($mysqli,$candidateId).'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.$actionNote.'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td><td>'.$subject.'</td><td><a href="candidateMain.php?canId='.base64_encode($candidateId).'&fname='.base64_encode(getCandidateFirstNameByCandidateId($mysqli,$candidateId)).'&lname='.base64_encode(getCandidateLastNameByCandidateId($mysqli,$candidateId)).'&eml='.base64_encode(getEmployeeEmail($mysqli,$candidateId)).'&mbl='.base64_encode(getCandidateMobileNoByCandidateId($mysqli,$candidateId)).'&address='.base64_encode(getCandidateAddressById($mysqli,$candidateId)).'&$conId='.base64_encode($consultantId).'" target="_blank">'.getCandidateFullName($mysqli,$candidateId).'('.getNickNameById($mysqli,$candidateId).') '.$candidateId.'</a></td><td>'.getConsultantName($mysqli,$consultantId).'</td></tr>';
  }
  echo $row;	
}else if((isset($limitStart) || !empty($limitStart)) && !empty($consId) && ($duration == 'None') && ($sortType == 'ToDo')){
    if($actSort<>0){
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
								  AND activityId = ?
								  AND todoDate IS NOT NULL
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
        $notesList->bind_param("ii",$consultantSelected,$actSort)or die($mysqli->error);
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
								  AND todoDate IS NOT NULL
								  AND actionDate IS NULL
								  ORDER BY todoDate DESC LIMIT $limitStart, $limitCount")or die($mysqli->error);
        $notesList->bind_param("i",$consultantSelected)or die($mysqli->error);
    }
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
	  $row = $row.'<tr><td>'.getPriorityLevel($mysqli,$priorityId).'</td><td>'.$todoDate.'</td><td>'.$todoTime.'</td><td><div class="'.vevoIndicator($mysqli,$diaryNoteId).'">&nbsp;</div><a href="diaryNotes.php?dNoteId='.$diaryNoteId.'&fname='.getCandidateFirstNameByCandidateId($mysqli,$candidateId).'&lname='.getCandidateLastNameByCandidateId($mysqli,$candidateId).'&canId='.$candidateId.'&actId='.$activityId.'&pId='.$priorityId.'&consId='.$consultantId.'&subj='.$subject.'&toDate='.$todoDate.'&toTime='.$todoTime.'&toDur='.$todoDuration.'&tNote='.$todoNote.'&actDate='.$actionDate.'&actTime='.$actionTime.'&actDur='.$actionDuration.'&actNote='.htmlspecialchars($actionNote).'&crDate='.$createdDate.'&modDate='.$modifiedDate.'&crBy='.$createdBy.'&lBy='.$lastmodBy.'" style="cursor:pointer" target="_blank">'.getActivityLevel($mysqli,$activityId).'</a></td><td>'.$subject.'</td><td><a href="candidateMain.php?canId='.base64_encode($candidateId).'&fname='.base64_encode($firstName).'&lname='.base64_encode($lastName).'&eml='.base64_encode(getEmployeeEmail($mysqli,$candidateId)).'&mbl='.base64_encode(getCandidateMobileNoByCandidateId($mysqli,$candidateId)).'&address='.base64_encode(getCandidateAddressById($mysqli,$candidateId)).'&$conId='.base64_encode($consultantId).'" target="_blank">'.getCandidateFullName($mysqli,$candidateId).'('.getNickNameById($mysqli,$candidateId).') '.$candidateId.'</a></td><td>'.getConsultantName($mysqli,$consultantId).'</td></tr>';
  }
  echo $row;
}

?>