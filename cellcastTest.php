<?php

define('CELL_CAST_APP_KEY', openssl_decrypt('YyDFIihjf6asKE86Iw3pOZiFxcfgPhg7eq6U2yjMsJkzvtsgN8LYxlblSZXlIE2Mc18M2eSMoOv8tF7foRdZmTU7u37yX86WjOLzUYkX33L0sYXAUcxIRPqQJ5KXbdwf6cCqWbDAWTCSghUhDdCF4mRf3IZhomnS3sApqZgCV8GvtwYi68BjpHWON544lVTGsccCyN9K996jfy5bh8VaI+52cduYU4lZXBw4U51ycy2ljLhUxNI+U9b/moCesSVMDleLuwANT9ACDvPFjmM5hJRhLWYbm98AqJjxCCf0bjzjEd7nE2WpKQ==', "AES-128-CTR", "Ou#0209TsndSaen^ndsHdfWy"));

try {
        $HOST = 'https://api.cellcast.com/api/v1/apiClient/account';
        $HEADERS = array('Authorization: Bearer '.CELL_CAST_APP_KEY,
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
            echo $output;
            $rsData = json_decode($output, true);
            $smsCreditBalance = $rsData['data']['sms_balance'];
            $mmsCreditBalance = $rsData['data']['mms_balance'];
            $balanceString = 'SMS : <span class="creditBalance">'.$smsCreditBalance.' </span>  MMS : <span class="creditBalance">'.$mmsCreditBalance.'</span>';
            $balanceString;
            $msg = json_encode(array("status" => 200, "msg" => "balance check successful", "result" => json_decode($output)));
        }
        curl_close($process);
    }catch (Exception $e){
        echo $e->getMessage();
    }