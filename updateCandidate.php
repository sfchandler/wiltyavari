<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
$msgArray = array();
$jobActive = $_REQUEST['jobActive'];
$jobActiveDesc = $_REQUEST['jobActiveDesc'];
$jobDescription = $_REQUEST['jobDescription'];
$department = getDepartmentById($mysqli,$_REQUEST['departmentId']);
$expPosition = getCandidatePositionNameById($mysqli,$_REQUEST['expPosition']);
$action = $_REQUEST['action'];

$firstName = trim($_REQUEST['firstName']);
$lastName = trim($_REQUEST['lastName']);
$candidateEmail = $_REQUEST['candidateEmail'];
$candidateSex = $_REQUEST['candidateSex'];
$screenDate = $_REQUEST['screenDate'];
$suburb = $_REQUEST['suburb'];
$candidatePhone = $_REQUEST['candidatePhone'];
$candidateMobile = $_REQUEST['candidateMobile'];
$currentWrk = $_REQUEST['currentWrk'];
$howfar = $_REQUEST['howfar'];
$criminalConviction = $_REQUEST['criminalConviction'];
$convictionDescription = $_REQUEST['convictionDescription'];
$medicalCondition = $_REQUEST['medicalCondition'];
$medicalConditionDesc = $_REQUEST['medicalConditionDesc'];
$hasCar = $_REQUEST['hasCar'];
//$licenceType = $_REQUEST['licenceType'];
$residentStatus = $_REQUEST['residentStatus'];
$workType = $_REQUEST['workType'];
$overtime = $_REQUEST['overtime'];
$bookInterview = $_REQUEST['bookInterview'];
$intvwTime = $_REQUEST['intvwTime'];
$consultantId = $_REQUEST['consultantId'];

$white_card = $_REQUEST['white_card'];
$forklift = $_REQUEST['forklift'];
$mr_hr_hc = $_REQUEST['mr_hr_hc'];
$reach_forklift = $_REQUEST['reach_forklift'];
$stock_picker = $_REQUEST['stock_picker'];
$first_aid = $_REQUEST['first_aid'];
$policeCheck = $_REQUEST['policeCheck'];
$remarks = $_REQUEST['remarks'];
$safetyGear = $_REQUEST['safetyGear'];
$shiftAvailable = $_REQUEST['shiftAvailable'];
$messageid = $_REQUEST['messageid'];

define('MAIL_USERNAME', DEFAULT_EMAIL);
define('MAIL_PASSWORD', DEFAULT_EMAIL_PASSWORD);
define('MAIL_HOST','outlook.office365.com');
define('FROM_ADDRESS', DEFAULT_EMAIL);
define('FROM_NAME', DOMAIN_NAME);
define('SUBJECT', 'Interview Information');
$arr = array();
array_push($arr,$candidateEmail);
$toAddress = $arr;
$cc = array();
array_push($cc,getConsultantEmail($mysqli, $consultantId));
$ccAddress = $cc;
$emdImg = getMediaData($mysqli);
$empStatus = 'ACTIVE';
$dob = '';

