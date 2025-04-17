<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");


if($_REQUEST['action'] == 'invoiceDropdown'){
    echo getClientsForDropdownTimeSheet($mysqli);
}elseif($_REQUEST['action'] == 'accClients'){
    echo getAllClientsListDropDown($mysqli);
}elseif ($_REQUEST['action'] == 'scheduling'){
    echo getClientsForScheduling($mysqli);
}elseif ($_REQUEST['action'] == 'department'){
    echo getClientsForDocumentUpload($mysqli);
}elseif ($_REQUEST['action'] == 'locations'){
    echo getClientsForDocumentUpload($mysqli);
}elseif($_REQUEST['action'] == 'SINGLESELECT'){
    echo getClientsDropdownSingleSelect($mysqli);
}else{
    echo getClientsForDropdown($mysqli);
}

	
?>