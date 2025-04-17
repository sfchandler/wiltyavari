<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$messageId = htmlentities($_REQUEST['messageid']);
$refcode = $_REQUEST['refcode'];
$firstName = $_REQUEST['firstName'];
$lastName = $_REQUEST['lastName'];
$emailAddress = $_REQUEST['email'];
$phoneNumber = $_REQUEST['phone'];
$matchPercentage = '';
/*if(empty($messageId))
{

}
else{*/
    $stmt = $mysqli->prepare("SELECT DISTINCT
			candidate.firstName,
			candidate.lastName,
			candidate.address,
			candidate.homePhoneNo,
			candidate.mobileNo,
			candidate.email,
			candidate.consultantId,
			candidate.messageid,
			candidate.candidateId,
			candidate.screenDate,
			candidate.sex
		  FROM
			candidate
			LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
			AND (candidate.messageid = reg_candidate.messageId)
			AND (candidate.firstName = reg_candidate.firstName)
			AND (candidate.lastName = reg_candidate.lastName)
			AND (candidate.mobileNo = reg_candidate.mobile)
			AND (candidate.address = reg_candidate.homeAddress)
			AND (candidate.email = reg_candidate.email)
		  WHERE
			candidate.firstName = ? AND 
			candidate.lastName = ? AND 
			candidate.mobileNo = ? AND 
			candidate.email = ?");

    $stmt->bind_param("ssss",$firstName,$lastName,$phoneNumber,$emailAddress) or die($mysqli->error);
    $stmt->execute();
    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
    $stmt->store_result();
    $num_of_rows = $stmt->num_rows;
    if($num_of_rows > 0){
        $matchPercentage = ((4/4)*100).'%';
    }else{
        $stmt->free_result();
        $stmt = $mysqli->prepare("SELECT DISTINCT
				candidate.firstName,
				candidate.lastName,
				candidate.address,
				candidate.homePhoneNo,
				candidate.mobileNo,
				candidate.email,
				candidate.consultantId,
				candidate.messageid,
				candidate.candidateId,
				candidate.screenDate,
				candidate.sex
			  FROM
				candidate
				LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
				AND (candidate.messageid = reg_candidate.messageId)
				AND (candidate.firstName = reg_candidate.firstName)
				AND (candidate.lastName = reg_candidate.lastName)
				AND (candidate.mobileNo = reg_candidate.mobile)
				AND (candidate.address = reg_candidate.homeAddress)
				AND (candidate.email = reg_candidate.email)
			  WHERE
				candidate.firstName = ? AND 
				candidate.lastName = ? AND 
				candidate.mobileNo = ?");
        $stmt->bind_param("sss",$firstName,$lastName,$phoneNumber) or die($mysqli->error);
        $stmt->execute();
        $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
        $stmt->store_result();
        $num_of_rows = $stmt->num_rows;
        if($num_of_rows > 0){
            $matchPercentage = ((3/4)*100).'%';
        }else{
            $stmt->free_result();
            $stmt = $mysqli->prepare("SELECT DISTINCT
					candidate.firstName,
					candidate.lastName,
					candidate.address,
					candidate.homePhoneNo,
					candidate.mobileNo,
					candidate.email,
					candidate.consultantId,
					candidate.messageid,
					candidate.candidateId,
					candidate.screenDate,
					candidate.sex
				  FROM
					candidate
					LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
					AND (candidate.messageid = reg_candidate.messageId)
					AND (candidate.firstName = reg_candidate.firstName)
					AND (candidate.lastName = reg_candidate.lastName)
					AND (candidate.mobileNo = reg_candidate.mobile)
					AND (candidate.address = reg_candidate.homeAddress)
					AND (candidate.email = reg_candidate.email)
				  WHERE
					candidate.firstName = ? AND 
					candidate.lastName = ? AND 
					candidate.email = ?");
            $stmt->bind_param("sss",$firstName,$lastName,$emailAddress) or die($mysqli->error);
            $stmt->execute();
            $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            if($num_of_rows > 0){
                $matchPercentage = ((3/4)*100).'%';
            }else{
                $stmt->free_result();
                $stmt = $mysqli->prepare("SELECT DISTINCT
						candidate.firstName,
						candidate.lastName,
						candidate.address,
						candidate.homePhoneNo,
						candidate.mobileNo,
						candidate.email,
						candidate.consultantId,
						candidate.messageid,
						candidate.candidateId,
						candidate.screenDate,
						candidate.sex
					  FROM
						candidate
						LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
						AND (candidate.messageid = reg_candidate.messageId)
						AND (candidate.firstName = reg_candidate.firstName)
						AND (candidate.lastName = reg_candidate.lastName)
						AND (candidate.mobileNo = reg_candidate.mobile)
						AND (candidate.address = reg_candidate.homeAddress)
						AND (candidate.email = reg_candidate.email)
					  WHERE
						candidate.firstName = ? AND 
						candidate.mobileNo = ? AND 
						candidate.email = ?");
                $stmt->bind_param("sss",$firstName,$phoneNumber,$emailAddress) or die($mysqli->error);
                $stmt->execute();
                $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                $stmt->store_result();
                $num_of_rows = $stmt->num_rows;
                if($num_of_rows > 0){
                    $matchPercentage = ((3/4)*100).'%';
                }else{
                    $stmt->free_result();
                    $stmt = $mysqli->prepare("SELECT DISTINCT
							candidate.firstName,
							candidate.lastName,
							candidate.address,
							candidate.homePhoneNo,
							candidate.mobileNo,
							candidate.email,
							candidate.consultantId,
							candidate.messageid,
							candidate.candidateId,
							candidate.screenDate,
							candidate.sex
						  FROM
							candidate
							LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
							AND (candidate.messageid = reg_candidate.messageId)
							AND (candidate.firstName = reg_candidate.firstName)
							AND (candidate.lastName = reg_candidate.lastName)
							AND (candidate.mobileNo = reg_candidate.mobile)
							AND (candidate.address = reg_candidate.homeAddress)
							AND (candidate.email = reg_candidate.email)
						  WHERE
							candidate.lastName = ? AND 
							candidate.mobileNo = ? AND 
							candidate.email = ?");
                    $stmt->bind_param("sss",$lastName,$phoneNumber,$emailAddress) or die($mysqli->error);
                    $stmt->execute();
                    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                    $stmt->store_result();
                    $num_of_rows = $stmt->num_rows;
                    if($num_of_rows > 0){
                        $matchPercentage = ((3/4)*100).'%';
                    }else{
                        $stmt->free_result();
                        $stmt = $mysqli->prepare("SELECT DISTINCT
								  candidate.firstName,
								  candidate.lastName,
								  candidate.address,
								  candidate.homePhoneNo,
								  candidate.mobileNo,
								  candidate.email,
								  candidate.consultantId,
								  candidate.messageid,
								  candidate.candidateId,
								  candidate.screenDate,
								  candidate.sex
								FROM
								  candidate
								  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
								  AND (candidate.messageid = reg_candidate.messageId)
								  AND (candidate.firstName = reg_candidate.firstName)
								  AND (candidate.lastName = reg_candidate.lastName)
								  AND (candidate.mobileNo = reg_candidate.mobile)
								  AND (candidate.address = reg_candidate.homeAddress)
								  AND (candidate.email = reg_candidate.email)
								WHERE
								  candidate.firstName = ? AND 
								  candidate.lastName = ?");
                        $stmt->bind_param("ss",$firstName,$lastName) or die($mysqli->error);
                        $stmt->execute();
                        $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                        $stmt->store_result();
                        $num_of_rows = $stmt->num_rows;
                        if($num_of_rows > 0){
                            $matchPercentage = ((2/4)*100).'%';
                        }else{
                            $stmt->free_result();
                            $stmt = $mysqli->prepare("SELECT DISTINCT
											  candidate.firstName,
											  candidate.lastName,
											  candidate.address,
											  candidate.homePhoneNo,
											  candidate.mobileNo,
											  candidate.email,
											  candidate.consultantId,
											  candidate.messageid,
											  candidate.candidateId,
											  candidate.screenDate,
											  candidate.sex
											FROM
											  candidate
											  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
											  AND (candidate.messageid = reg_candidate.messageId)
											  AND (candidate.firstName = reg_candidate.firstName)
											  AND (candidate.lastName = reg_candidate.lastName)
											  AND (candidate.mobileNo = reg_candidate.mobile)
											  AND (candidate.address = reg_candidate.homeAddress)
											  AND (candidate.email = reg_candidate.email)
											WHERE
											  candidate.firstName = ? AND 
											  candidate.mobileNo = ?");
                            $stmt->bind_param("ss",$firstName,$phoneNumber) or die($mysqli->error);
                            $stmt->execute();
                            $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                            $stmt->store_result();
                            $num_of_rows = $stmt->num_rows;
                            if($num_of_rows > 0){
                                $matchPercentage = ((2/4)*100).'%';
                            }else{
                                $stmt->free_result();
                                $stmt = $mysqli->prepare("SELECT DISTINCT
													  candidate.firstName,
													  candidate.lastName,
													  candidate.address,
													  candidate.homePhoneNo,
													  candidate.mobileNo,
													  candidate.email,
													  candidate.consultantId,
													  candidate.messageid,
													  candidate.candidateId,
													  candidate.screenDate,
													  candidate.sex
													FROM
													  candidate
													  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
													  AND (candidate.messageid = reg_candidate.messageId)
													  AND (candidate.firstName = reg_candidate.firstName)
													  AND (candidate.lastName = reg_candidate.lastName)
													  AND (candidate.mobileNo = reg_candidate.mobile)
													  AND (candidate.address = reg_candidate.homeAddress)
													  AND (candidate.email = reg_candidate.email)
													WHERE
													  candidate.lastName = ? AND 
													  candidate.mobileNo= ?");
                                $stmt->bind_param("ss",$lastName,$phoneNumber) or die($mysqli->error);
                                $stmt->execute();
                                $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                $stmt->store_result();
                                $num_of_rows = $stmt->num_rows;
                                if($num_of_rows > 0){
                                    $matchPercentage = ((2/4)*100).'%';
                                }else{
                                    $stmt->free_result();
                                    $stmt = $mysqli->prepare("SELECT DISTINCT
																  candidate.firstName,
																  candidate.lastName,
																  candidate.address,
																  candidate.homePhoneNo,
																  candidate.mobileNo,
																  candidate.email,
																  candidate.consultantId,
																  candidate.messageid,
																  candidate.candidateId,
																  candidate.screenDate,
																  candidate.sex
																FROM
																  candidate
																  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																  AND (candidate.messageid = reg_candidate.messageId)
																  AND (candidate.firstName = reg_candidate.firstName)
																  AND (candidate.lastName = reg_candidate.lastName)
																  AND (candidate.mobileNo = reg_candidate.mobile)
																  AND (candidate.address = reg_candidate.homeAddress)
																  AND (candidate.email = reg_candidate.email)
																WHERE
																  candidate.email = ? AND 
																  candidate.mobileNo= ?");
                                    $stmt->bind_param("ss",$emailAddress,$phoneNumber) or die($mysqli->error);
                                    $stmt->execute();
                                    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                    $stmt->store_result();
                                    $num_of_rows = $stmt->num_rows;
                                    if($num_of_rows > 0){
                                        $matchPercentage = ((2/4)*100).'%';
                                    }else{
                                        $stmt->free_result();
                                        $stmt = $mysqli->prepare("SELECT DISTINCT
																			candidate.firstName,
																			candidate.lastName,
																			candidate.address,
																			candidate.homePhoneNo,
																			candidate.mobileNo,
																			candidate.email,
																			candidate.consultantId,
																			candidate.messageid,
																			candidate.candidateId,
																			candidate.screenDate,
																			candidate.sex
																		  FROM
																			candidate
																			LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																			AND (candidate.messageid = reg_candidate.messageId)
																			AND (candidate.firstName = reg_candidate.firstName)
																			AND (candidate.lastName = reg_candidate.lastName)
																			AND (candidate.mobileNo = reg_candidate.mobile)
																			AND (candidate.address = reg_candidate.homeAddress)
																			AND (candidate.email = reg_candidate.email)
																		  WHERE
																			candidate.firstName = ?");
                                        $stmt->bind_param("s",$firstName) or die($mysqli->error);
                                        $stmt->execute();
                                        $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                        $stmt->store_result();
                                        $num_of_rows = $stmt->num_rows;
                                        if($num_of_rows > 0){
                                            $matchPercentage = ((1/4)*100).'%';
                                        }else{
                                            $stmt->free_result();
                                            $stmt = $mysqli->prepare("SELECT DISTINCT
																					  candidate.firstName,
																					  candidate.lastName,
																					  candidate.address,
																					  candidate.homePhoneNo,
																					  candidate.mobileNo,
																					  candidate.email,
																					  candidate.consultantId,
																					  candidate.messageid,
																					  candidate.candidateId,
																					  candidate.screenDate,
																					  candidate.sex
																					FROM
																					  candidate
																					  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																					  AND (candidate.messageid = reg_candidate.messageId)
																					  AND (candidate.firstName = reg_candidate.firstName)
																					  AND (candidate.lastName = reg_candidate.lastName)
																					  AND (candidate.mobileNo = reg_candidate.mobile)
																					  AND (candidate.address = reg_candidate.homeAddress)
																					  AND (candidate.email = reg_candidate.email)
																					WHERE
																					  candidate.lastName = ?");
                                            $stmt->bind_param("s",$lastName) or die($mysqli->error);
                                            $stmt->execute();
                                            $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                            $stmt->store_result();
                                            $num_of_rows = $stmt->num_rows;
                                            if($num_of_rows > 0){
                                                $matchPercentage = ((1/4)*100).'%';
                                            }else{
                                                $stmt->free_result();
                                                $stmt = $mysqli->prepare("SELECT DISTINCT
																								  candidate.firstName,
																								  candidate.lastName,
																								  candidate.address,
																								  candidate.homePhoneNo,
																								  candidate.mobileNo,
																								  candidate.email,
																								  candidate.consultantId,
																								  candidate.messageid,
																								  candidate.candidateId,
																								  candidate.screenDate,
																								  candidate.sex
																								FROM
																								  candidate
																								  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																								  AND (candidate.messageid = reg_candidate.messageId)
																								  AND (candidate.firstName = reg_candidate.firstName)
																								  AND (candidate.lastName = reg_candidate.lastName)
																								  AND (candidate.mobileNo = reg_candidate.mobile)
																								  AND (candidate.address = reg_candidate.homeAddress)
																								  AND (candidate.email = reg_candidate.email)
																								WHERE
																								  candidate.mobileNo = ?");
                                                $stmt->bind_param("s",$phoneNumber) or die($mysqli->error);
                                                $stmt->execute();
                                                $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                                $stmt->store_result();
                                                $num_of_rows = $stmt->num_rows;
                                                if($num_of_rows > 0){
                                                    $matchPercentage = ((1/4)*100).'%';
                                                }else{
                                                    $stmt->free_result();
                                                    $stmt = $mysqli->prepare("SELECT DISTINCT
																											  candidate.firstName,
																											  candidate.lastName,
																											  candidate.address,
																											  candidate.homePhoneNo,
																											  candidate.mobileNo,
																											  candidate.email,
																											  candidate.consultantId,
																											  candidate.messageid,
																											  candidate.candidateId,
																											  candidate.screenDate,
																											  candidate.sex
																											FROM
																											  candidate
																											  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																											  AND (candidate.messageid = reg_candidate.messageId)
																											  AND (candidate.firstName = reg_candidate.firstName)
																											  AND (candidate.lastName = reg_candidate.lastName)
																											  AND (candidate.mobileNo = reg_candidate.mobile)
																											  AND (candidate.address = reg_candidate.homeAddress)
																											  AND (candidate.email = reg_candidate.email)
																											WHERE
																											  candidate.email = ?");
                                                    $stmt->bind_param("s",$emailAddress) or die($mysqli->error);
                                                    $stmt->execute();
                                                    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                                    $stmt->store_result();
                                                    $num_of_rows = $stmt->num_rows;
                                                    if($num_of_rows > 0){
                                                        $matchPercentage = ((1/4)*100).'%';
                                                    }else{
                                                        $matchPercentage = ((0/4)*100).'%';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
/*}*/
$html = '';
if($num_of_rows > 0) {
    if ($stmt <> '') {
        $html = $html . '<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
        <thead>
        <tr>
            <th style="width: 10%"><i class="fa fa-percent"></i> MATCH PERCENTAGE</th>
            <th style="width: 10%"><i class="fa fa-file"></i> CANDIDATE ID</th>
            <th style="width: 30%"><i class="fa fa-user"></i> NAME</th>
            <th style="width: 20%"><i class="fa fa-phone"></i> MOBILE NO</th>
            <th style="width: 30%"><i class="fa fa-envelope"></i> EMAIL</th>
        </tr>
        </thead>
        <tbody>';
        while ($stmt->fetch()) {
            if ($matchPercentage == '25%') {
                $percentageStyle = 'red-percentage';
            } elseif ($matchPercentage == '50%') {
                $percentageStyle = 'orange-percentage';
            } elseif ($matchPercentage == '75%') {
                $percentageStyle = 'yellow-percentage';
            } elseif ($matchPercentage == '100%') {
                $percentageStyle = 'green-percentage';
            }
            $html = $html . '<tr>
                <td id="matchPercentage" class="' . $percentageStyle . '">
                    <input id="pm" value="' . $matchPercentage . '" type="hidden">
                    ' . $matchPercentage . '</td>
                <td>' . $candidateId . '</td>
                <td class="msgId" data-messageid="' . $messageId . '" fname="' . $firstName . '" lname="' . $lastName . '" canId="' . $candidateId . '" eml="' . $email . '" mbl="' . $mobileNo . '">
                <a href="candidateMain.php?messageid=' . base64_encode($messageId) . '&canId=' . base64_encode($candidateId) . '&fname=' . base64_encode($firstName) . '&lname=' . base64_encode($lastName) . '&eml=' . base64_encode($email) . '&mbl=' . base64_encode($mobileNo) . '&conId=' . base64_encode($consultantId) . '&gender=' . base64_encode($sex) . '" target="_blank">' . $firstName . ' ' . $lastName . '</a>
                </td>
                <td>' . $mobileNo . '</td>
                <td>' . $email . '</td>
            </tr>';
        }
        $html = $html . '</tbody></table>';
    }
}else{
    //$html = $html.'<div class="error">No matching records found</div>';
}
echo $html;