<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
include("includes/php-imap-master/src/PhpImap/__autoload.php");
date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 100000000);
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
define('ATTACHMENT_DIRECTORY', __DIR__.DIRECTORY_SEPARATOR.'attachments');
$serverName = "outlook.office365.com";
$username = 'resume@ .com.au';
$passWord = "";
try{
    $mailbox = new PhpImap\Mailbox('{'.$serverName.':993/imap/ssl}INBOX', $username, $passWord, ATTACHMENT_DIRECTORY);
}catch (Exception $e){
    echo $e->getMessage();
}
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
    echo 'Mailbox is empty';
}else{
    echo 'Reading Mailbox';
}
rsort($mailsIds);
$latestMails = array_slice($mailsIds, 0, 8000);
foreach ($latestMails as $mails) {
    $mail = $mailbox->getMail($mails);
    $str = explode('ref:',$mail->subject);
    if(!empty($str[1])) {
        $reference = trim($str[1]);
    }else{
        $reference = NULL;
    }
    echo '>>>> '.$mail->textHtml.'<br>';
    $mailUpdate = updateResume($mysqli, htmlentities($mail->messageId), $mail->id, $mail->id, $mail->fromName . ' ' . $mail->fromAddress, $mail->toString, $mail->subject, htmlentities($mail->textHtml), date('Y-m-d H:i:s', strtotime($mail->date)),$reference);
    /*try {
        $att = $mail->getAttachments();
    }catch (Exception $e1){
        $error = $e1->getMessage();
    }
    foreach ($att as $files) {
        updateAttachmentPath($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath), $files->name);
        updateResumeContents($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath));
    }*/
}

function testEmail($status){
    require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = DEFAULT_EMAIL;
    $mail->Password = DEFAULT_EMAIL_PASSWORD;
    $mail->SMTPSecure = "tls";
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Host = "outlook.office365.com";
    $mail->LE = "\r\n";
    $mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME.' Notification');
    $subject = 'CRON TEST!!!!';
    $mail->AddAddress('swarnajithF@chandlerservices.com.au');

    $mail->Subject = $subject;
    //$mail->AddAttachment($filePath);
    $mail->IsHTML(true);
    $body = '<h2>TEST CRON JOBSSSS</h2>'.$status;
    $mail->Body = $body;
    $mail->send();
    if($mail){
        return "SUCCESS";
    }else{
        return "FAILURE";
    }
}

?>

