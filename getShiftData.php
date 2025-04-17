<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

$rCanId = $_REQUEST['rCanId']; 
$clid = $_REQUEST['clid'];
$stid = $_REQUEST['stid'];
$did = $_REQUEST['did'];
$strdate = $_REQUEST['strdate'];
$enddate = $_REQUEST['enddate'];
$consultant = $_REQUEST['consultant'];
$consultantId = getConsultantId($mysqli,$consultant);
$action = $_REQUEST['action'];
if(pinCheck($clid)){ // Add Client ID's needed PIN NO display
    $pinNo = "(PIN:".getPINNoById($mysqli,$rCanId).")";
}else{
    $pinNo = " ";
}
switch ($action){
    case 'OHS':
        $candidateName = getCandidateFullName($mysqli,$rCanId);
        $smsText = "";
        $smsText = $smsText."Hello ".$candidateName.", <br><br>\r\n";
        $smsText = $smsText."Please click below link to submit your OH&S Questionnaire <br><br>\r\n";
        $smsText = $smsText."* Please note this information is kept confidential and any OH&S issues or reports will be looked into anonymously *  <br><br>\r\n";
        $smsText = $smsText.DOMAIN_URL."/signOHS.php?id=".base64_encode($rCanId)."&cons_id=".base64_encode($consultantId)."&stateId=".base64_encode($_REQUEST['stateId'])."&deptId=".base64_encode($_REQUEST['deptId'])."&clientId=".base64_encode($_REQUEST['clientId'])."&positionId=".base64_encode($_REQUEST['positionId'])." <br><br>\r\n";
        $smsText = $smsText."Regards ".getConsultantName($mysqli,$consultantId)." @ Chandler <br><br>\r\n";
        $smsText = $smsText."PLEASE DO NOT REPLY TO THIS TEXT";
        echo $smsText;
        break;
    case 'SURVEY':
        $candidateName = getCandidateFullName($mysqli,$rCanId);
        $smsText = "";
        $smsText = $smsText."Hello ".$candidateName.", <br><br>\r\n";
        $smsText = $smsText."Thank you for choosing to work with Chandler Personnel. Your feedback matters to us.<br><br>\r\n";
        $smsText = $smsText."We place great value on your time, so this survey should take less than 5 minutes to complete <br><br>\r\n";
        $smsText = $smsText."Start survey, click here ".DOMAIN_URL."/customerSurvey.php?id=".base64_encode($rCanId)."&cons_id=".base64_encode($consultantId)."&stateId=".base64_encode($_REQUEST['stateId'])."&deptId=".base64_encode($_REQUEST['deptId'])."&clientId=".base64_encode($_REQUEST['clientId'])."&positionId=".base64_encode($_REQUEST['positionId'])." <br><br>\r\n";
        $smsText = $smsText."Regards ".getConsultantName($mysqli,$consultantId)." @ Chandler <br><br>\r\n";
        $smsText = $smsText."PLEASE DO NOT REPLY TO THIS TEXT";
        echo $smsText;
        break;
    case 'APPVERSION':
        $candidateName = getCandidateFullName($mysqli,$rCanId);
        $smsText = "";
        $smsText = $smsText."Hello ".$candidateName.", <br><br>\r\n";
        $smsText = $smsText."Please click below link to submit your Mobile Device/App Information <br><br>\r\n";
        $smsText = $smsText.DOMAIN_URL."/appVersion.php?id=".base64_encode($rCanId)."&cons_id=".base64_encode($consultantId)." <br><br>\r\n";
        $smsText = $smsText."Regards ".getConsultantName($mysqli,$consultantId)." @ Chandler <br><br>\r\n";
        $smsText = $smsText."PLEASE DO NOT REPLY TO THIS TEXT";
        echo $smsText;
        break;
    default:
        echo getShiftData($mysqli, $rCanId, $consultant, $clid, $pinNo, $stid, $did, $strdate, $enddate, $action);
        break;
}

?>