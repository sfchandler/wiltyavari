<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'CONSULTANT') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}

$msgid = base64_decode($_REQUEST['messageid']) ?? null;
$canId = base64_decode($_REQUEST['canId']) ?? null;
$empCondition = getEmployeeCondition($mysqli, $canId);
$empPromotion = getEmployeePromotionStatus($mysqli, $canId);
$fname = str_replace(')', '', str_replace('(', '', trim(base64_decode($_REQUEST['fname']))));
$lname = str_replace(')', '', str_replace('(', '', trim(base64_decode($_REQUEST['lname']))));
$nickname = getNickNameById($mysqli, $canId);
$eml = base64_decode($_REQUEST['eml']) ?? null;
$mbl = base64_decode($_REQUEST['mbl']) ?? null;
$dob = base64_decode($_REQUEST['dob']) ?? null;
$address = base64_decode($_REQUEST['address']) ?? null;
$consultants = getConsultants($mysqli);
$consId = getConsultantId($mysqli, $_SESSION['userSession']);
$docTypes = getDocumentTypes($mysqli);
$validFrom = $_REQUEST['validFrom'] ?? null;
$validTo = $_REQUEST['validTo'] ?? null ;
$reviewDate = $_REQUEST['reviewDate'] ?? '';

$person = $mysqli->prepare("SELECT candidate.firstName,
								   candidate.lastName,
								   candidate.nickname,
								   candidate.address,
								   candidate.unit_no,
								   candidate.street_number,
								   candidate.street_name,
								   candidate.suburb,
								   candidate.state,
								   candidate.postcode,
								   candidate.mobileNo,
								   candidate.email,
								   candidate.sex,
								   candidate.consultantId,
								   candidate.dob,
                                   candidate.foundhow,
                                   candidate.created_at,
                                   candidate.reg_pack_sent_time,
                                   candidate.fairwork_info_sent_time,
                                   candidate.superFundName,
                                   candidate.superMemberNo,
                                   candidate.superUSINo
							FROM candidate
							WHERE candidate.candidateId = ?") or die($mysqli->error);
$person->bind_param("s", $canId) or die($mysqli->error);
$person->execute();
$person->bind_result($firstName, $lastName, $nickname, $address, $unit_no, $street_number, $street_name, $suburb, $state, $postcode, $mobileNo, $email, $sex, $consultantId, $dob, $foundhow, $created_at,$reg_pack_sent_time,$fairwork_info_sent_time,$superFundName,$superMemberNo,$superUSINo) or die($mysqli->error);
$person->store_result();

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
</head>
<body>
<!-- HEADER -->
<header id="header">
    <?php include "template/top_menu.php"; ?>
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
    <!-- MAIN CONTENT -->
    <div id="content">
        <div id="candidate-list" class="inbox-body no-content-padding">
            <span id="errorMsg" class="error"></span>
            <span id="error_msg" class="error"></span>
            <?php if (!empty($_REQUEST['errorImage'])) {
                echo base64_decode($_REQUEST['errorImage']);
            } ?>
            <?php if (empty($canId) || $canId == '') { ?>
                <form name="frmMain" id="frmMain" class="smart-form" method="post" action="">
                    <div class="table-wrap custom-scroll animated fast fadeInRight">
                        <fieldset>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Candidate ID:
                                        <input type="hidden" name="canId" id="canId" value=""/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                </section>
                                <section class="col col-4">
                                    <button name="tickBtn" type="submit" class="tickBtn btn-sm btn-info">SAVE &#128076
                                    </button>
                                    <button type="button" class="cancelBtn btn btn-secondary btn-sm">CANCEL</button>
                                </section>
                                <section class="col col-4">
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <label for="fName" class="label">First Name :</label>
                                    <label class="input"><i class="icon-append fa fa-user"></i>
                                        <input type="text" name="fName" id="fName" placeholder="First Name" value="">
                                        <b class="tooltip tooltip-bottom-right">Please enter first name</b> </label>
                                </section>
                                <section class="col col-3">
                                    <label for="lName" class="label">Surname :</label>
                                    <label class="input"><i class="icon-append fa fa-user"></i>
                                        <input type="text" name="lName" id="lName" placeholder="Surname" value="">
                                        <b class="tooltip tooltip-bottom-right">Please enter surname</b> </label>
                                </section>
                                <section class="col col-3">
                                    <label for="nickname" class="label">NickName :</label>
                                    <label class="input"><i class="icon-append fa fa-user"></i>
                                        <input type="text" name="nickname" id="nickname" placeholder="Nickname"
                                               value="">
                                        <b class="tooltip tooltip-bottom-right">Please enter Nickname</b> </label>
                                </section>
                                <section class="col col-3">
                                    <label for="email" class="label">Email :</label>
                                    <label class="input"><i class="icon-append fa fa-envelope"></i>
                                        <input type="email" name="email" id="email" placeholder="Email Address"
                                               value="">
                                        <b class="tooltip tooltip-bottom-right">Please enter email address</b> </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <label for="dob" class="label">DOB :</label>
                                    <label class="input"><i class="icon-append fa fa-birthday-cake"></i>
                                        <input type="text" name="dob" id="dob" data-mask-placeholder="-"
                                               placeholder="Date of Birth" class="form-control" value="">
                                        <b class="tooltip tooltip-bottom-right">Please enter Date of Birth</b> </label>
                                </section>
                                <section class="col col-3">
                                    <label for="consultant_id" class="label">Consultant :</label>
                                    <select type="hidden" name="consultant_id" id="consultant_id" class="form-control">
                                        <option value="<?php echo getConsultantId($mysqli,$_SESSION['userSession']); ?>" selected><?php echo getConsultantName($mysqli,getConsultantId($mysqli,$_SESSION['userSession'])); ?></option>
                                    </select>
                                </section>
                                <section class="col col-3">
                                    <label for="mobile" class="label">Mobile Phone</label>
                                    <label class="input"><i class="icon-append fa fa-phone"></i>
                                        <input type="tel" name="mobile" id="mobile" value=""><b
                                                class="tooltip tooltip-bottom-right">Please enter your mobile number</b></label>
                                   <!-- placeholder="Mobile Phone" data-mask="9999 999 999"
                                    data-mask-placeholder="X"-->
                                </section>
                                <section class="col col-3">
                                    <label for="gender" class="label">Gender :</label>
                                    <label class="select">
                                        <select id="gender" name="gender">
                                            <option value="">Select Gender...</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Noanswer">Prefer not to answer</option>
                                        </select> <i></i> </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <label for="address" class="label">Full Address :</label>
                                    <label class="textarea"><i class="icon-append fa fa-envelope"></i>
                                        <textarea class="textarea" name="address" id="address"
                                                  placeholder="Address"></textarea><b
                                                class="tooltip tooltip-bottom-right">Please enter Address
                                            address</b></label>
                                </section>
                                <section class="col col-3">
                                    <label for="unit_no" class="label">Unit No :</label>
                                    <label class="input"><i class="icon-append fa fa-street-view"></i>
                                    <input name="unit_no" id="unit_no" placeholder="Unit No" type="text" value="" class="input"/>
                                    </label>
                                    &nbsp;&nbsp;
                                    <label for="street_number_1" class="label">Street No :</label>
                                    <label class="input"><i class="icon-append fa fa-street-view"></i>
                                        <input class="input" name="street_number_1" id="street_number_1"
                                               placeholder="Street No"/>
                                        <b class="tooltip tooltip-bottom-right">Please enter Street No</b></label>
                                </section>
                                <section class="col col-3">
                                    <label for="street_name" class="label">Street Name :</label>
                                    <label class="input"><i class="icon-append fa fa-street-view"></i>
                                        <input class="input" name="street_name" id="street_name"
                                               placeholder="Street Name"/>
                                        <b class="tooltip tooltip-bottom-right">Please enter Street Name</b></label>
                                </section>
                                <section class="col col-3">
                                    <label for="suburb" class="label">Suburb:</label>
                                    <label class="input"><i class="icon-append fa fa-street-view"></i>
                                        <input class="input" name="suburb" id="suburb" placeholder="Suburb"/>
                                        <b class="tooltip tooltip-bottom-right">Please enter Suburb</b></label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <label for="state" class="label">State:</label>
                                    <label class="input"><i class="icon-append fa fa-street-view"></i>
                                        <input class="input" name="state" id="state" placeholder="State"/>
                                        <b class="tooltip tooltip-bottom-right">Please enter State</b></label>
                                </section>
                                <section class="col col-3">
                                    <label for="postcode" class="label">Postcode:</label>
                                    <label class="input"><i class="icon-append fa fa-street-view"></i>
                                        <input class="input" name="postcode" id="postcode" placeholder="Postcode" value="0"/>
                                        <b class="tooltip tooltip-bottom-right">Please enter Postcode</b></label>
                                </section>
                                <section class="col col-3">
                                    <!--<label for="age" class="label">Age: </label>-->
                                    <label class="select">Applied for Promotion</label>
                                    <label class="select">
                                        <select id="promotion" name="promotion">
                                            <option value="0" selected>N/A</option>
                                            <option value="1">Applied for Promotion</option>
                                        </select> <i></i>
                                    </label>
                                </section>
                                <section class="col col-3">
                                    <label for="foundhow" class="label">How did you find us?</label>
                                    <label class="select">
                                        <select id="foundhow" name="foundhow">
                                            <option value="">Select</option>
                                            <option value="Seek">Seek</option>
                                            <option value="Indeed">Indeed</option>
                                            <option value="FB">FB</option>
                                            <option value="Referral">Referral</option>
                                            <option value="Talent Search">Talent Search</option>
                                            <option value="Jora">Jora</option>
                                            <option value="Jobboard">Jobboard</option>
                                            <option value="JobActive">JobActive</option>
                                            <option value="GoogleAd">Google Ad</option>
                                            <option value="Office Walk-In">Office Walk-In</option>
                                        </select> <i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-3">
                                    <table cellpadding="5" cellspacing="5" border="0">
                                        <tr>
                                            <?php echo getCandidateDocumentTypeIndicator($mysqli, $canId); ?>
                                        </tr>
                                    </table>
                                </section>
                            </div>
                        </fieldset>
                    </div>
                </form>
            <?php } else { ?>
                <form name="frmMain" id="frmMain" class="smart-form" method="post" action="">

                    <div class="table-wrap custom-scroll animated fast fadeInRight">
                        <?php while ($person->fetch()) { ?>
                            <fieldset>
                                <div class="row">
                                    <section class="col col-sm-3">
                                        <label class="label">Candidate ID:
                                            <?php echo $canId; ?> </label>
                                        <input type="hidden" name="canId" id="canId" value="<?php echo $canId; ?>"/>&nbsp;
                                        <br>
                                        <label class="label">JobAdder ID:
                                            <?php echo getJobAdderIdByCandidateId($mysqli,$canId); ?> </label>
                                        <input type="hidden" name="jobadderId" id="jobadderId" value="<?php echo getJobAdderIdByCandidateId($mysqli,$canId); ?>"/>&nbsp;
                                        <br>
                                        <div style="width: 150px;height: 150px; position: relative;">
                                            <?php if ($empCondition == '1') { ?>
                                                <div style="background-color: red;border: 1px solid red;color: white;padding: 10px;	width: 20px;height: 20px;position: absolute;right: -10px;top: -10px;text-align: center; border-radius: 50%;">
                                                    <i class="fa fa-2x fa-exclamation"></i></div>
                                            <?php } ?>
                                            <?php if ($empPromotion == '1') { ?>
                                                <div style="color: green;padding: 10px;width: 20px;height: 20px;position: absolute;left: -10px;top: 0px;text-align: center;">
                                                    <i class="fa fa-3x fa-dollar"></i></div>
                                            <?php } ?>
                                            <?php $empImage = getCandidateImage($mysqli, $canId);
                                            echo $empImage; ?>
                                        </div>
                                        <br>
                                        <div><?php if (!empty($created_at)) {
                                                echo 'profile created at ' . $created_at;
                                            } ?></div>
                                        <!--<div>
                                                            <div class="row">
                                                                <fieldset>
                                                                    <section class="col col-12">
                                                                        <form id="frmImage" enctype="multipart/form-data" method="post">
                                                                            <input type="file" name="employeeImage" id="employeeImage"/>
                                                                            <input type="hidden" name="empId" id="empId" value="<?php /*echo $canId;*/ ?>"/>
                                                                            <input type="submit" name="btnUploadImage" id="btnUploadImage" class="btn btn-sm btn-info" value="Upload Photo"/>
                                                                        </form>
                                                                    </section>
                                                                </fieldset>
                                                            </div>
                                                        </div>-->
                                    </section>
                                    <section class="col col-sm-3">
                                        <label class="label">PIN No:
                                            <?php echo getPINNoById($mysqli, $canId); ?>
                                        </label>
                                        <?php
                                        $empStatus = getEmployeeStatus($mysqli, $canId);
                                        ?>
                                        <button name="activateBtn" id="activateBtn" type="button"
                                                class="activateBtn btn-sm btn-info"
                                                value="<?php echo $empStatus; ?>"><?php echo $empStatus; ?></button>
                                        <br>
                                        <div class="activate_inactivate_notes" style="background-color: #FFFF00"> </div>
                                        <br>
                                        <?php echo getAuditStatus($mysqli, $canId); ?>
                                        <br>
                                        <div class="auditedPerson" style="background-color: #FFFF00"></div>
                                        <br>
                                        <br><br>
                                    </section>
                                    <section class="col col-sm-3">
                                        <button class="btn-info reverse btn-sm" type="button" style="width: 200px;">
                                            <a href="phone_screening.php?action=PHONE_SCREENING&canId=<?php echo $canId;?>&first_name=<?php echo $firstName; ?>&last_name=<?php echo $lastName; ?>&email=<?php echo $email; ?>&phone=<?php echo $mobileNo; ?>&gender=<?php echo $sex; ?>&suburb=<?php echo $suburb;?>&consId=<?php echo getConsultantIdByCandidateId($mysqli,$canId); ?>"
                                              id="phoneScreenBtn" class="phoneScreenBtn btn-info reverse" style="text-decoration: none">
                                                <i class="fa fa-phone"></i> Phone Screen
                                            </a>
                                        </button>
                                        <br>
                                        <br>
                                        <button class="jotFormBtn btn-info reverse btn-sm" type="button" style="width: 200px;">&nbsp;<i class="fa fa-send"></i> Send
                                            Registration Form Link
                                        </button> <span><?php echo $reg_pack_sent_time; ?></span>
                                        <br>
                                        <br>
                                        <button class="btn-info reverse btn-sm" type="button" style="width: 200px;">
                                            <a class="refCheckBtn" style="text-decoration: none" href="ref_check.php?canId=<?php echo $canId; ?>&consId=<?php echo getConsultantIdByCandidateId($mysqli,$canId); ?>" target="_blank">&nbsp;<i class="fa fa-check"></i> Do
                                                Reference Check
                                            </a>
                                        </button>
                                        <br>
                                        <br>
                                        <button class="empContractBtn btn-info reverse btn-sm" type="button" style="width: 200px;"><i
                                                    class="fa fa-send"></i> Send Employment Contract
                                        </button>
                                        <br>
                                        <br>
                                        <button class="inductionBtn btn-info reverse btn-sm" type="button" style="width: 200px;"><i
                                                    class="fa fa-send"></i> Send Casual Induction
                                        </button>
                                        <br>
                                        <br>
                                        <button class="empHandbookBtn btn-info reverse btn-sm" type="button" style="width: 200px;"><i class="fa fa-send"></i> Send  Handbook
                                        </button>
                                        <br>
                                        <br>
                                        <button class="sendFairWorkInfoBtn btn-info reverse btn-sm" type="button" style="width: 200px;">&nbsp;<i class="fa fa-send"></i> Send Fair Work Info
                                        </button>
                                        <span><?php echo $fairwork_info_sent_time; ?></span>
                                        <br>
                                        <br>
                                        <button class="surveyBtn btn-info reverse btn-sm" type="button" style="width: 200px;"><i class="fa fa-send"></i> Send
                                            Customer Survey
                                        </button>
                                        &nbsp; <?php echo getCustomerSurveySentTime($mysqli,$canId); ?>
                                        <br>
                                        <br>
                                    </section>
                                    <section class="col col-sm-3">
                                        <div style="text-align: center"><h3>Mobile App Login Information</h3></div>
                                        <br>
                                        <table class="table table-bordered" style="border: 1px solid dimgrey;">
                                            <tbody>
                                              <tr>
                                                <td><label class="label">Username:</label></td>
                                                <td> <?php echo getCandidateUsername($mysqli, $canId); ?>  <?php if(displayNoPhoneIndicator($mysqli, $canId)){ echo '<i style="color:red" class="glyphicon glyphicon-phone"></i><i style="color:red" class="fa fa-ban"></i>'; } ?>
                                                </td>
                                              </tr>
                                              <tr>
                                                <td>
                                                    <label class="label">Password: </label>
                                                </td>
                                                <td>
                                                    <?php echo getCandidatePassword($mysqli, $canId); ?>
                                                    <button name="appEmailBtn" id="appEmailBtn" type="button"  class="appEmailBtn btn-sm btn-info">Send Email </button>
                                                </td>
                                               </tr>
                                              <tr>
                                                  <td>
                                                      <?php
                                                      $appVersionData = getAppVersionInfoByCandidateId($mysqli,$canId);
                                                      foreach ($appVersionData as $appData){
                                                      ?>
                                                          <label class="label" for="mobile_os">Mobile OS</label>
                                                          <select name="mobile_os" id="mobile_os" class="form-control">
                                                              <option value="iOS" <?php if($appData['mobile_os'] == 'iOS'){ ?> selected <?php } ?>>iOS</option>
                                                              <option value="Android" <?php if($appData['mobile_os'] == 'Android'){ ?> selected <?php } ?>>Android</option>
                                                          </select>
                                                          <label class="label" for="os_version">OS Version</label>
                                                          <select name="os_version" id="os_version" class="form-control">
                                                              <option value="2.0" <?php if($appData['os_version'] == '2.0'){ ?> selected <?php } ?>>2.0</option>
                                                              <option value="Old version" <?php if($appData['os_version'] == 'Old version'){ ?> selected <?php } ?>>Old version</option>
                                                          </select>
                                                      <?php
                                                      }
                                                      ?>
                                                  </td>
                                                  <td><button type="button" name="appVersionBtn" id="appVersionBtn" class="btn-sm btn-info">Change</button></td>
                                              </tr>
                                              <tr>
                                                <td colspan="2">
                                                    <b>User Guides</b>
                                                    <div><a href="/resources/App User Guide.pdf"
                                                            target="_blank">Mobile App user guide</a></div>
                                                </td>
                                               </tr>
                                            </tbody>
                                          </table>
                                    </section>
                                </div>
                                <div style="border: 1px dashed black; padding: 10px 10px 10px 10px">
                                    <br>
                                    <div>
                                        <button name="tickBtn" type="submit" class="tickBtn btn-sm btn-info">SAVE
                                            &#128076
                                        </button>&nbsp;<button type="button" class="cancelBtn btn-info reverse btn-sm">
                                            CANCEL
                                        </button>
                                        <br>
                                        <br>
                                    </div>

                                    <div class="row">
                                        <section class="col col-3">
                                            <label for="fName" class="label">First Name :</label>
                                            <label class="input"><i class="icon-append fa fa-user"></i>
                                                <input type="text" name="fName" id="fName" placeholder="First Name"
                                                       value="<?php echo $firstName; ?>">
                                                <b class="tooltip tooltip-bottom-right">Please enter first name</b>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="lName" class="label">Surname :</label>
                                            <label class="input"><i class="icon-append fa fa-user"></i>
                                                <input type="text" name="lName" id="lName" placeholder="Surname"
                                                       value="<?php echo $lastName; ?>">
                                                <b class="tooltip tooltip-bottom-right">Please enter surname</b>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="nickname" class="label">NickName :</label>
                                            <label class="input"><i class="icon-append fa fa-user"></i>
                                                <input type="text" name="nickname" id="nickname" placeholder="Nickname"
                                                       value="<?php echo $nickname; ?>">
                                                <b class="tooltip tooltip-bottom-right">Please enter Nickname</b>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="email" class="label">Email :</label>
                                            <label class="input"><i class="icon-append fa fa-envelope"></i>
                                                <input type="email" name="email" id="email" placeholder="Email Address"
                                                       value="<?php echo $email; ?>">
                                                <b class="tooltip tooltip-bottom-right">Please enter email address</b>
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-3">
                                            <label for="dob" class="label">DOB :</label>
                                            <label class="input"><i class="icon-append fa fa-birthday-cake"></i>
                                                <input type="text" name="dob" id="dob" data-mask-placeholder="-"
                                                       placeholder="Date of Birth" class="form-control"
                                                       value="<?php echo $dob; ?>">
                                                <b class="tooltip tooltip-bottom-right">Please enter Date of Birth</b>
                                                &nbsp;&nbsp;
                                                <label for="state"
                                                       class="label">Age: <?php echo getCandidateAge($mysqli, $canId); ?></label>
                                            </label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="consultantId" class="label">Consultant : </label>
                                            <?php /*echo getConsultantName($mysqli,getConsultantIdByCandidateId($mysqli,$canId)); */?>
                                            <input type="hidden" name="consultantId" id="consultantId" value="<?php echo getConsultantIdByCandidateId($mysqli,$canId); ?>" readonly />
                                            <select name="consultant_id" id="consultant_id" class="form-control">
                                                <?php
                                                    $consultants = getConsultants($mysqli);
                                                    foreach ($consultants as $cons){
                                                ?>
                                                    <?php echo '<option value="'.$cons['consultantId'].'"'; ?> <?php if(getConsultantIdByCandidateId($mysqli,$canId) == $cons['consultantId']){ echo 'selected'; } ?> <?php echo '>'.$cons['name'].'</option>'; ?>
                                                <?php
                                                    }
                                                ?>
                                            </select>
                                        </section>
                                        <section class="col col-3">
                                            <label for="mobile" class="label">Mobile Phone</label>
                                            <label class="input"><i class="icon-append fa fa-phone"></i>
                                                <input type="tel" name="mobile" id="mobile"
                                                       value="<?php echo $mobileNo; ?>"><b
                                                        class="tooltip tooltip-bottom-right">Please enter your mobile
                                                    number</b></label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="gender" class="label">Gender :</label>
                                            <label class="select">
                                                <select id="gender" name="gender">
                                                    <option value="">Select Gender...</option>
                                                    <option value="Male" <?php if ($sex == 'Male' || $sex == 'M') { ?> selected<?php } ?>>
                                                        Male
                                                    </option>
                                                    <option value="Female" <?php if ($sex == 'Female' || $sex == 'F') { ?> selected<?php } ?>>
                                                        Female
                                                    </option>
                                                    <option value="Noanswer">Prefer not to answer</option>
                                                </select> <i></i> </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-3">
                                            <label for="address" class="label">Full Address :</label>
                                            <label class="textarea"><i class="icon-append fa fa-envelope"></i>
                                                <textarea class="textarea" name="address" id="address"
                                                          placeholder="Address"><?php echo $address; ?></textarea>
                                                <b class="tooltip tooltip-bottom-right">Please enter Address address</b></label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="unit_no" class="label">Unit No :</label>
                                            <label class="input"><i class="icon-append fa fa-street-view"></i>
                                            <input name="unit_no" id="unit_no" placeholder="Unit No" type="text" value="<?php echo $unit_no; ?>" class="input"/>
                                            </label>
                                            &nbsp;&nbsp;
                                            <label for="street_number_1" class="label">Street No :</label>
                                            <label class="input"><i class="icon-append fa fa-street-view"></i>
                                                <input class="input" name="street_number_1" id="street_number_1"
                                                       placeholder="Street No" value="<?php echo $street_number; ?>"/>
                                                <b class="tooltip tooltip-bottom-right">Please enter Street
                                                    No</b></label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="street_name" class="label">Street Name :</label>
                                            <label class="input"><i class="icon-append fa fa-street-view"></i>
                                                <input class="input" name="street_name" id="street_name"
                                                       placeholder="Street Name" value="<?php echo $street_name; ?>"/>
                                                <b class="tooltip tooltip-bottom-right">Please enter Street
                                                    Name</b></label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="suburb" class="label">Suburb:</label>
                                            <label class="input"><i class="icon-append fa fa-street-view"></i>
                                                <input class="input" name="suburb" id="suburb" placeholder="Street Name"
                                                       value="<?php echo $suburb; ?>"/>
                                                <b class="tooltip tooltip-bottom-right">Please enter Suburb</b></label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <!--<section class="col col-3">
                                            <label for="casual_status">Casual Status Notes</label>
                                            <textarea name="casual_status" id="casual_status" cols="55" rows="5" placeholder="Casual Status Notes"><?php /*echo $casual_status; */?></textarea>
                                        </section>-->
                                        <section class="col col-3">
                                            <label for="state" class="label">State:</label>
                                            <label class="input"><i class="icon-append fa fa-street-view"></i>
                                                <input class="input" name="state" id="state" placeholder="State"
                                                       value="<?php echo $state; ?>"/>
                                                <b class="tooltip tooltip-bottom-right">Please enter State</b></label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="postcode" class="label">Postcode:</label>
                                            <label class="input"><i class="icon-append fa fa-street-view"></i>
                                                <input class="input" name="postcode" id="postcode"
                                                       placeholder="Postcode" value="<?php echo $postcode; ?>"/>
                                                <b class="tooltip tooltip-bottom-right">Please enter
                                                    Postcode</b></label>
                                        </section>
                                        <section class="col col-3">
                                            <label for="foundhow" class="label">How did you find us?</label>
                                            <label class="select">
                                                <select id="foundhow" name="foundhow">
                                                    <option value="">Select</option>
                                                    <option value="Seek" <?php if ($foundhow == 'Seek') { ?> selected<?php } ?>>
                                                        Seek
                                                    </option>
                                                    <option value="Indeed"<?php if ($foundhow == 'Indeed') { ?> selected<?php } ?>>
                                                        Indeed
                                                    </option>
                                                    <option value="FB"<?php if ($foundhow == 'FB') { ?> selected<?php } ?>>
                                                        FB
                                                    </option>
                                                    <option value="Referral"<?php if ($foundhow == 'Referral') { ?> selected<?php } ?>>
                                                        Referral
                                                    </option>
                                                    <option value="Talent Search"<?php if ($foundhow == 'Talent Search') { ?> selected<?php } ?>>
                                                        Talent Search
                                                    </option>
                                                    <option value="Jora"<?php if ($foundhow == 'Jora') { ?> selected<?php } ?>>
                                                        Jora
                                                    </option>
                                                    <option value="Jobboard"<?php if ($foundhow == 'Jobboard') { ?> selected<?php } ?>>
                                                        Jobboard
                                                    </option>
                                                    <option value="JobActive"<?php if ($foundhow == 'JobActive') { ?> selected<?php } ?>>
                                                        JobActive
                                                    </option>
                                                    <option value="GoogleAd"<?php if ($foundhow == 'GoogleAd') { ?> selected<?php } ?>>
                                                        Google Ad
                                                    </option>
                                                    <option value="Office Walk-In"<?php if ($foundhow == 'Office Walk-In') { ?> selected<?php } ?>>Office Walk-In</option>
                                                </select> <i></i>
                                            </label>
                                        </section>
                                    </div>
                                    <div class="row">
                                        <section class="col col-3">
                                            <table cellpadding=5" cellspacing="5" border="0">
                                                <tr>
                                                    <?php echo getCandidateDocumentTypeIndicator($mysqli, $canId); ?>
                                                </tr>
                                            </table>
                                        </section>
                                    </div>
                                </div>
                            </fieldset>
                        <?php } ?>
                    </div>
                </form>

            <?php } ?>
            <div id="tabview" <?php if (empty($canId)){ ?>style="display:none;"<?php } ?>>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body">
                        <hr class="simple">
                        <ul id="myTab1" class="nav nav-tabs bordered">
                            <li>
                                <a href="#s1" data-toggle="tab"><i class="fa fa-fw fa-lg fa-plus"></i>Attributes</a>
                            </li>
                            <li>
                                <a href="#s2" data-toggle="tab"><i class="fa fa-fw fa-lg fa-pencil"></i> Notes</a>
                            </li>
                            <?php if ($_SESSION['userType'] != 'ALLOCATIONS') { ?>
                                <li class="active">
                                    <a href="#s3" data-toggle="tab"><i class="fa fa-fw fa-lg fa-file"></i> Documents</a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="#s4" data-toggle="tab"><i class="fa fa-fw fa-lg fa-mobile-phone"></i> SMS</a>
                            </li>
                            <li>
                                <a href="#s5" data-toggle="tab"><i class="fa fa-fw fa-lg fa-bullseye"></i>Allocate
                                    Client</a>
                            </li>
                            <li>
                                <a href="#s6" data-toggle="tab"><i class="glyphicon glyphicon-list-alt"></i> Roster Info</a>
                            </li>
                            <li>
                                <a href="#s7" data-toggle="tab"><i class="glyphicon glyphicon-certificate"></i>
                                    Positions</a>
                            </li>
                           <!-- <li>
                                <a href="#s8" data-toggle="tab"><i class="fa fa-car"></i> Car Pool</a>
                            </li>-->
                            <?php if ($_SESSION['userType'] == 'ACCOUNTS' || $_SESSION['userType'] == 'ADMIN') { ?>
                                <li>
                                    <a href="#s9" data-toggle="tab"><i class="glyphicon glyphicon-piggy-bank"></i> Super
                                        Fund</a>
                                </li>
                                <li>
                                    <a href="#s10" data-toggle="tab"><i class="glyphicon fa fa-lg fa-calculator"></i>
                                        Tax Formula</a>
                                </li>
                                <li>
                                    <a href="#s11" data-toggle="tab"><i class="glyphicon fa fa-lg fa-bank"></i> Bank
                                        Account</a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="#s12" data-toggle="tab"><i class="glyphicon fa fa-lg fa-openid"></i> Visa Type</a>
                            </li>
                            <li>
                                <a href="#s13" data-toggle="tab"><i class="glyphicon fa fa-lg fa-file-pdf-o"></i>PaySlips</a>
                            </li>

                            <li>
                                <a href="#s14" data-toggle="tab"><i class="glyphicon fa fa-lg fa-question"></i>Audit
                                    Checks</a>
                            </li>
                            <?php if ($_SESSION['userType'] == 'ACCOUNTS') { ?>
                                <!--<li>
                                    <a href="#s15" data-toggle="tab"><i class="glyphicon fa fa-lg fa-question"></i>Finance
                                        Checks</a>
                                </li>-->
                            <?php } ?>
                            <li>
                                <a href="#s16" data-toggle="tab"><i class="glyphicon fa fa-lg fa-question"></i>Medical Certificate/Sick Leave</a>
                            </li>
                            <li>
                                <a href="#s17" data-toggle="tab"><i class="glyphicon fa fa-lg fa-info"></i>Placement Info
                                    Checks</a>
                            </li>
                        </ul>
                        <div id="myTabContent1" class="tab-content padding-10">
                            <div class="tab-pane fade" id="s1">
                                <div style="float:left">
                                    <form class="smart-form">
                                        <fieldset>
                                            <label for="otherLicenceId">Attribute Name</label>
                                            <label class="input"><i class="icon-append fa fa-search"></i>
                                                <input id="otherLicenceId" type="text" class="input" size="40"/>
                                            </label>
                                        </fieldset>
                                    </form>
                                </div>
                                <div style="float:left; padding-left:10px; padding-top:40px">
                                    <?php $attrList = getOtherLicenceTypesByCandidate($mysqli, $canId); ?>
                                    <table id="attributes" class="table table-striped table-bordered table-hover"
                                           width="100%">
                                        <thead>
                                        <tr>
                                            <th data-class="expand">CODE</th>
                                            <th data-hide="phone"><i
                                                        class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>DESCRIPTION
                                            </th>
                                            <th data-hide="phone"><i
                                                        class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody class="attributesList">
                                        <?php echo $attrList; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div style="clear:both; padding-bottom:250px;">
                                </div>
                                <br><br><br>
                            </div>
                            <div class="tab-pane fade" id="s2">
                                <?php
                                $notesList = $mysqli->prepare("SELECT 
                                                                                    diaryNoteId,
                                                                                    firstName,
                                                                                    lastName,
                                                                                    candidateId,
                                                                                    axiomno,
                                                                                    activityId,
                                                                                    priorityId,
                                                                                    consultantId,
                                                                                    subject,
                                                                                    todoDate,
                                                                                    todoTime,
                                                                                    todoDuration,
                                                                                    todoNote,
                                                                                    actionDate,
                                                                                    actionTime,
                                                                                    actionDuration,
                                                                                    actionNote,
                                                                                    createdDate,
                                                                                    modifiedDate,
                                                                                    createdBy,
                                                                                    lastmodBy
                                                                                  FROM
                                                                                    diarynote 
                                                                                  WHERE candidateId = ? 
                                                                                  ORDER BY createdDate DESC") or die($mysqli->error);
                                $notesList->bind_param("s", $canId) or die($mysqli->error);
                                $notesList->execute();
                                $notesList->bind_result($diaryNoteId,
                                    $firstName,
                                    $lastName,
                                    $candidateId,
                                    $axiomno,
                                    $activityId,
                                    $priorityId,
                                    $consultantId,
                                    $subject,
                                    $todoDate,
                                    $todoTime,
                                    $todoDuration,
                                    $todoNote,
                                    $actionDate,
                                    $actionTime,
                                    $actionDuration,
                                    $actionNote,
                                    $createdDate,
                                    $modifiedDate,
                                    $createdBy,
                                    $lastmodBy) or die($mysqli->error);
                                $notesList->store_result();
                                $numRows = $notesList->num_rows;
                                ?>
                                <table class="table" style="width: 80%" cellpadding="0" cellspacing="0">
                                    <tbody>
                                      <tr>
                                        <td>
                                            <table style="width: 100%">
                                                <tbody>
                                                  <tr>
                                                    <td style="width: 58%">
                                                        <textarea name="casual_status" id="casual_status" cols="4" rows="4" class="form-control" placeholder="Casual Status Notes"><?php echo getCasualStatus($mysqli,$canId);?></textarea>
                                                        <button id="casualStatBtn" class="btn btn-sm btn-info">Update Casual Status Notes</button>
                                                    </td>
                                                    <td style="width: 2%"></td>
                                                    <td style="width: 40%">
                                                        <?php
                                                        $recStatuses = getRecruitmentStatuses($mysqli);
                                                        $recStatus = getRecruitmentStatusByCandidateId($mysqli,$canId); ?>
                                                        <label for="rec_status">Recruitment Status</label>
                                                        <select name="rec_status" id="rec_status" class="form-control">
                                                            <?php foreach ($recStatuses as $recStat){ ?>
                                                                <option value="<?php echo $recStat['rec_status_id']; ?>" <?php if($recStat['rec_status_id'] == $recStatus){ ?> selected <?php } ?>><?php echo $recStat['rec_status']; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                        <button id="recruitmentStatusBtn" class="btn btn-sm btn-info">Update Recruitment Status</button>
                                                    </td>
                                                  </tr>
                                                </tbody>
                                              </table>
                                        </td>
                                      </tr>
                                      <tr>
                                          <td>
                                              <a href="diaryNotes.php?messageid=<?php echo $msgid; ?>&canId=<?php echo $canId; ?>&fname=<?php echo $fname; ?>&lname=<?php echo $lname; ?>&eml=<?php echo $eml; ?>mbl=<?php echo $mbl; ?>&consId=<?php echo $consId; ?>"
                                                 target="_blank" class="btn btn-info"><i class="fa fa-fw fa-lg fa-plus"></i>New Note</a>
                                              <form name="frmFilter" id="frmFilter" style="padding: 0px 0px 0px 0px">
                                                  <input type="radio" name="status" id="status" value="All" checked>All
                                                  <input type="radio" name="status" id="status" value="Actioned">Actioned
                                                  <input type="radio" name="status" id="status" value="UnActioned">UnActioned
                                              </form>
                                          </td>
                                      </tr>
                                    </tbody>
                                  </table>

                                <!-- Widget ID (each widget will need unique ID)-->
                                <div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3"
                                     data-widget-editbutton="false">

                                    <!-- widget div-->
                                    <div>

                                        <!-- widget edit box -->
                                        <div class="jarviswidget-editbox">
                                            <!-- This area used as dropdown edit box -->

                                        </div>
                                        <!-- end widget edit box -->

                                        <!-- widget content -->
                                        <div class="widget-body no-padding">
                                            <div id="notelist" style="width:98%;">
                                                <table id="datatable_tabletools"
                                                       class="table table-striped table-bordered table-hover"
                                                       width="100%">
                                                    <thead>
                                                    <tr>
                                                        <th data-class="expand">CONSULTANT</th>
                                                        <th data-hide="phone"><i
                                                                    class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>DATE
                                                            CREATED
                                                        </th>
                                                        <th data-class="phone,tablet"><i
                                                                    class="fa fa-fw fa-times-circle txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            TIME
                                                        </th>
                                                        <th data-hide="phone"><i
                                                                    class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            ACTIVITY
                                                        </th>
                                                        <th data-class="phone">
                                                            TODO NOTE
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            SUBJECT
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            PERSON
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <?php
                                                    while ($notesList->fetch()) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo getConsultantName($mysqli, $consultantId);//echo getPriorityLevel($mysqli,$priorityId);
                                                                ?></td>
                                                            <td><?php echo $createdDate; ?></td>
                                                            <td><?php echo $todoTime; ?></td>
                                                            <td>
                                                                <a href="diaryNotes.php?dNoteId=<?php echo $diaryNoteId; ?>&fname=<?php echo $firstName; ?>&lname=<?php echo $lastName; ?>&canId=<?php echo $candidateId; ?>&actId=<?php echo $activityId; ?>&pId=<?php echo $priorityId; ?>&consId=<?php echo $consultantId; ?>&subj=<?php echo $subject; ?>&toDate=<?php echo $todoDate; ?>&toTime=<?php echo $todoTime; ?>&toDur=<?php echo $todoDuration; ?>&tNote=<?php echo $todoNote; ?>&actDate=<?php echo $actionDate; ?>&actTime=<?php echo $actionTime; ?>&actDur=<?php echo $actionDuration; ?>&actNote=<?php echo $actionNote; ?>&crDate=<?php echo $createdDate; ?>&modDate=<?php echo $modifiedDate; ?>&crBy=<?php echo $createdBy; ?>&lBy=<?php echo $lastmodBy; ?>"
                                                                   style="cursor:pointer"
                                                                   target="_blank"><?php echo getActivityLevel($mysqli, $activityId); ?></a>
                                                            </td>
                                                            <td><?php echo $todoNote; ?></td>
                                                            <td><?php echo $subject; ?></td>
                                                            <td><?php echo $firstName . ' ' . $lastName; ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                        <!-- end widget content -->

                                    </div>
                                    <!-- end widget div -->

                                </div>
                                <!-- end widget -->
                                <br> <br> <br> <br> <br>
                            </div>
                            <?php if ($_SESSION['userType'] != 'ALLOCATIONS') { ?>
                                <div class="tab-pane fade in active" id="s3">
                                    <div style="float:left; width:55%; padding-top:20px;">
                                        <span class="error"></span>
                                        <div style="height: 400px; overflow: auto">
                                            <table id="candidateDocList"
                                                   class="table table-striped table-bordered table-hover" width="80%">
                                                <thead>
                                                <tr>
                                                    <th data-class="expand"><i
                                                                class="fa fa-fw fa-file txt-color-blue hidden-md hidden-sm hidden-xs"></i>DOCUMENTS
                                                    </th>
                                                    <th data-class="expand"><i
                                                                class="fa fa-fw fa-file txt-color-blue hidden-md hidden-sm hidden-xs"></i>DOCUMENT
                                                        TYPE
                                                    </th>
                                                    <th data-class="expand"><i
                                                                class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION
                                                    </th>
                                                    <th data-class="expand"><i
                                                                class="fa fa-fw fa-envelope txt-color-blue hidden-md hidden-sm hidden-xs"></i>EMAIL
                                                    </th>
                                                </tr>
                                                </thead>
                                                <tbody class="documentsList">
                                                <?php echo getCandidateDocuments($mysqli, $canId, $_SESSION['userSession']); ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <br><br>
                                    </div>
                                    <div style="float:left; padding-left:20px;padding-bottom:50px; width:45%">
                                        <form id="frmFile" action="docUpload.php" class="smart-form"
                                              enctype="multipart/form-data" method="post">
                                            <div class="row">
                                                <fieldset>
                                                    <section class="col col-3">
                                                        <label for="docType" class="label">Document Type:</label>
                                                        <label class="select">
                                                            <select id="docTypeId" name="docTypeId">
                                                                <?php foreach ($docTypes as $dT) { ?>
                                                                    <option value="<?php echo $dT['typeId']; ?>"><?php echo $dT['typeLabel']; ?></option>
                                                                <?php } ?>
                                                            </select><i class="icon-append fa fa-file"></i></label>
                                                        <input type="hidden" name="candid"
                                                               value="<?php echo $canId; ?>"/>
                                                        <label for="file">File input:</label>
                                                        <div class="input input-file">
                                                                                        <span class="button">
                                                                                            <input class="input"
                                                                                                   type="file" id="file"
                                                                                                   name="file"
                                                                                                   onchange="this.parentNode.nextSibling.value = this.value">Browse</span><input
                                                                    type="text" placeholder="" readonly>
                                                            <button class="btn btn-info btn-sm" type="submit"
                                                                    value="Upload"><i
                                                                        class="glyphicon glyphicon-upload"></i>Upload
                                                            </button>
                                                        </div>
                                                        <div id="progress">
                                                            <div id="bar"></div>
                                                            <div id="percent">0%</div>
                                                        </div>
                                                        <div id="message"></div>
                                                    </section>
                                                    <section class="col col-3"></section>
                                                    <section class="col col-3">
                                                        <label for="docName">Document Name:</label>
                                                        <label class="input"><i class="icon-append fa fa-file-text"></i>
                                                            <input type="text" name="docName" id="docName" readonly
                                                                   value="">
                                                        </label>
                                                        <label for="validFrom">Valid From:</label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" name="validFrom" id="validFrom"
                                                                   data-mask-placeholder="-" class="form-control"
                                                                   data-mask="99/99/9999"
                                                                   value="<?php echo $validFrom; ?>">
                                                        </label>
                                                        <label for="validTo">Valid To:</label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" name="validTo" id="validTo"
                                                                   data-mask-placeholder="-" class="form-control"
                                                                   data-mask="99/99/9999"
                                                                   value="<?php echo $validTo; ?>">
                                                        </label>
                                                        <label for="reviewDate">Review Date:</label>
                                                        <label class="input"><i class="icon-append fa fa-calendar"></i>
                                                            <input type="text" name="reviewDate" id="reviewDate"
                                                                   data-mask-placeholder="-" class="form-control"
                                                                   data-mask="99/99/9999"
                                                                   value="<?php echo $reviewDate; ?>">
                                                        </label>
                                                    </section>
                                                    <section class="col col-3">
                                                        <label for="notes">Notes :</label>
                                                        <label class="textarea"><i class="icon-append fa fa-info"></i>
                                                            <textarea class="textarea" name="notes"
                                                                      placeholder="Notes"><?php echo $notes; ?></textarea>
                                                    </section>
                                                </fieldset>
                                            </div>
                                        </form>
                                    </div>
                                    <div style="float:left; padding-left:50px; padding-top:50px;">
                                    </div>
                                    <div style="clear:both;"></div>
                                </div>
                            <?php } ?>
                            <div class="tab-pane fade" id="s4">
                                <div>
                                    <div>
                                        <div>
                                            <a href="#" class="newSMS"><i class="fa fa-fw fa-lg fa-plus"></i><i
                                                        class="fa fa-fw fa-lg fa-mobile"></i>New</a>
                                            <form name="frmSMS" id="frmSMS">
                                                <input type="radio" name="smsstatus" id="smsstatus" value="All" checked>All
                                                <input type="radio" name="smsstatus" id="smsstatus" value="Outgoing">Outgoing
                                                <input type="radio" name="smsstatus" id="smsstatus" value="Incoming">Incoming
                                            </form>
                                            <div align="center"><span id="smsLoading">Loading Please wait...</span>
                                            </div>
                                            <div class="smsList">
                                                <table id="smsDataTable"
                                                       class="scroll table table-striped table-bordered table-hover">
                                                    <thead>
                                                    <tr>
                                                        <th data-class="expand"><i
                                                                    class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>DATE/TIME
                                                        </th>
                                                        <th data-class="expand"><i
                                                                    class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>CONSULTANT
                                                        </th>
                                                        <th data-hide="phone"><i
                                                                    class="fa fa-fw fa-arrows txt-color-blue hidden-md hidden-sm hidden-xs"></i>DIRECTION
                                                        </th>
                                                        <th data-class="phone,tablet"><i
                                                                    class="fa fa-fw fa-asterisk txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            STATUS
                                                        </th>
                                                        <th data-class="phone,tablet"><i
                                                                    class="fa fa-fw fa-asterisk txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            SMS DELIVERY
                                                        </th>
                                                        <th data-hide="phone"><i
                                                                    class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            SENDER
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-envelope txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            MESSAGE
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-mobile-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i>RECIPIENT
                                                            MOBILENO
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            ALERT ME
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            ACTIVITY
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-mobile-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            SENDING NUMBER
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            MESSAGEID
                                                        </th>
                                                        <th data-hide="phone,tablet"><i
                                                                    class="fa fa-fw fa-info txt-color-blue hidden-md hidden-sm hidden-xs"></i>
                                                            SMS ACCOUNT
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody id="smsBody">

                                                    </tbody>
                                                </table>
                                            </div>
                                            <br><br>
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="s5">
                                <header>
                                    Assign Candidate to Company/Department
                                </header>
                                <div class="row">
                                    <section class="col col-sm-4">
                                        <form id="assignFrm" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select Client/Location</label>
                                                        <label class="select">
                                                            <select name="clientId" id="clientId" class="clientsMenu">
                                                            </select> <i></i> </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select State</label>
                                                        <label class="select">
                                                            <select name="stateId" id="stateId" class="statesMenu">
                                                            </select> <i></i> </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select Priority</label>
                                                        <label class="select">
                                                            <select name="empPriority" id="empPriority">
                                                                <option value="0">None</option>
                                                                <option value="1">Prioritise</option>
                                                            </select> <i></i> </label>
                                                    </section>
                                                </div>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select Department/Area Of Work/Role</label>
                                                        <label class="select">
                                                            <select name="deptId" id="deptId" class="deptsMenu">
                                                            </select> <i></i> </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </section>
                                    <section class="col col-sm-8">
                                        <table id="allocateDataTable" class="table table-bordered">
                                            <thead>
                                            <tr>
                                                <th data-class="expand"><i
                                                            class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>COMPANY/CLIENT
                                                </th>
                                                <th data-hide="phone"><i
                                                            class="fa fa-fw fa-border txt-color-blue hidden-md hidden-sm hidden-xs"></i>STATE
                                                </th>
                                                <th data-hide="phone"><i
                                                            class="fa fa-fw fa-dedent txt-color-blue hidden-md hidden-sm hidden-xs"></i>DEPARTMENT
                                                </th>
                                                <th data-hide="phone"><i
                                                            class="fa fa-fw fa-dedent txt-color-blue hidden-md hidden-sm hidden-xs"></i>PRIORITY
                                                </th>
                                                <th data-hide="phone"><i
                                                            class="fa fa-fw fa-dedent txt-color-blue hidden-md hidden-sm hidden-xs"></i>OH&S
                                                    SMS SENT TIME
                                                </th>
                                                <!--<th data-class="phone">RITEQ ID</th>
                                                <th data-class="phone">CHRONUS ID</th>-->
                                                <th data-class="phone"><i
                                                            class="fa fa-fw fa-exclamation txt-color-blue hidden-md hidden-sm hidden-xs"></i>STATUS
                                                </th>
                                                <th data-hide="phone" colspan="2"><i
                                                            class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION  &nbsp; <button class="excludeAllAllocationsBtn btn btn-info">Exclude All</button>
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody id="allocateBody">
                                            </tbody>
                                        </table>
                                    </section>
                                </div>
                                <!--<div style="clear:both"></div>-->
                                <br><br><br>
                            </div>
                            <!-- end s5 tab -->
                            <!-- s6 tab -->
                            <div class="tab-pane fade" id="s6">
                                <div class="row">
                                    <fieldset>
                                        <legend>Shift Information</legend>
                                        <section class="col col-4">
                                            <div id="shiftrange" class="pull-left"
                                                 style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 20%">
                                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                                <span></span> <b class="caret"></b>
                                            </div>
                                            <input name="stDate" id="stDate" type="hidden"/>
                                            <input name="enDate" id="enDate" type="hidden"/>
                                            <button name="viewShiftsBtn" id="viewShiftsBtn" class="btn btn-info">View
                                                Shifts
                                            </button>
                                        </section>
                                    </fieldset>
                                </div>
                                <div class="row">
                                    <table class="table" width="80%">
                                        <thead>
                                        <tr>
                                            <th>Shift Date</th>
                                            <th>Shift Day</th>
                                            <th>Client</th>
                                            <th>State</th>
                                            <th>Department</th>
                                            <th>Position</th>
                                            <th>Shift Start</th>
                                            <th>Shift End</th>
                                            <th>Shift Status</th>
                                            <th>Check IN</th>
                                            <th>Check OUT</th>
                                            <th>Check IN Location</th>
                                            <th>Check OUT Location</th>
                                        </tr>
                                        </thead>
                                        <tbody id="shiftDisplay" style="height:200px; overflow-y: auto;">
                                        </tbody>
                                    </table>
                                </div>
                                <fieldset>
                                    <div class="row">
                                        <form id="frmAv" class="smart-form" method="post">
                                            <section class="col col-6">
                                                <header>
                                                    Roster Availability
                                                </header>
                                                <br>
                                                <div class="pull-left">Set UnAvailable Dates for Rosters &nbsp;</div>
                                                <div id="reportrange" class="pull-left"
                                                     style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 30%">
                                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                                    <span></span> <b class="caret"></b>
                                                </div>
                                                <div class="pull-left">
                                                    <input name="strDate" id="strDate" type="hidden"/>
                                                    <input name="endDate" id="endDate" type="hidden"/>
                                                    <button class="addDateRangeBtn btn btn-info btn-sm"
                                                            type="button">
                                                        <i class="glyphicon glyphicon-unchecked"></i>&nbsp;Set
                                                        Unavailable Date Range
                                                    </button>
                                                </div>
                                                <br><br><br>
                                                <div id="unDisplay" style="padding-left: 50px;">
                                                    <?php echo getAvailableDateRange($mysqli, $canId); ?>
                                                </div>
                                            </section>
                                        </form>
                                        <form id="frmNotes" class="smart-form" method="post">
                                            <section class="col col-6">
                                                <header>
                                                    Roster Notes
                                                </header>
                                                <div>
                                                    <label for="rosternote">Roster Notes :</label>
                                                    <label class="textarea"><i
                                                                class="icon-append fa fa-info"></i></label>
                                                    <textarea class="textarea" name="rosternote" id="rosternote"
                                                              placeholder="Roster Notes" rows="8"
                                                              style="min-width: 100%">
                                                                                         <?php echo getRosterNotes($mysqli, $canId); ?>
                                                                                     </textarea>
                                                    <button class="rosterNoteBtn btn btn-info btn-sm" type="submit">
                                                        <i class="glyphicon glyphicon-plus-sign"></i>&nbsp;Add/Edit
                                                        Roster Notes
                                                    </button>
                                                </div>
                                            </section>
                                        </form>
                                    </div>
                                </fieldset>
                            </div>
                            <!-- end s6 tab -->
                            <!-- start s7 tab -->
                            <div class="tab-pane fade" id="s7">
                                <fieldset>
                                    <div class="row">
                                        <form id="eposFrm" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select Position</label>
                                                        <label class="select">
                                                            <select name="positionid" id="positionid">
                                                            </select> <i></i> </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-certificate txt-color-blue hidden-md hidden-sm hidden-xs"></i>Position
                                        </th>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-asterisk txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action
                                        </th>
                                        </thead>
                                        <tbody id="positionList"><?php echo getAssignedPositions($mysqli, $canId); ?></tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- end s7 tab -->
                            <!-- start s8 tab -->
                            <!--<div class="tab-pane fade" id="s8">
                                <fieldset>
                                    <div class="row">
                                        <form id="carPoolFrm" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select Car Pool</label>
                                                        <label class="select">
                                                            <select name="carpoolId" id="carpoolId">
                                                            </select> <i></i> </label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <div>
                                    <table id="carPoolTbl" class="table table-bordered">
                                        <thead>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-car txt-color-blue hidden-md hidden-sm hidden-xs"></i>CarPool
                                        </th>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-asterisk txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action
                                        </th>
                                        </thead>
                                        <tbody id="carList"><?php /*echo listAssignedCarPool($mysqli, $canId); */?></tbody>
                                    </table>
                                </div>
                            </div>-->
                            <!-- end s8 tab -->
                            <!-- s9 tab -->
                            <div class="tab-pane fade" id="s9">
                                <fieldset>
                                    <div class="row">
                                        <form id="superFrm" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select SuperFund to Assign</label>
                                                        <label class="select">
                                                            <select name="transCode" id="transCode">
                                                            </select> <i></i> </label>
                                                    </section>
                                                    <section class="col col-4">
                                                        <label for="superFundName">Super Fund Name.</label>
                                                        <label class="input"><i
                                                                    class="icon-append fa fa-certificate"></i>
                                                            <input type="text" name="superFundName" id="superFundName"
                                                                   class="form-control"
                                                                   value="<?php echo $superFundName; ?>">
                                                        </label>
                                                        <label for="memberNo">Member No.</label>
                                                        <label class="input"><i
                                                                    class="icon-append fa fa-certificate"></i>
                                                            <input type="text" name="memberNo" id="memberNo"
                                                                   class="form-control"
                                                                   value="<?php echo $superMemberNo; ?>">
                                                        </label>
                                                        <label for="superUSINo">Super USI No.</label>
                                                        <label class="input"><i
                                                                    class="icon-append fa fa-certificate"></i>
                                                            <input type="text" name="superUSINo" id="superUSINo"
                                                                   class="form-control"
                                                                   value="<?php echo $superUSINo; ?>">
                                                        </label>
                                                        <button name="btnMemberNo" id="btnMemberNo"
                                                                class="btn btn-sm btn-next">Add/Edit MemberNo
                                                        </button>
                                                    </section>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <div>
                                    <table id="superFundTable" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th data-class="expand"><i
                                                        class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>SUPER
                                                FUND DESCRIPTION
                                            </th>
                                            <th data-hide="phone"><i
                                                        class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="superFundBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- s9 tab end -->
                            <!-- s10 tab -->
                            <div class="tab-pane fade" id="s10">
                                <fieldset>
                                    <div class="row">
                                        <form id="tfnFrm" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row pull-left" style="padding-left: 1%">
                                                    <label class="label">Tax File No(TFN)</label>
                                                    <label class="input pull-left">
                                                        <input class="input" type="text" name="tfn" id="tfn"/>
                                                    </label>
                                                    <button class="tfnBtn btn btn-default btn-sm" name="tfnBtn"
                                                            id="tfnBtn"><i class="glyphicon glyphicon-lock"></i>Update
                                                        TFN
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <table id="taxFileTable" class="table table-bordered" style="width: 300px;">
                                    <thead>
                                    <tr>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>TAX
                                            FILE NO
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="taxfileBody">
                                    </tbody>
                                </table>
                                <fieldset>
                                    <div class="row">
                                        <form id="taxFrm" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row">
                                                    <section class="col col-4">
                                                        <label class="label">Select Tax Code</label>
                                                        <label class="select">
                                                            <select name="taxcode" id="taxcode">
                                                            </select><i></i></label>
                                                    </section>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <div>
                                    <table id="taxTable" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th data-class="expand"><i
                                                        class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>TAX
                                                CODE DESCRIPTION
                                            </th>
                                            <th data-hide="phone"><i
                                                        class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="taxcodeBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- s10 tab end -->
                            <!-- s11 tab -->
                            <div class="tab-pane fade" id="s11">
                                <fieldset>
                                    <div class="row">
                                        <form id="frmAccount" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row pull-left" style="padding-left: 1%">
                                                    <label class="label">Bank Account Information</label>
                                                    <label class="input pull-left">
                                                        <input class="input" type="text" name="bankName"
                                                               id="bankName" placeholder="Bank Account Name"/>
                                                    </label>
                                                    <label class="input pull-left">
                                                        <input class="input" type="text" name="accountName"
                                                               id="accountName" placeholder="Account Name"/>
                                                    </label>
                                                    <label class="input pull-left">
                                                        <input class="input" type="text" name="accountNumber"
                                                               id="accountNumber" placeholder="Account Number"/>
                                                    </label>
                                                    <label class="input pull-left">
                                                        <input class="input" size="7" type="text" name="bsb" id="bsb"
                                                               placeholder="xxx-xxx"/>
                                                    </label>
                                                    <button class="saveAccountBtn btn btn-default btn-sm"
                                                            name="saveAccountBtn" id="saveAccountBtn"><i
                                                                class="glyphicon fa fa-lg fa-bank"></i>Update Bank
                                                        Account
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <table id="accountInfoTbl" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Account
                                            Name
                                        </th>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Account
                                            Number
                                        </th>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>BSB
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="accountInfoBody">
                                    </tbody>
                                </table>
                            </div>
                            <!-- s11 tab end -->
                            <!-- s12 tab -->
                            <div class="tab-pane fade" id="s12">
                                <fieldset>
                                    <div class="row">
                                        <form id="frmAccount" class="smart-form" method="post">
                                            <fieldset>
                                                <div class="row pull-left" style="padding-left: 1%">
                                                    <label class="label">Visa Information</label>
                                                    <label class="pull-left">
                                                        <select name="visaType" id="visaType" class="form-control">
                                                        </select>
                                                    </label>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>
                                </fieldset>
                                <table id="visaInfoTbl" class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Visa
                                        </th>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Expiry
                                            Date
                                        </th>
                                        <th data-class="expand"><i
                                                    class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody id="visaInfoBody"><?php echo listAssignedVisaType($mysqli, $canId); ?></tbody>
                                </table>
                            </div>
                            <!-- s12 tab end -->
                            <!-- s13 -->
                            <div class="tab-pane fade" id="s13">
                                <fieldset>
                                    <div class="row">
                                        <div class="row pull-left" style="padding-left: 1%">
                                            <label class="label">Pay Slip Information</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <div style="width:100%; height:100%; overflow-y: scroll; height: 400px;">
                                    <table id="payslipTbl" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th data-class="expand"><i
                                                        class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>payrun
                                                ID
                                            </th>
                                            <th data-class="expand"><i
                                                        class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Weekending
                                                Date
                                            </th>
                                            <th data-class="expand"><i
                                                        class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>PaySlip
                                            </th>
                                            <th data-class="expand"><i
                                                        class="fa fa-fw txt-color-blue hidden-md hidden-sm hidden-xs"></i>&nbsp;
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody id="payslipTblBody">
                                        <?php echo getCandidatePaySlips($mysqli, $canId); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- s13 tab end -->

                            <!-- s14 -->
                            <div class="tab-pane fade" id="s14">
                                <div align="center"><h2>Audit Check</h2></div>
                                <div class="error"
                                     style="color: red; font-weight: bold;"><?php $profileTfn = getCandidateTFN($mysqli, $canId); ?>
                                    <button name="tfnCheck" id="tfnCheck" class="tfnCheck btn btn-info"
                                            data-profiletfn="<?php echo $profileTfn; ?>">TFN Check
                                    </button>
                                    <div id="tfnCheckResult" class="error"></div>
                                </div>
                                <div class="auditChkError"></div>
                                <div id="accCheck" style="width:100%; height:100%;">

                                </div>
                            </div>
                            <!-- s14 tab end -->
                            <!-- s15 -->

                            <?php if ($_SESSION['userType'] == 'ACCOUNTS') { ?>
                                <!--<div class="tab-pane fade" id="s15">
                                    <div align="center"><h2>Finance Checks</h2></div>
                                    <div class="error" style="color: red; font-weight: bold;"></div>
                                    <div class="financeChkError"></div>
                                    <div id="financeCheck" style="width:100%; height:100%;">

                                    </div>
                                </div>-->
                            <?php } ?>
                            <!-- s15 tab end -->
                            <!-- s16 tab -->
                            <div class="tab-pane fade" id="s16">
                                <table class="table">
                                    <thead>
                                      <tr>
                                        <th>DOCUMENTS</th>
                                        <th>DOCUMENT TYPE</th>
                                        <th>NOTES/SHIFTID</th>
                                        <th>SUBMITTED AT</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php echo getCandidateMedicalCertificateInfo($mysqli,$canId);?>
                                    </tbody>
                                  </table>
                            </div>
                            <!-- s17 placement info -->
                            <div class="tab-pane fade" id="s17">
                                <?php
                                $placement_info = getPlacementInfoByJobAdderId($mysqli,getJobAdderIdByCandidateId($mysqli,$canId));
                                ?>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                      <tr>
                                        <th>Placement ID</th>
                                        <th>Candidate Details</th>
                                        <th>Job Details</th>
                                        <th>Placement Period</th>
                                        <th>Payment Details</th>
                                        <th>Billing Details</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                <?php
                                foreach($placement_info as $placement){
                                    echo '<tr>
                                            <td>'.$placement['placement_id'].'</td>
                                            <td>
                                                <ul>
                                                    <li>Name: '.$placement['candidate_name'].'</li>
                                                    <li>Mobile: '.$placement['candidate_mobile'].'</li>
                                                    <li>Email: '.$placement['candidate_email'].'</li>
                                                    <li>DateOfBirth: '.$placement['candidate_dob'].'</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>Name: '.$placement['job_detail_name'].'</li>
                                                    <li>Job Title: '.$placement['job_title'].'</li>
                                                    <li>Workplace Address: '.$placement['work_place_address'].'</li>
                                                </ul>
                                                <ul>
                                                    <b>Supervisor/Approver Info:</b>
                                                    <li>Name: '.$placement['approver_name'].'</li>
                                                    <li>Email: '.$placement['approver_email'].'</li>
                                                </ul>  
                                            </td>
                                            <td>
                                                <ul>
                                                    <li>Type: '.$placement['placement_period_type'].'</li>
                                                    <li>Start Date: '.$placement['placement_period_start_date'].'</li>
                                                    <li>End Date: '.$placement['placement_period_end_date'].'</li>
                                                </ul>
                                            </td>
                                            <td>
                                                 <ul>
                                                    <li>Pay rate: '.$placement['pay_rate'].'</li>
                                                    <li>Charge rate '.$placement['charge_rate'].'</li>
                                                    <li>NetMargin: '.$placement['net_margin'].'</li>
                                                    <li>Award: '.$placement['award'].'</li>
                                                </ul>
                                            </td>  
                                             <td>
                                                <ul>
                                                    <li>Name: '.$placement['billing_name'].'</li>
                                                    <li>Email:  '.$placement['billing_email'].'</li>
                                                </ul>
                                                <ul>
                                                    <b>Billing Address:</b>
                                                    <li>Billing Address'.$placement['billing_address'].'</li>
                                                    <li>Terms'.$placement['billing_terms'].'</li>
                                                </ul>
                                            </td>  
                                         </tr>';
                                }
                                ?>
                                </tbody>
                                </table>
                            </div>
                            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                        </div>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <div id="empConEmailPopup" style="width:500px; display:block">
                <form id="empContractEmailFrm" name="empContractEmailFrm" class="smart-form" method="post"
                      action="empContractEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="empConsultantEmail" id="empConsultantEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="empFirstName" name="empFirstName" value="<?php echo base64_encode($fname); ?>"
                                       class="form-control"/>
                                <input type="hidden" id="empLastName" name="empLastName" value="<?php echo base64_encode($lname); ?>"
                                       class="form-control"/>
                                <input type="hidden" id="empCanId" name="empCanId" value="<?php echo base64_encode($canId); ?>"
                                       class="form-control"/>
                                <input type="hidden" id="empEmail" name="empEmail" value="<?php echo base64_encode($email); ?>"
                                       class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="empContractBody"
                                          id="empContractBody" placeholder="" style="width: 100%;display: none">
                                                            </textarea>
                                <div id="empEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="inductionEmailPopup" style="width:500px; display:block">
                <form id="inductionEmailFrm" name="inductionEmailFrm" class="smart-form" method="post"
                      action="inductionEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="inductionConEmail" id="inductionConEmail"
                                       value="<?php echo getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession'])); ?>"/>
                                <input type="hidden" id="inductionCanId" name="inductionCanId"
                                       value="<?php echo $canId; ?>" class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="inductionBody"
                                          id="inductionBody" placeholder=""
                                          style="width: 100%;display: none"></textarea>
                                <div id="inductionEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="daviesInductionEmailPopup" style="width:500px; display:block">
                <form id="daviesInductionEmailFrm" name="daviesInductionEmailFrm" class="smart-form" method="post"
                      action="daviesInductionEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="daviesInductionConEmail" id="daviesInductionConEmail"
                                       value="<?php echo getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession'])); ?>"/>
                                <input type="hidden" id="daviesInductionCanId" name="daviesInductionCanId"
                                       value="<?php echo $canId; ?>" class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="daviesInductionBody"
                                          id="daviesInductionBody" placeholder=""
                                          style="width: 100%;display: none"></textarea>
                                <div id="daviesInductionEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="daviesEmailPopup" style="width:500px; display:block">
                <form id="daviesEmailFrm" name="daviesEmailFrm" class="smart-form" method="post"
                      action="daviesEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="daviesConEmail" id="daviesConEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="daviesCanId" name="daviesCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="daviesBody" id="daviesBody"
                                          placeholder="" style="width: 100%;display: none"></textarea>
                                <div id="daviesEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="covidEmailPopup" style="width:500px; display:block">
                <form id="covidEmailFrm" name="covidEmailFrm" class="smart-form" method="post" action="covidEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="covidConEmail" id="covidConEmail"
                                       value="<?php echo getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession'])); ?>"/>
                                <input type="hidden" id="covidCanId" name="covidCanId" value="<?php echo $canId; ?>"
                                       class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="covidBody" id="covidBody"
                                          placeholder="" style="width: 100%;display: none"></textarea>
                                <div id="covidEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="jotFormEmailPopup" style="width:500px; display:block">
                <form id="jotFormEmailFrm" name="jotFormEmailFrm" class="smart-form" method="post"
                      action="jotFormEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="jotFormConEmail" id="jotFormConEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="jotFormCanId" name="jotFormCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <input type="hidden" id="jotFormFullName" name="jotFormFullName"
                                       value="<?php echo getCandidateFullName($mysqli, $canId); ?>"
                                       class="form-control"/>
                                <input type="hidden" id="jotFormCanEmail" name="jotFormCanEmail"
                                       value="<?php echo base64_encode(getEmployeeEmail($mysqli, $canId)); ?>" class="form-control"/>
                                <input type="hidden" id="jotFormFirstName" name="jotFormFirstName"
                                       value="<?php echo base64_encode($firstName); ?>" class="form-control"/>
                                <input type="hidden" id="jotFormLastName" name="jotFormLastName"
                                       value="<?php echo base64_encode($lastName); ?>" class="form-control"/>
                                <input type="hidden" id="jotFormDOB" name="jotFormDOB"
                                       value="<?php echo base64_encode($dob); ?>" class="form-control"/>
                                <input type="hidden" id="jotFormMobile" name="jotFormMobile"
                                       value="<?php echo base64_encode($mobileNo); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_unit_no" name="jotForm_unit_no"
                                       value="<?php echo base64_encode($unit_no); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_street_number_1" name="jotForm_street_number_1"
                                       value="<?php echo base64_encode($street_number); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_street_name" name="jotForm_street_name"
                                       value="<?php echo base64_encode($street_name); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_suburb" name="jotForm_suburb"
                                       value="<?php echo base64_encode($suburb); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_state" name="jotForm_state"
                                       value="<?php echo base64_encode($state); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_postcode" name="jotForm_postcode"
                                       value="<?php echo base64_encode($postcode); ?>" class="form-control"/>
                                <input type="hidden" id="jotForm_address" name="jotForm_address"
                                       value="<?php echo base64_encode($address); ?>" class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="jotFormBody" id="jotFormBody"
                                          placeholder="" style="width: 100%; display: none"></textarea>
                                <div id="jotFormEmailText"></div>
                                <textarea rows="5" class="custom-scroll textarea" name="reg_instructions"
                                          id="reg_instructions" placeholder="" style="width: 100%;"></textarea>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="refFormEmailPopup" style="width:400px; display:block">
                <form id="refFormEmailFrm" name="refFormEmailFrm" class="smart-form" method="post"
                      action="refFormEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="refFormConEmail" id="refFormConEmail"
                                       value="<?php echo getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession'])); ?>"/>
                                <input type="hidden" id="refFormCanId" name="refFormCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <label for="refFormFullName">Referee Name:</label>
                                <input type="text" id="refFormFullName" name="refFormFullName" value=""
                                       class="form-control"/>
                                <label for="refFormEmail">Referee Email:</label>
                                <input type="text" id="refFormEmail" name="refFormEmail" value="" class="form-control"/>
                                <textarea rows="5" class="custom-scroll textarea" name="refFormBody" id="refFormBody"
                                          placeholder="" style="width: 100%; display: none"></textarea>
                                <div id="refFormEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="formsEmailPopup" style="width:500px; display:block">
                <form id="formsEmailFrm" name="formsEmailFrm" class="smart-form" method="post" action="formsEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="formsConEmail" id="formsConEmail"
                                       value="<?php echo getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession'])); ?>"/>
                                <input type="hidden" id="formsCanId" name="formsCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <input type="hidden" id="formsCanEmail" name="formsCanEmail"
                                       value="<?php echo getEmployeeEmail($mysqli, $canId); ?>" class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="formsEmailBody"
                                          id="formsEmailBody" placeholder=""
                                          style="width: 100%;display: none"></textarea>
                                <div id="formsEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="surveyEmailPopup" style="width:400px; display:block">
                <form id="surveyFormEmailFrm" name="surveyFormEmailFrm" class="smart-form" method="post"
                      action="surveyFormEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="surveyFormConId" id="surveyFormConId"
                                       value="<?php echo getConsultantId($mysqli, $_SESSION['userSession']); ?>"/>
                                <input type="hidden" id="surveyFormCanId" name="surveyFormCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <input type="hidden" id="surveyFormFullName" name="surveyFormFullName"
                                       value="<?php echo getCandidateFullName($mysqli, $canId); ?>"
                                       class="form-control"/>
                                <input type="hidden" id="surveyCanEmail" name="surveyCanEmail"
                                       value="<?php echo getEmployeeEmail($mysqli, $canId); ?>" class="form-control"/>
                                <textarea rows="5" class="custom-scroll textarea" name="surveyFormBody"
                                          id="surveyFormBody" placeholder=""
                                          style="width: 100%; display: none"></textarea>
                                <div id="surveyFormEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="missionEmailPopup" style="width:400px; display:block">
                <form id="missionFormEmailFrm" name="missionFormEmailFrm" class="smart-form" method="post"
                      action="missionFormEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="missionFormConEmail" id="missionFormConEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli,getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="missionFormCanId" name="missionFormCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <input type="hidden" id="missionFormFullName" name="missionFormFullName"
                                       value="<?php echo getCandidateFullName($mysqli, $canId); ?>"
                                       class="form-control"/>
                                <textarea rows="5" class="custom-scroll textarea" name="missionFormBody"
                                          id="missionFormBody" placeholder=""
                                          style="width: 100%; display: none"></textarea>
                                <div id="missionFormEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="mchefEmailPopup" style="width:400px; display:block">
                <form id="mchefFormEmailFrm" name="mchefFormEmailFrm" class="smart-form" method="post"
                      action="mchefFormEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="mchefFormConEmail" id="mchefFormConEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli,getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="mchefFormCanId" name="mchefFormCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <input type="hidden" id="mchefFormFullName" name="mchefFormFullName"
                                       value="<?php echo getCandidateFullName($mysqli, $canId); ?>"
                                       class="form-control"/>
                                <textarea rows="5" class="custom-scroll textarea" name="mchefFormBody"
                                          id="mchefFormBody" placeholder=""
                                          style="width: 100%; display: none"></textarea>
                                <div id="mchefFormEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="empVariationEmailPopup" style="width:400px; display:block">
                <form id="empVariationFormEmailFrm" name="empVariationFormEmailFrm" class="smart-form" method="post"
                      action="empVariationFormEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="empVariationFormConEmail" id="empVariationFormConEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli,getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="empVariationFormCanId" name="empVariationFormCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <input type="hidden" id="empVariationFormFullName" name="empVariationFormFullName"
                                       value="<?php echo getCandidateFullName($mysqli, $canId); ?>"
                                       class="form-control"/>
                                <textarea rows="5" class="custom-scroll textarea" name="empVariationFormBody"
                                          id="empVariationFormBody" placeholder=""
                                          style="width: 100%; display: none"></textarea>
                                <div id="empVariationFormEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="handbookEmailPopup" style="width:500px; display:block">
                <form id="handbookEmailFrm" name="handbookEmailFrm" class="smart-form" method="post"
                      action="handbookEmail.php">
                    <div class="row">
                        <section class="col col-12" style="width:100%;height:100%;">
                            <label class="textarea textarea-resizable">
                                <input type="hidden" name="handbookConEmail" id="handbookConEmail"
                                       value="<?php echo base64_encode(getConsultantEmail($mysqli, getConsultantId($mysqli, $_SESSION['userSession']))); ?>"/>
                                <input type="hidden" id="handbookCanId" name="handbookCanId"
                                       value="<?php echo base64_encode($canId); ?>" class="form-control"/>
                                <textarea rows="10" class="custom-scroll textarea" name="handbookBody"
                                          id="handbookBody" placeholder=""
                                          style="width: 100%;display: none"></textarea>
                                <div id="handbookEmailText"></div>
                            </label>
                        </section>
                    </div>
                </form>
            </div>
            <div id="activatePopup" style="width:500px; display:block">
                <div class="error" id="activateNoteError"></div>
                <textarea name="activate_note" id="activate_note" cols="30" rows="10" class="form-control" required></textarea>
            </div>
        </div>
        <!-- END MAIN CONTENT -->
    </div>
    <!-- END MAIN PANEL -->

    <!-- PAGE FOOTER -->
    <div class="page-footer">
        <div class="row">
            <div class="col-xs-12 col-sm-6">
                <span class="txt-color-white"><?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
            </div>

            <div class="col-xs-6 col-sm-6 text-right hidden-xs">
                <div class="txt-color-white inline-block">

                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE FOOTER -->
    <?php include "template/scripts.php"; ?>

    <!-- BOOTSTRAP JS
		<script src="js/bootstrap/bootstrap.min.js"></script>-->
    <!-- DATE RANGE PICKER -->
    <script type="text/javascript" src="js/daterangepicker/moment.js"></script>
    <script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
    <!-- JQUERY VALIDATE -->
    <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
    <!-- JQUERY MASKED INPUT -->
    <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
    <script type="text/javascript">
        runAllForms();
        (function() {
            var widget, initAddressFinder = function() {
                widget = new AddressFinder.Widget(
                    document.getElementById('address'),
                    'DYM7UE36AWQV8F94PKJH',
                    'AU', {
                        "address_params": {
                            "gnaf,paf" : "1",
                        }
                    }
                );

                widget.on('result:select', function(fullAddress, metaData) {
                    // need to update these ids to match those in your form
                    document.getElementById("unit_no").value = metaData.unit_identifier;
                    document.getElementById("street_number_1").value = metaData.street_number_1;
                    document.getElementById("street_name").value = metaData.street;
                    document.getElementById("suburb").value = metaData.locality_name;
                    var state = "";
                    switch (metaData.state_territory) {
                        case "ACT":
                            state = "AUSTRALIAN CAPITAL TERRITORY";
                            break;
                        case "NSW":
                            state = "NEW SOUTH WALES";
                            break;
                        case "VIC":
                            state = "VICTORIA";
                            break;
                        case "NT":
                            state = "NORTHERN TERRITORY";
                            break;
                        case "QLD":
                            state = "QUEENSLAND";
                            break;
                        case "SA":
                            state = "SOUTH AUSTRALIA";
                            break;
                        case "TAS":
                            state = "TASMANIA";
                            break;
                        case "WA":
                            state = "WESTERN AUSTRALIA";
                            break;
                    }
                    document.getElementById("state").value = state;
                    document.getElementById("postcode").value = metaData.postcode;

                });

            }

            function downloadAddressFinder() {
                var script = document.createElement('script');
                script.src = 'https://api.addressfinder.io/assets/v3/widget.js';
                script.async = true;
                script.onload = initAddressFinder;
                document.body.appendChild(script);
            };

            document.addEventListener('DOMContentLoaded', downloadAddressFinder);
            let addr_1 = document.getElementById('address')
        })();
        $(document).ready(function() {
            $('#dob').datepicker({
                dateFormat: 'dd/mm/yy',
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
            $.ajaxSetup({
                headers: {
                    'CsrfToken': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var activateDialog;
            /*********** file upload ***************/
            var options = {
                beforeSend: function () {
                    $("#progress").show();
                    //clear everything
                    $("#bar").width('0%');
                    $("#message").html("");
                    $("#percent").html("0%");
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    $("#bar").width(percentComplete + '%');
                    $("#percent").html(percentComplete + '%');

                },
                success: function () {
                    $("#bar").width('100%');
                    $("#percent").html('100%');

                },
                complete: function (response) {
                    if (response.responseText != 'Error Uploading') {
                        $('.documentsList').html(response.responseText);
                    } else {
                        $("#message").html("<font color='green'>" + response.responseText + "</font>");
                    }
                },
                error: function () {
                    $("#message").html("<font color='red'> ERROR: unable to upload files</font>");
                }
            };
            $('#validFrom').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
            $('#validFrom').datetimepicker({dateFormat: 'dd-mm-yy'});
            $(document).on('click', '#validFrom', function () {
                $('#validFrom').datetimepicker({dateFormat: 'dd-mm-yy'});
            });
            $('#validTo').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
            $('#validTo').datetimepicker({dateFormat: 'dd-mm-yy'});
            $(document).on('click', '#validTo', function () {
                $('#validTo').datetimepicker({dateFormat: 'dd-mm-yy'});
            });
            $('#reviewDate').datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: "-100:+0"
            });
            $('#reviewDate').datetimepicker({dateFormat: 'dd-mm-yy'});
            $(document).on('click', '#reviewDate', function () {
                $('#reviewDate').datetimepicker({dateFormat: 'dd-mm-yy'});
            });
            $(document).on('change', '#file', function () {
                $('#docName').val($('input[type=file]').val());
            });
            $("#frmFile").ajaxForm(options);
            /*********** end file upload ***********/
            $('.ui-autocomplete-input').css('width', '40px')
            $('#otherLicenceId').autocomplete({
                source: <?php include "./getAttributes.php"; ?>,
                select: function (event, ui) {
                    var otherLicenceType = ui.item.value;
                    var otherLicenceId = ui.item.id;
                    var candidateId = $('#canId').val();
                    $.ajax({
                        type: "POST",
                        url: "./saveAttributes.php",
                        data: {candidateId: candidateId, otherLicenceId: otherLicenceId},
                        dataType: 'html',
                        success: function (data) {
                            $('.attributesList').html(data);
                        }
                    }).done(function (data) {

                    });
                }
            });
            $(document).on('click', '.attrRemove', function () {
                var $row = $(this).closest("tr");
                var cid = $row.find('.cid').data('cid');
                var oid = $row.find('.oid').data('oid');
                $.ajax({
                    type: "POST",
                    url: "./removeAttribute.php",
                    data: {cid: cid, oid: oid},
                    dataType: 'html',
                    success: function (data) {
                        $('.attributesList').html(data);
                    }
                }).done(function (data) {

                });
            });
            $(document).on('click', '.docRemove', function () {
                var $row = $(this).closest("tr");
                var canid = $row.find('.canid').data('canid');
                var fpath = $row.find('.fpath').data('fpath');
                $.ajax({
                    type: "POST",
                    url: "./docRemove.php",
                    data: {canid: canid, fpath: fpath},
                    dataType: 'html',
                    success: function (data) {
                        $('.documentsList').html('');
                        $('.documentsList').html(data);
                    }
                }).done(function (data) {

                });
            });
            $(document).on('click', '.workpermit', function () {
                var $row = $(this).closest("tr");
                var canId = $row.find('.canid').data('canid');
                var emailpath = $row.find('.emailpath').data('emailpath');
                var action = 'WORKPERMIT';
                $.ajax({
                    type: "POST",
                    url: "./workPermit.php",
                    data: {canId: canId, emailpath: emailpath, action: action},
                    dataType: 'text',
                    success: function (data) {
                        if (data == 'SUCCESS') {
                            $('.error').html('');
                            $('.error').html('Email Sent Successfully');
                        } else {
                            $('.error').html('');
                            $('.error').html('Error sending email');
                        }
                    }
                }).done(function (data) {

                });
            });


            $(document).on('click', '.newSMS', function () {
                var candidateId = $('#canId').val();
                window.open('./smsMain.php?canId=' + candidateId + '', 'newSMS');
            });
            $(document).on('click', '.cancelBtn', function () {
                window.close();
            });
            $('#bsb').keyup(function () {
                var bsb = $(this).val().split("-").join(""); // remove hyphens
                if (bsb.length > 0) {
                    bsb = bsb.match(new RegExp('.{1,3}', 'g')).join("-");
                }
                $(this).val(bsb);
            });
            $(document).on('click', '.saveAccountBtn', function () {
                var errorClass = 'invalid';
                var errorElement = 'em';
                var frmAccount = $("#frmAccount").validate({
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
                        accountName: {
                            required: true
                        },
                        accountNumber: {
                            required: true
                        },
                        bsb: {
                            required: true
                        }
                    },
                    messages: {
                        accountName: {
                            required: "Please enter employee Bank Account Name"
                        },
                        accountNumber: {
                            required: "Please enter employee Bank Account Number"
                        },
                        bsb: {
                            required: "Please enter employee Bank Account BSB"
                        }
                    },
                    submitHandler: function (form) {
                        var bankName = $('#bankName').val();
                        var accountName = $('#accountName').val();
                        var accountNumber = $('#accountNumber').val();
                        var bsb = $('#bsb').val();
                        var canId = $('#canId').val();
                        var getAccount = 'update';
                        $.ajax({
                            type: "POST",
                            url: "./saveBankAccount.php",
                            data: {
                                canId: canId,
                                bankName:bankName,
                                accountName: accountName,
                                accountNumber: accountNumber,
                                bsb: bsb,
                                getAccount: getAccount
                            },
                            dataType: "html",
                            success: function (data) {
                                if (data == 'Required') {
                                    alert('Please fill all the fields');
                                } else if (data == 'bsb is invalid') {
                                    alert('bsb is invalid');
                                } else {
                                    $('#accountInfoBody').html('');
                                    $('#accountInfoBody').html(data);
                                    location.reload();
                                }
                            }
                        });
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
            getBankAccountInfo();

            function getBankAccountInfo() {
                var canId = $('#canId').val();
                var getAccount = 'get';
                $.ajax({
                    type: "POST",
                    url: "./saveBankAccount.php",
                    data: {canId: canId, getAccount: getAccount},
                    dataType: "html",
                    success: function (data) {
                        $('#accountInfoBody').html('');
                        $('#accountInfoBody').html(data);
                    }
                });
            }

            $(document).on('click', '.tickBtn', function (evt) {
                var errorClass = 'invalid';
                var errorElement = 'em';
                var frmMain = $("#frmMain").validate({
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
                        fName: {
                            required: true
                        },
                        lName: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        mobile: {
                            required: true
                        },
                        gender: {
                            required: true
                        },
                        foundhow: {
                            required: true
                        }
                    },
                    messages: {
                        fName: {
                            required: "Please enter candidate first name"
                        },
                        lName: {
                            required: "Please enter candidate last name"
                        },
                        email: {
                            required: "Please enter candidate email",
                            email: "Please enter a VALID email address"
                        },
                        gender: {
                            required: "Please select candidate gender"
                        },
                        mobile: {
                            required: "Please enter candidate mobile"
                        },
                        foundhow: {
                            required: "Please select how did you find us"
                        }
                    },
                    submitHandler: function (form) {
                        var address = $('textarea#address').val();
                        var street_number = $('#street_number_1').val();
                        var street_name = $('#street_name').val();
                        var suburb = $('#suburb').val();
                        var state = $('#state').val();
                        var postcode = $('#postcode').val();
                        var dob = $('#dob').val();
                        var gender = $('#gender :selected').val();
                        var mobile = $('#mobile').val();
                        var email = $('#email').val();
                        var fName = $('#fName').val();
                        var lName = $('#lName').val();
                        var nickname = $('#nickname').val();
                        var canId = $('#canId').val();
                        var foundhow = $('#foundhow :selected').val();
                        var promotion = $('#promotion :selected').val();
                        var consultantId = $('#consultant_id :selected').val();
                            $.ajax({
                                type: "POST",
                                url: "./saveCandidate.php",
                                data: {
                                    address: address,
                                    street_number: street_number,
                                    street_name: street_name,
                                    suburb: suburb,
                                    state: state,
                                    postcode: postcode,
                                    dob: dob,
                                    gender: gender,
                                    mobile: mobile,
                                    email: email,
                                    fName: fName,
                                    lName: lName,
                                    nickname: nickname,
                                    canId: canId,
                                    foundhow: foundhow,
                                    promotion: promotion,
                                    consultantId: consultantId
                                },
                                dataType: "html",
                                success: function (data) {
                                    $('#error').text('');
                                    if (data == 'Updated') {
                                        $('.error').text('User Details Updated');
                                        location.reload();
                                    } else if (data == 'Inserted') {
                                        $('.error').text('User Created Successfully');
                                        location.reload();
                                    } else if (data == 'Required') {
                                        $('.error').text('Please fill all the fields');
                                    } else if (data == '') {
                                        location.reload();
                                    } else {
                                        $('.error').text('Error Occurred ' + data);
                                    }
                                }
                            });

                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
            $(document).on('click', '#status', function () {
                if ($('input[name=status]:checked', '#frmFilter').val() !== '') {
                    var status = $(this).val();
                    var canId = $('#canId').val();
                    if (status != '') {
                        $.ajax({
                            type: "POST",
                            url: "./getNotes.php",
                            data: {status: status, canId: canId},
                            dataType: "html",
                            success: function (data) {
                                $('#notelist').html('');
                                $('#notelist').html(data);
                                $('#datatable_tabletools').dataTable({
                                    "bSort": false,
                                    "aaSorting": [[]],
                                    "bPaginate": true,
                                    "bLengthChange": false,
                                    "bFilter": false,
                                    "bInfo": false,
                                    "bStateSave": true,
                                    "fnStateSave": function (oSettings, oData) {
                                        localStorage.setItem('offersDataTables', JSON.stringify(oData));
                                    },
                                    "fnStateLoad": function (oSettings) {
                                        return JSON.parse(localStorage.getItem('offersDataTables'));
                                    }
                                });
                            }
                        });
                    }
                }
            });
            loadCandidateSMSLog();

            function loadCandidateSMSLog() {
                var smsStatus = $('input[name=smsstatus]:checked', '#frmSMS').val();
                var canId = $('#canId').val();
                $.ajax({
                    type: "POST",
                    url: "./getSMS.php",
                    data: {canId: canId, smsStatus: smsStatus},
                    dataType: "html",
                    success: function (data) {
                        $("#smsBody").html('');
                        $("#smsBody").append(data);
                        $("#smsLoading").hide();
                    }
                });
            }
            // Change the selector if needed
            var $table = $('#smsDataTable.scroll'),
                $bodyCells = $table.find('tbody tr:first').children(),
                colWidth;

            // Adjust the width of thead cells when window resizes
            $(window).resize(function () {
                // Get the tbody columns width array
                colWidth = $bodyCells.map(function () {
                    return $(this).width();
                }).get();

                // Set the width of thead columns
                $table.find('thead tr').children().each(function (i, v) {
                    $(v).width(colWidth[i]);
                });
            }).resize(); // Trigger resize handler

            setInterval(function () {
                var firstRow = $('#smsDataTable > tbody > tr:first').attr('id');//$("#smsDataTable > tr:last-child").data("data-smsid");
                var canId = $('#canId').val();
                var smsStatus = $('input[name=smsstatus]:checked', '#frmSMS').val();
                $.ajax({
                    type: "POST",
                    url: "getSMS.php",
                    data: {firstRow: firstRow, canId: canId, smsStatus: smsStatus},
                    dataType: "html"
                }).done(function (response) {

                    loadCandidateSMSLog();
                });
            }, 5000);

            $(document).on('click', '#smsstatus', function () {
                loadCandidateSMSLog();
            });

            $('.smsList').scroll(function () {
                if ($("#smsLoading").css('display') == 'none') {
                    if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                        var limitStart = $("#smsBody tr").length;
                        loadCandidateSMSLog();
                    }
                }
            });

            (function poll() {
                setTimeout(function () {
                    var canId = $('#canId').val();
                    var status = $('input[name=status]:checked').val();
                    $.ajax({
                        url: "./getNotes.php",
                        data: {canId: canId, status: status},
                        success: function (data) {
                            $('#notelist').html('');
                            $('#notelist').html(data);
                            if ($('input[name=status]:checked').val() !== '') {
                                $("input[name=status][value=" + $('input[name=status]:checked').val() + "]").prop('checked', true);
                            }
                            $('#datatable_tabletools').dataTable({
                                "bSort": false,
                                "aaSorting": [[]],
                                "bPaginate": true,
                                "bLengthChange": false,
                                "bFilter": false,
                                "bInfo": false,
                                "bStateSave": true,
                                "fnStateSave": function (oSettings, oData) {
                                    localStorage.setItem('offersDataTables', JSON.stringify(oData));
                                },
                                "fnStateLoad": function (oSettings) {
                                    return JSON.parse(localStorage.getItem('offersDataTables'));
                                }
                            });

                        },
                        dataType: "html",
                        complete: poll,
                        timeout: 3000
                    });
                }, 2000);
            })();

            getClients();

            function getClients() {
                $.ajax({
                    url: "getClients.php",
                    type: "POST",
                    dataType: "html",
                    success: function (data) {
                        $('.clientsMenu').html('');
                        $('.clientsMenu').html(data);
                    }
                });
            }

            getAllClients();

            function getAllClients() {
                var action = 'accClients';
                $.ajax({
                    url: "getClients.php",
                    type: "POST",
                    dataType: "html",
                    data: {action: action},
                    success: function (data) {
                        $('#accClientId').html('');
                        $('#accClientId').html(data);
                        setClient();
                        setPosition();
                    }
                });
            }

            $(document).on('click', '#consAuditBtn', function (evt) {
                var frmAccCheck = $('#frmAccCheck').validate({
                    rules: {
                        1: {
                            required: true
                        },
                        2: {
                            required: true
                        },
                        3: {
                            required: true
                        },
                        4: {
                            required: true
                        },
                        5: {
                            required: true
                        },
                        6: {
                            required: true
                        },
                        7: {
                            required: true
                        },
                        8: {
                            required: true
                        },
                        9: {
                            required: true
                        },
                        10: {
                            required: true
                        },
                        11: {
                            required: true
                        },
                        12: {
                            required: true
                        },
                        13: {
                            required: true
                        },
                        14: {
                            required: true
                        },
                        15: {
                            required: true
                        }
                    },
                    messages: {
                        1: {
                            required: 'required'
                        },
                        2: {
                            required: 'required'
                        },
                        3: {
                            required: 'required'
                        },
                        4: {
                            required: 'required'
                        },
                        5: {
                            required: 'required'
                        },
                        6: {
                            required: 'required'
                        },
                        7: {
                            required: 'required'
                        },
                        8: {
                            required: 'required'
                        },
                        9: {
                            required: 'required'
                        },
                        10: {
                            required: 'required'
                        },
                        11: {
                            required: 'required'
                        },
                        12: {
                            required: 'required'
                        },
                        13: {
                            required: 'required'
                        },
                        14: {
                            required: 'required'
                        },
                        15: {
                            required: 'required'
                        }
                    },
                    submitHandler: function (form) {
                        if ($('#jobOrderNotify').prop('checked') == true) {
                            var jobOrderNotify = 1;
                            var clientid = $('#accClientId :selected').val();
                            var positionid = $('#accPositionId :selected').val();
                            var canId = $('#canId').val();
                            var action = 'MAIL';
                            $.ajax({
                                type: "POST",
                                url: "./verifyAccounts.php",
                                data: {
                                    canId: canId,
                                    action: action,
                                    clientid: clientid,
                                    positionid: positionid,
                                    jobOrderNotify: jobOrderNotify
                                },
                                dataType: 'text',
                                success: function (data) {
                                    $('.auditChkError').html('');
                                    $('.auditChkError').html(data);
                                    getAllClients();
                                }
                            });
                        } else {
                            $('.auditChkError').html('');
                            $('.auditChkError').html('Please tick to agree selection');
                            alert('Please tick to agree selection');
                        }
                    }
                });
            });
            $(document).on('change', '#accClientId', function () {
                getClientPositions();
            });

            function getClientPositions() {
                var clientid = $('#accClientId :selected').val();
                var dropSelect = 'N';
                $.ajax({
                    url: "getClientPositionsList.php",
                    type: "POST",
                    dataType: "html",
                    data: {clientid: clientid, dropSelect: dropSelect},
                    success: function (data) {
                        $('#accPositionId').html('');
                        $('#accPositionId').html(data);
                    }
                });
            }

            function setClient() {
                var action = 'CLIENT';
                var canId = $('#canId').val();
                $.ajax({
                    url: "verifyAccounts.php",
                    type: "POST",
                    dataType: "text",
                    data: {action: action, canId: canId},
                    success: function (data) {
                        $('#accClientId option').each(function () {
                            if ($(this).val() === data) {
                                $(this).prop("selected", true);
                                getClientPositions();
                            }
                        });
                    }
                });
            }

            function setPosition() {
                var action = 'POSITION';
                var canId = $('#canId').val();
                $.ajax({
                    url: "verifyAccounts.php",
                    type: "POST",
                    dataType: "text",
                    data: {action: action, canId: canId},
                    success: function (data) {
                        $('#accPositionId option').each(function () {
                            if ($(this).val() === data) {
                                $(this).prop("selected", true);
                            }
                        });
                    }
                });
            }

            loadAuditCheck();

            function loadAuditCheck() {
                var canId = $('#canId').val();
                var action = 'GET';
                $.ajax({
                    type: "POST",
                    url: "./verifyAccounts.php",
                    data: {canId: canId, action: action},
                    dataType: 'html',
                    success: function (data) {
                        $('#accCheck').html('');
                        $('#accCheck').html(data);
                        getAllClients();
                        if($('input:radio[name=12]:checked').val() == 'No') {
                            $('.ref29').show();
                        }else {
                            $('.ref29').hide();
                        }
                    }
                }).done(function (data) {
                });
            }

            loadFinanceCheck();

            function loadFinanceCheck() {
                var canId = $('#canId').val();
                var action = 'GET';
                $.ajax({
                    type: "POST",
                    url: "./verifyFinance.php",
                    data: {canId: canId, action: action},
                    dataType: 'html',
                    success: function (data) {
                        $('#financeCheck').html('');
                        $('#financeCheck').html(data);
                    }
                }).done(function (data) {
                });
            }

            $(document).on('click', '.financeRadio', function () {
                var canId = $('#canId').val();
                var chkTypeSelection = $(this).val();
                var chkTypeName = $(this).attr('name');
                var action = 'UPDATE';
                $.ajax({
                    type: "POST",
                    url: "./verifyFinance.php",
                    data: {canId: canId, action: action, chkTypeSelection: chkTypeSelection, chkTypeName: chkTypeName},
                    dataType: 'html',
                    success: function (data) {
                        if ((data == 'added') || (data == 'updated')) {
                            loadFinanceCheck();
                        }
                    }
                }).done(function (data) {
                });
            });
            $(document).on('click', '.consRadio', function () {
                var canId = $('#canId').val();
                var chkTypeSelection = $(this).val();
                var chkTypeName = $(this).attr('name');
                var action = 'UPDATE';
                if(chkTypeName == '12'){
                    if(chkTypeSelection == 'No'){
                        $('.ref29').show();
                    }
                }
                $.ajax({
                    type: "POST",
                    url: "./verifyAccounts.php",
                    data: {canId: canId, action: action, chkTypeSelection: chkTypeSelection, chkTypeName: chkTypeName},
                    dataType: 'html',
                    success: function (data) {
                        if ((data == 'added') || (data == 'updated')) {
                            loadAuditCheck();
                            getAllClients();
                        }
                    }
                }).done(function (data) {

                });
            });
            $(document).on('click', '.accRadio', function () {
                var canId = $('#canId').val();
                var chkTypeSelection = $(this).val();
                var chkTypeName = $(this).attr('name');
                var clientid = $('#accClientId :selected').val();
                var positionid = $('#accPositionId :selected').val();
                var action = 'PAYROLL';
                /*if(chkTypeName == '12-P'){
                    if(chkTypeSelection == 'No'){
                        $('.ref29').show();
                    }else{
                        $('.ref29').hide();
                    }
                }*/
                $.ajax({
                    type: "POST",
                    url: "./verifyAccounts.php",
                    data: {
                        canId: canId,
                        action: action,
                        chkTypeSelection: chkTypeSelection,
                        chkTypeName: chkTypeName,
                        clientid: clientid,
                        positionid: positionid
                    },
                    dataType: 'html',
                    success: function (data) {
                        console.log('....' + chkTypeSelection);
                        if ((data == 'added') || (data == 'updated')) {
                            loadAuditCheck();
                            getAllClients();
                        }
                    }
                }).done(function (data) {

                });
            });

            $(document).on('click', '.clientsMenu', function () {
                var clientId = $('#clientId :selected').val();
                $.ajax({
                    url: "getStateByClient.php",
                    type: "POST",
                    dataType: "html",
                    data: {clientId: clientId},
                    success: function (data) {
                        $('#stateId').html('');
                        $('#stateId').html(data);
                    }
                });
            });
            $(document).on('change', '.clientsMenu', function () {
                var clientId = $('#clientId :selected').val();
                $.ajax({
                    url: "getStateByClient.php",
                    type: "POST",
                    dataType: "html",
                    data: {clientId: clientId},
                    success: function (data) {
                        $('#stateId').html('');
                        $('#stateId').html(data);
                    }
                });
            });
            $(document).on('click', '#stateId', function () {
                var clientId = $('#clientId :selected').val();
                var stateId = $('#stateId :selected').val();
                $.ajax({
                    url: "getDepartment.php",
                    type: "POST",
                    dataType: "html",
                    data: {clientId: clientId, stateId: stateId},
                    success: function (data) {
                        $('#deptId').html('');
                        $('#deptId').html(data);
                    }
                });
            });
            $(document).on('change', '#stateId', function () {
                var clientId = $('#clientId :selected').val();
                var stateId = $('#stateId :selected').val();
                $.ajax({
                    url: "getDepartment.php",
                    type: "POST",
                    dataType: "html",
                    data: {clientId: clientId, stateId: stateId},
                    success: function (data) {
                        $('#deptId').html('');
                        $('#deptId').html(data);
                    }
                });
            });
            $(document).on('change', '#deptId', function () {
                var clientId = $('#clientId :selected').val();
                var stateId = $('#stateId :selected').val();
                var deptId = $('#deptId :selected').val();
                var candidateId = $('#canId').val();
                var priorityId = $('#empPriority :selected').val();
                if (deptId != 0) {
                    $.ajax({
                        url: "allocateEmployee.php",
                        type: "POST",
                        dataType: "html",
                        data: {
                            candidateId: candidateId,
                            clientId: clientId,
                            stateId: stateId,
                            deptId: deptId,
                            priorityId: priorityId
                        },
                        success: function (data) {
                            $('#allocateBody').html('');
                            $('#allocateBody').html(data);
                        }
                    });
                }
            });
            listAllocations();

            function listAllocations() {
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "listAllocations.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId},
                    success: function (data) {
                        $('#allocateBody').html('');
                        $('#allocateBody').html(data);
                    }
                });
            }

            $(document).on('click', '.includeAllocationBtn', function () {
                var $row = $(this).closest("tr");
                var allid = $row.find('.allid').data('allid');
                var status = '1';
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "activateAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {status: status, allid: allid, candidateId: candidateId},
                    success: function (data) {
                        $('#allocateBody').html('');
                        $('#allocateBody').html(data);
                    }
                });
            });
            $(document).on('click','.updateRiteq',function (){
                let $row = $(this).closest("tr");
                let riteq_id = $row.find('.riteq_id').val();
                let candidateId = $('#canId').val();
                let action = 'riteq';
                $.ajax({
                    url: "activateAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {action:action,riteq_id:riteq_id, candidateId: candidateId},
                    success: function () {
                        listAllocations();
                    }
                });
            });
            $(document).on('click','.updateChronus',function (){
                let $row = $(this).closest("tr");
                let chronus_id = $row.find('.chronus_id').val();
                let candidateId = $('#canId').val();
                let action = 'chronus';
                $.ajax({
                    url: "activateAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {action:action,chronus_id:chronus_id, candidateId: candidateId},
                    success: function () {
                        listAllocations();
                    }
                });
            });
            $(document).on('click','#casualStatBtn',function (){
                let casual_status = $('textarea#casual_status').val();
                let candidateId = $('#canId').val();
                let action = 'casualStatus';
                $.ajax({
                    url: "activateAllocation.php",
                    type: "POST",
                    dataType: "text",
                    data: {casual_status:casual_status, candidateId: candidateId, action:action},
                    success: function () {
                         location.reload();
                    }
                });
            });

            $(document).on('click','.excludeAllAllocationsBtn', function () {
                $('.excludeAllocationBtn').trigger('click');
            });
            $(document).on('click', '.excludeAllocationBtn', function () {
                var $row = $(this).closest("tr");
                var allid = $row.find('.allid').data('allid');
                var status = '0';
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "activateAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {status: status, allid: allid, candidateId: candidateId},
                    success: function (data) {
                        $('#allocateBody').html('');
                        $('#allocateBody').html(data);
                    }
                });
            });
            $(document).on('click', '.removeAllocationBtn', function () {
                var $row = $(this).closest("tr");
                var allid = $row.find('.allid').data('allid');
                var status = '0';
                var candidateId = $('#canId').val();
                var action = 'remove';
                $.ajax({
                    url: "activateAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {status: status, allid: allid, candidateId: candidateId, action: action},
                    success: function (data) {
                        $('#allocateBody').html('');
                        $('#allocateBody').html(data);
                    }
                });
            });
            populatePositions();
            function populatePositions() {
                $.ajax({
                    url: "getCandidatePositionList.php",
                    type: "POST",
                    dataType: "html",
                    success: function (data) {
                        $('#positionid').html('');
                        $('#positionid').html(data);
                    }
                });
            }
            $(document).on('change', '#positionid', function () {
                var positionid = $('#positionid :selected').val();
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "assignPosition.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, positionid: positionid},
                    success: function (data) {
                        $('#positionList').html('');
                        $('#positionList').html(data);
                    }
                });
            });
            $(document).on('click', '#removeEmpPositionBtn', function () {
                var $row = $(this).closest("tr");
                var positionid = $row.find('.positionid').data('positionid');
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "removeAssignedPosition.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, positionid: positionid},
                    success: function (data) {
                        $('#positionList').html('');
                        $('#positionList').html(data);
                    }
                });
            });
            $('#reportrange').daterangepicker({
                "autoApply": true,
                startDate: start,
                endDate: end,
                ranges: {}
            }, cb);
            var start = moment().subtract(29, 'days');
            var end = moment();
            cb(start, end);
            function cb(start, end) {
                $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                $('#strDate').val(start.format('YYYY-MM-DD'));
                $('#endDate').val(end.format('YYYY-MM-DD'));
            }
            $('#shiftrange').daterangepicker({
                "autoApply": true,
                stDate: st,
                enDate: en,
                ranges: {}
            }, calendarset);
            var st = moment().subtract(7, 'days');
            var en = moment();
            calendarset(st, en);
            function calendarset(st, en) {
                $('#shiftrange span').html(st.format('MMMM D, YYYY') + ' - ' + en.format('MMMM D, YYYY'));
                $('#stDate').val(st.format('YYYY-MM-DD'));
                $('#enDate').val(en.format('YYYY-MM-DD'));
            }
            $(document).on('click', '#viewShiftsBtn', function () {
                var stDate = $('#stDate').val();
                var enDate = $('#enDate').val();
                var canId = $('#canId').val();
                $.ajax({
                    url: "getCandidateShiftData.php",
                    type: "POST",
                    dataType: "html",
                    data: {stDate: stDate, enDate: enDate, canId: canId},
                    success: function (data) {
                        if (data) {
                            $('#shiftDisplay').html('');
                            $('#shiftDisplay').html(data);
                        }
                    }
                });
            });
            $(document).on('click', '.addDateRangeBtn', function () {
                var strDate = $('#strDate').val();
                var endDate = $('#endDate').val();
                var canId = $('#canId').val();
                $.ajax({
                    url: "updateShiftAvailability.php",
                    type: "POST",
                    dataType: "text",
                    data: {strDate: strDate, endDate: endDate, canId: canId},
                    success: function (data) {
                        if (data) {
                            $('#unDisplay').html('');
                            $('#unDisplay').html(data);
                        }
                    }
                });
            });
            $(document).on('click', '.removeAvailabilityBtn', function () {
                var canId = $('#canId').val();
                var removeStatus = 'remove';
                $.ajax({
                    url: "updateShiftAvailability.php",
                    type: "POST",
                    dataType: "text",
                    data: {removeStatus: removeStatus, canId: canId},
                    success: function (data) {
                        if (data) {
                            $('#unDisplay').html('');
                            $('#unDisplay').html(data);
                        }
                    }
                });
            });
            $(document).on('click', '.rosterNoteBtn', function () {
                var errorClass = 'invalid';
                var errorElement = 'em';
                var rosterFrm = $("#frmNotes").validate({
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
                        rosternote: {
                            required: true
                        }
                    },
                    messages: {
                        rosternote: {
                            required: "Please enter roster note text"
                        }
                    },
                    submitHandler: function (form) {
                        var rosternote = $('textarea#rosternote').val();
                        var canid = $('#canId').val();
                        $.ajax({
                            type: "POST",
                            url: "./saveRosterNote.php",
                            data: {rosternote: rosternote, canid: canid},
                            dataType: "html",
                            success: function (data) {
                                if (data) {
                                    $('textarea#rosternote').val(data);
                                }
                            }
                        });
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
            var canId = $('#canId').val();
            activateDialog = $('#activatePopup').dialog({
                autoOpen: false,
                height: 200,
                width: 400,
                modal: true,
                title:"Active/Inactive",
                open: function(event, ui) {
                    $("#activatePopup").css({'overflow':'hidden'});
                },
                buttons: {
                    Send: function(){
                        $('#activateNoteError').html('');
                        if($('textarea#activate_note').val() !== '') {
                            var status = '';
                            var btnStatus = $('#activateBtn').val();
                            if (btnStatus == 'ACTIVE') {
                                status = 'INACTIVE';
                            } else {
                                status = 'ACTIVE';
                            }
                            var activate_note = $('textarea#activate_note').val();
                            $.ajax({
                                type: "POST",
                                url: "./activateCandidate.php",
                                data: {status: status, canId: canId,activate_note:activate_note},
                                dataType: "text",
                                success: function (data) {
                                    $('#activateBtn').val(data);
                                    $('#activateBtn').html(data);
                                    getActivateInactivateNotes(canId)
                                    activateDialog.dialog("close");
                                }
                            });
                        }else{
                            $('#activateNoteError').html('Please add reason for activate/inactivate');
                        }
                    },
                    Cancel: function() {
                        activateDialog.dialog("close");
                    }
                }
            });
            $(document).on('click', '#activateBtn', function () {
                activateDialog.dialog("open");
                jQuery('.ui-dialog-titlebar-close').removeClass("ui-dialog-titlebar-close").html('<span style="font-weight: bold; width: 2px; height: 2px;">x</span>');

            });
            getPersonAuditedBy(canId);
            getActivateInactivateNotes(canId);
            $(document).on('click', '.ohsViewBtn', function () {
                var $this = $(this);
                var $row = $(this).closest("tr");
                var candid = $row.find('.candid').data('candid');
                var stid = $row.find('.stid').data('stid');
                var did = $row.find('.did').data('did');
                var clid = $row.find('.clid').data('clid');
                var status = '';
                var btnStatus = $('#ohsViewBtn+clid+stid+did').val();
                if (btnStatus == 'CHECKED') {
                    status = 'NOT CHECKED';
                } else {
                    status = 'CHECKED';
                }
                $.ajax({
                    type: "POST",
                    url: "./ohsCheck.php",
                    data: {status: status, candid: candid, stid: stid, did: did, clid: clid},
                    dataType: "text",
                    async: true,
                    success: function (data) {
                        $this.val(data);
                        $this.html(data);
                    }
                });
            });
            function getPersonAuditedBy(canId) {
                $.ajax({
                    type: "POST",
                    url: "./getAuditedPerson.php",
                    data: {canId: canId},
                    dataType: "text",
                    success: function (data) {
                        $('.auditedPerson').html('');
                        $('.auditedPerson').html(data);
                    }
                });
            }
            function getActivateInactivateNotes(canId){
                $.ajax({
                    type: "POST",
                    url: "./getActivateInactivateNotes.php",
                    data: {canId: canId},
                    dataType: "text",
                    success: function (data) {
                        $('.activate_inactivate_notes').html('');
                        $('.activate_inactivate_notes').html(data);
                    }
                });
            }
            $(document).on('click', '#accAuditBtn', function () {
                var frmAccCheck = $('#frmAccCheck').validate({
                    rules: {
                        '1-P': {
                            required: true
                        },
                        '2-P': {
                            required: true
                        },
                        '3-P': {
                            required: true
                        },
                        '4-P': {
                            required: true
                        },
                        '5-P': {
                            required: true
                        },
                        '6-P': {
                            required: true
                        },
                        '7-P': {
                            required: true
                        },
                        '8-P': {
                            required: true
                        },
                        '9-P': {
                            required: true
                        },
                        '10-P': {
                            required: true
                        },
                        '11-P': {
                            required: true
                        },
                        '12-P': {
                            required: true
                        },
                        '13-P': {
                            required: true
                        },
                        '14-P': {
                            required: true
                        },
                        '15-P': {
                            required: true
                        },
                        '16-P': {
                            required: true
                        },
                        '17-P': {
                            required: true
                        },
                        '18-P': {
                            required: true
                        }
                    },
                    messages: {
                        '1-P': {
                            required: 'required'
                        },
                        '2-P': {
                            required: 'required'
                        },
                        '3-P': {
                            required: 'required'
                        },
                        '4-P': {
                            required: 'required'
                        },
                        '5-P': {
                            required: 'required'
                        },
                        '6-P': {
                            required: 'required'
                        },
                        '7-P': {
                            required: 'required'
                        },
                        '8-P': {
                            required: 'required'
                        },
                        '9-P': {
                            required: 'required'
                        },
                        '10-P': {
                            required: 'required'
                        },
                        '11-P': {
                            required: 'required'
                        },
                        '12-P': {
                            required: 'required'
                        },
                        '13-P': {
                            required: 'required'
                        },
                        '14-P': {
                            required: 'required'
                        },
                        '15-P': {
                            required: 'required'
                        },
                        '15-P': {
                            required: 'required'
                        },
                        '16-P': {
                            required: 'required'
                        },
                        '17-P': {
                            required: 'required'
                        },
                        '18-P': {
                            required: 'required'
                        }
                    },
                    submitHandler: function (form) {
                        var auditStatus = '';
                        var btnStatus = $('#accAuditBtn').val();
                        if (btnStatus == 'AUDIT COMPLETE') {
                            auditStatus = 'AUDIT INCOMPLETE';
                        } else {
                            auditStatus = 'AUDIT COMPLETE';
                        }
                        var canId = $('#canId').val();
                        $.ajax({
                            type: "POST",
                            url: "./activateCandidate.php",
                            data: {auditStatus: auditStatus, canId: canId},
                            dataType: "text",
                            success: function (data) {
                                $('#accAuditBtn').val(data);
                                $('#accAuditBtn').html(data);
                                getPersonAuditedBy(canId);
                                getActivateInactivateNotes(canId);
                            }
                        });
                    }
                });
            });
            $(document).on('change', '#transCode', function () {
                var transCode = $('#transCode :selected').val();
                var candidateId = $('#canId').val();
                if (transCode != 'None') {
                    $.ajax({
                        url: "assignSuperFund.php",
                        type: "POST",
                        dataType: "html",
                        data: {candidateId: candidateId, transCode: transCode},
                        success: function (data) {
                            //console.log('SUPER'+data);
                            $('#superFundBody').html('');
                            $('#superFundBody').html(data);
                        }
                    });
                }
            });
            $(document).on('click', '#btnMemberNo', function () {
                console.log('memberNo clicked');
                var candidateId = $('#canId').val();
                var memberNo = $('#memberNo').val();
                $.ajax({
                    url: "processMemberNo.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, memberNo: memberNo},
                    success: function (data) {
                        console.log('SUPER' + data);
                        /*$('#').html('');
                             $('#').html(data);*/
                    }
                });
            });
            $(document).on('click', '#removeSuperFundBtn', function () {
                var candidateId = $(this).closest('td').attr('data-candid');
                var transCode = $(this).closest('td').attr('data-transCode');
                $.ajax({
                    url: "removeSuperFundAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, transCode: transCode},
                    success: function (data) {
                        //console.log('REMOVE SUPER'+data);
                        $('#superFundBody').html('');
                        $('#superFundBody').html(data);
                    }
                });
            });
            listSuperFundAllocations();
            function listSuperFundAllocations() {
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "getSuperFundsList.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId},
                    success: function (data) {
                        //console.log('LIST SUPER'+data);
                        $('#superFundBody').html('');
                        $('#superFundBody').html(data);
                    }
                });
            }
            listSuperFundTypes();
            function listSuperFundTypes() {
                $.ajax({
                    url: "listSuperFundTypes.php",
                    type: "POST",
                    dataType: "html",
                    success: function (data) {
                        $('#transCode').html('');
                        $('#transCode').html(data);
                    }
                });
            }
            listCarPool();
            function listCarPool() {
                $.ajax({
                    url: "listCarPool.php",
                    type: "POST",
                    dataType: "html",
                    success: function (data) {
                        $('#carpoolId').html('');
                        $('#carpoolId').html(data);
                    }
                })
            }
            listVisaTypes();
            function listVisaTypes() {
                $.ajax({
                    url: "listVisaType.php",
                    type: "POST",
                    dataType: "html",
                    success: function (data) {
                        $('#visaType').html('');
                        $('#visaType').html(data);
                    }
                })
            }
            $(document).on('change', '#visaType', function () {
                var visaTypeId = $('#visaType :selected').val();
                var candidateId = $('#canId').val();
                if (visaTypeId != 'None') {
                    $.ajax({
                        url: "assignVisaType.php",
                        type: "POST",
                        dataType: "html",
                        data: {candidateId: candidateId, visaTypeId: visaTypeId},
                        success: function (data) {
                            $('#visaInfoBody').html('');
                            $('#visaInfoBody').html(data);
                        }
                    });
                }
            });
            $(document).on('click', '.removeVisaTypeBtn', function () {
                var candidateId = $(this).closest('td').attr('data-candid');
                var empVisaTypeId = $(this).closest('td').attr('data-empvisatypeid');
                $.ajax({
                    url: "removeVisaTypeAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, empVisaTypeId: empVisaTypeId},
                    success: function (data) {
                        $('#visaInfoBody').html('');
                        $('#visaInfoBody').html(data);
                    }
                });
            });
            $('input[name="visaExpiryDate"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                autoUpdateInput: false,
                minYear: 1901,
                maxYear: 2050
            });
            $('input[name="visaExpiryDate"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('YYYY-MM-DD'));
                console.log('Date Selected' + picker.startDate.format('YYYY-MM-DD'));
                $('#visaExpiryDate').val(picker.startDate.format('YYYY-MM-DD'));
            });
            $(document).on('click', '#visaExpBtn', function () {
                var empVisaTypeId = $(this).closest('td').next('td').attr('data-empvisatypeid');
                var visaExpDate = $('#visaExpiryDate').val();
                $.ajax({
                    url: "updateEmployeeVisaType.php",
                    type: "POST",
                    dataType: "html",
                    data: {empVisaTypeId: empVisaTypeId, visaExpDate: visaExpDate},
                    success: function (data) {
                        if (data == 'updated') {
                            location.reload();
                        }
                    }
                });
            });
            $(document).on('change', '#carpoolId', function () {
                var carPoolId = $('#carpoolId :selected').val();
                var candidateId = $('#canId').val();
                if (carpoolId != 'None') {
                    $.ajax({
                        url: "assignCarPool.php",
                        type: "POST",
                        dataType: "html",
                        data: {candidateId: candidateId, carPoolId: carPoolId},
                        success: function (data) {
                            $('#carList').html('');
                            $('#carList').html(data);
                        }
                    });
                }
            });
            $(document).on('click', '.removeCarPoolBtn', function () {
                var candidateId = $(this).closest('td').attr('data-candid');
                var empPoolId = $(this).closest('td').attr('data-emppoolid');
                console.log('RC' + candidateId + 'del' + empPoolId);
                $.ajax({
                    url: "removeCarPoolAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, empPoolId: empPoolId},
                    success: function (data) {
                        console.log('REMOVE CARPOOL' + data);
                        $('#carList').html('');
                        $('#carList').html(data);
                    }
                });
            });
            listTaxFormulaTypes();
            function listTaxFormulaTypes() {
                $.ajax({
                    url: "listTaxFormulaTypes.php",
                    type: "POST",
                    dataType: "html",
                    success: function (data) {
                        $('#taxcode').html('');
                        $('#taxcode').html(data);
                    }
                });
            }
            listTaxCodeAllocations();
            function listTaxCodeAllocations() {
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "getTaxCodesList.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId},
                    success: function (data) {
                        $('#taxcodeBody').html('');
                        $('#taxcodeBody').html(data);
                    }
                });
            }
            $(document).on('change', '#taxcode', function () {
                var taxcode = $('#taxcode :selected').val();
                var candidateId = $('#canId').val();
                if (taxcode != 'None') {
                    $.ajax({
                        url: "assignTaxType.php",
                        type: "POST",
                        dataType: "html",
                        data: {candidateId: candidateId, taxcode: taxcode},
                        success: function (data) {
                            $('#taxcodeBody').html('');
                            $('#taxcodeBody').html(data);
                        }
                    });
                }
            });
            $(document).on('click', '#removeTaxCodeBtn', function () {
                var candidateId = $(this).closest('td').attr('data-candid');
                var taxcode = $(this).closest('td').attr('data-taxcode');
                $.ajax({
                    url: "removeTaxCodeAllocation.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId, taxcode: taxcode},
                    success: function (data) {
                        $('#taxcodeBody').html('');
                        $('#taxcodeBody').html(data);
                    }
                });
            });
            var candidateId = $('#canId').val();
            displayTFN(candidateId);
            function displayTFN(candidateId) {
                $.ajax({
                    url: "getTFN.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId},
                    success: function (data) {
                        $('#taxfileBody').html('');
                        $('#taxfileBody').html(data);
                    }
                });
            }
            $(document).on('click', '.tfnBtn', function () {
                var errorClass = 'invalid';
                var errorElement = 'em';
                var tfnFrm = $("#tfnFrm").validate({
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
                        tfn: {
                            required: true
                        }
                    },
                    messages: {
                        tfn: {
                            required: "Please enter TFN No"
                        }
                    },
                    submitHandler: function (form) {
                        var tfn = $('#tfn').val();
                        var candidateId = $('#canId').val();
                        $.ajax({
                            url: "addTFN.php",
                            type: "POST",
                            dataType: "html",
                            data: {candidateId: candidateId, tfn: tfn},
                            success: function (data) {
                                $('#taxfileBody').html('');
                                $('#taxfileBody').html(data);
                            }
                        });
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            });
            $(document).on('click', '.removePaySlipBtn', function () {
                var removeId = $(this).closest("tr").attr('id');
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "removePayslip.php",
                    type: "POST",
                    dataType: "html",
                    data: {removeId: removeId, candidateId: candidateId},
                    success: function (data) {
                        $('#payslipTblBody').html('');
                        $('#payslipTblBody').html(data);
                    }
                });
            });
            $(document).on('click', '.appEmailBtn', function () {
                var candidateId = $('#canId').val();
                $.ajax({
                    url: "genAppEmail.php",
                    type: "POST",
                    dataType: "html",
                    data: {candidateId: candidateId},
                    success: function (data) {
                        $('#errorMsg').html('');
                        $('#errorMsg').html(data);
                    }
                });
            });
            $(document).on('click', '.tfnCheck', function () {
                var tfn = $(this).attr('data-profiletfn');
                var canId = $('#canId').val();
                console.log('tfn..' + tfn);
                $.ajax({
                    url: "tfn_check_audit.php",
                    type: "POST",
                    dataType: "html",
                    data: {tfn: tfn, canId: canId},
                    success: function (data) {
                        $('#tfnCheckResult').html('');
                        $('#tfnCheckResult').html(data);
                    }
                });
            });
            $(document).on('click','#recruitmentStatusBtn', function (){
                let rec_status = $('#rec_status :selected').val();
                let canId = $('#canId').val();
                $.ajax({
                    url: "process_rec_status.php",
                    type: "POST",
                    dataType: "text",
                    data: {rec_status: rec_status, canId: canId},
                    success: function (data) {
                        location.reload();
                    }
                });
            });
            $(document).on('click','#appVersionBtn', function(){
                let canId = $('#canId').val();
                let mobile_os = $('#mobile_os :selected').val();
                let os_version = $('#os_version :selected').val();
                let action = 'UPDATE';
                $.ajax({
                    url: "appVersionProcess.php",
                    type: "POST",
                    dataType: "text",
                    data: {mobile_os: mobile_os,os_version:os_version, canId: canId,action:action},
                    success: function (data) {
                        if(data == 'UPDATED') {
                            location.reload();
                        }else{
                            $('#errorMsg').html(data);
                        }
                    }
                });
            });
            $(document).on('click','.sendFairWorkInfoBtn', function(){
                let canId = $('#canId').val();
                let action = 'FairWorkInfo';
                $.ajax({
                    url: "sendFairWorkInfo.php",
                    type: "POST",
                    dataType: "text",
                    data: { canId:canId,action:action},
                    success: function(data){
                        if (data === 'MAILSENT'){
                            alert('Email generated successfully');
                        }else{
                            alert('Error generating email');
                        }
                    }
                }).done(function(data) {
                });
            });
        });
    </script>
    <div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>