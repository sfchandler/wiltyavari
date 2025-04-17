<?php
define('DB_SERVER', "localhost");
define('DB_USER', openssl_decrypt('cTDjPjNFaq6RKA==',"AES-128-CTR","Ou#0209TsndSaen^ndsHdfWy"));
define('DB_PASSWORD', openssl_decrypt('Nmr2cxAXKYKHEC0aQVrwXA==',"AES-128-CTR","Ou#0209TsndSaen^ndsHdfWy"));
define('DB_DATABASE', openssl_decrypt('cTDjPjNFaq6RKFoXFA==',"AES-128-CTR","Ou#0209TsndSaen^ndsHdfWy"));
$mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_DATABASE);
$mysqli->set_charset("utf8");
if($mysqli->connect_errno){
	trigger_error('mysqli Connection failed! ' . htmlspecialchars(mysqli_connect_error()), E_USER_ERROR);
}

?>