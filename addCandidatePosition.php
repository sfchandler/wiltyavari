<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_REQUEST['position'])){
    $position = strtoupper($_REQUEST['position']);
	if(validateExistingCandidatePosition($mysqli,$position)){
		echo 'exists';
	}else{
		echo addCandidatePosition($mysqli,$position);
	}
}
?>