<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
include("includes/php-imap-master/src/PhpImap/__autoload.php");
date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 100000000);
use PhpImap\Mailbox as ImapMailbox;
use PhpImap\IncomingMail;
use PhpImap\IncomingMailAttachment;
define('ATTACHMENT_DIRECTORY', __DIR__.DIRECTORY_SEPARATOR.'tlattachments');
$serverName = 'outlook.office365.com';
$username = 'sales@chandlerservices.com.au';
$passWord = 'Wox12253';
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
$latestMails = array_slice($mailsIds, 0, 500);
foreach ($latestMails as $mails) {
    $mail = $mailbox->getMail($mails);
    $str = explode('ref:',$mail->subject);
    if(!empty($str[1])) {
        $reference = trim(str_replace(' ', '', $str[1]));
    }else{
        $reference = NULL;
    }
    //echo htmlentities($mail->messageId).$mail->id. $mail->id. $mail->fromName . ' ' . $mail->fromAddress. $mail->toString. $mail->subject. $mail->textHtml. date('Y-m-d H:i:s', strtotime($mail->date)).'<br>';
    $txt = '';
    if(empty($mail->textHtml)){
        $txt = $mail->textPlain;
    }else{
        $txt = $mail->textHtml;
    }
    $mailUpdate = updateTalentRequestMails($mysqli, htmlentities($mail->messageId), $mail->id, $mail->id, $mail->fromName . ' ' . $mail->fromAddress, $mail->toString, $mail->subject, $txt, date('Y-m-d H:i:s', strtotime($mail->date)),$reference);
    try {
        $att = $mail->getAttachments();
    }catch (Exception $e1){
        $error = $e1->getMessage();
    }
    foreach ($att as $files) {
        //echo './',$files->filePath. $files->name.'<br>';
        updateTalentRequestAttachmentPath($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath), $files->name);
        updateTalentRequestContents($mysqli, htmlentities($mail->messageId), str_replace('/var/www/html/','./',$files->filePath));
    }
}

?>

