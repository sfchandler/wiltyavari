<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 18/09/2018
 * Time: 2:13 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
ini_set('max_execution_time', 100000000);

$sql = $mysqli->prepare("SELECT 
  axiomno,
  diaryNoteId
FROM
  diarynote
WHERE
  candidateId IS NULL AND 
  firstName IS NULL AND 
  lastName IS NULL AND 
  axiomno IS NOT NULL LIMIT 0,3000")or die($mysqli->error);
$sql->execute();
$sql->bind_result($axiomno,$diaryNoteId) or die($mysqli->error);
$sql->store_result();
$sql->num_rows();
while($sql->fetch()){
    $dataInfo[] = array('axiomno' =>$axiomno, 'diaryNoteId' =>$diaryNoteId);
}
/*$sql->free_result();
$sql->close();*/
$up = $mysqli->prepare("UPDATE diarynote SET candidateId = ?, firstName = ?, lastName = ? WHERE diaryNoteId = ?")or die($mysqli->error);
//echo var_dump($dataInfo);
foreach ($dataInfo as $data) {
    $axId = $data['axiomno'];
    $canId = getCandidateIdByAxiomNo($mysqli,$axId);
    $fName = getCandidateFirstNameByCandidateId($mysqli,$canId);
    $lName = getCandidateLastNameByCandidateId($mysqli,$canId);
    $noteId = $data['diaryNoteId'];
    //$fName = 'AXIOM LEGACY DATA';
    $up->bind_param("sssi",$canId,$fName,$lName,$noteId)or die($mysqli->error);
    if($up->execute()){
        echo 'Updated<br>';
    }else{
        echo 'Error '.$mysqli->error.'<br>';
    }
}
$up->close();