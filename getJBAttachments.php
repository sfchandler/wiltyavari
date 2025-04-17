<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$messageid = htmlentities($_REQUEST['messageid']);
$tableAttachmentPath = getTableAttachmentPath($mysqli,'jobboard');
$mailatt = $mysqli->prepare("SELECT messageid,filepath,filename FROM {$tableAttachmentPath} WHERE messageid = ?")or die($mysqli->error);
$mailatt->bind_param("s",$messageid);
$mailatt->execute();
$mailatt->bind_result($messageid, $filepath,$filename) or die($mysqli->error);
$attr = array();
while($mailatt->fetch()){
    $fpath = explode('/',$filepath);
    //$filename = $fpath[2];
    $path_parts = pathinfo($filename);
    if($path_parts['extension'] == 'pdf'){
        $extension = 'pdf';
    }else if($path_parts['extension'] == 'doc'){
        $extension = 'doc';
    }else if($path_parts['extension'] == 'docx'){
        $extension = 'docx';
    }else if($path_parts['extension'] == 'txt'){
        $extension = 'txt';
    }

    $row = array('messageid' =>$messageid,'filepath'=>$filepath,'filename'=>$filename,'filetype'=>$extension);
    $attr[] = $row;
}
echo json_encode($attr);
?>