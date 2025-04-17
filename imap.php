<!DOCTYPE html>
<html lang="en">

<head>
    <!--<script src="./js/jquery-3.1.1.js"></script>
    <script type="text/javascript">
        $(function() {
            setTimeout(function () {
                window.location.reload();
            }, 40*60*1000);//1*60*1000
        });
    </script>-->
</head>
</html>

<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*ini_set('max_execution_time', -1);
ini_set('default_socket_timeout', 900);*///15 minutes


$ServerName = "outlook.office365.com";
$UserName = "SwarnajithF@chandlerservices.com.au/resumes@chandlerhealth.com.au";
$PassWord = "Luha8528";

/* try to connect */
//$inbox = imap_open('{'.$ServerName.':993/imap/ssl/novalidate-cert}', $UserName, $PassWord, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN'))or die(var_dump(imap_errors()));//error_log(var_dump(imap_errors()), 3, "./errors/chandler-errors.log")
$inbox = imap_open ('{'.$ServerName.':995/pop3/ssl/novalidate-cert}', $UserName, $PassWord);
$emails = imap_search($inbox, 'ALL');

/*$tryCnt = 0;

while(!is_resource($inbox)){
    $inbox = imap_open('{'.$ServerName.':993/imap/ssl/novalidate-cert}INBOX', $UserName, $PassWord, NULL, 1,
        array('DISABLE_AUTHENTICATOR' => 'PLAIN'));
    $tryCnt++;
    if($tryCnt > 20){

        echo "Cannot Connect To Exchange Server:<br>";
        die(var_dump(imap_errors()));

    }
}
if(is_resource($inbox)) {*/
if ($hdr = imap_check($inbox)) {
    echo "Num Messages " . $hdr->Nmsgs . "<br><br>";
    $msgCount = $hdr->Nmsgs;
} else {
    echo "failed";
}

/* if any emails found, iterate through each email */
if ($emails) {

    $count = 1;

    /* put the newest emails on top */
    rsort($emails);

    /* for every email... */
    foreach ($emails as $email_number) {

        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox, $email_number, 0);
        /* Get message id */
        echo $msgid = $overview[0]->message_id;
        /* get mail message */
        $message = imap_fetchbody($inbox, $email_number, 2);

        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);

        $attachments = array();

        /* if any attachments found... */
        if (isset($structure->parts) && count($structure->parts)) {
            for ($i = 0; $i < count($structure->parts); $i++) {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );

                if ($structure->parts[$i]->ifdparameters) {
                    foreach ($structure->parts[$i]->dparameters as $object) {
                        if (strtolower($object->attribute) == 'filename') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }

                if ($structure->parts[$i]->ifparameters) {
                    foreach ($structure->parts[$i]->parameters as $object) {
                        if (strtolower($object->attribute) == 'name') {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }

                if ($attachments[$i]['is_attachment']) {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);

                    /* 4 = QUOTED-PRINTABLE encoding */
                    if ($structure->parts[$i]->encoding == 3) {
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    } /* 3 = BASE64 encoding */
                    elseif ($structure->parts[$i]->encoding == 4) {
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }

        /* iterate through each attachment and save it */
        foreach ($attachments as $attachment) {
            if ($attachment['is_attachment'] == 1) {
                $filename = $attachment['name'];
                if (empty($filename)) $filename = $attachment['filename'];

                if (empty($filename)) $filename = time() . ".dat";

                /* prefix the email number to the filename in case two emails
                 * have the attachment with the same file name.
                 */
                $newFileName = $email_number . "-" . $filename;
                $filePath = './hattachments/' . $newFileName;
                /* Check file extension */
                $fileArray = pathinfo($newFileName);
                $file_ext = $fileArray['extension'];
                // determines if the search string is in the filename.
                $filenameString = 'resume';
                /* upload files to server
                if (file_exists($filePath) == FALSE) {
                    try {
                        $fp = fopen($filePath, "w+");
                        stream_set_blocking($fp, 0);
                        if (flock($fp, LOCK_EX)) {
                            fwrite($fp, $attachment['attachment']);
                        }
                        flock($fp, LOCK_UN);
                        fclose($fp);
                    }
                    catch (Exception $e) {
                        echo 'Caught exception File Write: ', $e->getMessage(), "\n";
                    }
                    updateHealthAttachmentPath($mysqli, htmlentities($msgid), $filePath, $filename);
                }*/
                /* check for file names ending with resume */
                if (strpos(strtolower($newFileName), strtolower($filenameString))) {
                    echo 'MSGID'.htmlentities($msgid).'FILEPATH'.$filePath;
                    if ($file_ext == "doc" || $file_ext == "docx" || $file_ext == "pdf" || $file_ext == "DOC" || $file_ext == "DOCX" || $file_ext == "PDF") {
                        /* insert file contents to database */
                        /*$upRs = updateHealthResumeContents($mysqli, htmlentities($msgid), $filePath);
                        echo 'update status ' . $upRs;
                        if ($upRs == 'inserted') {
                            echo '<br>' . $newFileName . ' saved<br>';
                        } else if ($upRs == 'exists') {
                            echo '<br>' . $newFileName . ' exists<br>';
                        } else {
                            echo 'Error - Updating Email message content ->' . $upRs;
                        }*/
                    } else {
                        echo 'Invalid File format' . $newFileName . '<br>';
                    }
                }
            }

        }
    }

}

/* close the connection */
imap_close($inbox);


?>