<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$stmt = $mysqli->prepare("SELECT 
								  firstName,
								  lastName,
								  candidateId
								FROM
								  candidate");
$stmt->execute();
$stmt->bind_result($firstName,$lastName,$candidateId);
$empArray = array();
while($stmt->fetch()){
    $row = array("value"=>$firstName.' '.$lastName,"id"=>$candidateId);
    $empArray[] = $row;
}
echo json_encode($empArray);
?>