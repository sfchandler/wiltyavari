<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$otherLicenceId = $_REQUEST['oid'];
$candidateId = $_REQUEST['cid'];
echo removeCandidateOtherLicence($mysqli,$candidateId, $otherLicenceId);
?>