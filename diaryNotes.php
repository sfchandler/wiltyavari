<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}

$priorities = getPriorityList($mysqli);
$consultants = getConsultantList($mysqli);
$activities = getActivityList($mysqli);
$consId = getConsultantId($mysqli,$_SESSION['userSession']);
$messageid = htmlentities($_REQUEST['messageid']);
$firstName = $_REQUEST['fname'];
$lastName = $_REQUEST['lname'];
$phoneNumber = $_REQUEST['mbl'];
$emailAddress = $_REQUEST['eml'];
/*$candidateMailFrom = retrieveCandidateName($mysqli,$messageid,$_SESSION['accountName']);
$str = explode('via',$candidateMailFrom);
$fullName = explode(' ',$str[0]);
$firstName = $fullName[0];
$lastName = $fullName[1].' '.$fullName[2];
$msgBody = retrieveCandidateMsgBody($mysqli,$messageid,$_SESSION['accountName']);
$emailAddress = get_string_between($msgBody, 'mailto:', '&quot;');
$phoneNumber = get_string_between($msgBody, 'Phone\r\n                                                        &lt;/p&gt;\r\n                                                        &lt;p style=&quot;font-weight: bold; margin: 0;&quot;&gt;\r\n$$                                                            ', '\r\n');*/
$candidateId = $_REQUEST['canId'];//getCandidateId($mysqli,$messageid,$emailAddress);
$dnoteId = $_REQUEST['dNoteId'];

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
    	<?php include "template/header.php";?>
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

			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
				</span>
				<!-- breadcrumb
				<ol class="breadcrumb">
					<li>Home</li><li>Inbox</li><li>Candidate Review</li>
				</ol> -->
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
                                    <table width="100%" border="0" cellpadding="2" cellspacing="2">
                                     <thead>
                                     <tr>
                                     	<th>
                                            <?php if(empty($dnoteId)) { ?>
                                                <form name="frmNotes" id="frmNotes" class="smart-form"
                                                      action="updateDiaryNotes.php" method="post">
                                                    <table width="93%" border="1" cellpadding="2" cellspacing="2"
                                                           class="table table-striped table-bordered table-hover">
                                                        <tbody>
                                                        <tr>
                                                            <td width="12%" align="center">Consultant :</td>
                                                            <td width="28%">
                                                                <select name="consultantId" id="consultantId"
                                                                        class="select">
                                                                    <?php
                                                                    foreach ($consultants as $cons) {
                                                                        ?>
                                                                        <option value="<?php echo $cons['consultantId']; ?>" <?php if ($consId == $cons['consultantId']) { ?> selected <?php } ?>><?php echo $cons['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                Note Created By: <?php echo getConsultantName($mysqli,$_REQUEST['consId']);?>
                                                            </td>
                                                            <td width="7%">NoteId
                                                                : <?php if ($_REQUEST['dNoteId'] <> '') { ?><input
                                                                    name="dNoteId" id="dNoteId" type="hidden"
                                                                    value="<?php echo $_REQUEST['dNoteId']; ?>"/><?php echo $_REQUEST['dNoteId'];
                                                                } else {
                                                                    echo getDiaryNoteId($mysqli); ?><input
                                                                    name="dNoteId" id="dNoteId" type="hidden"
                                                                    value="<?php echo getDiaryNoteId($mysqli); ?>"/><?php } ?>
                                                                <input type="hidden" name="emailAddress"
                                                                       value="<?php echo $emailAddress; ?>"/><input
                                                                        type="hidden" name="mobileNo"
                                                                        value="<?php echo $phoneNumber; ?>"/><input
                                                                        type="hidden" name="messageid"
                                                                        value="<?php echo $messageid; ?>"/></td>
                                                            <td width="53%">
                                                                <footer style="background:none; float:left; border-top:none">
                                                                    <?php if ($_REQUEST['dNoteId'] <> '') { ?>
                                                                        <input type="submit" name="submit"
                                                                               class="submitNote btn btn-primary"
                                                                               value="updateNote"/>
                                                                    <?php } else { ?>
                                                                        <input type="submit" name="submit"
                                                                               class="submitNote btn btn-primary"
                                                                               value="addNote"/>
                                                                    <?php } ?>
                                                                </footer>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="center">Activity :</td>
                                                            <td>
                                                                <select name="activityId" id="activityId"
                                                                        class="select">
                                                                    <?php
                                                                    foreach ($activities as $act) {
                                                                        ?>
                                                                        <option value="<?php echo $act['activityId']; ?>" <?php if ($_REQUEST['actId'] == $act['activityId']) { ?> selected <?php } ?>><?php echo $act['activityType']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </td>
                                                            <td></td>
                                                            <td>
                                                            </td>
                                                            <!--<td colspan="2" rowspan="2">Priority :
                                                                <?php
/*                                                                foreach ($priorities as $pT) {
                                                                    */?>
                                                                    <label style="display:block">
                                                                        <input name="priorityId" type="radio"
                                                                               value="<?php /*echo $pT['priorityId']; */?>" <?php /*if ($_REQUEST['pId'] == $pT['priorityId']) { */?> checked <?php /*} */?>>
                                                                        <i></i><?php /*echo $pT['priorityLevel']; */?>
                                                                    </label>
                                                               <?php /*} */?>
                                                            </td> -->
                                                        </tr>
                                                        <tr>
                                                            <td align="center">Person :</td>
                                                            <td><input name="fName" id="fName" type="hidden"
                                                                       value="<?php if ($_REQUEST['fname'] <> '') {
                                                                           echo $_REQUEST['fname'];
                                                                       } else {
                                                                           echo $firstName;
                                                                       } ?>"/><input name="lName" id="lName"
                                                                                     type="hidden"
                                                                                     value="<?php if ($_REQUEST['lname'] <> '') {
                                                                                         echo $_REQUEST['lname'];
                                                                                     } else {
                                                                                         echo $lastName;
                                                                                     } ?>"/><input type="hidden"
                                                                                                   name="canId"
                                                                                                   id="canId"
                                                                                                   value="<?php if ($_REQUEST['canId'] <> '') {
                                                                                                       echo $_REQUEST['canId'];
                                                                                                   } else {
                                                                                                       echo $candidateId;
                                                                                                   } ?>"/> <?php if ($_REQUEST['fname'] <> '') {
                                                                    echo $_REQUEST['fname'];
                                                                } else {
                                                                    echo $firstName;
                                                                }
                                                                echo ' '; ?><?php if ($_REQUEST['lname'] <> '') {
                                                                    echo $_REQUEST['lname'];
                                                                } else {
                                                                    echo $lastName;
                                                                } ?> <?php echo ' ';
                                                                if ($_REQUEST['canId'] <> '') {
                                                                    echo $_REQUEST['canId'];
                                                                } else {
                                                                    echo $candidateId;
                                                                } ?></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" align="left">Subject :
                                                                <label class="input"><input name="subject" type="text"
                                                                                            id="subject" size="150"
                                                                                            value="<?php if ($_REQUEST['subj'] <> '') {
                                                                                                echo $_REQUEST['subj'];
                                                                                            } ?>"></label></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="4" align="left">
                                                                <table width="100%" border="0">
                                                                    <tbody>
                                                                    <tr>
                                                                        <td align="left" valign="top">
                                                                            <table border="1" cellpadding="2"
                                                                                   cellspacing="2"
                                                                                   class="table table-striped table-bordered table-hover">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>Todo :</td>
                                                                                    <td>
                                                                                        <label class="input"> <i
                                                                                                    class="icon-append fa fa-calendar"></i>
                                                                                            <input type="text"
                                                                                                   name="todoDate"
                                                                                                   id="todoDate"
                                                                                                   data-mask-placeholder="-"
                                                                                                   placeholder="To do notes date"
                                                                                                   class="form-control"
                                                                                                   data-mask="99/99/9999"
                                                                                                   <?php if ($_REQUEST['toDate'] <> ''){ ?>value="<?php echo $_REQUEST['toDate']; ?>"<?php } ?>>
                                                                                        </label>
                                                                                    </td>
                                                                                    <td>
                                                                                        <?php if ($_REQUEST['toTime'] <> '') {
                                                                                            $tTime = explode(":", $_REQUEST['toTime']);
                                                                                            $tTime[0];
                                                                                            $tTime[1];
                                                                                        }
                                                                                        ?>
                                                                                        <label class="input">
                                                                                            <input id="todoTime"
                                                                                                   name="todoTime"
                                                                                                   <?php if ($_REQUEST['toTime'] <> ''){ ?>value="<?php echo $tTime[0] . ':' . $tTime[1]; ?>"
                                                                                                   <?php }else{ ?>value="00:00"<?php } ?> />
                                                                                        </label>
                                                                                    </td>
                                                                                    <td>Dur:</td>
                                                                                    <td>
                                                                                        <?php if ($_REQUEST['toDur'] <> '') {
                                                                                            $tDur = explode(":", $_REQUEST['toDur']);
                                                                                            $tDur[0];
                                                                                            $tDur[1];
                                                                                        }
                                                                                        ?>
                                                                                        <label class="input">
                                                                                            <input name="todoDhrs"
                                                                                                   type="text"
                                                                                                   id="todoDhrs"
                                                                                                   size="5"
                                                                                                   <?php if ($_REQUEST['toDur'] <> ''){ ?>value="<?php echo $tDur[0]; ?>"
                                                                                                   <?php }else{ ?>value=""<?php } ?>>
                                                                                        </label>
                                                                                    </td>
                                                                                    <td>:</td>
                                                                                    <td><label class="input">
                                                                                            <input name="todoDmns"
                                                                                                   type="text"
                                                                                                   id="todoDmns"
                                                                                                   size="5"
                                                                                                   <?php if ($_REQUEST['toDur'] <> ''){ ?>value="<?php echo $tDur[1]; ?>"
                                                                                                   <?php }else{ ?>value=""<?php } ?>>
                                                                                        </label>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="7">
                                                                                        <textarea name="todoNote"
                                                                                                  rows="5"
                                                                                                  class="textarea"
                                                                                                  id="todoNote"
                                                                                                  style="border: none; width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php if ($_REQUEST['tNote'] <> '') {
                                                                                                echo $_REQUEST['tNote'];
                                                                                            } ?></textarea>
                                                                                    </td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                        <td align="left" valign="top">
                                                                            <table border="1" cellpadding="2"
                                                                                   cellspacing="2"
                                                                                   class="table table-striped table-bordered table-hover">
                                                                                <tbody>
                                                                                <tr>
                                                                                    <td>Action :</td>
                                                                                    <td><label class="input"> <i
                                                                                                    class="icon-append fa fa-calendar"></i>
                                                                                            <input type="text"
                                                                                                   name="actionDate"
                                                                                                   id="actionDate"
                                                                                                   data-mask-placeholder="-"
                                                                                                   placeholder="action notes date"
                                                                                                   class="form-control"
                                                                                                   data-mask="99/99/9999"
                                                                                                   <?php if ($_REQUEST['actDate'] <> ''){ ?>value="<?php echo $_REQUEST['actDate']; ?>"<?php } ?>>
                                                                                        </label></td>
                                                                                    <td>
                                                                                        <?php if ($_REQUEST['actTime'] <> '') {
                                                                                            $asplit = explode(":", $_REQUEST['actTime']);
                                                                                            $asplit[0];
                                                                                            $asplit[1];
                                                                                        }
                                                                                        ?>
                                                                                        <label class="input"><input
                                                                                                    id="actionTime"
                                                                                                    name="actionTime"
                                                                                                    <?php if ($_REQUEST['actTime'] <> ''){ ?>value="<?php echo $asplit[0] . ':' . $asplit[1]; ?>"
                                                                                                    <?php }else{ ?>value="00:00"<?php } ?>/>
                                                                                        </label>
                                                                                    </td>
                                                                                    <td>Dur :</td>
                                                                                    <td>
                                                                                        <?php if ($_REQUEST['actDur'] <> '') {
                                                                                            $acDur = explode(":", $_REQUEST['actDur']);
                                                                                            $acDur[0];
                                                                                            $acDur[1];
                                                                                        }
                                                                                        ?>
                                                                                        <label class="input">
                                                                                            <input name="actionDhrs"
                                                                                                   type="text"
                                                                                                   id="actionDhrs"
                                                                                                   size="5"
                                                                                                   <?php if ($_REQUEST['actDur'] <> ''){ ?>value="<?php echo $acDur[0]; ?>"
                                                                                                   <?php }else{ ?>value=""<?php } ?>>
                                                                                        </label>
                                                                                    </td>
                                                                                    <td>:</td>
                                                                                    <td>
                                                                                        <label class="input">
                                                                                            <input name="actionDmns"
                                                                                                   type="text"
                                                                                                   id="actionDmns"
                                                                                                   size="5"
                                                                                                   <?php if ($_REQUEST['actDur'] <> ''){ ?>value="<?php echo $acDur[1]; ?>"
                                                                                                   <?php }else{ ?>value=""<?php } ?>>
                                                                                        </label>
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="7"><textarea
                                                                                                name="actionNote"
                                                                                                rows="5"
                                                                                                class="textarea"
                                                                                                id="actionNote"
                                                                                                style="border: none; width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php if ($_REQUEST['actNote'] <> '') {
                                                                                                echo $_REQUEST['actNote'];
                                                                                            } ?></textarea></td>
                                                                                </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </form>
                                                <?php
                                            }else{
                                                    $sql = $mysqli->prepare("SELECT
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
                                                                                    WHERE diaryNoteId = ?")or die($mysqli->error);
                                                    $sql->bind_param("s",$dnoteId)or die($mysqli->error);
                                                    $sql->execute();
                                                    $sql->store_result();
                                                    $sql->bind_result($diaryNoteId, $firstName, $lastName, $candidateId, $axiomno, $activityId, $priorityId,
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
                                                                    $lastmodBy)or die($mysqli->error);
                                                    while($sql->fetch()){
                                            ?>
                                            <form name="frmNotes" id="frmNotes" class="smart-form" action="updateDiaryNotes.php" method="post">
                                                <table width="93%" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                                                    <tbody>
                                                    <tr>
                                                        <td width="12%" align="center">Consultant :</td>
                                                        <td width="28%">
                                                            <select name="consultantId" id="consultantId" class="select">
                                                                <?php
                                                                foreach($consultants as $cons){
                                                                    ?>
                                                                    <option value="<?php echo $cons['consultantId']; ?>" <?php if($consId == $cons['consultantId']){?> selected <?php } ?>><?php echo $cons['name']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                            Note Created By: <?php echo getConsultantName($mysqli,$_REQUEST['consId']);?>
                                                        </td>
                                                        <td width="7%">NoteId : <input name="dNoteId" id="dNoteId" type="hidden" value="<?php echo $diaryNoteId; ?>"/><?php echo $diaryNoteId; ?><input type="hidden" name="emailAddress" value="<?php echo $emailAddress; ?>"/><input type="hidden" name="mobileNo" value="<?php echo $phoneNumber; ?>"/><input type="hidden" name="messageid" value="<?php echo $messageid; ?>"/></td>
                                                        <td width="53%">
                                                            <footer style="background:none; float:left; border-top:none">
                                                                <input type="submit" name="submit" class="submitNote btn btn-primary" value="updateNote"/>
                                                            </footer>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center">Activity :</td>
                                                        <td>
                                                            <select name="activityId" id="activityId" class="select">
                                                                <?php
                                                                foreach($activities as $act){
                                                                    ?>
                                                                    <option value="<?php echo $act['activityId']; ?>" <?php if($activityId == $act['activityId']){?> selected <?php } ?>><?php echo $act['activityType']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </td>
                                                        <td></td>
                                                        <td></td>
                                                        <!--<td colspan="2" rowspan="2">Priority :
                                                            <?php /*
                                                            foreach($priorities as $pT) {
                                                                */?>
                                                                <label style="display:block">
                                                                    <input name="priorityId" type="radio" value="<?php /*echo $pT['priorityId']; */?>" <?php /*if($priorityId == $pT['priorityId']){*/?> checked <?php /*} */?>>
                                                                    <i></i><?php /*echo $pT['priorityLevel']; */?>
                                                                </label>
                                                            <?php /*} */?>
                                                        </td>-->
                                                    </tr>
                                                    <tr>
                                                        <td align="center">Person :</td>
                                                        <td><input name="fName" id="fName" type="hidden" value="<?php echo $firstName; ?>"/><input name="lName" id="lName" type="hidden" value="<?php echo $lastName; ?>"/><input type="hidden" name="canId" id="canId" value="<?php echo $candidateId; ?>"/> <?php echo $firstName.' '.$lastName;?> <?php echo ' '. $candidateId; ?></td>
                                                        <td></td><td></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" align="left">Subject :
                                                            <label class="input"><input name="subject" type="text" id="subject" size="150" value="<?php echo $subject; ?>"></label></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4" align="left"><table width="100%" border="0">
                                                                <tbody>
                                                                <tr>
                                                                    <td align="left" valign="top">
                                                                        <table border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>Todo :</td>
                                                                                <td>
                                                                                    <label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                                                        <input type="text" name="todoDate" id="todoDate" data-mask-placeholder="-" placeholder="To do notes date" class="form-control" data-mask="99/99/9999" value="<?php echo $todoDate; ?>">
                                                                                    </label>
                                                                                </td>
                                                                                <td>
                                                                                    <?php if($todoTime<>''){
                                                                                        $tTime = explode(":", $todoTime);
                                                                                        $tTime[0];
                                                                                        $tTime[1];
                                                                                    }
                                                                                    ?>
                                                                                    <label class="input">
                                                                                        <input id="todoTime" name="todoTime" <?php if($todoTime<>''){?>value="<?php echo $tTime[0].':'.$tTime[1]; ?>" <?php }else{ ?>value="00:00"<?php } ?>/>
                                                                                    </label>
                                                                                </td>
                                                                                <td>Dur:</td>
                                                                                <td>
                                                                                    <?php if($todoDuration<>''){
                                                                                        $tDur = explode(":", $todoDuration);
                                                                                        $tDur[0];
                                                                                        $tDur[1];
                                                                                    }
                                                                                    ?>
                                                                                    <label class="input">
                                                                                        <input name="todoDhrs" type="text" id="todoDhrs" size="5" value="<?php echo $tDur[0]; ?>" value="">
                                                                                    </label>
                                                                                </td>
                                                                                <td>:</td>
                                                                                <td><label class="input">
                                                                                        <input name="todoDmns" type="text" id="todoDmns" size="5" value="<?php echo $tDur[1]; ?>" value="">
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="7">
                                                                                    <textarea name="todoNote" rows="5" class="textarea" id="todoNote" style="border: none; width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php echo $todoNote; ?></textarea>
                                                                                </td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </td>
                                                                    <td align="left" valign="top">
                                                                        <table border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                                                                            <tbody>
                                                                            <tr>
                                                                                <td>Action :</td>
                                                                                <td><label class="input"> <i class="icon-append fa fa-calendar"></i>
                                                                                        <input type="text" name="actionDate" id="actionDate" data-mask-placeholder="-" placeholder="action notes date" class="form-control" data-mask="99/99/9999" <?php if($_REQUEST['actDate']<>''){?>value="<?php echo $_REQUEST['actDate']; ?>"<?php } ?>>
                                                                                    </label></td>
                                                                                <td>
                                                                                    <?php if($actionTime<>''){
                                                                                        $asplit = explode(":", $actionTime);
                                                                                        $asplit[0];
                                                                                        $asplit[1];
                                                                                    }
                                                                                    ?>
                                                                                    <label class="input"><input id="actionTime" name="actionTime" <?php if($actionTime<>''){?>value="<?php echo $asplit[0].':'.$asplit[1]; ?>"<?php }else{ ?>value="00:00"<?php } ?>/>
                                                                                    </label>
                                                                                </td>
                                                                                <td>Dur :</td>
                                                                                <td>
                                                                                    <?php if($actionDuration<>''){
                                                                                        $acDur = explode(":", $actionDuration);
                                                                                        $acDur[0];
                                                                                        $acDur[1];
                                                                                    }
                                                                                    ?>
                                                                                    <label class="input">
                                                                                        <input name="actionDhrs" type="text" id="actionDhrs" size="5" <?php if($actionDuration<>''){?>value="<?php echo $acDur[0]; ?>"<?php }else{ ?>value=""<?php } ?>>
                                                                                    </label>
                                                                                </td>
                                                                                <td>:</td>
                                                                                <td>
                                                                                    <label class="input">
                                                                                        <input name="actionDmns" type="text" id="actionDmns" size="5" <?php if($actionDuration<>''){?>value="<?php echo $acDur[1]; ?>"<?php }else{ ?>value=""<?php } ?>>
                                                                                    </label>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td colspan="7"><textarea name="actionNote" rows="5"  class="textarea" id="actionNote" style="border: none; width: 100%; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; box-sizing: border-box;"><?php if($actionNote<>''){echo $actionNote;}?></textarea></td>
                                                                            </tr>
                                                                            </tbody>
                                                                        </table></td>
                                                                </tr>
                                                                </tbody>
                                                            </table></td>
                                                    </tr>
                                                    </tbody>
                                                </table></form>
                                            <?php }
                                            } ?>
                                        </th>
                                   	   </tr>
                                     </thead>
                                     <tbody>
                                     <tr><td>&nbsp;</td>
                                       </tr>
                                     </tbody>
                                    </table>
                            
                                    
                                    <p>&nbsp;</p>
                                    </div>
									<!-- end widget content -->
				
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->
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
		<!--<div id="shortcut">
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
        
		<?php include "template/scripts.php"; ?>
        <script type="text/javascript">
			runAllForms();
			$(function() {
				$('#todoDate').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+20"
				});
				$('#actionDate').datepicker({
					changeMonth: true,
					changeYear: true,
					yearRange: "-100:+20"
				});
				$(document).on('click', '.submitNote', function(evt) {
					$("#frmNotes").validate({
						rules : {
							subject : {
								required : true
							},
							todoDate : {
								required: function() {
									return $('#actionDate').is(':blank');
								}
							}/*,
							todoNote : {
								required: function() {
									return $('#actionNote').is(':blank');
								}
							}*/,
							actionDate : {
								required: function() {
									return $('#todoDate').is(':blank');
								}
							}/*,
							actionNote : {
								required: function() {
									return $('#todoNote').is(':blank');
								}
							},
							priorityId : {
								required: function() {
									return $('[name="priorityId"]:checked').length === 0; 
								}
							}*/
						},
						messages : {
							subject : {
								required : 'Please insert a subject for the note'
							},
							todoDate : {
								required : 'Please select a todo date'
							}/*,
							todoNote : {
								required : 'Please insert a to do note'
							}*/,
							actionDate : {
								required : 'Please select an action date'
							}/*,
							actionNote : {
								required : 'Please insert an action note'
							},
							priorityId : {
								required : 'Please select a priority level'
							}*/
						},
						submitHandler : function(form) {
							form.submit();
						}
					});
				});
				$.widget("ui.timespinner", $.ui.spinner, {
					options: {
						// seconds
						step: 60 * 1000,
						// hours
						page: 60
					},
					_parse: function( value ) {
						if ( typeof value === "string" ) {
							// already a timestamp
							if ( Number( value ) == value ) {
								return Number( value );
							}
							return +Globalize.parseDate( value );
						}
						return value;
					},
			
					_format: function( value ) {
						return Globalize.format( new Date(value), "t" );
					}
				});
		
			
			Globalize.culture("de-DE");
			$("#todoTime").timespinner();
			$("#actionTime").timespinner();
			});
		</script>
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
			/*
			loadCandidateInfo();
			function loadCandidateInfo() {
				loadURL("ajax/candidate/candidateMatch.php", $('#candidate-list > .table-wrap'));
			}*/
			
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
		  
		  /* TABLETOOLS */
			$('#datatable_tabletools').dataTable({
				
				// Tabletools options: 
				//   https://datatables.net/extensions/tabletools/button_options
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},		
		        "oTableTools": {
		        	 "aButtons": [
		             "copy",
		             "csv",
		             "xls",
		                {
		                    "sExtends": "pdf",
		                    "sTitle": "Chandler_PDF",
		                    "sPdfMessage": "Chandler PDF Export",
		                    "sPdfSize": "A4"
		                },
		             	{
	                    	"sExtends": "print",
	                    	"sMessage": "Generated by ChandlerAdmin <i>(press Esc to close)</i>"
	                	}
		             ],
		            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_tabletools) {
						responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_tabletools.respond();
				}
			});
			
			/* END TABLETOOLS */
	</script>
	</body>

</html>