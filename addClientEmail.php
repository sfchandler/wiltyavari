<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_POST['clientId'];
$action = $_POST['action'];

if($action == 'ADD') {
    $email = $_POST['email'];
    if (!empty($email) && (!empty($clientId))) {
        echo addClientEmail($mysqli, $clientId, $email);
    }
}else if($action == 'DELETE') {
    $clEmId = $_POST['clEmId'];
    echo removeClientEmail($mysqli,$clEmId,$clientId);
}