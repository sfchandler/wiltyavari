<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'CONSULTANT') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consultant_id = getConsultantId($mysqli, $_SESSION['userSession']);
$limitStart = $_POST['limitStart'];
$limitCount = 100;
$empStatus = $_REQUEST['empStatus'];
if ((isset($limitStart) || !empty($limitStart))) {
    $mobSearch = str_replace(' ', '', trim($_REQUEST['searchMobile']));
    $lamattinaSearch = $_REQUEST['searchLamattinaId'];

    $fNameSearch = trim($_REQUEST['searchFirstName']);
    $lNameSearch = trim($_REQUEST['searchLastName']);
    $nickNameSearch = trim($_REQUEST['searchNickName']);
    $fullNameSearch = trim($_REQUEST['searchFullName']);
    $emailSearch = str_replace(' ', '', trim($_REQUEST['searchEmail']));
    $attrId = $_REQUEST['attrId'];
    $canId = $_REQUEST['canId'];
    $chronus_id = $_REQUEST['chronus_id'];
    $querySequence;
    if ($empStatus == 'checked') {
        if (!empty($canId) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch)) {
            $searchString = "candidate.candidateId = ? AND empStatus = 'INACTIVE'";
            $querySequence = 1;
        } else if (!empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.chronus_id = ? AND empStatus = 'INACTIVE'";
            $querySequence = 2;
        } else if (!empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 3;
        } else if (!empty($lNameSearch) && empty($fNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.lastName LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 4;
        } else if (!empty($mobSearch) && empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.mobileNo = ? AND empStatus = 'INACTIVE'";
            $querySequence = 5;
        } else if (!empty($emailSearch) && empty($lNameSearch) && empty($fNameSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.email = ? AND empStatus = 'INACTIVE'";
            $querySequence = 6;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 7;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND empStatus = 'INACTIVE'";
            $querySequence = 8;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && !empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND candidate.email = ? AND empStatus = 'INACTIVE'";
            $querySequence = 9;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && !empty($emailSearch) && !empty($mobSearch) && !empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND candidate.email = ? AND candidate.candidateId = ? AND empStatus = 'INACTIVE'";
            $querySequence = 10;
        } else if (empty($lNameSearch) && !empty($fNameSearch) && !empty($emailSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.email = ? AND empStatus = 'INACTIVE'";
            $querySequence = 11;
        } else if (empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.mobileNo = ? AND empStatus = 'INACTIVE'";
            $querySequence = 12;
        } else if (!empty($lNameSearch) && empty($fNameSearch) && !empty($emailSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.lastName LIKE ? AND candidate.email = ? AND empStatus = 'INACTIVE'";
            $querySequence = 13;
        } else if (!empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.lastName LIKE ? AND candidate.mobileNo = ? AND empStatus = 'INACTIVE'";
            $querySequence = 14;
        } else if (empty($lNameSearch) && empty($fNameSearch) && !empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.email LIKE ? AND candidate.mobileNo = ? AND empStatus = 'INACTIVE'";
            $querySequence = 15;
        } else if (!empty($canId) && empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.firstName LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 16;
        } else if (!empty($canId) && !empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.lastName LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 17;
        } else if (!empty($canId) && empty($lNameSearch) && empty($fNameSearch) && !empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.email = ? AND empStatus = 'INACTIVE'";
            $querySequence = 18;
        } else if (!empty($canId) && empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.mobilNo = ? AND empStatus = 'INACTIVE'";
            $querySequence = 19;
        } else if (empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId) && !empty($attrId)) {
            $searchString = "candidate_otherlicence.otherLicenceId = ? AND empStatus = 'INACTIVE'";
            $querySequence = 20;
        } else if (empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId) && empty($attrId) && !empty($nickNameSearch)) {
            $searchString = "candidate.nickname LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 21;
        } else if (empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId) && empty($attrId) && empty($nickNameSearch) && !empty($lamattinaSearch)) {
            $searchString = "candidate.lamattinaId LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 22;
        } else if (!empty($fullNameSearch) && empty($canId) && empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.fullName LIKE ? AND empStatus = 'INACTIVE'";
            $querySequence = 23;
        }
    } else {
        if (!empty($canId) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch)) {
            $searchString = "candidate.candidateId = ?";
            $querySequence = 1;
        } else if (!empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.chronus_id = ?";
            $querySequence = 2;
        } else if (!empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ?";
            $querySequence = 3;
        } else if (!empty($lNameSearch) && empty($fNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.lastName LIKE ?";
            $querySequence = 4;
        } else if (!empty($mobSearch) && empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && empty($canId)) {
            $searchString = "candidate.mobileNo = ?";
            $querySequence = 5;
        } else if (!empty($emailSearch) && empty($lNameSearch) && empty($fNameSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.email = ?";
            $querySequence = 6;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ?";
            $querySequence = 7;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ?";
            $querySequence = 8;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && !empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND candidate.email = ?";
            $querySequence = 9;
        } else if (!empty($lNameSearch) && !empty($fNameSearch) && !empty($emailSearch) && !empty($mobSearch) && !empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.lastName LIKE ? AND candidate.mobileNo = ? AND candidate.email = ? AND candidate.candidateId = ?";
            $querySequence = 10;
        } else if (empty($lNameSearch) && !empty($fNameSearch) && !empty($emailSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.email = ?";
            $querySequence = 11;
        } else if (empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.firstName LIKE ? AND candidate.mobileNo = ?";
            $querySequence = 12;
        } else if (!empty($lNameSearch) && empty($fNameSearch) && !empty($emailSearch) && empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.lastName LIKE ? AND candidate.email = ?";
            $querySequence = 13;
        } else if (!empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.lastName LIKE ? AND candidate.mobileNo = ?";
            $querySequence = 14;
        } else if (empty($lNameSearch) && empty($fNameSearch) && !empty($emailSearch) && !empty($mobSearch) && empty($canId)) {
            $searchString = "candidate.email LIKE ? AND candidate.mobileNo = ?";
            $querySequence = 15;
        } else if (!empty($canId) && empty($lNameSearch) && !empty($fNameSearch) && empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.firstName LIKE ?";
            $querySequence = 16;
        } else if (!empty($canId) && !empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.lastName LIKE ?";
            $querySequence = 17;
        } else if (!empty($canId) && empty($lNameSearch) && empty($fNameSearch) && !empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.email = ?";
            $querySequence = 18;
        } else if (!empty($canId) && empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && !empty($mobSearch)) {
            $searchString = "candidate.candidateId = ? AND candidate.mobilNo = ?";
            $querySequence = 19;
        } else if (empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId) && !empty($attrId)) {
            $searchString = "candidate_otherlicence.otherLicenceId = ?";
            $querySequence = 20;
        } else if (empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId) && empty($attrId) && !empty($nickNameSearch)) {
            $searchString = "candidate.nickname LIKE ?";
            $querySequence = 21;
        } else if (empty($chronus_id) && empty($fNameSearch) && empty($lNameSearch) && empty($mobSearch) && empty($emailSearch) && empty($canId) && empty($attrId) && empty($nickNameSearch) && !empty($lamattinaSearch)) {
            $searchString = "candidate.lamattinaId LIKE ?";
            $querySequence = 22;
        } else if (!empty($fullNameSearch) && empty($canId) && empty($lNameSearch) && empty($fNameSearch) && empty($emailSearch) && empty($mobSearch)) {
            $searchString = "candidate.fullName = ?";
            $querySequence = 23;
        }
    }
    if (in_array($consultant_id, array(105,112))) {
        $stmt = $mysqli->prepare("SELECT DISTINCT 
				candidate.firstName,
				candidate.lastName,
				candidate.nickname,
				candidate.lamattinaId,
				candidate.address,
				candidate.mobileNo,
				candidate.email,
				candidate.consultantId,
				candidate.candidateId,
				candidate.dob,
				candidate.chronus_id,
				candidate.sex,
				candidate.type,
				candidate.supervicerId
			  FROM
				candidate
				LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
				LEFT OUTER JOIN candidate_otherlicence ON (candidate.candidateId = candidate_otherlicence.candidateId)
			  WHERE
				" . $searchString . "
			  AND candidate.consultantId = ?	
 			  LIMIT $limitStart, $limitCount") or die($mysqli->error);
        switch ($querySequence) {
            case 1:
                $stmt->bind_param("si", $canId, $consultant_id) or die($mysqli->error);
                break;
            case 2:
                $stmt->bind_param("si", $chronus_id, $consultant_id) or die($mysqli->error);
                break;
            case 3:
                $stmt->bind_param("si", $fNameSearch, $consultant_id) or die($mysqli->error);
                break;
            case 4:
                $stmt->bind_param("si", $lNameSearch, $consultant_id) or die($mysqli->error);
                break;
            case 5:
                $stmt->bind_param("si", $mobSearch, $consultant_id) or die($mysqli->error);
                break;
            case 6:
                $stmt->bind_param("si", $emailSearch, $consultant_id) or die($mysqli->error);
                break;
            case 7:
                $stmt->bind_param("ssi", $fNameSearch, $lNameSearch, $consultant_id) or die($mysqli->error);
                break;
            case 8:
                $stmt->bind_param("sssi", $fNameSearch, $lNameSearch, $mobSearch, $consultant_id) or die($mysqli->error);
                break;
            case 9:
                $stmt->bind_param("ssssi", $fNameSearch, $lNameSearch, $mobSearch, $emailSearch, $consultant_id) or die($mysqli->error);
                break;
            case 10:
                $stmt->bind_param("sssssi", $fNameSearch, $lNameSearch, $mobSearch, $emailSearch, $canId, $consultant_id) or die($mysqli->error);
                break;
            case 11:
                $stmt->bind_param("ssi", $fNameSearch, $emailSearch, $consultant_id) or die($mysqli->error);
                break;
            case 12:
                $stmt->bind_param("ssi", $fNameSearch, $mobSearch, $consultant_id) or die($mysqli->error);
                break;
            case 13:
                $stmt->bind_param("ssi", $lNameSearch, $emailSearch, $consultant_id) or die($mysqli->error);
                break;
            case 14:
                $stmt->bind_param("ssi", $lNameSearch, $mobSearch, $consultant_id) or die($mysqli->error);
                break;
            case 15:
                $stmt->bind_param("ssi", $emailSearch, $mobSearch, $consultant_id) or die($mysqli->error);
                break;
            case 16:
                $stmt->bind_param("ssi", $canId, $fNameSearch, $consultant_id) or die($mysqli->error);
                break;
            case 17:
                $stmt->bind_param("ssi", $canId, $lNameSearch, $consultant_id) or die($mysqli->error);
                break;
            case 18:
                $stmt->bind_param("ssi", $canId, $emailSearch, $consultant_id) or die($mysqli->error);
                break;
            case 19:
                $stmt->bind_param("ssi", $canId, $mobSearch, $consultant_id) or die($mysqli->error);
                break;
            case 20:
                $stmt->bind_param("ii", $attrId, $consultant_id) or die($mysqli->error);
                break;
            case 21:
                $stmt->bind_param("si", $nickNameSearch, $consultant_id) or die($mysqli->error);
                break;
            case 22:
                $stmt->bind_param("si", $lamattinaSearch, $consultant_id) or die($mysqli->error);
                break;
            case 23:
                $stmt->bind_param("si", $fullNameSearch, $consultant_id) or die($mysqli->error);
                break;
        }
    }else {
        $stmt = $mysqli->prepare("SELECT DISTINCT 
				candidate.firstName,
				candidate.lastName,
				candidate.nickname,
				candidate.lamattinaId,
				candidate.address,
				candidate.mobileNo,
				candidate.email,
				candidate.consultantId,
				candidate.candidateId,
				candidate.dob,
				candidate.chronus_id,
				candidate.sex,
				candidate.type,
				candidate.supervicerId
			  FROM
				candidate
				LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
				LEFT OUTER JOIN candidate_otherlicence ON (candidate.candidateId = candidate_otherlicence.candidateId)
			  WHERE
				" . $searchString . "
 			  LIMIT $limitStart, $limitCount") or die($mysqli->error);
        switch ($querySequence) {
            case 1:
                $stmt->bind_param("s", $canId) or die($mysqli->error);
                break;
            case 2:
                $stmt->bind_param("s", $chronus_id) or die($mysqli->error);
                break;
            case 3:
                $stmt->bind_param("s", $fNameSearch) or die($mysqli->error);
                break;
            case 4:
                $stmt->bind_param("s", $lNameSearch) or die($mysqli->error);
                break;
            case 5:
                $stmt->bind_param("s", $mobSearch) or die($mysqli->error);
                break;
            case 6:
                $stmt->bind_param("s", $emailSearch) or die($mysqli->error);
                break;
            case 7:
                $stmt->bind_param("ss", $fNameSearch, $lNameSearch) or die($mysqli->error);
                break;
            case 8:
                $stmt->bind_param("sss", $fNameSearch, $lNameSearch, $mobSearch) or die($mysqli->error);
                break;
            case 9:
                $stmt->bind_param("ssss", $fNameSearch, $lNameSearch, $mobSearch, $emailSearch) or die($mysqli->error);
                break;
            case 10:
                $stmt->bind_param("sssss", $fNameSearch, $lNameSearch, $mobSearch, $emailSearch, $canId) or die($mysqli->error);
                break;
            case 11:
                $stmt->bind_param("ss", $fNameSearch, $emailSearch) or die($mysqli->error);
                break;
            case 12:
                $stmt->bind_param("ss", $fNameSearch, $mobSearch) or die($mysqli->error);
                break;
            case 13:
                $stmt->bind_param("ss", $lNameSearch, $emailSearch) or die($mysqli->error);
                break;
            case 14:
                $stmt->bind_param("ss", $lNameSearch, $mobSearch) or die($mysqli->error);
                break;
            case 15:
                $stmt->bind_param("ss", $emailSearch, $mobSearch) or die($mysqli->error);
                break;
            case 16:
                $stmt->bind_param("ss", $canId, $fNameSearch) or die($mysqli->error);
                break;
            case 17:
                $stmt->bind_param("ss", $canId, $lNameSearch) or die($mysqli->error);
                break;
            case 18:
                $stmt->bind_param("ss", $canId, $emailSearch) or die($mysqli->error);
                break;
            case 19:
                $stmt->bind_param("ss", $canId, $mobSearch) or die($mysqli->error);
                break;
            case 20:
                $stmt->bind_param("i", $attrId) or die($mysqli->error);
                break;
            case 21:
                $stmt->bind_param("s", $nickNameSearch) or die($mysqli->error);
                break;
            case 22:
                $stmt->bind_param("s", $lamattinaSearch) or die($mysqli->error);
                break;
            case 23:
                $stmt->bind_param("s", $fullNameSearch) or die($mysqli->error);
                break;
        }
    }

    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($firstName, $lastName, $nickname, $lamattinaId, $address, $mobileNo, $email, $consultantId, $candidateId, $dob, $chronus_id, $sex, $type, $supervicerId);

    $num_of_rows = $stmt->num_rows;
    if ($num_of_rows > 0) {
        while ($stmt->fetch()) {
            $row = $row . '<tr><td class="srchCanId" data-srchCanId="' . $candidateId . '"><a href="candidateMain.php?canId=' . base64_encode($candidateId) . '&fname=' . base64_encode($firstName) . '&lname=' . base64_encode($lastName) . '&nickname=' . base64_encode($nickname) . '&lamattinaId=' . base64_encode($lamattinaId) . '&eml=' . base64_encode($email) . '&mbl=' . base64_encode($mobileNo) . '&address=' . base64_encode($address) . '&dob=' . base64_encode($dob) . '&gender=' . base64_encode($sex) . '&conId=' . base64_encode($consultantId) . '" target="_blank">' . $firstName . ' ' . $lastName . ' (' . getConsultantName($mysqli, $consultantId) . ')</a>';
            if (!in_array($consultant_id, array(111,112))) {
                $row = $row . '<label for="clientId" class="select">
                                <select name="clientId" id="clientId">';
                $row = $row . getClientsForDropdown($mysqli);
                $row = $row . '</select><i></i></label>';

                $row = $row . '<button id="makeSupervisorBtn" name="makeSupervisorBtn" class="makeSupervisorBtn btn btn-info btn-sm">Make Supervisor</button>';
                $row = $row . '<br><br><button id="genPassWord" name="genPassWord" class="genPassWord btn btn-info btn-sm">Generate Password</button></td>';
            }
            if ($type == 1) {
                $row = $row . '<td>Supervisor</td>';
            } else {
                $row = $row . '<td>Casual</td>';
            }
            $row = $row . '<td class="canType" data-canId="' . $candidateId . '">';
            if (!in_array($consultant_id, array(111,112))) {
            $row = $row . '<input id="employeeName" name="employeeName" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100% size:15" placeholder="Supervisor Name"/>
                <input type="hidden" name="empSelected" id="empSelected"/>';

                $row = $row . '<button id="assignBtn" name="assignBtn" class="assignBtn btn btn-info btn-sm">Assign Supervisor</button>';
            }
            $row = $row . '<label class="supType">' . getCandidateFirstNameByCandidateId($mysqli, getCandidateIdByNo($mysqli, $supervicerId)) . '&nbsp;' . getCandidateLastNameByCandidateId($mysqli, getCandidateIdByNo($mysqli, $supervicerId)) . '&nbsp;(' . getCandidateIdByNo($mysqli, $supervicerId) . ')</label></td></td><td>' . $candidateId . '<br><br>'.getAuditStatus($mysqli,$candidateId).'</td><td>' . $mobileNo . '</td><td>' . $address . '</td><td>' . $email . '</td></tr>';
        }
        echo $row;
    } else {
        echo '<tr><td colspan="8">No Matching records found</td></tr>';
    }
} else {
    echo '<tr><td colspan="8">No Matching records found</td></tr>';
}
?>