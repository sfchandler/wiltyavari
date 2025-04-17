<?php
function generateInterviewEmail($mysqli,$candidateId,$messageid,$firstName,$lastName,$candidateEmail,$intvwTime,$consultantId){
	require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
	$mail = new PHPMailer();
	$mail->CharSet =  "utf-8";
	$mail->IsSMTP();
	$mail->SMTPAuth = true;
	$mail->Username = DEFAULT_EMAIL;
	$mail->Password = DEFAULT_EMAIL_PASSWORD;
	$mail->SMTPSecure = "tls";
	$mail->SMTPOptions = array(
		'ssl' => array(
			'verify_peer' => false,
			'verify_peer_name' => false,
			'allow_self_signed' => true
		)
	);                           // Enable TLS encryption, `ssl` also accepted
	$mail->Host = "outlook.office365.com";
	$mail->LE = "\r\n";
	$mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME);
	$subject = 'Interview Information';
	$mail->AddAddress($candidateEmail);
	$mail->AddCC(getConsultantEmail($mysqli,$consultantId));
	$mail->Subject = $subject;
	$mail->IsHTML(true);
	$body = '<br/>Dear '.$firstName.' '.$lastName.',<br/>
			  <p>It was great to talk to you. I look forward to meeting you on '.$intvwTime.'. Please come to <b>Level 9, 10 Queen Street</b>, Melbourne 3000.</p><br/>
			  <p>If you are catching public transport, please get off at Flinders St Station, we are a few blocks from there. There is also street parking available but may be limited.</p><br/>
			  <b>Please bring the following with you:</b>
			  <ul>
			  	<li>Passport, Australian Birth Certificate, or Australian Citizenship Certificate</li>
				<li>Any licenses/certificates you may have (ie. drivers, forklift, truck, white/red cards, first aid)</li>
				<li>Bank details</li>
				<li>Tax file number</li>
				<li>Super Fund details - <b>must include</b> Fund Name, Membership No., Fund ABN, and Superannuation Product Identification Number (SPIN) *<b>you will be able to call your Fund for this information</b>* - <span style="color:#0070c0">if you wish to use our company Super, we will supply details upon interview</span></li>
				<li>At least 1 Australian work reference (personal references will not be considered) – <span style="color:#0070c0">You do not need to do this if you have already supplied this reference on your resume.</span></li>
				<li><span style="color:#0070c0"><b>If you have a current police check, please bring this with you</b></span></li>
			  </ul>
			  <br/>
			  <p>Please Register with '.DOMAIN_NAME.' Recruitment Services via below link Before Coming for the interview</p>
			  <a href="'.DOMAIN_URL.'/registerCandidate.php?candidateId='.base64_encode($candidateId).'&consultantId='.base64_encode($consultantId).'&messageId='.base64_encode($messageid).'">Click here to register</a>
			  <br/>
			  <p>Please find below the instructions for doing your WorkPro OH&S test. <b>PLEASE COMPLETE THIS TEST BEFORE YOUR INTERVIEW.</b></p>
			  <br/>
			  <p align="center">
			  <b>WORKPRO INSTRUCTIONS</b>
			  <br/>
			  <a href="http://www.workpro.com.au/">www.workpro.com.au</a>
			  <br/>
			  Click "Register" 
			  <br/>	
Please fill out all required information and answer the RESIDENCY CHECK section correctly. Click Submit and follow the next instructions.
<br/>
Your User Name will then be created. Please use this User Name and the password you have just created to log on to WorkPro
<br/>
Click Next
<br/>
Click Agree
<br/>
Please enter the MAC Code: 12304216391
<br/>
Click Next
<br/>
Click Agree
<br/>
Click Induction/Training Modules tab at top left
<br/>
Select Induction Module 11- WHS & EEO (Combined Modules 01&10) enter the MAC Code 12304216391 again and click Launch
<br/>
Question 1: Please tick “Recruiters/Employers Office”
<br/>
You will then need to listen and go through each section by clicking on the items down the left hand column. Make sure you wait for each module to highlight blue before clicking on it, otherwise the test will restart.
</p>
<br/>
<p align="justify">
On completion, you will need to sit the multiple choice and true/false testing. The successful pass mark for this test is 80%. If you do not achieve this result first time around do not stress, you can go back and correct your answers until you do achieve this result. Once completed, WorkPro will let you know that you have completed the test successful
</p>';
	$mail->Body = $body;
	$mail->send();
	/*** send email end ***/
	if($mail){
		return "SUCCESS";
	}else{
		return "FAILURE";	
	}
}
?>