<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 1/10/2019
 * Time: 10:35 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
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
$someFileName = 'signatureTerms-'.time().'.jpg';
// element 1 of array from explode() contains B64-encoded data
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}
$signaturePath = './sgterms/'.$someFileName;
try {
    file_put_contents($signaturePath, $fileData);
}catch (Exception $e){
    echo 'error'.$e->getMessage();
}

$fullName=base64_decode($_POST['fullName']);
$address=base64_decode($_POST['address']);
$curDate=base64_decode($_POST['curDate']);
$conEmail=base64_decode($_POST['conEmail']);

class TERMSPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 5, 40, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 20);
    }
}

try{
    $pdf = new TERMSPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('TERMS FORM INFORMATION');
    $pdf->SetSubject('TERMS FORM INFORMATION');
    $pdf->SetKeywords('TERMS FORM INFORMATION');
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 8));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetMargins(5, 30, 5);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetFont('helvetica', '', 8);
    $pdf->AddPage();
    $pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));
    $pdf->Line(0,0,$pdf->getPageWidth(),0);
    $pdf->Line($pdf->getPageWidth(),0,$pdf->getPageWidth(),$pdf->getPageHeight());
    $pdf->Line(0,$pdf->getPageHeight(),$pdf->getPageWidth(),$pdf->getPageHeight());
    $pdf->Line(0,0,0,$pdf->getPageHeight());
    $style = array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'solid' => 0, 'color' => array(0, 0, 0));
    $html = $html.'<style>.termTitle{font-weight: bold;}label{font-weight: bold;}ol { counter-reset: item;}li {display: block;text-align: justify;}ol li:before{font-weight: bold;}li:before {content: counters(item, ".") " ";counter-increment: item;}.signInfo{border-bottom: 1px solid black}</style>';
    $html = $html.'<div width="980px"><div align="center" style="background-color:#000000;color:white;">TERMS OF EMPLOYMENT</div>
        <br>
        <div class="row"><label for="">Date: </label>'.$curDate.'</div>
        <br>
        <div class="row"><b>PRIVATE & CONFIDENTIAL</b></div>
        <br>
        <div class="row">
            <section class="col col-m-12">
                <label>NAME:</label>
                '.$fullName.'
            </section>
        </div>
        <div class="row">
            <section class="col col-m-12">
                <label>ADDRESS:</label>
                '.$address.'
            </section>
        </div>
        <br>
        <div class="row">
            <section class="col col-m-12"><b>Employment with Chandler Personnel Services Pty Limited (ACN 091 298 234) ("Chandler Personnel")</b></section>
        </div>
        <br>
        <div class="row">
                Following our recent discussions, it is with pleasure that I offer you casual employment with Chandler Personnel.
                <br><br>
                Please read this letter carefully as this offer is conditional on you agreeing to the terms contained in it.
        </div>
        <div class="row">
                <ol>
                    <li><b>DEFINITIONS</b>
                    <br>
                        <table>
                            <tr><td><b>1.1</b> Assignment means a work placement in which you will perform the Services for a Client as directed by the Client.</td></tr>
                            <tr><td><b>1.2</b> Client means a client of Chandler Personnel and Clients means more than one client of Chandler Personnel.</td></tr>
                            <tr><td><b>1.3</b> Conditions of Assignment means the location, start time, finish time (if known) and pay rate of the Assignment, and who the Candidate it required to report to during the Assignment.</td></tr>
                            <tr><td><b>1.4</b> Related Person means a "related body corporate" as that expression is defined in the Corporations Act 2001 (Cth) or any legislation replacing the Corporations Act 2001 (Cth).</td></tr>
                            <tr><td><b>1.5</b> Services means the services to be performed by you for a Client during an Assignment.</td></tr>
                        </table>
                    </li>
                    <li><b>NATURE OF EMPLOYMENT RELATIONSHIP</b>
                    <br>
                        <table>
                            <tr><td><b>2.1</b> The relationship between you and us is that of casual employee and employer.</td></tr>
                            <tr><td><b>2.2</b> You acknowledge and agree that this offer of employment does not involve any representation or expectation of continuing work or regular and systematic engagement.</td></tr>
                            <tr><td><b>2.3</b> This offer of employment also does not involve any representation by the Company that you will become either a temporary or permanent employee of a Client or that you will be given priority or precedence over any other employee or applicant for an Assignment with a Client.</td></tr>
                            <tr><td><b>2.4</b> Without limiting the above-mentioned clause, this letter does not make, constitute or appoint you as a temporary or permanent employee of, or independent contractor to, Chandler Personnel.</td></tr>
                            <tr><td><b>2.5</b> If your name appears on a roster it will be purely for administrative purposes and in no way represents regular, systematic or ongoing engagement or employment.</td></tr>
                        </table>
                    </li>
                    <li><b>WORK FOR OTHER EMPLOYERS</b>
                    <br>
                        <table>
                            <tr><td><b>3.1</b> Consistent with the casual nature of your employment relationship with Chandler Personnel, the parties to this letter acknowledge and agree that you may perform work and services for other persons, provided that any such work or Services do not conflict with your obligations to Chandler Personnel under this letter.</td></tr>
                            <tr><td><b>3.2</b> Prior to commencing each Assignment, you will declare to Chandler Personnel any conflict of interest or potential conflict of interest that you may have.</td></tr>
                        </table>
                    </li>
                    <li><b>COMMENCEMENT DATE</b></li>
                        <p>The terms and conditions of employment contained in this letter will commence on the below signed date and will continue until terminated in accordance with the terms of this letter.</p>
                    <li><b>DURATION OF WORK</b></li>
                        <p>Subject to the terms of this letter, you will be engaged for a minimum of four hours on any day on which you are offered and accept an Assignment.</p>
                    <li><b>REMUNERATION</b>
                    <br>
                        <table>
                            <tr><td><b>6.1</b> Your remuneration will be as advised per Assignment and in accordance with any applicable statutory or legislative obligations.</td></tr>
                            <tr><td><b>6.2</b> Chandler Personnel will pay you on a weekly basis by way of electronic funds transfer to your nominated bank account, subject to timesheets being received and authorised by relevant Clients.</td></tr>
                            <tr><td><b>6.3</b> Chandler Personnel will make superannuation contributions on your behalf in accordance with its obligations at law.  You may choose a superannuation fund by completing the “standard choice form” provided to you by Chandler Personnel or else your funds will be directed into the RSSF Superannuation fund.</td></tr>
                            <tr><td><b>6.4</b> It is a condition to the offer of employment contained in this letter that you provide Chandler Personnel with accurate personal information such as bank account details, tax file number and superannuation fund information.</td></tr>
                            <tr><td><b>6.5</b> If at any time an industrial instrument (including an Award) applies to your employment, you agree that your wages are in satisfaction of your entitlements (including without limitation, annual leave loading, minimum wage, overtime and penalties) under the relevant industrial instrument and that the relevant industrial instrument does not form part of the terms of this letter.</td></tr>
                        </table>
                    </li>
                    <li><b>LEAVE</b>
                    <br>
                        <table>
                            <tr><td><b>7.1</b> As a casual employee you are not entitled to paid annual leave or personal leave.</td></tr>
                            <tr><td><b>7.2</b> You are entitled to take two days unpaid compassionate leave where a member of your immediate family or household has a personal illness or injury which poses a serious threat to their life or dies. The two days need not be consecutive.</td></tr>
                            <tr><td><b>7.3</b> You are entitled to take two days of unpaid carer’s leave if you are required to care for a member of your immediate family or household. The two days need not be consecutive.</td></tr>
                        </table>
                    </li>
                    <li><b>REPRESENTATION OF SKILLS</b>
                    <br>
                        <table>
                            <tr><td><b>8.1</b> You acknowledge that the decision by Chandler Personnel to employ you was based on the qualifications, licences, experience, and expertise which you have represented to us that you have.</td></tr>
                            <tr><td><b>8.2</b> You acknowledge and agree that your employment with Chandler Personnel may be immediately terminated in the event that any information relating to your qualifications, experience and/or expertise is found to be false or misleading.</td></tr>
                        </table>
                    </li>
                    <li><b>PROVISION OF SERVICES</b>
                    <br>
                        <table>
                            <tr><td><b>9.1</b> You are employed to provide a variety of services for Clients in accordance with this letter, although the specific type of tasks which you will be directed to perform by Clients may vary from time to time.</td></tr>
                            <tr><td><b>9.2</b> Prior to or as close as reasonably possible to the commencement of each Assignment, we will provide you with the Conditions of Assignment.</td></tr>
                            <tr><td><b>9.3</b> You are deemed to have accepted the Conditions of Assignment if Chandler Personnel has sent a copy of them to your nominated email address or mobile number either prior to, or as close as reasonably possible to, the commencement of the Assignment and you have commenced the Assignment, unless you advise us otherwise prior to attending the site to commence the Assignment.</td></tr>
                            <tr><td><b>9.4</b> The Client will provide all necessary equipment, vehicles and plant required for you to adequately perform the Services required under the Assignment.</td></tr>
                            <tr>
                                <td><b>9.5</b> In providing the Services, you undertake to:
                                <br>
                                    <table>
                                        <tr><td><b>9.5.1</b> make yourself available to Chandler Personnel and the Clients to provide the Services during those hours that may be necessary to fully and properly provide the Services;</td></tr>
                                        <tr><td><b>9.5.2</b> ensure that you perform the Services with due care, skill and diligence and to the best of your skill and ability;</td></tr>
                                        <tr><td><b>9.5.3</b> ensure that you are properly trained to perform your duties safely and without risk of injury to any person;</td></tr>
                                        <tr><td><b>9.5.4</b> strictly comply with all applicable laws in any way applicable to the provision of the Services;</td></tr>
                                        <tr><td><b>9.5.5</b> strictly comply with all policies of Chandler Personnel and the Client as varied and communicated to you from time to time, while acknowledging that such policies are not incorporated into and do not otherwise form part of this letter;</td></tr>
                                        <tr><td><b>9.5.6</b> use your best endeavours to promote and serve the interests of Chandler Personnel; and</td></tr>
                                        <tr><td><b>9.5.7</b> not, without prior approval of Chandler Personnel, provide Services to any other person whose interests, in Chandler Personnel’s reasonable opinion, may conflict with the interests of Chandler Personnel or the Clients.</td></tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li><b>EQUIPMENT</b>
                    <br>
                        <table>
                            <tr><td><b>10.1</b> Where you use your own equipment to perform work for the Client, you warrant that the equipment is in proper working order.  You agree to take responsibility for any downtime due to your equipment\'s malfunction and not to charge for your time while your equipment is not in working condition.</td></tr>
                            <tr><td><b>10.2</b> You warrant that all of your computer equipment and software that you use to perform work for the Client is licensed and virus free.</td></tr>
                            <tr><td><b>10.3</b> You must not without authority from the Client introduce into the Client\'s computer equipment by any means any software, program or data.</td></tr>
                        </table>
                    </li>
                    <li><b>SAFETY</b>
                    <br>
                        <table>
                            <tr><td><b>11.1</b> You must exercise reasonable care and diligence in the performance of your duties and comply with all reasonable instructions provided to you by the Client to protect your own health and safety and the health and safety of others.</td></tr>
                            <tr><td><b>11.2</b> You must not commence active duties as part of an Assignment unless and until you:
                                <br>
                                    <table>
                                        <tr><td><b>11.2.1</b> have been authorised to do so by the Client;</td></tr>
                                        <tr><td><b>11.2.2</b> have received proper training from the Client as to how to perform the duties in a safe manner; and</td></tr>
                                        <tr><td><b>11.2.3</b> understand the Client’s emergency and accident procedures, including details of the Client’s designated safety representatives.</td></tr>
                                    </table>
                                </td>
                            </tr>
                            <tr><td><b>11.3</b> You must report any work-related injuries, or injuries which will affect your capacity to work, to the Client’s designated representatives and to Chandler Personnel as soon as practicable.</td></tr>
                            <tr><td><b>11.4</b> Where you have concerns that you are not equipped with the skills, training or plant, equipment or vehicles to perform a task in a safe manner, you must not perform the relevant task and must notify Chandler Personnel of this concern as soon as is reasonably practicable.  Upon such contact, Chandler Personnel will liaise with the Client about the said task as soon as is reasonably practicable.</td></tr>
                            <tr><td><b>11.5</b> Where you have concerns that your working environment is unsafe for any reason, you must notify Chandler Personnel as soon as is reasonably practicable.  Upon such notification, Chandler Personnel will liaise with the Client about your concerns as soon as is reasonably practicable.</td></tr>
                            <tr><td><b>11.6</b> You must not consume or possess any substance (illegal or legal) that is likely to impair your ability to properly and safely carry out your duties at any time at your place of work, or on or in any property (including vehicles) owned or used by a Client.</td></tr>
                        </table>
                    </li>
                    <li><b>ACKNOWLEDGEMENTS</b>
                        <br>
                        You acknowledge that:
                        <br>
                        <table>
                            <tr><td><b>12.1</b> Chandler Personnel is in the business of providing its labour hire employees to Clients for an agreed period of time in order for those employees to perform work as directed by the Client;</td></tr>
                            <tr><td><b>12.2</b> pursuant to this letter, Chandler Personnel may offer you an Assignment at a Client’s worksite. The location, duration and rate of pay applicable to each such Assignment will be advised at the time such an offer is made;</td></tr>
                            <tr><td><b>12.3</b> the location, duration and rate of pay for each Assignment may vary from Assignment to Assignment;</td></tr>
                            <tr><td><b>12.4</b> from the time that you report for duty at a Client’s worksite, you are under the care, control and supervision of the Client for the duration of the Assignment;</td></tr>
                            <tr><td><b>12.5</b> Chandler Personnel is not liable to a Client for any damage, loss or injury of whatsoever nature or kind, however caused, whether by the negligence of you or otherwise, which may be suffered or incurred, whether directly or indirectly, in respect of the Services provided to a Client by you under this letter.</td></tr>
                        </table>
                    </li>
                    <li><b>MEDICAL AND DRUG & ALCOHOL TESTING</b>
                        <br>
                        <table>
                            <tr><td><b>13.1</b> You consent to undergoing during your own time and at your own expense a medical examination, including drug and alcohol testing, and for the results from such testing to be provided to Chandler Personnel in order to determine your fitness for duties.  Employment by Chandler Personnel is conditional upon Chandler Personnel being satisfied that the results from such testing indicate that you are fit to perform the duties required under this letter.</td></tr>
                            <tr><td><b>13.2</b> You consent to undergoing random drug and alcohol testing at any time as directed by Chandler Personnel.  The expense of such random testing will be met by Chandler Personnel.  A failure to comply with this requirement may result in disciplinary action being taken against you by Chandler Personnel.</td></tr>
                        </table>
                    </li>
                    <li><b>PERSONAL PROTECTIVE EQUIPMENT</b>
                        <br>
                        <table>
                            <tr><td><b>14.1</b> Unless otherwise advised, personal protective equipment (“PPE”) in the form of safety footwear and clothing will be provided by you and at your expense and must be to a standard which is satisfactory to Chandler Personnel.</td></tr>
                            <tr><td><b>14.2</b> You agree that Chandler Personnel can deduct from your wages the cost of repairing or replacing any personal protective equipment which is provided to you by a Client as part of an Assignment and which is damaged otherwise than by normal wear and tear while in your possession.</td></tr>
                            <tr><td><b>14.3</b> At the end of an Assignment, or sooner if required by a Client, you will deliver to a Client any PPE, or any other property of a Client, which is in your possession or control.</td></tr>
                        </table>
                    </li>
                    <li><b>APPROPRIATE WORKPLACE BEHAVIOUR</b>
                        <p>You must at all times during your employment with Chandler Personnel and during an Assignment with a Client conduct yourself in an appropriate manner which includes but is not limited to ensuring that you comply with all legislative requirements prohibiting conduct which constitutes unlawful discrimination and/or harassment and/or bullying contrary to occupational health and safety legislation.</p>
                    </li>
                    <li><b>EXPENSES</b>
                        <p>Chandler Personnel will reimburse you for any business expenses that you may incur in performing your duties pursuant to this letter in accordance with Chandler Personnel\'s expense approval procedures and policies and subject to satisfactory verification.  Reimbursement is also subject to receipt of a valid tax invoice.</p>
                    </li>
                    <li><b>TERMINATION OF AN ASSIGNMENT</b>
                        <br>
                        <table>
                            <tr><td><b>17.1</b> An Assignment may be terminated upon the provision of not less than one hour’s notice to you.  Notice may be provided to you by way of a personal visit, an email, a telephone call or an SMS to your mobile telephone.</td></tr>
                            <tr><td><b>17.2</b> Chandler Personnel may elect to make payment in lieu of all, or part, of the notice period.</td></tr>
                            <tr><td><b>17.3</b> If you have attended a Client’s work site as directed by Chandler Personnel and an Assignment is cancelled by Chandler Personnel with less than one hour’s notice having been provided, you will be paid for the minimum period of time required by the relevant legislation, Award or agreement.</td></tr>
                            <tr><td><b>17.4</b> If you are unable to attend for an Assignment for any reason whatsoever, you are required to provide Chandler Personnel with a minimum of one hour’s notice.</td></tr>
                        </table>    
                    </li>
                    <li><b>CONFIDENTIALITY</b>
                        <br>
                        <table>
                            <tr><td><b>18.1</b> During or after the termination of your employment, you must not, except in the proper course of your duties or if compelled to do so by law, divulge to anyone any confidential information including but not limited to information concerning the processes, methods, systems, programs, manuals, reports, books, data, business transactions, trade secrets, client lists, price structures, marketing strategies, operations, dealings, finances or affairs of Chandler Personnel or of any of its Clients.</td></tr>
                            <tr><td><b>18.2</b> You must not use or attempt to use any such confidential information in any manner which may injure or cause loss either directly or indirectly to Chandler Personnel or a Client.</td></tr>
                            <tr><td><b>18.3</b> The requirement to maintain confidentiality will continue to apply after the termination of your employment.</td></tr>
                        </table>
                    </li>
                    <li><b>TERMINATION OF EMPLOYMENT</b>
                        <br>
                        <table>
                            <tr><td><b>19.1</b> Either you or Chandler Personnel can terminate the employment relationship in accordance with any relevant legislation, Award or agreement.</td></tr>
                            <tr><td><b>19.2</b> Your employment may be immediately terminated if you commit any dishonest act, serious misconduct or other act that justifies immediate dismissal, or you are precluded from performing your duties of employment.</td></tr>
                        </table>
                    </li>
                    <li><b>OFFER OF EMPLOYMENT BY THE CLIENT</b>
                        <br>
                        <table>
                            <tr><td><b>20.1</b> In consideration of your remuneration and to protect Chandler Personnel’s business and goodwill, you will not at any time during your Assignment or for a period of 3 months following the termination of your Assignment undertake work for a Client or any Related Person of its or their businesses or subcontractors for whom you have specifically worked during your Assignment, unless through Chandler Personnel or with the written consent of Chandler Personnel, which it may withhold at its absolute discretion.</td></tr>
                            <tr><td><b>20.2</b> You acknowledge and agree that:
                                <br>
                                <table>
                                    <tr><td><b>20.2.1</b> the restrictions in paragraph 20.1 are fair and reasonable in all the circumstances and are reasonably necessary to protect the business, reputation and goodwill of Chandler Personnel and any Related Person;</td></tr>
                                    <tr><td><b>20.2.2</b> you have had the opportunity to obtain legal advice as to the meaning and effect of paragraph 20.1;</td></tr>
                                    <tr><td><b>20.2.3</b> damages may not be an adequate remedy to protect the interests of Chandler Personnel or any Related Person affected, and a breach of this post-termination restraint may result in Chandler Personnel seeking an injunction against you; and</td></tr>
                                    <tr><td><b>20.2.4</b> the restrictions in paragraph 20.1 will survive the termination of your employment and will remain in full force and effect after the termination of your employment.</td></tr>
                                </table>
                                </td>
                            </tr>
                        </table>
                    </li>
                    <li><b>RETURN OF PROPERTY</b>
                    <p>On the termination of the Assignment, you must immediately return to Chandler Personnel all Chandler Personnel property that is then in your possession or control (if any).</p>
                    </li>
                    <li><b>INDEMNITIES</b>
                    <p>You indemnify Chandler Personnel and will keep it indemnified against all claims, liabilities, losses, costs (on an indemnity basis whether incurred by or awarded against Chandler Personnel) and expenses it may incur arising out of a breach of any of your obligations under this letter, at law, or in performing the Services, except to the extent it has been caused or contributed to by the negligence of Chandler Personnel, its employees or agents.</p>
                    </li>
                    <li><b>MISCELLANEOUS</b>
                        <br>
                        <table>
                            <tr><td><b>23.1</b> The failure of Chandler Personnel at any time to insist on performance of any term of this letter is not a waiver of its right at any later time to insist on performance of that or any other term of this letter.</td></tr>
                            <tr><td><b>23.2</b> The terms and conditions of your employment cannot be varied unless such variation is in writing and signed by both you and Chandler Personnel.</td></tr>
                            <tr><td><b>23.3</b> The terms of this letter are separate, distinct and severable so that the unenforceability of any term in no way affects the enforceability of any other term.</td></tr>
                            <tr><td><b>23.4</b> The terms of this letter are confidential and may not be disclosed by you to any other person without the prior written approval of Chandler Personnel, other than for the purposes of obtaining professional legal or accounting advice.</td></tr>
                            <tr><td><b>23.5</b> Headings are for ease of reference and do not affect the meaning of this letter.</td></tr>
                            <tr><td><b>23.6</b> This letter constitutes the whole and entire agreement between the parties in respect of the subject matter of this letter and supersedes any prior representation, understanding or arrangement given or made by the parties whether orally or in writing.</td></tr>
                            <tr><td><b>23.7</b> Your employment will be governed by and construed in accordance with the laws of the State of Victoria, Australia and the parties unreservedly submit to the jurisdiction of the Courts of that State.</td></tr>
                            <tr><td><b>23.8</b> You acknowledge and agree that they have had the opportunity to seek independent legal advice in respect of the terms of this letter, and further acknowledge and agree that you have read and understood the terms of this letter.</td></tr>
                    </li>
                </ol>
                <p>If you have any queries concerning the contents of this letter, please contact me.  Otherwise, please sign the enclosed copy of this letter as an acknowledgement that you have read and understood this letter and that you accept the offer of employment contained within it.</p>
                <p>Yours sincerely</p>
                <br>
                <p><b>Perry De Silva</b></p>
                <p><b>Director</b></p><table><tr><td style="border-top: 1px solid black">&nbsp;</td></tr></table>
