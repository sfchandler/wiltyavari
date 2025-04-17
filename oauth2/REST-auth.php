<?php
session_start();
require_once '../oauth2/credentials.php';

// build endpoint
$base_url = 'https://id.jobadder.com/connect/authorize';
$version = 'v1/';
$auth_endpoint = 'oauth2/authorize';
$auth_url = $base_url;//$version.$auth_endpoint
try {
    $bytes = random_bytes(10);
    $state = bin2hex($bytes);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
$url = $auth_url."?"
    ."state=".$state
    ."&response_type=code"
    ."&client_id=". CLIENT_ID
    ."&scope=offline_access read_candidate read_contact read_placement read_company"
    ."&redirect_uri=". CALLBACK_URL;

?>
<a href="<?php echo $url; ?>">Authorize application</a>
