<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 31/07/2018
 * Time: 9:24 AM
 */

//API Url
$url = 'http://localhost/chandler/remote/shift/check_shifts.php';

//Initiate cURL.
$ch = curl_init($url);

//The JSON data.
$jsonData = array(
    'clockPin' => '285581'
);

//Encode the array into JSON.
$jsonDataEncoded = json_encode($jsonData);

//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);

//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);

//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));


//Execute the request
$result = curl_exec($ch);