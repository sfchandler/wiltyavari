<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
include("includes/php-imap-master/src/PhpImap/__autoload.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 100000000);
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
define('ATTACHMENT_DIRECTORY', __DIR__.DIRECTORY_SEPARATOR.'attachments');
$serverName = "outlook.office365.com";
$username = 'resume@ .com.au';
$passWord = " ";
try{
    $mailbox = new PhpImap\Mailbox('{'.$serverName.':993/imap/ssl}INBOX', $username, $passWord, ATTACHMENT_DIRECTORY);
}catch (Exception $e){
    $msg = $e->getMessage();
}
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
    $msg = 'Mailbox is empty';
}else{
    $msg = 'Reading Mailbox';
}
rsort($mailsIds);
$latestMails = array_slice($mailsIds, 0, 100);
foreach ($latestMails as $mails) {
    $mail = $mailbox->getMail($mails);
    $str = explode('ref:',$mail->subject);
    if(!empty($str[1])) {
        $reference = str_replace('/','',trim(str_replace(' ', '', $str[1])));
        if(!empty($reference)){
            addInboxReference($mysqli,$reference);
        }
        $status = getRefMailStatus($mysqli,$reference);
        if(empty($status)){
            $status = 1;
        }elseif ($status == '0'){
            $status = 0;
        }elseif ($status == '1'){
            $status = 1;
        }
    }else{
        $reference = 'UNKNOWN';
        $status = 0;
    }
    if(empty($mail->textHtml)){
        $msgBody = $mail->textPlain;
    }else{
        $msgBody = $mail->textHtml;
    }
    $mailUpdate = updateResumeMails($mysqli, htmlentities($mail->messageId), $mail->id, $mail->id, $mail->fromName . ' ' . $mail->fromAddress, $mail->toString, $mail->subject, $msgBody, date('Y-m-d H:i:s', strtotime($mail->date)),$reference,$status);
    try {
        $att = $mail->getAttachments();
    }catch (Exception $e1){
        $msg = $e1->getMessage();
    }
    echo $msg.'<br>';
    echo htmlentities($mail->messageId).' '.$mail->id.' '.$msgBody.'<br>';
    /*echo $msg;
    echo $reference.''.$mail->subject.''.$mail->fromName.'<br>';
    echo $mailUpdate.'<br>';*/
    foreach ($att as $files) {
        updateAttachmentPath($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath), $files->name);
        updateResumeContents($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath));
    }
}


