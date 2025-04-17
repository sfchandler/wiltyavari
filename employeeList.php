<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$stmt = $mysqli->prepare("SELECT 
								  candidateId,
								  firstName,
								  lastName
								FROM
								  candidate
							     ");
$stmt->execute();
$stmt->bind_result($candidateId,$firstName,$lastName);
$empArray = array();
while($stmt->fetch()){
    $row = array("value"=>$firstName.' '.$lastName, "id"=>strval($candidateId));
    $empArray[] = $row;
}
echo json_encode($empArray);
?>