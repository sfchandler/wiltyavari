<?php
include ("includes/textmagic-sms-api-php/TextMagicAPI.php");
try{
	$lastRetrievedId = 0; // for the start, request with 0(zero), later use your latest retrived message, by his ID try {
	$api = new TextMagicAPI(array(
    "username" => "chandler",
    "password" => "chandler727"
));
	$results = $api->receive($lastRetrievedId);
	
	$messages = $results['messages'];
	//var_dump($messages);
	foreach($messages as $message_id => $message) {
		$message_id = $message['message_id'];
		$text = $message['text'];
		$from = $message['from'];
		$timestamp = $message['timestamp'];
	}
	$unread = $results['unread']; // if it is greater than zero, rerequst this function with latest message ID retrived in that response
	echo $message_id.'<br>'.$text.'<br>'.$from.'<br>'.$timestamp.'<br>';
	echo var_dump($messages);	
} catch (WrongParameterValueException $e) { 
} catch (UnknownMessageIdException $e) { 
} catch (AuthenticationException $e) { 
} catch (IPAddressException $e) { 
} catch (RequestsLimitExceededException $e) { 
} catch (DisabledAccountException $e) { 
} catch (Exception $e) { 
	echo "Catched Exception with message '".$e->getMessage()."' in ".$e->getFile().":".$e->getLine(); 
}
?>
<!-- 
13625283
Fjxjcjxjxjxj
61422039969
15-03-2017
-->