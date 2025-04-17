<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$clientId = $_REQUEST['client_id'];
$positionId = $_REQUEST['position_id'];

echo getRecruitmentJobDescriptionByClientPosition($mysqli,$clientId,$positionId);