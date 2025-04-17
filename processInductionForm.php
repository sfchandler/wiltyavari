<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
require_once "includes/TCPDF-main/tcpdf.php";
date_default_timezone_set('Australia/Melbourne');
/*if (empty($_SESSION['csrf_token'])) {
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
}*/
$imgData = $_POST['imageSrc'];
$conEmail = base64_decode($_POST['conEmail']);
$canId = base64_decode($_POST['canId']);
$b64 = 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$someFileName = 'signature-'.time().'.jpg';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}
$signaturePath = './jot/'.$someFileName;
try {
    file_put_contents($signaturePath, $fileData);
}catch (Exception $e){
    echo 'error'.$e->getMessage();
}
class INDUCTIONPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 5, 30, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 11);
        $this->Ln(5);
        $this->SetTextColor(169,169,169);
        $this->Cell(0, '', DOMAIN_NAME.' ', 0, $ln = 0, 'R', 0, '', 0, false, 'B', 'B');
        $this->Ln(5);
        $this->SetTextColor(169,169,169);
        $this->Cell(0, '', 'CASUAL INDUCTION', 0, $ln = 0, 'R', 0, '', 0, false, 'B', 'B');
        $this->Ln(6);
        $this->SetTextColor(0,0,0);
        $this->Cell(0, '', '____________________________________________________________________________________________________________________________________________', 0, $ln = 0, 'R', 0, '', 0, false, 'B', 'B');
    }
}
try {
    $pdf = new INDUCTIONPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('INDUCTION');
    $pdf->SetSubject('INDUCTION');
    $pdf->SetKeywords('INDUCTION');
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
    $html = $html . '<div style="font-family: Arial, Helvetica, sans-serif; font-size: 12px">
<div align="center">
    Welcome to '.DOMAIN_NAME.'! Please read this induction, and it is advised that you keep this document throughout your employment with '.DOMAIN_NAME.'.
</div><br/>
<div align="center" style="width: 980px;">
<table class="table" style="border: solid 1px black">
    <tbody>
      <tr>
        <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">CONTACT US</td>
      </tr>
      <tr>
        <td>
            <ul>
                <li>Our best available contact number is <span style="font-size: 14px; color: #2a6395; font-weight: bold">1300 499 449. We are a 24-Hour Service.</span> The number that appears on your mobile when we call you is <span style="font-size: 14px; color: #2a6395; font-weight: bold">03 9656 9777.</span></li>
                <li>You will always be able to reach a consultant. If your inquiry is regarding payroll, please be sure to call between 08:30 - 17:00 Mon-Fri.</li>
                <li><b>IF YOU ARE SICK – Please call us. <span style="font-size: 14px; color: #2a6395; font-weight: bold">DO NOT TEXT IN SICK</span>. You are required to communicate with a consultant directly if you are unable to attend work. We do not accept Text Messages or Emails if you are sick or cancelling a shift.</b></li>
                <li>You are required to provide us with <span style="color: red; font-weight: bold">minimum 4 hours notice</span> if you are not able to attend an allocated shift for any reason. Failure to do so may result in your account being made <span style="font-size: 14px; color: #2a6395; font-weight: bold">INACTIVE.</span></li>
                <li><b>OFFICE HOURS</b> - Our office hours are Monday to Friday, 8:30am-5:00pm. If you have general enquiries, please contact us during this time.</li>
                <li>You will be able to get in contact an After-Hours Consultant if you have any issues with your shift between the hours of 17:00-08:30</li>
            </ul>
        </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">GETTING STARTED</td>
      </tr>
      <tr>
        <td><p style="text-align: justify">After your registration and interview, it might take a little while to get you your first shift, as it can be hard for clients to have new starters regularly. Please be patient and we will call you when we have your first shift available for you.
            Please make sure that you have downloaded the Chandler App and have received your log-in details and mobile app user guide.</p>
        </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">TEXT MESSAGES & ACCEPTING SHIFTS</td>
      </tr>
      <tr>
          <td>
              <ul>
                  <li>If you receive a text message/push notification regarding a shift or your roster, please log into the app and check release shifts. IF you would like to work the allocated shift please <b>ACCEPT and CONFIRM the shift.  IF</b> you are <b>NOT</b> able to work the allocated shift please <b>REJECT the shift and call us.</b></li>
                  <li><span style="color: red; font-weight: bold">Please do not reply to Text Messages to confirm shifts</span></li>
              </ul>
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">TIMESHEETS</td>
      </tr>
      <tr>
          <td>
              <ul>
                  <li>Our pay cycle runs from Monday to Sunday. Any hours you work from a Monday to Sunday in any given week will be paid the following Thursday.</li>
                  <li>You will receive your Payslip via email. If you have not received your payslip by 17:30 on Tuesday, please get in contact with our payroll department by 12 PM on Wednesday. Failure to inform discrepancies will cause your pay to be delayed by a week.</li>
                  <li><b>CLOCKING IN AND OUT</b> – All Chandler Personnel staff are required to clock in and out through the mobile app when you arrive on site. Please be sure to add your meal break before you clock out. You will only be able to clock in and out <b>while on site.</b></li>
                  <li>Please refer to the mobile app user guide for instructions on how to clock in and out using the app.</li>
                  <li><b>Failure to Clock In and Out – There will be a payroll administration fee if you fail to clock in and out</b> and will cause a delay in your pay. If you are unable to clock in or out, please contact us immediately.</li>
                  <li><b>Manual Time Sheets</b> – Please note that this is only for Selected clients. Unless advised by a consultant please do not have manual timesheets signed off and approved by your supervisor. If you are advised to complete a manual timesheet, please make sure your timesheets are always signed by a supervisor and filled in correctly. Any mistakes may lead to your pay being incorrect or delayed.</li>
                  <li>If the client requires you to complete an electronic timesheet and you are unable to clock in and out through the system, please advise the supervisor and Chandler immediately.</li>
              </ul>
          </td>
      </tr>
      <tr>
          <td></td>
      </tr>
      <tr>
          <td></td>
      </tr>
      <tr>
          <td><b>IN ALL CASES YOU ARE REQUIRED TO CLOCK IN AND OUT USING THE '.strtoupper(DOMAIN_NAME).' MEMBER APP</b></td>
      </tr>
      <br pagebreak="true"/>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">UNIFORM & PPE</td>
      </tr>
      <tr>
          <td>
              <p>
                  You must always be well-presented when attending work. Please wear a clean <b>high vis t-shirt or long-sleeves</b> (note some sites will not allow hoods or vests), <b>steel capped boots and clean long work pants.</b> (Shorts, track pants, leggings are not acceptable)
                  You will be notified of any site-specific rules or further PPE requirements when you are assigned to a specific job.
              </p>
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              NO-SHOW POLICY
          </td>
      </tr>
      <tr>
          <td><p style="text-align: justify">If you do not attend a shift you have been confirmed for, and you have not called to advise us BEFORE 4 hours prior to your shift starts, you will no longer receive shifts through Chandler. <span style="color: red">This is a very serious rule that is strictly enforced</span> – Our clients rely on people attending their shifts.</p></td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">WHILE YOU ARE AT WORK</td>
      </tr>
      <tr>
          <td><p style="text-align: justify">We ask that you APPLY yourself and complete your duties to the best of your knowledge and skill. We ask that you show your initiative and engage with fellow staff, supervisors and visitors in a friendly and inviting manner.</p></td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">IF IN DOUBT, STOP!</td>
      </tr>
      <tr>
          <td>
              <span style="text-align: justify">
                  While on site, if you are ever asked to complete a task that you believe is unsafe or beyond your training or capabilities, please STOP. Discuss the task with your supervisor and if you are still unsure, call Chandler immediately.
              </span>
          </td>
      </tr>
      <tr>
        <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
            SHIFT TIMES
        </td>
      </tr>
      <tr>
          <td>
              <ul>
                  <li>Shift times vary from site to site. It is at the discretion of the supervisor to shorten shifts; however, you will be paid for a minimum of 4 hours.</li>
                  <li>It is important that if you accept a shift that you are able to stay for the whole shift. If you need to leave earlier due to an emergency or unavoidable circumstance, you MUST let the supervisor know.</li>
                  <li>Your shifts may get cancelled from time to time. We will call you at least 1 hour before your shift starts if this happens. If we cancel your shift, you must not attend – we thank you in advance for your understanding.</li>
              </ul>
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              ORIENTATION
          </td>
      </tr>
      <tr>
          <td>
              It is the joint responsibility of the site and the casual employee to ensure that they are familiar with the site they are working at for the day. Before you begin your shift ensure you are aware of: emergency exits, first aid kit, who your supervisor is, amenities, your task responsibilities, PPE required and any other important site-specific information.
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              HAZARD IDENTIFICATION
          </td>
      </tr>
      <tr>
          <td>
              You may be exposed to various hazards while at work such as slips, trips, falls, manual handling, heat, cold etc. Ensure that you are aware of the correct precautions for the handling of these hazards.
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              INCIDENTS, ACCIDENTS OR NEAR-MISSES
          </td>
      </tr>
      <tr>
          <td>
             <p style="text-align: justify">
                 All incidents, accidents and near misses must be reported. Please follow the sites protocol and report to Chandler. If you are injured, please seek site First Aid immediately and have someone notify your supervisor.
             </p>
             <p><b>Please see below worksafe factsheets</b></p>
              <br>
              <p>
                  <a href="https://chandlerservices-my.sharepoint.com/:f:/g/personal/denisam_chandlerservices_com_au/Eq70YawrmMxGmgQlpSnu1wsBdUezuuHw5EtkdfcneV5t9w?e=R67JEJ" target="_blank">Worksafe Information</a>
              </p>
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              CONFIDENTIALITY
          </td>
      </tr>
      <tr>
          <td>
              <p style="text-align: justify">
                 During or after the termination of your employment, you must not divulge any confidential information of Chandler Personnel or of any of its clients, TO ANYONE.
              </p>
              <p style="text-align: justify">
                  The use of mobile phones while on site is not allowed. Taking photos or videos of a work site is strictly prohibited. Chandler Personnel’s clients enforce this rule and there will be consequences for any non-compliance.
              </p>
          </td>
      </tr>
      <br pagebreak="true"/>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              CODE OF CONDUCT
          </td>
      </tr>
      <tr>
          <td>
              <p style="text-align: justify">
                  All staff are encouraged to use their knowledge and skills to perform their duties to the best of their ability, work well together, communicate with respect and tolerance, and work constructively to solve conflict. While at work all staff must:
              </p>
              <ul>
                <li>
                        Take reasonable care for his or her own health and safety, and the safety of others at their workplace
                </li>
              </ul>
              <p style="text-align: justify">
                  Co-operate with his or her employer with respect to any action taken by the employer to comply with a requirement imposed by or under this act or the regulations.
              </p>
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              HARRASSMENT AND BULLYING POLICY
          </td>
      </tr>
      <tr>
          <td>
             <p style="text-align: justify">
                 All workplaces should be free from harassment, where all people are treated with dignity, courtesy, and respect. Any incident of harassment should be reported immediately to the person in charge, and to Chandler. Harassment is unlawful whether it is intentional or unintentional – whether it is in the form of imitating someone’s accent, spreading rumours, offensive jokes, treats, insults or pushing and shoving. Any sexual harassment should also be reported. Chandler Personnel does not tolerate any harassment or bullying.
             </p>
          </td>
      </tr>
      <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              MOBILE APP USER GUIDE
          </td>
      </tr>
      <tr>
          <td>
              <p style="text-align: justify">
                  As mentioned earlier, you need to use Chandler Services Mobile app to Accept shifts and Clock in and out. The app user guide will be emailed to you once you joined with '.DOMAIN_NAME.'.
              </p>
              <p style="text-align: justify">
                  You MUST read the user guide. If you have any question regarding any functionality of the mobile app or if you have not received the user guide, please call '.DOMAIN_NAME.' on <span style="font-size: 14px; color: #2a6395; font-weight: bold">03 9656 9777.</span>
              </p>
          </td>
      </tr>
       <tr>
          <td style="background-color: #2a6395; color: white; font-weight: bold; text-align: center">
              RELATED INFORMATION FROM FAIR WORK OMBUDSMAN
          </td>
      </tr>
       <tr>
          <td>
              <p style="text-align: justify">
                  Kindly refer following links for your knowledge and understanding 
              </p>
              <p><a href="https://www.fairwork.gov.au/sites/default/files/migration/724/casual-employment-information-statement.pdf" target="_blank">https://www.fairwork.gov.au/sites/default/files/migration/724/casual-employment-information-statement.pdf</a></p>
              <p><a href="https://www.fairwork.gov.au/sites/default/files/migration/724/Fair-Work-Information-Statement.pdf" target="_blank">https://www.fairwork.gov.au/sites/default/files/migration/724/Fair-Work-Information-Statement.pdf</a></p>
          </td>
      </tr>
      <tr>
          <td>
              <div style="width: 500px;">
                      <p style="text-align:left">I HAVE READ AND UNDERSTOOD THE ABOVE INDUCTION.</p>
                      <br>
                      <div style="text-align:left">Candidate Signature</div>
                      <div id="signature" style="text-align:left"><img src="' . $signaturePath . '"/></div>
                      <div>'.date('d/m/Y H:i:s').'<span style="font-size: 8px">(date time)</span></div>
              </div>
          </td>
      </tr>
    </tbody>
  </table>
</div>
</div>';

    if(!file_exists('documents/'.$canId)){
        mkdir('documents/'.$canId, 0777);
        chown('./documents/' . $canId,'chandler');
    }
    $fileName = 'induction_'.$canId.'_' . time();
    $filePath = './documents/'.$canId.'/'.$fileName.'.pdf';
    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__.'/documents/'.$canId.'/'.$fileName.'.pdf', 'F');
    if(!empty($filePath)){
        copy($filePath,'./documents/' . $canId.'/'.$fileName.'.pdf');
        updateCandidateDocs($mysqli,$canId, 11,$fileName.'.pdf','./documents/' . $canId.'/'.$fileName.'.pdf','','','','SIGNED');
    }
    echo generateInductionMail(getCandidateFirstNameByCandidateId($mysqli,$canId),getCandidateLastNameByCandidateId($mysqli,$canId),$conEmail,$filePath);
}catch (Exception $e){
    echo $e->getMessage();
}