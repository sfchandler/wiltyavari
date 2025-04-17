<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 10/10/2017
 * Time: 10:20 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$centreName = $_REQUEST['centreName'];
if($_REQUEST['action'] == 'GET'){
    echo getProfitCentres($mysqli);
}
if(isset($centreName)&&!empty($centreName)) {
    echo getProfitCentre($mysqli, $centreName);
}
?>