<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$rCanId = $_REQUEST['rCanId'];
$clid = $_REQUEST['clid'];
$stid = $_REQUEST['stid'];
$did = $_REQUEST['did'];
$strdate = $_REQUEST['strdate'];
$enddate = $_REQUEST['enddate'];
$consultant = $_REQUEST['consultant'];
echo getShiftId($mysqli,$rCanId,$consultant,$clid,$stid,$did,$strdate,$enddate);

?>