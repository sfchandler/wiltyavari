<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if(!empty($_POST['shift_id']) && $_POST['comments']){
   echo updateTimesheetComments($mysqli, $_POST['shift_id'], $_POST['comments']);
}