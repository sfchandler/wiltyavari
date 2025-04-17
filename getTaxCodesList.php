<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 22/08/2017
 * Time: 1:29 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(isset($_REQUEST['candidateId'])){
    echo listAssignedTaxCodes($mysqli, $_REQUEST['candidateId']);
}
?>