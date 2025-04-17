<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$clientId = $_REQUEST['clientId'];
$stateId = $_REQUEST['stateId'];
$deptId = $_REQUEST['deptId'];
$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$header = $_REQUEST['headerGlobal'];
$positionId = $_REQUEST['positionId'];
$action = $_REQUEST['action'];
$loggedInUser = $_SESSION['userSession'];
switch ($action){
    case 'DISPLAY':
        $row = '';
        $row = $row . '<tr>';
        for ($i = 0, $size = count($header); $i < $size; ++$i) {
            $row = $row.'<td class="jbOrderTableCell">';
            $startTime = '';
            $row = $row.'<div class="jbOrderAdd" data-date="' . $header[$i]['headerFullDate'] . '" data-clid="' . $clientId . '" data-posid="' . $positionId . '" data-deptid="' . $deptId . '" data-stateid="' . $stateId . '">+</div>';
            try {
                $jobOrders = getJobOrderQty($mysqli, $header[$i]['headerFullDate'], $clientId, $positionId, $deptId, $stateId);
                foreach($jobOrders as $jb) {
                    $shiftCount = 0;
                    $shiftInfo = getJobOrderMatchingShifts($mysqli, $header[$i]['headerFullDate'], $clientId, $positionId, $deptId, $stateId,$jb['start_time']);
                    $tblRow = '';
                    foreach ($shiftInfo as $shift){
                        $shiftCount = $shiftCount + $shift['numShifts'];
                        $gender = getGenderById($mysqli,$shift['candidateId']);
                        $genderIndicator = '';
                        if($gender == 'Male'){
                            $genderIndicator = '<span class="jbMale"><i class="fa fa-male"></i></span>';
                        }elseif ($gender == 'Female'){
                            $genderIndicator = '<span class="jbFemale"><i class="fa fa-female"></i></span>';
                        }
                        $tblRow = $tblRow.'<tr><td><div class="jbEmp">'.$genderIndicator.' '.$shift['firstName'].' '.$shift['lastName'].'</div></td><td>'.$shift['numShifts'].'</td></tr>';
                    }
                    $toFillQty = ($jb['order_qty'] - $shiftCount);
                    $row = $row.'<div class="jbOrderBubble';
                    if($toFillQty == 0){
                        $row = $row.' jbOrderBubbleGreen';
                    }
                    $row = $row.'" title="'.displayJobOrderLog($mysqli,$jb['job_id']).'">';
                    $row = $row.'<div class="jbEdit" data-jbid="'.$jb['job_id'].'" data-sttime="'.$jb['start_time'].'" data-jbqty='.$jb['order_qty'].' data-maleqty="'.$jb['male_qty'].'" data-femaleqty="'.$jb['female_qty'].'" data-date="' . $header[$i]['headerFullDate'] . '" data-clid="' . $clientId . '" data-posid="' . $positionId . '" data-deptid="' . $deptId . '" data-stateid="' . $stateId . '"><a href="#" style="text-decoration: none; color: white"><i class="fa fa-edit"></i></a></div>';
                    $row = $row.'<div class="jbInfo">'.$jb['start_time'].'</div>';
                    $row = $row.'<div class="jbQty">'.$jb['order_qty'].'</div>';
                    $row = $row.'<span class="jbMale"><i class="fa fa-male"></i> '.$jb['male_qty'].'</span>&nbsp;&nbsp;';
                    $row = $row.'<span class="jbFemale"><i class="fa fa-female"></i> '.$jb['female_qty'].'</span>';
                    $row = $row.'<div><a class="jbCollapse" data-toggle="collapse" data-target="#jbEmpList'.$jb['job_id'].'"><i class="fa fa-eye"></i></a></div></div>';
                    $row = $row.'<div id="jbEmpList'.$jb['job_id'].'" class="collapse" style="padding-left: 20%;">';
                    $row = $row.'<table class="table table-bordered" style="width: 80%"><thead><tr><th>Name</th><th>Shift Count</th></tr></thead><tbody>';
                    $row = $row.$tblRow;
                    $row = $row.'<tr class="filledTotal"><td>Total</td><td>'.$shiftCount.'</td></tr>';
                    $row = $row.'<tr class=';
                    if($toFillQty == 0){
                        $row = $row.'"filledQty"';
                        $row = $row.'><td colspan="2">Filled</td>';
                    }else{
                        $row = $row.'"toFillQty"';
                        if($toFillQty > 0) {
                            $row = $row.'><td>To Fill</td><td>';
                            $row = $row.$toFillQty.'</td>';
                        }else{
                            $row = $row.'><td></td><td>';
                            $row = $row.'OverFilled</td>';
                        }
                    }
                    $row = $row.'</tr>';
                    $row = $row.'</tbody></table></div>&nbsp;';
                }
            }catch (Exception $e1){
                echo $e1->getMessage();
            }
            $row = $row.'</td>';
        }
        $row = $row.'</tr>';
        echo $row;
        break;
    case 'ADD':
        $ordDate = $_REQUEST['ordDate'];
        $ordQty = $_REQUEST['ordQty'];
        $clid = $_REQUEST['clid'];
        $posid = $_REQUEST['posid'];
        $deptid = $_REQUEST['deptid'];
        $stateid = $_REQUEST['stateid'];
        $starttime = $_REQUEST['starttime'];
        $maleqty = $_REQUEST['maleqty'];
        $femaleqty = $_REQUEST['femaleqty'];
        $jobStatus = addJobOrderIndex($mysqli,$ordDate,$ordQty,$clid,$deptid,$stateid,$posid,$starttime,$maleqty,$femaleqty);
        if(strpos($jobStatus,'-')){
            $jobString = explode('-',$jobStatus);
            $message = $jobString[0];
            $job_id = $jobString[1];
            logJobOrderIndex($mysqli,$job_id,$ordDate,$ordQty,getClientNameByClientId($mysqli,$clid),getDepartmentById($mysqli,$deptid),getStateById($mysqli,$stateid),getPositionByPositionId($mysqli,$posid),$starttime,$maleqty,$femaleqty,$loggedInUser,'CREATE JOB ORDER');
        }else{
             $message = $jobStatus;
        }
        echo $message;
        break;
    case 'EDIT':
        $job_id = $_REQUEST['job_id'];
        $ordDate = $_REQUEST['ordDate'];
        $ordQty = $_REQUEST['ordQty'];
        $clid = $_REQUEST['clid'];
        $posid = $_REQUEST['posid'];
        $deptid = $_REQUEST['deptid'];
        $stateid = $_REQUEST['stateid'];
        $starttime = $_REQUEST['starttime'];
        $maleqty = $_REQUEST['maleqty'];
        $femaleqty = $_REQUEST['femaleqty'];
        $jobStatus = updateJobOrderIndex($mysqli, $job_id,$starttime,$ordQty, $maleqty, $femaleqty);
        logJobOrderIndex($mysqli,$job_id,$ordDate,$ordQty,getClientNameByClientId($mysqli,$clid),getDepartmentById($mysqli,$deptid),getStateById($mysqli,$stateid),getPositionByPositionId($mysqli,$posid),$starttime,$maleqty,$femaleqty,$loggedInUser,'UPDATE JOB ORDER');
        echo $jobStatus;
        break;
    /*case 'REMOVE':
        $job_id = $_REQUEST['job_id'];
        echo removeJobOrder($mysqli,$job_id);
        break;*/
    case 'PENDING':
        $html = $html.'<table class="pendingTable table table-striped" style="font-size:xx-small;">
                    <thead>
                      <tr>
                        <th colspan="6">PENDING ORDERS</th>
                      </tr>  
                      <tr>
                        <th>TO FILL</th>
                        <th>ORDER DATE</th>
                        <th>START TIME</th>
                        <th>DEPARTMENT</th>
                        <th>POSITION</th>
                        <th>STATE</th>
                      </tr>
                    </thead>
                    <tbody>';
        for ($i = 0, $size = count($header); $i < $size; ++$i) {
            $jobClientOrders = getClientJobOrderQty($mysqli,$header[$i]['headerFullDate'], $clientId);
            foreach ($jobClientOrders as $jb) {
                $shiftCount = 0;
                $shiftInfo = getJobOrderMatchingShifts($mysqli, $header[$i]['headerFullDate'], $clientId, $jb['pos_id'], $jb['dept_id'], $jb['state_id'], $jb['start_time']);
                foreach ($shiftInfo as $shift) {
                    $shiftCount = $shiftCount + $shift['numShifts'];
                }
                $toFillQty = ($jb['order_qty'] - $shiftCount);
                if($toFillQty > 0) {
                    $html = $html . '<tr> <td class="toFillQty">' . $toFillQty . '</td>
                                        <td>'.$header[$i]['headerFullDate'].'</td>
                                        <td>' . $jb['start_time'] . '</td>
                                        <td>' . getDepartmentById($mysqli, $jb['dept_id']) . '</td>
                                        <td>' . getPositionByPositionId($mysqli, $jb['pos_id']) . '</td>
                                        <td>' . getStateCodeById($mysqli, $jb['state_id']) . '</td>
                                    </tr>';
                }
            }
        }
        echo $html = $html.'</tbody></table>';
        break;
    default:
        break;
}