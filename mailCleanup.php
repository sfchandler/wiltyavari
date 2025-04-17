<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 16/04/2018
 * Time: 8:36 AM
 */
require_once("includes/db_conn.php");
ini_set('max_execution_time', 10000000000000);

$dir = './attachments';
echo scan_dir($dir);
function scan_dir($dir) {
    $ignored = array('.', '..', '.svn', '.htaccess');

    $files = array();
    foreach (scandir($dir) as $file) {
        if (in_array($file, $ignored)) continue;
        $files[$file] = filemtime($dir . '/' . $file);
    }

    arsort($files);
    $files = array_keys($files);

    return ($files) ? $files : false;
}
/*$mel = $mysqli->prepare("SELECT
                              resume.autoid,
                              resume.messageid,
                              attachmentpath.filepath,
                              attachmentpath.filename,
                              attachmentpath.krId,
                              attachment.id
                            FROM
                              resume
                              INNER JOIN attachment ON (resume.messageid = attachment.messageid)
                              INNER JOIN attachmentpath ON (resume.messageid = attachmentpath.messageid)
                            WHERE
                              resume.date <= (NOW() - INTERVAL 1 MONTH)
                            ORDER BY
                              resume.date DESC") or die($mysqli->error);
$mel->execute();
$mel->store_result();
$mel->bind_result($autoid,$messageid,$filepath,$filename,$krId,$id) or die($mysqli->error);
$status = false;
$count = 0;
while($mel->fetch()){
    $count++;
    if(file_exists($filepath)){
        echo 'File '.$filepath.' exists'.'<br>';
        if(unlink($filepath)) {
            echo 'unlink '.$filepath.'<br>';
            deleteMelMails($mysqli, $autoid, $krId, $id, $filepath);
        }else{
            echo 'Error Unlinking'.'<br>';
        }
    }else{
        echo 'File '.$filepath.' does not exist'.'<br>';
        echo 'Removing file information from DB '.$filepath.'<br>';
        deleteMelMails($mysqli, $autoid, $krId, $id, $filepath);
    }
}

function deleteMelMails($mysqli,$autoid,$krId,$id,$filepath){
    $delMail = $mysqli->prepare("DELETE FROM resume WHERE autoid = ?") or die($mysqli->error);
    $delMail->bind_param("i",$autoid) or die($mysqli->error);
    $delMail->execute();
    $mailRows = $delMail->affected_rows;

    $delPath = $mysqli->prepare("DELETE FROM attachmentpath WHERE krId = ?") or die($mysqli->error);
    $delPath->bind_param("i",$krId) or die($mysqli->error);
    $delPath->execute();
    $pathRows = $delPath->affected_rows;

    $delAttachment = $mysqli->prepare("DELETE FROM attachment WHERE id = ?") or die($mysqli->error);
    $delAttachment->bind_param("i",$id) or die($mysqli->error);
    $delAttachment->execute();
    $attachmentRows = $delAttachment->affected_rows;

    if($mailRows && $pathRows && $attachmentRows){
        echo 'ALL deleted'.'<br>';
    }else{
        echo 'Error'.'<br>';
    }
}*/
?>