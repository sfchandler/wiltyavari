<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$deptId = $_REQUEST['deptId'];
echo getClientDepartmentNote($mysqli,$deptId);