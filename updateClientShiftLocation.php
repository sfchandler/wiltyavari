<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 30/10/2017
 * Time: 1:08 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$stateName = $_REQUEST['stateName'];
$shiftAddress = $_REQUEST['shiftAddress'];
$street = $_REQUEST['street'];
$city = $_REQUEST['city'];
$suburb = $_REQUEST['suburb'];
$country = $_REQUEST['country'];
$latitude = $_REQUEST['latitude'];
$longitude = $_REQUEST['longitude'];
$postalCode = $_REQUEST['postalCode'];
$location_check = $_REQUEST['location_check'];
$id = $_REQUEST['id'];
if(isset($id)){
    echo updateClientShiftLocation($mysqli,$id,$clientId,$stateName,$shiftAddress,$street,$city,$suburb,$country,$postalCode,$latitude,$longitude,$location_check);
}
?>