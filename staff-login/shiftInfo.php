<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
if($_REQUEST['action'] == 'INFO') {
    $shifts = getEmployeeShiftsByDate($mysqli, $_SESSION['staffSession']);
    if(!empty($shifts)) {
        $html = $html . '<table class="table-striped" width="100%" cellspacing="2" cellpadding="2">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Company</th>
                        <th>Shift Start</th>
                        <th>Shift End</th>
                        <th></th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>';
        foreach ($shifts as $shift) {
            $html = $html . '<tr>
                        <td>' . $shift['shiftDate'] . '</td>
                        <td>' . $shift['client'] . '</td>
                        <td>' . $shift['shiftStart'] . '</td>
                        <td>' . $shift['shiftEnd'] . '</td>
                        <td data-shiftid="'.$shift['shiftId'].'"><button class="acceptBtn btn btn-success form-control form-control-sm">Accept</button></td>
                        <td data-shiftid="'.$shift['shiftId'].'"><button class="rejectBtn btn btn-danger form-control form-control-sm">Reject</button></td>';
        }
        $html = $html . '</tr>
                    </tbody>
                  </table>';
        echo $html;
    }
}elseif ($_REQUEST['action'] == 'HISTORY'){
    $shiftHistory = getEmployeeShiftHistory($mysqli,$_SESSION['staffSession']);
    if(!empty($shiftHistory)) {
        $html = $html . '<table class="table-striped" width="100%" cellspacing="2" cellpadding="2">
                    <thead>
                      <tr>
                        <th>Shift Date</th>
                        <th>Shift Day</th>
                        <th>Client</th>
                        <th>Shift Start</th>
                        <th>Shift End</th>
                        <th>Work Break</th>
                        <th>Work Hours</th>
                      </tr>
                    </thead>
                    <tbody>';
        foreach ($shiftHistory as $shift) {
            $html = $html . '<tr>
                        <td>' . $shift['shiftDate'] . '</td>
                        <td>' . $shift['shiftDay'] . '</td>
                        <td>' . $shift['client'] . '</td>
                        <td>' . $shift['shiftStart'] . '</td>
                        <td>' . $shift['shiftEnd'] . '</td>
                        <td>' . $shift['workBreak'] . '</td>
                        <td>' . $shift['wrkHrs'] . '</td>';
        }
        $html = $html . '</tr>
                    </tbody>
                  </table>';
        echo $html;
    }
}elseif($_REQUEST['action'] == 'ACCEPT') {
    $shiftId = $_POST['shiftId'];
    $status = $_POST['status'];
    $q1=$_POST['q1'];
    $q2=$_POST['q2'];
    $q3=$_POST['q3'];
    $q4=$_POST['q4'];
    updateShiftQuestions($mysqli,$shiftId,$q1,$q2,$q3,$q4,$status);
    updateShiftLog($mysqli,$_SESSION['staffSession'],$shiftId,$status);
    echo updateShiftStatus($mysqli,$_SESSION['staffSession'],$shiftId,$status);
}elseif($_REQUEST['action'] == 'REJECTED') {
    $shiftId = $_POST['shiftId'];
    $status = $_POST['status'];
    $q1=$_POST['q1'];
    $q2=$_POST['q2'];
    $q3=$_POST['q3'];
    $q4=$_POST['q4'];
    updateShiftLog($mysqli,$_SESSION['staffSession'],$shiftId,$status);
    $update = updateShiftStatus($mysqli,$_SESSION['staffSession'],$shiftId,$status);
    updateShiftQuestions($mysqli,$shiftId,$q1,$q2,$q3,$q4,$status);
    if($update == 'shiftUpdated'){
        $shiftClient = getClientNameByClientId($mysqli,getClientIdByShiftId($mysqli,$shiftId));
        $shiftDate = getShiftDateByShiftId($mysqli,$shiftId);
        $shiftCandidate = getCandidateIdByShiftId($mysqli,$shiftId);
        $candidateName = getCandidateFullName($mysqli,$shiftCandidate);
        $subject = $candidateName.' for '.$shiftDate.' is '.$_REQUEST['action'];
        $mailBody = 'Hello, <br>'.'<p style="text-decoration: underline">Shift Status Update</p><br>'.'Shift at'.$shiftClient.' for '.$shiftDate.' of '.$shiftCandidate.' '.$candidateName.' been '.$_REQUEST['action'];
        generateNotification('','','',$subject.' - '.$shiftId,'', DOMAIN_NAME,$mailBody,'','');
        //generateRejectNotification($shiftId,$subject,$mailBody);
    }
    echo $update;
}