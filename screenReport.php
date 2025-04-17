<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$action = $_REQUEST['action'];
if($action == 'SCREENREPORT'){
    $html = '';
    $dataSet = getScreenReportData($mysqli);
    if (!empty($dataSet)) {
        foreach ($dataSet as $data) {
            $candidateInfo = explode('via', $data['mailfrom']);
            $html = $html.'<tr>
                <td>'.$candidateInfo[0].'</td>';
            $html = $html.'<td>'.$data['position'].'</td>';
            $html = $html.'<td>'.$data['modifiedDate'].'</td>';
            $html = $html.'<td>'.getMailComment($mysqli,$data['autoid']).'</td>';
            $html = $html.'<td>'.$data['category'].'</td>';
            $html = $html.'<td>'.$data['username'].'</td>';
            $html = $html.'</tr>';
        }
    }
    echo $html;
}