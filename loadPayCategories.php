<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$action = $_REQUEST['action'];
if($action == 'display'){
    echo displayPayCategoryList($mysqli);
}else if(empty($action)){
    echo getPayCategoryList($mysqli);
}else{
    echo getPayCategoryList($mysqli);
}
?>