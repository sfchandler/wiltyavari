<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 19/10/2017
 * Time: 10:16 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$status = $_REQUEST['status'];
$candidateId = $_REQUEST['candidateId'];
$jobCode = $_REQUEST['jobCode'];
if($status == 'NAME'){
    echo getCandidateFirstNameByCandidateId($mysqli, $candidateId);
}else if($status == 'JOBCODE'){
    echo getPositionByPositionId($mysqli,getPositionIdByJobCode($mysqli,$jobCode));
}

?>