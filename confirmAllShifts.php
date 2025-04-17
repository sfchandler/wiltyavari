<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/17/2017
 * Time: 2:53 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$canid = $_REQUEST['canid'];
$clid = $_REQUEST['clid'];
$stid = $_REQUEST['stid'];
$did = $_REQUEST['did'];
$strdate = $_REQUEST['strdate'];
$enddate = $_REQUEST['enddate'];
$consultantId = getConsultantId($mysqli,$_REQUEST['consultant']);

echo confirmAllShifts($mysqli,$canid,$clid,$stid,$did,$strdate,$enddate,$consultantId);

?>