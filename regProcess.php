<?php
include("includes/db_conn.php");
include("includes/functions.php");

$candidateId = base64_decode($_REQUEST['candidateId']);
$messageId = $_REQUEST['messageId'];
$consultantId = $_REQUEST['consultantId'];
$title = $_REQUEST['title']; 
$firstName = $_REQUEST['firstName']; 
$lastName = $_REQUEST['lastName']; 
$address = str_replace('&','and',$mysqli->real_escape_string($_REQUEST['address'])); 
$postcode = $_REQUEST['postcode']; 
$homePhone = $_REQUEST['homePhone'];
$mobile = $_REQUEST['mobile']; 
$dob = $_REQUEST['dob']; 
$gender = $_REQUEST['gender']; 
$nationality = $_REQUEST['nationality']; 
$email = $_REQUEST['email']; 
$emgFullName = $_REQUEST['emgFullName']; 
$relationship = $_REQUEST['relationship'];
$emgAddress = str_replace('&','and',$mysqli->real_escape_string($_REQUEST['emgAddress'])); 
$emghomePhone = $_REQUEST['emghomePhone']; 
$emgMobile = $_REQUEST['emgMobile']; 
$behalf = $_REQUEST['behalf']; 
$transportMethod = $_REQUEST['transportMethod']; 
$howFar = $_REQUEST['howFar'];
$earlyCalls = $_REQUEST['earlyCalls'];
$visa = $_REQUEST['visa']; 
$visaLimitation = $_REQUEST['visaLimitation']; 
$qualification = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['qualification']))); 
$shiftAvailable = $_REQUEST['shiftAvailable']; 
$notes = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['notes']))); 
$newsletter = $_REQUEST['newsletter'];
$jobactive =$_REQUEST['jobactive'];
$disabilities = $_REQUEST['disabilities']; 
$disabilityDesc = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['disabilityDesc']))); 
$compensation = $_REQUEST['compensation']; 
$compensationDesc = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['compensationDesc']))); 
$empName1 = $_REQUEST['empName1'];
$doi1 = $_REQUEST['doi1']; 
$natureInjury1 = $_REQUEST['natureInjury1']; 
$durAbsense1 = $_REQUEST['durAbsense1']; 
$empName2 = $_REQUEST['empName2']; 
$doi2 = $_REQUEST['doi2']; 
$natureInjury2 = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['natureInjury2']))); 
$durAbsense2 = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['durAbsense2']))); 
$pension = $_REQUEST['pension']; 
$pensionDesc = $mysqli->real_escape_string(stripslashes($_REQUEST['pensionDesc'])); 
$hearing = $_REQUEST['hearing']; 
$hearingDesc = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['hearingDesc']))); 
$smoker = $_REQUEST['smoker']; 
$eyetrouble = $_REQUEST['eyetrouble']; 
$hearingImp = $_REQUEST['hearingImp']; 
$surgicalPro = $_REQUEST['surgicalPro']; 
$asthma = $_REQUEST['asthma']; 
$hernia = $_REQUEST['hernia']; 
$duodenal = $_REQUEST['duodenal']; 
$deafness = $_REQUEST['deafness']; 
$epilepsy = $_REQUEST['epilepsy']; 
$lungDisorder = $_REQUEST['lungDisorder']; 
$fainting = $_REQUEST['fainting']; 
$backdisorder = $_REQUEST['backdisorder']; 
$nervedisorder = $_REQUEST['nervedisorder']; 
$dizziness = $_REQUEST['dizziness']; 
$arthritis = $_REQUEST['arthritis']; 
$injury = $_REQUEST['injury']; 
$injuryDesc = str_replace('&','and',$mysqli->real_escape_string(stripcslashes($_REQUEST['injuryDesc']))); 
$swollen = $_REQUEST['swollen']; 
$chronic = $_REQUEST['chronic']; 
$skin = $_REQUEST['skin']; 
$diabetes = $_REQUEST['diabetes']; 
$medicalAttention = $_REQUEST['medicalAttention']; 
$medicalAttentionDesc = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['medicalAttentionDesc']))); 
$criminalHistory = $_REQUEST['criminalHistory']; 
$criminalHistoryDesc = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['criminalHistoryDesc'])));
$doo1 = $_REQUEST['doo1'];
$natureOffence1 = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['natureOffence1'])));
$doo2 = $_REQUEST['doo2'];
$natureOffence2 = str_replace('&','and',$mysqli->real_escape_string(stripslashes($_REQUEST['natureOffence2'])));
$pcheck = $_REQUEST['pcheck'];

