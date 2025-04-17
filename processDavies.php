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
$empWorkArea = base64_decode($_POST['empWorkArea']);
$conEmail = base64_decode($_POST['conEmail']);
$signedDate = date('d/m/Y');
$canId = base64_decode($_POST['empId']);
$b64 = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$someFileName = 'sigEmpDavies-'.time().'.jpg';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}
$signaturePath = './documents/'.$canId.'/'.$someFileName;
try {
    file_put_contents($signaturePath, $fileData);
}catch (Exception $e){
    echo 'error'.$e->getMessage();
}

class DAVIESPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        //$this->setJPEGQuality(90);
        $this->Image($image_file, 5, 5, '25', '20', 'png', '', 'R', false, 300, '', false, false, 0, false, false, false);
    }
    public function Footer()
    {
        // set style for QR barcode
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $this->Line(5, 280, 205, 280);
        // Set font
        $this->SetFont('helvetica', 'R', 8);
        // Page number
        $this->SetY(-19);
        $this->Cell(0, 10, 'Policy Name:                   HR030 Policies and Procedures – Temperature Checks - Temporary', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-15);
        $this->Cell(0, 10, 'Version Number:                1 ', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-11);
        $this->Cell(0, 10,'Date:                           '.date('d/m/Y'), 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-7);
        $this->Cell(0, 10, 'Authorised by:                 HR Manager', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        // Position at 15 mm from bottom
        $this->SetY(-15);

    }
}
try {
    $pdf = new DAVIESPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('DAVIES TEMPERATURE CHECK');
    $pdf->SetSubject('DAVIES TEMPERATURE CHECK');
    $pdf->SetKeywords('DAVIES TEMPERATURE CHECK');
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
    $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
    $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
    $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
    $pdf->Line(0, 0, 0, $pdf->getPageHeight());
    $html = $html . '<style>
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        .table-bordered-style{
            border: black 1px solid;
        }
        .para_title{
            color: #2a6395;
            font-weight: bold;
            font-size: 14pt;
            padding-left: 20px;
        }
        .sub_heading{
            padding-left: 40px;
            font-size: 12pt;
            font-weight: bold;
            color: #2a6395;
        }
        ul{
            padding-left: 50px;
        }
        p{
            text-align: justify;
            padding-left: 20px;
        }
        p.para_bold{
            font-weight: bold;
        }
    </style>';
    $html = $html . '<div align="center"><h2>Davies Bakery Pty Ltd Temperature Checks – Temporary</h2></div>';
    $html = $html . '<div style="padding-left: 200px;padding-top: 50px; width: 980px;" width="980px">';
    $html = $html.'<div align="center">
                    <table class="table-bordered-style" cellspacing="0" cellpadding="0" border="1">
                        <tr>
                            <td style="text-align: left">Document ID</td>
                            <td style="text-align: left">Temperature Checks – Temporary</td>
                        </tr>
                        <tr>
                            <td style="text-align: left">Date</td>
                            <td style="text-align: left">01 July 2020</td>
                        </tr>
                        <tr>
                            <td style="text-align: left">Date of Next Review</td>
                            <td style="text-align: left">as required</td>
                        </tr>
                        <tr>
                            <td style="text-align: left">Approved by</td>
                            <td style="text-align: left">HR Manager</td>
                        </tr>
                        <tr>
                            <td style="text-align: left">Responsible Officer</td>
                            <td style="text-align: left">Managing Director</td>
                        </tr>
                        <tr>
                            <td style="text-align: left">References and Legislation</td>
                            <td style="text-align: left">Victorian Government Health Department Department of Health and Human Services</td>
                        </tr>
                        <tr>
                            <td style="text-align: left">Version</td>
                            <td style="text-align: left">1.0</td>
                        </tr>
                    </table>
                 </div>';
    $html = $html.'<div class="para_title">1.	POLICY STATEMENT</div>
<div>
    <p>COVID19 (Coronavirus) is highly contagious and implementing practices is essential to prevent its spread.</p>
    <p>Our top priority at Davies Bakery remains the health and safety of our people and protecting the quality and ongoing delivery of our products to our customers.</p>
    <p>This procedure is applicable to any Davies Bakery employee or contractor.</p>
</div>
<div class="para_title">2.	PURPOSE</div>
<div>
    <p>Davies Bakery has adopted this Policy to explain how the process will work and ensure that it is done fairly, effectively, safely, consistently and in a manner that complies with current government emergency orders and public health guidelines</p>
</div>
<div class="para_title">3.	SCOPE</div>
<div>
    <p>During the COVID19 pandemic, Davies Bakery has introduced temperature checking of employees and contractors. This measure is to aid in the early detection of employee’s potentially being unwell.</p>
    <p class="para_bold">No employee or contractor will be exempt from temperature checking and temperature checking will be required prior to entry and not waived for any occasion.</p>
</div>
<div class="para_title">4.	PROCEDURE</div>
<div>
    <div class="sub_heading">4.1	Body Temperature Check</div>
    <p>
        <ul>
            <li>On arrival at work it is the employee or contractor’s responsibility to have their temperature checked with a thermal
                temperature gun or thermometer.
            </li>
            <li>If a supervisor is not present when you arrive for work, the employee should seek out a responsible person to take their temperature.</li>
            <li>Following the instructions of the temperature gun or non-contact forehead thermometer a temperature readout will be obtained.</li>
            <li>Normal body temperature range should be between 36.5°C and 37.5°C.</li>
            <li>If temperature readout is outside these parameters, then a retest will be conducted on the spot.</li>
            <li>If reading again is above, the employee or contractor is required to conduct ear thermometer check.</li>
            <li>This is more accurate. This should be done in the portable container outside BM1 carpark.</li>
            <li>If ear thermometer reading shows 38°C or above the employee or contractor will be sent home and paid for the remainder of their shift.</li>
            <li>The employee or contractor should seek medical advice due to a high fever.</li>
            <li>Employee or contractor cannot return to site until they have been seen by a medical practitioner.</li>
            <li>In addition, the employee or contractor should contact Bernie Relf, HR Manager on 0411 409 300 with an update once they have been seen by a medical practitioner.</li>
        </ul>
    </p>
</div>
<div class="para_title">5.	DURATION OF POLICY</div>
<div>
    <p>1.	This Policy is a temporary measure that will last only as long as the COVID19 pandemic and will end once the Victorian Government or Department of Health and Human Services declare the pandemic under control.</p>
    <p>2.	In addition, Davies Bakery reserves the right to modify any of the Policy, including the elimination or addition of requirements, as guidelines change over the course of the COVID19pandemic.</p>
    <p>3.	If any additions or deletions are made to this Policy, employees and contractors will be advised in writing.</p>
</div>
<div class="para_title">6.	SIGN OFF</div>
<div>
    <p>I have read the HR032 Policies and Procedures – Temperature Checks – Temporary policy.</p>
    <p>I have understood the above policy and understand it is my responsibility to get my temperature checked.</p>
    <p>I agree to follow the directions in the above policy and have my temperature checked every shift I am on at Davies Bakery.</p>
    <p>I agree that if I have any questions about the policy, I will speak to my supervisor in the first instance. If I still have questions, I will speak with Bernie Relf, HR Manager 0411 409 300</p>
</div>';
    $html = $html.'<div>
                            <table style="width: 450px">
                                <tbody>
                                <tr>
                                    <td>Employee Name:</td>
                                    <td>'.getCandidateFullName($mysqli,$canId).'</td>
                                </tr>
                                <tr>
                                    <td>Employee work area:</td>
                                    <td>'.$empWorkArea.'</td>
                                </tr>
                                <tr>
                                    <td>Date:</td>
                                    <td>'.date('d/m/Y').'</td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 50px;">Signed:</td>
                                    <td><div id="signature"><img src="' . $signaturePath . '"/></div></td>
                                </tr>
                                </tbody>
                                </table>
                    </div></div>';
    if(!file_exists('documents/'.$canId)){
        mkdir('documents/'.$canId, 0777);
        chown('./documents/' . $canId,'chandler');
    }
    $fileName = 'davies_temp_check_' . time();
    $filePath = './documents/'.$canId.'/'. $fileName . '.pdf';
    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__ .'/documents/'.$canId.'/'.$fileName.'.pdf', 'F');
    try {
        if (!empty($html)) {
           updateCandidateDocs($mysqli,$canId, 47,$fileName,'./documents/' . $canId.'/'.$fileName,'','','','');
           echo generateDaviesTemperatureCheckMail(getCandidateFirstNameByCandidateId($mysqli,$canId), getCandidateLastNameByCandidateId($mysqli,$canId), $conEmail, $filePath);
        }
    } catch (Exception $e1) {
        echo $e1->getMessage();
    }
}catch (Exception $e){
    echo $e->getMessage();
}