<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if($_REQUEST['action'] == 'SENDSURVEY') {
    if (!empty($_REQUEST['client_id']) && !empty($_REQUEST['client_email']) && !empty($_REQUEST['client_name']) && !empty($_REQUEST['client_position']) && !empty($_REQUEST['survey_mail_body'])) {
        $mailBody = nl2br(trim($_REQUEST['survey_mail_body']));
        $logId = addClientSurveyLog($mysqli,$_REQUEST['client_id'],$_REQUEST['client_name'],$_REQUEST['client_position'],$_REQUEST['client_email']);
        echo generateNotification(
            trim($_REQUEST['client_email']),
            '',
            '',
            'Help Us Improve – Take a Quick Survey on Our Service',
            DEFAULT_EMAIL,
            DOMAIN_NAME,
            $mailBody.'<br>'
            .'<a href="'.DOMAIN_URL.'/client_survey.php?client_id='.base64_encode($_REQUEST['client_id']).'&log_id='.base64_encode($logId).'&client_name='.base64_encode($_REQUEST['client_name']).'&client_position='.base64_encode($_REQUEST['client_position']).'&client_email='.base64_encode($_REQUEST['client_email']).'">Click here to submit survey</a>'.
            '<br><br>If you have any specific comments or concerns, please feel free to include them in your response. We’re eager to hear what worked well for you and where we can make improvements.
             <br><br>
             Thank you for your time and for choosing Chandler Personnel. We look forward to continuing to serve you.
            <br><br>    ,
            <br>Director<br><br>
            '.DOMAIN_NAME.' 
            <br><br>
            T:  
            <br> 
            M:  
            <br> 
            E:  
            <br>
            A:  
            <br>
            W: '.DOMAIN_NAME.'
            ',
            '',
            '');
    }
}