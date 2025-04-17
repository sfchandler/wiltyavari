<?php
session_start();

require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
require_once("../includes/PHPExcel-1.8/Classes/PHPExcel.php");

$shiftStatus = $_REQUEST['filterStatus'];
$selector = $_REQUEST['selector'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$empSelected = $_REQUEST['empSelected'];
$supervisorClient = $_SESSION['supervisorClient'];
$supervisorDepartment = $_SESSION['supervisorDepartment'];
$deptId = $_REQUEST['deptId'];
$positionId = $_REQUEST['positionId'];
if($shiftStatus == 'Y'){
    $jobList = getTimeClockBySupervisorForPeriod($mysqli, $shiftStatus, $startDate, $endDate, $empSelected,$supervisorClient,$deptId,$positionId);
}elseif($shiftStatus == 'N'){
    $jobList = getTimeClockBySupervisorForPeriod($mysqli, $shiftStatus, $startDate, $endDate, $empSelected,$supervisorClient,$deptId,$positionId);
}

$inputHtml = '<input type="checkbox" id="selectAll"/><i class="fa fa-fw fa-check txt-color-blue hidden-md hidden-sm hidden-xs"></i>';
$htmlData = $htmlData.'
<style>
table {
    border-collapse: collapse;
    width: 100%;
    font-size: 10pt;
}
th{
}
th.supervisor{
    background: #8fb489;
}
td{
    white-space: nowrap;
    text-align: center;
}
tr:nth-of-type(odd) { 
  background: #eee; 
}
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {
	table, thead, tbody, th, td, tr { 
		display: block; 
	}
	thead tr { 
		position: absolute;
		top: -9999px;
		left: -9999px;
	}
	tr { border: 1px solid #ccc; }
	
	td { 
		border: none;
		border-bottom: 1px solid #eee; 
		position: relative;
		padding-left: 50%; 
	}
	
	td:before { 
		position: absolute;
		top: 6px;
		left: 6px;
		width: 45%; 
		padding-right: 10px; 
		white-space: nowrap;
	}
	
	td:nth-of-type(1):before { content: "EmployeeID"; }
	td:nth-of-type(2):before { content: "Employer"; }
	td:nth-of-type(3):before { content: "Employee Name"; }
	td:nth-of-type(4):before { content: "Shift Day"; }
	td:nth-of-type(5):before { content: "Shift Date"; }
	td:nth-of-type(6):before { content: "Pick Roster Time"; }
	td:nth-of-type(7):before { content: "Supervisor Check IN(24hrs)"; }
	td:nth-of-type(8):before { content: "Payroll Check IN(24hrs)"; }
	td:nth-of-type(9):before { content: "Actual Check IN(24hrs)"; }
	td:nth-of-type(10):before { content: "Roster Start (24hrs)"; }
	td:nth-of-type(11):before { content: "Supervisor Check OUT(24hrs)"; }
	td:nth-of-type(12):before { content: "Payroll Check OUT(24hrs)"; }
	td:nth-of-type(13):before { content: "Actual Check OUT(24hrs)"; }
	td:nth-of-type(14):before { content: "Roster End (24hrs)"; }
	td:nth-of-type(15):before { content: "Work Break(minutes)"; }
	td:nth-of-type(16):before { content: "Work Hours"; }
	td:nth-of-type(17):before { content: "Comments"; }
	td:nth-of-type(18):before { content: "'.$inputHtml.'"; }
}
</style>';
if($shiftStatus == 'N') {
    $htmlData = $htmlData . '<table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employer</th>    
                                    <th>Employee Name</th>
                                    <th>Position</th>
                                    <th>Shift Day</th>
                                    <th>Shift Date</th>
                                    <!--<th>Pick Roster Time
                                    <input type="checkbox" id="rosterTimeAll">
                                    </th>-->
                                   <!-- <th>Roster Start (24hrs)</th>-->
                                  <!--  <th>Roster End(24hrs)</th>-->
                                    <th>Actual Check IN(24hrs)</th>
                                    <th>Actual Check OUT(24hrs)</th>
                                    <th>Supervisor Check IN(24hrs)</th>
                                    <th>Supervisor Check OUT(24hrs)</th>
                                    <th>Work Break(minutes)</th>
                                    <th>Work Hours</th>
                                    <th>Comments</th>
                                    <th>CheckIn Location</th>
                                    <th>CheckOut Location</th>
                                    <th>';
    $htmlData = $htmlData . '<input type="checkbox" id="selectAll"/><i class="fa fa-fw fa-check txt-color-blue hidden-md hidden-sm hidden-xs"></i>';
    $htmlData = $htmlData . '</th>
                        </tr>
                    </thead>
                    <tbody class="ClockBody">';
    $count = 0;
    if(sizeof($jobList)>0) {
        foreach ($jobList as $data) {
            $shDate = date('Y-m-d', strtotime($data['shiftDate']));
            if ($data['type'] == 'R') {
                $rows = $rows . '<tr style="background-color: white">';
                $checkInTime = '';
                $checkOutTime = '';
                $calculatedShiftWorkHours = '';
            } else {
                $checkInTime = timeChecker($data['checkIn'], $data['rosterStart']);
                $checkOutTime = checkoutTimeChecker($data['checkOut'], $data['rosterEnd']);
                if (($checkOutTime == '00:00') && ($data['type'] != 'R')) {
                    $rows = $rows . '<tr style="background-color: white">';
                } else {
                    $rows = $rows . '<tr>';
                }
                $calculatedShiftWorkHours = calculateHoursWorked(date('Y-m-d', strtotime($data['shiftDate'])), $data['supervisorCheckIn'], $data['supervisorCheckOut'], $data['workBreak']);
            }

            $rows = $rows . '<td><input type="hidden" name="shiftId[]" class="shiftId" value="' . $data['shiftId'] . '"/><input type="hidden" name="clientId[]" class="clientId" value="' . $data['clientId'] . '"/><input type="hidden" name="positionId[]" class="positionId" value="' . $data['positionId'] . '"/><input type="hidden" name="candidateId[]" class="candidateId" value="' . $data['candidateId'] . '"/><input type="hidden" name="supervicerId[]" class="supervicerId" value="' . $data['supervicerId'] . '"/>' . $data['candidateId'] . '</td>
                    <td style="word-wrap:break-word;">' . getClientNameByClientId($mysqli, $data['clientId']) . '</td>
                    <td title="'.getPositionByPositionId($mysqli,$data['positionId']).'">' . getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']) . ' ' . getCandidateLastNameByCandidateId($mysqli, $data['candidateId']) . '</td>
                    <td><select name="position_id[]">'.getPositionsBySupervisorClientWithPositionId($mysqli,$data['clientId'],$deptId,$data['positionId']).'</select></td>
                    <td><input type="hidden" name="shiftDay[]" class="shiftDay" value="' . $data['shiftDay'] . '"/>' . $data['shiftDay'] . '</td>
                    <td><input type="hidden" name="shiftDate[]" class="shiftDate" value="' . date('Y-m-d', strtotime($data['shiftDate'])) . '"/>' . date('Y-m-d', strtotime($data['shiftDate'])) . '</td>';
            /*$rows = $rows . '<td><input type="checkbox" name="rosterTick[' . $count . ']" class="rosterTick" value="' . $data['rosterStart'].'-'.$data['rosterEnd']. '"/></td>';*/
            /* $rows = $rows . '<td>' . $data['rosterStart'] . '</td><td>' . $data['rosterEnd'] . '</td>';*/
            $rows = $rows . '<td>' . $data['checkIn'] . '</td>';
            $rows = $rows . '<td><input type="hidden" name="actualCheckOut" class="actualCheckOut" value="' . $data['checkOut'] . '"/>' . $data['checkOut'] . '</td>';
            $rows = $rows . '<td><input type="text" name="checkInTime[]" class="shiftStart" value="' . $checkInTime . '" size="8"/></td>';
            $rows = $rows . '<td><input type="text" name="checkOutTime[]" class="shiftEnd" value="' . $checkOutTime . '" size="8"/>
                            </td>';
            $rows = $rows . '<td><input type="text" name="workBreak[]" class="wrkBreak" value="' . $data['workBreak'] . '" size="5"/>&nbsp; </td>
                    <td>';
            $rows = $rows . '<span class="totalHrs"></span>';
            $rows = $rows . '<input type="hidden" name="wrkhrs[]" class="hrs" value="' . $calculatedShiftWorkHours . '" size="5"/></td>';
            $rows = $rows . '<td><textarea name="comment[]" cols="5" rows="3">'.$data['comment'].'</textarea></td>';
            if(!empty($data['checkin_latitude'])) {
                $rows = $rows . '<td><a href="https://maps.google.com/?q='.$data['checkin_latitude']. ',' . $data['checkin_longitude'] . '" target="_blank"><img src="../img/googlemaps.png" alt="" width="35"></a></td>';
            }else{
                $rows = $rows . '<td></td>';
            }
            if(!empty($data['checkout_latitude'])) {
                $rows = $rows.'<td><a href="https://maps.google.com/?q='.$data['checkout_latitude'].','.$data['checkout_longitude'].'" target="_blank"><img src="../img/googlemaps.png" alt="" width="35"></a></td>';
            }else{
                $rows = $rows . '<td></td>';
            }
            $rows = $rows . '<td><input type="checkbox" name="checkTick[' . $count . ']" class="checkTick" value="' . $data['shiftId'] . '"/>';
            $rows = $rows . '</td></tr>';
            $count++;
        }
    }
    $htmlData = $htmlData . $rows;
    $htmlData = $htmlData . '<tr>';
    $htmlData = $htmlData . '<td colspan="18" align="right"><button type="submit" name="submitBtn" id="submitBtn" class="pull-right btn btn-danger">Submit</button></td>';
    $htmlData = $htmlData . '</tr></tbody></table></form>';
    echo $htmlData;
} elseif ($shiftStatus == 'Y') {
    $htmlData = $htmlData . '<table class="table table-striped table-bordered table-hover">
                             <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Employer</th>    
                                    <th>Employee Name</th>
                                    <th>Position</th>
                                    <th>Shift Day</th>
                                    <th>Shift Date</th>
                                    <!-- <th>Roster Start (24hrs)</th>
                                     <th>Roster End(24hrs)</th>-->
                                     <th>Actual Check IN(24hrs)</th>
                                     <th>Actual Check OUT(24hrs)</th>
                                    <th class="supervisor">Supervisor CheckIN</th>
                                    <th class="supervisor">Supervisor CheckOut</th>
                                    <th>Work Break(minutes)</th>
                                    <th>Work Hours</th>
                                    <th>Comments</th>
                                    <th>CheckIn Location</th>
                                    <th>CheckOut Location</th>
                                    <th></th>';
    $htmlData = $htmlData.'</tr>
                    </thead>
                    <tbody class="ClockBody">';
    $count = 0;
    foreach ($jobList as $data) {
        $shDate = date('Y-m-d', strtotime($data['shiftDate']));
        $rows = $rows.'<tr><td>'.$data['candidateId'].'</td>
                        <td>'.getClientNameByClientId($mysqli, $data['clientId']).'</td>
                        <td title="'.getPositionByPositionId($mysqli,$data['positionId']).'">'.getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']).' '. getCandidateLastNameByCandidateId($mysqli, $data['candidateId']).'</td>
                        <td>'.getPositionByPositionId($mysqli,$data['positionId']).'</td>
                        <td>'.$data['shiftDay'].'</td>
                        <td>'.date('Y-m-d', strtotime($data['shiftDate'])).'</td>';
        /*$rows = $rows.'<td>'.$data['rosterStart'].'</td><td>'.$data['rosterEnd'].'</td>';*/
        $rows = $rows.'<td>'.$data['checkIn'].'</td><td>'.$data['checkOut'].'</td>';
        $rows = $rows.'<td>'.$data['supervisorCheckIn'].'</td>';
        $rows = $rows.'<td>'.$data['supervisorCheckOut'].'</td>
                        <td>'.$data['workBreak'].'</td>
                        <td>'.$data['wrkhrs'].'</td>
                        <td>'.$data['comment'].'</td>';
        if(!empty($data['checkin_latitude'])) {
            $rows = $rows . '<td><a href="https://maps.google.com/?q='.$data['checkin_latitude']. ',' . $data['checkin_longitude'] . '" target="_blank">CheckIn Location</a></td>';
        }else{
            $rows = $rows . '<td></td>';
        }
        if(!empty($data['checkout_latitude'])) {
            $rows = $rows.'<td><a href="https://maps.google.com/?q='.$data['checkout_latitude'].','.$data['checkout_longitude'].'" target="_blank">CheckOut Location</a></td>';
        }else{
            $rows = $rows . '<td></td>';
        }
        $rows = $rows.'<td></td>';
        $rows = $rows . '</tr>';
        $count++;
    }
    $htmlData = $htmlData . $rows;
    $htmlData = $htmlData . '</tbody></table></form>';
    echo $htmlData;
}
?>