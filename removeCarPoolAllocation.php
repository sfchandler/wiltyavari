<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 24/07/2018
 * Time: 9:02 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_REQUEST['candidateId'])&& isset($_REQUEST['empPoolId'])){
    echo removeCarPoolAllocation($mysqli,$_REQUEST['candidateId'],$_REQUEST['empPoolId']);
}