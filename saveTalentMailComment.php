<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/06/2018
 * Time: 11:25 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$autoId = $_POST['mAutoId'];
$mComment = $_POST['mComment'];
if(isset($autoId) && isset($mComment)){
    echo saveTalentMailComment($mysqli,$autoId,$mComment,$_SESSION['userSession']);
}

?>