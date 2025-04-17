<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 26/07/2018
 * Time: 11:51 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

//$clientId = $_REQUEST['clientId'];
if($_REQUEST['action'] == 'HOLIDAY'){
    echo getStatesForDropdown($mysqli);
}else {
    echo getStatesDropdown($mysqli);
}