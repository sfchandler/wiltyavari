<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$action = $_REQUEST['action'];
if($action == 'GET') {
    $attributeId = 180;
    $donotuse = '%DO NOT USE%';
    $positionId = $_REQUEST['positionId'];
    $sql = $mysqli->prepare("SELECT 
                                  candidate.candidateId,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate.mobileNo,
                                  candidate.email,
                                  candidate.state,
                                  candidate.suburb   
                                FROM
                                  candidate
                                INNER JOIN employee_positions ON (candidate.candidateId = employee_positions.candidateId)
                                WHERE candidate.firstName NOT LIKE ? AND
                                      candidate.lastName NOT LIKE ? AND
                                 employee_positions.positionid = ?
                                ORDER BY candidate.suburb") or die($mysqli->error); //tickbox
    $sql->bind_param("ssi", $donotuse,$donotuse, $positionId) or die($mysqli->error);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($candidateId, $firstName, $lastName, $mobileNo, $email,$state,$suburb) or die($mysqli->error);
    $row = '';
    $dataArray = array();
    while ($sql->fetch()) {
        $lastDateOfWork = getLastPayWeekending($mysqli,$candidateId);
        $row = $row . '<tr>
                <td class="ck" data-mobile="'.$mobileNo.'" data-email="'.$email.'"><input type="checkbox" name="chk[]" class="chkSMS" value="'.$candidateId.'"/></td>
                <td><a href="candidateMain.php?canId='.base64_encode($candidateId).'&fname='.base64_encode($firstName).'&lname='.base64_encode($lastName).'" target="_blank">' . $candidateId . '</a></td>
                <td>' . $firstName . '</td>
                <td>' . $lastName . '</td>
                <td class="mobileNo">' . $mobileNo . '</td>
                <td class="emailList">' . $email . '</td>
                <td>'.$lastDateOfWork.'</td>
                <td>'.$state.'</td>
                <td>'.$suburb.'</td>
              </tr>';
        $rec = array('candidate'=>$candidateId,'firstName'=>$firstName,'lastName'=>$lastName,'mobileNo'=>$mobileNo,'email'=>$email,'lastDate'=>$lastDateOfWork,'state'=>$state,'suburb'=>$suburb);
        $dataArray[] = $rec;
    }
    echo $row;
}elseif ($action == 'EXCEL'){
    $donotuse = '%DO NOT USE%';
    $positionId = $_REQUEST['positionId'];
    $sql = $mysqli->prepare("SELECT 
                                  candidate.candidateId,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate.mobileNo,
                                  candidate.email,
                                  candidate.state,
                                  candidate.suburb   
                                FROM
                                  candidate
                                INNER JOIN employee_positions ON (candidate.candidateId = employee_positions.candidateId)
                                WHERE candidate.firstName NOT LIKE ? AND
                                      candidate.lastName NOT LIKE ? AND
                                 employee_positions.positionid = ?
                                ORDER BY candidate.suburb") or die($mysqli->error); //tickbox
    $sql->bind_param("ssi", $donotuse,$donotuse, $positionId) or die($mysqli->error);
    $sql->execute();
    $sql->store_result();
    $sql->bind_result($candidateId, $firstName, $lastName, $mobileNo, $email,$state,$suburb) or die($mysqli->error);
    $row = '';
    $dataArray = array();
    while ($sql->fetch()) {
        $lastDateOfWork = getLastPayWeekending($mysqli, $candidateId);
        $rec = array('candidateId' => $candidateId, 'firstName' => $firstName, 'lastName' => $lastName, 'mobileNo' => $mobileNo, 'email' => $email, 'lastDate' => $lastDateOfWork, 'state' => $state, 'suburb' => $suburb);
        $dataArray[] = $rec;
    }
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'EMPLOYEE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'MOBILE NO');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'EMAIL');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'LAST DATE OF WORK');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'STATE');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'SUBURB');
    $objPHPExcel->getActiveSheet()->setTitle('Marketing Data Filter Report');
    $rowCount = 1;
    foreach ($dataArray as $data) {
        $rowCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $data['firstName']);
        $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $data['lastName']);
        $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $data['mobileNo']);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $data['email']);
        $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $data['lastDate']);
        $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $data['state']);
        $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $data['suburb']);
    }
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $excelPath = './reports/mk_data_filter.xlsx';
    $objWriter->save('./reports/mk_data_filter.xlsx');
    echo '<a href="'.$excelPath.'" class="btn btn-success" target="_blank"><i class="fa fa-file-excel-o"></i>&nbsp; View Excel</a>';
}elseif ($action == 'EMAIL'){
    $email = $_REQUEST['email'];
    $emailBody = $_REQUEST['emailBody'];
    echo sendMarketingEmail($email,$emailBody);
}elseif ($action == 'SMS'){

    $from = '61439994685';
    $validity = 0;
    $direction = 'Outgoing';
    $mobile = $_REQUEST['mobile'];
    $smsText = $_REQUEST['smsText'];
    $consultantId = getConsultantId($mysqli,$_SESSION['userSession']);
    $response = sendCellCastSMS($smsText,rawurlencode($mobile),rawurlencode($from));
    $recipientName = getCandidateLastNameByCandidateId($mysqli,$candidateId).', '.getCandidateFirstNameByCandidateId($mysqli,$candidateId);
    $candidateId = getCandidateIdByMobileNo($mysqli,$mobile);
    $rsData = json_decode($response,true);
    $messageId = $rsData['message_id'];
    $numRecipients = $rsData['recipients'];
    $deliveryStatus = $rsData['delivery_stats']['delivered'];
    $responseStatus = $rsData['error']['code'];
    $errorDescription = $rsData['error']['description'];
    $sms = $rsData['sms'];
    $cost = $rsData['cost'];
    $sentDateTime = date('Y-m-d H:i:s');
    $ins = $mysqli->prepare("INSERT INTO smslog(message_id,
												consultantId,
												sentTimeStamp,
												candidateId,
												recipientName,
												recipientNumber,
												smsMessage,
												smsReturnData,
												sent,
												unitCost,
												smsActivity,
												smsAccount,
												smsSender,
												alertMe,
												errorDescription,
												direction)
										VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)")or die($mysqli->error);
    $ins->bind_param("sissssssssssssss", $messageId, $consultantId, $sentDateTime, $candidateId, $recipientName, $mobile, $smsText, $responseStatus, $deliveryStatus, $cost, $act, $smsAccount, $dedicatedNumber, $alertMe, $errorDescription, $direction) or die($mysqli->error);
    if($ins->execute()){
        echo 'Message sent';
    }else{
        echo 'ERROR'.$mysqli->error;
    }
}
