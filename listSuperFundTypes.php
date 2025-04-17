<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/08/2017
 * Time: 4:56 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getSuperFundTypes($mysqli);
?>