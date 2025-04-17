<?php
	require_once("includes/db_conn.php");
	require_once("includes/functions.php");
/*	if($_REQUEST['action'] == 'scheduling'){
        echo getClientDepartments($mysqli);
    }else {
        echo getClientDepartments($mysqli);
    }*/
echo getClientDepartments($mysqli);
?>