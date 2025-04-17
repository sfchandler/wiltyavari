<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$loggedInUser = $_SESSION['userSession'];
$clientSearch = $mysqli->real_escape_string($_REQUEST['clientSearch']);
echo listDepartments($mysqli,$clientSearch,$loggedInUser);


?>