<?php
include("includes/db_conn.php");
include("includes/functions.php");
ini_set('max_execution_time', 1000000);
ini_set('display_startup_errors', 1);

$sql = $mysqli->prepare("SELECT contents FROM attachment LIMIT 0,10")or die($mysqli->error);
$sql->execute();
$sql->bind_result($contents)or die($mysqli->error);
while($sql->fetch()){
    echo 'content--->'.$contents.'<br>';
}