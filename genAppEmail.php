<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 9/08/2019
 * Time: 2:33 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_POST['candidateId'])) {
    echo generateMobileAppLoginEmail($mysqli, $_POST['candidateId']);
}else{
    echo 'Employee ID not set';
}