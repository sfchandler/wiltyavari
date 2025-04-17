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
$username = '@.com.au';
$passWord = "";
try{
    $mailbox = new PhpImap\Mailbox('{'.$serverName.':993/imap/ssl}INBOX', $username, $passWord, ATTACHMENT_DIRECTORY);
}catch (Exception $e){
    $e->getMessage();
}
$mailsIds = $mailbox->searchMailbox('ALL');
if(!$mailsIds) {
    echo 'Mailbox is empty';
}else{
    echo 'Reading Mailbox';
}
rsort($mailsIds);
$latestMails = array_slice($mailsIds, 0, 100);
foreach ($latestMails as $mails) {
    $mail = $mailbox->getMail($mails);
    echo htmlentities($mail->messageId). $mail->id.  $mail->fromName . ' ' . $mail->fromAddress. $mail->toString. $mail->subject. $mail->textHtml. date('Y-m-d H:i:s', strtotime($mail->date)).'<br>';
    /*$str = explode('ref:',$mail->subject);
    if(!empty($str[1])) {
        $reference = trim(str_replace(' ', '', $str[1]));
        $status = getRefMailStatus($mysqli,$reference);
    }else{
        $reference = 'UNKNOWN';
        $status = 0;
    }
    $mailUpdate = updateResumeMails($mysqli, htmlentities($mail->messageId), $mail->id, $mail->id, $mail->fromName . ' ' . $mail->fromAddress, $mail->toString, $mail->subject, $mail->textHtml, date('Y-m-d H:i:s', strtotime($mail->date)),$reference,$status);
    try {
        $att = $mail->getAttachments();
    }catch (Exception $e1){
        $error = $e1->getMessage();
        //testA('Error saving attachments'.$error);
    }
    foreach ($att as $files) {
        //echo 'fpa>>'.$files->filePath;
        updateAttachmentPath($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath), $files->name);
        updateResumeContents($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath));
    }*/
}



?>

