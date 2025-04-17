<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/10/2017
 * Time: 3:30 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$sql = $mysqli->prepare("SELECT id,address FROM shift_address WHERE latitude IS NULL AND longitude IS NULL")or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($id,$address)or die($mysqli->error);
while($sql->fetch()){
    $geoArray = getLatLong($address);
    $latitude = $geoArray['latitude'];
    $longitude = $geoArray['longitude'];
    $formatted_address = $geoArray['formatted_address'];
    $street = $geoArray['street'];
    $city = $geoArray['city'];
    $state = $geoArray['state'];
    $sub = $geoArray['suburb'];
    $country = $geoArray['country'];
    $postCode = $geoArray['postalCode'];
    //echo $id.'>>>>'.$latitude.$longitude.$formatted_address.'>>>>>>>>>>'.$street.$city.'<br>';
    if(!empty($latitude) && !empty($longitude)&&!empty($formatted_address)&&!empty($id)){
        updateGeoCodes($mysqli,$id,$formatted_address,$street,$city,$state,$sub,$country,$postCode,$latitude,$longitude);
    }
}
?>