<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 20/10/2017
 * Time: 12:38 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$id = $_POST['id'];
echo getPayrollDetailsById($mysqli,$id);

?>