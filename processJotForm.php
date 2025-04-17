<?php

session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
require_once "includes/TCPDF-main/tcpdf.php";
require_once('includes/fpdf182/fpdf.php');
require_once('includes/FPDI-2.3.2/src/autoload.php');
require_once('includes/FPDI-2.3.2/src/FpdfTpl.php');
ini_set('max_execution_time', 180);
ini_set('pcre.backtrack_limit', 1000000);
date_default_timezone_set('Australia/Melbourne');
use setasign\Fpdi\Fpdi;


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$allowed = array('jpg','jpeg','png','gif','doc','docx','pdf');
$canId = base64_decode($_POST['empId']);
$empEmail = getEmployeeEmail($mysqli,$canId);
if (!file_exists('documents/' . $canId)) {
    mkdir('documents/' . $canId, 0777);
}
if(base64_decode($_POST['email']) != $empEmail){
    echo 'EMAIL';
}
if(validateTFN($mysqli, base64_decode($_POST['tfn']))){
    echo 'Tax file number';
}
if(base64_decode($_POST['video_check1']) != 'X') {
    echo 'Please tick the video 1 checkbox';
}
if(base64_decode($_POST['video_check2']) != 'X'){
    echo 'Please tick the video 2 checkbox';
}
if(base64_decode($_POST['video_check3']) != 'X'){
    echo 'Please tick the video 3 checkbox';
}
if(base64_decode($_POST['video_check4']) != 'X'){
    echo 'Please tick the video 4 checkbox';
}

