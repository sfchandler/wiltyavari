<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/05/2019
 * Time: 5:12 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*function addUID($mysqli,$candidateId){
    $ins = $mysqli->prepare("INSERT INTO uid_container(candidateId) VALUES(?)")or die($mysqli->error);
    $ins->bind_param("s",$candidateId)or die($mysqli->error);
    $ins->execute();
    if($ins){
        return $mysqli->error;
    }else{
        return $mysqli->error;
    }
}*/
function validateUIDContainer($mysqli,$candidateId){
    $sql = $mysqli->prepare("SELECT candidateId FROM uid_container WHERE candidateId = ?")or die($mysqli->error);
    $sql->bind_param("s",$candidateId)or die($mysqli->error);
    $sql->execute();
    $num_of_rows = $sql->num_rows;
    if ($num_of_rows > 0) {
        return true;
    }else{
        return false;
    }
}

$sql = $mysqli->prepare("SELECT candidateId FROM candidate")or die($mysqli->error);
$sql->execute();
$sql->bind_result($candidateId)or die($mysqli->error);
$sql->store_result();
$numRows = $sql->num_rows;
if($numRows>0){
    while($sql->fetch()){
        if(!validateUIDContainer($mysqli,$candidateId)) {
            addUID($mysqli, $candidateId);
        }
    }
}

