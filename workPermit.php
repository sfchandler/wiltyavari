<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$action = $_REQUEST['action'];
$candidateId = $_REQUEST['canId'];
$emailpath = $_REQUEST['emailpath'];
$empEmail = getEmployeeEmail($mysqli,$candidateId);
$firstName = getCandidateFirstNameByCandidateId($mysqli,$candidateId);
$lastName = getCandidateLastNameByCandidateId($mysqli,$candidateId);
$consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession']));
if($action == 'WORKPERMIT') {
    try {
        echo generateWorkPermitEmail($firstName, $lastName, $consultantEmail, $empEmail, $emailpath);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}