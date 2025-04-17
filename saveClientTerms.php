<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 31/10/2017
 * Time: 11:55 AM
 */

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$action = $_REQUEST['action'];
$days =$_REQUEST['days'];
$description = $_REQUEST['description'];
$termId = $_REQUEST['termId'];

if($action == 'GET'){
    echo getClientTerms($mysqli);
}else if($action == 'ADD'){
    echo addClientTerm($mysqli,$days,$description);
}else if($action == 'DELETE'){
    deleteClientTerm($mysqli,$termId);
    echo getClientTerms($mysqli);
}

?>