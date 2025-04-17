<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$stmt = $mysqli->prepare("SELECT transCode,transCodeDesc FROM transactioncode ORDER BY transCodeDesc ASC") or die($mysqli->error);
$stmt->execute();
$stmt->bind_result($transCode,$transCodeDesc) or die($mysqli->error);
$transArray = array();
while($stmt->fetch()){
    $row = array("value"=>$transCodeDesc, "id"=>strval($transCode));
    $transArray[] = $row;
}
echo json_encode($transArray);
?>