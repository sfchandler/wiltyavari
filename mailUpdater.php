<!DOCTYPE html>
<html lang="en">

<head>
    <!--<meta http-equiv="refresh" content="15;">-->
	<script src="../js/jquery.min.js"></script>
	<script type="text/javascript">
        /*$(function() {
            alert('DOM ready');
            setTimeout(function () { 
                window.location.reload(); 
            }, 1*60*1000);
        });*/
    </script>
</head>
</html>    
	
<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

ini_set('max_execution_time', 100000000);

$ServerName = "outlook.office365.com"; 
$UserName = "SwarnajithF@chandlerservices.com.au/resume@chandlerrecruitment.com.au";
$PassWord = "Luha8528";
 
/* try to connect */
$inbox = imap_open('{'.$ServerName.':993/imap/ssl}', $UserName, $PassWord)or die(error_log("Could not open Mailbox - try again later!", 3, "./errors/chandler-errors.log")); 

$emails = imap_search($inbox,'ALL');
/* useful only if the above search is set to 'ALL' */
$max_emails = 16;

if ($hdr = imap_check($inbox)) {
	//echo "Num Messages " . $hdr->Nmsgs ."\n\n<br><br>";
	error_log("Num Messages " . $hdr->Nmsgs ."\n\n<br><br>", 3, "./errors/chandler-errors.log");
	$msgCount = $hdr->Nmsgs;
} else {
	error_log("Failed ", 3, "./errors/chandler-errors.log");
} 

$MN=$msgCount;
$ow=imap_fetch_overview($inbox,"1:$MN",0);
$size=sizeof($ow);

for($i=$size-1;$i>=0;$i--){
   	$val=$ow[$i];
	$headers = imap_header($inbox, $i+1);
	$msg=$val->msgno;
	$message_id=$val->message_id;
	$uid=$val->uid;
   	$from=$val->from;
	$to=$val->to;
  	$date=$val->date;
	$subj=$val->subject;
   	$seen=$val->seen;
   
	$from = str_replace("\"","",$from);
   
   	/*list($dayName,$day,$month,$year,$time) = split(" ",$date); 
		$time = substr($time,0,5);
   		$date = $day ." ". $month ." ". $year . " ". $time;
   
	if (strlen($subj) > 60) {
   		$subj = substr($subj,0,59) ."...";
	}*/
	$timestamp = strtotime($date);
   	//echo 'uid '.$uid.'  '.'message_id=>'.htmlentities($headers->message_id).'  '.'msgno '.$msg.'from  '.$from.' to '.$to.' timestamp  '.$timestamp.' Date=>'.date("d F Y H:i:s", $timestamp).'subject  '.$subj."<br/>";
	$msgbody = bodyRetrieval($inbox, $msg);
	$headersInfo = imap_headerinfo($inbox,$msg);
	$mailUpdate = updateResumeMails($mysqli,htmlentities($headers->message_id),$uid,$msg,$from,$to,$subj,htmlentities($msgbody),date('Y-m-d H:i:s', $timestamp));
	if($mailUpdate){
		error_log(htmlentities($headers->message_id)." MSG Added", 3, "./errors/chandler-errors.log");
	}else{
		error_log(htmlentities($headers->message_id)." Mail Existing/Error Inserting", 3, "./errors/chandler-errors.log");
	}
	
}
	
