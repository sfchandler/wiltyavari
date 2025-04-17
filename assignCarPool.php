<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 23/07/2018
 * Time: 5:19 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['carPoolId'] <> 'None' || $_REQUEST['candidateId'] <> ''){
    echo assignCarPoolToCandidate($mysqli, $_REQUEST['candidateId'], $_REQUEST['carPoolId']);
}else{
    echo 'NONE';
}