<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if(empty($_REQUEST['empId'])){
    $error = 'Casual ID not set. Please contact chandler consultant';
    header("Location:error.php?error=$error");
}
if(empty($_REQUEST['empEmail'])){
    $error = 'Casual Email not set. Please contact chandler consultant';
    header("Location:error.php?error=$error");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
    <script type="text/javascript" src="js/validation_messages.js"></script>
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
        .sign-panel{
            margin: 0 auto;
            padding: 10px 50px 10px 50px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 100%;
        }
        .h3box {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
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
        /* ------------- ajax loading styles ---------- */
        .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('img/page-loading.gif')
            50% 50%
            no-repeat;
        }
        .loadDisplay {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('img/page-loading.gif')
            50% 50%
            no-repeat;
        }
        /*ajax-loader.gif*/
        /* When the body has the loading class, turn
           the scrollbar off with overflow:hidden */
        body.loading {
            overflow: hidden;
        }
        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .modal {
            display: block;
        }
        body.ajaxLoader {
            overflow: hidden;
        }
        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.ajaxLoader .loadDisplay {
            display: block;
        }
        /* ------------  end ajax styles -------------*/
/*input[type='file'] {
  color: transparent;
}*/
        #status1 span.status {
            display: none;
            font-weight: bold;
        }
        #status2 span.status {
            display: none;
            font-weight: bold;
        }
        #status3 span.status {
            display: none;
            font-weight: bold;
        }
        #status4 span.status {
            display: none;
            font-weight: bold;
        }
        span.status.complete {
            color: green;
        }
        span.status.incomplete {
            color: red;
        }
        #status1.complete span.status.complete {
            display: inline;
        }
        #status1.incomplete span.status.incomplete {
            display: inline;
        }
        #status2.complete span.status.complete {
            display: inline;
        }
        #status2.incomplete span.status.incomplete {
            display: inline;
        }
        #status3.complete span.status.complete {
            display: inline;
        }
        #status3.incomplete span.status.incomplete {
            display: inline;
        }
        #status4.complete span.status.complete {
            display: inline;
        }
        #status4.incomplete span.status.incomplete {
            display: inline;
        }
        .video-wrapper {
            display:none;
            /*padding-bottom: calc(var(--aspect-ratio, .5625) * 100%);*/
        }
        @media (-webkit-min-device-pixel-ratio: 0) and (min-device-width:1024px)
        {
            .video-wrapper { display:block!important; }
            .video-fallback { display:none!important; }
        }
        @supports (-webkit-overflow-scrolling:touch) and (color:#ffffffff) {
            div[class^=video-wrapper] { display:block!important; }
            div[class^=video-fallback] { display:none!important; }
        }
        #MessageViewBody .video-wrapper { display:block!important; }
        #MessageViewBody .video-fallback { display:none!important; }
    </style>

</head>
<body>
<!--<div id="header" style="padding: 20px 20px 20px 20px" align="center">
    <img src="img/logo.png" width="220" height="50">
    <h1>Candidate Registration Information</h1>
    <div class="error">Please do not copy paste anything to the form inputs when you fill the form</div>
</div>-->
<div class="container">
    <br><br>
    <div class="sign-panel">
    <br><br>
    <div style="text-align: center">
        <img src="img/logo.png" width="220" height="50">
        <br>
        <div class="h3box"><h3><i class="fa fa-pencil"></i>&nbsp;Candidate Registration Information</h3></div>
        <div class="error">Please do not copy paste anything to the form inputs when you fill the form</div>
        <br>
    </div>
