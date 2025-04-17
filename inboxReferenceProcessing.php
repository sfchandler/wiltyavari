<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$referenceCode = $_POST['referenceCode'];
$id = $_POST['referenceId'];
if($_POST['action'] == 'Delete'){

    deleteInboxReference($mysqli,$id);
    echo listInboxReferences($mysqli);
}elseif($_POST['action'] == 'Update'){


    updateInboxReference($mysqli,$referenceCode,$id);
    echo listInboxReferences($mysqli);
}elseif($_POST['action'] == 'Add'){
    addInboxReference($mysqli,$referenceCode);
    echo listInboxReferences($mysqli);
}elseif($_POST['action']=='DeActivate') {
    mailActivateDeActivate($mysqli,0,$referenceCode);
    echo listInboxReferences($mysqli);
}elseif($_POST['action']=='Activate'){
    mailActivateDeActivate($mysqli,1,$referenceCode);
    echo listInboxReferences($mysqli);
}else{
    echo listInboxReferences($mysqli);
}
?>