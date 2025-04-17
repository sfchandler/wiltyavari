<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/10/2017
 * Time: 11:52 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if(isset($_REQUEST['clientId'])) {
    echo generateStatesForShiftAddress($mysqli, $_REQUEST['clientId']);
}
?>