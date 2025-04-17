<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('max_execution_time', 100000000000000);
set_time_limit(0);
$actVevoId = 92;
$stmt = $mysqli->prepare("SELECT 
                                  todoDate,
                                  diaryNoteId,
                                  noteType,
                                  firstName,
                                  lastName,
                                  candidateId,
                                  activityId,
                                  activityType,
                                  consultantId,
                                  todoTime,
                                  todoNote,
                                  actionDate,
                                  actionTime,
                                  createdDate,
                                  loginAccount,
                                  createdBy
                                FROM
                                  diarynote
                                WHERE
                                  diarynote.activityId = ?
                                AND  
                                  diarynote.todoDate IS NOT NULL
                                  AND DATE(todoDate) = CURDATE() + INTERVAL 7 DAY") or die($mysqli->error);
$stmt->bind_param("i",$actVevoId)or die($mysqli->error);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($todoDate,$diaryNoteId,$noteType, $firstName,$lastName,$candidateId,$activityId,$activityType,$consultantId,$todoTime,$todoNote,$actionDate,$actionTime,$createdDate,$loginAccount,$createdBy)or die($mysqli->error);
$count = 1;
while($stmt->fetch()){
    if(!notifierLogCheck($mysqli,$diaryNoteId)){
        $consultantName = getConsultantName($mysqli, $consultantId);
        $consultantEmail = getConsultantEmail($mysqli, $consultantId);
        $actType = getActivityTypeByActivityId($mysqli, $activityId);
        try {
            $mailBody = '<span style="font-family:Arial, Verdana, Geneva, sans-serif; font-size:12pt;">Visa Entitlement for '.$firstName.' '.$lastName.'('.$candidateId.') is due to expire by on or before, '.$todoDate.' A (<a href="'.DOMAIN_URL.'/diaryNotes.php?dNoteId='.$diaryNoteId.'">Note</a>) has been added by '.$consultantName.' on category '.$actType.'</p></span>';
            $mailSubject = DOMAIN_NAME.' - VEVO CHECK Notification';
            $from = DEFAULT_EMAIL;
            $fromName = DOMAIN_NAME;
            $bcc = ADMIN_EMAIL;
            $cc = '';
            generateNotification($consultantEmail,$cc,$bcc,$mailSubject,$from,$fromName,$mailBody,'','');
        }catch (Exception $e){
            echo $e->getMessage();
        }
        updateNotificationLog($mysqli,$diaryNoteId,1,'');
    }
}

