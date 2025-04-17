<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$notes = getDiaryNoteByCandidateId($mysqli,$_REQUEST['candidate_id']);
$html = '<table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Subject</th>
                <th>Consultant</th>
                <th>todoDate</th>
                <th>todoNote</th>
                <th>createdDate</th>
                <th>modifiedDate</th>
            </tr>
        </thead>
        <tbody>';
foreach ($notes as $note){
    $html = $html.'<tr>
                <td>'.$note['subject'].'</td>
                <td>'.getConsultantName($mysqli,$note['consultantId']).'</td>
                <td>'.$note['todoDate'].'</td>
                <td>'.$note['todoNote'].'</td>
                <td>'.$note['createdDate'].'</td>
                <td>'.$note['modifiedDate'].'</td>
            </tr>';
}
$html = $html.'</tbody></table>';
echo $html;