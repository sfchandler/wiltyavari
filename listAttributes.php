<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if($_POST['action'] == 'Delete'){
    $attrId = $_POST['attrId'];
    deleteAttribute($mysqli,$attrId);
    echo listAttributes($mysqli);
}elseif($_POST['action'] == 'Update'){
    $attributeName = $_POST['attributeName'];
    $attributeId = $_POST['attributeId'];
    $attributeCode = $_POST['attributeCode'];
    updateAttribute($mysqli,$attributeName,$attributeId,$attributeCode);
    echo listAttributes($mysqli);
}elseif($_POST['action'] == 'Add'){
    $attributeName = $_POST['attributeName'];
    $attributeId = $_POST['attributeId'];
    $attributeCode = $_POST['attributeCode'];
    addAttribute($mysqli,$attributeName,$attributeCode);
    echo listAttributes($mysqli);
}else{
    echo listAttributes($mysqli);
}
?>