<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
$limitStart = $_POST['limitStart'];
$limitCount = 10; 
if((isset($limitStart ) || !empty($limitStart))){
	$mobSearch = str_replace(' ','',trim($_REQUEST['searchMobile']));
	$fNameSearch = str_replace(' ','',trim($_REQUEST['searchFirstName']));
	$lNameSearch = str_replace(' ','',trim($_REQUEST['searchLastName']));
	$emailSearch = str_replace(' ','',trim($_REQUEST['searchEmail']));
	$canId = $_REQUEST['canId'];
	$axiomno = $_REQUEST['axiomno'];
	$querySequence;
		if(!empty($canId)&&empty($fNameSearch)&& empty($lNameSearch)&& empty($mobSearch)&& empty($emailSearch)){
			$searchString = "candidate.candidateId = ?";
			$querySequence = 1;
		}else if(!empty($axiomno) && empty($fNameSearch)&& empty($lNameSearch)&& empty($mobSearch)&& empty($emailSearch)&& empty($canId)){
			$searchString = "candidate.axiomno = ?";
			$querySequence = 2;
		}else if(!empty($fNameSearch)&& empty($lNameSearch)&& empty($mobSearch)&& empty($emailSearch)&& empty($canId)){
			$searchString = "candidate.firstName LIKE ?";
			$querySequence = 3;
		}else if(!empty($lNameSearch)&& empty($fNameSearch)&& empty($mobSearch)&& empty($emailSearch)&& empty($canId)){
			$searchString = "candidate.lastName LIKE ?";
			$querySequence = 4;
		}
		else if(!empty($mobSearch) && empty($lNameSearch)&& empty($fNameSearch)&& empty($emailSearch)&& empty($canId)){
			$searchString = "candidate.mobileNo = ?";
			$querySequence = 5;
		}
		else if(!empty($emailSearch) && empty($lNameSearch)&& empty($fNameSearch)&& empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.email = ?";
			$querySequence = 6;
		}
		else if(!empty($lNameSearch)&& !empty($fNameSearch)&& empty($emailSearch) && empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ?";
			$querySequence = 7;
		}
		else if(!empty($lNameSearch)&& !empty($fNameSearch)&& empty($emailSearch) && !empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ?";
			$querySequence = 8;
		}
		else if(!empty($lNameSearch)&& !empty($fNameSearch)&& !empty($emailSearch) && !empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND candidate.email = ?";
			$querySequence = 9;
		}
		else if(!empty($lNameSearch)&& !empty($fNameSearch)&& !empty($emailSearch) && !empty($mobSearch)&& !empty($canId)){
			$searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND candidate.email = ? AND candidate.candidateId = ?";
			$querySequence = 10;
		}else if(empty($lNameSearch)&& !empty($fNameSearch)&& !empty($emailSearch) && empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.firstName LIKE ? AND candidate.email = ?";
			$querySequence = 11;
		}
		else if(empty($lNameSearch)&& !empty($fNameSearch)&& empty($emailSearch) && !empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.firstName LIKE ? AND candidate.mobileNo = ?";
			$querySequence = 12;
		}
		else if(!empty($lNameSearch)&& empty($fNameSearch)&& !empty($emailSearch) && empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.lastName LIKE ? AND candidate.email = ?";
			$querySequence = 13;
		}
		else if(!empty($lNameSearch)&& empty($fNameSearch)&& empty($emailSearch) && !empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.lastName LIKE ? AND candidate.mobileNo = ?";
			$querySequence = 14;
		}
		else if(empty($lNameSearch)&& empty($fNameSearch)&& !empty($emailSearch) && !empty($mobSearch)&& empty($canId)){
			$searchString = "candidate.email LIKE ? AND candidate.mobileNo = ?";
			$querySequence = 15;
		}
		else if(!empty($canId) && empty($lNameSearch)&& !empty($fNameSearch)&& empty($emailSearch) && empty($mobSearch)){
			$searchString = "candidate.candidateId = ? AND candidate.firstName LIKE ?";
			$querySequence = 16;
		}
		else if(!empty($canId) && !empty($lNameSearch)&& empty($fNameSearch)&& empty($emailSearch) && empty($mobSearch)){
			$searchString = "candidate.candidateId = ? AND candidate.lastName LIKE ?";
			$querySequence = 17;
		}
		else if(!empty($canId) && empty($lNameSearch)&& empty($fNameSearch)&& !empty($emailSearch) && empty($mobSearch)){
			$searchString = "candidate.candidateId = ? AND candidate.email = ?";
			$querySequence = 18;
		}
		else if(!empty($canId) && empty($lNameSearch)&& empty($fNameSearch)&& empty($emailSearch) && !empty($mobSearch)){
			$searchString = "candidate.candidateId = ? AND candidate.mobilNo = ?";
			$querySequence = 19;
		}
			
		$stmt = $mysqli->prepare("SELECT 
				candidate.firstName,
				candidate.lastName,
				candidate.address,
				candidate.mobileNo,
				candidate.email,
				candidate.consultantId,
				candidate.candidateId,
				candidate.axiomno
			  FROM
				candidate
				LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
			  WHERE
				".$searchString."
 			  LIMIT $limitStart, $limitCount") or die($mysqli->error);
		switch ($querySequence) {
			case 1:
				$stmt->bind_param("s",$canId) or die($mysqli->error);
				break;
			case 2:
				$stmt->bind_param("s",$axiomno) or die($mysqli->error);
				break;	
			case 3:
				$stmt->bind_param("s",$fNameSearch) or die($mysqli->error);
				break;
			case 4:
				$stmt->bind_param("s",$lNameSearch) or die($mysqli->error);
				break;
			case 5:
				$stmt->bind_param("s",$mobSearch) or die($mysqli->error);
				break;
			case 6:
				$stmt->bind_param("s",$emailSearch) or die($mysqli->error);
				break;
			case 7:
				$stmt->bind_param("ss",$fNameSearch,$lNameSearch) or die($mysqli->error);
				break;
			case 8:
				$stmt->bind_param("sss",$fNameSearch,$lNameSearch,$mobSearch) or die($mysqli->error);
				break;
			case 9:
				$stmt->bind_param("ssss",$fNameSearch,$lNameSearch,$mobSearch,$emailSearch) or die($mysqli->error);
				break;
			case 10:
				$stmt->bind_param("sssss",$fNameSearch,$lNameSearch,$mobSearch,$emailSearch,$canId) or die($mysqli->error);
				break;
			case 11:
				$stmt->bind_param("ss",$fNameSearch,$emailSearch) or die($mysqli->error);	
				break;
			case 12:
				$stmt->bind_param("ss",$fNameSearch,$mobSearch) or die($mysqli->error);	
				break;
			case 13:
				$stmt->bind_param("ss",$lNameSearch,$emailSearch) or die($mysqli->error);	
				break;
			case 14:
				$stmt->bind_param("ss",$lNameSearch,$mobSearch) or die($mysqli->error);	
				break;
			case 15:
				$stmt->bind_param("ss",$emailSearch,$mobSearch) or die($mysqli->error);	
				break;	
			case 16:
				$stmt->bind_param("ss",$canId,$fNameSearch) or die($mysqli->error);
				break;
			case 17:
				$stmt->bind_param("ss",$canId,$lNameSearch) or die($mysqli->error);
				break;
			case 18:
				$stmt->bind_param("ss",$canId,$emailSearch) or die($mysqli->error);
				break;
			case 19:
				$stmt->bind_param("ss",$canId,$mobSearch) or die($mysqli->error);
				break;						
		}
		$stmt->execute();
		$stmt->store_result();
		$stmt->bind_result($firstName, $lastName, $address, $mobileNo, $email, $consultantId,$candidateId,$axiomno);
		
    	$num_of_rows = $stmt->num_rows;
		if($num_of_rows > 0){
			while($stmt->fetch()){
				$row = $row.'<tr><td>'.$lastName.' '.$firstName.' ('.getConsultantName($mysqli,$consultantId).')</td><td>'.$candidateId.'</td><td>'.$axiomno.'</td><td class="cid" data-cid="'.$candidateId.'"><a href="#" class="addRecipient">'.$mobileNo.'</a></td><td>'.$address.'</td><td>'.$email.'</td></tr>';
				/*$row = array('name'=>$lastName.' '.$firstName.' ('.getConsultantName($mysqli,$consultantId).')','mobile'=>$mobileNo,'address'=>$address,'email'=>$email);
	  			$candidateArray[] = $row;*/
			}
			echo $row;
		}else{
			//echo '<tr><td colspan="4">No Matching records found</td></tr>';
			//echo json_encode($candidateArray);
		}
}else{
	echo '<tr><td colspan="4">No Matching records found</td></tr>';
	//echo json_encode($candidateArray);
}
?>