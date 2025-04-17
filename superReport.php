<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 16/11/2018
 * Time: 4:31 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
date_default_timezone_set('Australia/Melbourne');

$transCodeType = 5;
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$status = 'CLOSED';
$limit = $_POST['limit'];

function getGrossBreakdown($mysqli,$canId,$startDate,$endDate,$status){
    $itemIds = array(9,11,12,13);
    $selectedIds = join("','",$itemIds);
    $breakSql = $mysqli->prepare("SELECT 
                                      payrundetails.candidateId,
                                      payrundetails.itemType,
                                      payrundetails.category,
                                      payrundetails.amount,
                                      payrundetails.superAnnuation,
                                      payrundetails.net,
                                      payrundetails.gross,
                                      payrundetails.paygTax
                                    FROM
                                      payrundetails
                                    WHERE
                                     payrundetails.candidateId = ? AND
                                      payrundetails.weekendingDate BETWEEN ? AND ? AND
                                      payrundetails.status = ? ORDER BY payrundetails.candidateId ASC") or  die($mysqli->error);
    $breakSql->bind_param("ssss",$canId,$startDate,$endDate,$status)or die($mysqli->error);
    $breakSql->execute();
    $breakSql->bind_result($candidateId,$itemType,$category,$amount,$superAnnuation,$net,$gross,$paygTax)or die($mysqli->error);
    $breakSql->store_result();
    $num_rows = $breakSql->num_rows;
    $dataArray = array();
    if($num_rows>0) {
        while ($breakSql->fetch()) {
            $row = array('candidateId'=>$candidateId,'itemType' => $itemType,'category'=>$category,'amount'=>$amount,'super'=>$superAnnuation,'net'=>$net,'gross'=>$gross,'tax'=>$paygTax);
            $dataArray[] = $row;
        }
    }
    return $dataArray;
}
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
                                  payrundetails.candidateId ASC")or die($mysqli->error);

$sql->bind_param("ssss",$transCodeType,$startDate,$endDate,$status)or die($mysqli->error);
$sql->execute();
$sql->bind_result($candidateId,$itemType,$category,$amount,$gross,$superAnnuation,$superMemberNo,$firstName,$lastName,$title,$dob,$sex,$street_number,$street_name,$suburb,$postcode,$state,$tfn,$mobileNo,$email,$transCodeDesc,$superfundSPINID,$usi,$superfundABN)or die($mysqli->error);
$sql->store_result();
$num_of_rows = $sql->num_rows;
$dataArray = array();
if($num_of_rows>0) {
    $canId = '';
    $grossTotal = 0;
    $superTotal = 0;
    while ($sql->fetch()) {
        if(empty($canId)){
            $canId = $candidateId;
            $grossTotal = $gross;
            $superTotal = $superAnnuation;
        }else if($canId == $candidateId){
            $grossTotal = $grossTotal + $gross;
            $superTotal = $superTotal + $superAnnuation;
        }else if($canId != $candidateId){
            $canId = $candidateId;
            $grossTotal = $gross;
            $superTotal = $superAnnuation;
        }
        if($grossTotal > $limit) {
            $dataArray[$canId] = array('candidateId' => $candidateId, 'superMemberNo' => $superMemberNo, 'firstName' => $firstName, 'lastName' => $lastName, 'dob' => $dob,'title'=>$title, 'sex' => $sex, 'street_number' => $street_number,'street_name'=>$street_name, 'suburb' => $suburb, 'postcode' => $postcode, 'state' => $state, 'tfn' => $tfn, 'mobileNo' => $mobileNo, 'email' => $email, 'transCodeDesc' => $transCodeDesc,
                'superfundSPINID' => $superfundSPINID,'usi'=>$usi, 'superfundABN' => $superfundABN, 'grossTotal' => $grossTotal, 'superAnnuationTotal' => $superTotal);
        }//date('d/m/Y',strtotime($dob))
    }
}
$dataSet = $dataArray;


/*foreach ($dataSet as $data) {
    echo $data['candidateId'].'&nbsp;'.$data['grossTotal'].'&nbsp;'.$data['superAnnuationTotal'].'<br>';
}*/
$objPHPExcel = new PHPExcel();

$objPHPExcel->setActiveSheetIndex(0);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'SUPER MEMBERNO');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'EMPLOYEE ID');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'FIRST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'LAST NAME');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'DOB');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'TITLE');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'GENDER');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'STREET ADDRESS');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'SUBURB');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'POSTCODE');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'STATE');
$objPHPExcel->getActiveSheet()->setCellValue('L1', 'COUNTRY');
$objPHPExcel->getActiveSheet()->setCellValue('M1', 'FIRST WEEKENDING');
$objPHPExcel->getActiveSheet()->setCellValue('N1', 'EMPLOYMENT TYPE');
$objPHPExcel->getActiveSheet()->setCellValue('O1', 'TFN');
$objPHPExcel->getActiveSheet()->setCellValue('P1', 'SUPER AMOUNT');
$objPHPExcel->getActiveSheet()->setCellValue('Q1', 'Termination Date');
$objPHPExcel->getActiveSheet()->setCellValue('R1', 'MOBILE NO');
$objPHPExcel->getActiveSheet()->setCellValue('S1', 'EMAIL');
$objPHPExcel->getActiveSheet()->setCellValue('T1', 'SPINID');
$objPHPExcel->getActiveSheet()->setCellValue('U1', 'SUPER TYPE');
$objPHPExcel->getActiveSheet()->setCellValue('V1', 'ABN');
$objPHPExcel->getActiveSheet()->setCellValue('W1', 'GROSS');

