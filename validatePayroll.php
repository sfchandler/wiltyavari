<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
date_default_timezone_set('Australia/Melbourne');
ini_set('max_execution_time', 0);

$weekendingDate = $_POST['weekEndingDate'];
$timeSheetData = getTimeSheetDataForPayrollValidation($mysqli,$weekendingDate);
$table = '<div style="width:100%; height:100%; overflow-y: scroll; height: 500px;"><table cellpadding="5" cellspacing="5" border="1" class="table table-striped table-bordered table-hover"><thead><th>Employee ID</th><th>TFN</th><th>Taxcode</th><th>Super Member No</th><th>Super Fund Check</th><th>Internal Police Clearance</th><th>External Police Clearance</th><th>External Police Check Receipt</th><th>Bank Account Details</th></thead>';
foreach ($timeSheetData as $data){
    $bank_table = '<table cellpadding="5" cellspacing="5" border="1" class="table table-striped table-bordered table-hover"><thead><th>Account Name</th><th>Account Number</th><th>BSB</th></thead>';
    $rows = getEmployeeBankAccountRows($mysqli,$data['candidateId']);
    if(empty($rows)){
        $rows = '<tr><td colspan="4" style="background-color: rosybrown">Bank Account details not found</td></tr>';
    }
    $btable = $bank_table.$rows.'</table>';
    if($data['internalPoliceCheckStatus'] == 'No'){
        $css = 'notifyRed';
    }else if($data['internalPoliceCheckStatus'] == 'Yes'){
        $css = 'notifyGreen';
    }else{
        $css = '';
    }
    if($data['externalPoliceCheckStatus'] == 'No'){
        $cssex = 'notifyRed';
    }else if($data['externalPoliceCheckStatus'] == 'Yes'){
        $cssex = 'notifyGreen';
    }else{
        $cssex = '';
    }
    if($data['externalPoliceCheckReceiptStatus'] == 'No'){
        $cssexr = 'notifyRed';
    }else if($data['externalPoliceCheckReceiptStatus'] == 'Yes'){
        $cssexr = 'notifyGreen';
    }else{
        $cssexr = '';
    }
    $row = $row.'<tr><td>'.$data['candidateId'].'</td><td>'.$data['tfn'].'</td><td>'.$data['taxcode'].'</td><td>'.$data['superMemberNo'].'</td><td>'.$data['superFundCheck'].'</td><td class="'.$css.'" style="text-align:center">'.$data['internalPoliceCheckStatus'].'</td><td class="'.$cssex.'" style="text-align:center">'.$data['externalPoliceCheckStatus'].'</td><td class="'.$cssexr.'" style="text-align:center">'.$data['externalPoliceCheckReceiptStatus'].'</td><td>'.$btable.'</td></tr>';
}
$html = $table.$row.'</table></div>';
echo $html;