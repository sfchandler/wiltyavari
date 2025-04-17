<?php

require_once("includes/db_conn.php");
require_once("includes/functions.php");
//error_reporting(E_ALL);
//ini_set('display_errors', true);
$emCheck = $_POST['emCheck'];
try {
    $check = validateEmailAndSetId($mysqli,$emCheck);
}catch (Exception $e){
    echo $e->getMessage();
}
if(!empty($check) && ($check != false)){
    echo base64_encode($check);
}else{
    echo 'FALSE';
}