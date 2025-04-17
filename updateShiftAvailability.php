<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/19/2017
 * Time: 5:02 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$canId = $_REQUEST['canId'];
$strDate = $_REQUEST['strDate'];
$endDate = $_REQUEST['endDate'];
$removeStatus = $_REQUEST['removeStatus'];
if($removeStatus == 'remove'){
    echo removeUnAvailblity($mysqli,$canId);
}else {
    echo updateRosterAvailability($mysqli, $canId, $strDate, $endDate);
}
?>