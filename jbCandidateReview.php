<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$messageId = htmlentities($_REQUEST['messageid']);
$firstName = '';
$lastName = '';
$emailAddress = '';
$phoneNumber = '';
$candidateMailFrom = retrieveCandidateName($mysqli,$messageId,'jobboard');
if(!empty($candidateMailFrom)){
    $str = explode('via',$candidateMailFrom);
    $fullName = explode(' ',$str[0]);
    $firstName = trim($fullName[0]);
    $lastName = $fullName[1].' '.$fullName[2];
}
$msgBody = retrieveCandidateMsgBody($mysqli,$messageId,'jobboard');
//$emailAddress = retrieveResumeEmail($mysqli,$messageId,'jobboard');
//$phoneNumber =  retrieveResumeMobile($mysqli,$messageId,'jobboard');

$msgPart = strrpos($msgBody,'Name:');
if ($msgPart !== false) {
    $nameParts = substr($msgBody,$msgPart + 5);
    $nmPart = explode('<br>',$nameParts);
    $fullNamePart = trim($nmPart[0]);
    $fullNameExt = explode(' ',$fullNamePart);
    $firstName = $fullNameExt[0];
    $lastName = $fullNameExt[1];

}

$msgPart1 = strrpos($msgBody,'Email:');
if ($msgPart1 !== false) {
    $emailParts = substr($msgBody,$msgPart1 + 6);
    $emPart = explode('<br>',$emailParts);
    $emailAddress = trim($emPart[0]);
}

$msgPart2 = strrpos($msgBody,'Phone Number:');
if ($msgPart2 !== false) {
    $phoneParts = substr($msgBody,$msgPart2 + 13);
    $phPart = explode('<br>',$phoneParts);
    $phoneNumber = trim($phPart[0]);
}

