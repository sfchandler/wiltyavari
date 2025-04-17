<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
header('Content-Type: application/json');
$headers = apache_request_headers();
if (isset($headers['Csrftoken'])) {
    if ($headers['Csrftoken'] !== $_SESSION['csrf_token']) {
        exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
} else {
    exit(json_encode(['error' => 'No CSRF token.']));
}*/
$canId = $_POST['canId'];
$lName = str_replace(')','',str_replace('(','',trim($_POST['lName'])));//str_replace(' ','',trim($_POST['lName']));
$fName = str_replace(')','',str_replace('(','',trim($_POST['fName'])));
$fullName = $fName.' '.$lName;
$nickname = str_replace(')','',str_replace('(','',str_replace(' ','',trim($_POST['nickname']))));
$email = str_replace(' ','',trim($_POST['email']));
$consId = $_POST['consultantId'];
$mobile = str_replace(' ','',trim($_POST['mobile']));
$gender = $_POST['gender'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$unit_no = $_POST['unit_no'];
$street_number = $_POST['street_number'];
$street_name = $_POST['street_name'];
$suburb = $_POST['suburb'];
$state = $_POST['state'];
$postcode = $_POST['postcode'];
$foundhow = $_POST['foundhow'];
$promotion = $_POST['promotion'];
$consultantId = $_POST['consultantId'];
/*$casual_status = $_POST['casual_status'];*/
$password = '';
$username = $canId;
if(!empty($dob)){
    $password = trim(str_replace('/','',$dob));
    $options = [
        'cost' => 12,
    ];
    $hash = password_hash($password,PASSWORD_BCRYPT,$options);
}
$currentDateTime = date('Y-m-d H:i:s');

if(!empty($canId)){
	if(!empty($canId) && !empty($lName) && !empty($fName) && !empty($email)  && !empty($mobile) && !empty($gender)){
        $up = $mysqli->prepare("UPDATE candidate SET firstName = ?, lastName = ?,fullName = ?, email = ?, mobileNo = ?, sex = ?, dob = ?, address = ?, unit_no = ?, street_number = ?, street_name = ?, suburb = ?, state = ?, postcode = ?, nickname = ?,username =?, password = ?,foundhow = ?,promotion = ?, consultantId = ?, updated_at = ? WHERE candidateId = ?") or die($mysqli->error);
        $up->bind_param("sssssssssssssisssssiss", $fName, $lName, $fullName,$email, $mobile, $gender, $dob, $address,$unit_no,$street_number,$street_name,$suburb,$state,$postcode,$nickname,$canId,$hash,$foundhow,$promotion,$consultantId,$currentDateTime,$canId) or die($mysqli->error);
        $up->execute();
        $nrow = $up->affected_rows;
        if ($nrow > 0) {
             if(!empty($dob)){
                updateUsernamePassword($mysqli,$canId,$dob);
            }
            updateUserActivityLog($mysqli,$_SESSION['userSession'],$_SERVER['REMOTE_ADDR'],'CANDIDATE PROFILE','','CANDIDATE PROFILE UPDATED','Candidate profile '.$canId.' updated by '.$_SESSION['userSession']);
            echo 'Updated';
        } else {
            echo $mysqli->error;
        }
	}else{
		echo 'Required';
	}
}else{
    if(!empty($consultantId)){
        $canId = getNewCandidateId($mysqli);

        if($_POST['action'] == 'JobAdder'){
            if(!validateStaffPlacementInfo($mysqli, $_POST['placement_id'])) {
                addPlacementInfo($mysqli
                    , $_POST['placement_id']
                    , $_POST['candidate_id']
                    , $_POST['candidate_name']
                    , $_POST['candidate_mobile']
                    , $_POST['candidate_email']
                    , $_POST['candidate_dob']
                    , $_POST['job_detail_name']
                    , $_POST['job_title']
                    , $_POST['work_place_address']
                    , $_POST['approver_name']
                    , $_POST['approver_email']
                    , $_POST['placement_period_type']
                    , $_POST['placement_period_start_date']
                    , $_POST['placement_period_end_date']
                    , $_POST['billing_name']
                    , $_POST['billing_email']
                    , $_POST['billing_address']
                    , $_POST['billing_terms']
                    , $_POST['pay_rate']
                    , $_POST['charge_rate']
                    , $_POST['net_margin']
                    , $_POST['award']);
            }else{
                echo 'placement exists';
            }
        }
        if(!validateCandidateId($mysqli,$canId) && !validateCandidateByEmail($mysqli,$email)) {
            $pin = null;
            try {
                $pin = generateOnePIN($mysqli, getCandidateNoById($mysqli, $canId));
            }catch (Exception $e){
                echo 'PIN generateion Error '.$e->getMessage();
            }
            $ref_code_applied = 'MANUALLYCREATED';
            if($_POST['action'] == 'JobAdder') {
                $jobAdderId = $_POST['candidate_id'];
                $dob = date('d/m/Y', strtotime($_POST['candidate_dob']));
                $ins = $mysqli->prepare("INSERT INTO candidate(candidateId,jobadderId,clockPin,firstName,nickname,lastName,fullName,mobileNo,email,sex,dob,address,unit_no,street_number,street_name,suburb,state,postcode,username,password,foundhow,promotion,ref_code_applied,consultantId,updated_at)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die($mysqli->error);
                $ins->bind_param("siissssssssssssssssssssis", $canId, $jobAdderId, $pin, $fName, $nickname, $lName, $fullName, $mobile, $email, $gender, $dob, $address, $unit_no, $street_number, $street_name, $suburb, $state, $postcode, $canId, $hash, $foundhow, $promotion, $ref_code_applied, $consultantId, $currentDateTime) or die($mysqli->error);
            }else{
                $ins = $mysqli->prepare("INSERT INTO candidate(candidateId,clockPin,firstName,nickname,lastName,fullName,mobileNo,email,sex,dob,address,unit_no,street_number,street_name,suburb,state,postcode,username,password,foundhow,promotion,ref_code_applied,consultantId,updated_at)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die($mysqli->error);
                $ins->bind_param("sissssssssssssssssssssis", $canId,  $pin, $fName, $nickname, $lName, $fullName, $mobile, $email, $gender, $dob, $address, $unit_no, $street_number, $street_name, $suburb, $state, $postcode, $canId, $hash, $foundhow, $promotion, $ref_code_applied, $consultantId, $currentDateTime) or die($mysqli->error);
            }
            $ins->execute();
            $nrow = $ins->affected_rows;
            if ($nrow > 0) {
                if (!file_exists('./documents/' . $canId)) {
                    mkdir('./documents/' . $canId, 0777);

                }
                if(!empty($dob)){
                    updateUsernamePassword($mysqli,$canId,$dob);
                }
                addUID($mysqli,$canId);
                addQuestionnaire($mysqli,$canId);
                addSignature($mysqli,$canId);
                generateNotification('outapay@outapay.com','','','Candidate Profile Creation',DEFAULT_EMAIL,DOMAIN_NAME,$_SESSION['userSession'].' has created '.getCandidateFullName($mysqli,$canId).'('.$canId.') profile online','','');
                echo 'Inserted';
            } else {
                echo $mysqli->error;
            }
        }else{
            echo 'Existing Candidate';
        }
    }else{
        echo 'Consultant Not Set';
    }
}
?>