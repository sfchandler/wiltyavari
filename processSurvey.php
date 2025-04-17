<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$answer1 = base64_decode($_POST['answer1']);
$answer2 = base64_decode($_POST['answer2']);
$answer3 = base64_decode($_POST['answer3']);
$answer4 = base64_decode($_POST['answer4']);
/*$client = getClientNameByClientId($mysqli, base64_decode($_REQUEST['clientId']));
$department = getDepartmentById($mysqli, base64_decode($_REQUEST['deptId']));
$candidatePosition = getCandidatePositionNameById($mysqli, base64_decode($_REQUEST['positionId']));*/
if(!empty($answer1)&&!empty($answer2)&&!empty($answer3)&&!empty($answer4)) {
    $id = base64_decode($_POST['id']);
    $candidate_name = getCandidateFullName($mysqli,$id);
    $cons_id = base64_decode($_POST['cons_id']);

    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('CUSTOMER SURVEY');
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetFont('helvetica', '', 10);
    $pdf->AddPage();

    $html = $html . '<style>
            table {
                table-layout: auto;
                border-collapse: collapse;
                width: 100%;
                font-size: 10pt;
            }
            td{
                white-space: nowrap;
                text-align: justify;
            }
            </style>';
    $html = $html.'<table style="width: 50%; border: none;">
        <tbody>
          <tr>
            <td>Candidate/Employee Name:</td>
            <td>'.$candidate_name.'</td>
          </tr>
        </tbody>
      </table><br><br>';
    $html = $html . '<table style="width: 95%; border: none;">
                 <tbody>
                        <tr><td>1.How would you rate your experience with your consultant?</td></tr>
                        <tr><td>Thanks! You rated this ' . ratingStar($answer1) . ' stars.' . '</td></tr>
                        <tr><td></td></tr>
                        <tr><td>2.How helpful was your consultant during the recruitment process?</td></tr>
                        <tr><td>Thanks! You rated this ' . ratingStar($answer2) . ' stars.' . '</td></tr>
                        <tr><td></td></tr>
                        <tr><td>3.How do you rate your overall experience with Chandler Personnel?</td></tr>
                        <tr><td>Thanks! You rated this ' . ratingStar($answer3) . ' stars.' . '</td></tr>
                        <tr><td></td></tr>
                        <tr><td>4.How likely are you to refer Chandler Personnel to others?</td></tr>
                        <tr><td>Thanks! You rated this ' . ratingStar($answer4) . ' stars.' . '</td></tr>
                 </tbody></table>';
    $html = utf8_decode($html);
    @$pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
    //$fileNamePDF = 'survey_' . substr(getClientNameByClientId($mysqli, base64_decode($_REQUEST['clientId'])), 0, 5) . '_' . substr(getDepartmentById($mysqli, base64_decode($_REQUEST['deptId'])), 0, 5) . '_' . getCandidatePositionNameById($mysqli, base64_decode($_REQUEST['positionId'])) . '_' . time() . '.pdf';
    $fileNamePDF = 'survey_'.base64_decode($_REQUEST['id']).'_'. time() . '.pdf';
    $filePath = './documents/' . $id . '/' . $fileNamePDF;
    $pdf->Output(__DIR__ . '/documents/' . $id . '/' . $fileNamePDF, 'F');
    updateCandidateDocs($mysqli, $id, 61, $fileNamePDF, $filePath, '', '', '', 'SIGNED');
    //updateCandidateDocs($mysqli, $id, 61, $fileNamePDF, $filePath, base64_decode($_REQUEST['id']) . '-' . base64_decode($_REQUEST['clientId']) . '-' . base64_decode($_REQUEST['stateId']) . '-' . base64_decode($_REQUEST['deptId']) . '-' . base64_decode($_REQUEST['positionId']), '', '', 'SIGNED');
    $mailSubject = 'Customer Satisfaction survey submission';
    $mailBody = '<br>Hi ' .getConsultantName($mysqli, $cons_id).', <br><br>';
    $mailBody = $mailBody . $candidate_name . '(' . $id . ') has submitted customer satisfaction survey online. <br><br> ';
    if (($answer1 <= 2) || ($answer2 <= 2) || ($answer3 <= 2) || ($answer4 <= 2)) {
        $mailBody = $mailBody . '<span style="color: red"> One or more answers has been rated lower </span>';
        $mailSubject = '⚠️Customer Satisfaction survey submission';
    }
    $mailBody = $mailBody . '<br><br><br>';
    $mailBody = $mailBody . 'Best regards, <br>';
    $mailBody = $mailBody . DOMAIN_NAME.' Online <br>';
    generateNotification(getConsultantEmail($mysqli, $cons_id), '', '', $mailSubject, DEFAULT_EMAIL, DOMAIN_NAME, $mailBody, '', '');
    echo 'SUCCESS';
}else{
    echo 'FAILURE';
}