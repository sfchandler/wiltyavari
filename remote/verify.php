<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 25/07/2018
 * Time: 11:35 AM
 */
$response = array();

require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sql = $mysqli->prepare("SELECT candidate_no FROM candidate WHERE clockPin IS NULL")or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($candidate_no)or die($mysqli->error);
while($sql->fetch()) {
    rePin:
    $pin = generatePIN();
    if (!checkUserPIN($mysqli, $pin)) {
        updateUserPIN($mysqli, $pin,$candidate_no);
        echo 'CLOCK PIN UPDATED ' . $pin.'<br>';
    } else {
        echo 'CLOCK PIN ALREADY EXISTS ' . $pin.'<br>';
        goto rePin;
    }
}


