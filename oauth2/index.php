<?php
session_start();
require_once '../oauth2/credentials.php';
error_reporting(E_ALL);
ini_set('display_errors', true);
// build endpoint
$base_url = 'https://id.jobadder.com/connect/token';
$version = 'v1/';
$token_endpoint = 'oauth2/token';
$token_url = $base_url;//$version.$token_endpoint

// extract code from the authorization response
$code = $_GET['code'];
// build authentication request
$curl = curl_init($token_url);
$curl_post_data = array(
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => CALLBACK_URL,
    'content_type' => 'application/json'
);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post_data));

// send request to authenticate
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('Error occurred during curl exec. Additional info: ' . var_export($info));
}
curl_close($curl);
// decode response and extract access token
$decoded = json_decode($curl_response);
$access_token = $decoded->access_token;
$refresh_token = $decoded->refresh_token;

// refresh token
$curl = curl_init($token_url);
$curl_post_data = array(
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'grant_type' => 'refresh_token',
    'refresh_token' => $refresh_token,
    'content_type' => 'application/json'
);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($curl_post_data));

// send request to authenticate
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('Error occurred during curl exec. Additional info: ' . var_export($info));
}
curl_close($curl);
$_SESSION['refresh_token'] = $refresh_token;
$_SESSION['access_token'] = $access_token;
echo nl2br("Access token: " . $access_token . "Refresh token " . $refresh_token . "\r\n");

header("Location:../jobadder.php");


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Redirect Page</title>
</head>
<body>
<h1>Redirect Page</h1>
</body>
</html>

