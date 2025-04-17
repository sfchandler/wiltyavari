<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$term = $_REQUEST['search'];
$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionId'];
/*$stmt = $mysqli->prepare("SELECT
								  candidateId,
								  firstName,
								  lastName
								FROM
								  candidate
							    WHERE
                                  concat(lastName,firstName)
                                LIKE 
                                 '%".$term."%'
							     ");*/
$stmt = $mysqli->prepare("SELECT DISTINCT 
                                  candidate.candidateId,
                                  candidate.firstName,
                                  candidate.lastName
                                FROM
                                  employee_allocation
                                  INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                  /*INNER JOIN employee_positions ON (employee_allocation.candidateId = employee_positions.candidateId)  */
                                WHERE
                                  employee_allocation.clientId = ?
                               /* AND  
                                  employee_positions.positionid = ?*/
                                AND
                                  concat(candidate.lastName,candidate.firstName)
                                LIKE 
                                     '%".$term."%'")or die($mysqli->error);
$stmt->bind_param("i",$clientId)or die($mysqli->error);
$stmt->execute();
$stmt->bind_result($candidateId,$firstName,$lastName);
$empArray = array();
while($stmt->fetch()){
    $row = array("value"=>$firstName.' '.$lastName, "id"=>strval($candidateId));
    $empArray[] = $row;
}
echo json_encode($empArray);
?>