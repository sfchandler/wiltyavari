<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 15/12/2017
 * Time: 10:03 AM
 */
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
ini_set('memory_limit', '3072M');
date_default_timezone_set('Australia/Melbourne');
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";


$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$consultants = $_POST['consultantId'];
$activities = $_POST['actId'];
//$selectedConsultants = $_REQUEST['consultantId'];
$dataSet = getConsultantNotesKPI($mysqli,$startDate,$endDate,$consultants,$activities);
$actArray[] = getActivityCount($mysqli);
if(empty($dataSet)) {
    $msg = base64_encode('No Results to be displayed');
    header("Location: kpiReport.php?msg=$msg");
}
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->setHeaderTemplateAutoreset(true);
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('');
$pdf->SetTitle('KPI Report');
$pdf->SetSubject('KPI Report');
$pdf->SetKeywords('KPI Report');
define('PDF_CUSTOM_HEADER_STRING','                          User:'.$_SESSION['userSession'].'               Printed: '.date("Y-m-d H:i:s"));
// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_CUSTOM_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();

$html = $html.'<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
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
</style>';
$actArray = getActivityCount($mysqli);
if(!empty($dataSet)) {
    $html = $html.'<div class="pageTitle">KPI Report</div><table border="1">
    <thead>
        <tr>
            <th style="text-align: center;text-transform: uppercase;">Consultant</th>
            <th style="text-align: center;text-transform: uppercase;">Activities</th>
            <th style="text-align: center;text-transform: uppercase;">No.Actioned</th>
        </tr>    
    </thead>
    <tbody>';
    $rowCount = 2;
    $consId = 0;
    $noRecords = 0;
    $activityId = 0;
    $activity = '';
    $activityCount = 0;
    $consultant = '';
    $actTypes = array();
    $k = 0;
    $len = count($dataSet);
    foreach ($dataSet as $data) {
        foreach ($actArray as $key => $value) {
            if($key == $data['activityId']){
                $actArray[$key]['actCount'] = $value['actCount'] + 1;
            }
        }
        if ($i == 0) {
            // first iteration
        }
        if(empty($consId)){
            $consId = $data['consultantId'];
            $consultant = $data['consultantName'];
        }
        if(empty($activityId)){
            $activityId = $data['activityId'];
            $activity = $data['activityType'];
        }

        if($consId != $data['consultantId']){
            $rowCount = $rowCount+1;
            if($activityId != $data['activityId']){
                $html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td>'.$consultant.'</td><td>'.$activity.'</td><td>'.$activityCount.'</td></tr>';
                $activityId = $data['activityId'];
                $activity = $data['activityType'];

                $consultant = $data['consultantName'];
                $activityCount = 1;
            }else{
                if($consId != $data['consultantId']){
                    $consId = $data['consultantId'];
                    //$html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td>'.$consultant.'</td><td>'.$activity.'</td><td>'.$activityCount.'</td></tr>';
                    $activityCount++;
                }else{
                    $activityCount++;
                }
            }

        }else{
            if($activityId != $data['activityId']){
                $html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td>'.$consultant.'</td><td>'.$activity.'</td><td>'.$activityCount.'</td></tr>';
                $activityId = $data['activityId'];
                $activity = $data['activityType'];

                $consultant = $data['consultantName'];
                $activityCount = 1;
            }else{
                if($consId != $data['consultantId']){
                    $consId = $data['consultantId'];
                    //$html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td>'.$consultant.'</td><td>'.$activity.'</td><td>'.$activityCount.'</td></tr>';
                }else{
                    $activityCount++;
                }
            }
        }
        if ($k == $len - 1) {
            $consultant = $data['consultantName'];
            $html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td>'.$consultant.'</td><td>'.$activity.'</td><td>'.$activityCount.'</td></tr>';
            if($consId != $data['consultantId']){
                $consId = $data['consultantId'];
                if($activityId == $data['activityId']) {
                }
            }else{
            }
        }
        $k++;
        /*$rowCount++;
        $noRecords++;
        if(empty($consId)) {
            $consId = $data['consultantId'];
            $consultant = $data['consultantName'];
        }
        if(empty($activityId)) {
            $activityId = $data['activityId'];
            $activity = $data['activityType'];
        }
        if($consId != $data['consultantId']){
            $rowCount = $rowCount+1;
            $consId = $data['consultantId'];
            $consultant = $data['consultantName'];
            $noRecords = 0;
            $activityId = 0;
            $activityCount = 0;
            $html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td colspan="3"><hr></td></tr>';
        }
        if($activityId != $data['activityId']){
            $html = $html.'<tr nobr="true" class="zebra'.($i++ & 1).'"><td>'.$consultant.'</td><td>'.$activity.'</td>';
            if($activityCount == 0){
                $activityCount = 1;
            }
            $html = $html.'<td>'.$activityCount.'</td></tr>';
            $activityId = $data['activityId'];
            $activity = $data['activityType'];
            $actTypes[$activity] += $activityCount;
            $activityCount = 1;
        }else{
            $activityCount++;
        }*/
    }
    $html = $html.'</tbody></table>';

    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->AddPage('P', 'A4');
    $sum = $sum.'<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
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
</style>';
    $sum = $sum.'<div class="pageTitle">Totals for Activities Actioned</div><table align="center" border="1" style="width: 60%"><thead><tr>
            <th style="text-align: center;text-transform: uppercase;">Activity</th>
            <th style="text-align: center;text-transform: uppercase;">Total No. Actioned</th>
        </tr></thead><tbody>';
    foreach ($actArray as $key=>$value){
        if($key <> null) {
            if(!empty($actArray[$key]['actCount'])) {
                $sum = $sum . '<tr nobr="true" class="zebra' . ($i++ & 1) . '"><td style="text-align: left">' . $actArray[$key]['activityType'] . '</td><td style="text-align: right">' . $actArray[$key]['actCount'] . '</td></tr>';
            }
        }
    }
    $sum = $sum.'</tbody></table>';

    $pdf->writeHTML($sum, true, false, false, false, 'L');
    // reset pointer to the last page
    $pdf->lastPage();
    // Close and output PDF document
    $pdf->Output('KPIReport.pdf','D');
}else{


}

?>