//if(!checkRegCandidateId($mysqli,$candidateId)){
	$stmt = $mysqli->prepare("INSERT INTO reg_candidate(
								  candidateId,
								  messageId,
								  title,
								  firstName,
								  lastName,
								  homeAddress,
								  postcode,
								  homePhone,
								  mobile,
								  dob,
								  gender,
								  nationality,
								  email,
								  emgFullName,
								  relationship,
								  emgAddress,
								  emghomePhone,
								  emgMobile,
								  behalf,
								  transportMethod,
								  howFar,
								  earlyCalls,
								  visa,
								  visaLimitation,
								  qualification,
								  notes,
								  jobactive,
								  newsletter,
								  disabilities,
								  disabilityDesc,
								  compensation,
								  compensationDesc,
								  empName1,
								  doi1,
								  natureInjury1,
								  durAbsense1,
								  empName2,
								  doi2,
								  natureInjury2,
								  durAbsense2,
								  pension,
								  pensionDesc,
								  hearing,
								  hearingDesc,
								  smoker,
								  eyetrouble,
								  hearingImp,
								  surgicalPro,
								  asthma,
								  hernia,
								  duodenal,
								  deafness,
								  epilepsy,
								  lungDisorder,
								  fainting,
								  backdisorder,
								  nervedisorder,
								  dizziness,
								  arthritis,
								  injury,
								  injuryDesc,
								  swollen,
								  chronic,
								  skin,
								  diabetes,
								  medicalAttention,
								  medicalAttentionDesc,
								  criminalHistory,
								  criminalHistoryDesc,
								  doo1,
								  natureOffence1,
								  doo2,
								  natureOffence2,
								  pcheck)	VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die($mysqli->error);
	$stmt->bind_param("ssssssisssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
				$candidateId,
				$messageId,
				$title,
				$firstName,
				$lastName,
				$address,
				$postcode,
				$homePhone,
				$mobile,
				$dob,
				$gender,
				$nationality,
				$email,
				$emgFullName,
				$relationship,
				$emgAddress,
				$emghomePhone,
				$emgMobile,
				$behalf,
				$transportMethod,
				$howFar,
				$earlyCalls,
				$visa,
				$visaLimitation,
				$qualification,
				$notes,
				$jobactive,
				$newsletter,
				$disabilities,
				$disabilityDesc,
				$compensation,
				$compensationDesc,
				$empName1,
				$doi1,
				$natureInjury1,
				$durAbsense1,
				$empName2,
				$doi2,
				$natureInjury2,
				$durAbsense2,
				$pension,
				$pensionDesc,
				$hearing,
				$hearingDesc,
				$smoker,
				$eyetrouble,
				$hearingImp,
				$surgicalPro,
				$asthma,
				$hernia,
				$duodenal,
				$deafness,
				$epilepsy,
				$lungDisorder,
				$fainting,
				$backdisorder,
				$nervedisorder,
				$dizziness,
				$arthritis,
				$injury,
				$injuryDesc,
				$swollen,
				$chronic,
				$skin,
				$diabetes,
				$medicalAttention,
				$medicalAttentionDesc,
				$criminalHistory, 
				$criminalHistoryDesc,
				$doo1,
				$natureOffence1,
				$doo2,
				$natureOffence2,
				$pcheck) or die($mysqli->error);
	$stmt->execute();
	$nrows = $stmt->affected_rows;
	if($nrows == '1'){
		if(!empty($shiftAvailable)){
			foreach ($shiftAvailable as $value) {
				updateRegShifts($mysqli,$candidateId,$value);			
			}
		}
		$genStatus = generateRegPackV2($mysqli,$candidateId,
				$title,
				$firstName,
				$lastName,
				$address,
				$postcode,
				$homePhone,
				$mobile,
				$dob,
				$gender,
				$nationality,
				$email,
				$emgFullName,
				$relationship,
				$emgAddress,
				$emghomePhone,
				$emgMobile,
				$behalf,
				$transportMethod,
				$howFar,
				$earlyCalls,
				$visa,
				$visaLimitation,
				$qualification,
				$shiftAvailable,
				$notes,
				$jobactive,
				$newsletter,
				$disabilities,
				$disabilityDesc,
				$compensation,
				$compensationDesc,
				$empName1,
				$doi1,
				$natureInjury1,
				$durAbsense1,
				$empName2,
				$doi2,
				$natureInjury2,
				$durAbsense2,
				$pension,
				$pensionDesc,
				$hearing,
				$hearingDesc,
				$smoker,
				$eyetrouble,
				$hearingImp,
				$surgicalPro,
				$asthma,
				$hernia,
				$duodenal,
				$deafness,
				$epilepsy,
				$lungDisorder,
				$fainting,
				$backdisorder,
				$nervedisorder,
				$dizziness,
				$arthritis,
				$injury,
				$injuryDesc,
				$swollen,
				$chronic,
				$skin,
				$diabetes,
				$medicalAttention,
				$medicalAttentionDesc,
				$criminalHistory, 
				$criminalHistoryDesc,
				$doo1,
				$natureOffence1,
				$doo2,
				$natureOffence2,
				$pcheck,
				$consultantId);
		  if($genStatus){
			  	/*if(generateRegistrationEmail($mysqli,$firstName,$lastName,$candidateId,$email,$consultantId) == 'SUCCESS'){
					echo json_encode('success');
				}else{
					echo json_encode('registration email generation failed');
				}*/
		  }else{
			  echo json_encode($genStatus);
		  }
	}else{
	  echo json_encode($mysqli->error);
	}
/*}else{
	echo json_encode('You are already registered');
	
}*/


?>