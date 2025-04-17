<!DOCTYPE html>
<html lang="en">

<head>
    <!--<meta http-equiv="refresh" content="15;">-->
	<script src="js/jquery-3.1.1.js"></script>
	<script type="text/javascript">
        $(function() {
            setTimeout(function () { 
                window.location.reload(); 
            }, 3*60*1000);//1*60*1000
        });
    </script>
</head>
</html>    
	
<?php
startScript:
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
ini_set('max_execution_time', 1000000000);
//ini_set('default_socket_timeout', 900);//15 minutes
/*set_time_limit(0);
while(true) {*/
    $ServerName = "outlook.office365.com";
    $UserName = "applicationsnsw@chandlerservices.com.au";
    $PassWord = "Sup89423";

    $inbox = imap_open('{'.$ServerName . ':993/imap/ssl/novalidate-cert}INBOX', $UserName, $PassWord, NULL, 1, array('DISABLE_AUTHENTICATOR' => 'PLAIN'))or die(var_dump(imap_errors()));
    //$inbox = imap_open ('{'.$ServerName.':995/pop3/ssl/novalidate-cert}', $UserName, $PassWord)or die(var_dump(imap_errors()));

    $tryCnt = 0;
    //$max_emails = 300;
    while (!is_resource($inbox)) {

        $inbox = imap_open("{'.$ServerName.':993/imap/ssl/novalidate-cert}$inbox",
            $UserName, $PassWord, NULL, 1,
            array('DISABLE_AUTHENTICATOR' => 'GSSAPI'));
        $tryCnt++;

        if (!is_resource($inbox)) {

            $inbox = imap_open("{'.$ServerName.':993/imap/ssl/novalidate-cert}$inbox",
                $UserName, $PassWord, NULL, 1,
                array('DISABLE_AUTHENTICATOR' => 'PLAIN'));
            $tryCnt++;

        }
        if (!is_resource($inbox)) {
            $inbox = imap_open('{' . $ServerName . ':995/pop3/ssl/novalidate-cert}', $UserName, $PassWord) or die(var_dump(imap_errors()));
            $tryCnt++;
        }

        if ($tryCnt > 20) {

            echo "Cannot Connect To Exchange Server:<BR>";
            die(var_dump(imap_errors()));

        }
    }
    if ($hdr = imap_check($inbox)) {
        echo "Num Messages " . $hdr->Nmsgs . "<br><br>";
        $msgCount = $hdr->Nmsgs;
    } else {
        echo "Failed Imap Check";
    }
    $emails = imap_search($inbox, 'ALL');
    /* if any emails found, iterate through each email */
    if ($emails) {

        $count = 1;

        /* put the newest emails on top */
        rsort($emails);

        /* for every email... */
        foreach ($emails as $email_number) {
            $count++;
            /* get information specific to this email */
            $overview = imap_fetch_overview($inbox, $email_number, 0);
            /* Get message id */
            //echo $msgid = $overview[0]->message_id;
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
                    //$newFileName = $email_number . "-" . $filename;
                    $newFileName = '';
                    $filePath = './nswattachments/' . $filename;
                    /* Check file extension */
                    $fileArray = pathinfo($filename);
                    $file_ext = $fileArray['extension'];
                    // determines if the search string is in the filename.
                    $filenameString = 'resume';
                    /* upload files to server */
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
                            //echo 'Caught exception File Write: ', $e->getMessage(), "\n";
                        }
                        /* update all attachment file paths */
                        updateNSWAttachmentPath($mysqli, htmlentities($msgid), $filePath, $filename);
                        $newFileName = $filename;
                    } else {
                        $newAttachmentFileName = $email_number . "-" . $filename;
                        $filePath = './nswattachments/' . $newAttachmentFileName;
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
                        /* update all attachment file paths */
                        updateNSWAttachmentPath($mysqli, htmlentities($msgid), $filePath, $filename);
                        $newFileName = $newAttachmentFileName;
                    }
                    /* check for file names ending with resume */
                    if (strpos(strtolower($newFileName), strtolower($filenameString))) {
                        if ($file_ext == "doc" || $file_ext == "docx" || $file_ext == "pdf" || $file_ext == "DOC" || $file_ext == "DOCX" || $file_ext == "PDF") {
                            /* insert file contents to database */
                            $upRs = updateNSWResumeContents($mysqli, htmlentities($msgid), $filePath);
                            if ($upRs == 'inserted') {
                                echo '<br>' . $newFileName . ' saved<br>';
                            } else if ($upRs == 'updated') {
                                echo '<br>' . $newFileName . ' updated<br>';
                            } else {
                                echo 'Error - Updating NSW email content - ' . $upRs;
                            }
                        } else {
                            echo 'Invalid File format' . $newFileName . '<br>';
                        }
                    }
                }

            }
            //if ($count == $max_emails) break;
        }

    }

    /* close the connection */
    imap_close($inbox);
    /*sleep(60);
}*/
    goto startScript;

?>