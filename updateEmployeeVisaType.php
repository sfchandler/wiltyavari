<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 10/05/2019
 * Time: 11:13 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(isset($_REQUEST['empVisaTypeId'])&& isset($_REQUEST['visaExpDate'])){
    echo updateEmployeeVisaType($mysqli,$_REQUEST['empVisaTypeId'],$_REQUEST['visaExpDate']);
}