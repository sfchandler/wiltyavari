<?php
    function sendSMS($content) {
        $ch = curl_init('https://api.smsbroadcast.com.au/api-adv.php');
		curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec ($ch);
        curl_close ($ch);
        return $output;    
    }

    $username = 'chandler';
    $password = 'chandler727';
    $destination = '0484012477'; //Multiple numbers can be entered, separated by a comma
    $source = '0488863255';//Not more than 11 characters
    $text = 'SMS SMSBROADCASTING>>>>>>>>>>>';
    $ref = uniqid();
        
    $content =  'username='.rawurlencode($username).
                '&password='.rawurlencode($password).
                '&to='.rawurlencode($destination).
                '&from='.rawurlencode($source).
                '&message='.rawurlencode($text).
                '&ref='.rawurlencode($ref);
 
    $smsbroadcast_response = sendSMS($content);
	$response_lines = explode("\n", $smsbroadcast_response);
    
     foreach($response_lines as $data_line){
        $message_data = "";
        $message_data = explode(':',$data_line);
        if($message_data[0] == "OK"){
            echo "The message to ".$message_data[1]." was successful, with reference ".$message_data[2]."\n";
			var_dump($response_lines);
        }elseif( $message_data[0] == "BAD" ){
            echo "The message to ".$message_data[1]." was NOT successful. Reason: ".$message_data[2]."\n";
        }elseif( $message_data[0] == "ERROR" ){
            echo "There was an error with this request. Reason: ".$message_data[1]."\n";
        }
    }
?>