<?php

include ("includes/textmagic-sms-api-php/TextMagicAPI.php");
try{
	$messageId = 123310337; // for the start, request with 0(zero), later use your latest retrived message, by his ID try {
	$api = new TextMagicAPI(array(
    "username" => "chandler",//swarnajithfernando
    "password" => "chandler727"//JmazWG9KBW
	));

	$results = $api->messageStatus(array($messageId));

	foreach($results as $msgId => $msgInfo) {
		echo $msgInfo['text'].'<br>';
		echo $msgInfo['status'].'<br>';
		echo $msgInfo['reply_number'].'<br>';
	
		// this parameter exists for final statuses
		if (array_key_exists('completed_time', $msgInfo)){
			echo date('d/m/Y H:i:s',$msgInfo['completed_time']).'<br>';
		}
		// this parameter exists for final statuses
		if (array_key_exists('credits_cost', $msgInfo)){
		   echo $msgInfo['credits_cost'].'<br>';
		}
	}
} catch (WrongParameterValueException $e) {
} catch (TooManyItemsException $e) { 
} catch (UnknownMessageIdException $e) { 
} catch (AuthenticationException $e) { 
} catch (IPAddressException $e) { 
} catch (RequestsLimitExceededException $e) { 
} catch (DisabledAccountException $e) { 
} catch (Exception $e) { 
	echo "Catched Exception with message '".$e->getMessage()."' in ".$e->getFile().":".$e->getLine(); 
}
?>