<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
echo updateRecruitmentStatusByCandidateId($mysqli,$_REQUEST['rec_status'],$_REQUEST['canId']);