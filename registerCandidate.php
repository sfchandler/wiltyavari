<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$shiftTypes = getShiftAvailable($mysqli);
$consultantId = base64_decode($_REQUEST['consultantId']);
$candidateId = $_REQUEST['candidateId'];
$messageId = base64_decode($_REQUEST['messageId']);

?>
<!DOCTYPE html>
<html lang="en-us" id="extr-page">
	<head>
		<meta charset="utf-8">
		<title> <?php echo DOMAIN_NAME; ?></title>
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
		<!-- #CSS Links -->
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.min.css">

		<link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css">


		<link rel="stylesheet" type="text/css" media="screen" href="css/chandlerStyle.css">
		<!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
		<link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">

		<!-- #FAVICONS -->
		<link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
		<link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

		<!-- #GOOGLE FONT -->
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">

		<!-- #APP SCREEN / ICONS -->
		<!-- Specifying a Webpage Icon for Web Clip 
			 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
		<link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
		<link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
		<link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
		<link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">
		
		<!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		
		<!-- Startup image for web apps -->
		<link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
		<link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
		<link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">

	</head>
	
	<body id="login">
	
		<header id="header">
			<!--<span id="logo"></span>-->

			<div id="logo-group">
				<span id="logo"> <img src="img/logo.png" alt=" <?php echo DOMAIN_NAME; ?> Admin"> </span>

				<!-- END AJAX-DROPDOWN -->
			</div>

			<!--<span id="extr-page-header-space"> <span class="hidden-mobile hiddex-xs">Already registered?</span> <a href="login.php" class="btn btn-danger">Sign In</a> </span>-->

		</header>

		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container">

				<div class="row">
				  <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  	  <span class="msg"></span>	
					  <div id="regForm" class="well no-padding">
							<form action="" id="frmRegisterCandidate" class="smart-form">
								<header>
									Registration Form and Casual Employee Contract*
								</header>
								<fieldset>
                                	<legend>Personal Details</legend>
                                	<section class="col col-4">
										<label class="select"> <i class="icon-append fa fa-user"></i>
											<select id="title" name="title">
                                            	<option value="Mr.">Mr.</option>
                                                <option value="Mrs.">Mrs.</option>
                                                <option value="Miss">Miss</option>
                                                <option value="Ms">Ms</option>
                                            </select>
											<b class="tooltip tooltip-bottom-right">Please select Your title</b> </label>
									</section>
									<section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="firstName" placeholder="First Name">
											<b class="tooltip tooltip-bottom-right">Please enter Your First Name</b> </label>
									</section>
                                    <section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="lastName" placeholder="Last Name">
											<b class="tooltip tooltip-bottom-right">Please enter Your Last Name</b> </label>
									</section>
                                    <section class="col col-8">
										<label class="input"> <i class="icon-append fa fa-home"></i>
											<input type="text" name="address" placeholder="Address">
											<b class="tooltip tooltip-bottom-right">Please enter your address</b> </label>
									</section>
									<section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-home"></i>
											<input type="text" name="postcode" placeholder="Post Code">
											<b class="tooltip tooltip-bottom-right">Please enter your Post Code</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-phone"></i>
											<input type="text" name="homePhone" data-mask="9999999999" data-mask-placeholder="X" placeholder="Home Phone">
											<b class="tooltip tooltip-bottom-right">Please enter your Home Phone Number</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-phone"></i>
											<input type="text" name="mobile" data-mask="9999999999" data-mask-placeholder="X" placeholder="Mobile">
											<b class="tooltip tooltip-bottom-right">Please enter your Mobile Phone Number</b> </label>
									</section>
                                    <section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-calendar"></i>
												<input type="text" name="dob" id="dob" data-mask-placeholder="-" placeholder="Date of Birth" class="form-control" data-mask="99/99/9999">
										</label>
									</section>
                                    <section class="col col-4">
											<label class="select">
												<select id="gender" name="gender">
													<option value="None" selected="" disabled="">Gender</option>
													<option value="Male">Male</option>
													<option value="Female">Female</option>
													<option value="Noanswer">Prefer not to answer</option>
												</select> <i></i> </label>
									</section>
                                    <section class="col col-4">
										<label class="input"> <i class="icon-append fa fa-flag"></i>
											<input type="text" name="nationality" placeholder="Nationality">
											<b class="tooltip tooltip-bottom-right">Please enter your Nationality</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="email" name="email" id="email" placeholder="Email address">
											<b class="tooltip tooltip-bottom-right">Needed to verify your account</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-envelope"></i>
											<input type="email" name="confemail" id="confemail" placeholder="Confirm Email address">
											<b class="tooltip tooltip-bottom-right">Needed to verify your account</b> </label>
									</section>
								</fieldset>
                                <br/>
								<fieldset>
                                	<legend>Emergency Contact Information / Authority to Act</legend>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="emgFullName" placeholder="Full Name of Emergency Contact">
											<b class="tooltip tooltip-bottom-right">Enter your Emergency Contact Full Name</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-user"></i>
											<input type="text" name="relationship" placeholder="Relationship">
											<b class="tooltip tooltip-bottom-right">Enter your Emergency Contact Relationship</b> </label>
									</section>
                                    <section class="col col-lg-12">
										<label class="input"> <i class="icon-append fa fa-home"></i>
											<input type="text" name="emgAddress" placeholder="Address">
											<b class="tooltip tooltip-bottom-right">Enter your Emergency Contact Address</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-phone"></i>
											<input type="text" name="emghomePhone" data-mask="9999999999" data-mask-placeholder="X" placeholder="Home Phone">
											<b class="tooltip tooltip-bottom-right">Please enter your Emergency Contact Home Phone Number</b> </label>
									</section>
                                    <section class="col col-6">
										<label class="input"> <i class="icon-append fa fa-phone"></i>
											<input type="text" name="emgMobile" data-mask="9999999999" data-mask-placeholder="X" placeholder="Mobile">
											<b class="tooltip tooltip-bottom-right">Please enter your Emergency Contact Mobile Phone Number</b> </label>
									</section>
                                    <section class="col col-lg-12">I give this person permission to act on my behalf (speak about me to my consultant, change bank details etc.): 
                                        <div class="inline-group">
                                            <label class="radio">
                                            <input name="behalf" type="radio" value="Yes">
                                            <i></i>
                                            Yes
                                            </label>
                                            <label class="radio">
                                            <input name="behalf" type="radio" value="No">
                                            <i></i>
                                            No
                                            </label>
                                        </div>
                                    </section>
                                </fieldset>
                                <br/>
								<fieldset>
                                	<legend>Transport Details</legend>
                                    <section class="col col-6">
                                    	<label>What is your main method of transport? </label>
										<label class="input"> <i class="icon-append fa fa-train"></i>
											<input type="text" name="transportMethod" placeholder="What is your transport method">
											<b class="tooltip tooltip-bottom-right">Enter your main method of transport</b> </label>
									</section>
                                    <section class="col col-6">
                                    	<label>How far are you prepared to travel for work?</label>
                                        <label class="input"><i class="icon-append fa fa-binoculars"></i>
											<input type="text" name="howFar" placeholder="How far you travel for work">
											<b class="tooltip tooltip-bottom-right">Enter How far you travel for work</b> </label>
                                    </section>
                                    <section class="col col-12">
                                    	<label>Are you happy to take early morning calls (usually from 5.30am onwards)?</label>
                                        <label class="input"><i class="icon-append fa fa-question-circle"></i>
											<input type="text" name="earlyCalls" placeholder="Answering early morning calls">
											<b class="tooltip tooltip-bottom-right">Are you happy to take early morning calls</b> </label>
                                    </section>
                                </fieldset>
                                <br/>
								<fieldset>
                                	<legend>Additional Information</legend>
                                    <section class="col col-lg-12">
                                    	<label>Please select the following that best represents your visa status: </label>
										<div class="inline-group">
                                            <label class="radio">
                                            <input class="visa" name="visa" type="radio" value="Australian/New Zealand Citizen">
                                            <i></i>
                                            Australian/New Zealand Citizen
                                            </label>
                                            <label class="radio">
                                            <input class="visa" name="visa" type="radio" value="Permanent Resident">
                                            <i></i>
                                            Permanent Resident
                                            </label>
                                            <label class="radio">
                                            <input class="visa" name="visa" type="radio" value="International Student">
                                            <i></i>
                                            International Student
                                            </label>
                                            <label class="radio">
                                            <input class="visa" name="visa" type="radio" value="Working Visa">
                                            <i></i>
                                            Working Visa
                                            </label>
                                            <label class="radio">
                                            <input class="visa" name="visa" type="radio" value="Bridging Visa">
                                            <i></i>
                                            Bridging Visa
                                            </label>
                                        </div>
									</section>
                                    <section class="col col-lg-12">
                                    	<label id="visaLimitationLabel" for="visaLimitation">Please specify any working limitations that you have on your visa, if any: </label>
                                        <label class="input"><i class="icon-append fa fa-info"></i>
                                        <input type="text" name="visaLimitation" id="visaLimitation" placeholder="Specify Work Limitation on your visa if any">
                                        <b class="tooltip tooltip-bottom-right">Specify Work Limitation on your visa if any</b> </label>
                                    </section>
                                    <section class="col col-lg-12">
                                    	<label>Please detail any qualifications and licences that you hold: </label>
                                        <label class="textarea"><i class="icon-append fa fa-info"></i>
                                        <textarea rows="5" class="textarea" id="qualification" name="qualification" placeholder="Specify any qualifications or licences"></textarea></label>
                                    </section>
                                    <section class="col col-lg-12">
                                    	<label>What is your shift preference/availability:</label>
                                        <div class="inline-group">
                                        <?php 
										foreach($shiftTypes as $sT){						
										?>
											<label class="checkbox" >
											<input type="checkbox" name="shiftAvailable" value="<?php echo $sT['shiftAvailableId'];?>">
											<i></i><?php echo $sT['shift']; ?></label>
										<?php
										}
										?>							
                                        </div>				
                                        <label class="textarea"><i class="icon-append fa fa-info"></i>
                                        <textarea rows="5" id="notes" name="notes" placeholder="Notes"></textarea></label>
                                     </section>
                                    <section class="col col-lg-12">
                                        <label>Are you part of any Job Active Services/Wage Subsidy Agreement? Please name the company and who your consultant:  </label>
                                        <label class="textarea"><i class="icon-append fa fa-info"></i>
                                            <textarea rows="5" id="jobactive" name="jobactive"></textarea></label>
                                    </section>
                                    <section class="col col-lg-12">
                                     <label class="checkbox" >
											<input type="checkbox" name="newsletter" value="1"><i></i>Please tick this box if you DO NOT wish to receive our Chandler Newsletter containing company updates, job opportunities and industry news.</label>
                                    </section>
                                    </fieldset>
                                    <br/>
									<fieldset>
                                	<legend>Medical History</legend>
                                    <section class="col col-lg-12">
                                    <label>Do you have any physical or mental disabilities, medical conditions or previous injury which may affect your ability to perform any job? </label>
                                    	<div class="inline-group">
                                            <label class="radio">
                                            <input name="disabilities" id="disabilities" class="disabilities" type="radio" value="Yes">
                                            <i></i>
                                            Yes
                                            </label>
                                            <label class="radio">
                                            <input name="disabilities" id="disabilities" class="disabilities" type="radio" value="No">
                                            <i></i>
                                            No
                                            </label>
                                        </div><label class="textarea"><i class="icon-append fa fa-info"></i>
                                    <textarea name="disabilityDesc" id="disabilityDesc" rows="5" id="disabilityDesc" class="textarea" placeholder="If so, give details: "></textarea>  </label>
                                    </section>
                                    <section class="col col-lg-12">
                                    	<label>Have you ever claimed Workers Compensations? </label>
                                    	<div class="inline-group">
                                            <label class="radio">
                                            <input name="compensation" id="compensation" class="compensation" type="radio" value="Yes">
                                            <i></i>
                                            Yes
                                            </label>
                                            <label class="radio">
                                            <input name="compensation" id="compensation" class="compensation" type="radio" value="No">
                                            <i></i>
                                            No
                                            </label>
                                        </div><label class="textarea"><i class="icon-append fa fa-info"></i>
                                    <textarea name="compensationDesc" rows="5" id="compensationDesc" class="textarea" placeholder="If so, give details: "></textarea></label>
                                    <table id="compTbl" width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tbody>
                                        <tr>
                                          <td>EMPLOYERS NAME</td>
                                          <td>DATE OF INJURY</td>
                                          <td>NATURE OF INJURY</td>
                                          <td>DURATION OF ABSENCE</td>
                                        </tr>
                                        <tr>
                                          <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="empName1" placeholder="Enter Your Employers Name"></label></td>
                                          <td><label class="input"> <i class="icon-append fa fa-calendar"></i>
												<input type="text" name="doi1" id="doi1" data-mask-placeholder="-" placeholder="Date of Injury" class="form-control" data-mask="99/99/9999">
										</label></td>
                                          <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="natureInjury1" placeholder="Enter Nature of Injury"></label></td>
                                          <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="durAbsense1" placeholder="Enter Duration of Absense"></label></td>
                                        </tr>
                                        <tr>
                                          <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="empName2" placeholder="Enter Your Employers Name"></label></td>
                                          <td><label class="input"> <i class="icon-append fa fa-calendar"></i>
												<input type="text" name="doi2" id="doi2" data-mask-placeholder="-" placeholder="Date of Injury" class="form-control" data-mask="99/99/9999">
										</label></td>
                                          <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="natureInjury2" placeholder="Enter Nature of Injury"></label></td>
                                          <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="durAbsense2" placeholder="Enter Duration of Absense"></label></td>
                                        </tr>
                                      </tbody>
                                    </table>

                                    </section>
                                    <section class="col col-lg-12">
                                    	<label>Are you currently in receipt, or have you ever received a pension? </label>
                                    	<div class="inline-group">
                                            <label class="radio">
                                            <input name="pension" id="pension" class="pension" type="radio" value="Yes">
                                            <i></i>
                                            Yes
                                            </label>
                                            <label class="radio">
                                            <input name="pension" id="pension" class="pension" type="radio" value="No">
                                            <i></i>
                                            No
                                            </label>
                                        </div>
                                        <label class="textarea">
                                        	<i class="icon-append fa fa-info"></i>
                                            <textarea name="pensionDesc" rows="5" id="pensionDesc" class="textarea" placeholder="If so, give details: "></textarea></label></section>
                                        <section class="col col-lg-12">
                                    	<label>Have you ever had your hearing tested by a qualified doctor? </label>
                                    	<div class="inline-group">
                                            <label class="radio">
                                            <input name="hearing" id="hearing" class="hearing" type="radio" value="Yes">
                                            <i></i>
                                            Yes
                                            </label>
                                            <label class="radio">
                                            <input name="hearing" id="hearing" class="hearing" type="radio" value="No">
                                            <i></i>
                                            No
                                            </label>
                                        </div>
                                        <label class="textarea">
                                        <i class="icon-append fa fa-info"></i>
                                        <textarea name="hearingDesc" rows="5" id="hearingDesc" class="textarea" placeholder="If so, give details: "></textarea></label></section>				
                                        <section class="col col-lg-12">
                                    	<label>Are you a smoker? </label>
                                    	<div class="inline-group">
                                            <label class="radio">
                                            <input name="smoker" id="smoker" class="smoker" type="radio" value="Yes">
                                            <i></i>
                                            Yes
                                            </label>
                                            <label class="radio">
                                            <input name="smoker" id="smoker" class="smoker" type="radio" value="No">
                                            <i></i>
                                            No
                                            </label>
                                        </div>
                                        </section>
                                      <section class="col col-lg-12">
                                    	<label>Have you ever experienced or been treated for any of the following conditions? </label>
                                    	<table width="100%" style="border:1px dashed">
                                          <tbody>
                                            <tr>
                                              <td>Eye Trouble
                                              </td>
                                              <td><div class="inline-group">
                                                        <label class="radio">
                                                        <input name="eyetrouble" id="eyetrouble" class="eyetrouble" type="radio" value="Yes">
                                                        <i></i>
                                                        Yes
                                                        </label>
                                                        <label class="radio">
                                                        <input name="eyetrouble" id="eyetrouble" class="eyetrouble" type="radio" value="No">
                                                        <i></i>
                                                        No
                                                        </label>
                                                    </div></td>
                                              <td>Hearing Impairment</td>
                                              <td><div class="inline-group">
                                                        <label class="radio">
                                                        <input name="hearingImp" id="hearingImp" class="hearingImp" type="radio" value="Yes">
                                                        <i></i>
                                                        Yes
                                                        </label>
                                                        <label class="radio">
                                                        <input name="hearingImp" id="hearingImp" class="hearingImp" type="radio" value="No">
                                                        <i></i>
                                                        No
                                                        </label>
                                                    </div></td>
                                              <td>Surgical Procedures</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="surgicalPro" id="surgicalPro" class="surgicalPro" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="surgicalPro" id="surgicalPro" class="surgicalPro" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td>Asthma</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="asthma" id="asthma" class="asthma" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="asthma" id="asthma" class="asthma" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Hernia</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="hernia" id="hernia" class="hernia" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="hernia" id="hernia" class="hernia" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Duodenal Ulcer</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="duodenal" id="duodenal" class="duodenal" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="duodenal" id="duodenal" class="duodenal" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                            </tr>
                                            <tr>
                                              <td>Deafness</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="deafness" id="deafness" class="deafness" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="deafness" id="deafness" class="deafness" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Epilepsy</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="epilepsy" id="epilepsy" class="epilepsy" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="epilepsy" id="epilepsy" class="epilepsy" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div>
                                              </td>
                                              <td>Lung Disorder</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="lungDisorder" id="lungDisorder" class="lungDisorder" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="lungDisorder" id="lungDisorder" class="lungDisorder" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div>
                                               </td>
                                            </tr>
                                            <tr>
                                              <td>Fainting/Blackout</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="fainting" id="fainting" class="fainting" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="fainting" id="fainting" class="fainting" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Back trouble/disorder</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="backdisorder" id="backdisorder" class="backdisorder" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="backdisorder" id="backdisorder" class="backdisorder" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Nerve Disorder</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="nervedisorder" id="nervedisorder" class="nervedisorder" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="nervedisorder" id="nervedisorder" class="nervedisorder" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                            </tr>
                                            <tr>
                                              <td>Dizziness</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="dizziness" id="dizziness" class="dizziness" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="dizziness" id="dizziness" class="dizziness" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Arthritis</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="arthritis" id="arthritis" class="arthritis" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="arthritis" id="arthritis" class="arthritis" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td rowspan="3" align="left" valign="top">Injury - any body part</td>
                                              <td rowspan="3" align="left" valign="top"><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="injury" id="injury" class="injury" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="injury" id="injury" class="injury" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div>
                                                  <label class="textarea">
                                        <i class="icon-append fa fa-info"></i>
                                        <textarea name="injuryDesc" id="injuryDesc" rows="5" id="injuryDesc" class="textarea" placeholder="If so, please describe "></textarea></label></td>
                                            </tr>
                                            <tr>
                                              <td>Swollen Joints</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="swollen" id="swollen" class="swollen" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="swollen" id="swollen" class="swollen" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Chronic Illnesses</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="chronic" id="chronic" class="chronic" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="chronic" id="chronic" class="chronic" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                            </tr>
                                            <tr>
                                              <td>Skin Trouble</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="skin" id="skin" class="skin" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="skin" id="skin" class="skin" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div></td>
                                              <td>Diabetes</td>
                                              <td><div class="inline-group">
                                                      <label class="radio">
                                                      <input name="diabetes" id="diabetes" class="diabetes" type="radio" value="Yes">
                                                      <i></i>
                                                      Yes
                                                      </label>
                                                      <label class="radio">
                                                      <input name="diabetes" id="diabetes" class="diabetes" type="radio" value="No">
                                                      <i></i>
                                                      No
                                                      </label>
                                                  </div>
                                              </td>
                                            </tr>
                                          </tbody>
                                        </table>
                                        </section>
                                        <section class="col col-lg-12">
                                        <label>Are you currently receiving any medical attention from a doctor or anyone else?</label>
                                        	<div class="inline-group">
                                                <label class="radio">
                                                <input name="medicalAttention" id="medicalAttention" class="medicalAttention" type="radio" value="Yes">
                                                <i></i>
                                                Yes
                                                </label>
                                                <label class="radio">
                                                <input name="medicalAttention" id="medicalAttention" class="medicalAttention" type="radio" value="No">
                                                <i></i>
                                                No
                                                </label>
                                             </div>
                                             <label class="textarea">
                                        <i class="icon-append fa fa-info"></i>
                                        <textarea name="medicalAttentionDesc" rows="5" id="medicalAttentionDesc" class="textarea" placeholder="If so, give details: "></textarea></label>
                                        </section>
                                        <section class="col col-lg-12">
                                        <p>Chandler Personnel aims to maintain high standards of professional conduct and ensure all employees are suitable for their role. To assist us to comply with our obligation to ensure a safe workplace, all persons wishing to be employed by Chandler Personnel on a casual or permanent basis are required to undertake a police check prior to commencement. Chandler Personnel will accept previously completed Police Checks, so long as they are within 3 years of the commencement date.</p>	
                                        </section>
                                        <section class="col col-lg-12">
                                        <div>
                                        Do you have any prior or pending criminal history?
                                        </div>
                                        <div class="inline-group">
                                                <label class="radio">
                                                <input name="criminalHistory" id="criminalHistory" class="criminalHistory" type="radio" value="Yes">
                                                <i></i>
                                                Yes
                                                </label>
                                                <label class="radio">
                                                <input name="criminalHistory" id="criminalHistory" class="criminalHistory" type="radio" value="No">
                                                <i></i>
                                                No
                                                </label>
                                             </div>
                                        <label class="textarea">
                                        <i class="icon-append fa fa-info"></i>
                                        <textarea name="criminalHistoryDesc" rows="5" id="criminalHistoryDesc" class="textarea" placeholder="If so, give details: "></textarea>
                                        </label>
                                        </section>
                                        <section class="col col-lg-12">
                                        	<table id="compTbl" width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tbody>
                                                <tr>
                                                  <td>DATE</td>
                                                  <td>NATURE OF OFFENCE</td>
                                                </tr>
                                                <tr>
                                                  <td><label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                        <input type="text" name="doo1" id="doo1" data-mask-placeholder="-" placeholder="Date of Offence" class="form-control" data-mask="99/99/9999">
                                                </label></td>
                                                  <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="natureOffence1" placeholder="Enter Nature of Offence"></label></td>
                                                </tr>
                                                <tr>
                                                  <td><label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                        <input type="text" name="doo2" id="doo2" data-mask-placeholder="-" placeholder="Date of Offence" class="form-control" data-mask="99/99/9999">
                                                </label></td>
                                                  <td><label class="input"><i class="icon-append fa fa-info"></i><input type="text" name="natureOffence2" placeholder="Enter Nature of Offence"></label></td>
                                                </tr>
                                              </tbody>
                                            </table>
                                        </section>
                                        <section class="col col-lg-12">
                                        <label>All persons who do not have a previously completed Police Check have the following options below. Please tick the most suitable box, and provide signatures where required.
