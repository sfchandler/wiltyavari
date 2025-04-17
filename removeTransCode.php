<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 12/04/2018
 * Time: 3:05 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(isset($_REQUEST['transCode'])){
    echo removeTransactionCode($mysqli,$_REQUEST['transCode']);
}
?>