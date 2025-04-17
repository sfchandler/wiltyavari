<?php
include 'includes/functions.php';
try {
    $HOST = 'https://cellcast.com.au/api/v3/account';
    $API_KEY = CELL_CAST_APP_KEY;
    $headers = array(
        'APPKEY: '.CELL_CAST_APP_KEY,
        'Accept: application/json',
        'Content-Type: application/json',
    );
    $HEADERS = array('APPKEY:'.$API_KEY, 'Accept: application/json',
        'Content-Type: application/json',);
    $process = curl_init($HOST);
    curl_setopt($process, CURLOPT_POST, true);
    curl_setopt($process, CURLOPT_HTTPHEADER, $HEADERS);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($process, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    $output = curl_exec($process);
    curl_close($process);
    $rsData = json_decode($output, true);
    $smsCreditBalance = $rsData['data']['sms_balance'];
    $mmsCreditBalance = $rsData['data']['mms_balance'];
    $balanceString = 'SMS : <span class="creditBalance">'.$smsCreditBalance.' </span>  MMS : <span class="creditBalance">'.$mmsCreditBalance.'</span>';
    if (intval($smsCreditBalance) < 1000) {
        generateNotification('outapay@outapay.com', 'outapay@outapay.com', ' ', 'CELLCAST SMS CREDIT BALANCE LOW', DEFAULT_EMAIL, DOMAIN_NAME, '<div style="font-size: 16pt"><br><br><img src="'.DOMAIN_URL.'/img/logo.png" width="220" alt=""><br><br><span style="color: red;"><b>CELLCAST SMS CREDIT BALANCE IS LOW</b></span><br><br>Current SMS Credit Balance is <span style="color: red"><b>' . $smsCreditBalance . '</b></span> Please top up CELLCAST Credit IMMEDIATELY !!</div><br><br><a href="https://www.cellcast.com.au/client/login.php" target="_blank">CELLCAST LOGIN</a>', '', '');
    }
    echo $smsCreditBalance;
}catch (Exception $e){
    echo $e->getMessage();
}