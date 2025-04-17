<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

require_once "includes/TCPDF-main/tcpdf.php";
date_default_timezone_set('Australia/Melbourne');
$candidate_id = base64_decode($_POST['id']);
$candidate_name = base64_decode($_POST['candidate_name']);
$company_name = base64_decode($_POST['company_name']);
$position = base64_decode($_POST['position']);
$department = getDepartmentById($mysqli,base64_decode($_REQUEST['deptId']));
$induction = base64_decode($_POST['induction']);
$adequate = base64_decode($_POST['adequate']);
$adequate_info = base64_decode($_POST['adequate_info']);
$co_worker = base64_decode($_POST['co_worker']);
$work_culture = base64_decode($_POST['work_culture']);
$physical_env = base64_decode($_POST['physical_env']);
$safety = base64_decode($_POST['safety']);
$safety_info = base64_decode($_POST['safety_info']);
$concern = base64_decode($_POST['concern']);
$concern_info = base64_decode($_POST['concern_info']);
$safety_scale = base64_decode($_POST['safety_scale']);
$training_info = base64_decode($_POST['training_info']);
$first_day_tasks = base64_decode($_POST['first_day_tasks']);
$supervision = base64_decode($_POST['supervision']);
$supervision_info = base64_decode($_POST['supervision_info']);
$experienced_workers = base64_decode($_POST['experienced_workers']);
$experienced_workers_info = base64_decode($_POST['experienced_workers_info']);
$discuss_concern = base64_decode($_POST['discuss_concern']);
$unsafe = base64_decode($_POST['unsafe']);
if(!validateDocumentSubmission($mysqli,$candidate_id,base64_decode($_REQUEST['clientId']),base64_decode($_REQUEST['stateId']),base64_decode($_REQUEST['deptId']),base64_decode($_REQUEST['positionId']),60)) {

    if ($discuss_concern == 'YES') {
        $consultant_discuss = '<div style="color: red">Your consultant will contact you for a confidential discussion</div>';
        $mailExclamation = '!!';
    } else {
        $consultant_discuss = '';
        $mailExclamation = '';
    }

    if (($adequate == 'NO') || ($safety == 'YES') || ($concern == 'NO') || ($supervision == 'NO') || ($experienced_workers == 'NO')) {
        $mailExclamation = '!!';
    }

    $id = base64_decode($_POST['id']);
    $cons_id = base64_decode($_POST['cons_id']);
    $imgData = $_POST['imageSrc'];
    $b64 = 'data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
    $dat = explode(',', $imgData);
    $filename = 'signature-' . time() . '.png';
    if (($fileData = base64_decode($dat[1])) === false) {
        exit('Base64 decoding error.');
    }
    $signaturePath = './documents/' . $id . '/' . $filename;
    file_put_contents($signaturePath, $fileData);

    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle('OHS QUESTIONNAIRE');
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
    $html = '';
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
    $html = $html . '<table style="width: 95%; border: none;">
                        <tbody>
                        <tr>
                            <td colspan="2"><b>Candidate Name: </b> ' . $candidate_name . '</td>
                        </tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2"><b>Company: </b>' . $company_name . '</td>
                        </tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2"><b>Position: </b>' . $position . '</td>
                        </tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2"><b>Department: </b>' . $department . '</td>
                        </tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2"><b>FIRST DAY PROCESS</b></td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">1.	Do you remember what process you went through on your first day</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <ul>
                                    <li>What sort of induction/training did you receive? </li>
                                </ul>
                                <br>
                                <span style="color: blue">' . $induction . '</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="1">
                                <ul>
                                    <li>What job tasks were you required to do on your first day?( please write as much details as you can) </li>
                                </ul>
                                <br>
                                <span style="color: blue">' . $first_day_tasks . '</span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <ul> 
                                    <li>What sort of training do you receive if you move to a different section/role/task?</li>
                                </ul>
                                <br>
                                <span style="color: blue">' . $training_info . '</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                               <span style="color: blue">' . $adequate . '</span>
                                <br>  
                                    (If NO please explain)
                                   <span style="color: red">' . $adequate_info . '</span>
                            </td>
                        </tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2"><b>WORK ENVIRONMENT</b></td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">2.	Describe the environment you are working in</td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <ul>
                                    <li>How are they treated by co-workers/supervisors? </li>
                                </ul>
                                <br>
                                <span style="color: blue">' . $co_worker . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">
                                <ul>
                                    <li>Work culture? </li>
                                </ul>
                                <br>
                                <span style="color: blue">' . $work_culture . '</span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <ul>
                                    <li>Physical environment? </li>
                                </ul>
                                <br>
                                <span style="color: blue">' . $physical_env . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">3.	Have you noticed anything or been asked to do anything that you don’t think is safe?
                                <br>
                                <span style="color: blue">' . $safety . '</span>
                                (If YES please explain)
                                <span style="color: red">' . $safety_info . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">4.	If you saw something that you believe is unsafe, what would you do?
                                <br>
                                <span style="color: blue">' . $unsafe . '</span>
                                <ul>
                                    <li>Do you think your concern would be taken seriously?</li>
                                </ul>
                                <br>
                                    <span style="color: blue">' . $concern . '</span>
                                    <br>  
                                    (If NO please explain)
                                    <span style="color: red">' . $concern_info . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">5.	On a scale of 1 to 10 how safety conscious do you think this company is?
                                <br>
                                ( 1 being unsafe and 10 being extremely safe)
                                <br>
                                   <span style="color: blue">' . $safety_scale . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2"><b>SUPERVISION</b></td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">6.	What sort of supervision do you receive?
                                <ul>
                                    <li>Do you see your supervisor often? </li>
                                </ul>
                                <br>
                                    <span style="color: blue">' . $supervision . '</span>
                                    (If NO please explain)<br> 
                                    <span style="color: red">' . $supervision_info . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">7.	Do you have experienced workers around to help you if required?
                                <br>
                                    <span style="color: blue">' . $experienced_workers . '</span><br>  
                                    (If NO please explain)<br> 
                                    <span style="color: red">' . $experienced_workers_info . '</span>
                            </td>
                        </tr>
                         <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">8.	Is there any concerns you would like to discuss with your consultant?
                                <br>
                                <span style="color: blue">' . $discuss_concern . '</span>
                                <br>  
                                <span style="color: red">' . $consultant_discuss . '</span>
                                <br>
                            </td>
                        </tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr><td colspan="2">I hereby declare that the information provided here is correct and accurate.</td></tr>
                        <tr><td colspan="2"><br></td></tr>
                        <tr>
                            <td colspan="2">
                                <img src="' . $signaturePath . '"/>    
                                <br>
                                ' . date('d/m/Y H:i:s') . '   
                            </td>
                        </tr>
                        </tbody>
                    </table>';
//$str = preg_replace('/[[:cntrl:]]/', '', $html);

    $html = utf8_decode($html);
    @$pdf->writeHTML($html);
    $pdf->lastPage();
    $fileNamePDF = 'ohs_sub_' . substr(getClientNameByClientId($mysqli, base64_decode($_REQUEST['clientId'])), 0, 5) . '_' . substr(getDepartmentById($mysqli, base64_decode($_REQUEST['deptId'])), 0, 5) . '_' . time() . '.pdf';
    $filePath = './documents/' . $id . '/' . $fileNamePDF;
    /*ob_clean();*/
    $pdf->Output(__DIR__.'/documents/' . $id . '/' . $fileNamePDF, 'F');
    updateCandidateDocs($mysqli, $id, 60, $fileNamePDF, $filePath, base64_decode($_REQUEST['id']) . '-' . base64_decode($_REQUEST['clientId']) . '-' . base64_decode($_REQUEST['stateId']) . '-' . base64_decode($_REQUEST['deptId']) . '-' . base64_decode($_REQUEST['positionId']), $mailExclamation, '', 'SIGNED');
    if (!empty($mailExclamation)) {
        $mailBody = '<span style="color: red; font-size: 16pt"><b>' . $mailExclamation . '</b></span><br><br>Candidate ' . $candidate_name . '(' . $candidate_id . '), has submitted OH&S Questionnaire for ' . $company_name . '-' . $position . ' Online<br/><br/>';
    } else {
        $mailBody = '<br>Candidate ' . $candidate_name . '(' . $candidate_id . '), has submitted OH&S Questionnaire for ' . $company_name . '-' . $position . ' Online<br/><br/>';
    }
    generateNotification(getConsultantEmail($mysqli, $cons_id), '', '', $mailExclamation . ' OH&S Questionnaire submission', DEFAULT_EMAIL, DOMAIN_NAME, $mailBody, '', '');
    echo 'SUCCESS';
}else{
    echo 'FAILURE';
}
?>