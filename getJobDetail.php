<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jobcode = $_REQUEST['jobcode'];
try{
    echo getJobDetail($mysqli,$jobcode);
}catch (Exception $e){
    echo $e->getMessage();
}

?>