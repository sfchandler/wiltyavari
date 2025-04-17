<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo removeAssignedPosition($mysqli, $_REQUEST['positionid'],$_REQUEST['candidateId']);
?>