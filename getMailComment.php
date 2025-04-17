<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 4/06/2018
 * Time: 4:39 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$autoId = $_REQUEST['autoId'];
echo getMailComment($mysqli,$autoId);

?>