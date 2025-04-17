<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
if($_REQUEST['stateId'] <> '0' || $_REQUEST['deptId'] <> '0'){
    try {
        $allocationList = assignEmployeeToClient($mysqli, $_REQUEST['candidateId'], $_REQUEST['clientId'], $_REQUEST['stateId'], $_REQUEST['deptId'], $_REQUEST['priorityId']);
        $clientIds = array(5, 243, 154, 36, 278,28,287,34,246,232,144,157);
        if(in_array($_REQUEST['clientId'],$clientIds)) {
            $clientName = getClientNameByClientId($mysqli,$_REQUEST['clientId']);
            generateMailNotification('Police check required',ACCOUNTS_EMAIL,BLUE_COLLAR_OPS,$_REQUEST['candidateId'].'('.getCandidateFullName($mysqli,$_REQUEST['candidateId']).') must have Police check in-order to work on '.$clientName);
        }
        if ($_REQUEST['clientId'] == 294){
            $mail_body = getCandidateFullName($mysqli,$_REQUEST['candidateId']).' ('.$_REQUEST['candidateId'].') must have the below documents in-order to work at Mission Foods <br>  
                                    1.	Dosafe -food safety certificate <br>
                                    2.	Mission foods induction <br>
                                    3.	GMP Food defence <br>
                                    4.	Mission visitor induction <br>
                                    5.	Confidentiality agreement <br>';
            //generateMailNotification('Mission Food Compliance Check Alert',ACCOUNTS_EMAIL,BLUE_COLLAR_OPS,$mail_body);
            $missionFoodInd = getCandidateDocumentByDocTypeId($mysqli,$_REQUEST['candidateId'],76);
            $missionFoodTr = getCandidateDocumentByDocTypeId($mysqli,$_REQUEST['candidateId'],77);
            $missionFoodVisitorInd = getCandidateDocumentByDocTypeId($mysqli,$_REQUEST['candidateId'],78);
            $missionFoodConfAgr = getCandidateDocumentByDocTypeId($mysqli,$_REQUEST['candidateId'],79);
            $missionFoodSafety = getCandidateDocumentByDocTypeId($mysqli,$_REQUEST['candidateId'],80);
            $files = array($missionFoodInd,$missionFoodTr,$missionFoodVisitorInd,$missionFoodConfAgr,$missionFoodSafety);
            generateMailNotificationWithAttachments('Mission Food Compliance Check Alert',ACCOUNTS_EMAIL,BLUE_COLLAR_OPS,$mail_body,$files);
        }
        if ($_REQUEST['clientId'] == 322){
            $mail_body = getCandidateFullName($mysqli,$_REQUEST['candidateId']).' ('.$_REQUEST['candidateId'].') must have the below documents in-order to work at Melbourne Chef<br>  
                                    1.	Dosafe -food safety certificate <br>
                                    2.	Melbourne Chefs induction <br>';
            generateMailNotification('Melbourne Chef Compliance Check Alert',ACCOUNTS_EMAIL,BLUE_COLLAR_OPS,$mail_body);
        }
        echo $allocationList;
    }catch(Exception $e){
        echo $e->getMessage();
    }
}else{
	echo listAllocation($mysqli,$_REQUEST['candidateId']);
}
?>