<?php

include "./includes/db_conn.php";
include "./includes/functions.php";
//ini_set('max_execution_time', 1000000);

$stmt = $mysqli->prepare("SELECT 
							  candidate_otherlicence.axiomno,
							  candidate_otherlicence.attributeCode
							FROM
							  candidate_otherlicence");
$stmt->execute();
$stmt->bind_result($axiomno,$attributeCode) or die($mysqli->error);
$stmt->store_result();
$numRows = $stmt->num_rows;
echo 'NUMROWS'.$numRows.'<br>';
while($stmt->fetch()){
	//echo $FIRSTNAME,$SURNAME,$ADDRESS,$EMAILADDRESS,$PHONE,$PHONEMOBILE,$Gender,$CANDIDATE.'<br>';
	$dataInfo[] = array(
		'axiomno' =>$axiomno,
		'attributeCode' =>$attributeCode); 
}
//echo var_dump($dataInfo);
$stmt->free_result();
$attr = $mysqli->prepare("UPDATE
  candidate_otherlicence
SET
  candidateId = ?,
  otherLicenceId = ?
WHERE
  candidate_otherlicence.axiomno = ?
AND 
  candidate_otherlicence.attributeCode = ?") or die($mysqli->error);
 
foreach ($dataInfo as $data) {
	
	$axno = $data['axiomno'];
	$attributeCode = $data['attributeCode'];
	$attrId = getOtherLicenceId($mysqli,$attributeCode);
	$canId = getCandidateIdByAxiomNo($mysqli,$axno);
	echo $axno.'<br>';echo $attributeCode.'<br>'.$attrId.'<br>'.$canId.'<br>';
	try{
		$attr->bind_param("siis", $canId,$attrId,$axno,$attributeCode)or die($mysqli->error);
		$attr->execute();
		$nrows = $attr->affected_rows;				
		if($nrows == '1'){
			echo 'ATTRUPDATED<br>';
		}else{
			echo $mysqli->error.'<br>';
		}
	}catch(Exception $e){
		echo 'Caught error'.$e->getMessage();
	}
}
//$ins->close();
//$reg->close();

?>