<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 14/09/2017
 * Time: 4:48 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_REQUEST['candidateId']) && isset($_REQUEST['weekendingDate']) && ($_REQUEST['status'] == 'display')){
    echo listEmployeeDeductionCodes($mysqli, $_REQUEST['candidateId'],$_REQUEST['weekendingDate']);
}else if(isset($_REQUEST['candidateId']) && isset($_REQUEST['transCode']) && isset($_REQUEST['weekendingDate']) && ($_REQUEST['status'] == 'add')){
    echo addDeductionCodes($mysqli,$_REQUEST['candidateId'],$_REQUEST['transCode'],$_REQUEST['weekendingDate']);
}else if(isset($_REQUEST['candidateId']) && isset($_REQUEST['weekendingDate']) && isset($_REQUEST['did'])&& ($_REQUEST['status'] == 'delete')){
    echo removeEmployeeDeductionCode($mysqli,$_REQUEST['candidateId'], $_REQUEST['weekendingDate'],$_REQUEST['did']);
}

?>