<form name="frmJotForm" id="frmJotForm" class="frmJotForm" method="post" enctype="multipart/form-data">
    <fieldset>
    <legend>Personal Information</legend>
    <div class="row">
        <section class="col col-sm-3">
            <label>Title</label>
            <select name="title" id="title" class="form-control">
                <option value="Mr">Mr</option>
                <option value="Mrs">Mrs</option>
                <option value="Miss">Miss</option>
                <option value="Ms">Ms</option>
            </select>
        </section>
        <section class="col col-sm-3">
            <label>First Name</label>
            <input type="text" name="firstName" id="firstName" value="<?php echo base64_decode($_REQUEST['empFirstName']); ?>" class="alphaonly form-control" placeholder="First Name" onpaste="return false;" ondrop="return false;" autocomplete="off" required/>
        </section>
        <section class="col col-sm-3">
            <label>Middle Name</label>
            <input type="text" name="middleName" id="middleName" class="alphaonly form-control" placeholder="Middle Name" onpaste="return false;" ondrop="return false;" autocomplete="off"/>
        </section>
        <section class="col col-sm-3">
            <label>Last Name</label>
            <input type="text" name="lastName" id="lastName" value="<?php echo base64_decode($_REQUEST['empLastName']); ?>" class="alphaonly form-control" placeholder="Last Name" onpaste="return false;" ondrop="return false;" autocomplete="off" required/>
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-3">
            <label>Gender</label>
            <select name="gender" id="gender" class="form-control">
                <option value="Male">Male</option>
                <option value="Female">Female</option>
                <option value="Intersex">Intersex</option>
                <option value="Unknown">Unknown</option>
            </select>
        </section>
        <section class="col col-sm-3">
            <label>Data of birth</label>
            <input type="hidden" name="empId" id="empId" value="<?php echo $_REQUEST['empId']; ?>"/>
            <input type="text" name="dob_selected" id="dob_selected" class="form-control" placeholder="Date of birth" value="<?php echo base64_decode($_REQUEST['empDOB']); ?>" readonly>
            <input type="hidden" name="dob" id="dob" class="form-control" placeholder="Date of birth" value="<?php echo base64_decode($_REQUEST['empDOB']); ?>">
        </section>
        <section class="col col-sm-3"><label for="">Date of birth year before 1971</label><br>
            Note: First select 1971 and reselect from dropdown for previous years if needed.
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-3">
            <label>Full Address</label>
            <textarea class="form-control" name="address" id="address" placeholder="Full Address"><?php echo base64_decode($_REQUEST['emp_address']); ?></textarea>
        </section>
        <section class="col col-sm-3">
            <label>Unit Number</label>
            <input name="unit_no" id="unit_no" placeholder="Unit No" type="text" value="<?php echo base64_decode($_REQUEST['emp_unit_no']); ?>" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Street Number</label>
            <input name="street_number_1" id="street_number_1" type="text" placeholder="Street No" value="<?php echo base64_decode($_REQUEST['emp_street_no']); ?>" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Street Name</label>
            <input name="street_name" id="street_name" type="text" placeholder="Street Name" value="<?php echo base64_decode($_REQUEST['emp_street_name']); ?>" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Suburb</label>
            <input name="suburb" id="suburb" type="text" placeholder="Suburb" value="<?php echo base64_decode($_REQUEST['emp_suburb']); ?>" class="form-control"/>
        </section>
    </div>
    <div class="row">
        <section class="col col-sm-3">
            <label>State</label>
            <input name="state" id="state" type="text" placeholder="State" value="<?php echo base64_decode($_REQUEST['emp_state']); ?>" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Post code</label>
            <input name="postcode" id="postcode" type="text" placeholder="Postcode" value="<?php echo base64_decode($_REQUEST['emp_postcode']); ?>" class="form-control"/>
        </section>
        <section class="col col-sm-3">
            <label>Mobile</label>
            <input name="mobile" id="mobile" type="text" placeholder="Mobile" value="<?php echo base64_decode($_REQUEST['empMobile']); ?>" class="form-control" maxlength="10"/>
        </section>
        <section class="col col-sm-3">
            <label>Email</label>
            <input name="email" id="email" type="email" placeholder="Email" value="<?php echo base64_decode($_REQUEST['empEmail']); ?>" class="form-control" required readonly/>
        </section>
    </div>
    <br>
    <div class="row">
        <section class="col col-sm-12">
            <label for="profileFile"><i class="fa fa-photo"></i>Attach your photo
            <input type="file" name="profileFile" id="profileFile" class="form-control" title=""/></label>
        </section>
    </div>
    <br><br>
    <div class="row">
        <section class="col col-sm-12">
            <label for="jobActive"> Are you currently registered with any jobactive provider? If yes kindly specify the provider name.</label>
            <input type="radio" name="jobActive" id="jobActive" value="Yes" class="jobActive">&nbsp;Yes
            <input type="radio" name="jobActive" id="jobActive" value="No" class="jobActive">&nbsp;No
            <textarea name="jobActiveDesc" id="jobActiveDesc"></textarea>
        </section>
    </div>
    <br><br>
    <div class="row">
        <section class="col col-sm-3">
            <label>Residential Status:</label>
            <div>
                <input type="radio" name="residentStatus" id="Citizen" value="Australian Citizen" class="rsStatus">&nbsp;Australian Citizen
                <br>
                <input type="radio" name="residentStatus" id="PR" value="Australian Permanent Resident" class="rsStatus">&nbsp;Australian Permanent Resident
                <br>
                <input type="radio" name="residentStatus" id="WorkingVisa" value="Working Visa" class="rsStatus">&nbsp;Working Visa
                <br>
                <input type="radio" name="residentStatus" id="TemporaryResident" value="Temporary Resident" class="rsStatus">&nbsp;Temporary Resident Visa
                <br>
                <input type="radio" name="residentStatus" id="Student" value="Student Visa" class="rsStatus">&nbsp;Student Visa
            </div>
        </section>
        <section class="col col-sm-3" id="vsExp">
            <label>Visa Expiry Date:</label>
            <input type="hidden" name="visaExpiry" id="visaExpiry" class="form-control">
            <input type="text" name="visaExpiry" id="visaExpiry" class="form-control" readonly>
        </section>
        <section class="col col-sm-3">
            <label for="passportFile"><i class="fa fa-plane"></i> Attach Passport image:
                <input type="file" name="passportFile" id="passportFile" class="form-control" title=""/></label>
            <label for="birthFile"><i class="fa fa-certificate"></i> Attach Birth certificate
                <input type="file" name="birthFile" id="birthFile" class="form-control" title=""/></label>
            <label><i class="fa fa-certificate"></i> Attach Australian Citizenship certificate</label>
                <input type="file" name="citizenFile" id="citizenFile" class="form-control" title=""/>
            <label><i class="fa fa-car"></i> Attach Driving Licence</label>
                <input type="file" name="drivingFile" id="drivingFile" class="form-control" title=""/>
            <label><i class="fa fa-medkit"></i> Attach Australian Medicare Card</label>
                <input type="file" name="medicareFile" id="medicareFile" class="form-control" title=""/>
            <label><i class="fa fa-file-photo-o"></i> Attach Australian Student Card</label>
                <input type="file" name="studentFile" id="studentFile" class="form-control" title=""/>
        </section>
        <section class="col col-sm-3">
            <label><i class="fa fa-indent"></i> Attach White card</label>
            <input type="file" name="whiteFile" id="whiteFile" class="form-control" title=""/>
            <label><i class="fa fa-indent"></i> Attach Forklift licence</label>
            <input type="file" name="forkliftFile" id="forkliftFile" class="form-control" title=""/>
        </section>
    </div>
    <br>
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
                <input name="emcMobile" id="emcMobile" type="text" placeholder="Emergency Contact Mobile" value="" class="form-control"/>
            </section>
            <section class="col col-sm-3">
                <label>Home Phone Number</label>
                <input name="emcHomePhone" id="emcHomePhone" type="text" placeholder="Emergency Contact Home Phone" value="" class="form-control"/>
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
    <br>
    <fieldset>
        <legend>Tax File number Declaration Information</legend>
        <div class="row">
            <section class="col col-sm-3">
                <label>Tax File Number</label>
                <input type="text" name="tfn" id="tfn" placeholder="TFN" class="form-control" maxlength="9"/>
            </section>
        </div>
        <br>
        <div class="row">
            <section class="col col-sm-6">
                <label for="paidBasis"><strong>On what basis are you paid?</strong></label><br>
                <input type="radio" name="paidBasis" value="Full-time">Full-time employment<br>
                <input type="radio" name="paidBasis" value="Part-time">Part-time employment<br>
                <input type="radio" name="paidBasis" value="Labour-hire">Labour hire<br>
                <input type="radio" name="paidBasis" value="Superannuation">Superannuation or annuity income stream<br>
                <input type="radio" name="paidBasis" value="Casual">Casual employment
            </section>
        </div>
        <br>
        <div class="row">
            <section class="col col-sm-6">
            <label for=""><strong>Are you:</strong></label><br>
                <input type="radio" name="taxResident" value="Australian resident">An Australian resident for tax purposes<br>
                <input type="radio" name="taxResident" value="Foreign resident">A foreign resident for tax purposes<br>
                <input type="radio" name="taxResident" value="Working holiday resident">A working holiday maker<br>
            </section>
        </div>
        <br>
        <div class="row">
            <section class="col col-sm-6">
                <label for=""><strong>Do you want to claim the tax-free threshold from this payer?</strong></label>
            <p>Only claim the tax‑free threshold from one payer at a time, unless your total income from
                all sources for the financial year will be less than the tax‑free threshold.</p>
            <input type="radio" name="taxClaim" value="Yes">Yes
            <input type="radio" name="taxClaim" value="No">No
            <p>Answer no here if you are a foreign resident or working holiday
                maker, except if you are a foreign resident in receipt of an
                Australian Government pension or allowance</p><br>
            </section>
        </div>
        <div class="row">
            <section class="col col-sm-6">
            <label for="">Do you have a Higher Education Loan Program (HELP), VET Student
                Loan (VSL), Financial Supplement (FS), Student Start-up Loan (SSL) or
                Trade Support Loan (TSL) debt?</label>
            <input type="radio" name="taxHelp" value="Yes">Yes
            <input type="radio" name="taxHelp" value="No">No
            <p>Your payer will withhold additional amounts to cover any compulsory
                Yes repayment that may be raised on your notice of assessment.</p>
            </section>
        </div>
    </fieldset>
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
    <br><br>
    <div align="center"><h3>Police check Information</h3></div>
    <hr>
        <div class="row">
            <section class="col col-sm-4">Do you have an Australian police clearance
                <input type="radio" name="policeCheck" value="Yes" class="policeCheck">Yes
                <input type="radio" name="policeCheck" value="No" class="policeCheck">No
            </section>
            <section class="col col-sm-3" id="policeFileDisplay">
                <label><i class="fa fa-file-photo-o"></i> Attach Australian Police Clearance certificate</label>
                <input type="file" name="policeFile" id="policeFile"/>
            </section>
        </div>
        <br>
        <div class="row" id="crimeHistory">
                <section class="col col-sm-6">Do you have any prior or pending criminal history?
                    <input type="radio" name="crimeCheck" value="Yes" class="crimeCheck">Yes
                    <input type="radio" name="crimeCheck" value="No" class="crimeCheck">No
                </section>
        </div>
        <br>
        <div class="statdec">
            <fieldset>
                <legend>Statutory Declaration</legend>
                <p>I,&nbsp; <span class="statName"></span> of <span class="statAddress"></span>
                    <input type="text" name="statOccupation" id="statOccupation" class="form-control-sm" placeholder="Fill your occupation here" size="50">, do solemnly and sincerely declare that:-</p>
                <p>
                    <strong>
                        <input type="checkbox" name="neverConvicted" value="X"> I have never been convicted of a criminal offense in Australia
                    <br>
                        <input type="checkbox" name="neverImprisonment" value="X"> I have never been convicted of a criminal offence and/or sentenced to imprisonment in any country other than Australia
                    </strong>
                </p>
                <p><strong>
                    I acknowledge that this declaration is true and correct, and I make it with the understanding and
                    belief that a person who makes a false declaration is liable to the penalties of perjury.
                    </strong>
                </p>
                <p>Declared at Level 9, 10 Queen St, Melbourne</p>
                <p>this  <?php echo date('d'); ?> day of <?php echo date('M').' '.date('Y'); ?></p>
            </fieldset>
        </div>
        <div class="authFrm"><fieldset>
                <legend>Police Check Authority Form Declaration</legend>
                <table id="crimeTbl" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Nature of Offense</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td width="25%"><input type="text" name="crimeDate1" id="crimeDate1" class="form-control" size="25"></td>
                        <td><input type="text" name="crime1" id="crime1" class="form-control"></td>
                      </tr>
                      <tr>
                          <td width="25%"><input type="text" name="crimeDate2" id="crimeDate2" class="form-control" size="25"></td>
                          <td><input type="text" name="crime2" id="crime2" class="form-control"></td>
                      </tr>
                    </tbody>
                  </table>
                <br>
                <!--<div class="row">
                    <section class="col col-sm-12">
                        <input type="radio" name="optionChk" class="optionChk" value="option1"><strong>Option 1:</strong> I hold a previously completed National Police Check (within 3 years)
                    </section>
                </div>-->
                <div class="row">
                    <section class="col col-sm-12">
                        <input type="radio" name="optionChk" class="optionChk" value="option2"> I have completed the enclosed Application Form and provided sufficient ID so that Chandler Personnel can conduct a National Police Check on my behalf, *<i>once I commence employment</i>*. I understand that I am responsible for the payment of this Police Check, and that an amount of $49.00 will be deducted from my first pay.
                    </section>
                </div>
                <br>
                <div id="fit2wrk">
                    <section class="col col-sm-12">
                        <h3>Fit2Work</h3>
                        <hr>
                        <strong>Place of birth(Required)</strong>
                        <div class="row">
                            <section class="col col-sm-4"><label>Suburb</label>
                                <input type="text" name="pb_suburb" id="pb_suburb" class="form-control">
                            </section>
                            <section class="col col-sm-4"><label>State</label>
                                <input type="text" name="pb_state" id="pb_state" class="form-control">
                            </section>
                            <section class="col col-sm-4"><label>Country</label>
                                <input type="text" name="pb_country" id="pb_country" class="form-control">
                            </section>
                        </div>
                        <strong>Additional Details</strong>
                        <br>
                        <strong>Previous names(if applicable)</strong>
                        <div class="row">
                            <section class="col col-sm-5"><label>First Name:</label>
                                <input type="text" name="fw_first_name" id="fw_first_name" class="form-control">
                            </section>
                            <section class="col col-sm-3"><label>Middle Name</label>
                                <input type="text" name="fw_middle_name" id="fw_middle_name" class="form-control">
                            </section>
                            <section class="col col-sm-3"><label>Last Name</label>
                                <input type="text" name="fw_last_name" id="fw_last_name" class="form-control">
                            </section>
                            <section class="col col-sm-3"><label>Type:</label>
                                <input type="radio" name="fw_type" value="Previous">Previous
                                <input type="radio" name="fw_type" value="Maiden">Maiden
                            </section>
                        </div>
                        <strong>5 Year Previous Address</strong>
                        <div class="row">
                            <!--<section class="col col-sm-3">
                                <label>Full Address</label>
                                <textarea class="form-control" name="fw_address1" id="fw_address1" placeholder="Full Address"></textarea>
                            </section>-->
                            <section class="col col-sm-3">
                                <label>Unit Number</label>
                                <input name="fw_unit_no1" id="fw_unit_no1" placeholder="Unit No" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Number</label>
                                <input name="fw_street_number1" id="fw_street_number1" placeholder="Street No" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Name</label>
                                <input name="fw_street_name1" id="fw_street_name1" placeholder="Street Name" value="" class="form-control"/>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label>Suburb</label>
                                <input name="fw_suburb1" id="fw_suburb1" placeholder="Suburb" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>State</label>
                                <input name="fw_state1" id="fw_state1" placeholder="State" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Post code</label>
                                <input name="fw_postcode1" id="fw_postcode1" placeholder="Postcode" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Country</label>
                                <input name="fw_country1" id="fw_country1" placeholder="Country" value="" class="form-control"/>
                            </section>
                        </div>
                        <strong>5 Year Previous Address</strong>
                        <div class="row">
                           <!-- <section class="col col-sm-3">
                                <label>Full Address</label>
                                <textarea class="form-control" name="fw_address2" id="fw_address2" placeholder="Full Address"></textarea>
                            </section>-->
                            <section class="col col-sm-3">
                                <label>Unit Number</label>
                                <input name="fw_unit_no2" id="fw_unit_no2" placeholder="Unit No" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Number</label>
                                <input name="fw_street_number2" id="fw_street_number2" placeholder="Street No" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Street Name</label>
                                <input name="fw_street_name2" id="fw_street_name2" placeholder="Street Name" value="" class="form-control"/>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label>Suburb</label>
                                <input name="fw_suburb2" id="fw_suburb2" placeholder="Suburb" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>State</label>
                                <input name="fw_state2" id="fw_state2" placeholder="State" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Post code</label>
                                <input name="fw_postcode2" id="fw_postcode2" placeholder="Postcode" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label>Country</label>
                                <input name="fw_country2" id="fw_country2" placeholder="Country" value="" class="form-control"/>
                            </section>
                        </div>
                        <strong>Documents</strong>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label for="">Aust. Drivers Licence No.</label>
                                <input name="fw_licence" id="fw_licence" placeholder="Licence" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label for="">State/Territory</label>
                                <select name="fw_licence_state" id="fw_licence_state" class="form-control">
                                    <option value="" selected disabled></option>
                                    <option value="NSW">New South Wales</option>
                                    <option value="VIC">Victoria</option>
                                    <option value="QLD">Queensland</option>
                                    <option value="WA">Western Australia</option>
                                    <option value="SA">South Australia</option>
                                    <option value="TAS">Tasmania</option>
                                    <option value="ACT">Australian Capital Territory</option>
                                    <option value="NT">Northern Territory</option>
                                </select>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3">
                                <label for="">Passport No.</label>
                                <input name="fw_passport_no" id="fw_passport_no" placeholder="Passport No" value="" class="form-control"/>
                            </section>
                            <section class="col col-sm-3">
                                <label for="">Passport Country</label>
                                <input name="fw_passport_country" id="fw_passport_country" placeholder="Passport country" value="" class="form-control"/>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-sm-3"><label>Type:</label><br>
                                <input type="radio" name="fw_passport_type" value="Private">Private<br>
                                <input type="radio" name="fw_passport_type" value="Government">Government<br>
                                <input type="radio" name="fw_passport_type" value="UN Refugee">UN Refugee
                            </section>
                        </div>
                    </section>
                </div>
            </fieldset>
        </div>
    <br>
    <div>
        <fieldset>
            <legend>Workplace Health & Safety Training </legend>
            <h3>Video Content</h3>
            <div class="row">
                <section class="col col-sm-6">
                    <video id="video1" width="320" height="176" controls="controls" poster="" src="video/1_Safety_at_work.mp4">
                        <!-- fallback  width="320" height="176" -->
                        <a href="video/1_Safety_at_work.mp4" target="_blank">Download</a>
                    </video>
                    <br>
                    <a href="video/1_Safety_at_work.mp4" target="_blank">Download & View</a>
                    <br>
                    <div id="status1" class="incomplete">
                        <!--                                        <span>Play status: </span>-->
                        <!--                                        <span class="status complete">COMPLETE</span>-->
                        <!--                                        <span class="status incomplete">INCOMPLETE</span>-->
                        <input type="hidden" name="video1_status" id="video1_status"/>
                        <br />
                    </div>
                    <!--<div>
                        <span id="played1">0</span> seconds out of
                        <span id="duration1"></span> seconds.
                    </div>-->
                    <br>
                    <input type="checkbox" name="video_check1" id="video_check1" class="video_check1" value="X" required> &nbsp; I confirm that I have watched and understood the work health and safety module 1
                </section>
                <section class="col col-sm-6">
                    <video id="video2" width="320" height="176" controls="controls" poster="" src="video/2_Diversity_at_work.mp4">
                        <!-- fallback -->
                        <a href="video/2_Diversity_at_work.mp4" target="_blank">Download</a>
                    </video>
                    <br>
                    <a href="video/2_Diversity_at_work.mp4" target="_blank">Download & View</a>
                    <br>
                    <div id="status2" class="incomplete">
                        <!--                                        <span>Play status: </span>-->
                        <!--                                        <span class="status complete">COMPLETE</span>-->
                        <!--                                        <span class="status incomplete">INCOMPLETE</span>-->
                        <input type="hidden" name="video2_status" id="video2_status"/>
                        <br />
                    </div>
                    <!--<div>
                        <span id="played2">0</span> seconds out of
                        <span id="duration2"></span> seconds.
                    </div>-->
                    <br>
                    <input type="checkbox" name="video_check2" id="video_check2" class="video_check2" value="X" required> &nbsp; I confirm that I have watched and understood the work health and safety module 2
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-6">
                    <video id="video3" width="320" height="176" controls="controls" poster="" src="video/3_Manual_handling_at_work.mp4">
                        <!-- fallback -->
                        <a href="video/3_Manual_handling_at_work.mp4" target="_blank">Download</a>
                    </video>
                    <br>
                    <a href="video/3_Manual_handling_at_work.mp4" target="_blank">Download & View</a>
                    <br>
                    <div id="status3" class="incomplete">
                        <!--                                          <span>Play status: </span>-->
                        <!--                                          <span class="status complete">COMPLETE</span>-->
                        <!--                                          <span class="status incomplete">INCOMPLETE</span>-->
                        <input type="hidden" name="video3_status" id="video3_status"/>
                        <br />
                    </div>
                    <!--<div>
                        <span id="played3">0</span> seconds out of
                        <span id="duration3"></span> seconds.
                    </div>-->
                    <br>
                    <input type="checkbox" name="video_check3" id="video_check3" class="video_check3" value="X" required> &nbsp; I confirm that I have watched and understood the work health and safety module 3
                </section>
                <section class="col col-sm-6">
                    <video id="video4" width="320" height="176" controls="controls" poster="" src="video/4_Emergencies_at_work.mp4">
                        <!-- fallback -->
                        <a href="video/4_Emergencies_at_work.mp4" target="_blank">Download</a>
                    </video>
                    <br>
                    <a href="video/4_Emergencies_at_work.mp4" target="_blank">Download & View</a>
                    <br>
                    <div id="status4" class="incomplete">
                        <!--                                          <span>Play status: </span>-->
                        <!--                                          <span class="status complete">COMPLETE</span>-->
                        <!--                                          <span class="status incomplete">INCOMPLETE</span>-->
                        <input type="hidden" name="video4_status" id="video4_status"/>
                        <br />
                    </div>
                    <!--<div>
                        <span id="played4">0</span> seconds out of
                        <span id="duration4"></span> seconds.
                    </div>-->
                    <br>
                    <input type="checkbox" name="video_check4" id="video_check4" class="video_check4" value="X" required> &nbsp; I confirm that I have watched and understood the work health and safety module 4
                </section>
            </div>
            <!--<div class="row">
                <section class="col col-sm-4">
                    <strong><a href="http://moodle.chandlertraining.com.au/" target="_blank" class="btn btn-primary"><i class="glyphicon glyphicon-link"></i>&nbsp;Click here to take the test</a></strong>
                    <br>
                    <br>
                    <a href="docform/Instructions for accessing Chandler training Moodl - Blue Collar.pdf" target="_blank" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-link"></i>&nbsp;Click here for instructions</a>
                </section>
                <section class="col col-sm-4">
                    <label><i class="fa fa-certificate"></i> Attach WH&S Training Certificate</label>
                    <input type="file" name="whsFile" id="whsFile" class="form-control"/>
                </section>
            </div>-->
        </fieldset>
    </div>
    <br>
    <div>
        <fieldset>
            <legend>COVID19 Vaccinations</legend>
            <div class="row">
                <section class="col col-sm-4">
                    <label><i class="fa fa-certificate"></i> Attach COVID19 Vaccination 1</label>
                    <input type="file" name="covid19File1" id="covid19File1" class="form-control"/>
                </section>
                <section class="col col-sm-4">
                    <label><i class="fa fa-certificate"></i> Attach COVID19 Vaccination 2</label>
                    <input type="file" name="covid19File2" id="covid19File2" class="form-control"/>
                </section>
                <section class="col col-sm-4">
                    <label><i class="fa fa-certificate"></i> Attach COVID19 Vaccination 3</label>
                    <input type="file" name="covid19File3" id="covid19File3" class="form-control"/>
                </section>
            </div>
        </fieldset>
    </div>
    <br>
        <div align="center"><h3>Health Questionnaire</h3></div>
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
                    <br>
                    <textarea name="surgeryInformationDesc" id="surgeryInformationDesc"></textarea>
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
            <!--<div class="row">
                <section class="col col-sm-3">
                    <label>Was a Workcover claim lodged? (Question not applicable to QLD. Applicants)</label>
                    <br>
                    <input type="radio" name="workCoverClaim" value="Yes">Yes
                    <input type="radio" name="workCoverClaim" value="No">No
                </section>
            </div>-->
        </fieldset>
        <br>
        <fieldset>
            <legend>Section C : Physical Abilities</legend>
            <label class="">Please indicate whether you have, or could have, difficulties performing any of the following activities.</label>
            <br><br>
            <label for="">If you <b>have, or could have difficulties performing any of the following activities, </b>&nbsp;</label>answer <span style="color: red"><b>YES</b></span>
            <br>
                If not answer <b>NO</b>
            <br>
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
                <!--<section class="col col-sm-3">
                    <label>Working at heights</label>
                    <br>
                    <input type="radio" name="workHeights" value="Yes">Yes
                    <input type="radio" name="workHeights" value="No">No
                </section>-->
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
            <p>If you have any questions or concerns about our commitment to your privacy, please don’t hesitate to contact us on 1300 1300 1300.</p>
        </section>
    </div>
