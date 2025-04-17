<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 26/09/2018
 * Time: 4:57 PM
 */

require_once("includes/db_conn.php");
//require_once("includes/functions.php");
ini_set('max_execution_time', 1000000);
ini_set('display_errors',1);
$sql = $mysqli->prepare("SELECT date,messageid FROM resume ORDER BY date ASC LIMIT 0,20")or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($date,$messageid) or die($mysqli->error);
$count = 0;
$resumeArray = array();
$filePathArray = array();

while($sql->fetch()){
    $resumeArray[$messageid] = array('date'=>date('Y-m-d', strtotime($date)));
    //echo $messageid.date('Y-m-d', strtotime($date)).$count++.'<br>';
}
$sql->free_result();
//var_dump($resumeArray);
$count = 0;

foreach ($resumeArray as $key=>$value) {
    $mailDate = $value['date'];
    $paths = getFileAttachmentPath($mysqli, $key);
    foreach ($paths as $path) {
        $exPath = $path['path'];
        $newPath = str_replace('./attachments/', './attachments/' . $mailDate . '/', $exPath);
        if (rename($exPath, $newPath)) {
            if (updateAttachmentPaths($mysqli, $newPath, $path['id'])) {
                echo 'Attachment Path updated and files moved';
            }else{
                echo 'Error Updating Path';
            }
        } else {
            echo 'Error moving files';
        }
    }
}
foreach ($resumeArray as $key=>$value) {
    $mailDate = $value['date'];
    $files = getFilePath($mysqli, $key);
    foreach ($files as $file) {
        $exPath = $file['path'];
        $newPath = str_replace('./attachments/', './attachments/' . $mailDate . '/', $exPath);
        if (updateAttachment($mysqli, $newPath, $file['id'])) {
            echo 'Attachment Table Updated';
        } else {
            echo 'Error Updating Attachment table';
        }
    }
}

function updateAttachmentPaths($mysqli,$path,$krId){
    $up = $mysqli->prepare("UPDATE attachmentpath SET filepath = ? WHERE krId = ?")or die($mysqli->error);
    $up->bind_param("si",$path,$krId)or die($mysqli->error);
    if($up->execute()){
        return true;
    }else{
        return false;
    }
}
function updateAttachment($mysqli,$path,$id){
    $up = $mysqli->prepare("UPDATE attachment SET filepath = ? WHERE id = ?")or die($mysqli->error);
    $up->bind_param("si",$path,$id)or die($mysqli->error);
    if($up->execute()){
        return true;
    }else{
        return false;
    }
}
function getFileAttachmentPath($mysqli,$messageid){
    $stmt = $mysqli->prepare("SELECT krId,filepath FROM attachmentpath WHERE messageid = ?") or die($mysqli->error);
    $stmt->bind_param("s",$messageid)or die($mysqli->error);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($krId,$filepath)or die($mysqli->error);
    $pathArray = array();
    while($stmt->fetch()){
        $row = array('id'=>$krId,'path'=>$filepath);
        $pathArray[]  = $row;
    }
    return $pathArray;
}
function getFilePath($mysqli,$messageid){
    $stmt = $mysqli->prepare("SELECT id, filepath FROM attachment WHERE messageid = ?") or die($mysqli->error);
    $stmt->bind_param("s",$messageid)or die($mysqli->error);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id,$filepath)or die($mysqli->error);
    $pathArray = array();
    while($stmt->fetch()){
        $row = array('id'=>$id,'path'=>$filepath);
        $pathArray[]  = $row;
    }
    return $pathArray;
}