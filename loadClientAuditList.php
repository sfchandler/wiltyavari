<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

$clientId =  $_REQUEST['clientId'];
if($_REQUEST['action'] == 'GET') {
    $html = $html . '<form name="frmAccCheck" id="frmAccCheck" action="loadClientAuditList.php">
            <table class="table" width="300px">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th></th>
                    <th></th>
                    <th>Consultant Action</th>
                    <th>Consultant</th>
                    <th>Timestamp</th>
                    <th>Payroll Action</th>
                    <th>Payroll officer</th>
                    <th>Timestamp</th>
                  </tr>
                </thead>
                <tbody>';
    $chklist = getClientAccountCheckList($mysqli);
    foreach ($chklist as $chk) {
        $docTypeCheck = validateClientAuditCheckType($mysqli,$clientId,$chk['id']);
        $consultant = getClientAuditCheckConsultant($mysqli,$clientId,$chk['id']);
        $chkTime = getClientAuditCheckTime($mysqli,$clientId,$chk['id']);
        $docTypeCheckPayroll = validateClientPayrollAuditCheckType($mysqli,$clientId,$chk['id']);
        $payrollOfficer = getClientAuditCheckPayrollOfficer($mysqli,$clientId,$chk['id']);
        $verifiedTime = getClientPayrollAuditCheckTime($mysqli,$clientId,$chk['id']);
        $html = $html . '<tr>
                    <td><label for="' . $chk['id'] . '">' . $chk['description'].'</label></td>
                    <td></td>
                    <td></td>
                    <td>';
            $html = $html . '<input name="' . $chk['id'] . '" type="radio" value="Yes" class="consRadio"';
            if ($docTypeCheck == 'Yes') {
                $html = $html . ' checked';
            }
            $html = $html . '>Yes<input name="' . $chk['id'] . '" type="radio" value="No" class="consRadio"';
            if ($docTypeCheck == 'No') {
                $html = $html . ' checked';
            }
            $html = $html . '>No</td>
                    <td>' . $consultant . '</td>
                    <td>' . $chkTime . '</td>
                    <td>';
        if($_SESSION['userType'] =='ACCOUNTS') {
            $html = $html . '<input name="' . $chk['id'].'-P'. '" type="radio" value="Yes" class="accRadio"';
            if($docTypeCheckPayroll == 'Yes'){
                $html = $html.' checked';
            }
            $html = $html . '>Yes<input name="' . $chk['id'].'-P' . '" type="radio" value="No" class="accRadio"';
            if($docTypeCheckPayroll == 'No'){
                $html = $html.' checked';
            }
            $html = $html . '>No';
        }
        $html = $html.'</td>
                    <td>'.$payrollOfficer.'</td>
                    <td>'.$verifiedTime.'</td>
                  </tr>';
    }
    $html = $html . '<tr>
                    <td>Job Order </td>
                    <td></td>
                    <td></td>
                    <td>';
    if($_SESSION['userType'] =='CONSULTANT') {
        $html = $html . 'I have selected all necessary checks &nbsp;<input type="checkbox" name="jobOrderNotify" id="jobOrderNotify" value="Notify">';
    }
    $html = $html . '</td>
                    <td colspan="5"></td></tr>';
    $html = $html . '<tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>';
    if($_SESSION['userType'] =='CONSULTANT') {
        $html = $html.'<button type="submit" class="btn btn-info" name="consAuditBtn" id="consAuditBtn">Notify to Audit</button>';
    }
    $html = $html.'</td>
                    <td></td>
                    <td>';
    if($_SESSION['userType'] =='ACCOUNTS') {
        $auditStatus = getClientAuditStatus($mysqli, $clientId);
        $clientStatus = getClientStatus($mysqli,$clientId);
        $html = $html.'<button name="activateBtn" id="activateBtn" type="button" class="activateBtn btn btn-info" value="'.$clientStatus.'">'.$clientStatus.'</button>&nbsp;&nbsp;&nbsp;';
        $html = $html.'<button type="submit" class="btn btn-info" name="accAuditBtn" id="accAuditBtn" value="'.$auditStatus.'">'.$auditStatus.'</button><div class="auditedPerson"></div>';
    }
    $html = $html.'</td>
                <td></td>
                <td></td></tr>';
    $html = $html . '</tbody>
              </table>
        </form>';
    echo $html;
}elseif ($_REQUEST['action'] == 'UPDATE'){
    $chkTypeSelection = $_REQUEST['chkTypeSelection'];
    $chkTypeName = $_REQUEST['chkTypeName'];
    echo updateClientAuditCheckList($mysqli,$clientId,$_SESSION['userSession'],$chkTypeName,$chkTypeSelection);
}elseif ($_REQUEST['action'] == 'PAYROLL'){
    $chkTypeSelection = $_REQUEST['chkTypeSelection'];
    $chkTypeName = $_REQUEST['chkTypeName'];
    $chkTypeNamePart = explode('-',$chkTypeName);
    $chkTypeNamePayroll = $chkTypeNamePart[0];
    echo updateClientAuditCheckPayroll($mysqli, $clientId, $_SESSION['userSession'], $chkTypeNamePayroll, $chkTypeSelection);
}elseif ($_REQUEST['action'] == 'MAIL'){
    $client = getClientNameByClientId($mysqli,$clientId);
    $jobOrderNotify = $_REQUEST['jobOrderNotify'];
    updateClientAuditMailByConsultant($mysqli,$clientId,$jobOrderNotify);
    $consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession']));
    echo generateNotification(ACCOUNTS_EMAIL,'',$consultantEmail,DOMAIN_NAME.' - payroll client audit check notification -'.$client,DEFAULT_EMAIL,DOMAIN_NAME,'<span style="font-family:Arial, Verdana, Geneva, sans-serif; font-size:12pt;">Client Audit check list submitted by '.$_SESSION['userSession'].' for '.$client.' </span><br><br>URL for web login: <a href="'.DOMAIN_URL.'" target="_blank">'.DOMAIN_URL.'</a>','','');
    //echo generateClientAuditCheckNotification($_SESSION['userSession'],'ChandlerAccounts@chandlerservices.com.au',$client);
}