<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

try {
    $shiftInfo = getReleasedShifts($mysqli);
}catch (Exception $e){
    echo $e->getMessage();
}
$table = '<table id="relTbl" class="table table-bordered table-striped table-hover table-responsive">
                        <thead>
                          <tr>
                            <th class="thFilter">Client</th>
                            <th class="thFilter">Released Time</th>
                            <th class="thFilter">Release Shift Date</th>
                            <th class="thFilter">Day</th>
                            <th class="thFilter">State</th>
                            <th class="thFilter">Department</th>
                            <th class="thFilter">Position</th>
                            <th class="thFilter">Shift Start</th>
                            <th class="thFilter">Shift End</th>
                            <th class="thFilter">Shift Break</th>
                            <th class="thFilter">Release Status</th>
                            <th class="thFilter">Accepted by</th>
                            <th>Action</th>
                          </tr>
                        </thead><tbody id="rel_shift_body">';
$row = '';
foreach($shiftInfo as $rel) {
    $row = $row.'<tr>
        <td>'.$rel['client'].'</td>
        <td>'.$rel['release_time'].'</td>
        <td>'.$rel['rel_shift_date'].'</td>
        <td>'.$rel['rel_shift_day'].'</td>
        <td>'.$rel['state'].'</td>
        <td>'.$rel['department'].'</td>
        <td>'.$rel['positionName'].'</td>
        <td>'.$rel['rel_shift_start'].'</td>
        <td>'.$rel['rel_shift_end'].'</td>
        <td>'.$rel['rel_shift_break'].'</td>
        <td>'.$rel['rel_shift_status'].'</td>
        <td>'.displayShiftReleasedAcceptance($mysqli,$rel['rel_shift_id']).'</td>
        <td data-relshiftid="'.$rel['rel_shift_id'].'">';
    if(!validateReleaseAcceptance($mysqli,$rel['rel_shift_id'])){
        $row = $row.'<button name="confirmRelShift" id="confirmRelShift" class="btn btn-info confirmRelShift">CONFIRM & CREATE</button>';
    }
    $row = $row.'&nbsp;&nbsp;<button class="btn btn-danger releaseShiftRemoveBtn"><i class="fa fa-trash"></i></button>';
    $row = $row.'</td>
    </tr>';
}
$footer = '</tbody></table>';
echo $table.$row.$footer;