<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$contents = retrieveCandidateEmailContent($mysqli,'&lt;83c94e$10afcec@ip-smtp3-web.seek.com.au&gt;','melbourne');
echo 'CONTENTS<br>'.$contents.'<br>';

//$pattern="/(?:[a-z0-9!#$%&'*+=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+=?^_`{|}~-]+)*|\"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*\")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/";
	$pattern='/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i';
	preg_match_all($pattern, $contents, $matches);	
	$matchedEmail;
	foreach($matches[0] as $email){
		$matchedEmail = $email;
	}
	echo $matchedEmail;
//echo extractEmailFromContent($contents);
?>
