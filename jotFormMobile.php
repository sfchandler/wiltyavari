<?php
session_start();
$useragent=$_SERVER['HTTP_USER_AGENT'];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <title>Candidate Information Collection Form</title>
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui.css">
    <!-- JQUERY UI AUTO COMPLETE STYLES -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/jquery.ui.autocomplete.css">
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <!-- Jquery UI date range picker -->
    <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker.css" />
    <!-- Jquery UI date time picker -->
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <!-- BOOTSTRAP JS -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
    <script src="js/libs/jquery-ui-1.10.3.min.js"></script>
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
    <img src="img/LogoChandlerServices.png" width="150" height="60">
</div>
<div align="center"><h1>Candidate Registration Information</h1></div>
<div class="container">
<br>
<h3>Attach Identification Documents</h3>
<div class="row">
    <section class="col col-sm-3">
        <form name="frmPassport" id="frmPassport" class="frmPassport" action="jotAttachments.php" method="post" enctype="multipart/form-data">
        <label><i class="fa fa-plane"></i> Attach Passport</label>
        <input type="file" name="passportFile" id="passportFile" onchange="this.parentNode.nextSibling.value = this.value"/>
        <button class="btn btn-info btn-sm" type="submit" value="Attach Passport"><i class="glyphicon glyphicon-upload"></i> Attach Passport image</button>
        <div id="passportprogress">
            <div id="passportbar"></div>
            <div id="passportpercent">0%</div>
        </div>
        <div id="passportmessage"></div>
        </form>
    </section>
    <section class="col col-sm-3">
        <form name="frmBirth" id="frmBirth" class="frmBirth" action="jotAttachments.php" method="post" enctype="multipart/form-data">
        <label><i class="fa fa-certificate"></i> Attach Australian Birth certificate</label>
        <input type="file" name="birthFile" id="birthFile" onchange="this.parentNode.nextSibling.value = this.value"/>
        <button class="btn btn-info btn-sm" type="submit" value="Attach Birth Certificate"><i class="glyphicon glyphicon-upload"></i> Attach Birth Certificate image</button>
        <div id="birthprogress">
            <div id="birthbar"></div>
            <div id="birthpercent">0%</div>
        </div>
        <div id="birthmessage"></div>
        </form>
    </section>
    <section class="col col-sm-3">
        <form name="frmCitizen" id="frmCitizen" action="jotAttachments.php" class="fromCitizen" method="post" enctype="multipart/form-data">
        <label><i class="fa fa-certificate"></i> Attach Australian Citizenship certificate</label>
        <input type="file" name="citizenFile" id="citizenFile" onchange="this.parentNode.nextSibling.value = this.value"/>
        <button class="btn btn-info btn-sm" type="submit" value="Attach Citizenship Certificate"><i class="glyphicon glyphicon-upload"></i> Attach Citizenship Certificate image</button>
        <div id="citizenprogress">
            <div id="citizenbar"></div>
            <div id="citizenpercent">0%</div>
        </div>
        <div id="citizenmessage"></div>
        </form>
    </section>
</div>
<br>
    <h3>Attach Secondary Documents</h3>
