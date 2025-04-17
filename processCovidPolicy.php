<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
header('Content-Type: application/json');
$headers = apache_request_headers();
if (isset($headers['CsrfToken'])) {
    if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
        exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
} else {
    exit(json_encode(['error' => 'No CSRF token.']));
}
$imgData = $_POST['imageSrc'];

$b64 = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$someFileName = 'sigEmp-'.time().'.jpg';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}
$signaturePath = './policy/'.$someFileName;
try {
    file_put_contents($signaturePath, $fileData);
}catch (Exception $e){
    echo 'error'.$e->getMessage();
}
$candidateId = base64_decode($_POST['canId']);
$firstName = getCandidateFirstNameByCandidateId($mysqli,$candidateId);
$lastName = getCandidateLastNameByCandidateId($mysqli,$candidateId);
$q1=base64_decode($_POST['q1']);
$q2=base64_decode($_POST['q2']);
$q3=base64_decode($_POST['q3']);
$q4=base64_decode($_POST['q4']);
$q5=base64_decode($_POST['q5']);
$q6=base64_decode($_POST['q6']);
$q7=base64_decode($_POST['q7']);
$q8=base64_decode($_POST['q8']);
$conEmail = $_POST['conEmail'];
class COVIDPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 5, 60, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
    }
}
if(!empty($conEmail)&&!empty($signaturePath)) {
    try {
        $pdf = new COVIDPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(' ');
        $pdf->SetTitle('COVID POLICY INFORMATION');
        $pdf->SetSubject('COVID POLICY INFORMATION');
        $pdf->SetKeywords('COVID POLICY INFORMATION');
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(5, 30, 2);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8);
        $pdf->AddPage();
        //$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));
        $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
        $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->Line(0, 0, 0, $pdf->getPageHeight());
        $html = $html . '<style>
            table{
                width: 970px;
            }
            p{
                text-align: justify;
                padding-left: 20px;
            }</style>';
        $html = $html . '<div style="padding-left: 10px; width: 980px;" width="980px"><h2 style="text-align: center">COVID19 POLICY</h2><br>';
        $html = $html . '<div style="text-align: justify;padding-left:50px;padding-right: 50px;">It is the responsibility of '.DOMAIN_NAME.' as an external contractor to have all personnel entering work sites to complete the below assessment prior to registration. It is a requirement that if any personnel providing the answer YES to any of the assessment questions, they are to be quarantined and provide '.DOMAIN_NAME.' with a medical clearance in writing.</div>
                        <div style="text-align: left">Please answer the below questions truthfully</div><div>
                        <ul>
                            <li>Have you travelled anywhere outside Australia in the last 14 days?</li>
                            <br>
                            '.$q1.'
                            <br>
                            <li>Have you transitioned through any International airports in the last 14 days?</li>
                            <br>
                            '.$q2.'
                            <br>
                            <li>Have you or an immediate family member been tested for COVID-19?</li>
                            <br>
                            '.$q3.'
                            <br>
                            <li>Do you currently have a fever, cough, sore throat, shortness of breath, runny nose, aches and pains or feel unwell?</li>
                            <br>
                            '.$q4.'
                            <br>
                            <li>Have you been in contact with someone who has returned from overseas in the past 14 days?</li>
                            <br>
                            '.$q5.'
                            <br>
                            <li>Have you been in close contact with a confirmed case in the past 14 days?</li>
                            <br>
                            '.$q6.'
                            <br>
                            <li>Declaration of not having been to any of the NSW government listed exposure sites</li>
                            <br>
                            '.$q7.'
                            <br>
                            <li>Declaration of not crossing work with other locations/employers within the last 14 days</li>
                            <br>
                            '.$q8.'
                            <br>
                        </ul>
                        </div><div>
                        <p style="text-align: left;">If you have answered YES to ANY of these questions you must notify your consultant immediately.</p>
                        <p style="text-align: left;"><b>Declaration</b></p>
                        <p style="text-align: justify;">I declare that the information and responses I have provided are correct and true. I have read and understand the COVID-19 fact sheet. </p>
                        <p style="text-align: justify;">I understand that it is my responsibility to notify Chandler Health immediately, If I have had, or believe I may have had direct contact with a confirmed case of COVID-19</p>
                    </div><div>';
        $html = $html.'Name: <b>'.$firstName.' '.$lastName.'</b>     Date:  <b>'.date('d/m/Y').'</b>';
        $html = $html.'<br><label>Signature</label><br>';
        $html = $html.'<div id="signature"><img src="' . $signaturePath . '"/>';;
        $html = $html.'</div></div>';
        $fileName = 'empCovidPolicy_' . time();
        $filePath = './policy/' . $fileName . '.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->lastPage();
        $pdf->Output(__DIR__ . '/policy/' . $fileName . '.pdf', 'F');
        try {
            if(!file_exists('documents/'.$candidateId)){
                mkdir('documents/'.$candidateId, 0777);
                chown('./documents/' . $candidateId,'chandler');
            }
            copy($filePath,'./documents/' . $candidateId.'/'.$fileName.'.pdf');
            updateCandidateDocs($mysqli,$candidateId, 52,$fileName.'.pdf','./documents/'.$candidateId.'/'.$fileName.'.pdf','','','','SIGNED');
            if (!empty($html)) {
                echo generateCovidPolicyMail($firstName, $lastName, $conEmail, $filePath);
            }
        } catch (Exception $e1) {
            echo $e1->getMessage();
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}else{
    echo 'FAILURE';
}