if(getRegPackStatus($mysqli,$canId) == '1'){
    echo 'EXISTS';
}else{
    $empCondition = 0;
    $jotFormFilled = '';
    $passportFileSubmitted = '';
    $birthFileSubmitted = '';
    $citizenFileSubmitted = '';
    $drivingFileSubmitted = '';
    $medicareFileSubmitted = '';
    $studentFileSubmitted = '';
    $policeFileSubmitted = '';
    $profileFileSubmitted = '';
    $whiteFileSubmitted = '';
    $forkliftFileSubmitted = '';
    $covid19File1Submitted = '';
    $covid19File2Submitted = '';
    $covid19File3Submitted = '';
    $txFilePath = '';
    $crFilePath = '';
    $stFilePath = '';
    $updateStatus = '';
    $imgData = $_POST['imageSrc'];
    $conEmail = base64_decode($_POST['conEmail']);
    $b64 = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
    $dat = explode(',', $imgData);
    $someFileName = 'signature-' . time() . '.jpg';
    if (($fileData = base64_decode($dat[1])) === false) {
        exit('Base64 decoding error.');
    }
    $signaturePath = './jot/' . $someFileName;

    try {
        file_put_contents($signaturePath, $fileData);
    } catch (Exception $e) {
        echo 'error' . $e->getMessage();
    }
    try {
        $firstName = base64_decode($_POST['firstName']);
        $middleName = base64_decode($_POST['middleName']);
        $lastName = base64_decode($_POST['lastName']);
        $gender = base64_decode($_POST['gender']);
        $title = base64_decode($_POST['title']);
        $paidBasis = base64_decode($_POST['paidBasis']);
        $taxClaim = base64_decode($_POST['taxClaim']);
        $taxHelp = base64_decode($_POST['taxHelp']);
        $taxResident = base64_decode($_POST['taxResident']);
        $dob = date('d/m/Y', strtotime(base64_decode($_POST['dob'])));
        $address = base64_decode($_POST['address']);
        $unit_no = base64_decode($_POST['unit_no']);
        $street_number_1 = base64_decode($_POST['street_number_1']);
        $street_name = base64_decode($_POST['street_name']);
        $suburb = base64_decode($_POST['suburb']);
        $state = base64_decode($_POST['state']);
        $postcode = base64_decode($_POST['postcode']);
        $mobile = base64_decode($_POST['mobile']);
        $email = $empEmail;
        $jobactive = base64_decode($_POST['jobActive']);
        $jobactiveDesc = base64_decode($_POST['jobActiveDesc']);
        $residentStatus = base64_decode($_POST['residentStatus']);
        $emcName = base64_decode($_POST['emcName']);
        $emcRelationship = base64_decode($_POST['emcRelationship']);
        $emcMobile = base64_decode($_POST['emcMobile']);
        $emcHomePhone = base64_decode($_POST['emcHomePhone']);
        $referee1Name = base64_decode($_POST['referee1Name']);
        $referee1CompanyName = base64_decode($_POST['referee1CompanyName']);
        $referee1Position = base64_decode($_POST['referee1Position']);
        $referee1Relationship = base64_decode($_POST['referee1Relationship']);
        $referee1Mobile = base64_decode($_POST['referee1Mobile']);
        $referee2Name = base64_decode($_POST['referee2Name']);
        $referee2CompanyName = base64_decode($_POST['referee2CompanyName']);
        $referee2Position = base64_decode($_POST['referee2Position']);
        $referee2Relationship = base64_decode($_POST['referee2Relationship']);
        $referee2Mobile = base64_decode($_POST['referee2Mobile']);
        $bankAccountName = base64_decode($_POST['bankAccountName']);
        $bankName = base64_decode($_POST['bankName']);
        $bsb = base64_decode($_POST['bsb']);
        $bankAccountNumber = base64_decode($_POST['bankAccountNumber']);
        $tfn = base64_decode($_POST['tfn']);
        $superAccountName = base64_decode($_POST['superAccountName']);
        $superFundName = base64_decode($_POST['superFundName']);
        $superMembershipNo = base64_decode($_POST['superMembershipNo']);
        $superFundAddress = base64_decode($_POST['superFundAddress']);
        $superPhoneNo = base64_decode($_POST['superPhoneNo']);
        $superWebsite = base64_decode($_POST['superWebsite']);
        $superFundABN = base64_decode($_POST['superFundABN']);
        $superFundUSI = base64_decode($_POST['superFundUSI']);
        $medicalCondition = base64_decode($_POST['medicalCondition']);
        $medConditionDesc = base64_decode($_POST['medConditionDesc']);
        $psycoCondition = base64_decode($_POST['psycoCondition']);
        $psycoConditionDesc = base64_decode($_POST['psycoConditionDesc']);
        $alergyCondition = base64_decode($_POST['alergyCondition']);
        $alergyConditionDesc = base64_decode($_POST['alergyConditionDesc']);
        $pregnantCondition = base64_decode($_POST['pregnantCondition']);
        $shoulderCondition = base64_decode($_POST['shoulderCondition']);
        $armCondition = base64_decode($_POST['armCondition']);
        $strainCondition = base64_decode($_POST['strainCondition']);
        $epilepsyCondition = base64_decode($_POST['epilepsyCondition']);
        $hearingCondition = base64_decode($_POST['hearingCondition']);
        $stressCondition = base64_decode($_POST['stressCondition']);
        $fatiqueCondition = base64_decode($_POST['fatiqueCondition']);
        $asthmaCondition = base64_decode($_POST['asthmaCondition']);
        $arthritisCondition = base64_decode($_POST['arthritisCondition']);
        $dizzinessCondition = base64_decode($_POST['dizzinessCondition']);
        $headCondition = base64_decode($_POST['headCondition']);
        $speechCondition = base64_decode($_POST['speechCondition']);
        $backCondition = base64_decode($_POST['backCondition']);
        $kneeCondition = base64_decode($_POST['kneeCondition']);
        $persistentCondition = base64_decode($_POST['persistentCondition']);
        $skinCondition = base64_decode($_POST['skinCondition']);
        $stomachStrains = base64_decode($_POST['stomachStrains']);
        $visionCondition = base64_decode($_POST['visionCondition']);
        $boneCondition = base64_decode($_POST['boneCondition']);
        $bloodCondition = base64_decode($_POST['bloodCondition']);
        $lungCondition = base64_decode($_POST['lungCondition']);
        $surgeryInformation = base64_decode($_POST['surgeryInformation']);
        $surgeryInformationDesc = base64_decode($_POST['surgeryInformationDesc']);
        $stomachCondition = base64_decode($_POST['stomachCondition']);
        $heartCondition = base64_decode($_POST['heartCondition']);
        $infectiousCondition = base64_decode($_POST['infectiousCondition']);
        $medicalTreatment = base64_decode($_POST['medicalTreatment']);
        $medicalTreatmentDesc = base64_decode($_POST['medicalTreatmentDesc']);
        $drowsinessCondition = base64_decode($_POST['drowsinessCondition']);
        $drowsinessConditionDesc = base64_decode($_POST['drowsinessConditionDesc']);
        $chronicCondition = base64_decode($_POST['chronicCondition']);
        $chronicConditionDesc = base64_decode($_POST['chronicConditionDesc']);
        $workInjury = base64_decode($_POST['workInjury']);
        $workInjuryDesc = base64_decode($_POST['workInjuryDesc']);
        $workCoverClaim = base64_decode($_POST['workCoverClaim']);
        $crouchingCondition = base64_decode($_POST['crouchingCondition']);
        $sittingCondition = base64_decode($_POST['sittingCondition']);
        $workShoulderHeight = base64_decode($_POST['workShoulderHeight']);
        $hearingConversation = base64_decode($_POST['hearingConversation']);
        $workAtHeights = base64_decode($_POST['workAtHeights']);
        $groundCondition = base64_decode($_POST['groundCondition']);
        $handlingFood = base64_decode($_POST['handlingFood']);
        $shiftWork = base64_decode($_POST['shiftWork']);
        $standingMinutes = base64_decode($_POST['standingMinutes']);
        $liftingCondition = base64_decode($_POST['liftingCondition']);
        $grippingObjects = base64_decode($_POST['grippingObjects']);
        $repetitiveMovement = base64_decode($_POST['repetitiveMovement']);
        $walkingStairs = base64_decode($_POST['walkingStairs']);
        $handTools = base64_decode($_POST['handTools']);
        $protectiveEquipment = base64_decode($_POST['protectiveEquipment']);
        $workConfinedSpaces = base64_decode($_POST['workConfinedSpaces']);
        $workHotColdEnvironment = base64_decode($_POST['workHotColdEnvironment']);
        $supercheck = base64_decode($_POST['supercheck']);
        $policeCheck = base64_decode($_POST['policeCheck']);
        $statOccupation = base64_decode($_POST['statOccupation']);
        $crimeCheck = base64_decode($_POST['crimeCheck']);
        $crimeDate1 = base64_decode($_POST['crimeDate1']);
        $crime1 = base64_decode($_POST['crime1']);
        $crimeDate2 = base64_decode($_POST['crimeDate2']);
        $crime2 = base64_decode($_POST['crime2']);
        $optionChk = base64_decode($_POST['optionChk']);
        $neverConvicted = base64_decode($_POST['neverConvicted']);
        $neverImprisonment = base64_decode($_POST['neverImprisonment']);
        $visaExpiry = base64_decode($_POST['visaExpiry']);
        $pb_suburb = base64_decode($_POST['pb_suburb']);
        $pb_state= base64_decode($_POST['pb_state']);
        $pb_country= base64_decode($_POST['pb_country']);
        $fw_first_name= base64_decode($_POST['fw_first_name']);
        $fw_middle_name= base64_decode($_POST['fw_middle_name']);
        $fw_last_name= base64_decode($_POST['fw_last_name']);
        $fw_unit_no1= base64_decode($_POST['fw_unit_no1']);
        $fw_street_number1= base64_decode($_POST['fw_street_number1']);
        $fw_street_name1= base64_decode($_POST['fw_street_name1']);
        $fw_suburb1= base64_decode($_POST['fw_suburb1']);
        $fw_state1= base64_decode($_POST['fw_state1']);
        $fw_postcode1= base64_decode($_POST['fw_postcode1']);
        $fw_country1= base64_decode($_POST['fw_country1']);
        $fw_unit_no2= base64_decode($_POST['fw_unit_no2']);
        $fw_street_number2= base64_decode($_POST['fw_street_number2']);
        $fw_street_name2= base64_decode($_POST['fw_street_name2']);
        $fw_suburb2= base64_decode($_POST['fw_suburb2']);
        $fw_state2= base64_decode($_POST['fw_state2']);
        $fw_postcode2= base64_decode($_POST['fw_postcode2']);
        $fw_country2= base64_decode($_POST['fw_country2']);
        $fw_licence= base64_decode($_POST['fw_licence']);
        $fw_licence_state= base64_decode($_POST['fw_licence_state']);
        $fw_passport_no= base64_decode($_POST['fw_passport_no']);
        $fw_passport_country= base64_decode($_POST['fw_passport_country']);
        $fw_type = base64_decode($_POST['fw_type']);
        $fw_passport_type = base64_decode($_POST['fw_passport_type']);
        $video_check1 = base64_decode($_POST['video_check1']);
        $video_check2 = base64_decode($_POST['video_check2']);
        $video_check3 = base64_decode($_POST['video_check3']);
        $video_check4 = base64_decode($_POST['video_check4']);
        $video1_status = base64_decode($_POST['video1_status']);
        $video2_status = base64_decode($_POST['video2_status']);
        $video3_status = base64_decode($_POST['video3_status']);
        $video4_status = base64_decode($_POST['video4_status']);

        class REGPDF extends TCPDF
        {
            public function Header()
            {
                $image_file = K_PATH_IMAGES . 'logo.png';
                $this->Image($image_file, 10, 5, 60, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
                $this->SetFont('helvetica', 'B', 20);
            }
        }

        try {
            $pdf = new REGPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);
            $pdf->setHeaderTemplateAutoreset(true);
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor(' ');
            $pdf->SetTitle('REG FORM INFORMATION');
            $pdf->SetSubject('REG FORM INFORMATION');
            $pdf->SetKeywords('REG FORM INFORMATION');
            $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
            $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
            $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
            $pdf->SetMargins(5, 30, 2);
            $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
            $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
            if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
                require_once(dirname(__FILE__) . '/lang/eng.php');
                $pdf->setLanguageArray($l);
            }
            $pdf->SetFont('helvetica', '', 8);
            $pdf->AddPage();
            $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));
            $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
            $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
            $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
            $pdf->Line(0, 0, 0, $pdf->getPageHeight());
            $html = '';
            $html = $html . '<style>td{ text-align: center;border: 1px solid grey;}.client{text-align: left;}.amount{text-align: right;}label{font-weight: bold}.rowTitle{text-align: left;}th{ text-align: center; font-weight: bold; border: 1px solid dimgrey}.zebra0{background-color: #f1f1f1;}.zebra1{background-color: white;}.lastPg:last-child {page-break-after:auto;}.answer{color: darkblue;}</style>';
            $html = $html . '<div align="center" style="text-align:center;font-weight: bold; font-size: 20pt">REGISTRATION FORM&nbsp;&nbsp;INFORMATION</div>';
            $html = $html . '<div width="980px"><div class="row">
                            <section class="col col-sm-3">
                                <label>First Name: </label>
                                <i class="answer">' . $firstName . '</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Middle Name: </label>
                                <i class="answer">' . $middleName . '</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Last Name: </label>
                                <i class="answer">' . $lastName . '</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Gender: </label>
                                <i class="answer">' . $gender . '</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Data of birth: </label>
                                <i class="answer">' . $dob . '</i>
                            </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Full Address: </label>
                        <i class="answer">' . $address . '</i>
                    </section>
                    <section class="col col-sm-1">
                        <label>Unit: </label>
                        <i class="answer">' . $unit_no . '</i>
                    </section>
                    <section class="col col-sm-1">
                        <label>Street Number: </label>
                        <i class="answer">' . $street_number_1 . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Street Name: </label>
                        <i class="answer">' . $street_name . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Suburb: </label>
                        <i class="answer">' . $suburb . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>State: </label>
                        <i class="answer">' . $state . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Post code: </label>
                        <i class="answer">' . $postcode . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Mobile: </label>
                        <i class="answer">' . $mobile . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Email: </label>
                        <i class="answer">' . $email . '</i>
                    </section>
                </div>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Are you currently registered with any jobactive provider? If yes kindly specify the provider name. </label>';
            $html = $html .'<i';
            if ($jobactive == 'Yes') {
                $html = $html . ' style="color:red"';
            }
            $html = $html.'>'.$jobactive.'</i>
                        <br>
                        <i class="answer">' . $jobactiveDesc . '</i>
                    </section>
                    <section class="col col-sm-3"></section>
                    <section class="col col-sm-3"></section>
               </div>
               <br>
               <div class="row">     
                    <section class="col col-sm-3">
                        <label>Residential Status: </label>
                        <i class="answer">' . $residentStatus . '</i>
                    </section>
                    <section class="col col-sm-3" id="vsExp">
                        <label>Visa Expiry Date:</label>
                        <i class="answer">'.$visaExpiry.'</i>
                    </section>
                </div>
                <br>
                <h3>Emergency Contact Information</h3>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Full Name</label>
                        <i class="answer">' . $emcName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Relationship</label>
                        <i class="answer">' . $emcRelationship . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Mobile Phone Number</label>
                        <i class="answer">' . $emcMobile . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Home Phone Number</label>
                        <i class="answer">' . $emcHomePhone . '</i>
                    </section>
                </div>
                <h3>Referee 1 Information</h3>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Referee 1 Name</label>
                        <i class="answer">' . $referee1Name . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Referee 1 CompanyName</label>
                        <i class="answer">' . $referee1CompanyName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Referee 1 Position</label>
                        <i class="answer">' . $referee1Position . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Referee 1 Relationship</label>
                        <i class="answer">' . $referee1Relationship . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Referee 1 Mobile</label>
                        <i class="answer">' . $referee1Mobile . '</i>
                    </section>
                </div>
                <h3>Referee 2 Information</h3>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Referee 2 Name</label>
                        <i class="answer">' . $referee2Name . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Referee 2 CompanyName</label>
                        <i class="answer">' . $referee2CompanyName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Referee 2 Position</label>
                        <i class="answer">' . $referee2Position . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Referee 2 Relationship</label>
                        <i class="answer">' . $referee2Relationship . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Referee 2 Mobile</label>
                        <i class="answer">' . $referee2Mobile . '</i>
                    </section>
                </div>
                <h3>Bank Account Information</h3>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Account Name</label>
                        <i class="answer">' . $bankAccountName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Bank Name</label>
                        <i class="answer">' . $bankName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>BSB</label>
                        <i class="answer">' . $bsb . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Account Number</label>
                        <i class="answer">' . $bankAccountNumber . '</i>
                    </section>
                </div>
                <h3>Tax File number Declaration Information</h3>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Tax File Number</label>
                        <i class="answer">' . $tfn . '</i>
                    </section>
                </div>
                <div class="row">
                <section class="col col-sm-6">
                    <label for="">On what basis are you paid?</label>
                    <i class="answer">'.$paidBasis.'</i>
                </section>
                </div>
                <div class="row">
                    <section class="col col-sm-6">
                    <label for=""><strong>Are you:</strong></label>
                    <i class="answer">'.$taxResident.'</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-6">
                        <label for=""><strong>Do you want to claim the tax-free threshold from this payer?</strong></label>
                    <p>Only claim the tax‑free threshold from one payer at a time, unless your total income from
                        all sources for the financial year will be less than the tax‑free threshold.</p>
                    <i class="answer">'.$taxClaim.'</i>
                    <p>Answer no here if you are a foreign resident or working holiday
                        maker, except if you are a foreign resident in receipt of an
                        Australian Government pension or allowance</p>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-6">
                    <label for="">Do you have a Higher Education Loan Program (HELP), VET Student
                        Loan (VSL), Financial Supplement (FS), Student Start-up Loan (SSL) or
                        Trade Support Loan (TSL) debt?</label>
                    <i class="answer">'.$taxHelp.'</i>
                    <p>Your payer will withhold additional amounts to cover any compulsory
                        Yes repayment that may be raised on your notice of assessment.</p>
                    </section>
                </div>
                <h3>Super Fund Information</h3>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Super check</label>
                        <i class="answer">' . $supercheck . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Super Account Name</label>
                        <i class="answer">' . $superAccountName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Super Fund Name</label>
                        <i class="answer">' . $superFundName . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Super Membership Number</label>
                        <i class="answer">' . $superMembershipNo . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Super Fund Address</label>
                        <i class="answer">' . $superFundAddress . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Super Phone No</label>
                        <i class="answer">' . $superPhoneNo . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Super Website</label>
                        <i class="answer">' . $superWebsite . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <label>Super Fund ABN</label>
                        <i class="answer">' . $superFundABN . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Super Fund USI</label>
                        <i class="answer">' . $superFundUSI . '</i>
                    </section>
                </div>
                <label>Your employer is not required to accept your choice of fund if you have not provided the appropriate documents.</label>
                <br>
                
                <div align="center"><h3>Police check Information</h3></div>
                <div class="row">
                    <section class="col col-sm-4">Do you have a Australian police clearance
                        <i class="answer">'.$policeCheck.'</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-4">
                        <p>Do you have any prior or pending criminal history?</p>
                    </section>
                </div>    
                <div class="row">
                    <section class="col col-sm-6">
                        <i class="answer">'.$crimeCheck.'</i>
                    </section>
                </div>
                <div class="statdec">
                        <h3>Statutory Declaration</h3>
                        <p>I, '.$firstName.' '.$lastName.' of '.$address.'
                            '.$statOccupation.', do solemnly and sincerely declare that:-</p>
                        <p>
                            <strong>'.$neverConvicted.'  I have never been convicted of a criminal offense in Australia
                            <br>
                             '.$neverImprisonment.'  I have never been convicted of a criminal offence and/or sentenced to imprisonment in any country other than Australia
                            </strong>
                        </p>
                        <p><strong>
                            I acknowledge that this declaration is true and correct, and I make it with the understanding and
                            belief that a person who makes a false declaration is liable to the penalties of perjury.
                            </strong>
                        </p>
                        <p>Declared at Level 9, 10 Queen St, Melbourne</p>
                        <p>this  '.date('d').' day of '.date('M').' '.date('Y').'</p>
                        <br>
                        <h3>Police Check Authority Form Declaration</h3>
                        <table id="crimeTbl" class="table table-striped table-bordered">
                            <thead>
                              <tr>
                                <th width="20%">Date</th>
                                <th width="80%">Nature of Offense</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td width="20%">'.$crimeDate1.'</td>
                                <td width="80%">'.$crime1.'</td>
                              </tr>
                              <tr>
                                  <td width="20%">'.$crimeDate2.'</td>
                                  <td width="80%">'.$crime2.'</td>
                              </tr>
                            </tbody>
                          </table>
                          <strong>Please tick the most suitable box.</strong>';
                        if ($optionChk == 'option1') {
                            $html = $html . 'I hold a previously completed National Police Check (within 3 years)';
                        }else{
                            $html = $html .'I have completed the enclosed Application Form and provided sufficient ID so that Chandler Personnel can conduct a National Police Check on my behalf';
                        }

                        $html = $html.'<div id="fit2wrk">
                    <section class="col col-sm-12">
                        <h3>Fit2Work</h3>
                        <strong>Place of birth(Required)</strong>
                        <div class="row">
                            <section class="col col-sm-4"><label>Suburb</label>
                                <i class="answer">'.$pb_suburb.'</i>
                            </section>
                            <section class="col col-sm-4"><label>State</label>
                                <i class="answer">'.$pb_state.'</i>
                            </section>
                            <section class="col col-sm-4"><label>Country</label>
                                <i class="answer">'.$pb_country.'</i>
                            </section>
                        </div>
                        <strong>Additional Details</strong>
                        <strong>Previous names(if applicable)</strong>
                        <div class="row">
                            <section class="col col-sm-3"><label>First Name:</label>
                                <i class="answer">'.$fw_first_name.'</i>
                            </section>
                            <section class="col col-sm-3"><label>Middle Name</label>
                                <i class="answer">'.$fw_middle_name.'</i>
                            </section>
                            <section class="col col-sm-3"><label>Last Name</label>
                                <i class="answer">'.$fw_last_name.'</i>
                            </section>
                            <section class="col col-sm-3"><label>Type</label>
                                <i class="answer">'.$fw_type.'</i>
                            </section>
                        </div>
                        <strong>5 Year Previous Address</strong>
                        <div class="row">                         
                            <section class="col col-sm-3">
                                <label>Unit Number</label>
                                <i class="answer">'.$fw_unit_no1.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Number</label>
                                <i class="answer">'.$fw_street_number1.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Name</label>
                                <i class="answer">'.$fw_street_name1.'</i>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label>Suburb</label>
                                <i class="answer">'.$fw_suburb1.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>State</label>
                                <i class="answer">'.$fw_state1.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Post code</label>
                                <i class="answer">'.$fw_postcode1.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Country</label>
                                <i class="answer">'.$fw_country1.'</i>
                            </section>
                        </div>
                        <strong>5 Year Previous Address</strong>
                        <div class="row">
                          <section class="col col-sm-3">
                                <label>Unit Number</label>
                                <i class="answer">'.$fw_unit_no2.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Number</label>
                                <i class="answer">'.$fw_street_number2.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Name</label>
                                <i class="answer">'.$fw_street_name2.'</i>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label>Suburb</label>
                                <i class="answer">'.$fw_suburb2.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>State</label>
                                <i class="answer">'.$fw_state2.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Post code</label>
                                <i class="answer">'.$fw_postcode2.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label>Country</label>
                                <i class="answer">'.$fw_country2.'</i>
                            </section>
                        </div>
                        <strong>Documents</strong>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label for="">Aust. Drivers Licence No.</label>
                                <i class="answer">'.$fw_licence.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label for="">State/Territory</label>
                                <i class="answer">'.$fw_licence_state.'</i>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label for="">Passport No.</label>
                                <i class="answer">'.$fw_passport_no.'</i>
                            </section>
                            <section class="col col-sm-3">
                                <label for="">Passport Country</label>
                                <i class="answer">'.$fw_passport_country.'</i>
                            </section>
                             <section class="col col-sm-3">
                                <label for="">Passport Type</label>
                                <i class="answer">'.$fw_passport_type.'</i>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-12">
                                <p>'.$video_check1.' I confirm that I have watched and understood the work health and safety module video 1</p>
                                <p>'.$video_check2.' I confirm that I have watched and understood the work health and safety module video 2</p>
                                <p>'.$video_check3.' I confirm that I have watched and understood the work health and safety module video 3</p>
                                <p>'.$video_check4.' I confirm that I have watched and understood the work health and safety module video 4</p>
                            </section>
                        </div>
                    </section>
                </div>';

            $html = $html . '</div>
                <br>
                <div align="center"><h3>Health Questionnaire</h3></div>
                <hr>
                <p> Health and safety of our employees is of utmost importance to '.DOMAIN_NAME.'. This questionnaire is designed to assist us in ensuring that our employees are only placed in the assignments which they are capable of performing efficiently and in a safely manner.</p>
                <p>Please read this document carefully and discuss any queries that you may have prior to completing the form with your respective '.DOMAIN_NAME.' Consultants.</p>
                <p><b>IMPORTANT:</b> The information obtained in this questionnaire will be treated in strict confidence and will only be used in conjunction with the requirements of an assignment.</p>
                <p><b>INJURY DECLARATION</b></p>
                <p>You are required to disclose to '.DOMAIN_NAME.' Consultants any or all existing or pre-existing injuries, illnesses or diseases suffered by you which could be accelerated, aggravated, deteriorate or recur by you performing the responsibilities associated with the employment for which you are applying with '.DOMAIN_NAME.' Consultants.</p>
                <p>If you fail to disclose this information or if you provide false and misleading information in relation to any pre-existing injury/condition you and your dependents may not be entitled to any form of workers’ compensation and this may also constitute grounds for disciplinary action or dismissal.</p>
                <br>
                <h3>Section A : Health History</h3>
                <label>Please select the appropriate answer:</label>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Have you ever been medically retired on the grounds of ill health?</label>
                        <i';
            if ($medicalCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $medicalCondition . '</i>
                        <br>
                        <i class="answer">' . $medConditionDesc . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Do you have a physical or psychological condition that might preclude you from some work duties or certain workplace environments (eg. asthma, Hay fever, vertigo)?</label>
                        <i';
            if ($psycoCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $psycoCondition . '</i>
                        <br>
                        <i class="answer">' . $psycoConditionDesc . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Do you suffer from any allergies?</label>
                        <br>
                        <i';
            if ($alergyCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $alergyCondition . '</i>
                        <br>
                        <i class="answer">' . $alergyConditionDesc . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Some work duties and workplace environments may not be advisable for pregnant women. If you wish to indicate that you are pregnant you may do so voluntarily here.</label>
                        <i';
            if ($pregnantCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $pregnantCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any neck or shoulder injuries/pain</label>
                        <br>
                        <i';
            if ($shoulderCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $shoulderCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any arm, hand, elbow or wrist injury/pain</label>
                        <br>
                        <i';
            if ($armCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $armCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Repetitive strains or overuse injury</label>
                        <br>
                        <i';
            if ($strainCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $strainCondition . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Epilepsy, fits, seizures, blackouts</label>
                        <br>
                        <i';
            if ($epilepsyCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $epilepsyCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Loss of hearing, Impaired Hearing</label>
                        <br>
                        <i';
            if ($hearingCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $hearingCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Stress/Anxiety or nervous disorder</label>
                        <br>
                        <i';
            if ($stressCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $stressCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Fatigue / tiredness related issues</label>
                        <br>
                        <i';
            if ($fatiqueCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $fatiqueCondition . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Asthma or other respiratory/breathing problems</label>
                        <br>
                        <i';
            if ($asthmaCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $asthmaCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Arthritis, rheumatism</label>
                        <br>
                        <i';
            if ($arthritisCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $arthritisCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Dizziness, fainting, vertigo</label>
                        <br>
                        <i';
            if ($dizzinessCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $dizzinessCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Head Injury</label>
                        <br>
                        <i';
            if ($headCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $headCondition . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Speech impairment</label>
                        <br>
                        <i';
            if ($speechCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $speechCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any back injury/pain e.g. Scoliosis</label>
                        <br>
                        <i';
            if ($backCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $backCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any knee, leg or ankle pain/injury</label>
                        <br>
                        <i';
            if ($kneeCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $kneeCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Persistent or frequent headaches, migraines</label>
                        <br>
                        <i';
            if ($persistentCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $persistentCondition . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Skin disorders, dermatitis, eczema</label>
                        <br>
                        <i';
            if ($skinCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $skinCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any stomach strains/hernias etc.</label>
                        <br>
                        <i';
            if ($stomachStrains == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $stomachStrains . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Difficulty with vision or sight in either eye, Impaired Vision</label>
                        <br>
                        <i';
            if ($visionCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $visionCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any problems with bones/joints or muscles</label>
                        <br>
                        <i';
            if ($boneCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $boneCondition . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>High / Low blood pressure</label>
                        <br>
                        <i';
            if ($bloodCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $bloodCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Lung disorders/ Nerve disorders</label>
                        <br>
                        <i';
            if ($lungCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $lungCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Any operations or surgery? If Yes Please give details</label>
                        <br>
                        <i';
            if ($surgeryInformation == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $surgeryInformation . '<br>'.$surgeryInformationDesc.'</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Stomach problems, ulcers</label>
                        <br>
                        <i';
            if ($stomachCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $stomachCondition . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Heart trouble, angina</label>
                        <br>
                        <i';
            if ($heartCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $heartCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Infectious disease</label>
                        <br>
                        <i';
            if ($infectiousCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $infectiousCondition . '</i>
                    </section>
                </div>
                <br>
                <h3>Section B : Medical Details</h3>
                <label>Please select the appropriate answer:</label>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Are you currently receiving any medical treatment for illness, injury or medical condition?</label>
                        <br>
                        <i';
            if ($medicalTreatment == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $medicalTreatment . '</i>
                        <br>
                        <i class="answer">' . $medicalTreatmentDesc . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Are you taking any medication that has the potential to cause drowsiness or affect your work performance (including operating machinery?</label>
                        <br>
                        <i';
            if ($drowsinessCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $drowsinessCondition . '</i>
                        <br>
                        <i class="answer">' . $drowsinessConditionDesc . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Do you have any pre-existing and/or chronic and/or long term injuries or illness?</label>
                        <br>
                        <i';
            if ($chronicCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $chronicCondition . '</i>
                        <br>
                        <i class="answer">' . $chronicConditionDesc . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Have you ever had a work related injury?</label>
                        <br>
                        <i';
            if ($workInjury == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $workInjury . '</i>
                        <br>
                        <i class="answer">' . $workInjuryDesc . '</i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        
                    </section>
                </div>
                <br>
                <h3>Section C : Physical Abilities</h3>
                <label>Please indicate whether you have, or could have, difficulties performing any of the following activities.</label>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Crouching/bending/ Kneeling (repeatedly)</label>
                        <br>
                        <i';
            if ($crouchingCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $crouchingCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Sitting for up to 30 minutes</label>
                        <br>
                        <i';
            if ($sittingCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $sittingCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Working above shoulder height</label>
                        <br>
                        <i';
            if ($workShoulderHeight == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $workShoulderHeight . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Hearing a normal conversation</label>
                        <br>
                        <i';
            if ($hearingConversation == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $hearingConversation . '</i>
                    </section>
                </div>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Climbing a ladder/working at heights</label>
                        <br>
                        <i';
            if ($workAtHeights == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $workAtHeights . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Walking/working on uneven ground</label>
                        <br>
                        <i';
            if ($groundCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $groundCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Handling meat and/or food produce</label>
                        <br>
                        <i';
            if ($handlingFood == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $handlingFood . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Performing Shift Work</label>
                        <br>
                        <i';
            if ($shiftWork == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $shiftWork . '</i>
                    </section>
                </div>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Standing for 30 minutes</label>
                        <br>
                        <i';
            if ($standingMinutes == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $standingMinutes . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Lifting objects weighing 15 kilograms or more</label>
                        <br>
                        <i';
            if ($liftingCondition == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $liftingCondition . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Gripping objects firmly with both hands</label>
                        <br>
                        <i';
            if ($grippingObjects == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $grippingObjects . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Repetitive movement of hands or arms</label>
                        <br>
                        <i';
            if ($repetitiveMovement == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $repetitiveMovement . '</i>
                    </section>
                </div>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Walking up and down stairs</label>
                        <br>
                        <i';
            if ($walkingStairs == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $walkingStairs . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Using hand tools/operating machinery</label>
                        <br>
                        <i';
            if ($handTools == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $handTools . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Wearing personal protective equipment (PPE)</label>
                        <br>
                        <i';
            if ($protectiveEquipment == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $protectiveEquipment . '</i>
                    </section>
                </div>
                <br>
                <div class="row">
                    <section class="col col-sm-3">
                        <br>
                        <label>Working in confined spaces or underground</label>
                        <br>
                        <i';
            if ($workConfinedSpaces == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $workConfinedSpaces . '</i>
                    </section>
                    <section class="col col-sm-3">
                        <br>
                        <label>Working in hot/cold environments inc. refrigerated storage</label>
                        <br>
                        <i';
            if ($workHotColdEnvironment == 'Yes') {
                $html = $html . ' style="color:red"';
                $empCondition = 1;
            }
            $html = $html . '>' . $workHotColdEnvironment . '</i>
                    </section>
                </div>
                <br>
                <br>
                <div align="center"><h3>PRIVACY POLICY</h3></div>
                <div class="row">
                    <section class="col col-lg-6">
                        <p>Your privacy is important to Chandler Services. It is our commitment to protect the privacy of the information of our employees and others. This statement outlines our privacy policy and how we manage and disclose personal information.</p>
                        <br>
                        <div><b>What is your personal information?</b></div>
                        <p>Personal information is any information or an opinion (whether true or not) about you. It may range from the very sensitive (eg. criminal history, medical history or condition) to the everyday information (eg. full name, address, and phone number). It would include the opinions of others about your work performance (whether true or not), your work experience and qualifications, aptitude test results and other information obtained by us in connection with your possible work placements.</p>
                        <div><b>Why is your personal information collected?</b></div>
                        <p>Your personal information will be collected by the experienced team of consultants at Chandler Services. It is collected and held to assist Chandler Services in determining your suitability for work placements. It is also used for staff management and in order to identify any training requirements.</p>
                        <div><b>How will your information be collected?</b></div>
                        <p>Personal information will be collected from you directly when you fill out and submit one of our registration forms or any other information in connection with your application to us for registration. Personal information will also be collected when:</p>
                        <p> - we receive any reference about you</p>
                        <p> - we receive the results of any competency or medical test</p>
                        <p> - we receive performance feedback (whether positive or negative)</p>
                        <p> - we receive any complaint from or about you in the workplace</p>
                        <p> - we receive any information about a workplace accident in which you are involved</p>
                        <p> - we receive any information about any insurance investigation, litigation, registration or professional disciplinary matter, criminal matter, inquest or inquiry in which you are involved</p>
                        <p> - you provide us with any additional information about you</p>
                        <div><b>How will your information be used?</b></div>
                        <p>Your personal information may be used in connection with:</p>
                        <p> - your actual or possible work placement</p>
                        <p> - your performance appraisals our assessment of your ongoing performance and prospects</p>
                        <p> - any test or assessment (including medical tests and assessments) that you might be required to undergo</p>
                        <p> - our identification of your training needs</p>
                        <p> - any workplace rehabilitation</p>
                    </section>
                    <section class="col col-lg-6">
                        <p> - our management of any complaint, investigation or inquiry in which you are involved</p>
                        <p> - any insurance claim or proposal that requires disclosure of your personal information</p>
                        <div><b>Who might your personal information be disclosed to?</b></div>
                        <p> - potential and actual employers and clients of Chandler Services</p>
                        <p> - Referees</p>
                        <p> - companies within the Chandler Services Group</p>
                        <p> - our insurers</p>
                        <p> - a professional association or registration body that has a proper interest in the disclosure of your personal information</p>
                        <p> - a workers compensation body</p>
                        <p> - our contractors and suppliers (eg. IT contractors and database designers)</p>
                        <p> - any person with a lawful entitlement to obtain the information</p>
                        <div><b>How can you gain access to your personal information that we hold?</b></div>
                        <p>Under privacy legislation you have a right to see any personal information about you that we may hold. If you are able to establish that any of the information that we hold about you is not accurate, complete and up to date we will take reasonable steps to correct this.</p>
                        <div><b>How is your personal information stored?</b></div>
                        <p>Chandler Services takes all reasonable steps to ensure that information held in paper or electronic form is secure, and that it is protected from misuse, loss, unauthorized access, modification or disclosure. All staff at Chandler Services will take reasonable steps to ensure that personal information is only used for recruitment purposes or disclosed to other organisations to the extent necessary for our business purposes. When personal information is no longer required it will be destroyed.</p>
                        <div><b>Changes to our Privacy Policy?</b></div>
                        <p>If any changes are made to Chandler Services’ Privacy Policy, they will be posted on our website so that you are always kept up to date about the information we might use and whether it will be disclosed to anyone.</p>
                        <div><b>Inquiries or Feedback?</b></div>
                        <p>If you have any questions or concerns about our commitment to your privacy, please don’t hesitate to contact us on 1300 499 449.</p>
                    </section>
                </div>
                <br pagebreak="true"/>
                <div class="row">
                    <section class="col col-sm-12">
                        <p>I HAVE READ AND UNDERSTOOD THE ABOVE PRIVACY POLICY.</p>
                        <br>
                        <label>Candidate Signature</label>
                        <div id="signature"><img src="' . $signaturePath . '"/>';
            $html = $html . '</div>
                    </section>
                </div>
            </div>';
            $fileName = 'reg_'.$canId.'_'. time();
            $filePath = './jot/' . $fileName . '.pdf';
            //$pdf->writeHTML($html, true, false, false, false, '');
            $html = utf8_decode($html);
            @$pdf->writeHTML($html);
            $pdf->lastPage();
            $pdf->Output(__DIR__ . '/jot/' . $fileName . '.pdf', 'F');
            $jotFormFilled = $filePath;
            if (!file_exists('documents/' . $canId)) {
                mkdir('documents/' . $canId, 0777);
            }
            if(copy($jotFormFilled, './documents/' . $canId . '/' . $fileName . '.pdf')) {
                unlink(__DIR__ . '/jot/' . $fileName . '.pdf');
                unlink(__DIR__.'/jot/'.$signaturePath);
            }
            if(!empty($html)) {
                if (file_exists('./documents/' . $canId . '/' . $fileName . '.pdf')) {
                    try {
                        updateCandidateDocs($mysqli, $canId, 23, $fileName . '.pdf', './documents/' . $canId . '/' . $fileName . '.pdf', '', '', '', '');
                        $updateStatus = updateCandidateInfo($mysqli, $canId, '', $firstName, $lastName, '', $mobile, $email, $gender, '', '', '', '', '', '', '', '', $residentStatus, $medicalCondition, $medConditionDesc, '', '', '', '', $dob, getConsultantIdByEmail($mysqli, $conEmail), 'INACTIVE', $empCondition, 1, $superMembershipNo, $tfn);

                        if (($updateStatus == 'added') || ($updateStatus == 'existingUpdated')) {
                            if ($residentStatus == 'Working Visa') {
                                addCandidateVisaTypeAndExpiry($mysqli, $canId, 4, $visaExpiry);
                            } elseif ($residentStatus == 'Temporary Resident') {
                                addCandidateVisaTypeAndExpiry($mysqli, $canId, 3, $visaExpiry);
                            } elseif ($residentStatus == 'Student Visa') {
                                addCandidateVisaTypeAndExpiry($mysqli, $canId, 2, $visaExpiry);
                            } elseif ($residentStatus == 'Australian Citizen') {
                                addCandidateVisaTypeAndExpiry($mysqli, $canId, 0, ' ');
                            } elseif ($residentStatus == 'Australian Permanent Resident') {
                                addCandidateVisaTypeAndExpiry($mysqli, $canId, 1, ' ');
                            }
                            updateBankAccount($mysqli, $canId, $bankAccountName, $bankAccountNumber, $bsb);
                            updateCandidateAddress($mysqli, $canId, $address,$unit_no, $street_number_1, $street_name, $suburb, $state, $postcode);

                            /* ============================== Tax Form PDF  ===================================*/
                            $txPdf = new Fpdi();
                            $txPdf->AddPage();
                            $tx_source_pdf = "docform/CPS_TFN_declaration_form_N3092.pdf";
                            $tx_pdf = "docform/CPS_TFN_declaration_form_" . time() . ".pdf";
                            shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $tx_pdf . '" "' . $tx_source_pdf . '"');
                            $txPdf->setSourceFile($tx_pdf);
                            $page1 = $txPdf->importPage(1);
                            $txPdf->useTemplate($page1);
                            $txPdf->AddPage();
                            $page2 = $txPdf->importPage(2);
                            $txPdf->useTemplate($page2);
                            $txPdf->AddPage();
                            $page3 = $txPdf->importPage(3);
                            $txPdf->useTemplate($page3);
                            $txPdf->AddPage();
                            $page4 = $txPdf->importPage(4);
                            $txPdf->useTemplate($page4);
                            $txPdf->AddPage();
                            $page5 = $txPdf->importPage(5);
                            $txPdf->useTemplate($page5);
                            $txPdf->SetFont("helvetica", "", 12);
                            $fontSize = '12';
                            $fontColor = '0,0,0';
                            $txPdf->SetTextColor($fontColor);
                            $dobSplit = explode('/', $dob);
                            $dobdd = str_split($dobSplit[0]);
                            $dobmm = str_split($dobSplit[1]);
                            $dobyy = str_split($dobSplit[2]);

                            $fullDate = date('d/m/Y');
                            $year = str_split(date('Y'));
                            $month = str_split(date('m'));
                            $day = str_split(date('d'));
                            $tfnSplit = str_split($tfn);

                            $txPdf->Text(49, 37, $tfnSplit[0]);
                            $txPdf->Text(54, 37, $tfnSplit[1]);
                            $txPdf->Text(59, 37, $tfnSplit[2]);
                            $txPdf->Text(69, 37, $tfnSplit[3]);
                            $txPdf->Text(74, 37, $tfnSplit[4]);
                            $txPdf->Text(79, 37, $tfnSplit[5]);
                            $txPdf->Text(89, 37, $tfnSplit[6]);
                            $txPdf->Text(94, 37, $tfnSplit[7]);
                            $txPdf->Text(99, 37, $tfnSplit[8]);

                            if (strlen($email) > 19) {
                                $emailPart1 = substr($email, 0, 19);
                                $emailPart2 = substr($email, 19);
                                $txPdf->Text(112, 38, chunk_split(strtoupper($emailPart1), 1));
                                $txPdf->Text(112, 47, chunk_split(strtoupper($emailPart2), 1));
                            } else {
                                $txPdf->Text(112, 38, chunk_split(strtoupper($email), 1));
                            }

                            $txPdf->Text(162, 57, $dobdd[0]);
                            $txPdf->Text(166, 57, $dobdd[1]);
                            $txPdf->Text(175, 57, $dobmm[0]);
                            $txPdf->Text(179, 57, $dobmm[1]);
                            $txPdf->Text(188, 57, $dobyy[0]);
                            $txPdf->Text(193, 57, $dobyy[1]);
                            $txPdf->Text(197, 57, $dobyy[2]);
                            $txPdf->Text(202, 57, $dobyy[3]);

                            if ($paidBasis == 'Full-time') {
                                $txPdf->Text(126, 71, 'X');
                            } elseif ($paidBasis == 'Part-time') {
                                $txPdf->Text(146, 71, 'X');
                            } elseif ($paidBasis == 'Labour-hire') {
                                $txPdf->Text(159, 71, 'X');
                            } elseif ($paidBasis == 'Superannuation') {
                                $txPdf->Text(183, 71, 'X');
                            } elseif ($paidBasis == 'Casual') {
                                $txPdf->Text(202, 71, 'X');
                            }

                            if ($taxResident == 'Australian resident') {
                                $txPdf->Text(136, 85, 'X');
                            } elseif ($taxResident == 'Foreign resident') {
                                $txPdf->Text(167, 85, 'X');
                            } elseif ($taxResident == 'Working holiday resident') {
                                $txPdf->Text(202, 85, 'X');
                            }

                            if ($taxClaim == 'Yes') {
                                $txPdf->Text(118, 107, 'X');
                            } elseif ($taxClaim == 'No') {
                                $txPdf->Text(131, 107, 'X');
                            }

                            if ($taxHelp == 'Yes') {
                                $txPdf->Text(118, 128, 'X');
                            } elseif ($taxHelp == 'No') {
                                $txPdf->Text(202, 128, 'X');
                            }

                            if ($title == 'Mr') {
                                $txPdf->Text(54, 72, 'X');
                            } elseif ($title == 'Mrs') {
                                $txPdf->Text(69, 72, 'X');
                            } elseif ($title == 'Miss') {
                                $txPdf->Text(84, 72, 'X');
                            } elseif ($title == 'Ms') {
                                $txPdf->Text(98, 72, 'X');
                            }

                            $txPdf->Text(9, 82, chunk_split(strtoupper($lastName), 1));
                            $txPdf->Text(9, 91, chunk_split(strtoupper($firstName), 1));

                            if (strlen($address) > 19) {
                                $addressPart1 = substr($address, 0, 19);
                                $addressPart2 = substr($address, 19);
                                $txPdf->Text(9, 114, chunk_split(strtoupper($addressPart1), 1));
                                $txPdf->Text(9, 122, chunk_split(strtoupper($addressPart2), 1));
                            } else {
                                $txPdf->Text(9, 114, chunk_split(strtoupper($address), 1));
                            }

                            $txPdf->Text(9, 132, chunk_split(strtoupper($suburb), 1));

                            $txPdf->Text(9, 140, chunk_split(strtoupper($state), 1));

                            $txPdf->Text(34, 140, chunk_split($postcode, 1));

                            $txPdf->Image($signaturePath, 105, 135, 70, 15, 'png');
                            $txPdf->Text(162, 150, $day[0]);
                            $txPdf->Text(166, 150, $day[1]);
                            $txPdf->Text(175, 150, $month[0]);
                            $txPdf->Text(179, 150, $month[1]);
                            $txPdf->Text(188, 150, $year[0]);
                            $txPdf->Text(193, 150, $year[1]);
                            $txPdf->Text(197, 150, $year[2]);
                            $txPdf->Text(202, 150, $year[3]);

                            $txFileName = 'taxFrm_' . $canId . '_' . time() . '.pdf';
                            $txFilePath = __DIR__ . '/tax/' . $txFileName;
                            $txPdf->Output(__DIR__ . '/tax/' . $txFileName, 'F');
                            copy($txFilePath, './documents/' . $canId . '/' . $txFileName);
                            updateCandidateDocs($mysqli, $canId, 46, $txFileName, './documents/' . $canId . '/' . $txFileName, '', '', '', '');
                            /*================================= Statutory Declaration PDF =========================================*/
                            if (($policeCheck == 'No') && ($crimeCheck == 'No') && (!empty($statOccupation))) {
                                $statPdf = new Fpdi();
                                $statPdf->AddPage();
                                $stat_source_pdf = "docform/StatutoryDeclaration_Criminal_Convictions_v3.pdf";
                                $stat_pdf = "docform/StatutoryDeclaration_" . time() . ".pdf";
                                shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $stat_pdf . '" "' . $stat_source_pdf . '"');
                                $statPdf->setSourceFile($stat_pdf);
                                $page1 = $statPdf->importPage(1);
                                $statPdf->useTemplate($page1);
                                $statPdf->SetFont("Times", "", 12);
                                $fontSize = '12';
                                $fontColor = '0,0,0';
                                $statPdf->SetTextColor($fontColor);

                                $statPdf->Text(37, 61, $firstName . ' ' . $lastName);
                                $statPdf->Text(37, 75, $address);
                                $statPdf->Text(37, 89, $statOccupation);
                                $statPdf->Text(27, 118, $neverConvicted);
                                $statPdf->Text(27, 126, $neverImprisonment);
                                $statPdf->Text(46, 214, date('d'));
                                $statPdf->Text(70, 214, date('M'));
                                $statPdf->Text(80, 214, date('Y'));
                                $statPdf->Image($signaturePath, 118, 200, 80, 15, 'png');

                                $stFileName = 'statDec_' . $canId . '_' . time() . '.pdf';
                                $stFilePath = __DIR__ . '/docform/' . $stFileName;
                                $statPdf->Output(__DIR__ . '/docform/' . $stFileName, 'F');
                                copy($stFilePath, './documents/' . $canId . '/' . $stFileName);
                                updateCandidateDocs($mysqli, $canId, 27, $stFileName, './documents/' . $canId . '/' . $stFileName, '', '', '', '');

                            }
                            //======================================= Police check authority form   ===========================================================
                            $crimePdf = new Fpdi();
                            $crimePdf->AddPage();
                            $crime_source_pdf = "docform/PoliceCheckAuthorityFormv2.pdf";
                            $crime_pdf = "docform/PoliceCheckAuthority_" . time() . ".pdf";
                            shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $crime_pdf . '" "' . $crime_source_pdf . '"');
                            $crimePdf->setSourceFile($crime_pdf);
                            $page1 = $crimePdf->importPage(1);
                            $crimePdf->useTemplate($page1);
                            $crimePdf->SetFont("helvetica", "", 10);
                            $fontSize = '10';
                            $fontColor = '0,0,0';
                            $crimePdf->SetTextColor($fontColor);

                            if ($crimeCheck == 'Yes') {
                                $crimePdf->Text(170, 70, 'X');
                                $crimePdf->Text(22, 86, $crimeDate1);
                                $crimePdf->Text(55, 86, $crime1);
                                $crimePdf->Text(22, 95, $crimeDate2);
                                $crimePdf->Text(55, 95, $crime2);
                            } else {
                                $crimePdf->Text(185, 70, 'X');
                            }

                            if ($optionChk == 'option1') {
                                $crimePdf->Text(21, 122, 'X');
                            } else {
                                $crimePdf->Text(21, 129, 'X');
                            }
                            $crimePdf->Text(23, 162, $firstName . ' ' . $lastName);

                            $crimePdf->Image($signaturePath, 10, 175, 80, 15, 'png');

                            $crimePdf->Text(20, 198, $firstName . ' ' . $lastName);
                            $crimePdf->Text(95, 198, date('d/m/Y'));

                            $crFileName = 'policeCheck_' . $canId . '_' . time() . '.pdf';
                            $crFilePath = __DIR__ . '/docform/' . $crFileName;
                            $crimePdf->Output(__DIR__ . '/docform/' . $crFileName, 'F');
                            copy($crFilePath, './documents/' . $canId . '/' . $crFileName);
                            updateCandidateDocs($mysqli, $canId, 18, $crFileName, './documents/' . $canId . '/' . $crFileName, '', '', '', '');
                            // ==================================== Fit2Work ===============================
                            //======================================= Police check authority form   ===========================================================
                            $fitPdf = new Fpdi();
                            $fitPdf->AddPage();
                            $fit_source_pdf = "docform/Fit2Work Form.pdf";
                            $fit_pdf = "docform/Fit2Work_" . time() . ".pdf";
                            shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $fit_pdf . '" "' . $fit_source_pdf . '"');
                            $fitPdf->setSourceFile($fit_pdf);
                            $page1 = $fitPdf->importPage(1);
                            $fitPdf->useTemplate($page1);
                            $fitPdf->SetFont("helvetica", "", 10);
                            $fontSize = '10';
                            $fontColor = '0,0,0';
                            $fitPdf->SetTextColor($fontColor);

                            $fitPdf->Text(26, 36, strtoupper($firstName));
                            $fitPdf->Text(138, 36, strtoupper($middleName));
                            $fitPdf->Text(26, 44, strtoupper($lastName));

                            if ($gender == 'Male') {
                                $fitPdf->Text(25, 52, 'x');
                            } elseif ($gender == 'Female') {
                                $fitPdf->Text(38, 52, 'x');
                            } elseif ($gender == 'Intersex') {
                                $fitPdf->Text(55, 52, 'x');
                            } elseif ($gender == 'Unknown') {
                                $fitPdf->Text(72, 52, 'x');
                            }
                            $dobSplit = explode('/', $dob);
                            $dobdd = str_split($dobSplit[0]);
                            $dobmm = str_split($dobSplit[1]);
                            $dobyy = str_split($dobSplit[2]);
                            $fitPdf->Text(139, 54, $dobdd[0]);
                            $fitPdf->Text(146, 54, $dobdd[1]);
                            $fitPdf->Text(156, 54, $dobmm[0]);
                            $fitPdf->Text(160, 54, $dobmm[1]);
                            $fitPdf->Text(170, 54, $dobyy[0]);
                            $fitPdf->Text(174, 54, $dobyy[1]);
                            $fitPdf->Text(178, 54, $dobyy[2]);
                            $fitPdf->Text(182, 54, $dobyy[3]);

                            $fitPdf->Text(29, 73, $pb_suburb);
                            $fitPdf->Text(99, 73, $pb_state);
                            $fitPdf->Text(155, 73, $pb_country);

                            $fitPdf->Text(22, 94, $unit_no);
                            $fitPdf->Text(50, 94, $street_number_1);
                            $fitPdf->Text(75, 94, $street_name);
                            $fitPdf->Text(187, 94, $postcode);
                            $fitPdf->Text(22, 102, $suburb);
                            $fitPdf->Text(100, 102, $state);
                            $fitPdf->Text(149, 102, 'Australia');

                            $fitPdf->Text(25, 131, $fw_first_name);
                            $fitPdf->Text(138, 131, $fw_middle_name);
                            $fitPdf->Text(25, 139, $fw_last_name);

                            if ($fw_type == 'Previous') {
                                $fitPdf->Text(172, 139, 'x');
                            } elseif ($fw_type == 'Maiden') {
                                $fitPdf->Text(189, 139, 'x');
                            }

                            $fitPdf->Text(22, 156, $fw_unit_no1);
                            $fitPdf->Text(49, 156, $fw_street_number1);
                            $fitPdf->Text(75, 156, $fw_street_name1);
                            $fitPdf->Text(187, 156, $fw_postcode1);
                            $fitPdf->Text(22, 166, $fw_suburb1);
                            $fitPdf->Text(100, 166, $fw_state1);
                            $fitPdf->Text(149, 166, $fw_country1);

                            $fitPdf->Text(22, 180, $fw_unit_no2);
                            $fitPdf->Text(49, 180, $fw_street_number2);
                            $fitPdf->Text(75, 180, $fw_street_name2);
                            $fitPdf->Text(187, 180, $fw_postcode2);
                            $fitPdf->Text(22, 190, $fw_suburb2);
                            $fitPdf->Text(100, 190, $fw_state2);
                            $fitPdf->Text(149, 190, $fw_country2);

                            $fitPdf->Text(143, 206, $mobile);
                            $fitPdf->Text(20, 216, $email);

                            $fitPdf->Text(45, 230, $fw_licence);
                            $fitPdf->Text(144, 230, $fw_licence_state);

                            $fitPdf->Text(45, 249, $fw_passport_no);
                            $fitPdf->Text(144, 249, $fw_passport_country);

                            if ($fw_passport_type == 'Private') {
                                $fitPdf->Text(45, 254, 'x');
                            } elseif ($fw_passport_type == 'Government') {
                                $fitPdf->Text(61, 254, 'x');
                            } elseif ($fw_passport_type == 'UN Refugee') {
                                $fitPdf->Text(83, 254, 'x');
                            }

                            $fitPdf->AddPage();
                            $page2 = $fitPdf->importPage(2);
                            $fitPdf->useTemplate($page2);
                            $fitPdf->AddPage();
                            $page3 = $fitPdf->importPage(3);
                            $fitPdf->useTemplate($page3);
                            $fitPdf->AddPage();
                            $page4 = $fitPdf->importPage(4);
                            $fitPdf->useTemplate($page4);

                            $fitPdf->Text(12, 34, $firstName . ' ' . $middleName);
                            $fitPdf->Text(104, 34, $lastName);

                            $fitPdf->Image($signaturePath, 40, 140, 80, 15, 'png');

                            $fitPdf->Text(130, 151, date('d'));
                            $fitPdf->Text(138, 151, date('m'));
                            $fitPdf->Text(146, 151, date('Y'));

                            $fitPdf->AddPage();
                            $page5 = $fitPdf->importPage(5);
                            $fitPdf->useTemplate($page5);
                            $fitPdf->AddPage();
                            $page6 = $fitPdf->importPage(6);
                            $fitPdf->useTemplate($page6);

                            $fit2wkFileName = 'fit2wrk_' . $canId . '_' . time() . '.pdf';
                            $fit2wkFilePath = __DIR__ . '/docform/' . $fit2wkFileName;
                            $fitPdf->Output(__DIR__ . '/docform/' . $fit2wkFileName, 'F');
                            copy($fit2wkFilePath, './documents/' . $canId . '/' . $fit2wkFileName);
                            updateCandidateDocs($mysqli, $canId, 56, $fit2wkFileName, './documents/' . $canId . '/' . $fit2wkFileName, '', '', '', '');

                            $output_dir = './jot/';
                            $msg = '';
                            if (!empty($_FILES['passportFile'])) {
                                if ($_FILES['passportFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['passportFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['passportFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['passportFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['passportFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-passport-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['passportFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $passportFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 9, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['passportFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['birthFile'])) {
                                if ($_FILES['birthFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['birthFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['birthFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['birthFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['birthFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-birth-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['birthFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $birthFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 3, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['birthFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['citizenFile'])) {
                                if ($_FILES['citizenFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['citizenFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['citizenFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['citizenFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['citizenFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-citizen-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['citizenFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $citizenFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 3, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['citizenFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['drivingFile'])) {
                                if ($_FILES['drivingFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['drivingFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['drivingFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['drivingFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['drivingFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-drv-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['drivingFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $drivingFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 7, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['drivingFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['medicareFile'])) {
                                if ($_FILES['medicareFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['medicareFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['medicareFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['medicareFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['medicareFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-med-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['medicareFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $medicareFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 15, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['medicareFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['studentFile'])) {
                                if ($_FILES['studentFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['studentFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['studentFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['studentFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['studentFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-st-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['studentFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $studentFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 9, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['studentFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['policeFile'])) {
                                if ($_FILES['policeFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['policeFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['policeFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['policeFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['policeFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-pc-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['policeFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $policeFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 18, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['policeFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['profileFile'])) {
                                if ($_FILES['profileFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['profileFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['profileFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['profileFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['profileFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-pr-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        employeeImageUpdate($mysqli, addslashes(base64_encode(file_get_contents($_FILES['profileFile']['tmp_name']))), $canId);
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['profileFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $profileFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 17, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['profileFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            /*if (!empty($_FILES['whsFile'])) {
                                if ($_FILES['whsFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['whsFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['whsFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['whsFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['whsFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-whs-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['whsFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $whsFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 53, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['whsFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }*/
                            if (!empty($_FILES['whiteFile'])) {
                                if ($_FILES['whiteFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['whiteFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['whiteFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['whiteFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['whiteFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-white-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['whiteFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $whiteFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 54, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['whiteFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['forkliftFile'])) {
                                if ($_FILES['forkliftFile']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['forkliftFile']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['forkliftFile']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['forkliftFile']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['forkliftFile']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-frklft-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['forkliftFile']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $forkliftFileSubmitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 55, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['forkliftFile']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            /* ------------ vaccinations --------------*/
                            if (!empty($_FILES['covid19File1'])) {
                                if ($_FILES['covid19File1']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['covid19File1']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['covid19File1']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['covid19File1']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['covid19File1']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-vacc1-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['covid19File1']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $covid19File1Submitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 57, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['covid19File1']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['covid19File2'])) {
                                if ($_FILES['covid19File2']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['covid19File2']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['covid19File2']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['covid19File2']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['covid19File2']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-vacc2-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['covid19File2']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $covid19File2Submitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 58, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['covid19File2']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            if (!empty($_FILES['covid19File3'])) {
                                if ($_FILES['covid19File3']['error'] > 0) {
                                    $msg = 'Error: ' . $_FILES['covid19File3']['error'] . '<br>';
                                } else {
                                    if (file_exists($_FILES['covid19File3']['name'])) {
                                        $msg = 'Please rename your file to a different name';
                                    } else {
                                        $fileName = pathinfo($_FILES['covid19File3']['name'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo($_FILES['covid19File3']['name'], PATHINFO_EXTENSION);
                                        $newFileName = $fileName . '-vacc3-' . time() . "." . $fileExt;
                                        $filePath = $output_dir . $newFileName;
                                        if(in_array($fileExt,$allowed)) {
                                            if (move_uploaded_file($_FILES['covid19File3']['tmp_name'], $output_dir . $newFileName)) {
                                                copy($filePath, './documents/' . $canId . '/' . basename($filePath));
                                                $covid19File3Submitted = $filePath;
                                                updateCandidateDocs($mysqli, $canId, 59, basename($filePath), './documents/' . $canId . '/' . basename($filePath), '', '', '', '');
                                            } else {
                                                $msg = 'Error Uploading' . $_FILES['covid19File3']['error'];
                                            }
                                        }else{
                                            $msg = 'Error Uploading - File type not allowed';
                                        }
                                    }
                                }
                            }
                            //====================== generate registration form email =========================
                            generateJotFormMail($firstName, $lastName, $conEmail, $jotFormFilled, $passportFileSubmitted, $birthFileSubmitted, $citizenFileSubmitted, $drivingFileSubmitted, $medicareFileSubmitted, $studentFileSubmitted, $policeFileSubmitted, $txFilePath, $crFilePath, $stFilePath, $profileFileSubmitted, $whiteFileSubmitted, $forkliftFileSubmitted, $fit2wkFilePath);
                            unlink($stFilePath);
                            unlink($fit2wkFilePath);
                            unlink($crFilePath);
                            unlink($txFilePath);
                            unlink($passportFileSubmitted);
                            unlink($birthFileSubmitted);
                            unlink($citizenFileSubmitted);
                            unlink($drivingFileSubmitted);
                            unlink($medicareFileSubmitted);
                            unlink($studentFileSubmitted);
                            unlink($policeFileSubmitted);
                            unlink($profileFileSubmitted);
                            unlink($whiteFileSubmitted);
                            unlink($forkliftFileSubmitted);
                            unlink($covid19File1Submitted);
                            unlink($covid19File2Submitted);
                            unlink($covid19File3Submitted);
                            unlink(__DIR__ . '/' . $tx_pdf);
                            unlink(__DIR__ . '/' . $stat_pdf);
                            unlink(__DIR__ . '/' . $crime_pdf);
                            unlink(__DIR__ . '/' . $fit_pdf);
                            echo 'SUCCESS';
                        } else {
                            generateErrorNotification('REGISTRATION FORM UPDATE ERROR ', $updateStatus . $email . $firstName);
                            echo 'FAILURE' . $updateStatus;
                        }
                    } catch (Exception $e1) {
                        generateErrorNotification('REG FORM ERROR ', $e1->getMessage() . $canId . $firstName . $lastName . $mobile . $email . $gender . $residentStatus . $medicalCondition . $medConditionDesc . $dob . getConsultantIdByEmail($mysqli, $conEmail) . $empCondition . $superMembershipNo);
                        echo $e1->getMessage();
                    }
                } else {
                    echo 'FAILURE';
                }
            }else{
                echo 'FAILURE';
            }
        } catch (Exception $e) {
            generateErrorNotification('REGISTRATION FORM PDF ERROR ', $e->getMessage().$conEmail);
            echo 'FAILURE';
        }
    } catch (Exception $err) {
        generateErrorNotification('REGISTRATION FORM ERROR ', $err->getMessage());
        echo 'FAILURE';
    }
}