<br>
        <div class="row">
            <section class="col col-sm-6">
                <p>I HAVE READ AND UNDERSTOOD THE ABOVE PRIVACY POLICY.</p>
                <br>
                <label>Candidate Signature</label>
                <div id="signature"></div>
                <br>
            </section>
        </div>
        <div class="row">
            <section class="col col-sm-6">
                <br>
                <input type="hidden" name="conEmail" id="conEmail" value="<?php echo base64_decode($_REQUEST['conEmail']);?>">
                <input type="submit" id="regBtn" class="regBtn btn-success btn-lg" value="Register"/>
            </section>
        </div>
    <br>
</form>
    </div>
</div>
<br>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<script type="text/javascript" src="js/jotScript.js"></script>
<script type="text/javascript">
    var video1 = document.getElementById("video1");
    var video2 = document.getElementById("video2");
    var video3 = document.getElementById("video3");
    var video4 = document.getElementById("video4");

    var timeStarted1 = -1;
    var timePlayed1 = 0;
    var duration1 = 0;

    var timeStarted2 = -1;
    var timePlayed2 = 0;
    var duration2 = 0;

    var timeStarted3 = -1;
    var timePlayed3 = 0;
    var duration3 = 0;

    var timeStarted4 = -1;
    var timePlayed4 = 0;
    var duration4 = 0;

    // If video metadata is laoded get duration
    if(video1.readyState > 0) {
        //getDuration.call(video1);
        //If metadata not loaded, use event to get it
    }
    else
    {
        //video1.addEventListener('loadedmetadata', getDuration);
    }
    if(video2.readyState > 0) {
        //getDuration2.call(video2);
        //If metadata not loaded, use event to get it
    }
    else
    {
        //video2.addEventListener('loadedmetadata', getDuration2);
    }
    if(video3.readyState > 0) {
        //getDuration3.call(video3);
        //If metadata not loaded, use event to get it
    }
    else
    {
        //video3.addEventListener('loadedmetadata', getDuration3);
    }
    if(video4.readyState > 0) {
        //getDuration4.call(video4);
        //If metadata not loaded, use event to get it
    }
    else
    {
        //video4.addEventListener('loadedmetadata', getDuration4);
    }
    // remember time user started the video
    function videoStartedPlaying1() {
        timeStarted1 = new Date().getTime()/1000;
    }
    function videoStoppedPlaying1(event) {
        // Start time less then zero means stop event was fired vidout start event
        if(timeStarted1>0) {
            var playedFor1 = new Date().getTime()/1000 - timeStarted1;
            timeStarted1 = -1;
            // add the new number of seconds played
            timePlayed1+=playedFor1;
        }
        var seconds = Math.round(timePlayed1);
        document.getElementById("played1").innerHTML = seconds+"";
        document.getElementById('video1_status').value = seconds+" seconds";
        // Count as complete only if end of video was reached
        if(timePlayed1>=duration1 && event.type=="ended") {
            document.getElementById("status1").className="complete";
            document.getElementById('video1_status').value= 'complete';
        }
    }
    function getDuration() {
        duration1 = video1.duration;
        document.getElementById("duration1").appendChild(new Text(Math.round(duration1)+""));
        console.log("Duration: ", duration1);
    }
    // remember time user started the video
    function videoStartedPlaying2() {
        timeStarted2 = new Date().getTime()/1000;
    }
    function videoStoppedPlaying2(event) {
        // Start time less then zero means stop event was fired vidout start event
        if(timeStarted2>0) {
            var playedFor2 = new Date().getTime()/1000 - timeStarted2;
            timeStarted2 = -1;
            // add the new number of seconds played
            timePlayed2+=playedFor2;
        }
        var seconds = Math.round(timePlayed2);
        document.getElementById("played2").innerHTML = seconds+"";
        document.getElementById('video2_status').value = seconds+" seconds";
        // Count as complete only if end of video was reached
        if(timePlayed2>=duration2 && event.type=="ended") {
            document.getElementById("status2").className="complete";
            document.getElementById('video2_status').value= 'complete';
        }
    }

    function getDuration2() {
        duration2 = video2.duration;
        document.getElementById("duration2").appendChild(new Text(Math.round(duration2)+""));
        console.log("Duration: ", duration2);
    }

    // remember time user started the video
    function videoStartedPlaying3() {
        timeStarted3 = new Date().getTime()/1000;
    }
    function videoStoppedPlaying3(event) {
        // Start time less then zero means stop event was fired vidout start event
        if(timeStarted3>0) {
            var playedFor3 = new Date().getTime()/1000 - timeStarted3;
            timeStarted3 = -1;
            // add the new number of seconds played
            timePlayed3+=playedFor3;
        }
        var seconds = Math.round(timePlayed3);
        document.getElementById("played3").innerHTML = seconds+"";
        document.getElementById('video3_status').value = seconds+" seconds";
        // Count as complete only if end of video was reached
        if(timePlayed3>=duration3 && event.type=="ended") {
            document.getElementById("status3").className="complete";
            document.getElementById('video3_status').value= 'complete';
        }
    }

    function getDuration3() {
        duration3 = video3.duration;
        document.getElementById("duration3").appendChild(new Text(Math.round(duration3)+""));
        console.log("Duration: ", duration3);
    }

    // remember time user started the video
    function videoStartedPlaying4() {
        timeStarted4 = new Date().getTime()/1000;
    }
    function videoStoppedPlaying4(event) {
        // Start time less then zero means stop event was fired vidout start event
        if(timeStarted4>0) {
            var playedFor4 = new Date().getTime()/1000 - timeStarted4;
            timeStarted4 = -1;
            // add the new number of seconds played
            timePlayed4+=playedFor4;
        }
        var seconds = Math.round(timePlayed4);
        document.getElementById("played4").innerHTML = seconds+"";
        document.getElementById('video4_status').value = seconds+" seconds";
        // Count as complete only if end of video was reached
        if(timePlayed4>=duration4 && event.type=="ended") {
            document.getElementById("status4").className="complete";
            document.getElementById('video4_status').value= 'complete';
        }
    }

    function getDuration4() {
        duration4 = video4.duration;
        document.getElementById("duration4").appendChild(new Text(Math.round(duration4)+""));
        console.log("Duration: ", duration4);
    }

    video1.addEventListener("play", videoStartedPlaying1);
    video1.addEventListener("playing", videoStartedPlaying1);
    video1.addEventListener("ended", videoStoppedPlaying1);
    video1.addEventListener("pause", videoStoppedPlaying1);

    video2.addEventListener("play", videoStartedPlaying2);
    video2.addEventListener("playing", videoStartedPlaying2);
    video2.addEventListener("ended", videoStoppedPlaying2);
    video2.addEventListener("pause", videoStoppedPlaying2);

    video3.addEventListener("play", videoStartedPlaying3);
    video3.addEventListener("playing", videoStartedPlaying3);
    video3.addEventListener("ended", videoStoppedPlaying3);
    video3.addEventListener("pause", videoStoppedPlaying3);

    video4.addEventListener("play", videoStartedPlaying4);
    video4.addEventListener("playing", videoStartedPlaying4);
    video4.addEventListener("ended", videoStoppedPlaying4);
    video4.addEventListener("pause", videoStoppedPlaying4);
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>