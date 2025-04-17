<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
$canId = $_REQUEST['canId'];

if($_REQUEST['action'] == 'GET') {
    $html = $html . '<form name="frmAccCheck" id="frmAccCheck" action="verifyAccounts.php">
            <table class="table" width="300px">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Client</th>
                    <th>Position</th>
                    <th>Action</th>
                    <th>Consultant</th>
                    <th>Timestamp</th>
                    <th>Action</th>
                    <th>Payroll officer</th>
                    <th>Timestamp</th>
                  </tr>
                </thead>
                <tbody>';
    $chklist = getAccountCheckList($mysqli);
   //$docs = getDocumentTypeForAccounts($mysqli);
    foreach ($chklist as $chk) {
        $docTypeCheck = validateAuditCheckType($mysqli,$canId,$chk['id']);
        $consultant = getAuditCheckConsultant($mysqli,$canId,$chk['id']);
        $chkTime = getAuditCheckTime($mysqli,$canId,$chk['id']);
        $docTypeCheckPayroll = validatePayrollAuditCheckType($mysqli,$canId,$chk['id']);
        $payrollOfficer = getAuditCheckPayrollOfficer($mysqli,$canId,$chk['id']);
        $verifiedTime = getPayrollAuditCheckTime($mysqli,$canId,$chk['id']);
        $docPath = getCandidateDocumentByDocTypeId($mysqli,$canId,$chk['doc_type_id']);
        $docType = getCandidateDocumentTypeByDocTypeId($mysqli,$chk['doc_type_id']);
        $html = $html . '<tr class="ref' . $chk['id'] . '">
                    <td><label for="' . $chk['id'] . '">';
        if(!empty($docPath)) {
            $html = $html.'<a href="' . $docPath . '" target="_blank">' . $chk['description'] . '</a>';
        }else{
            $html = $html.$chk['description'];
        }
        $html = $html .'</label></td>
                    <td></td>
                    <td></td>
                    <td>';
        if(($chk['id'] != 19)) {
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
        }else{
            $html = $html . '</td>
                    <td></td>
                    <td></td>
                    <td>';
        }
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
            if($chk['id'] == 19) {
                $html = $html . '<input name="' . $chk['id'].'-P'. '" type="radio" value="Complete" class="accRadio"';
                if($docTypeCheckPayroll == 'Complete'){
                    $html = $html.' checked';
                }
                $html = $html . '>Complete';
            }
        }
        $html = $html.'</td>
                    <td>'.$payrollOfficer.'</td>
                    <td>'.$verifiedTime.'</td>
                  </tr>';
    }
    $html = $html . '<tr>
                    <td>Job Order </td>
                    <td><select class="form-control accClientId" name="accClientId" id="accClientId"></select></td>
                    <td><select class="form-control accPositionId" name="accPositionId" id="accPositionId"></select></td>
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
        $auditStatus = getAuditStatus($mysqli, $canId);
        $html = $html . '<button type="submit" class="btn btn-info" name="accAuditBtn" id="accAuditBtn" value="'.$auditStatus.'">'.$auditStatus.'</button><div class="auditedPerson"></div>';
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
    echo updateAuditCheckList($mysqli,$canId,$_SESSION['userSession'],$chkTypeName,$chkTypeSelection);
}elseif ($_REQUEST['action'] == 'PAYROLL'){
    $chkTypeSelection = $_REQUEST['chkTypeSelection'];
    $chkTypeName = $_REQUEST['chkTypeName'];
    $chkTypeNamePart = explode('-',$chkTypeName);
    $chkTypeNamePayroll = $chkTypeNamePart[0];
    echo updateAuditCheckPayroll($mysqli, $canId, $_SESSION['userSession'], $chkTypeNamePayroll, $chkTypeSelection,$_REQUEST['clientid'],$_REQUEST['positionid']);
}elseif ($_REQUEST['action'] == 'MAIL'){
    $client = getClientNameByClientId($mysqli,$_REQUEST['clientid']);
    $position = getCandidatePositionNameById($mysqli,$_REQUEST['positionid']);
    $canId = $_REQUEST['canId'];
    $casualName = getCandidateFullName($mysqli,$canId);
    $jobOrderNotify = $_REQUEST['jobOrderNotify'];
    updateAuditMailByConsultant($mysqli,$canId,$_REQUEST['clientid'],$_REQUEST['positionid'],$jobOrderNotify);
    updateClientAuditMailByConsultant($mysqli,$clientId,$jobOrderNotify);
    $consultantEmail = getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession']));
    echo generateNotification(ACCOUNTS_EMAIL,'',$consultantEmail,DOMAIN_NAME.' - payroll audit check notification -'.$casualName,DEFAULT_EMAIL,DOMAIN_NAME,'<span style="font-family:Arial, Verdana, Geneva, sans-serif; font-size:12pt;">Audit check list submitted by '.$_SESSION['userSession'].' for candidate ('.$canId.') in order to be allocated to '.$client .' with position '.$position.' </span><br><br>URL for web login: <a href="'.DOMAIN_URL.'" target="_blank">'.DOMAIN_URL.'</a>','','');
    //echo generateAuditCheckNotification($canId,$casualName,$_SESSION['userSession'],'ChandlerAccounts@chandlerservices.com.au',$client,$position);
}elseif($_REQUEST['action'] == 'CLIENT'){
    echo $clientSelected = getClientAllocatedOnAudit($mysqli,$canId);
}elseif($_REQUEST['action'] == 'POSITION'){
    echo $positionSelected = getPositionAllocatedOnAudit($mysqli,$canId);
}
