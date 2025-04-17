<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if(empty($_REQUEST['candidateId'])){
    $msg = "Access Denied";
    header("Location:error.php?error=$msg");
}elseif(validateEmploymentTermDocumentSigned($mysqli,$_REQUEST['candidateId'])){
    $msg = "Employment Terms Signed submitted";
    header("Location:error.php?error=$msg");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <!-- BOOTSTRAP JS -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!-- you load jquery somewhere before jSignature...-->
    <script src="js/jSignature/jSignature.min.js"></script>
    <!-- Jquery Form Validator -->
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.base64.js"></script>
    <script type="text/javascript" src="js/daterangepicker/moment.js"></script>
    <script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
    <style>
        .error{
            color: red;
        }
        label{
            font-weight: normal;
        }
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
    </style>
</head>
<body>
<div id="header" style="padding: 20px 20px 20px 20px">
    <img src="img/logo.png" width="220" height="50">
</div>
<div class="container">
<h2>Terms of Employment</h2>
<h4>Employment with <?php echo DOMAIN_NAME; ?> Pty Limited (ACN 091 298 234) ("<?php echo DOMAIN_NAME; ?>")</h4>
<br>
<div>Following our recent discussions, it is with pleasure that I offer you casual employment with <?php echo DOMAIN_NAME; ?>.</div>
<div>Please read the terms below carefully as this offer is conditional on you agreeing to the terms contained in it.</div>
    <br>
<div><b>1.	DEFINITIONS</b></div>
    <br>
<div>1.1.	Assignment means a work placement in which you will perform the Services for a Client as directed by the Client.</div>
<div>1.2.	Client means a client of <?php echo DOMAIN_NAME; ?> and Clients means more than one client of <?php echo DOMAIN_NAME; ?>.</div>
<div>1.3.	Conditions of Assignment means the location, start time, finish time (if known) and pay rate of the Assignment, and who the Candidate it required to report to during the Assignment.
</div>
<div>1.4.	Related Person means a “related body corporate” as that expression is defined in the Corporations Act 2001 (Cth) or any legislation replacing the Corporations Act 2001 (Cth).</div>
<div>1.5.	Services means the services to be performed by you for a Client during an Assignment.</div>
    <br>
<div><b>2.	NATURE OF EMPLOYMENT RELATIONSHIP</b></div>
    <br>
<div>2.1.	The relationship between you and us is that of casual employee and employer.</div>
<div>2.2.	You acknowledge and agree that this offer of employment does not involve any representation or expectation of continuing work or regular and systematic engagement.</div>
<div>2.3.	This offer of employment also does not involve any representation by the Company that you will become either a temporary or permanent employee of a Client or that you will be given priority or precedence over any other employee or applicant for an Assignment with a Client.
</div>
<div>2.4.	Without limiting the above-mentioned clause, this letter does not make, constitute or appoint you as a temporary or permanent employee of, or independent contractor to, <?php echo DOMAIN_NAME; ?>.
</div>
<div>2.5.	If your name appears on a roster it will be purely for administrative purposes and in no way represents regular, systematic or ongoing engagement or employment.
</div>
    <br>
<div><b>3.	WORK FOR OTHER EMPLOYERS</b></div>
    <br>
<div>3.1.	Consistent with the casual nature of your employment relationship with <?php echo DOMAIN_NAME; ?>, the parties to this letter acknowledge and agree that you may perform work and services for other persons, provided that any such work or Services do not conflict with your obligations to <?php echo DOMAIN_NAME; ?> under this letter.
</div>
<div>3.2.	Prior to commencing each Assignment, you will declare to <?php echo DOMAIN_NAME; ?> any conflict of interest or potential conflict of interest that you may have.</div>
    <br>
    <div><b>4.	COMMENCEMENT DATE</b></div>
    <br>
<div>The terms and conditions of employment contained in this letter will commence on the below signed date and will continue until terminated in accordance with the terms of this letter.
</div>
    <br>
<div><b>5.	DURATION OF WORK</b></div>
    <br>
<div>Subject to the terms of this letter, you will be engaged for a minimum of four hours on any day on which you are offered and accept an Assignment.
</div>
    <br>
<div><b>6.	REMUNERATION</b></div>
    <br>
<div>6.1.	Your remuneration will be as advised per Assignment and in accordance with any applicable statutory or legislative obligations.</div>
<div>6.2.	<?php echo DOMAIN_NAME; ?> will pay you on a weekly basis by way of electronic funds transfer to your nominated bank account, subject to timesheets being received and authorised by relevant Clients.
</div>
<div>6.3.	<?php echo DOMAIN_NAME; ?> will make superannuation contributions on your behalf in accordance with its obligations at law.  You may choose a superannuation fund by completing the “standard choice form” provided to you by <?php echo DOMAIN_NAME; ?> or else your funds will be directed into the RSSF Superannuation fund.
</div>
<div>6.4.	It is a condition to the offer of employment contained in this letter that you provide <?php echo DOMAIN_NAME; ?> with accurate personal information such as bank account details, tax file number and superannuation fund information.
</div>
<div>6.5.	If at any time an industrial instrument (including an Award) applies to your employment, you agree that your wages are in satisfaction of your entitlements (including without limitation, annual leave loading, minimum wage, overtime and penalties) under the relevant industrial instrument and that the relevant industrial instrument does not form part of the terms of this letter.
</div>
    <br>
<div><b>7.	LEAVE</b></div>
    <br>
<div>7.1.	As a casual employee you are not entitled to paid annual leave or personal leave.</div>
<div>7.2.	You are entitled to take two days unpaid compassionate leave where a member of your immediate family or household has a personal illness or injury which poses a serious threat to their life or dies. The two days need not be consecutive.
</div>
<div>7.3.	You are entitled to take two days of unpaid carer’s leave if you are required to care for a member of your immediate family or household. The two days need not be consecutive.
</div>
    <br>
<div><b>8.	REPRESENTATION OF SKILLS</b></div>
    <br>
<div>8.1.	You acknowledge that the decision by <?php echo DOMAIN_NAME; ?> to employ you was based on the qualifications, licences, experience, and expertise which you have represented to us that you have.
</div>
<div>8.2.	You acknowledge and agree that your employment with <?php echo DOMAIN_NAME; ?> may be immediately terminated in the event that any information relating to your qualifications, experience and/or expertise is found to be false or misleading.
</div>
    <br>
<div><b>9.	PROVISION OF SERVICES</b></div>
    <br>
<div>9.1.	You are employed to provide a variety of services for Clients in accordance with this letter, although the specific type of tasks which you will be directed to perform by Clients may vary from time to time.
</div>
<div>9.2.	Prior to or as close as reasonably possible to the commencement of each Assignment, we will provide you with the Conditions of Assignment.
</div>
<div>9.3.	You are deemed to have accepted the Conditions of Assignment if <?php echo DOMAIN_NAME; ?> has sent a copy of them to your nominated email address or mobile number either prior to, or as close as reasonably possible to, the commencement of the Assignment and you have commenced the Assignment, unless you advise us otherwise prior to attending the site to commence the Assignment.</div>
<div>9.4.	The Client will provide all necessary equipment, vehicles and plant required for you to adequately perform the Services required under the Assignment.</div>
<div><b>9.5.	In providing the Services, you undertake to:</b></div>
<div>9.5.1.	make yourself available to <?php echo DOMAIN_NAME; ?> and the Clients to provide the Services during those hours that may be necessary to fully and properly provide the Services;
</div>
<div>9.5.2.	ensure that you perform the Services with due care, skill and diligence and to the best of your skill and ability;</div>
<div>9.5.3.	ensure that you are properly trained to perform your duties safely and without risk of injury to any person;</div>
<div>9.5.4.	strictly comply with all applicable laws in any way applicable to the provision of the Services;</div>
<div>9.5.5.	strictly comply with all policies of <?php echo DOMAIN_NAME; ?> and the Client as varied and communicated to you from time to time, while acknowledging that such policies are not incorporated into and do not otherwise form part of this letter;
</div>
<div>9.5.6.	use your best endeavours to promote and serve the interests of <?php echo DOMAIN_NAME; ?>; and
</div>
<div>9.5.7.	not, without prior approval of <?php echo DOMAIN_NAME; ?>, provide Services to any other person whose interests, in <?php echo DOMAIN_NAME; ?>’s reasonable opinion, may conflict with the interests of <?php echo DOMAIN_NAME; ?> or the Clients.
</div>
    <br>
<div><b>10.	EQUIPMENT</b></div>
    <br>
<div>10.1.	Where you use your own equipment to perform work for the Client, you warrant that the equipment is in proper working order.  You agree to take responsibility for any downtime due to your equipment's malfunction and not to charge for your time while your equipment is not in working condition.
</div>
<div>10.2.	You warrant that all of your computer equipment and software that you use to perform work for the Client is licensed and virus free.
</div>
<div>10.3.	You must not without authority from the Client introduce into the Client's computer equipment by any means any software, program or data.
</div>
    <br>
<div><b>11.	SAFETY</b></div>
    <br>
<div>11.1.	You must exercise reasonable care and diligence in the performance of your duties and comply with all reasonable instructions provided to you by the Client to protect your own health and safety and the health and safety of others.
</div>
<div><b>11.2.	You must not commence active duties as part of an Assignment unless and until you:</b>
</div>
<div>11.2.1.	have been authorised to do so by the Client;</div>
<div>11.2.2.	have received proper training from the Client as to how to perform the duties in a safe manner; and</div>
<div>11.2.3.	understand the Client’s emergency and accident procedures, including details of the Client’s designated safety representatives.</div>
<div>11.3.	You must report any work-related injuries, or injuries which will affect your capacity to work, to the Client’s designated representatives and to <?php echo DOMAIN_NAME; ?> as soon as practicable.
</div>
<div>11.4.	Where you have concerns that you are not equipped with the skills, training or plant, equipment or vehicles to perform a task in a safe manner, you must not perform the relevant task and must notify <?php echo DOMAIN_NAME; ?> of this concern as soon as is reasonably practicable.  Upon such contact, <?php echo DOMAIN_NAME; ?> will liaise with the Client about the said task as soon as is reasonably practicable.
</div>
<div>11.5.	Where you have concerns that your working environment is unsafe for any reason, you must notify <?php echo DOMAIN_NAME; ?> as soon as is reasonably practicable.  Upon such notification, <?php echo DOMAIN_NAME; ?> will liaise with the Client about your concerns as soon as is reasonably practicable.
</div>
<div>11.6.	You must not consume or possess any substance (illegal or legal) that is likely to impair your ability to properly and safely carry out your duties at any time at your place of work, or on or in any property (including vehicles) owned or used by a Client.
</div>
    <br>
<div><b>12.	ACKNOWLEDGEMENTS</b></div>
    <br>
<div>You acknowledge that:</div>
<div>12.1.	<?php echo DOMAIN_NAME; ?> is in the business of providing its labour hire employees to Clients for an agreed period of time in order for those employees to perform work as directed by the Client;
</div>
<div>12.2.	pursuant to this letter, <?php echo DOMAIN_NAME; ?> may offer you an Assignment at a Client’s worksite. The location, duration and rate of pay applicable to each such Assignment will be advised at the time such an offer is made;
</div>
<div>12.3.	the location, duration and rate of pay for each Assignment may vary from Assignment to Assignment;
</div>
<div>12.4.	from the time that you report for duty at a Client’s worksite, you are under the care, control and supervision of the Client for the duration of the Assignment;
</div>
<div>12.5.	<?php echo DOMAIN_NAME; ?> is not liable to a Client for any damage, loss or injury of whatsoever nature or kind, however caused, whether by the negligence of you or otherwise, which may be suffered or incurred, whether directly or indirectly, in respect of the Services provided to a Client by you under this letter.
</div>
    <br>
<div><b>13.	MEDICAL AND DRUG & ALCHOL TESTING</b></div>
    <br>
<div>13.1.	You consent to undergoing during your own time and at your own expense a medical examination, including drug and alcohol testing, and for the results from such testing to be provided to <?php echo DOMAIN_NAME; ?> in order to determine your fitness for duties.  Employment by <?php echo DOMAIN_NAME; ?> is conditional upon <?php echo DOMAIN_NAME; ?> being satisfied that the results from such testing indicate that you are fit to perform the duties required under this letter.
</div>
<div>13.2.	You consent to undergoing random drug and alcohol testing at any time as directed by <?php echo DOMAIN_NAME; ?>.  The expense of such random testing will be met by <?php echo DOMAIN_NAME; ?>.  A failure to comply with this requirement may result in disciplinary action being taken against you by <?php echo DOMAIN_NAME; ?>.
</div>
    <br>
<div><b>14.	PERSONAL PROTECTIVE EQUIPMENT</b></div>
    <br>
<div>14.1.	Unless otherwise advised, personal protective equipment (“PPE”) in the form of safety footwear and clothing will be provided by you and at your expense and must be to a standard which is satisfactory to <?php echo DOMAIN_NAME; ?>.
</div>
<div>14.2.	You agree that <?php echo DOMAIN_NAME; ?> can deduct from your wages the cost of repairing or replacing any personal protective equipment which is provided to you by a Client as part of an Assignment and which is damaged otherwise than by normal wear and tear while in your possession.
</div>
<div>14.3.	At the end of an Assignment, or sooner if required by a Client, you will deliver to a Client any PPE, or any other property of a Client, which is in your possession or control.
</div>
    <br>
<div><b>15.	APPROPRIATE WORKPLACE BEHAVIOUR</b></div>
    <br>
<div>You must at all times during your employment with <?php echo DOMAIN_NAME; ?> and during an Assignment with a Client conduct yourself in an appropriate manner which includes but is not limited to ensuring that you comply with all legislative requirements prohibiting conduct which constitutes unlawful discrimination and/or harassment and/or bullying contrary to occupational health and safety legislation.
</div>
    <br>
<div><b>16.	EXPENSES</b></div>
    <br>
<div><?php echo DOMAIN_NAME; ?> will reimburse you for any business expenses that you may incur in performing your duties pursuant to this letter in accordance with <?php echo DOMAIN_NAME; ?>'s expense approval procedures and policies and subject to satisfactory verification.  Reimbursement is also subject to receipt of a valid tax invoice.</div>
    <br>
    <div><b>17.	TERMINATION OF AN ASSIGNMENT</b></div>
    <br>
<div>17.1.	An Assignment may be terminated upon the provision of not less than one hour’s notice to you.  Notice may be provided to you by way of a personal visit, an email, a telephone call or an SMS to your mobile telephone.
</div>
<div>17.2.	<?php echo DOMAIN_NAME; ?> may elect to make payment in lieu of all, or part, of the notice period.</div>
<div>17.3.	If you have attended a Client’s work site as directed by <?php echo DOMAIN_NAME; ?> and an Assignment is cancelled by <?php echo DOMAIN_NAME; ?> with less than one hour’s notice having been provided, you will be paid for the minimum period of time required by the relevant legislation, Award or agreement.</div>
<div>17.4.	If you are unable to attend for an Assignment for any reason whatsoever, you are required to provide <?php echo DOMAIN_NAME; ?> with a minimum of 4 hour’s notice.
</div>
    <br>
<div><b>18.	CONFIDENTIALITY</b></div>
    <br>
<div>18.1.	During or after the termination of your employment, you must not, except in the proper course of your duties or if compelled to do so by law, divulge to anyone any confidential information including but not limited to information concerning the processes, methods, systems, programs, manuals, reports, books, data, business transactions, trade secrets, client lists, price structures, marketing strategies, operations, dealings, finances or affairs of <?php echo DOMAIN_NAME; ?> or of any of its Clients.
</div>
<div>18.2.	You must not use or attempt to use any such confidential information in any manner which may injure or cause loss either directly or indirectly to <?php echo DOMAIN_NAME; ?> or a Client.
</div>
<div>18.3.	The requirement to maintain confidentiality will continue to apply after the termination of your employment.
</div>
    <br>
<div><b>19.	TERMINATION OF EMPLOYMENT</b></div>
    <br>
<div>19.1.	Either you or <?php echo DOMAIN_NAME; ?> can terminate the employment relationship in accordance with any relevant legislation, Award or agreement.</div>
<div>19.2.	Your employment may be immediately terminated if you commit any dishonest act, serious misconduct or other act that justifies immediate dismissal, or you are precluded from performing your duties of employment.
</div>
    <br>
<div><b>20.	OFFER OF EMPLOYMENT BY THE CLIENT</b></div>
    <br>
<div>20.1.	In consideration of your remuneration and to protect <?php echo DOMAIN_NAME; ?>’s business and goodwill, you will not at any time during your Assignment or for a period of 3 months following the termination of your Assignment undertake work for a Client or any Related Person of its or their businesses or subcontractors for whom you have specifically worked during your Assignment, unless through <?php echo DOMAIN_NAME; ?> or with the written consent of <?php echo DOMAIN_NAME; ?>, which it may withhold at its absolute discretion.
</div>
<div><b>20.2.	You acknowledge and agree that:</b></div>
<div>20.2.1.	the restrictions in paragraph 20.1 are fair and reasonable in all the circumstances and are reasonably necessary to protect the business, reputation and goodwill of <?php echo DOMAIN_NAME; ?> and any Related Person;
</div>
<div>20.2.2.	you have had the opportunity to obtain legal advice as to the meaning and effect of paragraph 20.1;</div>
<div>20.2.3.	damages may not be an adequate remedy to protect the interests of <?php echo DOMAIN_NAME; ?> or any Related Person affected, and a breach of this post-termination restraint may result in <?php echo DOMAIN_NAME; ?> seeking an injunction against you; and</div>
<div>20.2.4.	the restrictions in paragraph 20.1 will survive the termination of your employment and will remain in full force and effect after the termination of your employment.</div>
    <br>
    <div><b>21.	RETURN OF PROPERTY</b></div>
    <br>
<div>On the termination of the Assignment, you must immediately return to <?php echo DOMAIN_NAME; ?> all <?php echo DOMAIN_NAME; ?> property that is then in your possession or control (if any).</div>
    <br>
    <div><b>22.	INDEMNITIES</b></div>
    <br>
<div>You indemnify <?php echo DOMAIN_NAME; ?> and will keep it indemnified against all claims, liabilities, losses, costs (on an indemnity basis whether incurred by or awarded against <?php echo DOMAIN_NAME; ?>) and expenses it may incur arising out of a breach of any of your obligations under this letter, at law, or in performing the Services, except to the extent it has been caused or contributed to by the negligence of <?php echo DOMAIN_NAME; ?>, its employees or agents.
</div>
    <br>
<div><b>23.	MISCELLANEOUS</b></div>
    <br>
<div>23.1.	The failure of <?php echo DOMAIN_NAME; ?> at any time to insist on performance of any term of this letter is not a waiver of its right at any later time to insist on performance of that or any other term of this letter.
</div>
<div>23.2.	The terms and conditions of your employment cannot be varied unless such variation is in writing and signed by both you and <?php echo DOMAIN_NAME; ?>.</div>
<div>23.3.	The terms of this letter are separate, distinct and severable so that the unenforceability of any term in no way affects the enforceability of any other term.</div>
<div>23.4.	The terms of this letter are confidential and may not be disclosed by you to any other person without the prior written approval of <?php echo DOMAIN_NAME; ?>, other than for the purposes of obtaining professional legal or accounting advice.
</div>
<div>23.5.	Headings are for ease of reference and do not affect the meaning of this letter.</div>
<div>23.6.	This letter constitutes the whole and entire agreement between the parties in respect of the subject matter of this letter and supersedes any prior representation, understanding or arrangement given or made by the parties whether orally or in writing.</div>
<div>23.7.	Your employment will be governed by and construed in accordance with the laws of the State of Victoria, Australia and the parties unreservedly submit to the jurisdiction of the Courts of that State.
</div>
<div>23.8.	You acknowledge and agree that they have had the opportunity to seek independent legal advice in respect of the terms of this letter, and further acknowledge and agree that you have read and understood the terms of this letter.
</div>
<div>If you have any queries concerning the contents here, please contact <?php echo DOMAIN_NAME; ?> on 03 9656 9777. Otherwise, please sign below to acknowledge that you have read and understood all terms & conditions and that you accept the offer of employment contained within it.</div>
<div>Yours sincerely</div>
<div><b>Perry De Silva</b></div>
<div><b>Director</b></div>
<br>
<label>I acknowledge that I have read and understood this letter and accept the terms and conditions of employment as set out in this letter and its appendices.</label>
    <br>
<form name="frmTermsForm" id="frmTermsForm" class="frmTermsForm" method="post">
    <div class="row">
        <section class="col col sm-6">
            &nbsp;&nbsp;&nbsp;&nbsp;<b>Name:</b><?php echo base64_decode($_REQUEST['firstName']).' '.base64_decode($_REQUEST['lastName']);?>&nbsp;&nbsp;<b>Date:</b>&nbsp;<?php echo date('d/m/Y');?>
            <input type="hidden" name="firstName" id="firstName" value="<?php echo $_REQUEST['firstName'];?>">
            <input type="hidden" name="lastName" id="lastName" value="<?php echo $_REQUEST['lastName'];?>">
            <input type="hidden" name="candidateId" id="candidateId" value="<?php echo $_REQUEST['candidateId'];?>">
            <input type="hidden" name="conEmail" id="conEmail" value="<?php echo $_REQUEST['conEmail'];?>">
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-6">
            <label>Candidate Signature</label>
            <div id="signature"></div>
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-6">
            <br>
            <input type="submit" id="empTermsBtn" class="empTermsBtn btn-success btn-lg" value="Submit"/>
        </section>
    </div>
</form>
</div>
<br>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<script type="text/javascript" src="js/empTerms.js"></script>
</body>
</html>