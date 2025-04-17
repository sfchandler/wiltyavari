<?php
namespace PhpImap;
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/php-imap-5.0.1/src/PhpImap/__autoload.php';
//include("includes/php-imap-master/src/PhpImap/__autoload.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 100000000);
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
define('ATTACHMENT_DIRECTORY', __DIR__.DIRECTORY_SEPARATOR.'jbattachments');
$serverName = OUTLOOK_SERVER_NAME;
$username = JOBBOARD_EMAIL_ADDRESS;
$passWord = JOBBOARD_EMAIL_PASSWORD;
try{
    $mailbox = new Mailbox('{'.$serverName.':993/imap/ssl}INBOX', $username, $passWord, ATTACHMENT_DIRECTORY);
}catch (Exception $e){
    echo $msg = $e->getMessage();
}
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
   echo $msg = 'Mailbox is empty';
}else{
   echo  $msg = 'Reading Mailbox';
}
rsort($mailsIds);
$latestMails = array_slice($mailsIds, 0, 200);
foreach ($latestMails as $mails) {
    $mail = $mailbox->getMail($mails);
    $str = explode(DOMAIN_URL.'/job-post/',$mail->subject);
    if(!empty($str[1])) {
        $reference = str_replace('/','',trim(str_replace(' ', '', $str[1])));
        if(!empty($reference)){
            addJobBoardReference($mysqli,$reference);
        }
        $status = getJobBoardRefMailStatus($mysqli,$reference);
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
    $mailUpdate = updateJobBoardMails($mysqli, htmlentities($mail->messageId), $mail->id, $mail->id, $mail->fromName . ' ' . $mail->fromAddress, $mail->toString, $mail->subject, $msgBody, date('Y-m-d H:i:s', strtotime($mail->date)),$reference,$status);
    try {
        $att = $mail->getAttachments();
    }catch (Exception $e1){
        $msg = $e1->getMessage();
    }
    echo $msg;
    echo $reference.''.$mail->subject.''.$mail->fromName.'<br>';
    echo $mailUpdate.'<br>';
    foreach ($att as $files) {
        updateJobBoardAttachmentPath($mysqli, htmlentities($mail->messageId), str_replace('/opt/bitnami/apache/htdocs/','./',$files->filePath), $files->name);
        updateJobBoardContents($mysqli, htmlentities($mail->messageId), str_replace('/opt/bitnami/apache/htdocs/','./',$files->filePath));
    }
}

