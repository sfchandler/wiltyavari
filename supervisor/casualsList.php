<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");

$supervisorClient = $_SESSION['supervisorClient'];

if(isset($supervisorClient)){
    $stmt = $mysqli->prepare("SELECT 
                                      employee_allocation.candidateId,
                                      candidate.firstName,
                                      candidate.lastName
                                    FROM
                                      employee_allocation
                                      INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                    WHERE
                                      employee_allocation.clientId = ?");
    $stmt->bind_param("i",$supervisorClient)or die($mysqli->error);
    $stmt->execute();
    $stmt->bind_result($candidateId,$firstName,$lastName);
    $empArray = array();
    while($stmt->fetch()){
        $row = array("value"=>$firstName.' '.$lastName, "id"=>strval($candidateId));
        $empArray[] = $row;
    }
    echo json_encode($empArray);
}

?>