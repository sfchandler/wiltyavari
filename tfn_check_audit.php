<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo validateTFNAuditCheck($mysqli,$_POST['tfn'],$_POST['canId']);