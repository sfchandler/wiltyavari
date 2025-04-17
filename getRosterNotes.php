<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/22/2017
 * Time: 2:23 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$roscanid = $_REQUEST['roscanid'];

echo getRosterNotes($mysqli,$roscanid);

?>