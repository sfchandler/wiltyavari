<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$referenceCode = $_POST['referenceCode'];
$id = $_POST['referenceId'];
if($_POST['action'] == 'Delete'){
    deleteJobBoardReference($mysqli,$id);
    echo listJobBoardReferences($mysqli);
}elseif($_POST['action'] == 'Update'){
    updateJobBoardReference($mysqli,$referenceCode,$id);
    echo listJobBoardReferences($mysqli);
}elseif($_POST['action'] == 'Add'){
    addJobBoardReference($mysqli,$referenceCode);
    echo listJobBoardReferences($mysqli);
}elseif($_POST['action']=='DeActivate') {
    jobBoardMailActivateDeActivate($mysqli,0,$referenceCode);
    echo listJobBoardReferences($mysqli);
}elseif($_POST['action']=='Activate'){
    jobBoardMailActivateDeActivate($mysqli,1,$referenceCode);
    echo listJobBoardReferences($mysqli);
}else{
    echo listJobBoardReferences($mysqli);
}
?>