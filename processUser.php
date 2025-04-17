<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$user_id_to_delete = $_POST['user_id'];
$user = $_POST['username'];
$sql = $mysqli->prepare("SELECT username,session_id FROM login_monitor WHERE username = ? AND user_id = ?")or die($mysqli->error);
$sql->bind_param("si",$user,$user_id_to_delete)or die($mysqli->error);
$sql->execute();
$sql->bind_result($username,$session_id)or die($mysqli->error);

while($sql->fetch()){
    session_id($session_id);
    session_start();
    session_destroy();
    session_commit();
    $_SESSION = array();
    session_unset();
}



