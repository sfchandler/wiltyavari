<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$clientId = $_POST['clientid'];
$status = $_POST['status'];

    $sql = $mysqli->prepare("SELECT
                          timeclock.candidateId,
                          timeclock.shiftId,
                          timeclock.shiftDate,
                          timeclock.shiftDay,
                          timeclock.clientId,
                          timeclock.positionId,
                          timeclock.deptId,
                          timeclock.jobCode,
                          timeclock.checkIn,
                          timeclock.checkOut,
                          timeclock.workBreak,
                          timeclock.wrkhrs,
                          timeclock.supervicerId,
                          timeclock.supervisorCheck,
                          timeclock.supervisor,
                          timeclock.approvalTime,
                          timeclock.transport
                        FROM
                          timeclock
                        WHERE
                        timeclock.checkIn IS NOT NULL
                        AND timeclock.checkOut IS NOT NULL
                        AND timeclock.supervisorCheck = ?
                        AND timeclock.clientId = ?
                        AND timeclock.shiftDate BETWEEN ? AND ?") or die($mysqli->error);
    $sql->bind_param("siss", $status, $clientId, $startDate, $endDate) or die($mysqli->error);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($candidateId, $shiftId, $shiftDate, $shiftDay, $clientId, $positionId, $deptId, $jobCode, $checkIn, $checkOut, $workBreak,$wrkhrs, $supervicerId, $supervisorCheck, $supervisor, $approvalTime, $transport) or die($mysqli->error);
    $error = '';
    while ($sql->fetch()) {
        $row = updateTimeSheetForCasuals($mysqli, $candidateId,$shiftId,$shiftDate,$shiftDay,$clientId,$positionId,$deptId,$jobCode,$checkIn,$checkOut,$workBreak,$wrkhrs,$supervisorCheck,$transport);
        $error = 'Added';
    }
    echo $error;
?>