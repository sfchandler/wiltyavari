<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$action = $_REQUEST['action'] ?? null;
if($action == 'VIEW'){
    $cons_id = $_REQUEST['cons_id'] ?? null;
    $docTypeId = 61;
    $sciclunaDocTypeId = 62;
    echo getSurveyDocumentsList($mysqli,$cons_id,$docTypeId,$sciclunaDocTypeId);
}