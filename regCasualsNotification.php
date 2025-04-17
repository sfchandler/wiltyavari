<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 0);
set_time_limit(0);
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$endDate = date('Y-m-d', strtotime("-5 days"));

$consultants = getDistinctConsultants($mysqli);

foreach ($consultants as $cons) {
    $html = '<style>.zebra0{
                background-color: #cbd2d5;
            }
            .zebra1{
                background-color: white;
            }</style><table style="border: 1px" border="1" width="450">
            <thead>
              <tr>
                <th>EMPLOYEE ID</th>
                <th>FIRST NAME</th>
                <th>LAST NAME</th>
                <th>MOBILE NO</th>
                <th>EMAIL</th>
                <th>CONSULTANT</th>
                <th>FOUND US BY</th>
                <th>JOB REFERENCE APPLIED</th>
                <th>POSITIONS ASSIGNED</th>
                <th>REGISTERED DATE</th>
                <th>SCREENED DATE</th>
                <th>PHONE SCREENED DATE</th>
                <th>REGPACK SENT TIME</th>
                <th>REGPACK RECEIVED TIME</th>
                <th>REGPACK STATUS</th>
                <th>EMPLOYEE STATUS</th>
                <th>AUDIT STATUS</th>
                <th>CASUAL NOTES</th>
                <th>DO NOT USE</th>
                <th>STATUS UPDATED TIME</th>
                <th>LAST SHIFT</th>
              </tr>
            </thead>
            <tbody class="tblBody">';
    $endHtml = '</tbody></table>';
    $dataSet = getRegisteredCasualsInformationByConsultant($mysqli, $cons['consultantId']);
    $consultantEmail = getConsultantEmail($mysqli, $cons['consultantId']);
    if (!empty($dataSet)) {
        $i = 0;
        foreach ($dataSet as $data) {
            $shiftInfo = explode(':', getLastShiftInfoByCandidateId($mysqli, $data['candidateId']));
            $shiftSubmittedTime = $shiftInfo[0];
            if (empty($shiftSubmittedTime) && getAttributeCodeById($mysqli, getDONOTUSEAttribute($mysqli, $data['candidateId'])) != 'DO NOT USE') {
                    $html = $html . '<tr class="zebra' . ($i++ & 1) . '"><td>' . $data['candidateId'] . '</td>
                                    <td>' . $data['firstName'] . '</td>
                                    <td>' . $data['lastName'] . '</td>
                                    <td>' . $data['mobileNo'] . '</td>
                                    <td>' . $data['email'] . '</td>
                                    <td>' . getConsultantName($mysqli, $data['consultantId']) . '</td>';
                    $html = $html . '<td>' . getCandidateFoundHow($mysqli, $data['candidateId']) . '</td>';
                    $html = $html . '<td>' . $data['ref_code_applied'] . '</td>';
                    $html = $html . '<td>';
                    $posList = getEmployeePositionList($mysqli, $data['candidateId']);
                    foreach ($posList as $pos) {
                        $html = $html . $pos['positionName'] . '<br>';
                    }
                    $html = $html . '</td>';
                    $html = $html . '<td>' . $data['created_at'] . '</td>';
                    $html = $html . '<td>' . $data['screenDate'] . '</td>';
                    $html = $html . '<td>' . getCandidateDocumentDateByDocTypeId($mysqli, $data['candidateId'], 35) . '</td>';
                    $html = $html . '<td>' . getRegPackSentTime($mysqli, $data['candidateId']) . '</td>';
                    $html = $html . '<td>' . getCandidateDocumentDateByDocTypeId($mysqli, $data['candidateId'], 23) . '</td>';
                    if ($data['reg_pack_status'] == 1) {
                        $regpack = 'RECEIVED';
                    } else {
                        $regpack = '';
                    }
                    $html = $html . '<td>' . $regpack . '</td>';
                    $html = $html . '<td>' . $data['empStatus'] . '</td>';
                    if ($data['auditStatus'] == '1') {
                        $auditStatus = 'AUDITED';
                    } else {
                        $auditStatus = 'N/A';
                    }
                    $html = $html . '<td>' . $auditStatus . '</td>';
                    $html = $html . '<td>';
                    $html = $html . $data['casual_status'];
                    $html = $html . '</td>';
                    $html = $html . '<td>' . getAttributeCodeById($mysqli, getDONOTUSEAttribute($mysqli, $data['candidateId'])) . '</td>';
                    $html = $html . '<td>' . $data['casual_status_update'] . '</td>';
                    $html = $html . '<td>' . $shiftSubmittedTime . '</td>';
                    $html = $html . '</tr>';
            }
        }
        generateNotification($consultantEmail, 'indikaw@chandlerpersonnel.com.au', '', 'Registered Casuals No Activity last 5 days', DEFAULT_EMAIL, DOMAIN_NAME, $html . $endHtml, '', '');
    }

}
