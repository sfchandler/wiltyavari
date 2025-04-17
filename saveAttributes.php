<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$otherLicenceId = $_REQUEST['otherLicenceId'];
$candidateId = $_REQUEST['candidateId'];
if(!empty($candidateId)){
	if(updateCandidateOtherLicence($mysqli,$candidateId,$otherLicenceId)){
		echo getOtherLicenceTypesByCandidate($mysqli,$candidateId);
	}else{
		echo getOtherLicenceTypesByCandidate($mysqli,$candidateId);
	}
}else{
	echo 'No data to display';
}
?>