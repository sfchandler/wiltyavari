<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
date_default_timezone_set('Australia/Melbourne');
try{
    $openShiftsData = openShiftsNotifer($mysqli);
    $mailBody = '';
    $html='';
    $html = '';
    $i = 0;
    $len = sizeof($openShiftsData);
    if(sizeof($openShiftsData)>0) {
        $html = $html.'<style>
                            .center-table{
                                margin-left: auto;
                                margin-right: auto;
                                font-family: Arial, Helvetica, sans-serif;
                                font-size: 8pt;
                                padding: 20px;
                                width: 60%;
                            }
                            .shift-cell-background{
                                background-color: #FFA07A;
                                text-align: center;
                                white-space:nowrap
                            }
                            .shift-status-cell{
                                color: black;
                                text-align: center;
                            }
                            .cellColorMale{
                                background:#9ed6ff;
                            }
                            .cellColorFeMale{
                                background:#ffb8f8;
                            }
                            table, th, td {
                              border: 1px solid black;
                              border-collapse: collapse;
                            }
                            th{
                                text-align: center;
                            }
                            th, td {
                              padding: 15px;
                            }</style><br>
                        <div style="text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 14pt; color: #a65858; font-weight: bold;"><strong>OPEN SHIFTS OF ALL CLIENTS UPTO 14 DAYS</strong></div>
                        <br>
                        <table class="center-table">
                            <thead>
                            <tr>
                                <th>CLIENT</th>
                                <th>DEPARTMENT</th>
                                <th>NAME</th>
                                <th>MOBILE</th>';
        $dateHeader = '';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d').'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 4 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 5 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 6 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 8 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 9 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 10 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 11 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 12 day')).'</th>';
        $dateHeader = $dateHeader.'<th>'.date('Y-m-d', strtotime(date('Y-m-d') . ' + 13 day')).'</th>';
        $dateHeader = $dateHeader.'<th>SHIFT STATUS</th></tr>';
        $html = $html.$dateHeader.'<tbody>';
        foreach ($openShiftsData as $rec){
            $html = $html.'<tr>';
            $html = $html.'<td>'.$rec['client'].'</td>
                               <td>'.$rec['department'].'</td>
                               <td class="';
            if ($rec['gender'] == 'Male') {
                $html = $html.'cellColorMale';
            }elseif ($rec['gender'] == 'Female') {
                $html = $html.'cellColorFeMale';
            }else{
                $html = $html.'';
            }
            $html = $html.'">'.$rec['first_name'].' '.$rec['last_name'].' ('.$rec['candidate_id'].')</td>
                               <td>'.$rec['mobile'].'</td>';
                               if(date('Y-m-d') == $rec['shift_date']){
                                   $html = $html.'<td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 1 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 2 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 3 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 4 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 5 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 6 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 7 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 8 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 9 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 10 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 11 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 12 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td><td></td>';
                               }elseif(date('Y-m-d', strtotime(date('Y-m-d') . ' + 13 day')) == $rec['shift_date']){
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td class="shift-cell-background">'.$rec['shift_start'].' - '.$rec['shift_end'].'</td>';
                               }else{
                                   $html = $html.'<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>';
                               }
                               $html = $html.'<td class="shift-status-cell">'.$rec['shift_status'].'</td>
                               </tr>';
        }
        $html = $html.'</tbody></table>';
        $mailBody = $mailBody.$html;
    }else{
        $mailBody = $mailBody.'No records';
    }
    generateMailNotification('Open Shift Notifier','', 'outapay@outapay.com', $mailBody);
}catch (Exception $e){
   $e->getMessage();
}