<div class="row">
    <section class="col col-sm-3">
        <form name="frmDriving" id="frmDriving" class="frmDriving" action="jotAttachments.php" method="post" enctype="multipart/form-data">
            <label><i class="fa fa-car"></i> Attach Driving Licence</label>
            <input type="file" name="drivingFile" id="drivingFile" onchange="this.parentNode.nextSibling.value = this.value"/>
            <button class="btn btn-info btn-sm" type="submit" value="Attach Driving Licence"><i class="glyphicon glyphicon-upload"></i> Attach Driving Licence image</button>
            <div id="drivingprogress">
                <div id="drivingbar"></div>
                <div id="drivingpercent">0%</div>
            </div>
            <div id="drivingmessage"></div>
        </form>
    </section>
    <section class="col col-sm-3">
        <form name="frmMedicare" id="frmMedicare" class="frmMedicare" action="jotAttachments.php" method="post" enctype="multipart/form-data">
            <label><i class="fa fa-medkit"></i> Attach Australian Medicare certificate</label>
            <input type="file" name="medicareFile" id="medicareFile" onchange="this.parentNode.nextSibling.value = this.value"/>
            <button class="btn btn-info btn-sm" type="submit" value="Attach Medicare Card"><i class="glyphicon glyphicon-upload"></i> Attach Medicare Card image</button>
            <div id="medicareprogress">
                <div id="medicarebar"></div>
                <div id="medicarepercent">0%</div>
            </div>
            <div id="medicaremessage"></div>
        </form>
    </section>
    <section class="col col-sm-3">
        <form name="frmStudent" id="frmStudent" action="jotAttachments.php" class="frmStudent" method="post" enctype="multipart/form-data">
            <label><i class="fa fa-file-photo-o"></i> Attach Australian Student card</label>
            <input type="file" name="studentFile" id="studentFile" onchange="this.parentNode.nextSibling.value = this.value"/>
            <button class="btn btn-info btn-sm" type="submit" value="Attach Student Card"><i class="glyphicon glyphicon-upload"></i> Attach Student Card image</button>
            <div id="studentprogress">
                <div id="studentbar"></div>
                <div id="studentpercent">0%</div>
            </div>
            <div id="studentmessage"></div>
        </form>
    </section>
    <section class="col col-sm-3">
        <form name="frmPolice" id="frmPolice" action="jotAttachments.php" class="frmPolice" method="post" enctype="multipart/form-data">
            <label><i class="fa fa-file-photo-o"></i> Attach Australian Police Clearance certificate</label>
            <input type="file" name="policeFile" id="policeFile" onchange="this.parentNode.nextSibling.value = this.value"/>
            <button class="btn btn-info btn-sm" type="submit" value="Attach Police Clearance"><i class="glyphicon glyphicon-upload"></i> Attach Police clearance certificate</button>
            <div id="policeprogress">
                <div id="policebar"></div>
                <div id="policepercent">0%</div>
            </div>
            <div id="policemessage"></div>
        </form>
    </section>