</label>
											<div class="inline-group">
                                                <label class="radio">
                                                <input name="pcheck" id="pcheck" class="pcheck" type="radio" value="1">
                                                <i></i>
                                                Option 1: I hold a previously completed National Police Check (within 3 years) 
                                                </label>
                                                <label class="radio">
                                                <input name="pcheck" id="pcheck" class="pcheck" type="radio" value="2">
                                                <i></i>
                                                Option 2: I have completed the enclosed Application Form and provided sufficient ID so that Chandler Personnel can conduct a National Police Check on my behalf *(sign below)
                                                </label>
                                             </div>
                                        </section>
                                    </fieldset>
								<footer>
									<input type="hidden" name="candidateId" value="<?php echo $candidateId;?>"/><input type="hidden" name="consultantId" value="<?php echo $consultantId;?>"/><input type="hidden" name="messageId" value="<?php echo $messageId;?>"/><input type="hidden" name="consultantId" value="<?php echo $consultantId;?>"/><button name="regBtn" id="regBtn" type="submit" class="btn btn-primary">
										Register
									</button>
								</footer>
								
							</form>
						</div>
                        <div class="message">
									<i class="fa fa-check"></i>
									<p>
										Thank you for your registering!
									</p>
								</div>
						<p class="note text-center">**</p>
						<h5 class="text-center">- Chandler Candidate Registration -</h5>
						<!--<ul class="list-inline text-center">
							<li>
								<a href="javascript:void(0);" class="btn btn-primary btn-circle"><i class="fa fa-facebook"></i></a>
							</li>
							<li>
								<a href="javascript:void(0);" class="btn btn-info btn-circle"><i class="fa fa-twitter"></i></a>
							</li>
							<li>
								<a href="javascript:void(0);" class="btn btn-warning btn-circle"><i class="fa fa-linkedin"></i></a>
							</li>
						</ul>-->
					</div>
				</div>
			</div>

		</div>

		<!--================================================== -->	

		<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
		<script src="js/plugin/pace/pace.min.js"></script>

	    <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
	    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
		<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');} </script>

	    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>

		<!-- IMPORTANT: APP CONFIG -->
		<script src="js/app.config.js"></script>

		<!-- JS TOUCH : include this plugin for mobile drag / drop touch events 		
		<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

		<!-- BOOTSTRAP JS -->		
		<script src="js/bootstrap/bootstrap.min.js"></script>

		<!-- JQUERY VALIDATE -->
		<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
		
		<!-- JQUERY MASKED INPUT -->
		<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
		
		<!--[if IE 8]>
			
			<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
			
		<![endif]-->

		<!-- MAIN APP JS FILE -->
		<script src="js/app.js"></script>

		<script type="text/javascript">
			runAllForms();
			
			// Model i agree button
			$("#i-agree").click(function(){
				$this=$("#terms");
				if($this.checked) {
					$('#myModal').modal('toggle');
				} else {
					$this.prop('checked', true);
					$('#myModal').modal('toggle');
				}
			});
			
			// Validation
			$(function() {
				/* AJAX loading animation */
				$body = $("body");

				$(document).on({
					ajaxStart: function() { $body.addClass("loading");    },
					 ajaxStop: function() { $body.removeClass("loading"); }    
				});
				/* -  end  -*/
				/* get url var */
				$.extend({
				  getUrlVars: function(){
					var vars = [], hash;
					var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
					for(var i = 0; i < hashes.length; i++)
					{
					  hash = hashes[i].split('=');
					  vars.push(hash[0]);
					  vars[hash[0]] = hash[1];
					}
					return vars;
				  },
				  getUrlVar: function(name){
					return $.getUrlVars()[name];
				  }
				});
				/**/
				$('.message').hide();
				$('#disabilityDesc').hide();
				$('.disabilities').on('click', function(){
					var disStatus = $('input[name=disabilities]:checked', '#frmRegisterCandidate').val();
					if(disStatus == 'Yes'){
						$('#disabilityDesc').show();
					}else{
						$('#disabilityDesc').hide();
					}
				});
				$('#compensationDesc').hide();
				$('#compTbl').hide();
				$('.compensation').on('click', function(){
					var compStatus = $('input[name=compensation]:checked', '#frmRegisterCandidate').val();
					if(compStatus == 'Yes'){
						$('#compensationDesc').show();
						$('#compTbl').show();
					}else{
						$('#compensationDesc').hide();
						$('#compTbl').hide();
					}
				});
				$('#pensionDesc').hide();
				$('.pension').on('click', function(){
					var penStatus = $('input[name=pension]:checked', '#frmRegisterCandidate').val();
					if(penStatus == 'Yes'){
						$('#pensionDesc').show();
					}else{
						$('#pensionDesc').hide();
					}
				});
				$('#hearingDesc').hide();
				$('.hearing').on('click', function(){
					var hearingStatus = $('input[name=hearing]:checked', '#frmRegisterCandidate').val();
					if(hearingStatus == 'Yes'){
						$('#hearingDesc').show();
					}else{
						$('#hearingDesc').hide();
					}
				});
				$('#injuryDesc').hide();
				$('.injury').on('click', function(){
					var injuryStatus = $('input[name=injury]:checked', '#frmRegisterCandidate').val();
					if(injuryStatus == 'Yes'){
						$('#injuryDesc').show();
					}else{
						$('#injuryDesc').hide();
					}
				});
				
				$('#medicalAttentionDesc').hide();
				$('.medicalAttention').on('click', function(){
					var injuryStatus = $('input[name=medicalAttention]:checked', '#frmRegisterCandidate').val();
					if(injuryStatus == 'Yes'){
						$('#medicalAttentionDesc').show();
					}else{
						$('#medicalAttentionDesc').hide();
					}
				});
				
				$('#criminalHistoryDesc').hide();
				$('.criminalHistory').on('click', function(){
					var offenceStatus = $('input[name=criminalHistory]:checked', '#frmRegisterCandidate').val();
					if(offenceStatus == 'Yes'){
						$('#criminalHistoryDesc').show();
					}else{
						$('#criminalHistoryDesc').hide();
					}
				});
				$('#visaLimitation').hide();
				$('#visaLimitationLabel').hide();
				$('.visa').on('click', function(){
					var visa = $('input[name=visa]:checked', '#frmRegisterCandidate').val();
					if(visa == 'International Student'){
						$('#visaLimitation').show();
						$('#visaLimitationLabel').show();
					}else if(visa == 'Working Visa'){
						$('#visaLimitation').show();
						$('#visaLimitationLabel').show();	
					}else if(visa == 'Bridging Visa'){
						$('#visaLimitation').show();
						$('#visaLimitationLabel').show();
					}else{
						$('#visaLimitation').hide();
						$('#visaLimitationLabel').hide();
					}
				});
				$('#doi1').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+0"
				});
				$('#doi2').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+0"
				});
				$('#doo1').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+0"
				});
				$('#doo2').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+0"
				});
				$('#dob').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+0"
				});
			
			$(document).on('click', '#regBtn', function(evt) {
				/*if ($.getUrlVar("candidateId") != null) {
					evt.preventDefault();
					alert("Original url modified. please click your email URL and retry");
				}
				if($.getUrlVar("consultantId") != null){
					evt.preventDefault();
					alert("Original url modified. please click your email URL and retry");
				}
				if($.getUrlVar("messageId") != null){
					evt.preventDefault();
					alert("Original url modified. please click your email URL and retry");
				}*/
				$("#frmRegisterCandidate").validate({
					rules : {
						firstName : {
							required : true
						},
						lastName : {
							required : true
						},
						address : {
							required : true
						},
						mobile : {
							required : true
						},
						postcode : {
							required : true
						},
						email : {
							required : true,
							email : true
						},
						confemail : {
							required : true,
							email : true,
							equalTo : '#email'
						},
						dob : {
							required : true,
						},
						gender : {
							required : true
						},
						nationality : {
							required : true
						},
						emgFullName : {
							required : true
						},
						relationship : {
							required : true
						},
						emgAddress : {
							required : true
						},
						emgMobile : {
							required : true
						},
						behalf : {
							required: function() {
								return $('[name="behalf"]:checked').length === 0; 
							}
						},
						transportMethod : {
							required : true
						},
						howFar : {
							required : true
						},
						earlyCalls : {
							required : true
						},
						visa : {
							required : function() {
								return $('[name="visa"]:checked').length === 0; 
							}
						},
						qualification : {
							required : true
						},						
						disabilities : {
							required : function() {
								return $('[name="disabilities"]:checked').length === 0; 
							}
						},
						disabilityDesc : {
							required : true
						},
						compensation : {
							required : function() {
								return $('[name="compensation"]:checked').length === 0; 
							}
						},
						compensationDesc : {
							required : true
						},
						pension : {
							required : function() {
								return $('[name="pension"]:checked').length === 0; 
							}
						},
						pensionDesc : {
							required : true
						},
						hearing : {
							required : function(){
								return $('[name="hearing"]:checked').length === 0;
							}
						},
						hearingDesc : {
							required : true
						},
						smoker : {
							required : function(){
								return $('[name="smoker"]:checked').length === 0;
							}
						},
						eyetrouble : {
							required : function(){
								return $('[name="eyetrouble"]:checked').length === 0;
							}
						},
						hearingImp : {
							required : function(){
								return $('[name="hearingImp"]:checked').length === 0;
							}
						},
						surgicalPro :{
							required : function(){
								return $('[name="surgicalPro"]:checked').length === 0;
							}
						},
						asthma : {
							required : function(){
								return $('[name="asthma"]:checked').length === 0;
							}
						},
						hernia : {
							required : function(){
								return $('[name="hernia"]:checked').length === 0;
							}
						},
						duodenal : {
							required : function(){
								return $('[name="duodena1"]:checked').length === 0;
							}
						},
						deafness : {
							required : function(){
								return $('[name="deafness"]:checked').length === 0;
							}
						},
						epilepsy : {
							required : function(){
								return $('[name="epilepsy"]:checked').length === 0;
							}
						},
						lungDisorder : {
							required : function(){
								return $('[name="lungDisorder"]:checked').length === 0;
							}
						},
						fainting : {
							required : function(){
								return $('[name="fainting"]:checked').length === 0;
							}
						},
						backdisorder : {
							required : function(){
								return $('[name="backdisorder"]:checked').length === 0;
							}
						},
						nervedisorder : {
							required : function(){
								return $('[name="nervedisorder"]:checked').length === 0;
							}
						},
						dizziness : {
							required : function(){
								return $('[name="dizziness"]:checked').length === 0;
							}
						},
						arthritis : {
							required : function(){
								return $('[name="arthritis"]:checked').length === 0;
							}
						},
						injury : {
							required : function() {
								return $('[name="injury"]:checked').length === 0; 
							}
						},
						injuryDesc : {
							required : true
						},
						swollen : {
							required : function(){
								return $('[name="swollen"]:checked').length === 0;
							}
						},
						chronic : {
							required : function(){
								return $('[name="chronic"]:checked').length === 0;
							}
						},
						skin : {
							required : function(){
								return $('[name="skin"]:checked').length === 0;
							}
						},
						diabetes : {
							required : function(){
								return $('[name="diabetes"]:checked').length === 0;
							}
						},
						medicalAttention : {
							required : function(){
								return $('[name="medicalAttention"]:checked').length === 0;
							}
						},
						medicalAttentionDesc : {
							required : true
						},
						criminalHistory : {
							required : function(){
								return $('[name="criminalHistory"]:checked').length === 0;
							}
						},
						criminalHistoryDesc : {
							required : true
						}
					},
					// Messages for form validation
					messages : {
						firstname : {
							required : 'Please select your first name'
						},
						lastname : {
							required : 'Please select your last name'
						},
						address : {
							required : 'Please enter address'
						},
						mobile : {
							required : 'Please enter your Home Phone No'
						},
						postcode : {
							required : 'Please enter your postcode'
						},
						email : {
							required : 'Please enter your email address',
							email : 'Please enter a VALID email address'
						},
						email : {
							required : 'Please enter your email'
						},
						confemail : {
							required : 'Please enter your email one more time',
							equalTo : 'Please enter the same email as above'
						},
						gender : {
							required : 'Please select your gender'
						},
						behalf : {
							required : 'Please select an option'	
						},
						transportMethod : {
							required : 'Please enter transport method'
						}
					},

					// Ajax form submition
					submitHandler : function(form) {
						// Create variables from the form
						var title = $("#title option:selected").val();
						var firstName = $("input[name=firstName]").val(); 
						var lastName = $("input[name=lastName]").val();
						var address = $("input[name=address]").val();  
						var postcode = $("input[name=postcode]").val();
						var homePhone = $("input[name=homePhone]").val();
						var mobile = $("input[name=mobile]").val();
						var dob = $("input[name=dob]").val();
						var gender = $("#gender option:selected").val();
						var nationality = $("input[name=nationality]").val();
						var email = $("input[name=email]").val();
						var emgFullName = $("input[name=emgFullName]").val();
						var relationship = $("input[name=relationship]").val();
						var emgAddress = $("input[name=emgAddress]").val();
						var emghomePhone = $("input[name=emghomePhone]").val();
						var emgMobile = $("input[name=emgMobile]").val();
						var behalf = $('input[name=behalf]:checked', '#frmRegisterCandidate').val();
						var transportMethod = $("input[name=transportMethod]").val();
						var howFar = $("input[name=howFar]").val();
						var earlyCalls = $("input[name=earlyCalls]").val();
						var visa = $('input[name=visa]:checked', '#frmRegisterCandidate').val();
						var visaLimitation = $("input[name=visaLimitation]").val();
						var qualification = $('textarea#qualification').val();
						var shiftAvailable = [];
						$.each($("input[name='shiftAvailable']:checked"), function(){            
							shiftAvailable.push($(this).val());
						});
						var notes = $('textarea#notes').val();
						var newsletter = $("input[name=newsletter]").val();
						var jobactive = $('textarea#jobactive').val();
						var disabilities = $('input[name=disabilities]:checked', '#frmRegisterCandidate').val();
						var disabilityDesc = $('textarea#disabilityDesc').val();
						var compensation = $('input[name=compensation]:checked', '#frmRegisterCandidate').val();
						var compensationDesc = $('textarea#compensationDesc').val();
						var empName1 = $("input[name=empName1]").val();
						var doi1 = $("input[name=doi1]").val();
						var natureInjury1 = $("input[name=natureInjury1]").val();
						var durAbsense1 = $("input[name=durAbsense1]").val();
						var empName2 = $("input[name=empName2]").val();
						var doi2 = $("input[name=doi2]").val();
						var natureInjury2 = $("input[name=natureInjury2]").val();
						var durAbsense2 = $("input[name=durAbsense2]").val();
						var pension = $('input[name=pension]:checked', '#frmRegisterCandidate').val();
						var pensionDesc = $('textarea#pensionDesc').val();
						var hearing = $('input[name=hearing]:checked', '#frmRegisterCandidate').val();
						var hearingDesc = $('textarea#hearingDesc').val();
						var smoker = $('input[name=smoker]:checked', '#frmRegisterCandidate').val();
						var eyetrouble = $('input[name=eyetrouble]:checked', '#frmRegisterCandidate').val();
						var hearingImp = $('input[name=hearingImp]:checked', '#frmRegisterCandidate').val();
						var surgicalPro = $('input[name=surgicalPro]:checked', '#frmRegisterCandidate').val();
						var asthma = $('input[name=asthma]:checked', '#frmRegisterCandidate').val();
						var hernia = $('input[name=hernia]:checked', '#frmRegisterCandidate').val();
						var duodenal = $('input[name=duodenal]:checked', '#frmRegisterCandidate').val();
						var deafness = $('input[name=deafness]:checked', '#frmRegisterCandidate').val();
						var epilepsy = $('input[name=epilepsy]:checked', '#frmRegisterCandidate').val();
						var lungDisorder = $('input[name=lungDisorder]:checked', '#frmRegisterCandidate').val();
						var fainting = $('input[name=fainting]:checked', '#frmRegisterCandidate').val();
						var backdisorder = $('input[name=backdisorder]:checked', '#frmRegisterCandidate').val();
						var nervedisorder = $('input[name=nervedisorder]:checked', '#frmRegisterCandidate').val();
						var dizziness = $('input[name=dizziness]:checked', '#frmRegisterCandidate').val();
						var arthritis = $('input[name=arthritis]:checked', '#frmRegisterCandidate').val();
						var injury = $('input[name=injury]:checked', '#frmRegisterCandidate').val();
						var injuryDesc = $('textarea#injuryDesc').val();
						var swollen = $('input[name=swollen]:checked', '#frmRegisterCandidate').val();
						var chronic = $('input[name=chronic]:checked', '#frmRegisterCandidate').val();
						var skin = $('input[name=skin]:checked', '#frmRegisterCandidate').val();
						var diabetes = $('input[name=diabetes]:checked', '#frmRegisterCandidate').val();
						var medicalAttention = $('input[name=medicalAttention]:checked', '#frmRegisterCandidate').val();
						var medicalAttentionDesc = $('textarea#medicalAttentionDesc').val();
						var candidateId = $("input[name=candidateId]").val();
						var messageId = $("input[name=messageId]").val();
						var consultantId = $("input[name=consultantId]").val();
						var criminalHistory = $('input[name=criminalHistory]:checked', '#frmRegisterCandidate').val();
						var criminalHistoryDesc = $('textarea#criminalHistoryDesc').val();
						var doo1 = $("input[name=doo1]").val();
						var natureOffence1 = $("input[name=natureOffence1]").val();
						var doo2 = $("input[name=doo2]").val();
						var natureOffence2 = $("input[name=natureOffence2]").val();
						var pcheck = $('input[name=pcheck]:checked', '#frmRegisterCandidate').val();
						$.ajax({
						   type: "POST",
						   url: "./regProcess.php", 
						   data: { title : title, firstName : firstName, lastName : lastName, address : address, postcode : postcode, homePhone : homePhone, mobile : mobile, dob : dob, gender : gender, nationality : nationality, email : email, emgFullName : emgFullName, relationship : relationship, emgAddress : emgAddress, emghomePhone : emghomePhone, emgMobile : emgMobile, behalf : behalf, transportMethod : transportMethod, howFar : howFar, earlyCalls : earlyCalls, visa : visa, visaLimitation : visaLimitation, qualification : qualification, shiftAvailable : shiftAvailable, notes : notes, newsletter : newsletter, jobactive : jobactive,	disabilities : disabilities, disabilityDesc : disabilityDesc, compensation : compensation, compensationDesc : compensationDesc, empName1 : empName1, doi1 : doi1, natureInjury1 : natureInjury1, durAbsense1 : durAbsense1, empName2 : empName2, doi2 : doi2, natureInjury2 : natureInjury2, durAbsense2 : durAbsense2, pension : pension, pensionDesc : pensionDesc, hearing : hearing, hearingDesc : hearingDesc, smoker : smoker, eyetrouble : eyetrouble, hearingImp : hearingImp, surgicalPro : surgicalPro, asthma : asthma, hernia : hernia, duodenal : duodenal, deafness : deafness, epilepsy : epilepsy, lungDisorder : lungDisorder, fainting : fainting, backdisorder : backdisorder, nervedisorder : nervedisorder, dizziness : dizziness, arthritis : arthritis, injury : injury, injuryDesc : injuryDesc, swollen : swollen, chronic : chronic, skin : skin, diabetes : diabetes, medicalAttention : medicalAttention, medicalAttentionDesc : medicalAttentionDesc, candidateId : candidateId, messageId : messageId, consultantId : consultantId, criminalHistory : criminalHistory, criminalHistoryDesc : criminalHistoryDesc, doo1 : doo1, natureOffence1 : natureOffence1, doo2 : doo2, natureOffence2 : natureOffence2, pcheck : pcheck},
						   dataType: 'json',
						   success: function (data) {
							   console.log(data);
							  if(data == 'success'){
							  	$('#regForm').hide();
								$('.message').show();
							  }else{
							  	$('.msg').html(data);
							  }
						   },
						   error: function(jqXHR, exception) {
								if (jqXHR.status === 0) {
									console.log('Not connect.\n Verify Network.');
								} else if (jqXHR.status == 404) {
									console.log('Requested page not found. [404]');
								} else if (jqXHR.status == 500) {
									console.log('Internal Server Error [500].');
								} else if (exception === 'parsererror') {
									console.log('Requested JSON parse failed.');
								} else if (exception === 'timeout') {
									console.log('Time out error.');
								} else if (exception === 'abort') {
									console.log('Ajax request aborted.');
								} else {
									console.log('Uncaught Error.\n' + jqXHR.responseText);
								}
							}
					  }).done(function(){
							
					  });
					},
					errorPlacement : function(error, element) {
						error.insertAfter(element.parent());
					}
				});
			});
			});
		</script>
<div class="modal"><!-- Place at bottom of page --></div>
	</body>
</html>