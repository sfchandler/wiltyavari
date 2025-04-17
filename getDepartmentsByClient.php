<?php
	require_once("includes/db_conn.php");
	require_once("includes/functions.php");
	
	$clid = $_REQUEST['clid'];
	$stid = $_REQUEST['stid'];
	echo getDepartmentsByClientId($mysqli, $clid, $stid);
	

?>