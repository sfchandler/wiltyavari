<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$canId = $_REQUEST['canId'];
echo getActivateInactivateReason($mysqli,$canId);