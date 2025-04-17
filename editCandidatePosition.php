<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

return editCandidatePosition($mysqli,$_REQUEST['positionid'],$_REQUEST['position']);

?>