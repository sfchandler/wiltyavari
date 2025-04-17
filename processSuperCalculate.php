<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$action = $_POST['action'];

if($action == 'GET') {
    $transCodeType = 5;
    $wkendDateStart = $_POST['superStart'];
    $wkendDateEnd = $_POST['superEnd'];
    $limit = $_POST['limit'];
    $status = 'CLOSED';
    $sql = $mysqli->prepare("SELECT
                                  payrundetails.candidateId,
                                  payrundetails.itemType,
                                  payrundetails.category,
                                  payrundetails.amount,
                                  payrundetails.gross,
                                  payrundetails.superAnnuation,
                                  candidate.superMemberNo,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate.title,
                                  candidate.dob,
                                  candidate.sex,
                                  candidate.street_number,
                                  candidate.street_name,
                                  candidate.suburb,
                                  candidate.postcode,
                                  candidate.state,
                                  candidate.tfn,
                                  candidate.mobileNo,
                                  candidate.email,
                                  transactioncode.transCodeDesc,
                                  transactioncode.superfundSPINID,
                                  transactioncode.usi,
                                  transactioncode.superfundABN
                                FROM
                                  payrundetails
                                  LEFT OUTER JOIN candidate ON (payrundetails.candidateId = candidate.candidateId)
                                  LEFT OUTER JOIN candidate_superfund ON (payrundetails.candidateId = candidate_superfund.candidateId)
                                  LEFT OUTER JOIN transactioncode ON (candidate_superfund.transCode = transactioncode.transCode)
                                WHERE
                                  transactioncode.transCodeType = ? AND 
                                  payrundetails.weekendingDate BETWEEN ? AND ? AND 
                                  payrundetails.status = ?
                                ORDER BY
                                  payrundetails.candidateId ASC") or die($mysqli->error);
    $sql->bind_param("ssss", $transCodeType, $wkendDateStart, $wkendDateEnd, $status) or die($mysqli->error);
    $sql->execute();
    $sql->bind_result($candidateId, $itemType, $category, $amount, $gross, $superAnnuation, $superMemberNo, $firstName, $lastName, $title, $dob, $sex, $street_number, $street_name, $suburb, $postcode, $state, $tfn, $mobileNo, $email, $transCodeDesc, $superfundSPINID, $usi, $superfundABN) or die($mysqli->error);
    $sql->store_result();
    $num_of_rows = $sql->num_rows;
    $dataArray = array();
    if ($num_of_rows > 0) {
        $canId = '';
        $grossTotal = 0;
        $superTotal = 0;
        while ($sql->fetch()) {
            if (empty($canId)) {
                $canId = $candidateId;
                $grossTotal = $gross;
                $superTotal = $superAnnuation;
            } else if ($canId == $candidateId) {
                $grossTotal = $grossTotal + $gross;
                $superTotal = $superTotal + $superAnnuation;
            } else if ($canId != $candidateId) {
                $canId = $candidateId;
                $grossTotal = $gross;
                $superTotal = $superAnnuation;
            }
            if ($grossTotal > $limit) {
                $dataArray[$canId] = array('candidateId' => $candidateId, 'superMemberNo' => $superMemberNo, 'firstName' => $firstName, 'lastName' => $lastName, 'dob' => $dob, 'title' => $title, 'sex' => $sex, 'street_number' => $street_number, 'street_name' => $street_name, 'suburb' => $suburb, 'postcode' => $postcode, 'state' => $state, 'tfn' => $tfn, 'mobileNo' => $mobileNo, 'email' => $email, 'transCodeDesc' => $transCodeDesc, 'superfundSPINID' => $superfundSPINID, 'usi' => $usi, 'superfundABN' => $superfundABN, 'grossTotal' => $grossTotal, 'superAnnuationTotal' => $superTotal);
            }//date('d/m/Y',strtotime($dob))
        }
    }
    $dataSet = $dataArray;
    $row = '';
    $total_super = 0;
    $total_gross = 0;

    foreach ($dataSet as $data) {
        $total_super = $total_super + $data['superAnnuationTotal'];
        $total_gross = $total_gross + $data['grossTotal'];
        $row = $row . '<tr><td>' . $data['candidateId'] . '</td><td>' . $data['firstName'] . '</td><td>' . $data['lastName'] . '</td><td>'.$data['grossTotal'].'</td><td>' . $data['superAnnuationTotal'] . '</td><td data-canid="' . $data['candidateId'] . '" data-wkdate="' .$wkendDateStart. '" data-superamt="' . $data['superAnnuationTotal'] . '"><button class="saveBtn btn btn-success"><i class="glyphicon glyphicon-floppy-saved"></i> Save</button></td></tr>';
    }
    $row = $row . '<tr><td colspan="3"></td><td style="font-weight: bold">'.number_format($total_gross,2).'</td><td style="font-weight: bold">'.number_format($total_super,2).'</td><td></td></tr>';
    echo $row;
}elseif($action == 'SAVE'){
    $wkStartDate = $_POST['wkStartDate'];
    $canid = $_POST['canid'];
    $superamt = $_POST['superamt'];
    echo saveSuperCalculate($mysqli,$wkStartDate,$canid,$superamt);
}