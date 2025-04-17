<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
date_default_timezone_set('Australia/Melbourne');

$consId = getConsultantId($mysqli, $_SESSION['userSession']);
$action = $_REQUEST['action'];
$accountName = 'jb_resume';
if($action == 'LOAD'){
    $html = '';
    $dataSet = getJobBoardResumes($mysqli,0);
    if (!empty($dataSet)) {
        foreach ($dataSet as $data) {
            $states = getStatesForResumeShortListDropdown($mysqli,$data['id']);
            $regions = getRegionsJBForDropdown($mysqli,$data['id']);
            $positions = getResumeJBShortListPositions($mysqli,$data['id']);
            $selectedGender = getJBResumeSelectedGenderForCandidate($mysqli,$data['id']);
            $html = $html.'<tr id="'.$data['id'].'" class="';
            if(!empty($selectedGender)) {
                if ($selectedGender == 'Male') {
                    $html = $html . 'cellColorMale';
                } elseif ($selectedGender == 'Female') {
                    $html = $html . 'cellColorFeMale';
                }
            }
            $html = $html.'">
                <td>'.$data['created_at'].'</td>';
            $html = $html.'<td>'.$data['applied_position'].'</td>';
            if(empty($data['photo_path'])){
                $html = $html . '<td><img src="img/avatars/default.png" class="profile_img_small" alt="" /></td>';
            }else {
                $html = $html . '<td><img src="' . $data['photo_path'] . '" class="profile_img_small" alt="" /></td>';
            }
            $html = $html.'<td>'.$data['first_name'].'</td>
                <td>'.$data['last_name'].'</td>
                <td>'.$data['email'].'</td>
                <td>'.$data['phone'].'</td>
                <td>'.$data['suburb'].'</td>
                <td>'.$data['experience'].'</td>
                <td class="states">
                    <select name="state_id" id="state_id" class="form-control-sm">
                        '.$states.'
                    </select>
                </td>
                <td class="region">
                    <select name="region" id="region" class="form-control-sm">
                        '.$regions.'
                    </select>
                </td>
                <td class="ps" style="width: 40%">
                    <div style="height: 100px; overflow-y: scroll">
                    '.$positions.'
                    </div>
                </td>
                <td class="gender">
                    <select name="gender" id="gender" class="form-control-sm">
                        <option value="Male"';
                if($data['gender'] == 'Male'){
                    $html = $html.' selected';
                }
                $html = $html.'>M</option>
                        <option value="Female"';
                if($data['gender'] == 'Female'){
                    $html = $html.' selected';
                }
                $html = $html.'>F</option>
                                </select>
                            </td>
                <td class="shortlist_para">
                    <input type="hidden" name="applied_date" id="applied_date" value="'.$data['created_at'].'"/>
                    <input type="hidden" name="ref_code" id="ref_code" value="'.$data['applied_position'].'"/>
                    <input type="hidden" name="fN" id="fN" value="'.$data['first_name'].'"/>
                    <input type="hidden" name="lN" id="lN" value="'.$data['last_name'].'"/>
                    <input type="hidden" name="email" id="email" value="'.$data['email'].'"/>
                    <input type="hidden" name="phone_number" id="phone_number" value="'.$data['phone'].'"/>
                    <input type="hidden" name="msg_id" id="msg_id" value=""/>
                    <input type="hidden" name="action" id="action" value="JOBBOARD"/>
                    <input type="hidden" name="account_name" id="account_name" value="'.$accountName.'"/>';
                    if(!empty(getPositionsByJBResumeShortListId($mysqli,$data['id']))) {
                        $html = $html.'<button type="button" class="shortlistBtn btn btn-xs btn-success"><i class="fa fa-check"></i> Talent Pool</button>';
                    }else{
                        $html = $html.'<button type="button" class="shortlistBtn btn btn-xs btn-info"><i class="fa fa-plus"></i> Talent Pool</button>';
                    }
            $html = $html.'</td>
                <td>';
                    if ($data['drivers_licence'] == 'TRUE') {
                        $html = $html.'DRIVERS LICENCE<br>';
                    }
                    if ($data['own_car'] == 'TRUE') {
                        $html = $html.'OWN CAR<br>';
                    }
                    if ($data['work_with_children'] == 'TRUE') {
                        $html = $html.'WORKING WITH CHILDREN<br>';
                    }
                    if ($data['police_check'] == 'TRUE') {
                        $html = $html.'POLICE CHECK<br>';
                    }
                    if ($data['forklift_licence'] == 'TRUE') {
                        $html = $html.'FORKLIFT LICENCE<br>';
                    }
                    if ($data['white_card'] == 'TRUE') {
                        $html = $html.'WHITECARD<br>';
                    }
                    if ($data['mr_licence'] == 'TRUE') {
                        $html = $html.'MR LICENCE<br>';
                    }
                $html = $html.'</td>';
            $html = $html.'<td>'.$data['work_rights'].'</td>';
            $html = $html.'<td>';
            if(!empty($data['resume_path'])) {
                $html = $html . '<a href="' . $data['resume_path'] . '" target="_blank">RESUME</a>';
            }
            $html = $html.'</td>';
            $html = $html.'<td>
                            <button name="unsuccessfulBtn" id="unsuccessfulBtn'.$data['id'].'" data-id="'.$data['id'].'" class="unsuccessfulBtn btn btn-xs btn-info reverse" >UNSUCCESSFUL</button>
                            <br><br><button name="crProBtn" id="crProBtn'.$data['id'].'" data-id="'.$data['id'].'" class="crProBtn btn btn-xs btn-info" >CREATE PROFILE</button>';
            $html = $html.'</td>';
            $html = $html.'</tr>';
            }
        }
    echo $html;
}elseif($action == 'LOADUNSUCCESSFUL'){
    $html = '';
    $dataSet = getJobBoardResumes($mysqli,2);
    if (!empty($dataSet)) {
        foreach ($dataSet as $data) {
            $states = getStatesForResumeShortListDropdown($mysqli,$data['id']);
            $regions = getRegionsJBForDropdown($mysqli,$data['id']);
            $positions = getResumeJBShortListPositions($mysqli,$data['id']);
            $html = $html.'<tr>
                <td>'.$data['created_at'].'</td>';
            $html = $html.'<td>'.$data['applied_position'].'</td>';
            if(empty($data['photo_path'])){
                $html = $html . '<td><img src="/img/avatars/default.png" class="profile_img" alt="" /></td>';
            }else {
                $html = $html . '<td><img src="' . $data['photo_path'] . '" class="profile_img" alt="" /></td>';
            }
            $html = $html.'<td>'.$data['first_name'].'</td>
                <td>'.$data['last_name'].'</td>
                <td>'.$data['email'].'</td>
                <td>'.$data['phone'].'</td>
                <td>'.$data['suburb'].'</td>
                <td>'.$data['experience'].'</td>
                <td class="states">
                    <select name="state_id" id="state_id" class="form-control-sm">
                        '.$states.'
                    </select>
                </td>
                <td class="region">
                    <select name="region" id="region" class="form-control-sm">
                        '.$regions.'
                    </select>
                </td>
                <td class="ps" style="width: 40%">
                    <div style="height: 100px; overflow-y: scroll">
                    '.$positions.'
                    </div>
                </td>
                <td class="gender">
                    <select name="gender" id="gender" class="form-control-sm">
                        <option value="Male">M</option>
                        <option value="Female">F</option>
                    </select>
                </td>
                <td class="shortlist_para">
                    <input type="hidden" name="applied_date" id="applied_date" value="'.$data['created_at'].'"/>
                    <input type="hidden" name="ref_code" id="ref_code" value="'.$data['applied_position'].'"/>
                    <input type="hidden" name="fN" id="fN" value="'.$data['first_name'].'"/>
                    <input type="hidden" name="lN" id="lN" value="'.$data['last_name'].'"/>
                    <input type="hidden" name="email" id="email" value="'.$data['email'].'"/>
                    <input type="hidden" name="phone_number" id="phone_number" value="'.$data['phone'].'"/>
                    <input type="hidden" name="msg_id" id="msg_id" value=""/>
                    <input type="hidden" name="action" id="action" value="JOBBOARD"/>
                    <input type="hidden" name="account_name" id="account_name" value="'.$accountName.'"/>
                    <button type="button" class="shortlistBtn btn btn-sm btn-info">ShortList</button>
                </td>
                <td>';
            if ($data['drivers_licence'] == 'TRUE') {
                $html = $html.'DRIVERS LICENCE<br>';
            }
            if ($data['own_car'] == 'TRUE') {
                $html = $html.'OWN CAR<br>';
            }
            if ($data['work_with_children'] == 'TRUE') {
                $html = $html.'WORKING WITH CHILDREN<br>';
            }
            if ($data['police_check'] == 'TRUE') {
                $html = $html.'POLICE CHECK<br>';
            }
            if ($data['forklift_licence'] == 'TRUE') {
                $html = $html.'FORKLIFT LICENCE<br>';
            }
            if ($data['white_card'] == 'TRUE') {
                $html = $html.'WHITECARD<br>';
            }
            if ($data['mr_licence'] == 'TRUE') {
                $html = $html.'MR LICENCE<br>';
            }
            $html = $html.'</td>';
            $html = $html.'<td>'.$data['work_rights'].'</td>';
            $html = $html.'<td><a href="'.$data['resume_path'].'" target="_blank">RESUME</a></td>';
            $html = $html.'<td>
                           <button name="crProBtn" id="crProBtn'.$data['id'].'" data-id="'.$data['id'].'" class="crProBtn btn btn-xs btn-info" >CREATE PROFILE</button>
                            </td>';
            $html = $html.'</tr>';
        }
    }
    echo $html;
}
elseif($action == 'UNSUCCESSFUL'){
    echo updateJobBoardResumeStatus($mysqli,$_REQUEST['id'],2);
}else {
    $firstName = base64_decode($_POST['first_name']);
    $lastName = base64_decode($_POST['last_name']);
    $gender = base64_decode($_POST['gender']);
    $email = base64_decode($_POST['candidate_email']);
    $phone = base64_decode($_POST['candidate_phone']);
    $suburb = base64_decode($_POST['suburb']);
    $experience = base64_decode($_POST['experience']);
    $drivers_licence = (base64_decode($_POST['drivers_licence']) == 'TRUE') ? 'TRUE' : 'FALSE'; // have to check
    $own_car = (base64_decode($_POST['own_car']) == 'TRUE') ? 'TRUE' : 'FALSE';
    $work_with_children = (base64_decode($_POST['work_with_children']) == 'TRUE') ? 'TRUE' : 'FALSE';
    $police_check = (base64_decode($_POST['police_check']) == 'TRUE') ? 'TRUE' : 'FALSE';
    $forklift_licence = (base64_decode($_POST['forklift_licence']) == 'TRUE') ? 'TRUE' : 'FALSE';
    $white_card_holder = (base64_decode($_POST['white_card_holder']) == 'TRUE') ? 'TRUE' : 'FALSE';
    $mr_licence = (base64_decode($_POST['mr_licence']) == 'TRUE') ? 'TRUE' : 'FALSE';
    $work_rights = base64_decode($_POST['work_rights']);
    $position = strtoupper(base64_decode($_POST['position']));
    $resume = $_FILES['resume'];
    $photo = $_FILES['photo'];
    $output_dir = './jbresume/';
    $fileName = pathinfo($_FILES['resume']['name'], PATHINFO_FILENAME);
    $fileExt = pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION);
    $newFileName = $fileName . "_" . time() . "." . $fileExt;
    $resume_path = $output_dir . $newFileName;
    $photoName = pathinfo($_FILES['photo']['name'], PATHINFO_FILENAME);
    $photoExt = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photoFileName = $photoName . "_" . time() . "." . $photoExt;
    $photo_path = $output_dir . $photoFileName;
    if (isset($firstName) && isset($lastName) && isset($email) && isset($phone) && isset($suburb) && isset($resume) && isset($position)) {
        try {
            if (!validateJobBoardResume($mysqli, $email)) {
                if (move_uploaded_file($_FILES['resume']['tmp_name'], $resume_path)) {
                    move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path);
                    if (addJobBoardResume($mysqli, $position, $firstName, $lastName, $gender, $email, $phone, $suburb, $experience, $drivers_licence, $own_car, $work_with_children, $police_check, $forklift_licence, $white_card_holder, $mr_licence, $work_rights, $resume_path,$photo_path) == 'Added') {
                        require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
                        echo generateMailNotification('NEW JOB BOARD APPLICATION SUBMISSION','','outapay@outapay.com',$firstName.' '.$lastName.' has submitted Job board application for position '.$position);
                    } else {
                        echo 'Error Saving Application Details';
                    }
                } else {
                    echo 'Error uploading resume';
                }
            } else {
                echo 'Your email address already exist please contact Chandler on 0396569777';
            }
        } catch (Exception $e) {
            echo 'Error - '.$e->getMessage();
        }
    } else {
        echo 'Please enter required fields';
    }
}