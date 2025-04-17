<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$payCategory = $_REQUEST['payCategory'];

echo addPayCategory($mysqli,$payCategory);
?>