$objPHPExcel->getActiveSheet()->setTitle('SUPER CALCULATIONS');

$rowCount = 1;
$candidateArray = array();
foreach ($dataSet as $data) {
    $rowCount++;
    if(strtoupper($data['sex']) == 'MALE'){
        $title = 'Mr.';
    }else{
        $title = 'Mrs';
    }
    $candidateArray[] = array('candidateId'=>$data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('A'.$rowCount, $data['superMemberNo']);
    $objPHPExcel->getActiveSheet()->setCellValue('B'.$rowCount, $data['candidateId']);
    $objPHPExcel->getActiveSheet()->setCellValue('C'.$rowCount, $data['firstName']);
    $objPHPExcel->getActiveSheet()->setCellValue('D'.$rowCount, $data['lastName']);
    $objPHPExcel->getActiveSheet()->setCellValue('E'.$rowCount, $data['dob']);
    $objPHPExcel->getActiveSheet()->setCellValue('F'.$rowCount, $title);
    $objPHPExcel->getActiveSheet()->setCellValue('G'.$rowCount, $data['sex']);
    $objPHPExcel->getActiveSheet()->setCellValue('H'.$rowCount, $data['street_number'].' '.$data['street_name']);
    $objPHPExcel->getActiveSheet()->setCellValue('I'.$rowCount, $data['suburb']);
    $objPHPExcel->getActiveSheet()->setCellValue('J'.$rowCount, $data['postcode']);
    $objPHPExcel->getActiveSheet()->setCellValue('K'.$rowCount, $data['state']);
    $objPHPExcel->getActiveSheet()->setCellValue('L'.$rowCount, 'Australia');
    $objPHPExcel->getActiveSheet()->setCellValue('M'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('N'.$rowCount, 'Casual');
    $objPHPExcel->getActiveSheet()->setCellValue('O'.$rowCount, $data['tfn']);
    $objPHPExcel->getActiveSheet()->setCellValue('P'.$rowCount, $data['superAnnuationTotal']);
    $objPHPExcel->getActiveSheet()->setCellValue('Q'.$rowCount, '');
    $objPHPExcel->getActiveSheet()->setCellValue('R'.$rowCount, $data['mobileNo']);
    $objPHPExcel->getActiveSheet()->setCellValue('S'.$rowCount, $data['email']);
    $objPHPExcel->getActiveSheet()->setCellValue('T'.$rowCount, $data['superfundSPINID']);
    $objPHPExcel->getActiveSheet()->setCellValue('U'.$rowCount, $data['transCodeDesc']);
    $objPHPExcel->getActiveSheet()->setCellValue('V'.$rowCount, $data['superfundABN']);
    $objPHPExcel->getActiveSheet()->setCellValue('W'.$rowCount, $data['grossTotal']);

}
$objPHPExcel->createSheet(1);
$objPHPExcel->setActiveSheetIndex(1);
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATEID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'CATEGORY');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'AMOUNT');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'SUPER');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'NET');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'GROSS');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'PAYG TAX');
$rwCount = 1;
foreach ($candidateArray as $item) {
    $grossBreakdown = getGrossBreakdown($mysqli,$item['candidateId'], $startDate, $endDate, $status);
    foreach ($grossBreakdown as $gB) {
        $rwCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rwCount, $gB['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rwCount, $gB['category']);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rwCount, $gB['amount']);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rwCount, $gB['super']);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rwCount, $gB['net']);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rwCount, $gB['gross']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rwCount, $gB['tax']);
    }
}

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('./reports/superReport.xlsx');
echo './reports/superReport.xlsx';