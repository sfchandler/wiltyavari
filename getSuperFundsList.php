<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 18/08/2017
 * Time: 9:48 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_REQUEST['candidateId'])){
    echo listAssignedSuperFund($mysqli, $_REQUEST['candidateId']);
}

?>