if($action == 'PHONE_SCREENING'){
     try{
         $screenDate = date('Y-m-d H:i:s');
         $candidateId = getCandidateIdByEmail($mysqli, $candidateEmail);
         $updateCandidate = updateCandidateScreening($mysqli, $candidateId, $messageid, $firstName, $lastName, $candidatePhone, $candidateMobile, $candidateEmail, $candidateSex, $screenDate, $suburb, $currentWrk, $howfar, $criminalConviction, $convictionDescription, $hasCar, $residentStatus, $medicalCondition, $medicalConditionDesc, $workType, $overtime, $bookInterview, $intvwTime, $dob, $consultantId, $empStatus);
         if ($updateCandidate === 'existingUpdated') {

                 generatePhoneScreenDocument($mysqli, $candidateId, $jobDescription,$department,$expPosition, $firstName, $lastName, $candidateEmail, $candidateSex, $screenDate, $suburb, $candidatePhone, $candidateMobile, $currentWrk, $howfar, $criminalConviction, $convictionDescription, $medicalCondition, $medicalConditionDesc, $hasCar, $residentStatus, $jobActive, $jobActiveDesc, $workType, $overtime, $bookInterview, $intvwTime, $consultantId, $white_card, $forklift, $reach_forklift, $mr_hr_hc, $stock_picker, $first_aid, $safetyGear, $shiftAvailable,$policeCheck,$remarks);
                 addDiaryNote($mysqli, 'diary', $firstName, $lastName, $candidateId, 17, 3, $consultantId, 'Successful', date('Y-m-d H:i:s', strtotime($intvwTime)), '', '', '', NULL, '', '', '', $consultantId, $_SESSION['accountName']);
                 echo 'Phone Screen Completed.';

         } else {
             echo $updateCandidate;
         }
    }catch (Exception $e){
       echo $e->getMessage();
    }
 }

