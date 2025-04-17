<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/22/2017
 * Time: 3:36 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$canid = $_REQUEST['canid'];
$rosternote = $_REQUEST['rosternote'];

echo saveRosterNote($mysqli,$canid,$rosternote);
?>