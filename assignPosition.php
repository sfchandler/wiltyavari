<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo assignPositionEmpoloyee($mysqli,$_REQUEST['candidateId'],$_REQUEST['positionid']);
?>