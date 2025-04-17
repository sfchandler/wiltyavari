<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 16/10/2017
 * Time: 12:42 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

    $cur_date = date('Y-m-d');
    $status = 'Y';
    $sql = $mysqli->prepare("SELECT 
                                      check_log.candidateId,
                                      check_log.shiftId,
                                      check_log.checkInDate,
                                      check_log.checkOutDate,
                                      check_log.confirmed,
                                      check_log.workBreak,
                                      check_log.actualWorkTime,
                                      check_log.confirmCheckInTime,
                                      check_log.confirmCheckOutTime
                                    FROM
                                      check_log
                                    WHERE
                                      DATE(check_log.checkInDate) = ?
                                    AND 
                                      check_log.confirmed = ?") or die($mysqli->error);
    //AND check_log.confirmed = ?
    $sql->bind_param("ss",$cur_date,$status) or die($mysqli->error);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($candidateId,$shiftId,$checkInDate,$checkOutDate,$confirmed,$workBreak,$actualWorkTime,$confirmCheckInTime,$confirmCheckOutTime) or die($mysqli->error);
    $err = 'sss';
    while($sql->fetch()) {
        $shiftDate = date('Y-m-d',strtotime($checkInDate));
        $dayOfWeek = dayOfWeek($shiftDate);
        $checkInTime = date('H:i',strtotime($confirmCheckInTime));
        $checkOutTime = date('H:i',strtotime($confirmCheckOutTime));
        $clientId = getClientIdByShiftId($mysqli,$shiftId);
        $positionId = getPositionIdByShiftId($mysqli,$shiftId);
        $jobCode = getJobCodeByClientPosition($mysqli,$clientId,$positionId);
        $wkHrs = $actualWorkTime;
        $wkEndDate = $shiftDate;
        $err = updateTimeSheetForMobileApp($mysqli, $candidateId, $shiftId, $shiftDate,$dayOfWeek,$clientId,$positionId,$jobCode,$checkInTime,$checkOutTime,$workBreak,$wkHrs,$wkEndDate,$confirmed);
    }
    echo 'Transfer Code : '.$err.'-'.$clientId.'-'.$positionId.'-'.$jobCode;
?>