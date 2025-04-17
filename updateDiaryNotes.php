<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    $loginAccount = $_SESSION['accountName'];
	$noteType = 'diary';
	$noteId = $_REQUEST['dNoteId'];
	$emailAddress = $_REQUEST['emailAddress'];
	$candidateMobile = $_REQUEST['mobileNo'];
	$consultantId = $_POST['consultantId'];
	$activityId = $_POST['activityId'];
	$priorityId = 3;//$_POST['priorityId'];
	$fName = $_POST['fName'];
	$lName = $_POST['lName'];
	$candidateId = $_POST['canId'];
	$subject = $_POST['subject'];
	if($_POST['todoDate'] == ''){
		$tDate = NULL;
	}else{
		$tDate = date("Y-m-d", strtotime($_POST['todoDate']));
	}
	$todoDate = $tDate;
	$todoTime = $_POST['todoTime'];
	$todoDhrs = $_POST['todoDhrs'];
	$todoDmns = $_POST['todoDmns'];
	if(empty($_POST['todoDhrs'])){
		$todoDhrs = '00';
	}
	if(empty($_POST['todoDmns'])){
		$todoDmns = '00';
	}
	$todoDuration = $todoDhrs.':'.$todoDmns;
	$todoNote = $_POST['todoNote'];
        if($_POST['actionDate'] == ''){
		$aDate = NULL;
	}else{
		$aDate = date("Y-m-d", strtotime($_POST['actionDate']));
	}
	$actionDate = $aDate;
	$actionTime = $_POST['actionTime'];
	$actionDhrs = $_POST['actionDhrs'];
	$actionDmns = $_POST['actionDmns'];
	if(empty($_POST['actionDhrs'])){
		$actionDhrs = '00';
	}
	if(empty($_POST['actionDmns'])){
		$actionDmns = '00';
	}
	$actionDuration = $actionDhrs.':'.$actionDmns;
	$actionNote = $_POST['actionNote'];
	$mobileNo = $_POST['mobileNo'];
	$messageid = $_POST['messageid'];
	if(isset($_POST['submit'])){
		if($_POST['submit'] == 'addNote'){
				$stmt = $mysqli->prepare("INSERT INTO
									  diarynote(
									  noteType,
									  firstName,
									  lastName,
									  candidateId,
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
									  lastmodBy,
									  loginAccount)
									VALUES(
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  ?,
									  NOW(),
									  NOW(),
									  ?,
									  ?,
									  ?)") or die ($mysqli->error);
			$stmt->bind_param("ssssiiisssssssssiis",$noteType,$fName,$lName,$candidateId,$activityId,$priorityId,$consultantId,$subject,$todoDate,$todoTime,$todoDuration,$todoNote,$actionDate,$actionTime,$actionDuration,$actionNote,$consultantId,$consultantId,$loginAccount)or die($mysqli->error);
			$stmt->execute();
			$nrows = $stmt->affected_rows;
			if($nrows == '1'){
				$msg = base64_encode("Diary Note Added");
				echo "<script>window.close();</script>";
			}else{
				$msg = base64_encode($mysqli->error);
				header("Location: diaryNotes.php?msg=$msg&canId=$candidateId&fname=$fName&lname=$lName&eml=$emailAddress&mbl=$mobileNo&messageid=$messageid");
			}	
		}else if($_POST['submit'] == 'updateNote'){
            $up = $mysqli->prepare("UPDATE diarynote SET 
									  firstName = ?,
									  lastName = ?,
									  candidateId = ?,
									  activityId = ?,
									  priorityId = ?,
									  consultantId = ?,
									  subject = ?,
									  todoDate = ?,
									  todoTime = ?,
									  todoDuration = ?,
									  todoNote = ?,
									  actionDate = ?,
									  actionTime = ?,
									  actionDuration = ?,
									  actionNote = ?,
									  modifiedDate = NOW(),
									  lastmodBy = ?,
									  loginAccount = ? WHERE diaryNoteId = ?") or die($mysqli->error);
			$up->bind_param("sssiiisssssssssssi",$fName,$lName,$candidateId,$activityId,$priorityId,$consultantId,$subject,$todoDate,$todoTime,$todoDuration,$todoNote,$actionDate,$actionTime,$actionDuration,$actionNote,$consultantId,$loginAccount,$noteId) or die($mysqli->error);
			$up->execute();
			$nrows = $up->affected_rows;
			if($nrows > 0){
				$msg = base64_encode("Diary Note Updated");
				echo "<script>window.close();</script>";
			}else{
				$msg = base64_encode($mysqli->error);
				header("Location: diaryNotes.php?error_msg=$msg&canId=$candidateId&fname=$fName&lname=$lName&eml=$emailAddress&mbl=$mobileNo&messageid=$messageid");
			}
		}
	}else{
		$msg = base64_encode("Error in Updating");
		header("Location: diaryNotes.php?error_msg=$msg&canId=$candidateId&fname=$fName&lname=$lName&eml=$emailAddress&mbl=$mobileNo&messageid=$messageid");
	}
	/*
	if(candidateCheck($mysqli,$firstName,$lastName,$emailAddress)==false){
		insertCandidateByDiaryNote($mysqli,$candidateId,$fName,$lName,$candidateMobile,$emailAddress,$consultantId);
	}
	*/
?>