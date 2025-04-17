<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

return removeClient($mysqli,$_REQUEST['clientId']);

?>