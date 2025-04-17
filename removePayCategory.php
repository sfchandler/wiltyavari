<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$payCatCode = $_REQUEST['payCatCode'];
echo removePayCategory($mysqli,$payCatCode)

?>