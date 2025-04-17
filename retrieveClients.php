<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$loggedInUser = $_SESSION['userType'];
echo retrieveClients($mysqli,$loggedInUser);
	
?>