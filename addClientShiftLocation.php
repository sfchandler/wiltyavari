<?php

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
if(isset($clientId)&&isset($stateName)&&isset($shiftAddress)&&isset($street)&&isset($city)&&isset($suburb)&&isset($country)&&(isset($location_check))){
    echo addClientShiftLocation($mysqli,$clientId,$stateName,$shiftAddress,$street,$city,$suburb,$country,$postalCode,$latitude,$longitude,$location_check);
}
