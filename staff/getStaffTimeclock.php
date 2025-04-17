<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
$candidateId = getCandidateIdByEmail($mysqli,$_SESSION['staff_username']);
switch ($_REQUEST['action']) {
    case 'ADD':
        try {
            $addShift = insertShift($mysqli,
                $_POST['shiftDate'],
                dayOfWeek($_POST['shiftDate']),
                $_POST['clientId'],
                $_POST['stateId'],
                $_POST['deptId'],
                $_POST['candidateId'],
                $_POST['shiftStart'],
                $_POST['shiftEnd'],
                $_POST['shiftBreak'],
                $_POST['workHours'],
                $_POST['shiftNote'],
                $_POST['positionId'],
                $_POST['shiftStatus'],
                1,
                0,
                $_SESSION['staff_username']
            );
            echo $addShift;
        }catch (Exception $e){
            echo json_encode($e->getMessage());
        }
        break;
    case 'FINALISE':
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $clientId = $_POST['clientId'];
        $stateId = $_POST['stateId'];
        $deptId = $_POST['deptId'];
        $positionId = $_POST['positionId'];
        echo updateTimeClock($mysqli,$candidateId,$start_date,$end_date,$clientId,$stateId,$deptId,$positionId);
        break;
    case 'BANKUPDATE':
        $bank_name = $_POST['bank_name'];
        $account_name = $_POST['account_name'];
        $bsb = $_POST['bsb'];
        $account_number = $_POST['account_number'];
        $status = updateBankAccount($mysqli,$candidateId,$bank_name,$account_name,$account_number,$bsb);
        if($status == 'Error updating bank account information'){
            echo $status;
        }else{
            echo 'Bank account information added/updated';
        }
        break;
    case 'TAXUPDATE':
        $tfn = $_POST['tax_file_no'];
        $tax_code = $_POST['tax_code'];
        updateCandidateTFN($mysqli,$candidateId,$tfn);
        assignTaxCodeToCandidate($mysqli,$candidateId,$tax_code);
        echo 'Tax information added/updated';
        break;
    case 'SUPERUPDATE':
        $super_fund_name = $_POST['super_fund_name'];
        $usi_no = $_POST['usi_no'];
        $super_member_no = $_POST['super_member_no'];
        $status = processSuperMemberInformation($mysqli,$candidateId,$super_fund_name,$super_member_no,$usi_no);
        if($status == 'updated'){
            echo 'Super Member Number updated';
        }else{
            echo 'Error';
        }
        break;
    default:
        $dateRange = $_POST['period'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $clientId = $_POST['clientId'];
        $stateId = $_POST['stateId'];
        $deptId = $_POST['deptId'];
        $positionId = $_POST['positionId'];
        $sql = $mysqli->prepare("SELECT shift.shiftId,
                                      shift.shiftDate,
                                      shift.shiftDay,
                                      shift.clientId,
                                      shift.stateId,
                                      shift.departmentId,
                                      shift.candidateId,
                                      shift.shiftStart,
                                      shift.shiftEnd,
                                      shift.workBreak,
                                      shift.wrkhrs,
                                      shift.shiftNote,
                                      shift.shiftStatus,
                                      shift.positionId
                            FROM shift WHERE shift.candidateId = ?
                                         AND shift.clientId = ?
                                         AND shift.stateId = ?
                                         AND shift.departmentId = ?
                                         AND shift.positionId = ?
                                         AND shift.shiftDate BETWEEN ? AND ? ORDER BY shift.shiftDate") or die($mysqli->error);
        $sql->bind_param("siiiiss", $candidateId,$clientId,$stateId,$deptId,$positionId,$start_date,$end_date);
        $sql->execute();
        $sql->bind_result($shiftId,
            $shiftDate,
            $shiftDay,
            $clientId,
            $stateId,
            $departmentId,
            $candidateId,
            $shiftStart,
            $shiftEnd,
            $workBreak,
            $wrkhrs,
            $shiftNote,
            $shiftStatus,
            $positionId) or die($mysqli->error);
        $sql->store_result();
        $dataArray = array();
        while ($sql->fetch()) {
            $dataArray[] = array(
                'shiftId' => $shiftId,
                'shiftDate' => $shiftDate,
                'shiftDay' => $shiftDay,
                'clientId' => $clientId,
                'stateId' => $stateId,
                'departmentId' => $departmentId,
                'candidateId' => $candidateId,
                'shiftStart' => $shiftStart,
                'shiftEnd' => $shiftEnd,
                'workBreak' => $workBreak,
                'wrkhrs' => $wrkhrs,
                'shiftNote' => $shiftNote,
                'shiftStatus' => $shiftStatus,
                'positionId' => $positionId
            );
        }
        $html = '';
        foreach ($dateRange as $dateRow) {
            if(!empty(($dataArray))) {
                if(!in_array($dateRow,array_column($dataArray, 'shiftDate'))) {
                    $html = $html . '<tr>
                                        <td class="text-center">' . $dateRow . '<input type="hidden" name="shift_date[]" value="' . $dateRow . '" class="sh_date"/>
                                        </td>
                                        <td class="text-center">' . dayOfWeek($dateRow) . '</td>
                                        <td class="text-center">
                                            <input type="text" name="shift_start[]" size="5" class="sh_start" style="width: 80px;"/>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="shift_end[]" size="5" class="sh_end" style="width: 80px;"/></td>
                                        <td class="text-center">
                                            <input type="number" name="shift_break[]"  size="3" class="sh_break" value="30" style="width: 50px;"/>
                                        </td>
                                        <td class="text-center">
                                            <input type="text" name="work_hours[]" class="sh_wrkhrs" value="" style="width: 50px;"/>
                                        </td>
                                        <td class="text-center">
                                            <textarea name="shift_note[]" cols="6" rows="4" class="shift_note"></textarea>
                                        </td>
                                        <td class="text-center"><button class="saveBtn btn btn-sm btn-info">Save</button></td></tr>';
                }else{
                    foreach ($dataArray as $data) {
                        if ($data['shiftDate'] == $dateRow) {
                            $html = $html . '<tr>
                                                <td class="text-center">' . $dateRow . '<input type="hidden" name="shiftId[]" class="shift_id" value="' . $data['shiftId'] . '"/>
                                                    <input type="hidden" name="shift_date[]" value="' . $dateRow . '" class="sh_date"/>
                                                </td>
                                                <td class="text-center">' . dayOfWeek($dateRow) . '</td><td class="text-center">
                                                    <input type="text" name="shift_start[]" size="5" class="sh_start" style="width: 80px;" value="' . $data['shiftStart'] . '"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="shift_end[]" size="5" class="sh_end" value="' . $data['shiftEnd'] . '" style="width: 80px;"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="number" name="shift_break[]"  size="3" class="sh_break" value="' . $data['workBreak'] . '" style="width: 50px;"/>
                                                </td>
                                                <td class="text-center">
                                                    <input type="text" name="work_hours[]" class="sh_wrkhrs" value="' . $data['wrkhrs'] . '" style="width: 50px;"/>
                                                </td>
                                                <td class="text-center">
                                                    <textarea name="shift_note[]" cols="6" rows="4" class="shift_note">' . $data['shiftNote'] . '</textarea>
                                                </td>
                                                <td class="text-center"></td>
                                              </tr>';
                        }
                    }
                }
            }else{
                $html = $html . '<tr><td class="text-center">' . $dateRow . '<input type="hidden" name="shift_date[]" value="' . $dateRow . '" class="sh_date"/></td><td class="text-center">' . dayOfWeek($dateRow) . '</td><td class="text-center"><input type="text" name="shift_start[]" size="5" class="sh_start" style="width: 80px;"/></td><td class="text-center"><input type="text" name="shift_end[]" size="5" class="sh_end" style="width: 80px;"/></td><td class="text-center"><input type="number" name="shift_break[]"  size="3" class="sh_break" value="30" style="width: 50px;"/></td><td class="text-center"><input type="text" name="work_hours[]" class="sh_wrkhrs" value="" style="width: 50px;"/></td><td class="text-center"><textarea name="shift_note[]" cols="6" rows="4" class="shift_note"></textarea></td><td class="text-center"><button class="saveBtn btn btn-sm btn-info">Save</button></td></tr>';
            }
        }
        $html = $html.'<tr class="saveAllRow"><td colspan="7"></td><td class="text-center"><button name="saveAllBtn" class="btn btn-info saveAllBtn">Save All</button></td></tr>';
        echo $html;
        break;
}
/*if ($_REQUEST['action'] == 'ADD') {

}elseif($_REQUEST['action'] == 'FINALISE'){

}else {

}*/