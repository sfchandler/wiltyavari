<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 27/06/2017
 * Time: 9:10 AM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
echo getClientDocuments($mysqli,$clientId);
?>