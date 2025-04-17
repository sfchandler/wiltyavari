<?php

require_once '../oauth2/credentials.php';

// build endpoint
$base_url = 'https://id.jobadder.com/connect/token';
$version = 'v1/';
$token_endpoint = 'oauth2/token';
$token_url = $base_url;//$version.$token_endpoint

// extract code from the authorization response
$code = $_GET('code');

// build authentication request
$curl = curl_init($token_url);
$curl_post_data = array(
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'grant_type' => 'authorization_code',
    'code'=> $code,
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

echo nl2br("Access token: " . $access_token . "Refresh token ".$refresh_token."\r\n");

//query for vendor key 33 in my demo company
/*$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer " . $access_token
    ],
    CURLOPT_URL => $base_url.$version."objects/accounts-payable/vendor/33",
    CURLOPT_RETURNTRANSFER => true
]);
$curl_response = curl_exec($curl);
if ($curl_response === false) {
    $info = curl_getinfo($curl);
    curl_close($curl);
    die('Error occurred during curl exec. Additional info: ' . var_export($info));
}
curl_close($curl);

// extract vendor data
$response_data = json_decode($curl_response);
if (property_exists($response_data->{"ia::result"}, "ia::error")) {
    echo "Error occurred: " . $response_data->{"ia::result"}->{"ia::error"}->message;
} else {
    $response_result = $response_data->{"ia::result"};
    echo nl2br("Vendor key: " . $response_result->key . "\r\n");
    echo nl2br("Vendor ID: " . $response_result->id . "\r\n");
    echo nl2br("Vendor name: " . $response_result->name . "\r\n\n");
}

// Display the token for use in subsequent tutorials
echo nl2br("Access token: " . $access_token . "\r\n");*/

?>
