<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

	$stmt = $mysqli->prepare("SELECT 
								  otherLicenceId,
								  otherLicenceType
								FROM
								  otherlicence");
	$stmt->execute();
	$stmt->bind_result($otherLicenceId,$otherLicenceType);
	$attributeArray = array();
	while($stmt->fetch()){
		$row = array("value"=>$otherLicenceType, "id"=>strval($otherLicenceId));
		$attributeArray[] = $row;		
	}
	echo json_encode($attributeArray);
?>