$matchPercentage = '';
if(empty($messageId)){

}else{
    $stmt = $mysqli->prepare("SELECT DISTINCT
			candidate.firstName,
			candidate.lastName,
			candidate.address,
			candidate.homePhoneNo,
			candidate.mobileNo,
			candidate.email,
			candidate.consultantId,
			candidate.messageid,
			candidate.candidateId,
			candidate.screenDate,
			candidate.sex
		  FROM
			candidate
			LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
			AND (candidate.messageid = reg_candidate.messageId)
			AND (candidate.firstName = reg_candidate.firstName)
			AND (candidate.lastName = reg_candidate.lastName)
			AND (candidate.mobileNo = reg_candidate.mobile)
			AND (candidate.address = reg_candidate.homeAddress)
			AND (candidate.email = reg_candidate.email)
		  WHERE
			candidate.firstName = ? AND 
			candidate.lastName = ? AND 
			candidate.mobileNo = ? AND 
			candidate.email = ?");

    $stmt->bind_param("ssss",$firstName,$lastName,$phoneNumber,$emailAddress) or die($mysqli->error);
    $stmt->execute();
    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
    $stmt->store_result();
    $num_of_rows = $stmt->num_rows;
    if($num_of_rows > 0){
        $matchPercentage = ((4/4)*100).'%';
    }else{
        $stmt->free_result();
        $stmt = $mysqli->prepare("SELECT DISTINCT
				candidate.firstName,
				candidate.lastName,
				candidate.address,
				candidate.homePhoneNo,
				candidate.mobileNo,
				candidate.email,
				candidate.consultantId,
				candidate.messageid,
				candidate.candidateId,
				candidate.screenDate,
				candidate.sex
			  FROM
				candidate
				LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
				AND (candidate.messageid = reg_candidate.messageId)
				AND (candidate.firstName = reg_candidate.firstName)
				AND (candidate.lastName = reg_candidate.lastName)
				AND (candidate.mobileNo = reg_candidate.mobile)
				AND (candidate.address = reg_candidate.homeAddress)
				AND (candidate.email = reg_candidate.email)
			  WHERE
				candidate.firstName = ? AND 
				candidate.lastName = ? AND 
				candidate.mobileNo = ?");
        $stmt->bind_param("sss",$firstName,$lastName,$phoneNumber) or die($mysqli->error);
        $stmt->execute();
        $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
        $stmt->store_result();
        $num_of_rows = $stmt->num_rows;
        if($num_of_rows > 0){
            $matchPercentage = ((3/4)*100).'%';
        }else{
            $stmt->free_result();
            $stmt = $mysqli->prepare("SELECT DISTINCT
					candidate.firstName,
					candidate.lastName,
					candidate.address,
					candidate.homePhoneNo,
					candidate.mobileNo,
					candidate.email,
					candidate.consultantId,
					candidate.messageid,
					candidate.candidateId,
					candidate.screenDate,
					candidate.sex
				  FROM
					candidate
					LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
					AND (candidate.messageid = reg_candidate.messageId)
					AND (candidate.firstName = reg_candidate.firstName)
					AND (candidate.lastName = reg_candidate.lastName)
					AND (candidate.mobileNo = reg_candidate.mobile)
					AND (candidate.address = reg_candidate.homeAddress)
					AND (candidate.email = reg_candidate.email)
				  WHERE
					candidate.firstName = ? AND 
					candidate.lastName = ? AND 
					candidate.email = ?");
            $stmt->bind_param("sss",$firstName,$lastName,$emailAddress) or die($mysqli->error);
            $stmt->execute();
            $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
            $stmt->store_result();
            $num_of_rows = $stmt->num_rows;
            if($num_of_rows > 0){
                $matchPercentage = ((3/4)*100).'%';
            }else{
                $stmt->free_result();
                $stmt = $mysqli->prepare("SELECT DISTINCT
						candidate.firstName,
						candidate.lastName,
						candidate.address,
						candidate.homePhoneNo,
						candidate.mobileNo,
						candidate.email,
						candidate.consultantId,
						candidate.messageid,
						candidate.candidateId,
						candidate.screenDate,
						candidate.sex
					  FROM
						candidate
						LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
						AND (candidate.messageid = reg_candidate.messageId)
						AND (candidate.firstName = reg_candidate.firstName)
						AND (candidate.lastName = reg_candidate.lastName)
						AND (candidate.mobileNo = reg_candidate.mobile)
						AND (candidate.address = reg_candidate.homeAddress)
						AND (candidate.email = reg_candidate.email)
					  WHERE
						candidate.firstName = ? AND 
						candidate.mobileNo = ? AND 
						candidate.email = ?");
                $stmt->bind_param("sss",$firstName,$phoneNumber,$emailAddress) or die($mysqli->error);
                $stmt->execute();
                $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                $stmt->store_result();
                $num_of_rows = $stmt->num_rows;
                if($num_of_rows > 0){
                    $matchPercentage = ((3/4)*100).'%';
                }else{
                    $stmt->free_result();
                    $stmt = $mysqli->prepare("SELECT DISTINCT
							candidate.firstName,
							candidate.lastName,
							candidate.address,
							candidate.homePhoneNo,
							candidate.mobileNo,
							candidate.email,
							candidate.consultantId,
							candidate.messageid,
							candidate.candidateId,
							candidate.screenDate,
							candidate.sex
						  FROM
							candidate
							LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
							AND (candidate.messageid = reg_candidate.messageId)
							AND (candidate.firstName = reg_candidate.firstName)
							AND (candidate.lastName = reg_candidate.lastName)
							AND (candidate.mobileNo = reg_candidate.mobile)
							AND (candidate.address = reg_candidate.homeAddress)
							AND (candidate.email = reg_candidate.email)
						  WHERE
							candidate.lastName = ? AND 
							candidate.mobileNo = ? AND 
							candidate.email = ?");
                    $stmt->bind_param("sss",$lastName,$phoneNumber,$emailAddress) or die($mysqli->error);
                    $stmt->execute();
                    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                    $stmt->store_result();
                    $num_of_rows = $stmt->num_rows;
                    if($num_of_rows > 0){
                        $matchPercentage = ((3/4)*100).'%';
                    }else{
                        $stmt->free_result();
                        $stmt = $mysqli->prepare("SELECT DISTINCT
								  candidate.firstName,
								  candidate.lastName,
								  candidate.address,
								  candidate.homePhoneNo,
								  candidate.mobileNo,
								  candidate.email,
								  candidate.consultantId,
								  candidate.messageid,
								  candidate.candidateId,
								  candidate.screenDate,
								  candidate.sex
								FROM
								  candidate
								  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
								  AND (candidate.messageid = reg_candidate.messageId)
								  AND (candidate.firstName = reg_candidate.firstName)
								  AND (candidate.lastName = reg_candidate.lastName)
								  AND (candidate.mobileNo = reg_candidate.mobile)
								  AND (candidate.address = reg_candidate.homeAddress)
								  AND (candidate.email = reg_candidate.email)
								WHERE
								  candidate.firstName = ? AND 
								  candidate.lastName = ?");
                        $stmt->bind_param("ss",$firstName,$lastName) or die($mysqli->error);
                        $stmt->execute();
                        $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                        $stmt->store_result();
                        $num_of_rows = $stmt->num_rows;
                        if($num_of_rows > 0){
                            $matchPercentage = ((2/4)*100).'%';
                        }else{
                            $stmt->free_result();
                            $stmt = $mysqli->prepare("SELECT DISTINCT
											  candidate.firstName,
											  candidate.lastName,
											  candidate.address,
											  candidate.homePhoneNo,
											  candidate.mobileNo,
											  candidate.email,
											  candidate.consultantId,
											  candidate.messageid,
											  candidate.candidateId,
											  candidate.screenDate,
											  candidate.sex
											FROM
											  candidate
											  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
											  AND (candidate.messageid = reg_candidate.messageId)
											  AND (candidate.firstName = reg_candidate.firstName)
											  AND (candidate.lastName = reg_candidate.lastName)
											  AND (candidate.mobileNo = reg_candidate.mobile)
											  AND (candidate.address = reg_candidate.homeAddress)
											  AND (candidate.email = reg_candidate.email)
											WHERE
											  candidate.firstName = ? AND 
											  candidate.mobileNo = ?");
                            $stmt->bind_param("ss",$firstName,$phoneNumber) or die($mysqli->error);
                            $stmt->execute();
                            $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                            $stmt->store_result();
                            $num_of_rows = $stmt->num_rows;
                            if($num_of_rows > 0){
                                $matchPercentage = ((2/4)*100).'%';
                            }else{
                                $stmt->free_result();
                                $stmt = $mysqli->prepare("SELECT DISTINCT
													  candidate.firstName,
													  candidate.lastName,
													  candidate.address,
													  candidate.homePhoneNo,
													  candidate.mobileNo,
													  candidate.email,
													  candidate.consultantId,
													  candidate.messageid,
													  candidate.candidateId,
													  candidate.screenDate,
													  candidate.sex
													FROM
													  candidate
													  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
													  AND (candidate.messageid = reg_candidate.messageId)
													  AND (candidate.firstName = reg_candidate.firstName)
													  AND (candidate.lastName = reg_candidate.lastName)
													  AND (candidate.mobileNo = reg_candidate.mobile)
													  AND (candidate.address = reg_candidate.homeAddress)
													  AND (candidate.email = reg_candidate.email)
													WHERE
													  candidate.lastName = ? AND 
													  candidate.mobileNo= ?");
                                $stmt->bind_param("ss",$lastName,$phoneNumber) or die($mysqli->error);
                                $stmt->execute();
                                $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                $stmt->store_result();
                                $num_of_rows = $stmt->num_rows;
                                if($num_of_rows > 0){
                                    $matchPercentage = ((2/4)*100).'%';
                                }else{
                                    $stmt->free_result();
                                    $stmt = $mysqli->prepare("SELECT DISTINCT
																  candidate.firstName,
																  candidate.lastName,
																  candidate.address,
																  candidate.homePhoneNo,
																  candidate.mobileNo,
																  candidate.email,
																  candidate.consultantId,
																  candidate.messageid,
																  candidate.candidateId,
																  candidate.screenDate,
																  candidate.sex
																FROM
																  candidate
																  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																  AND (candidate.messageid = reg_candidate.messageId)
																  AND (candidate.firstName = reg_candidate.firstName)
																  AND (candidate.lastName = reg_candidate.lastName)
																  AND (candidate.mobileNo = reg_candidate.mobile)
																  AND (candidate.address = reg_candidate.homeAddress)
																  AND (candidate.email = reg_candidate.email)
																WHERE
																  candidate.email = ? AND 
																  candidate.mobileNo= ?");
                                    $stmt->bind_param("ss",$emailAddress,$phoneNumber) or die($mysqli->error);
                                    $stmt->execute();
                                    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                    $stmt->store_result();
                                    $num_of_rows = $stmt->num_rows;
                                    if($num_of_rows > 0){
                                        $matchPercentage = ((2/4)*100).'%';
                                    }else{
                                        $stmt->free_result();
                                        $stmt = $mysqli->prepare("SELECT DISTINCT
																			candidate.firstName,
																			candidate.lastName,
																			candidate.address,
																			candidate.homePhoneNo,
																			candidate.mobileNo,
																			candidate.email,
																			candidate.consultantId,
																			candidate.messageid,
																			candidate.candidateId,
																			candidate.screenDate,
																			candidate.sex
																		  FROM
																			candidate
																			LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																			AND (candidate.messageid = reg_candidate.messageId)
																			AND (candidate.firstName = reg_candidate.firstName)
																			AND (candidate.lastName = reg_candidate.lastName)
																			AND (candidate.mobileNo = reg_candidate.mobile)
																			AND (candidate.address = reg_candidate.homeAddress)
																			AND (candidate.email = reg_candidate.email)
																		  WHERE
																			candidate.firstName = ?");
                                        $stmt->bind_param("s",$firstName) or die($mysqli->error);
                                        $stmt->execute();
                                        $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                        $stmt->store_result();
                                        $num_of_rows = $stmt->num_rows;
                                        if($num_of_rows > 0){
                                            $matchPercentage = ((1/4)*100).'%';
                                        }else{
                                            $stmt->free_result();
                                            $stmt = $mysqli->prepare("SELECT DISTINCT
																					  candidate.firstName,
																					  candidate.lastName,
																					  candidate.address,
																					  candidate.homePhoneNo,
																					  candidate.mobileNo,
																					  candidate.email,
																					  candidate.consultantId,
																					  candidate.messageid,
																					  candidate.candidateId,
																					  candidate.screenDate,
																					  candidate.sex
																					FROM
																					  candidate
																					  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																					  AND (candidate.messageid = reg_candidate.messageId)
																					  AND (candidate.firstName = reg_candidate.firstName)
																					  AND (candidate.lastName = reg_candidate.lastName)
																					  AND (candidate.mobileNo = reg_candidate.mobile)
																					  AND (candidate.address = reg_candidate.homeAddress)
																					  AND (candidate.email = reg_candidate.email)
																					WHERE
																					  candidate.lastName = ?");
                                            $stmt->bind_param("s",$lastName) or die($mysqli->error);
                                            $stmt->execute();
                                            $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                            $stmt->store_result();
                                            $num_of_rows = $stmt->num_rows;
                                            if($num_of_rows > 0){
                                                $matchPercentage = ((1/4)*100).'%';
                                            }else{
                                                $stmt->free_result();
                                                $stmt = $mysqli->prepare("SELECT DISTINCT
																								  candidate.firstName,
																								  candidate.lastName,
																								  candidate.address,
																								  candidate.homePhoneNo,
																								  candidate.mobileNo,
																								  candidate.email,
																								  candidate.consultantId,
																								  candidate.messageid,
																								  candidate.candidateId,
																								  candidate.screenDate,
																								  candidate.sex
																								FROM
																								  candidate
																								  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																								  AND (candidate.messageid = reg_candidate.messageId)
																								  AND (candidate.firstName = reg_candidate.firstName)
																								  AND (candidate.lastName = reg_candidate.lastName)
																								  AND (candidate.mobileNo = reg_candidate.mobile)
																								  AND (candidate.address = reg_candidate.homeAddress)
																								  AND (candidate.email = reg_candidate.email)
																								WHERE
																								  candidate.mobileNo = ?");
                                                $stmt->bind_param("s",$phoneNumber) or die($mysqli->error);
                                                $stmt->execute();
                                                $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                                $stmt->store_result();
                                                $num_of_rows = $stmt->num_rows;
                                                if($num_of_rows > 0){
                                                    $matchPercentage = ((1/4)*100).'%';
                                                }else{
                                                    $stmt->free_result();
                                                    $stmt = $mysqli->prepare("SELECT DISTINCT
																											  candidate.firstName,
																											  candidate.lastName,
																											  candidate.address,
																											  candidate.homePhoneNo,
																											  candidate.mobileNo,
																											  candidate.email,
																											  candidate.consultantId,
																											  candidate.messageid,
																											  candidate.candidateId,
																											  candidate.screenDate,
																											  candidate.sex
																											FROM
																											  candidate
																											  LEFT OUTER JOIN reg_candidate ON (candidate.candidateId = reg_candidate.candidateId)
																											  AND (candidate.messageid = reg_candidate.messageId)
																											  AND (candidate.firstName = reg_candidate.firstName)
																											  AND (candidate.lastName = reg_candidate.lastName)
																											  AND (candidate.mobileNo = reg_candidate.mobile)
																											  AND (candidate.address = reg_candidate.homeAddress)
																											  AND (candidate.email = reg_candidate.email)
																											WHERE
																											  candidate.email = ?");
                                                    $stmt->bind_param("s",$emailAddress) or die($mysqli->error);
                                                    $stmt->execute();
                                                    $stmt->bind_result($firstName,$lastName,$address,$homePhoneNo,$mobileNo,$email,$consultantId,$messageid,$candidateId,$screenDate,$sex);
                                                    $stmt->store_result();
                                                    $num_of_rows = $stmt->num_rows;
                                                    if($num_of_rows > 0){
                                                        $matchPercentage = ((1/4)*100).'%';
                                                    }else{
                                                        $matchPercentage = ((0/4)*100).'%';
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
</head>
<body>
<!-- HEADER -->
<header id="header">
    <?php include "template/top_menu.php";
    if($_REQUEST['error_msg']<>''){echo base64_decode($_REQUEST['error_msg']);}?>

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
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <?php include "template/breadcrumblinks.php"; ?>
        </ol>
        <!-- end breadcrumb -->
    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content">
        <!--<div class="inbox-nav-bar no-content-padding">
            <h1 class="page-title txt-color-blueDark hidden-tablet"><i class="fa fa-fw fa-inbox"></i> Inbox &nbsp;
            </h1>
        </div>-->
        <div id="candidate-list" class="inbox-body no-content-padding">
            <!--<div class="inbox-side-bar">
                <ul class="inbox-menu-lg">
                    <li class="active">
                        <a class="inbox-load" href="javascript:void(0);"> Inbox (<span class="numRows"></span>) </a>
                    </li>
                </ul>
            </div>-->
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
                        <h2>Searched For:</h2>
                        <div>
                            <form class="smart-form" name="frmUpload" id="frmUpload" action="processUpload.php" method="post">
                                <div class="row">
                                    <section class="col col-3">
                                        <label for="fN" class="label">First Name</label>
                                        <label class="input"><i class="icon-append fa fa-user"></i>
                                            <input class="input" type="text" name="fN" value="<?php echo $firstName;?>">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label for="lN" class="label">Last Name</label>
                                        <label class="input"><i class="icon-append fa fa-user"></i>
                                            <input class="input" type="text" name="lN" value="<?php echo $lastName;?>">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label for="em" class="label">Email:</label>
                                        <label class="input"><i class="icon-append fa fa-envelope"></i>
                                            <input class="input" type="email" name="em" value="<?php echo $emailAddress;?>">
                                        </label>
                                    </section>
                                    <section class="col col-3">
                                        <label for="ph" class="label">Phone Number</label>
                                        <label class="input"><i class="icon-append fa fa-phone"></i>
                                            <input class="input" type="text" name="ph" value="<?php echo $phoneNumber;?>">
                                        </label>
                                        <input type="hidden" name="msgId" value="<?php echo $messageId;?>">
                                        <input type="hidden" name="consId" value="<?php echo getConsultantId($mysqli,$_SESSION['userSession']);?>">
                                        <button name="uploadBtn" type="submit" class="uploadBtn btn btn-primary btn-sm"><i class="glyphicon glyphicon-upload"></i> Upload New/Update</button>
                                    </section>
                                </div>
                            </form>
                        </div>
                        <br>
                        <?php if($stmt<>''){?>
                            <table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th>MATCH</th>
                                    <th data-hide="phone">CANDIDATEID</th>
                                    <th data-class="phone,tablet"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i> NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i> MOBILE NO</th>
                                    <th data-hide="phone,tablet"><i class="fa fa-fw fa-envelope txt-color-blue hidden-md hidden-sm hidden-xs"></i> EMAIL</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                while($stmt->fetch()){
                                    ?>
                                    <tr>
                                        <td id="matchPercentage">
                                            <input id="pm" value="<?php echo $matchPercentage; ?>" type="hidden">
                                            <?php echo $matchPercentage; ?>
                                        </td>
                                        <td><?php echo $candidateId; ?></td>
                                        <td class="msgId" data-messageid="<?php echo $messageId; ?>" fname="<?php echo $firstName; ?>" lname="<?php echo $lastName;?>" canId="<?php echo $candidateId; ?>" eml="<?php echo $email; ?>" mbl="<?php echo $mobileNo; ?>"><a href="candidateMain.php?messageid=<?php echo base64_encode($messageId); ?>&canId=<?php echo base64_encode($candidateId); ?>&fname=<?php echo base64_encode($firstName); ?>&lname=<?php echo base64_encode($lastName);?>&eml=<?php echo base64_encode($email); ?>&mbl=<?php echo base64_encode($mobileNo); ?>&conId=<?php echo base64_encode($consultantId);?>&gender=<?php echo base64_encode($sex); ?>" target="_blank"><?php echo $firstName.' '.$lastName; ?></a></td>
                                        <td><?php echo $mobileNo; ?></td>
                                        <td><?php echo $email; ?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>

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
-->
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
</div>
<!-- END SHORTCUT AREA -->
<?php include "template/scripts.php"; ?>

<script type="text/javascript">
    $(document).ready(function() {
        // DO NOT REMOVE : GLOBAL FUNCTIONS!
        pageSetUp();
        // PAGE RELATED SCRIPTS
        /*
         * Fixed table height
         */
        //tableHeightSize()
        //$(window).resize(function() {
        //	tableHeightSize()
        //})

        function tableHeightSize() {
            if ($('body').hasClass('menu-on-top')) {
                var menuHeight = 68;
                // nav height

                var tableHeight = ($(window).height() - 224) - menuHeight;
                if (tableHeight < (320 - menuHeight)) {
                    $('.table-wrap').css('height', (320 - menuHeight) + 'px');
                } else {
                    $('.table-wrap').css('height', tableHeight + 'px');
                }
            } else {
                var tableHeight = $(window).height() - 224;
                if (tableHeight < 320) {
                    $('.table-wrap').css('height', 320 + 'px');
                } else {
                    $('.table-wrap').css('height', tableHeight + 'px');
                }
            }
        }
        /*loadCandidateInfo();
        function loadCandidateInfo() {
            loadURL("ajax/candidate/candidateMatch.php", $('#candidate-list > .table-wrap'));
        }*/

    });
</script>
<script>
    runAllForms();
    $(function() {

        $(document).on('click', '.uploadBtn', function(evt) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var $screenFrm = $("#frmUpload").validate({
                errorClass	: errorClass,
                errorElement	: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    fN: {
                        required: true
                    },
                    lN: {
                        required: true
                    },
                    em: {
                        required: true,
                        email : true
                    },
                    ph: {
                        required: true
                    }
                },
                messages: {
                    fN:{
                        required: "Please enter candidate first name"
                    },
                    lN:{
                        required: "Please enter candidate last name"
                    },
                    em:{
                        required: "Please enter candidate email",
                        email : "Please enter a VALID email address"
                    },
                    ph:{
                        required: "Please enter candidate mobile"
                    }
                },
                submitHandler: function (form) {
                    form.submit();
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
<script src="js/chandlerQuery.js"></script>

<script type="text/javascript">
    var responsiveHelper_dt_basic = undefined;
    var responsiveHelper_datatable_fixed_column = undefined;
    var responsiveHelper_datatable_col_reorder = undefined;
    var responsiveHelper_datatable_tabletools = undefined;

    var breakpointDefinition = {
        tablet : 1024,
        phone : 480
    };

    $('#dt_basic').dataTable({
        "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
            "t"+
            "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
        "autoWidth" : true,
        "oLanguage": {
            "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
        },
        "preDrawCallback" : function() {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper_dt_basic) {
                responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
            }
        },
        "rowCallback" : function(nRow) {
            responsiveHelper_dt_basic.createExpandIcon(nRow);
        },
        "drawCallback" : function(oSettings) {
            responsiveHelper_dt_basic.respond();
        },
        "bPaginate": false,
        "bInfo" : false
    });
</script>
</body>

</html>