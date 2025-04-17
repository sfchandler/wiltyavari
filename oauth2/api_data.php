<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
$refresh_token = $_SESSION['refresh_token'];
$access_token = $_SESSION['access_token'];
$base_url ='https://api.jobadder.com/v2/';
$query_url = 'placements';
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
try {
    //query for data
   /* $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer " . $access_token
        ],
        CURLOPT_URL => $base_url . $query_url,
        CURLOPT_RETURNTRANSFER => true
    ]);
    $curl_response = curl_exec($curl);
    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('Error occurred during curl exec. Additional info: ' . var_export($info));
    }
    curl_close($curl);*/
    // extract  data
    $response_data = jobAdderConnect($access_token,$base_url,$query_url);

    echo '<hr>';
    foreach ($response_data->items as $item) {
        echo '<a href="get_placement.php?placement_id='.$item->placementId.'" target="_blank">'.$item->placementId.'</a><br>';
        echo $item->job->jobId."<br>";
        echo $item->job->jobTitle."<br>";
        echo $item->candidate->candidateId."<br>";
        echo $item->candidate->firstName."<br>";
        echo $item->candidate->lastName."<br>";
        echo $item->candidate->email."<br>";
        echo $item->candidate->mobile."<br>";
    }
}catch (Exception $e) {
    echo $e->getMessage();
}
