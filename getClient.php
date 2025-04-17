<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/10/2017
 * Time: 4:26 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
echo getClientNameByClientId($mysqli, $_REQUEST['clientId']);
?>