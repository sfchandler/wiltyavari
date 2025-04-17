<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'CONSULTANT') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consId = getConsultantId($mysqli, $_SESSION['userSession']);
$licenceTypes = getOtherLicenceTypes($mysqli);
$gearTypes = getSafetyGearTypes($mysqli);
$expOpTypes = getExpOperating($mysqli);
$shiftTypes = getShiftAvailable($mysqli);
$consultants = getConsultants($mysqli);
$consultantName = getConsultantName($mysqli,$_REQUEST['consId']);
if($_REQUEST['action'] == 'PHONE_SCREENING') {
    $accountName = $_SESSION['accountName'];
    $fullName = $_REQUEST['first_name'].' '.$_REQUEST['last_name'];
    $firstName = $_REQUEST['first_name'];
    $lastName = $_REQUEST['last_name'];
    $emailAddress = $_REQUEST['email'];
    $phoneNumber = $_REQUEST['phone'];
    $gender = $_REQUEST['gender'];
    $suburb = $_REQUEST['suburb'];
}else{
    $accountName = $_SESSION['accountName'];
    if ($_REQUEST['jb'] == 'jcall') {
        $accountName = 'jobboard';
    }
    $messageid = htmlentities($_REQUEST['messageid']);
    $candidateMailFrom = retrieveCandidateName($mysqli, $messageid, $accountName);
    $str = explode('via', $candidateMailFrom);
    $fullName = explode(' ', $str[0]);
    $firstName = $fullName[0];
    $lastName = $fullName[1] . ' ' . $fullName[2];
    $msgBody = retrieveCandidateMsgBody($mysqli, $messageid, $accountName);
    $emailAddress = get_string_between($msgBody, 'mailto:', '&quot;');
    $suburb = '';
    /*$phoneNumber = get_string_between($msgBody,'Phone
                                                            &lt;/p&gt;
                                                            &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;
                                                                ','
                                                            &lt;/p&gt;');*/
    $phoneNumber = trim(get_string_between($msgBody, 'Phone\r\n                                                        &lt;/p&gt;\r\n                                                        &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;\r\n                                                            ', '\r\n'));
    $str1 = 'Phone\r\n                                                        &lt;/p&gt;\r\n                                                        &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;\r\n                                                            ';

    $str2 = '\r\n';

    $msgPart = strrpos($msgBody, 'Name:');
    if ($msgPart !== false) {
        $nameParts = substr($msgBody, $msgPart + 5);
        $nmPart = explode('<br>', $nameParts);
        $fullNamePart = trim($nmPart[0]);
        $fullNameExt = explode(' ', $fullNamePart);
        $firstName = $fullNameExt[0];
        $lastName = $fullNameExt[1];
    }

    if (empty($emailAddress)) {
        $msgPart1 = strrpos($msgBody, 'Email:');
        if ($msgPart1 !== false) {
            $emailParts = substr($msgBody, $msgPart1 + 6);
            $emPart = explode('<br>', $emailParts);
            $emailAddress = trim($emPart[0]);
        }
    }
    if (empty($phoneNumber)) {
        $msgPart2 = strrpos($msgBody, 'Phone Number:');
        if ($msgPart2 !== false) {
            $phoneParts = substr($msgBody, $msgPart2 + 13);
            $phPart = explode('<br>', $phoneParts);
            $phoneNumber = trim($phPart[0]);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
</head>
<body>
<!-- HEADER -->
<header id="header">
    <?php include "template/top_menu.php";
    if ($_REQUEST['error_msg'] <> '') {
        echo base64_decode($_REQUEST['error_msg']);
    } ?>

</header>
<!-- END HEADER -->

<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">

    <!-- User info -->
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <!-- end user info -->
    <?php include "template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i> 
			</span>

</aside>
<!-- END NAVIGATION -->

<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">
				<span class="ribbon-button-alignment"> 
				</span>
    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content">
        <div id="candidate-list" class="inbox-body no-content-padding">
            <div class="table-wrap custom-scroll animated fast fadeInRight">
                <!-- ajax will fill this area -->
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body">
                        <!-- widget grid -->
                        <section id="widget-grid" class="">
                            <!-- START ROW -->
                            <div class="row">
                                <!-- NEW COL START -->
                                <article class="col-sm-12 col-md-12 col-lg-12">

                                    <!-- Widget ID (each widget will need unique ID)-->
                                    <div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false"
                                         data-widget-editbutton="false">
                                        <!-- widget div-->
                                        <div>
                                            <!-- widget content -->
                                            <div class="widget-body no-padding">
                                                <div>

                                                    <!-- Success states for elements -->
                                                    <form id="screenFrm" name="screenFrm" class="smart-form">
                                                        <header>Telephone Screening Form</header>
                                                        <div class="error" style="text-align: center"></div>
                                                        <fieldset>
                                                            <div class="row">
                                                                <!--<section class="col col-3">
                                                                    <label for=""><span style="color:red">*</span>Select Client</label>
                                                                    <select name="clientId" id="clientId" class="form-control"></select>
                                                                </section>
                                                                <section class="col col-3">
                                                                    <label for=""><span style="color:red">*</span>Select State</label>
                                                                    <select name="stateId" id="stateId" class="form-control"></select>
                                                                </section>-->
                                                                <section class="col col-3">
                                                                    <label for=""><span style="color:red">*</span>Select Department</label>
                                                                    <select name="departmentId" id="departmentId" class="form-control"></select>
                                                                </section>
                                                                <section class="col col-3">
                                                                    <label for=""><span style="color:red">*</span>Select Position</label>
                                                                    <select name="expPosition" id="expPosition" class="form-control"></select>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-6">
                                                                    <label for="jobDescription" class="label"><span
                                                                                style="color:red">*</span>&nbsp;Job Description</label>
                                                                    <label class="input"><i
                                                                                class="icon-append fa fa-info"></i>
                                                                        <textarea name="jobDescription" id="jobDescription" value=" " cols="3" class="form-control"></textarea>
                                                                        <input type="hidden" name="action" id="action" value="<?php echo $_REQUEST['action']; ?>"/>
                                                                        <b class="tooltip tooltip-bottom-right">Please enter job description</b>
                                                                    </label>
                                                                </section>
                                                            </div>
                                                           <div class="row">
                                                                <section class="col col-6">
                                                                    <label for="firstName" class="label"><span
                                                                                style="color:red">*</span>&nbsp;First
                                                                        Name</label>
                                                                    <label class="input"><i
                                                                                class="icon-append fa fa-user"></i>
                                                                        <input type="text" name="firstName"
                                                                               id="firstName"
                                                                               value="<?php echo $firstName; ?>">
                                                                        <b class="tooltip tooltip-bottom-right">Please
                                                                            enter Your First Name</b>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-6">
                                                                    <label for="lastName" class="label"><span
                                                                                style="color:red">*</span>&nbsp;Last
                                                                        Name</label>
                                                                    <label class="input"><i
                                                                                class="icon-append fa fa-user"></i>
                                                                        <input type="text" name="lastName" id="lastName"
                                                                               value="<?php echo $lastName; ?>">
                                                                        <b class="tooltip tooltip-bottom-right">Please
                                                                            enter Your Last Name</b>
                                                                    </label>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-6">
                                                                    <label class="label"><span
                                                                                style="color:red">*</span>Email</label>
                                                                    <label class="input"><i
                                                                                class="icon-append fa fa-envelope"></i>
                                                                        <input type="email" name="candidateEmail"
                                                                               id="candidateEmail"
                                                                               value="<?php echo $emailAddress; ?>" readonly><b
                                                                                class="tooltip tooltip-bottom-right">Please
                                                                            enter your email</b>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-6">
                                                                    <label class="label"><span
                                                                                style="color:red">*</span>&nbsp;Gender</label>
                                                                    <label class="select">
                                                                        <select id="candidateSex" name="candidateSex">
                                                                            <option value="">Select Gender...</option>
                                                                            <option value="Male" <?php if($gender == 'Male'){?> selected <?php } ?>>Male</option>
                                                                            <option value="Female" <?php if($gender == 'Female'){?> selected <?php } ?>>Female</option>
                                                                            <option value="Noanswer" <?php if($gender == 'Noanswer'){?> selected <?php } ?>>Prefer not to answer</option>
                                                                        </select> <i></i> </label>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-3">
                                                                    <label for="screenDate" class="label">Date</label>
                                                                    <label class="input">
                                                                        <input type="text" name="screenDate"
                                                                               id="screenDate" class="screenDate" value="<?php echo date('Y-m-d H:i:s'); ?>" readonly>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-3">
                                                                    <label for="suburb" class="label">Suburb</label>
                                                                    <label class="input">
                                                                        <input type="text" name="candidateSuburb"
                                                                               id="candidateSuburb" value="<?php echo $suburb; ?>">
                                                                    </label>
                                                                </section>
                                                                <section class="col col-3">
                                                                    <label for="phone" class="label">Phone</label>
                                                                    <label class="input"><i
                                                                                class="icon-append fa fa-phone"></i>
                                                                        <input type="tel" name="candidatePhone"
                                                                               id="candidatePhone"
                                                                               data-mask="9999999999"
                                                                               data-mask-placeholder="X"
                                                                               placeholder="Home Phone"><b
                                                                                class="tooltip tooltip-bottom-right">Please
                                                                            enter your Home Phone Number</b>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-3">
                                                                    <label for="mobile" class="label"><span
                                                                                style="color:red">*</span>&nbsp;Mobile</label>
                                                                    <label class="input"> <i
                                                                                class="icon-append fa fa-phone"></i>
                                                                        <input type="tel" name="candidateMobile"
                                                                               id="candidateMobile"
                                                                               value="<?php echo $phoneNumber; ?>"
                                                                               data-mask="9999999999"
                                                                               data-mask-placeholder="X"
                                                                               placeholder="Mobile Phone">
                                                                        <b class="tooltip tooltip-bottom-right">Please
                                                                            enter your Mobile Phone Number</b>
                                                                    </label>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-4">
                                                                    <label for="currentWrk" class="label"><span
                                                                                style="color:red">*</span>&nbsp;Are you
                                                                        currently working or are you just looking for
                                                                        work? Tell me a little bit about your work
                                                                        history...</label>
                                                                    <label class="textarea textarea-resizable">
                                                                        <textarea rows="3" class="custom-scroll"
                                                                                  name="currentWrk"
                                                                                  id="currentWrk"></textarea>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="howfar" class="label"><span
                                                                                style="color:red">*</span>&nbsp;How far
                                                                        are you willing to Travel for Work?</label>
                                                                    <label class="input">
                                                                        <input type="text" name="howfar" id="howfar">
                                                                    </label>
                                                                </section>
                                                                <!--<section class="col col-4">
                                                                    <label for="genLabourPay" class="label">For most of
                                                                        our work, general labourers are paid around
                                                                        $21-$25 per hour + super. Is this ok with
                                                                        you?</label>
                                                                    <label class="textarea textarea-resizable">
                                                                        <textarea rows="3" class="custom-scroll"
                                                                                  name="genLabourPay"
                                                                                  id="genLabourPay"></textarea>
                                                                    </label>
                                                                </section>-->
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-4">
                                                                    <label for="criminalConviction" class="label">Do you
                                                                        have Prior or Pending Criminal Convictions that
                                                                        may affect your application?</label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="criminalConviction"
                                                                               id="criminalConviction"
                                                                               value="Yes"><i></i>Yes
                                                                    </label>
                                                                    <textarea name="convictionDescription"
                                                                              id="convictionDescription"></textarea>
                                                                    <label class="radio">
                                                                        <input type="radio" name="criminalConviction"
                                                                               id="criminalConviction" value="No"
                                                                               checked><i></i>No
                                                                    </label>
                                                                    <label for="policeCheck" class="label">Do you
                                                                        have a valid police check(done within the last 3 years).<br>If not are happy to do one(it will only be taken off your first pay slip($49) and we will provide you a copy)</label>
                                                                    <label class="checkbox">
                                                                        <input type="checkbox"
                                                                               name="policeCheck"
                                                                               value="No">
                                                                        <i></i>No
                                                                    </label>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="hasCar" class="label">Do you have your
                                                                        own car and licence?</label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="hasCar" id="hasCar"
                                                                               value="Yes"><i></i>Yes
                                                                    </label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="hasCar" id="hasCar"
                                                                               value="No" checked><i></i>No
                                                                    </label>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label class="label"><span
                                                                                style="color:red">*</span>&nbsp;What is
                                                                        your currnet residential status?</label>
                                                                    <label class="radio"></label>
                                                                    <input type="radio" name="residentStatus"
                                                                           id="Citizen"
                                                                           value="Australian Citizen"><i></i>Australian
                                                                    Citizen

                                                                    <label class="radio"></label>
                                                                    <input type="radio" name="residentStatus" id="PR"
                                                                           value="Australian Permanent Resident"><i></i>Australian
                                                                    Permanent Resident

                                                                    <label class="radio"></label>
                                                                    <input type="radio" name="residentStatus"
                                                                           id="WorkingVisa" value="Working Visa"><i></i>Working
                                                                    Visa

                                                                    <label class="radio"></label>
                                                                    <input type="radio" name="residentStatus"
                                                                           id="Student" value="Student Visa"><i></i>Student
                                                                    Visa
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-4">
                                                                    <label for="otherLicence" class="label">
                                                                        What other license/s and
                                                                        qualifications/experience do you have?
                                                                    </label>
                                                                    <div style="height: 300px; overflow-y: scroll;">
                                                                        <div class="col col-4">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="white_card"
                                                                                       value="White Card">
                                                                                <i></i>White Card
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="forklift"
                                                                                       value="Forklift">
                                                                                <i></i>Forklift
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="mr_hr_hc"
                                                                                       value="MR/HR/HC">
                                                                                <i></i>MR/HR/HC
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                        name="reach_forklift"
                                                                                        value="Reach Forklift">
                                                                                <i></i>Reach Forklift
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="stock_picker"
                                                                                       value="Stock/Order/LO Picker">
                                                                                <i></i>Stock/Order/LO Picker
                                                                            </label>
                                                                            <label class="checkbox">
                                                                                <input type="checkbox"
                                                                                       name="first_aid"
                                                                                       value="First Aid">
                                                                                <i></i>First Aid
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="safetyGear" class="label">Do you own/or
                                                                        willing to get?</label>
                                                                    <?php foreach ($gearTypes as $gT) {  ?>
                                                                        <div class="col col-12">
                                                                            <label class="checkbox">
                                                                                <input type="checkbox" name="safetyGear" value="<?php echo $gT['safetyGear']; ?>">
                                                                                <i></i><?php echo $gT['safetyGear']; ?>
                                                                            </label>
                                                                        </div>
                                                                    <?php } ?>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="">Are you currently registered with any jobactive provider? If yes kindly specify the provider name.</label>
                                                                    <label for="jobActive"
                                                                           class="textarea textarea-resizable">
                                                                    </label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="jobActive"
                                                                               id="jobActive" value="Yes"><i></i>Yes
                                                                    </label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="jobActive"
                                                                               id="jobActive" value="No"><i></i>No
                                                                    </label>
                                                                        <textarea rows="3" class="custom-scroll" name="jobActiveDesc" id="jobActiveDesc"></textarea>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="medicalCondition" class="label">Do you
                                                                        have any medical conditions that might affect
                                                                        your work? Any lifting restrictions or back/knee
                                                                        conditions? Which might effect repetitive standing?</label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="medicalCondition"
                                                                               id="medicalCondition" value="Yes"><i></i>Yes
                                                                    </label>
                                                                    <label for="medicalConditionDesc"
                                                                           class="textarea textarea-resizable">
                                                                        <textarea rows="3" class="custom-scroll"
                                                                                  name="medicalConditionDesc"
                                                                                  id="medicalConditionDesc"></textarea>
                                                                    </label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="medicalCondition"
                                                                               id="medicalCondition" value="No" checked><i></i>No
                                                                    </label>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-4">
                                                                    <label for="workType" class="label">Most of our work
                                                                        is on-call casual work with ongoing shifts beign
                                                                        offered to those who perform well and help us
                                                                        out by going in short notice. Does this type of
                                                                        arrangement suit you?</label>
                                                                    <label for="workType"
                                                                           class="textarea textarea-resizable">
                                                                        <textarea rows="3" class="custom-scroll"
                                                                                  name="workType"
                                                                                  id="workType"></textarea>
                                                                    </label>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="shiftAvailable" class="label"><span
                                                                                style="color:red">*</span>&nbsp;Shift
                                                                        Availability:</label>
                                                                    <?php foreach ($shiftTypes as $sT) { ?>
                                                                        <input type="checkbox" name="shiftAvailable"
                                                                               value="<?php echo $sT['shift']; ?>">
                                                                        <i></i><?php echo $sT['shift']; ?>
                                                                    <?php } ?>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="overtime" class="label">Are you able to
                                                                        work overtime if required?</label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="overtime"
                                                                               id="overtime" value="Yes"><i></i>Yes
                                                                    </label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="overtime"
                                                                               id="overtime" value="No" checked><i></i>No
                                                                    </label>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-4">
                                                                    <label for="bookInterview" class="label">Book
                                                                        Candidate in for Interview?</label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="bookInterview"
                                                                               id="bookInterview" value="Yes">
                                                                        <i></i>Yes</label>
                                                                    <label class="radio">
                                                                        <input type="radio" name="bookInterview"
                                                                               id="bookInterview" value="No" checked>
                                                                        <i></i>No</label>
                                                                </section>
                                                            </div>
                                                            <div class="row">
                                                                <section class="col col-4">
                                                                    <label class="label">Date and Time of
                                                                        Interview?</label>
                                                                    <label class="input">
                                                                        <input type="text" name="intvwTime"
                                                                               id="intvwTime" class="intvwTime">
                                                                    </label>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label class="label">Select Consultant</label>
                                                                    <?php echo $consultantName; ?>
                                                                    <input type="hidden" name="consultantId" id="consultantId" value="<?php echo $consId; ?>"/>
                                                                </section>
                                                                <section class="col col-4">
                                                                    <label for="label">Remarks</label>
                                                                    <textarea name="remarks" id="remarks" class="form-control" rows="5"></textarea>
                                                                </section>
                                                            </div>
                                                            <br>
                                                        </fieldset>
                                                        <footer>
                                                            <input type="hidden" name="messageid" id="messageid"
                                                                   value="<?php echo $messageid; ?>"><input
                                                                    type="submit" name="screenSubmit" id="screenSubmit"
                                                                    class="screenSubmit btn btn-primary"
                                                                    value="Submit"/>
                                                        </footer>
                                                    </form>
                        </section>
                        <!--/  -->

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

            </article>
        </div>
        </section>


    </div>
    <!-- end widget content -->

</div>
<!-- end widget div -->

</div>
<!-- end widget -->
<br>
</div>
</div>


</div>
<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white"> <?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
        </div>

        <div class="col-xs-6 col-sm-6 text-right hidden-xs">
            <div class="txt-color-white inline-block">

            </div>
        </div>
    </div>
</div>
<!-- END PAGE FOOTER -->

<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
Note: These tiles are completely responsive,
you can add as many as you like

<div id="shortcut">
    <ul>
        <li>
            <a href="inbox.php" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
        </li>
        <li>
            <a href="calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
        </li>
        <li>
            <a href="gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
        </li>
        <li>
            <a href="invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
        </li>
        <li>
            <a href="gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
        </li>
        <li>
            <a href="profile.html" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
        </li>
    </ul>
</div>-->
<!-- END SHORTCUT AREA -->
<?php include "./template/scr_scripts.php"; ?>

<script type="text/javascript">
    //runAllForms();
    /************* addressFinder **********/
    /*(function () {
        var widget, initAF = function () {
            widget = new AddressFinder.Widget(
                document.getElementById('candidateSuburb'),
                'RWXLVYB7T8EM4JH6NQPK',
                'AU', {
                    "address_params": {
                        "region_code": "H"
                    },
                    "show_locations": true
                }
            );
            widget.on('result:select', function (fullAddress, metaData) {
                $('#candidateSuburb').val(metaData.locality_name);
            });
        };

        function downloadAF(f) {
            var script = document.createElement('script');
            script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
            script.async = true;
            script.onload = f;
            document.body.appendChild(script);
        };
        document.addEventListener('DOMContentLoaded', function () {
            downloadAF(initAF);
        });
    })();*/
    $(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });

        populateClients();
        function populateClients(){
            var action = 'scheduling';
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                data:{action:action},
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        $(document).on('change','#clientId',function(){
            var clientId = $('#clientId :selected').val();
            var action = 'scheduling';
            $.ajax({
                url:"getStateByClient.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId},
                success: function(data){
                    $('#stateId').html('');
                    $('#stateId').html(data);
                }
            });
            $.ajax({
                url:"getClientPositionsList.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId,action:action},
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        });
        $(document).on('click','#clientId',function(){
            var clientId = $('#clientId :selected').val();
            var action = 'scheduling';
            $.ajax({
                url:"getStateByClient.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId},
                success: function(data){
                    $('#stateId').html('');
                    $('#stateId').html(data);
                }
            });
            $.ajax({
                url:"getClientPositionsList.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId,action:action},
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        });
        $(document).on('click','#stateId',function(){
            var clientId = $('#clientId :selected').val();
            var stateId = $('#stateId :selected').val();
            var action = 'scheduling';
            $.ajax({
                url:"getDepartment.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId,stateId:stateId,action:action},
                success: function(data){
                    $('#departmentId').html('');
                    $('#departmentId').html(data);
                }
            });
        });

        populateClientDepartments();
        function populateClientDepartments(){
            $.ajax({
                url:"getClientDepartments.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#departmentId').html('');
                    $('#departmentId').html(data);
                }
            });
        }
        $(document).on('click','#departmentId',function (){
            var department_info = $('#departmentId :selected').val();
            var data = department_info.split('-');
            var clientId = data[0];
            var action = 'scheduling';
            $.ajax({
                url:"getClientPositionsList.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId,action:action},
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        });
        $(document).on('click','#expPosition', function (){
            var department_info = $('#departmentId :selected').val();
            /*var clientId = $('#clientId :selected').val();
            var stateId = $('#stateId :selected').val();
            var departmentId = $('#departmentId :selected').val();*/
            var posId = $('#expPosition :selected').val();
            var action = 'jobdesc';
            $.ajax({
                url:"getDepartment.php",
                type:"POST",
                dataType:"text",
                data:{department_info:department_info,posId:posId,action:action},
                success: function(data){
                    $('textarea#jobDescription').val('');
                    $('textarea#jobDescription').val(data);
                }
            });
        });
        $(document).on('change','#expPosition', function (){
            var department_info = $('#departmentId :selected').val();
            /*var clientId = $('#clientId :selected').val();
            var stateId = $('#stateId :selected').val();
            var departmentId = $('#departmentId :selected').val();*/
            var posId = $('#expPosition :selected').val();
            var action = 'jobdesc';
            $.ajax({
                url:"getDepartment.php",
                type:"POST",
                dataType:"text",
                data:{department_info:department_info,posId:posId,action:action},
                success: function(data){
                    $('textarea#jobDescription').val('');
                    $('textarea#jobDescription').val(data);
                }
            });
        });

        /*$('.screenDate').datetimepicker({dateFormat: 'dd-mm-yy'});
        $('.screenDate').datetimepicker('setDate', (new Date()));*/
        //$('.intvwTime').datetimepicker({dateFormat: 'dd-mm-yy'});
        $('.intvwTime').datetimepicker({
            controlType: 'select',
            timeFormat: 'hh:mm tt'
        });
        $('#convictionDescription').hide();
        $('#medicalConditionDesc').hide();
        $('#jobActiveDesc').hide();
        $('#consultantId').hide();
        $('#intvwTime').hide();

        /*$(document).on('click', '.screenDate', function () {
            $('.screenDate').datetimepicker({dateFormat: 'dd-mm-yy'});
        });*/
        $(document).on('click', '.intvwTime', function () {
            $('.intvwTime').datetimepicker({dateFormat: 'dd-mm-yy'});
        });
        $(document).on('click', '#medicalCondition', function () {
            if ($('input[name=medicalCondition]:checked', '#screenFrm').val() == 'Yes') {
                $('#medicalConditionDesc').show();
            } else {
                $('#medicalConditionDesc').hide();
            }
        });
        $(document).on('click', '#jobActive', function () {
            if ($('input[name=jobActive]:checked', '#screenFrm').val() == 'Yes') {
                $('#jobActiveDesc').show();
            } else {
                $('#jobActiveDesc').hide();
            }
        });
        $(document).on('click', '#criminalConviction', function () {
            if ($('input[name=criminalConviction]:checked', '#screenFrm').val() == 'Yes') {
                $('#convictionDescription').show();
            } else {
                $('#convictionDescription').hide();
            }
        });
        $(document).on('click', '#bookInterview', function () {
            if ($('input[name=bookInterview]:checked', '#screenFrm').val() == 'Yes') {
                $('#intvwTime').show();
            } else if ($('input[name=bookInterview]:checked', '#screenFrm').val() == 'No') {
                $('#intvwTime').hide();
            }
        });

        $(document).on('click', '.screenSubmit', function (evt) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var $screenFrm = $("#screenFrm").validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    jobDescription: {
                      required: true
                    },
                    firstName: {
                        required: true
                    },
                    lastName: {
                        required: true
                    },
                    candidateEmail: {
                        required: true,
                        email: true
                    },
                    candidateMobile: {
                        required: true
                    },
                    convictionDescription: {
                        required: function (element) {
                            return $("input:radio[name='criminalConviction']:checked").val() == 'Yes';
                        }
                    },
                    candidateSex: {
                        required: true
                    },
                    currentWrk: {
                        required: true
                    },
                    howfar: {
                        required: true
                    },
                    residentStatus: {
                        required: true
                    },
                    shiftAvailable: {
                        required: true
                    }
                },
                messages: {
                    jobDescription: {
                        required: "Please enter job description"
                    },
                    firstName: {
                        required: "Please enter candidate first name"
                    },
                    lastName: {
                        required: "Please enter candidate last name"
                    },
                    candidateEmail: {
                        required: "Please enter candidate email",
                        email: "Please enter a VALID email address"
                    },
                    convictionDescription: {
                        required: "Please Describe Criminal Conviction"
                    },
                    candidateSex: {
                        required: "Please select candidate sex"
                    },
                    candidateMobile: {
                        required: "Please enter candidate mobile"
                    },
                    currentWrk: {
                        required: "Please enter work status"
                    },
                    howfar: {
                        required: "Please enter how far are you willing to travel for work"
                    },
                    residentStatus: {
                        required: "Please select residential status"
                    },
                    shiftAvailable: {
                        required: "Please select shift available"
                    }
                },
                submitHandler: function (form) {
                    var url = "./inbox.php";
                    var messageid = $('#messageid').val();
                    var jobDescription = $('textarea#jobDescription').val();
                    var departmentId = $('#departmentId :selected').val();
                    var expPosition = $('#expPosition :selected').val();
                    var action = $('#action').val();
                    var firstName = $('#firstName').val();
                    var lastName = $('#lastName').val();
                    var candidateEmail = $('#candidateEmail').val();
                    var candidateSex = $('#candidateSex :selected').val();
                    var screenDate = $('#screenDate').val();
                    var suburb = $('#candidateSuburb').val();
                    var candidatePhone = $('#candidatePhone').val();
                    var candidateMobile = $('#candidateMobile').val();
                    var currentWrk = $('textarea#currentWrk').val();
                    var howfar = $('#howfar').val();
                    //var genLabourPay = $('textarea#genLabourPay').val();
                    var criminalConviction = $('input[name=criminalConviction]:checked', '#screenFrm').val();
                    var convictionDescription = $('#convictionDescription').val();
                    var medicalCondition = $('input[name=medicalCondition]:checked', '#screenFrm').val();
                    var medicalConditionDesc = $('#medicalConditionDesc').val();
                    var jobActive = $('input[name=jobActive]:checked', '#screenFrm').val();
                    var jobActiveDesc = $('#jobActiveDesc').val();
                    var hasCar = $('input[name=hasCar]:checked', '#screenFrm').val();
                    var residentStatus = $('input[name=residentStatus]:checked', '#screenFrm').val();
                    var white_card =  $('input[name=white_card]:checked', '#screenFrm').val();
                    var forklift =  $('input[name=forklift]:checked', '#screenFrm').val();
                    var mr_hr_hc =  $('input[name=mr_hr_hc]:checked', '#screenFrm').val();
                    var reach_forklift =  $('input[name=reach_forklift]:checked', '#screenFrm').val();
                    var stock_picker =  $('input[name=stock_picker]:checked', '#screenFrm').val();
                    var first_aid =  $('input[name=first_aid]:checked', '#screenFrm').val();
                    var policeCheck = $('input[name=policeCheck]:checked', '#screenFrm').val();
                    var safetyGear = [];
                    $.each($("input[name='safetyGear']:checked"), function () {
                        safetyGear.push($(this).val());
                    });
                    var expOperating = [];
                    $.each($("input[name='expOperating']:checked"), function () {
                        expOperating.push($(this).val());
                    });
                    var workType = $('textarea#workType').val();
                    var shiftAvailable = [];
                    $.each($("input[name='shiftAvailable']:checked"), function () {
                        shiftAvailable.push($(this).val());
                    });
                    var overtime = $('input[name=overtime]:checked', '#screenFrm').val();
                    var bookInterview = $('input[name=bookInterview]:checked', '#screenFrm').val();
                    var intvwTime = $('#intvwTime').val();
                    var consultantId = $('#consultantId').val();
                    var remarks = $('textarea#remarks').val();
                    $(".screenSubmit").attr("disabled", true);
                    $.ajax({
                        type: "POST",
                        url: "./updateCandidate.php",
                        data: {
                            messageid: messageid,
                            jobDescription:jobDescription,
                            departmentId:departmentId,
                            expPosition:expPosition,
                            action:action,
                            firstName: firstName,
                            lastName: lastName,
                            candidateEmail: candidateEmail,
                            candidateSex: candidateSex,
                            screenDate: screenDate,
                            suburb: suburb,
                            candidatePhone: candidatePhone,
                            candidateMobile: candidateMobile,
                            currentWrk: currentWrk,
                            howfar: howfar,
                            criminalConviction: criminalConviction,
                            convictionDescription: convictionDescription,
                            medicalCondition: medicalCondition,
                            medicalConditionDesc: medicalConditionDesc,
                            jobActive:jobActive,
                            jobActiveDesc:jobActiveDesc,
                            hasCar: hasCar,
                            residentStatus: residentStatus,
                            white_card: white_card,
                            forklift: forklift,
                            mr_hr_hc:mr_hr_hc,
                            reach_forklift:reach_forklift,
                            stock_picker:stock_picker,
                            first_aid:first_aid,
                            policeCheck:policeCheck,
                            safetyGear:safetyGear,
                            expOperating: expOperating,
                            workType: workType,
                            shiftAvailable: shiftAvailable,
                            overtime: overtime,
                            bookInterview: bookInterview,
                            intvwTime: intvwTime,
                            consultantId: consultantId,
                            remarks:remarks
                        },
                        dataType: "text",
                        success: function (data) {
                            //console.log('>>>>>>>>>>>>>>>>>>>>>' + data);
                            $('.error').html('');
                            $('.error').html(data);
                            $('html, body').animate({scrollTop: '0px'}, 300);
                        },
                        error: function (jqXHR, exception) {
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
                    });
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>