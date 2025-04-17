<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 9/05/2019
 * Time: 3:33 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['visaTypeId'] <> 'None' || $_REQUEST['candidateId'] <> ''){
    echo assignVisaTypeToCandidate($mysqli, $_REQUEST['candidateId'], $_REQUEST['visaTypeId']);
}else{
    echo 'NONE';
}