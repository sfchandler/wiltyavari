<?php
require_once("includes/functions.php");
if($_REQUEST['action'] == 'WHOLESALE') {
    $HOST = 'https://app.wholesalesms.com.au/api/v2/get-balance.json';
    $API_KEY = '';
    $API_SECRET = '';
    $HEADERS = array('Content-Type: application/x-www-form-urlencoded', 'Authorization: Basic ' . base64_encode($API_KEY . ':' . $API_SECRET));

    $process = curl_init($HOST);
    curl_setopt($process, CURLOPT_POST, true);
    curl_setopt($process, CURLOPT_HTTPHEADER, $HEADERS);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    $output = curl_exec($process);
    curl_close($process);
    $rsData = json_decode($output, true);
    echo '<span class="creditBalance">'.$rsData['balance'].'</span>';
}elseif ($_REQUEST['action'] == 'CELLCAST'){
    try {
        $HOST = 'https://cellcast.com.au/api/v3/account';
        $HEADERS = array('APPKEY:' . CELL_CAST_APP_KEY,
            'Accept: application/json',
            'Content-Type: application/json',);
        $process = curl_init();
        curl_setopt($process, CURLOPT_HTTPHEADER, $HEADERS);
        curl_setopt($process, CURLOPT_HEADER, false);
        curl_setopt($process, CURLOPT_URL, $HOST);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
        if (!$output = curl_exec($process)){
            $response_error = json_decode(curl_error($process));
            $msg = json_encode(array("status" => 400, "msg" => "Something went to wrong, please try again", "result" => $response_error));
        }else{
            $rsData = json_decode($output, true);
            $smsCreditBalance = $rsData['data']['sms_balance'];
            $mmsCreditBalance = $rsData['data']['mms_balance'];
            $balanceString = 'SMS : <span class="creditBalance">'.$smsCreditBalance.' </span>  MMS : <span class="creditBalance">'.$mmsCreditBalance.'</span>';
            echo $balanceString;
            $msg = json_encode(array("status" => 200, "msg" => "balance check successful", "result" => json_decode($output)));
        }
        curl_close($process);
    }catch (Exception $e){
        echo $e->getMessage();
    }
}
?>