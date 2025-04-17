<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$action = $_POST['action'];
$reference = $_POST['reference'];
$id = $_POST['id'];
switch ($action){
    case "REFERENCE":
        echo getMailReferences($mysqli);
        break;
    case "LIST":
        echo getReferenceMailList($mysqli,$reference);
        break;
    case "VIEW":
        echo getMailBody($mysqli,$id);
        break;
}