</div>
<br>
<form name="frmJotForm" id="frmJotForm" class="frmJotForm" method="post">
    <fieldset>
    <legend>Personal Information</legend>
    <div class="row">
        <section class="col col-sm-3">
            <label>First Name</label>
            <input type="text" name="firstName" id="firstName" class="form-control" placeholder="First Name"/>
        </section>
        <section class="col col-sm-3">
            <label>Last Name</label>
            <input type="text" name="lastName" id="lastName" class="form-control" placeholder="Last Name"/>
        </section>
        <section class="col col-sm-3">
            <label>Gender</label>
            <select name="gender" id="gender" class="form-control">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
        </section>
        <section class="col col-sm-3">
            <label>Data of birth</label>
            <input type="hidden" name="dob" id="dob" class="form-control" placeholder="Data of birth" value="">
            <input type="text" name="dob" id="dob" class="form-control" placeholder="Data of birth" value="" readonly>
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-3">
            <label>Full Address</label>
            <textarea class="form-control" name="address" id="address" placeholder="Full Address"></textarea>
        </section>
        <section class="col col-sm-3">
            <label>Street Number</label>
            <input name="street_number_1" id="street_number_1" placeholder="Street No" value="" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Street Name</label>
            <input name="street_name" id="street_name" placeholder="Street Name" value="" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Suburb</label>
            <input name="suburb" id="suburb" placeholder="Suburb" value="" class="form-control"/>
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-3">
            <label>State</label>
            <input name="state" id="state" placeholder="State" value="" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Post code</label>
            <input name="postcode" id="postcode" placeholder="Postcode" value="" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Mobile</label>
            <input name="mobile" id="mobile" placeholder="Mobile" value="" class="form-control" maxlength="10"/>
        </section>
        <section class="col col-sm-3">
            <label>Email</label>
            <input name="email" id="email" type="email" placeholder="Email" value="" class="form-control" required/>
        </section>
    </div>
    <br>
    <div class="row">
        <section class="col col-sm-3">
            <label>Residential Status:</label>
            <div>
                <input type="radio" name="residentStatus" id="Citizen" value="Australian Citizen" class="">&nbsp;Australian Citizen
                <br>
                <input type="radio" name="residentStatus" id="PR" value="Australian Permanent Resident" class="">&nbsp;Australian Permanent Resident
                <br>
                <input type="radio" name="residentStatus" id="WorkingVisa" value="Working Visa" class="">&nbsp;Working Visa
                <br>
                <input type="radio" name="residentStatus" id="TemporaryResident" value="Temporary Resident" class="">&nbsp;Temporary Resident Visa
                <br>
                <input type="radio" name="residentStatus" id="Student" value="Student Visa" class="">&nbsp;Student Visa
            </div>
        </section>
        <section class="col col-sm-3">
            <input type="hidden" name="passportFileSubmitted" id="passportFileSubmitted" value="">
            <input type="hidden" name="birthFileSubmitted" id="birthFileSubmitted" value="">
            <input type="hidden" name="citizenFileSubmitted" id="citizenFileSubmitted" value="">
            <input type="hidden" name="drivingFileSubmitted" id="drivingFileSubmitted" value="">
            <input type="hidden" name="medicareFileSubmitted" id="medicareFileSubmitted" value="">
            <input type="hidden" name="studentFileSubmitted" id="studentFileSubmitted" value="">
            <input type="hidden" name="policeFileSubmitted" id="policeFileSubmitted" value="">
        </section>
    </div>
    <br>
    <div class="row">
        <section class="col col-sm-3">
            <label>WorkPro CIN(If applicable)</label>
            <input type="text" name="workprocin" id="workprocin" class="form-control" placeholder="Work PRO CIN"/>
        </section>
    </div>
    </fieldset>
        <br>
        <fieldset>
        <legend>Emergency Contact Information</legend>
        <div class="row">
            <section class="col col-sm-3">
                <label>Full Name</label>
                <input type="text" name="emcName" id="emcName" placeholder="Emergency Contact Name" class="form-control"/>
            </section>
            <section class="col col-sm-3">
                <label>Relationship</label>
                <input type="text" name="emcRelationship" id="emcRelationship" placeholder="Emergency Contact Relationship" class="form-control"/>
            </section>
            <section class="col col-sm-3">
                <label>Mobile Phone Number</label>
                <input name="emcMobile" id="emcMobile" placeholder="Emergency Contact Mobile" value="" class="form-control"/>
            </section>
            <section class="col col-sm-3">
                <label>Home Phone Number</label>
                <input name="emcHomePhone" id="emcHomePhone" placeholder="Emergency Contact Home Phone" value="" class="form-control"/>
            </section>
        </div>
        </fieldset>
        <br>
        <fieldset>
            <legend>Referee 1 Information</legend>
                <div class="row">
                        <section class="col col-sm-3">
                            <label>Referee 1 Name</label>
                            <input type="text" name="referee1Name" id="referee1Name" placeholder="Referee 1 Name" class="form-control"/>
                        </section>
                        <section class="col col-sm-3">
                            <label>Referee 1 CompanyName</label>
                            <input type="text" name="referee1CompanyName" id="referee1CompanyName" placeholder="Referee 1 CompanyName" class="form-control"/>
                        </section>
                        <section class="col col-sm-3">
                            <label>Referee 1 Position</label>
                            <input type="text" name="referee1Position" id="referee1Position" placeholder="Referee 1 Position" class="form-control"/>
                        </section>
                        <section class="col col-sm-3">
                            <label>Referee 1 Relationship</label>
                            <input type="text" name="referee1Relationship" id="referee1Relationship" placeholder="Referee 1 Relationship" class="form-control"/>
                        </section>
                </div>
                <div class="row">
                    <section class="col col-sm-3">
                        <label>Referee 1 Mobile</label>
                        <input type="text" name="referee1Mobile" id="referee1Mobile" placeholder="Referee 1 Mobile" class="form-control"/>
                    </section>
                </div>
        </fieldset>
        <br>
        <fieldset class="fieldset">
            <legend>Referee 2 Information</legend>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Referee 2 Name</label>
                    <input type="text" name="referee2Name" id="referee2Name" placeholder="Referee 2 Name" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Referee 2 CompanyName</label>
                    <input type="text" name="referee2CompanyName" id="referee2CompanyName" placeholder="Referee 2 CompanyName" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Referee 2 Position</label>
                    <input type="text" name="referee2Position" id="referee2Position" placeholder="Referee 2 Position" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Referee 2 Relationship</label>
                    <input type="text" name="referee2Relationship" id="referee2Relationship" placeholder="Referee 2 Relationship" class="form-control"/>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Referee 2 Mobile</label>
                    <input type="text" name="referee2Mobile" id="referee2Mobile" placeholder="Referee 2 Mobile" class="form-control"/>
                </section>
            </div>
        </fieldset>
        <br>
        <fieldset>
            <legend>Bank Account Information</legend>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Account Name</label>
                    <input type="text" name="bankAccountName" id="bankAccountName" placeholder="Account Name" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Bank Name</label>
                    <input type="text" name="bankName" id="bankName" placeholder="Bank Name" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>BSB</label>
                    <input type="text" name="bsb" id="bsb" placeholder="XXX-XXX" class="form-control" maxlength="7"/>
                </section>
                <section class="col col-sm-3">
                    <label>Account Number</label>
                    <input type="text" name="bankAccountNumber" id="bankAccountNumber" placeholder="Account Number" class="form-control"/>
                </section>
            </div>
        </fieldset>
        <div class="row">
            <section class="col col-sm-3">
                <label>Tax File Number</label>
                <input type="text" name="tfn" id="tfn" placeholder="TFN" class="form-control" maxlength="10"/>
            </section>
        </div>
        <br>
        <fieldset>
            <legend>Super Fund Information</legend>
            <div class="row">
                <section class="col col-sm-12">
                    <input type="checkbox" name="supercheck" id="supercheck" class="supercheck" value="DO NOT HAVE OWN SUPER ACCOUNT">
                    <label for="supercheck">DO NOT HAVE OWN SUPER ACCOUNT</label>
                </section>
            </div>
            <div class="row nosuper">
                <section class="col col-sm-3">
                    <label>Super Account Name</label>
                    <input type="text" name="superAccountName" id="superAccountName" placeholder="Super Account Name" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Super Fund Name</label>
                    <input type="text" name="superFundName" id="superFundName" placeholder="Super Account Name" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Super Membership Number</label>
                    <input type="text" name="superMembershipNo" id="superMembershipNo" placeholder="Super Membership No" class="form-control"/>
                </section>
            </div>
            <div class="row nosuper">
                <section class="col col-sm-3">
                    <label>Super Fund Address</label>
                    <input type="text" name="superFundAddress" id="superFundAddress" placeholder="Super Fund Address" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Super Phone No</label>
                    <input type="text" name="superPhoneNo" id="superPhoneNo" placeholder="Business/Landline Number" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Super Website</label>
                    <input type="text" name="superWebsite" id="superWebsite" placeholder="Website" class="form-control"/>
                </section>
                <section class="col col-sm-3">
                    <label>Super Fund ABN</label>
                    <input type="text" name="superFundABN" id="superFundABN" placeholder="Super Fund ABN" class="form-control"/>
                </section>
            </div>
            <div class="row nosuper">
                <section class="col col-sm-3">
                    <label>Super Fund USI</label>
                    <input type="text" name="superFundUSI" id="superFundUSI" placeholder="Super Fund USI" class="form-control"/>
                </section>
            </div>
        </fieldset>
        <label>Your employer is not required to accept your choice of fund if you have not provided the appropriate documents.</label>
        <br>
        <div align="center"><h3>Health Questionaire</h3></div>
        <hr>
        <p> Health and safety of our employees is of utmost importance to chandler recruitment. This questionnaire is designed to assist us in ensuring that our employees are only placed in the assignments which they are capable of performing efficiently and in a safely manner.</p>
        <p>Please read this document carefully and discuss any queries that you may have prior to completing the form with your respective Chandler Recruitment Consultants.</p>
        <p><b>IMPORTANT:</b> The information obtained in this questionnaire will be treated in strict confidence and will only be used in conjunction with the requirements of an assignment.</p>
        <p><b>INJURY DECLARATION</b></p>
        <p>You are required to disclose to Chandler Recruitment Consultants any or all existing or pre-existing injuries, illnesses or diseases suffered by you which could be accelerated, aggravated, deteriorate or recur by you performing the responsibilities associated with the employment for which you are applying with Chandler Recruitment Consultants.</p>
        <p>If you fail to disclose this information or if you provide false and misleading information in relation to any pre-existing injury/condition you and your dependents may not be entitled to any form of workers’ compensation and this may also constitute grounds for disciplinary action or dismissal.</p>
        <fieldset>
        <legend>Section A : Health History</legend>
            <label>Please select the appropriate answer:</label>
            <div class="row">
                <!--<section class="col col-sm-3">
                    <label>Do you have Prior or Pending Criminal Convictions that may affect your application?</label>
                    <input type="radio" name="criminalConviction" value="Yes">Yes
                    <input type="radio" name="criminalConviction" value="No" checked>No
                </section>-->
                <section class="col col-sm-3">
                    <label>Have you ever been medically retired on the grounds of ill health?</label>
                    <input type="radio" name="medicalCondition" value="Yes">Yes
                    <input type="radio" name="medicalCondition" value="No">No
                    <textarea name="medConditionDesc" id="medConditionDesc"></textarea>
                </section>
                <section class="col col-sm-3">
                    <label>Do you have a physical or psychological condition that might preclude you from some work duties or certain workplace environments (eg. asthma, Hay fever, vertigo)?</label>
                    <input type="radio" name="psycoCondition" value="Yes">Yes
                    <input type="radio" name="psycoCondition" value="No">No
                    <textarea name="psycoConditionDesc" id="psycoConditionDesc"></textarea>
                </section>
                <section class="col col-sm-3">
                    <label>Do you suffer from any allergies?</label>
                    <br>
                    <input type="radio" name="alergyCondition" value="Yes">Yes
                    <input type="radio" name="alergyCondition" value="No">No
                    <textarea name="alergyConditionDesc" id="alergyConditionDesc"></textarea>
                </section>
            </div>
         <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Some work duties and workplace environments may not be advisable for pregnant women. If you wish to indicate that you are pregnant you may do so voluntarily here.</label>
                    <input type="radio" name="pregnantCondition" value="Yes">Yes
                    <input type="radio" name="pregnantCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any neck or shoulder injuries/pain</label>
                    <br>
                    <input type="radio" name="shoulderCondition" value="Yes">Yes
                    <input type="radio" name="shoulderCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any arm, hand, elbow or wrist injury/pain</label>
                    <br>
                    <input type="radio" name="armCondition" value="Yes">Yes
                    <input type="radio" name="armCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Repetitive strains or overuse injury</label>
                    <br>
                    <input type="radio" name="strainCondition" value="Yes">Yes
                    <input type="radio" name="strainCondition" value="No">No
                </section>
            </div>
        <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Epilepsy, fits, seizures, blackouts</label>
                    <br>
                    <input type="radio" name="epilepsyCondition" value="Yes">Yes
                    <input type="radio" name="epilepsyCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Loss of hearing, Impaired Hearing</label>
                    <br>
                    <input type="radio" name="hearingCondition" value="Yes">Yes
                    <input type="radio" name="hearingCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Stress/Anxiety or nervous disorder</label>
                    <br>
                    <input type="radio" name="stressCondition" value="Yes">Yes
                    <input type="radio" name="stressCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Fatigue / tiredness related issues</label>
                    <br>
                    <input type="radio" name="fatiqueCondition" value="Yes">Yes
                    <input type="radio" name="fatiqueCondition" value="No">No
                </section>
            </div>
        <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Asthma or other respiratory/breathing problems</label>
                    <br>
                    <input type="radio" name="asthmaCondition" value="Yes">Yes
                    <input type="radio" name="asthmaCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Arthritis, rheumatism</label>
                    <br>
                    <input type="radio" name="arthritisCondition" value="Yes">Yes
                    <input type="radio" name="arthritisCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Dizziness, fainting, vertigo</label>
                    <br>
                    <input type="radio" name="dizzinessCondition" value="Yes">Yes
                    <input type="radio" name="dizzinessCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Head Injury</label>
                    <br>
                    <input type="radio" name="headCondition" value="Yes">Yes
                    <input type="radio" name="headCondition" value="No">No
                </section>
            </div>
        <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Speech impairment</label>
                    <br>
                    <input type="radio" name="speechCondition" value="Yes">Yes
                    <input type="radio" name="speechCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any back injury/pain e.g. Scoliosis</label>
                    <br>
                    <input type="radio" name="backCondition" value="Yes">Yes
                    <input type="radio" name="backCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any knee, leg or ankle pain/injury</label>
                    <br>
                    <input type="radio" name="kneeCondition" value="Yes">Yes
                    <input type="radio" name="kneeCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Persistent or frequent headaches, migraines</label>
                    <br>
                    <input type="radio" name="persistentCondition" value="Yes">Yes
                    <input type="radio" name="persistentCondition" value="No">No
                </section>
            </div>
        <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Skin disorders, dermatitis, eczema</label>
                    <br>
                    <input type="radio" name="skinCondition" value="Yes">Yes
                    <input type="radio" name="skinCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any stomach strains/hernias etc.</label>
                    <br>
                    <input type="radio" name="stomachStrains" value="Yes">Yes
                    <input type="radio" name="stomachStrains" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Difficulty with vision or sight in either eye, Impaired Vision</label>
                    <br>
                    <input type="radio" name="visionCondition" value="Yes">Yes
                    <input type="radio" name="visionCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any problems with bones/joints or muscles</label>
                    <br>
                    <input type="radio" name="boneCondition" value="Yes">Yes
                    <input type="radio" name="boneCondition" value="No">No
                </section>
            </div>
        <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>High / Low blood pressure</label>
                    <br>
                    <input type="radio" name="bloodCondition" value="Yes">Yes
                    <input type="radio" name="bloodCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Lung disorders/ Nerve disorders</label>
                    <br>
                    <input type="radio" name="lungCondition" value="Yes">Yes
                    <input type="radio" name="lungCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Any operations or surgery? If Yes Please give details</label>
                    <br>
                    <input type="radio" name="surgeryInformation" value="Yes">Yes
                    <input type="radio" name="surgeryInformation" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Stomach problems, ulcers</label>
                    <br>
                    <input type="radio" name="stomachCondition" value="Yes">Yes
                    <input type="radio" name="stomachCondition" value="No">No
                </section>
            </div>
        <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Heart trouble, angina</label>
                    <br>
                    <input type="radio" name="heartCondition" value="Yes">Yes
                    <input type="radio" name="heartCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Infectious disease</label>
                    <br>
                    <input type="radio" name="infectiousCondition" value="Yes">Yes
                    <input type="radio" name="infectiousCondition" value="No">No
                </section>
            </div>
        </fieldset>
        <br>
        <fieldset>
            <legend>Section B : Medical Details</legend>
            <label>Please select the appropriate answer:</label>
            <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Are you currently receiving any medical treatment for illness, injury or medical condition?</label>
                    <br>
                    <input type="radio" name="medicalTreatment" value="Yes">Yes
                    <input type="radio" name="medicalTreatment" value="No">No
                    <textarea name="medicalTreatmentDesc" id="medicalTreatmentDesc"></textarea>
                </section>
                <section class="col col-sm-3">
                    <label>Are you taking any medication that has the potential to cause drowsiness or affect your work performance (including operating machinery?</label>
                    <br>
                    <input type="radio" name="drowsinessCondition" value="Yes">Yes
                    <input type="radio" name="drowsinessCondition" value="No">No
                    <textarea name="drowsinessConditionDesc" id="drowsinessConditionDesc"></textarea>
                </section>
                <section class="col col-sm-3">
                    <label>Do you have any pre-existing and/or chronic and/or long term injuries or illness?</label>
                    <br>
                    <input type="radio" name="chronicCondition" value="Yes">Yes
                    <input type="radio" name="chronicCondition" value="No">No
                    <textarea name="chronicConditionDesc" id="chronicConditionDesc"></textarea>
                </section>
                <section class="col col-sm-3">
                    <label>Have you ever had a work related injury?</label>
                    <br>
                    <input type="radio" name="workInjury" value="Yes">Yes
                    <input type="radio" name="workInjury" value="No">No
                    <textarea name="workInjuryDesc" id="workInjuryDesc"></textarea>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Was a Workcover claim lodged? (Question not applicable to QLD. Applicants)</label>
                    <br>
                    <input type="radio" name="workCoverClaim" value="Yes">Yes
                    <input type="radio" name="workCoverClaim" value="No">No
                </section>
            </div>
        </fieldset>
        <br>
        <fieldset>
            <legend>Section C : Physical Abilities</legend>
            <label class="">Please indicate whether you have, or could have, difficulties performing any of the following activities.</label>
            <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Crouching/bending/ Kneeling (repeatedly)</label>
                    <br>
                    <input type="radio" name="crouchingCondition" value="Yes">Yes
                    <input type="radio" name="crouchingCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Sitting for up to 30 minutes</label>
                    <br>
                    <input type="radio" name="sittingCondition" value="Yes">Yes
                    <input type="radio" name="sittingCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Working above shoulder height</label>
                    <br>
                    <input type="radio" name="workShoulderHeight" value="Yes">Yes
                    <input type="radio" name="workShoulderHeight" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Hearing a normal conversation</label>
                    <br>
                    <input type="radio" name="hearingConversation" value="Yes">Yes
                    <input type="radio" name="hearingConversation" value="No">No
                </section>
            </div>
            <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Climbing a ladder/working at heights</label>
                    <br>
                    <input type="radio" name="workAtHeights" value="Yes">Yes
                    <input type="radio" name="workAtHeights" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Walking/working on uneven ground</label>
                    <br>
                    <input type="radio" name="groundCondition" value="Yes">Yes
                    <input type="radio" name="groundCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Handling meat and/or food produce</label>
                    <br>
                    <input type="radio" name="handlingFood" value="Yes">Yes
                    <input type="radio" name="handlingFood" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Performing Shift Work</label>
                    <br>
                    <input type="radio" name="shiftWork" value="Yes">Yes
                    <input type="radio" name="shiftWork" value="No">No
                </section>
            </div>
            <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Standing for 30 minutes</label>
                    <br>
                    <input type="radio" name="standingMinutes" value="Yes">Yes
                    <input type="radio" name="standingMinutes" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Lifting objects weighing 15 kilograms or more</label>
                    <br>
                    <input type="radio" name="liftingCondition" value="Yes">Yes
                    <input type="radio" name="liftingCondition" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Gripping objects firmly with both hands</label>
                    <br>
                    <input type="radio" name="grippingObjects" value="Yes">Yes
                    <input type="radio" name="grippingObjects" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Repetitive movement of hands or arms</label>
                    <br>
                    <input type="radio" name="repetitiveMovement" value="Yes">Yes
                    <input type="radio" name="repetitiveMovement" value="No">No
                </section>
            </div>
            <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Walking up and down stairs</label>
                    <br>
                    <input type="radio" name="walkingStairs" value="Yes">Yes
                    <input type="radio" name="walkingStairs" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Using hand tools/operating machinery</label>
                    <br>
                    <input type="radio" name="handTools" value="Yes">Yes
                    <input type="radio" name="handTools" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Wearing personal protective equipment (PPE)</label>
                    <br>
                    <input type="radio" name="protectiveEquipment" value="Yes">Yes
                    <input type="radio" name="protectiveEquipment" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Working at heights</label>
                    <br>
                    <input type="radio" name="workHeights" value="Yes">Yes
                    <input type="radio" name="workHeights" value="No">No
                </section>
            </div>
            <!--<br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Handling meat and/or food produce</label>
                    <br>
                    <input type="radio" name="handlingFood" value="Yes">Yes
                    <input type="radio" name="handlingFood" value="No" checked>No
                </section>
                <section class="col col-sm-3">
                    <label>Performing Shift Work</label>
                    <br>
                    <input type="radio" name="shiftWork" value="Yes">Yes
                    <input type="radio" name="shiftWork" value="No" checked>No
                </section>
                <section class="col col-sm-3">
                    <label>Standing for 30 minutes</label>
                    <br>
                    <input type="radio" name="standingMinutes" value="Yes">Yes
                    <input type="radio" name="standingMinutes" value="No" checked>No
                </section>
                <section class="col col-sm-3">
                    <label>Lifting objects weighing 15 kilograms or more</label>
                    <input type="radio" name="liftingCondition" value="Yes">Yes
                    <input type="radio" name="liftingCondition" value="No" checked>No
                </section>
            </div>-->
            <br>
            <div class="row">
                <section class="col col-sm-3">
                    <label>Working in confined spaces or underground</label>
                    <br>
                    <input type="radio" name="workConfinedSpaces" value="Yes">Yes
                    <input type="radio" name="workConfinedSpaces" value="No">No
                </section>
                <section class="col col-sm-3">
                    <label>Working in hot/cold environments inc. refrigerated storage</label>
                    <br>
                    <input type="radio" name="workHotColdEnvironment" value="Yes">Yes
                    <input type="radio" name="workHotColdEnvironment" value="No">No
                </section>
            </div>
            <br>
        </fieldset>
    <fieldset>
        <div align="center"><legend>PRIVACY POLICY</legend></div>
    </fieldset>
    <div class="row">
        <section class="col col-lg-6">
            <p>Your privacy is important to  <?php echo DOMAIN_NAME; ?>. It is our commitment to protect the privacy of the information of our employees and others. This statement outlines our privacy policy and how we manage and disclose personal information.</p>
            <br>
            <div><b>What is your personal information?</b></div>
            <p>Personal information is any information or an opinion (whether true or not) about you. It may range from the very sensitive (eg. criminal history, medical history or condition) to the everyday information (eg. full name, address, and phone number). It would include the opinions of others about your work performance (whether true or not), your work experience and qualifications, aptitude test results and other information obtained by us in connection with your possible work placements.</p>
            <div><b>Why is your personal information collected?</b></div>
            <p>Your personal information will be collected by the experienced team of consultants at  <?php echo DOMAIN_NAME; ?>. It is collected and held to assist  <?php echo DOMAIN_NAME; ?> in determining your suitability for work placements. It is also used for staff management and in order to identify any training requirements.</p>
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
            <p> - potential and actual employers and clients of  <?php echo DOMAIN_NAME; ?></p>
            <p> - Referees</p>
            <p> - companies within the  <?php echo DOMAIN_NAME; ?> Group</p>
            <p> - our insurers</p>
            <p> - a professional association or registration body that has a proper interest in the disclosure of your personal information</p>
            <p> - a workers compensation body</p>
            <p> - our contractors and suppliers (eg. IT contractors and database designers)</p>
            <p> - any person with a lawful entitlement to obtain the information</p>
            <div><b>How can you gain access to your personal information that we hold?</b></div>
            <p>Under privacy legislation you have a right to see any personal information about you that we may hold. If you are able to establish that any of the information that we hold about you is not accurate, complete and up to date we will take reasonable steps to correct this.</p>
            <div><b>How is your personal information stored?</b></div>
            <p> <?php echo DOMAIN_NAME; ?> takes all reasonable steps to ensure that information held in paper or electronic form is secure, and that it is protected from misuse, loss, unauthorized access, modification or disclosure. All staff at  <?php echo DOMAIN_NAME; ?> will take reasonable steps to ensure that personal information is only used for recruitment purposes or disclosed to other organisations to the extent necessary for our business purposes. When personal information is no longer required it will be destroyed.</p>
            <div><b>Changes to our Privacy Policy?</b></div>
            <p>If any changes are made to  <?php echo DOMAIN_NAME; ?>’ Privacy Policy, they will be posted on our website so that you are always kept up to date about the information we might use and whether it will be disclosed to anyone.</p>
            <div><b>Inquiries or Feedback?</b></div>
            <p>If you have any questions or concerns about our commitment to your privacy, please don’t hesitate to contact us on 1300 499 449.</p>
        </section>
    </div>
<br>
        <div class="row">
            <section class="col col-sm-6">
                <p>I HAVE READ AND UNDERSTOOD THE ABOVE PRIVACY POLICY.</p>
                <br>
                <label>Candidate Signature</label>
                <div id="signature"></div>
            </section>
            <!--<section class="col col-sm-3">
                <button name="reset" type="button" id="reset" class="btn-info btn-sm">Reset</button>
            </section>-->
        </div>
        <div class="row">
            <section class="col col-sm-6">
                <br>
                <input type="hidden" name="conEmail" id="conEmail" value="<?php echo $_REQUEST['conEmail'];?>">
                <input type="submit" id="registerBtn" class="registerBtn btn-success btn-lg" value="Register"/>
            </section>
        </div>
</form>
</div>
<br>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<script type="text/javascript" src="js/jotScript.js"></script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>
</html>