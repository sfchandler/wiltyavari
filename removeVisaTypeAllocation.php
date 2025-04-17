<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 9/05/2019
 * Time: 3:58 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_REQUEST['candidateId'])&& isset($_REQUEST['empVisaTypeId'])){
    echo removeVisaTypeAllocation($mysqli,$_REQUEST['candidateId'],$_REQUEST['empVisaTypeId']);
}