<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('max_execution_time', 100000000);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
/*$sql = $mysqli->prepare("SELECT DISTINCT reference FROM resume")or die($mysqli->error);
$sql->execute();
$sql->bind_result($reference) or die($mysqli->error);
$sql->store_result();
$sql->num_rows();
while($sql->fetch()){
    $dataInfo[] = array('reference'=>$reference);
}
$ins = $mysqli->prepare("INSERT INTO inbox_reference (reference) VALUES(?)")or die($mysqli->error);
foreach ($dataInfo as $data) {
    $reference = $data['reference'];
    $ins->bind_param("s",$reference)or die($mysqli->error);
    if($ins->execute()){
        echo 'Inserted<br>';
    }else{
        echo 'Error '.$mysqli->error.'<br>';
    }
}
$ins->close();*/
$catid = 7;
$inb_status = 1;
$status = 0;
$sql = $mysqli->prepare("SELECT autoid FROM mail_color_category WHERE catid = ?")or die($mysqli->error);
$sql->bind_param("i", $catid);
$sql->execute();
$sql->bind_result($autoid) or die($mysqli->error);
$sql->store_result();
$sql->num_rows();
while($sql->fetch()){
    $dataInfo[] = array('autoid'=>$autoid);
}
$up = $mysqli->prepare("UPDATE resume SET inbox_status = ?, status = ? WHERE autoid = ?")or die($mysqli->error);
foreach ($dataInfo as $data) {
    $autoid = $data['autoid'];
    $up->bind_param("iii",$inb_status,$status,$autoid)or die($mysqli->error);
    if($up->execute()){
        echo 'Update<br>';
    }else{
        echo 'Error '.$mysqli->error.'<br>';
    }
}
$up->close();
