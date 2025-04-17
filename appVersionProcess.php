<?php
require_once('includes/db_conn.php');
require_once('includes/functions.php');
if(!empty($_REQUEST['action']) && $_REQUEST['action'] == 'UPDATE'){
    $mobile_os = $_POST['mobile_os'];
    $os_version = $_POST['os_version'];
    $can_id = $_POST['canId'];
    $up = $mysqli->prepare("UPDATE app_version SET mobile_os = ?,os_version = ? WHERE candidate_id = ?") or die($mysqli->error);
    $up->bind_param("sss", $mobile_os,$os_version,$can_id) or die($mysqli->error);
    $up->execute();
    $nrows = $up->affected_rows;
    if ($nrows == '1') {
        echo 'UPDATED';
    }else{
        echo 'ERROR';
    }
}else {
    if (isset($_POST['mobile_os']) && isset($_POST['os_version']) && isset($_POST['can_id'])) {
        $can_id = base64_decode($_POST['can_id']);
        $id = base64_encode($can_id);
        $mobile_os = $_POST['mobile_os'];
        $os_version = $_POST['os_version'];
        $sql = $mysqli->prepare("SELECT candidate_id FROM app_version WHERE candidate_id = ?") or die($mysqli->error);
        $sql->bind_param("s", $can_id) or die($mysqli->error);
        $sql->execute();
        $sql->store_result();
        $sql->fetch();
        $num_of_rows = $sql->num_rows;
        $sql->free_result();
        if ($num_of_rows > 0) {
            $msg = "Mobile device information already submitted";
            header("Location: appVersion.php?msg=$msg&id=$id");
        } else {
            $ins = $mysqli->prepare("INSERT INTO app_version(candidate_id,mobile_os,os_version) VALUES (?,?,?)") or die($mysqli->error);
            $ins->bind_param("sss", $can_id, $mobile_os, $os_version) or die($mysqli->error);
            if ($ins->execute()) {
                $consultantEmail = getConsultantEmail($mysqli, getConsultantId($mysqli, $_REQUEST['cons_id']));
                $candidateName = getCandidateFullName($mysqli, $can_id);
                generateNotification($consultantEmail, '', '', 'App Version Check Sent Submitted', DEFAULT_EMAIL, DOMAIN_NAME, $candidateName . '(' . $can_id . ') has submitted app version check', '', '');
                $msg = "Mobile device information submitted successfully";
                header("Location: appVersion.php?msg=$msg&id=$id");
            } else {
                $msg = "Error submitting Mobile device information";
                header("Location: appVersion.php?msg=$msg&id=$id");
            }
        }
    }
}