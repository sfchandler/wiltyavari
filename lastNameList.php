<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$stmt = $mysqli->prepare("SELECT 
								  lastName,
								  candidateId
								FROM
								  candidate");
$stmt->execute();
$stmt->bind_result($lastName,$candidateId);
$empArray = array();
while($stmt->fetch()){
    $row = array("value"=>$lastName,"id"=>$candidateId);
    $empArray[] = $row;
}
echo json_encode($empArray);
?>