<p>I acknowledge that I have read and understood this letter and accept the terms and conditions of employment as set out in this letter and its appendices.</p>
                <br>
        </div>
        <table cellpadding="6" cellspacing="6" border="0">
            <tr>
                <td>
                    <div id="signature" class="signInfo"><img src="'.$signaturePath.'"/></div>
                    <b>Signature</b>
                </td>
                <td><br><br><br><br><br><div class="sgName signInfo">'.$fullName.'</div><b>Name</b></td>
                <td><br><br><br><br><br><div class="signInfo">'.$curDate.'</div><b>Date</b></td>
            </tr>
        </table>
    </div>';
    $fileName = 'termsForm_'.time();
    $filePath = './sgterms/'.$fileName.'.pdf';
    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->lastPage();
    /* $totalPageCount = $pdf->getNumPages();
     if(($pdf->getPage() == $totalPageCount)) {
         $pdf->deletePage($totalPageCount);
     }*/
    ob_clean();
    $pdf->Output(__DIR__.'/sgterms/'.$fileName.'.pdf', 'F');
    try {
        echo generateEmploymentTermsMail($fullName,$conEmail,$filePath);
    }catch (Exception $e1){
        echo $e1->getMessage();
    }
}catch (Exception $e){
    echo  $e->getMessage();
}

