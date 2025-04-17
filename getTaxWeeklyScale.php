<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 21/08/2017
 * Time: 1:26 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(isset($_POST['taxCode'])){
    $taxCodeDesc = getTaxCodeDescriptionByCode($mysqli,$_POST['taxCode']);
    echo getTaxWeeklyScale($mysqli,$_POST['taxCode'],$taxCodeDesc);
}

?>