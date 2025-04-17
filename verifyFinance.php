<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
$canId = $_REQUEST['canId'];
if($_REQUEST['action'] == 'GET') {
    $html = $html . '<form name="frmAccCheck" id="frmAccCheck" action="verifyFinance.php">
            <table class="table" width="300px">
                <thead>
                  <tr>
                    <th>Type</th>
                    <th>Action</th>
                    <th>Accounts User</th>
                    <th>Timestamp</th>
                  </tr>
                </thead>
                <tbody>';
    $chklist = getFinanceCheckList($mysqli);
    foreach ($chklist as $chk) {
        $docTypeCheck = validatefinanceCheckType($mysqli,$canId,$chk['id']);
        $accountsUser = getFinanceCheckUser($mysqli,$canId,$chk['id']);
        $chkTime = getFinanceCheckTime($mysqli,$canId,$chk['id']);
        $html = $html . '<tr>
                    <td><label for="'. $chk['id'].'">'.$chk['description'].'</label></td>
                    <td>';
            $html = $html.'<input name="'.$chk['id'].'" type="radio" value="Yes" class="financeRadio"';
            if ($docTypeCheck == 'Yes') {
                $html = $html.' checked';
            }
            $html = $html.'>Yes<input name="'.$chk['id'].'" type="radio" value="No" class="financeRadio"';
            if ($docTypeCheck == 'No') {
                $html = $html.' checked';
            }
            $html = $html.'>No</td>
                    <td>'.$accountsUser.'</td>
                    <td>'.$chkTime.'</td>
                    </tr>';
    }
    $html = $html . '</tbody>
              </table>
        </form>';
    echo $html;
}elseif ($_REQUEST['action'] == 'UPDATE'){
    $chkTypeSelection = $_REQUEST['chkTypeSelection'];
    $chkTypeName = $_REQUEST['chkTypeName'];
    echo updateFinanceCheckList($mysqli,$canId,$_SESSION['userSession'],$chkTypeName,$chkTypeSelection);
}
