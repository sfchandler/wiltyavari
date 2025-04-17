<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['getAccount'] == 'get'){
    echo getEmployeeBankAccountRows($mysqli,$_REQUEST['canId']);
}else if($_REQUEST['getAccount'] == 'update'){
    if(validateBankBSB($_REQUEST['bsb'])) {
        $response = updateBankAccount($mysqli, $_REQUEST['canId'], $_REQUEST['bankName'], $_REQUEST['accountName'], $_REQUEST['accountNumber'], $_REQUEST['bsb']);
        generateNotification(ACCOUNTS_EMAIL,'','','Candidate Bank details changed',DEFAULT_EMAIL,DOMAIN_NAME.' Financial Info Alert','<br><br> User '.$_SESSION['userSession']. ' has changed/updated Bank details of candidate '.getCandidateFullName($mysqli,$_REQUEST['canId']).'('.$_REQUEST['canId'].') at '.date('Y-m-d H:i:s'),'','');
        echo $response;
    }else{
        echo 'bsb is invalid';//: '.$_REQUEST['bsb'].'. Required format is 000-000.';
    }
}
else{
    echo 'Required';
}
?>