/* if any emails found, iterate through each email */
if($emails) {
 
    $count = 1;
 
    /* put the newest emails on top */
    rsort($emails);
 
    /* for every email... */
    foreach($emails as $email_number) 
    {
 
        /* get information specific to this email */
        $overview = imap_fetch_overview($inbox,$email_number,0);
		/* Get message id */
		$msgid = $overview[0]->message_id;
        /* get mail message */
        $message = imap_fetchbody($inbox,$email_number,2);
 		
        /* get mail structure */
        $structure = imap_fetchstructure($inbox, $email_number);
 
        $attachments = array();
 
        /* if any attachments found... */
        if(isset($structure->parts) && count($structure->parts)) 
        {
            for($i = 0; $i < count($structure->parts); $i++) 
            {
                $attachments[$i] = array(
                    'is_attachment' => false,
                    'filename' => '',
                    'name' => '',
                    'attachment' => ''
                );
 
                if($structure->parts[$i]->ifdparameters) 
                {
                    foreach($structure->parts[$i]->dparameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'filename') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['filename'] = $object->value;
                        }
                    }
                }
 
                if($structure->parts[$i]->ifparameters) 
                {
                    foreach($structure->parts[$i]->parameters as $object) 
                    {
                        if(strtolower($object->attribute) == 'name') 
                        {
                            $attachments[$i]['is_attachment'] = true;
                            $attachments[$i]['name'] = $object->value;
                        }
                    }
                }
 
                if($attachments[$i]['is_attachment']) 
                {
                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i+1);
 
                    /* 4 = QUOTED-PRINTABLE encoding */
                    if($structure->parts[$i]->encoding == 3) 
                    { 
                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                    }
                    /* 3 = BASE64 encoding */
                    elseif($structure->parts[$i]->encoding == 4) 
                    { 
                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                    }
                }
            }
        }
 
        /* iterate through each attachment and save it */
        foreach($attachments as $attachment)
        {
            if($attachment['is_attachment'] == 1)
            {
                $filename = $attachment['name'];
                if(empty($filename)) $filename = $attachment['filename'];
 
                if(empty($filename)) $filename = time() . ".dat";
				$newFileName = $email_number . "-" .$filename;
				$filePath = './attachments/'.$newFileName;
				/* Check file extension */
				$fileArray = pathinfo($newFileName);
				$file_ext  = $fileArray['extension'];
				// determines if the search string is in the filename.
				$filenameString = 'resume';
                /* upload files to server */
                try{
                    $fp = fopen($filePath, "w+");
                    stream_set_blocking($fp, 0);
                    if (flock($fp, LOCK_EX)) {
                        fwrite($fp, $attachment['attachment']);
                    }
                    flock($fp, LOCK_UN);
                    fclose($fp);
                } catch (Exception $e) {
                    echo 'Caught exception File Write: ',  $e->getMessage(), "\n";
                }
                /* update all attachment file paths */
                updateAttachmentPath($mysqli,htmlentities($msgid),$filePath);
                /* check for file names ending with resume */
                if(strpos(strtolower($newFileName), strtolower($filenameString))) {
                    if($file_ext == "doc" || $file_ext == "docx" || $file_ext == "pdf" || $file_ext == "DOC" || $file_ext == "DOCX" || $file_ext == "PDF")
					{
						/* insert file contents to database */
						$upRs = updateResumeContents($mysqli,htmlentities($msgid),$filePath);
						error_log(htmlentities($msgid)." update status ".$upRs, 3, "./errors/chandler-errors.log");
						//echo 'update status '.$upRs;
						if($upRs == true || $upRs == 'updated'){
							//echo '<br>'.$newFileName.' saved<br>';
							error_log(htmlentities($msgid)." update status ".$upRs, 3, "./errors/chandler-errors.log");
						}else{
							//echo 'Error - Updating resume content - '.$upRs;
							error_log(htmlentities($msgid)." Error - Updating resume content - ".$upRs, 3, "./errors/chandler-errors.log");
						}
					}else{
						//echo 'Invalid File format'.$newFileName.'<br>';
						error_log(htmlentities($msgid)." Invalid File format - ".$newFileName, 3, "./errors/chandler-errors.log");
					}
				}
            }
 
        }
        //if($count++ >= $max_emails) break;
    }
 
} 

/* close the connection */
imap_close($inbox);
/*echo "<script type=\"text/javascript\">
		window.open('./contentUpdater.php', '_blank')
    </script>";*/
header("Location:./contentUpdater.php");
?>