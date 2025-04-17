<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/10/2017
 * Time: 2:12 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getClientShiftLocations($mysqli);

?>