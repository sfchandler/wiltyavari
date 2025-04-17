<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('max_execution_time', 1000000000000);
$ServerName = "outlook.office365.com";
$UserName = "resume@ .com.au";
$PassWord = " ";

/* try to connect */
try {
    $inbox = imap_open('{' . $ServerName . ':993/imap/ssl}INBOX', $UserName, $PassWord) or die("Could not open Mailbox - try again later!");
}catch (Exception $e){
    echo $e->getMessage();
}
$emails = imap_search($inbox,'ALL');
/* useful only if the above search is set to 'ALL' */
$max_emails = 16;
$hdr = imap_check($inbox);
if ($hdr) {
    //echo "Num Messages " . $hdr->Nmsgs ."\n\n<br><br>";
    echo "Num Messages " . $hdr->Nmsgs ."<br><br>";
    $msgCount = $hdr->Nmsgs;
} else {
    header("Refresh:0");
}

$last = ($msgCount-50).':'.$msgCount;//($msgCount-50)
//$ow=imap_fetch_overview($inbox,"1701:1800",0);//"1:{$hdr->Nmsgs}"
$ow=imap_fetch_overview($inbox,"6400:6500",0);
/*$MN=$msgCount;
$ow=imap_fetch_overview($inbox,"1:$MN",0);*/
$size=sizeof($ow);
$num_msg = imap_num_msg($inbox);
if ($num_msg > 0) {
    for ($i = $size - 1; $i >= 0; $i--) {
        $val = $ow[$i];
        $headers = imap_header($inbox, $i + 1);
        $msg = $val->msgno;
        $message_id = $val->message_id;
        $uid = $val->uid;
        $from = $val->from;
        $to = $val->to;
        $date = $val->date;
        $subj = $val->subject;
        $seen = $val->seen;

        $from = str_replace("\"", "", $from);
        $timestamp = strtotime($date);
        $msgbody = bodyRetrieval($inbox, $msg);
        $bodyText = imap_fetchbody($inbox,$msg,1.2);
        if(!strlen($bodyText)>0){
            $bodyText = imap_fetchbody($inbox,$msg,1);
        }
        $headersInfo = imap_headerinfo($inbox, $msg);
        $emailAddress = get_string_between($bodyText, 'mailto:', '"');
        preg_match("/[0-9]{10}/", $bodyText, $match );
        $phoneNumber = $match[0];
        echo htmlentities($headers->message_id) . ' UID ' . $uid . ' MSGNO ' . $msg . 'EMAIL'.$emailAddress.'PHONE'.$phoneNumber. '<br>';
        echo updateResumeEmailAndMobile($mysqli,htmlentities($headers->message_id),$emailAddress,$phoneNumber);
        // updateResumeMails($mysqli, htmlentities($headers->message_id), $uid, $msg, $from, $to, $subj, $msgbody, date('Y-m-d H:i:s', strtotime($date)));
        //echo htmlentities($headers->message_id) . ' UID ' . $uid . ' MSGNO ' . $msg . ' FROM ' . $from . ' TO ' . $to . ' SUBJECT ' . $subj . ' DATE ' . date('Y-m-d H:i:s', strtotime($date)).'EMAIL'.$emailAddress.'PHONE'.$phoneNumber. '<br>' . $msgbody . '<br>';
    }
    /*for ($i=$num_msg; $i>0; $i--) {
        $headers = imap_header($inbox, $i); //Note 3

        //If the subject is "Contact Form Submission", read the body
        if ($headers->Subject == "Contact Form Submission") {
            echo htmlentities($headers->message_id) . ' UID ' . $uid . ' MSGNO ' . $msg . ' FROM ' . $from . ' TO ' . $to . ' SUBJECT ' . $subj . ' DATE ' . date('Y-m-d H:i:s', strtotime($date)) . '<br>' . $msgbody . '<br>';

            $body = imap_body($mailbox, $i); //Note 5
            //Run code to parse body information
        }
    }*/
}else {
    echo "No messages in mailbox";
}
imap_close($inbox);