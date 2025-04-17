<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getStateByClient($mysqli,$_REQUEST['clientId']);

?>