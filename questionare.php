<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
$licenceTypes = getOtherLicenceTypes($mysqli);
$gearTypes = getSafetyGearTypes($mysqli);
$expOpTypes = getExpOperating($mysqli);
$shiftTypes = getShiftAvailable($mysqli);
$consultants = getConsultants($mysqli);
$messageid = htmlentities($_REQUEST['messageid']);

/*$contents = retrieveCandidateEmailContent($mysqli,$messageid,$_SESSION['accountName']);
if($contents != NULL || $contents != 'CONVERSION-ERROR' || $contents <> ' '){
	$matchedEmail = extractEmailFromContent($contents);
*/	/* check existing email */
	//$emailCheck = matchExistingEmail($mysqli,$matchedEmail);
	/* ------------------------ */
$candidateMailFrom = retrieveCandidateName($mysqli,$messageid,$_SESSION['accountName']);
$str = explode('via',$candidateMailFrom);
$fullName = explode(' ',$str[0]);
$firstName = $fullName[0];
$lastName = $fullName[1].' '.$fullName[2];
$msgBody = retrieveCandidateMsgBody($mysqli,$messageid,$_SESSION['accountName']);
$emailAddress = get_string_between($msgBody, 'mailto:', '&quot;');
/*$phoneNumber = get_string_between($msgBody, 'Phone\r\n                                                        &lt;/p&gt;\r\n                                                        &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;\r\n                                                            ', '\r\n');*/
$phoneNumber = get_string_between($msgBody,'Phone
                                                        &lt;/p&gt;
                                                        &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;
                                                            ','
                                                        &lt;/p&gt;');
$qsForm = $qsForm.'<!-- widget grid -->
				<section id="widget-grid" class="">
					<!-- START ROW -->
					<div class="row">
						<!-- NEW COL START -->
						<article class="col-sm-12 col-md-12 col-lg-12">
							
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false"	data-widget-editbutton="false">
								<!-- widget options:
									usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
									
									data-widget-colorbutton="false"	
									data-widget-editbutton="false"
									data-widget-togglebutton="false"
									data-widget-deletebutton="false"
									data-widget-fullscreenbutton="false"
									data-widget-custombutton="false"
									data-widget-collapsed="true" 
									data-widget-sortable="false"
									
								-->
								<header>
									<span class="widget-icon"> <i class="fa fa-check txt-color-green"></i> </span>
									<h2>General Phone Screening Questions</h2>				
									
								</header>
				
								<!-- widget div-->
								<div>
									
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<!-- This area used as dropdown edit box -->
										
									</div>
									<!-- end widget edit box -->
									
									<!-- widget content -->
									<div class="widget-body no-padding">
										
										<!-- Success states for elements -->
										<form id="screenFrm" name="screenFrm" class="smart-form">
											<header>General Lab Telephone Screening Form</header>
											<fieldset>
                                            <div class="row">
                                            <section class="col col-4">
                                                <label for="firstName" class="label">First Name</label>
                                                <label class="input">
                                                    <input type="text" name="firstName" id="firstName" value="'.$firstName.'">
                                                </label>
                                            </section>
											<section class="col col-4">
                                                <label for="lastName" class="label">Last Name</label>
                                                <label class="input">
                                                    <input type="text" name="lastName" id="lastName" value="'.$lastName.'">
                                                </label>
                                            </section>
											<section class="col col-4">    
                                                <label class="label">Email</label>
                                                <label class="input">
                                                    <input type="text" name="candidateEmail" id="candidateEmail" value="'.$emailAddress.'">
                                                </label>
                                            </section>
                                            <section class="col col-4">
											<label class="select">
												<select id="candidateSex" name="candidateSex">
													<option value="">Select Gender...</option>
													<option value="Male">Male</option>
													<option value="Female">Female</option>
													<option value="Noanswer">Prefer not to answer</option>
												</select> <i></i> </label>   
                                            </section>
                                             </div>
                                             <div class="row">
                                                <section class="col col-4">    
                                                    <label for="screenDate" class="label">Date</label>
													<label class="input">
														<input type="text" name="screenDate" id="screenDate" class="screenDate">
													</label>
												</section>
                                                <section class="col col-4">
                                                	<label for="suburb" class="label">Suburb</label>
													<label class="input">
														<input type="text" name="candidateSuburb" id="candidateSuburb">
													</label>
                                                </section>
                                             </div>
                                             <div class="row">
                                                <section class="col col-4">
                                                	<label for="phone" class="label">Phone</label>
													<label class="input">
														<input type="text" name="candidatePhone" id="candidatePhone">
													</label>
                                                </section>
                                                <section class="col col-4">
                                                	<label for="mobile" class="label">Mobile</label>
													<label class="input">
														<input type="text" name="candidateMobile" id="candidateMobile" value="'.$phoneNumber.'">
													</label>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-4">
                                                    <label for="currentWrk" class="label">Are you currently working or are you just looking for work? Tell me a little bit about your work history...</label>
                                                    <label class="textarea textarea-resizable">
                                                        <textarea rows="3" class="custom-scroll" name="currentWrk" id="currentWrk"></textarea>
                                                    </label>
                                                </section>
                                                <section class="col col-4">
                                                <label for="howfar" class="label">How far are you willing to Travel for Work?</label>
                                                    <label class="input">
                                                        <input type="text" name="howfar" id="howfar">
                                                    </label>
                                                </section>
                                            </div>
                                            <div class="row">
                                                <section class="col col-4">
                                                    <label for="genLabourPay" class="label">For most of our work, general labourers are paid around $21-$25 per hour + super. Is this ok with you?</label>
                                                    <label class="textarea textarea-resizable">
                                                        <textarea rows="3" class="custom-scroll" name="genLabourPay" id="genLabourPay"></textarea>
                                                    </label>
                                                </section>
                                                <section class="col col-4">
                                                <label for="criminalConviction" class="label">Do you have Prior or Pending Criminal Convictions that may affect your application?</label>
                                                        <label class="radio">
                                                            <input type="radio" name="criminalConviction" id="criminalConviction" value="Yes"><i></i>Yes
                                                        </label>
															<textarea name="convictionDescription" id="convictionDescription"></textarea>
                                                        <label class="radio">
                                                            <input type="radio" name="criminalConviction" id="criminalConviction" value="No" checked><i></i>No
                                                        </label>
                                                </section>
                                                <section class="col col-4">
                                                <label for="hasCar" class="label">Do you have your own car and licence?</label>
                                                        <label class="radio">
                                                            <input type="radio" name="hasCar" id="hasCar" value="Yes"><i></i>Yes
                                                        </label>
                                                        <label class="radio">
                                                            <input type="radio" name="hasCar" id="hasCar" value="No" checked><i></i>No
                                                        </label>
                                                    </label>
                                                </section>
                                                <section class="col col-4">
                                                <label class="label">What is your currnet residential status?</label>
                                                        <label class="radio"></label>
                                                            <input type="radio" name="residentStatus" id="Citizen" value="Australian Citizen"><i></i>Australian Citizen
                                                        
                                                        <label class="radio"></label>
                                                            <input type="radio" name="residentStatus" id="PR" value="Australian Permanent Resident"><i></i>Australian Permanent Resident
                                                        
                                                        <label class="radio"></label>
                                                            <input type="radio" name="residentStatus" id="WorkingVisa" value="Working Visa"><i></i>Working Visa
                                                        
                                                        <label class="radio"></label>
                                                            <input type="radio" name="residentStatus" id="Student" value="Student Visa"><i></i>Student Visa
                                                        
                                                </section>
                                                <section class="col col-6">
                                                <label for="otherLicence" class="label">What other license/s and qualifications/experience do you have?</label>';
foreach($licenceTypes as $lT) {
	$qsForm = $qsForm.'<div class="col col-6">
                         <label class="checkbox">
                         <input type="checkbox" name="otherLicence" value="'.$lT['otherLicenceId'].'">
                         <i></i>'.$lT['otherLicenceType'].'</label>
					   </div>';
}
                                                        
														             
$qsForm = $qsForm.'</section><section class="col col-6">
                   <label for="safetyGear" class="label">Do you own/or willing to get?</label>';
foreach($gearTypes as $gT){		   
	$qsForm = $qsForm.'<div class="col col-6">
					<label class="checkbox">
                    <input type="checkbox" name="safetyGear" value="'.$gT['safetyGearId'].'">
                    <i></i>'.$gT['safetyGear'].'</label>
                   </div>';
}
$qsForm = $qsForm.'</section>
				   </div>
				   <div class="row">';
$qsForm = $qsForm.'<section class="col col-4">
                    <label for="medicalCondition" class="label">Do you have any medical conditions that might affect your work? Any lifting restrictions or back conditions?</label>
					<label class="radio">
						<input type="radio" name="medicalCondition" id="medicalCondition" value="Yes"><i></i>Yes
					</label>
					<label for="medicalConditionDesc" class="textarea textarea-resizable">
						<textarea rows="3" class="custom-scroll" name="medicalConditionDesc" id="medicalConditionDesc"></textarea>
						</label>
					<label class="radio">
						<input type="radio" name="medicalCondition" id="medicalCondition" value="No" checked><i></i>No
					</label>
                    </section>
                    </div>    
                    <div class="row">
					<section class="col col-4">
					<label for="workType" class="label">Most of our work is on-call casual work with ongoing shifts beign offered to those who perform well and help us out by going in short notice. Does this type of arrangement suit you?</label>
					<label for="workType" class="textarea textarea-resizable">
						<textarea rows="3" class="custom-scroll" name="workType" id="workType"></textarea>
					</label>
					</section>
                    <section class="col col-4">
                    <label for="shiftAvailable" class="label">Shift Availability:</label>';
foreach($shiftTypes as $sT){						
$qsForm = $qsForm.'<input type="checkbox" name="shiftAvailable" value="'.$sT['shiftAvailableId'].'">
                   <i></i>'.$sT['shift'].'';
}
$qsForm = $qsForm.'</section>
					<section class="col col-4">
					<label for="overtime" class="label">Are you able to work overtime if required?</label>
					<label class="radio">
					  <input type="radio" name="overtime" id="overtime" value="Yes"><i></i>Yes
					</label>
					<label class="radio">
					  <input type="radio" name="overtime" id="overtime" value="No" checked><i></i>No
					</label>
					</section>
                    </div>';
$qsForm = $qsForm.'<div class="row">
                                            	<section class="col col-4">
                                                <label for="bookInterview" class="label">Book Candidate in for Interview?</label>
                                                <label class="radio">
                                                <input type="radio" name="bookInterview" id="bookInterview" value="Yes">
                                                <i></i>Yes</label>
                                                <label class="radio">
                                                    <input type="radio" name="bookInterview" id="bookInterview" value="No" checked>
                                                    <i></i>No</label>
                                                </section>
                                            </div>
                                            <div class="row">
                                            	<section class="col col-4">
                                                <label class="label">Date and Time of Interview?</label>
                                                <label class="input">
                                                <input type="text" name="intvwTime" id="intvwTime" class="intvwTime">
                                                </label>
                                                </section>
                                                <section class="col col-4">
                                                <label class="label">Select Consultant</label>
													<label class="select">
														<select class="input-sm" name="consultantId" id="consultantId">';
foreach($consultants as $cT){														
$qsForm = $qsForm.'<option value="'.$cT['consultantId'].'">'.$cT['name'].'</option>';
}
$qsForm = $qsForm.'</select> <i></i> </label>
                                                </section>
                                            </div>
											<br> 
                                           </fieldset>
                                        <footer>
												<input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"><input type="submit" name="screenSubmit" id="screenSubmit" class="screenSubmit btn btn-primary" value="Submit"/>
										</footer>
                                         </form>
										<!--/ Success states for elements -->				
										
									</div>
									<!-- end widget content -->
									
								</div>
								<!-- end widget div -->
								
							</div>
							<!-- end widget -->
				
						</article>
                        </div>
                        </section>';
echo $qsForm;	
?>