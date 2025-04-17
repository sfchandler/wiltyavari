<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 13/06/2018
 * Time: 4:13 PM
**/
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('memory_limit', '3072M');
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
require_once("../includes/TCPDF-master/clockInConfig/tcpdf_include.php");
date_default_timezone_set('Australia/Melbourne');

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$empSelected = $_REQUEST['empSelected'];
$supervisor = $_SESSION['supervisorId'];
$supervisorCheck = 'Y';
//echo $startDate.$endDate.$empSelected.$supervisor;
$clockInData = getConfirmedClockInData($mysqli,$startDate,$endDate,$empSelected,$supervisor,$supervisorCheck);
if(!empty($clockInData)) {
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    define('PDF_CUSTOM_HEADER_STRING', 'CASUALS CONFIRMED TIMESHEET BY SUPERVISOR              '.date("Y-m-d H:i:s"));
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Chandler');
    $pdf->SetTitle('TimeSheet ClockIn from Report');
    $pdf->SetSubject('TimeSheet ClockIn Report');
    $pdf->SetKeywords('TimeSheet ClockIn Report');
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetFont('helvetica', '', 10);
    $pdf->AddPage();
    $fileName = 'clockinReport_'.time().'.pdf';
    $filePath = '../supervisor/'.$fileName;
    $html = $html.'<style>
                        table {
                            table-layout: fixed;
                            width: 100%;
                            white-space: nowrap;
                            border-collapse: collapse;
                            font-size: 8pt;
                            /*word-wrap:break-word;*/
                            text-transform: uppercase;
                        }
                        td.cellWidth{
                            text-align: right;
                            width: 8%;
                        }
                        td.shortWidth{
                            text-align: right;
                            width: 5%;
                        }
                        td.empId{
                            text-align: left;
                            width:12%;
                        }
                        td.desc{
                            text-align: left;
                            width:25%;
                        }
                        th{
                            font-weight: bold;
                            background-color: #0aa699;
                        }
                        .title{
                            margin-top: 0;
                            padding-top: 0;
                            text-align: left;
                            text-transform: uppercase;
                            font-weight: bold;
                        }
                        .pageTitle{
                            text-align: center;
                            text-transform: uppercase;
                            font-weight: bold;
                            font-size: 11pt;
                        }
                        
                        .zebra0{
                            background-color: #cbd2d5;
                        }
                        .zebra1{
                            background-color: white;
                        }
                        .totalRow{
                            font-weight: bold;
                        }
                        </style>
                    <table>
                        <thead>
                            <tr>
                                <th>Shift ID</th>
                                <th class="empId">Employee ID</th>
                                <th class="empId">Employee Name</th>
                                <th>Shift Date</th>
                                <th>Shift Day</th>
                                <th>Employer</th>
                                <th>Position</th>
                                <th>Check In Time</th>
                                <th>Check Out Time</th>
                                <th>Work Break</th>
                                <th>Work Hours</th>
                                <th>Supervisor</th>
                                <th>Approved Time</th>
                            </tr>    
                        </thead>
                        <tbody>';
    foreach ($clockInData as $data){
        $html = $html.'<tr class="zebra'.($j++ & 1 ).'"><td>'.$data['shiftId'].'</td>';
        $html = $html.'<td>'.$data['candidateId'].'</td>';
        $html = $html.'<td>'.getCandidateFullName($mysqli,$data['candidateId']).'</td>';
        $html = $html.'<td>'.$data['shiftDate'].'</td>';
        $html = $html.'<td>'.$data['shiftDay'].'</td>';
        $html = $html.'<td>'.getClientNameByClientId($mysqli,$data['clientId']).'</td>';
        $html = $html.'<td>'.getPositionByPositionId($mysqli,$data['positionId']).'</td>';
        $html = $html.'<td>'.$data['checkIn'].'</td>';
        $html = $html.'<td>'.$data['checkOut'].'</td>';
        $html = $html.'<td>'.$data['workBreak'].'</td>';
        $html = $html.'<td>'.$data['wrkhrs'].'</td>';
        $html = $html.'<td>'.getSupervisorNameById($mysqli,$data['supervicerId']).'</td>';
        $html = $html.'<td>'.$data['approvalTime'].'</td></tr>';
    }

    $html = $html.'</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__. '/' . $fileName , 'F');
    echo $filePath;
}