function generatePhoneScreenDocument($mysqli,$candidateId,$jobDescription,$department,$expPosition, $firstName,$lastName,$candidateEmail,$candidateSex, $screenDate,$suburb,$candidatePhone,$candidateMobile,$currentWrk,$howfar, $criminalConviction, $convictionDescription,$medicalCondition, $medicalConditionDesc, $hasCar, $residentStatus,$jobActive,$jobActiveDesc,$workType,$overtime, $bookInterview,$intvwTime,$consultantId,$white_card,$forklift,$reach_forklift,$mr_hr_hc,$stock_picker,$first_aid,$safetyGear,$shiftAvailable,$policeCheck,$remarks){

    require_once("includes/TCPDF-master/generalpdf/tcpdf_include.php");

    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(DOMAIN_NAME);
    $pdf->SetTitle('Candidate Phone Screening');
    $pdf->SetSubject('Chandler Phone Screening');
    $pdf->SetKeywords('Chandler Phone Screening');
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'                       generated on '.date('d/m/Y'), PDF_HEADER_STRING);
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetFont('helvetica', '', 11);
    $pdf->AddPage();

    $html = $html.'<style>
        table {
            table-layout: auto;
            border-collapse: collapse;
            width: 100%;
            font-size: 12pt;
            /*table-layout:fixed;word-wrap:break-word;*/
        }
        th{
            font-weight: bold;
            color: #2a6395;
            background-color: #c9cccf;
        }
        td{
            white-space: nowrap;
            text-align: justify;
        }
    </style><div>Candidate ID:  '.$candidateId.'</div>';
    $html = $html.'<table id="scrTable" border="1">
                    <thead>
                        <tr>
                            <th align="center">Question</th>
                            <th align="center">Answer</th>
                        </tr>
                    </thead>
                    <tbody class="scrBody">';
    $html = $html.'<tr><td>Job Description</td><td>'.$jobDescription.'</td></tr>';
    $html = $html.'<tr><td>Department</td><td>'.$department.'</td></tr>';
    $html = $html.'<tr><td>Position</td><td>'.$expPosition.'</td></tr>';
    $html = $html.'<tr><td>Name</td><td>'.$firstName.'&nbsp;'.$lastName.'</td></tr>';
    $html = $html.'<tr><td>Email</td><td>'.$candidateEmail.'</td></tr>';
    $html = $html.'<tr><td>Gender</td><td>'.$candidateSex.'</td></tr>';
    $html = $html.'<tr><td>Screen Date</td><td>'.$screenDate.'</td></tr>';
    $html = $html.'<tr><td>Suburb</td><td>'.$suburb.'</td></tr>';
    $html = $html.'<tr><td>Phone</td><td>'.$candidatePhone.'</td></tr>';
    $html = $html.'<tr><td>Mobile</td><td>'.$candidateMobile.'</td></tr>';
    $html = $html.'<tr><td>Current Work</td><td>'.$currentWrk.'</td></tr>';
    $html = $html.'<tr><td>How far are you willing to Travel for Work?</td><td>'.$howfar.'</td></tr>';
    $html = $html.'<tr><td>Do you have Prior or Pending Criminal Convictions that may affect your application?</td><td>'.$criminalConviction.'</td></tr>';
    $html = $html.'<tr><td>Criminal Conviction Description</td><td>'.$convictionDescription.'</td></tr>';
    $html = $html.'<tr><td>Do you have a valid police check(done within the last 3 years).<br>If not are happy to do one(it will only be taken off your first pay slip($49) and we will provide you a copy) </td><td>'.$policeCheck.'</td></tr>';

    $html = $html.'<tr><td>Do you have your own car and licence?</td><td>'.$hasCar.'</td></tr>';
    $html = $html.'<tr><td>What is your currnet residential status?</td><td>'.$residentStatus.'</td></tr>';
    $html = $html.'<tr><td>What other license/s and qualifications/experience do you have? </td><td>';

    $html = $html.$white_card.'&nbsp;  '.$forklift.'&nbsp;  '.$reach_forklift.'&nbsp;  '.$mr_hr_hc.'&nbsp;  '.$stock_picker.'&nbsp;  '.$first_aid;
    $html = $html.'</td></tr>';
    $html = $html.'<tr><td>Do you own or willing to get?</td><td>';
    foreach ($safetyGear as $gt) {
        $html = $html . $gt . '<br>';
    }
    $html = $html.'</td></tr>';
    $html = $html.'<tr><td>Do you have any medical conditions that might affect your work? Any lifting restrictions or back/knee conditions? Which might effect repetitive standing?</td><td>'.$medicalCondition.'</td></tr>';
    $html = $html.'<tr><td>Medical conditions Description</td><td>'.$medicalConditionDesc.'</td></tr>';
    $html = $html.'<tr><td>Are you currently registered with any jobactive provider? If yes kindly specify the provider name.</td><td>'.$jobActive.' '.$jobActiveDesc.'</td></tr>';
    $html = $html.'<tr><td>Most of our work is on-call casual work with ongoing shifts beign offered to those who perform well and help us out by going in short notice. Does this type of arrangement suit you?</td><td>'.$workType.'</td></tr>';
    $html = $html.'<tr><td>Shift Availability</td><td>';
    foreach ($shiftAvailable as $shiftAv) {
        $html = $html . $shiftAv . '<br>';
    }
    $html = $html.'</td></tr>';
    $html = $html.'<tr><td>Are you able to work overtime if required?</td><td>'.$overtime.'</td></tr>';
    $html = $html.'<tr><td>Book Candidate in for Interview?</td><td>'.$bookInterview.'</td></tr>';
    $html = $html.'<tr><td>Date and Time of Interview?</td><td>'.$intvwTime.'</td></tr>';
    $html = $html.'<tr><td>Consultant Selected</td><td>'.getConsultantName($mysqli,$consultantId).'</td></tr>';
    $html = $html.'<tr><td>Remarks: <p>'.$remarks.'</p></td></tr>';
    $html = $html.'</tbody></table>';
    $pdf->writeHTML($html, true, false, true, false, '');
    $pdf->lastPage();
    if (!file_exists('./documents/' . $candidateId)) {
        mkdir('./documents/' . $candidateId, 0777);
        chown('./documents/' . $candidateId,'chandler');
    }
    $time = time();
    $fileNamePDF = 'phonescr-'.$candidateId.'_'.$time.'.pdf';
    $filePath = __DIR__.'/documents/'.$candidateId.'/'.$fileNamePDF;
    $pdf->Output($filePath, 'F');
    updateCandidateDocs($mysqli, $candidateId, 35, $fileNamePDF, './documents/'.$candidateId.'/'.$fileNamePDF, '', '', '', 'Phone Screening');
}

?>