<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');

if(!empty($_REQUEST['canId']) && !empty($_REQUEST['action'])) {
    $canId = $_REQUEST['canId'];
    $action = $_REQUEST['action'];
    $candidateFullName = getCandidateFullName($mysqli,$canId);
    $consultantName = getConsultantName($mysqli,getConsultantId($mysqli,$_SESSION['userSession']));
    $consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession']));
    if ($action == 'FairWorkInfo') {
        try {
            updateCandidateFairWorkInfo($mysqli,$canId);
            echo generateNotification(
                getEmployeeEmail($mysqli,$canId),
                '',
                '',
                'Casual Employment Information Statement & Fair Work Information Statement',
                DEFAULT_EMAIL,
                'Chandler Personnel',
                '<br><br> Hi '.$candidateFullName.','.
                '<br><br>
                Please see the below links with the revised induction document including the new Fair Work guidance material for casual employment. 
                <br><br>
                <a href="'.DOMAIN_URL.'/induction_fair_work.php?conEmail='.base64_encode($consultantEmail).'&candidateId='.base64_encode($canId).'" target="_blank">Click here to view Induction</a>
                <br><br>
                Thank you,
                <br><br>
                '.$consultantName.'
                <br><br>
                '.DOMAIN_NAME.'

                ','','');
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
}

