<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Australia/Melbourne');
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
	<?php include "template/header.php";?>
        <style>
          .ui-menu { width: 200px; }
          .ui-widget-header { padding: 0.2em; }
        </style>
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
			<div id="content" class="container-body">
                <div class="rosterColorDefinition">
                    <table class="table table-bordered">
                        <tbody>
                          <tr>
                            <td>
                                <div class="rosterLabel">Open Shift:&nbsp;</div>
                                <div class="rosterDefault">&nbsp;</div>
                            </td>
                            <td>
                                <div class="rosterLabel">SMS Sent:&nbsp;</div>
                                <div class="rosterSMSSent">&nbsp;</div>
                            </td>
                            <td>
                                <div class="rosterLabel">Shift Confirmed:&nbsp;</div>
                                <div class="rosterShiftConfirmed">&nbsp;</div>
                            </td>
                            <td>
                                <div class="rosterLabel">Shift Cancelled:&nbsp;</div>
                                <div class="rosterShiftCancelled">&nbsp;</div>
                            </td>
                            <td>
                                <div class="rosterLabel">Not Available For Roster:&nbsp;</div>
                                <div class="rosterNotAvailable">N/A</div>
                            </td>
                            <td>
                                <div class="rosterLabel">Not Available For Shift:&nbsp;</div>
                                <div class="rosterShiftNotAvailable"></div>
                            </td>
                            <td>
                                <div class="rosterLabel">Existing Shift:&nbsp;</div>
                                <div class="rosterExistingShift"></div>
                            </td>
                            <td>
                                <div class="rosterLabel">Shift Unfilled:&nbsp;</div>
                                <div class="rosterShiftUnfilled">&nbsp;</div>
                            </td>
                            <td>
                                <div class="rosterLabel">Sick:&nbsp;</div>
                                <div class="rosterShiftSick">&nbsp;</div>
                            </td>
                              <td>
                                  <div class="rosterLabel">Rejected:&nbsp;</div>
                                  <div class="rosterShiftRejected">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel">Left Voice Mail:&nbsp;</div>
                                  <div class="rosterVoiceMail">&nbsp;</div>
                              </td>
                          </tr>
                          <tr>
                              <td>
                                  <div class="rosterLabel">No Answer:&nbsp;</div>
                                  <div class="rosterNoAnswer">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel">No Show:&nbsp;</div>
                                  <div class="rosterNoShow">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel"> CheckedIn &nbsp;</div>
                                  <div class="rosterCheckedIn">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel"> CheckedOut&nbsp;</div>
                                  <div class="rosterCheckedOut">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel"> Cancel With Notice&nbsp;</div>
                                  <div class="rosterCancelWithNotice">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel"> Cancel Without Notice&nbsp;</div>
                                  <div class="rosterCancelWithoutNotice">&nbsp;</div>
                              </td>
                              <td>
                                  <div class="rosterLabel"> Shift Cancelled by Agency</div>
                                  <div class="rosterShiftCancelAgency"> </div>
                              </td>
                              <td colspan="5"><div class="rosterColorDefinition">
                                      <div class="rosterLabel">&nbsp;Casuals Availability: &nbsp;AM <img src="img/am_tick.png" width="16" height="16"/><img src="img/am_cross.png" width="16" height="16"/> PM <img src="img/pm_tick.png" width="16" height="16"/><img src="img/pm_cross.png" width="16" height="16"/> NIGHT <img src="img/night_tick.png" width="16" height="16"/><img src="img/night_cross.png" width="16" height="16"/></div></div>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                </div>
                <div style="clear:both;"></div>
                <div>
                    <table style="border: 0; border-spacing: 5px;border-collapse: separate;">
                        <tbody>
                        <tr>
                            <td style="width: 35%;">
                                <div class="creditBalanceLabel" style="display: inline-block">WHOLESALE CREDIT BALANCE&nbsp;<span class="wholesaleBalance"></span></div>&nbsp;&nbsp;
                                <div class="creditBalanceLabel" style="display: inline-block">CELLCAST CREDIT BALANCE&nbsp;<span class="cellCastBalance"></span><span class="erMsg"></span></div>
                                <input type="hidden" name="stWrkDate" id="stWrkDate" value="" style="margin-left: 80%;background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%" size="20" placeholder="Start of work for shift count"/>
                            </td>
                            <td style="text-align: right;">
                                <button class="genExportRosterBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP ROSTER</button>
                                <button class="genExportRosterAllBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP ALL ROSTER</button>
                                <button class="genEveryExcelBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP EVERYONE</button>
                                <button class="genLastShiftExcelBtn btn btn-info btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp; EXP LAST SHIFT</button>
                                <button class="genEverythingBtn btn btn-danger btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP EVERYTHING</button>
                                <button class="genPerClientExcelBtn btn btn-danger btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP PER CLIENT</button>
                                <button class="genNoAnswerBtn btn btn-danger btn-xs roster-button" type="button"><i class="fa fa-file-excel-o"></i>&nbsp;EXP NOANSWER</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding-top:10px;clear:both;"></div>
                <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%; height: 32px;"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>


                <!--<div class="creditBalanceLabel">WHOLESALE CREDIT BALANCE&nbsp;<span class="wholesaleBalance"></span></div>
                <div class="creditBalanceLabel">CELLCAST CREDIT BALANCE&nbsp;<span class="cellCastBalance"></span> &nbsp;&nbsp;&nbsp;&nbsp;<span class="erMsg"></span></div>
                <input type="text" name="stWrkDate" id="stWrkDate" value="" style="margin-left: 80%;background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%" size="20" placeholder="Start of work for shift count"/>
                <div style="padding-top:10px;clear:both;"></div>
                <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%;"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                </div>-->
                <!--<div class="pull-left">
                    <input type="text" name="reportrange" id="reportrange" style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 9pt" class="form-control" readonly/>
                </div>-->
                <div class="pull-left">
                    <label for="clientId" class="select">
                        <select name="clientId" id="clientId"  class="form-control" style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                        </select><i></i></label>
                </div>
                <div class="pull-left">
                    <label for="stateId" class="select">
                        <select name="stateId" id="stateId"  class="form-control" style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                        </select><i></i></label>
                </div>
                <div class="pull-left">
                    <label for="departmentId" class="select">
                        <select name="departmentId" id="departmentId"  class="form-control" style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                        </select><i></i></label>
                </div>

                <div class="pull-left">
                    <label for="expPosition" class="select">
                        <select name="expPosition" id="expPosition"  class="form-control" style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                        </select><i></i></label>
                </div>
                <div class="pull-left">
                    <label for="employeeName" class="input">
                        <input id="employeeName" name="employeeName" type="text" class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt" placeholder="Employee Name"/>
                    </label><input type="hidden" name="empSelected" id="empSelected"/>
                </div>
                <div class="pull-left" style="padding-left: 5px;">
                    <label for="scheduleBtn">
                        <button name="scheduleBtn" id="scheduleBtn" class="scheduleBtn btn btn-info btn-square btn-sm"><i class="fa fa-clock-o"></i>&nbsp;SCHEDULE</button>
                    </label>
                    <div id="clientNote" title="" class="btn btn-info btn-square btn-sm"><i class="fa fa-sticky-note"></i> DEPARTMENT NOTES</div>
                </div>
                <div class="pull-left">                    &nbsp;&nbsp;

                </div>
                <div class="pull-left" style="padding-left: 5px;">
                    <label for="copyScheduleBtn">
                        <button name="copyScheduleBtn" id="copyScheduleBtn" class="copyScheduleBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon-time"></i>&nbsp;COPY LAST WEEK</button>
                    </label>
                </div>

                <!--<div class="pull-left" style="padding-left:10px;">
                    <label for="supervisorId" class="select">
                        <select name="supervisorId" id="supervisorId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt">
                        </select><i></i></label>
                </div>-->
                <div class="pull-left">
                    <label id="supervisorDetails"></label>
                </div>
                <div class="pull-left" id="searchedPerson"></div>
                <div class="pull-right">
                </div>
                <div class="pull-right">
                </div>
                <div id="rosterTable" style="height: 980px; overflow: auto">
                    <table id="tblRoster" class="rosterTable table table-striped table-bordered table-hover table-responsive" width="100%" border="1" cellspacing="0" cellpadding="0">
                      <thead class="rosterTableHead">
                      </thead>	
                      <tbody class="rosterTableBody">
                      </tbody>
                    </table>
                </div>

                <div style="padding-top: 50px;"></div>
                <br style="height: 600px;">
                <div id="rosterNotePopup" style="width:500px; display:block">
                    <div id="rosterNoteTxt"></div>
                </div>
            	<div id="shiftPopup" style="width:500px; display:block">
            		<form id="shiftFrm" name="shiftFrm" class="smart-form" method="post">
                    <div class="row">
                        <input type="hidden" name="shiftDate" id="shiftDate">
                        <input type="hidden" name="shiftDay" id="shiftDay">
                        <input type="hidden" name="clid" id="clid">
                        <input type="hidden" name="stid" id="stid">
                        <input type="hidden" name="did" id="did">
                        <input type="hidden" name="canid" id="canid">
                        <input type="hidden" name="startDate" id="startDate">
                        <input type="hidden" name="endDate" id="endDate">
                        <input type="hidden" name="dateRange" id="dateRange">
                        <input type="hidden" name="shStatus" id="shStatus">
                        <input type="hidden" name="bulkCanId" id="bulkCanId">
                        <section class="col col-12">
                            <span class="erMsg"></span>
                        </section>
                    </div>
                    <div class="row">    
                        <section class="col col-12">
                            <span id="empName" class="h2"></span>
                        </section>
                    </div>
                    <div class="row">    
                        <section class="col col-3">
                        	<label for="shiftStart">Start Time</label>
                            	<label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            	<input name="shiftStart" id="shiftStart" type="text" size="20" value="00:00">
                            </label>
                        </section>
                        <section class="col col-3">
                        	<label for="shiftEnd">Finish Time</label>
                            	<label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            	<input name="shiftEnd" id="shiftEnd" type="text" size="20" value="00:00">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label for="break">Break</label>
                            <label class="select">
                                <select name="break" id="break">
                                    <option value="0">0</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                    <option value="60">60</option>
                                    <option value="75">75</option>
                                    <option value="90">90</option>
                                    <option value="105">105</option>
                                    <option value="120">120</option>
                                    <option value="135">135</option>
                                    <option value="150">150</option>
                                    <option value="165">165</option>
                                    <option value="180">180</option>
                                    <option value="195">195</option>
                                    <option value="210">210</option>
                                    <option value="225">225</option>
                                    <option value="240">240</option>
                                </select>
                             <i></i></label> 
                        </section>
                        <section class="col col-3">
                            <label for="shiftCopy">Copy Shift to All</label>
                            <label class="checkbox">
                                <input type="checkbox" name="shiftCopy" id="shiftCopy"><i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label for="shiftLocation">Select Shift Location</label>
                            <select id="shiftLocation" name="shiftLocation" class="select">
                            </select>
                        </section>
                        <section class="col col-6">
                            <label for="shiftCallStatus">Select Shift Status</label>
                            <select id="shiftCallStatus" name="shiftCallStatus" class="select">
                                <option value="None" selected>Select Shift Status</option>
                                <option value="N/A">Not Available</option>
                                <option value="VOICEMAIL">Left Voice Mail</option>
                                <option value="NOANSWER">No Answer</option>
                                <option value="CANCELLATION WITH NOTICE">CANCELLATION WITH NOTICE</option>
                                <option value="CANCELLATION WITHOUT NOTICE">CANCELLATION WITHOUT NOTICE</option>
                                <option value="NO SHOW">NO SHOW</option>
                                <option value="CANCELLED BY AGENCY">CANCELLED BY AGENCY</option>
                            </select>
                        </section>
                    </div>
                    <div class="row">
                    	<section class="col col-12" style="width:100%;">
                        	<label class="textarea textarea-resizable">
                                   <textarea rows="8" class="custom-scroll" name="note" id="note" placeholder="Add a note to this shift .... "></textarea>
                            </label>
                        </section>
                    </div>     
                	</form>
                </div>
                
                <div id="editshiftPopup" style="width:500px; display:block">
            		<form id="editshiftFrm" name="editshiftFrm" class="smart-form" method="post">
                    <div class="row">
                    	<input type="hidden" name="shiftid" id="shiftid">
                        <input type="hidden" name="eshiftDate" id="eshiftDate">
                        <input type="hidden" name="eclid" id="eclid">
                        <input type="hidden" name="estid" id="estid">
                        <input type="hidden" name="edid" id="edid">
                        <input type="hidden" name="ecanid" id="ecanid">
                        <input type="hidden" name="econsultant" id="econsultant" value="<?php echo $_SESSION['userSession']; ?>">
                        <section class="col col-12">
                            <span class="erMsg"></span>
                        </section>
                    </div>
                    <div class="row">    
                        <section class="col col-12">
                            <span id="eempName" class="h2"></span>
                        </section>
                    </div>
                    <div class="row">    
                        <section class="col col-3">
                        	<label for="eshiftStart">Start Time</label>
                            	<label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            	<input name="eshiftStart" id="eshiftStart" type="text" size="20">
                            </label>
                        </section>
                        <section class="col col-3">
                        	<label for="eshiftEnd">Finish Time</label>
                            	<label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            	<input name="eshiftEnd" id="eshiftEnd" type="text" size="20">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label for="ebreak">Break</label>
                            <label class="select">
                                <select name="ebreak" id="ebreak">
                                    <option value="0">0</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                    <option value="60">60</option>
                                    <option value="75">75</option>
                                    <option value="90">90</option>
                                    <option value="105">105</option>
                                    <option value="120">120</option>
                                    <option value="135">135</option>
                                    <option value="150">150</option>
                                    <option value="165">165</option>
                                    <option value="180">180</option>
                                    <option value="195">195</option>
                                    <option value="210">210</option>
                                    <option value="225">225</option>
                                    <option value="240">240</option>
                                </select>
                             <i></i> </label> 
                        </section>
                        <section class="col col-3">
                        	<span class="confirmBox">
                            <label for="shiftStatus">Confirm Shift</label>
                            <label class="checkbox">
                        		<input type="checkbox" name="shiftStatus" id="shiftStatus"><i></i>
                            </label>
                            </span>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-6">
                            <label for="eshiftLocation">Select Shift Location</label>
                            <select id="eshiftLocation" name="eshiftLocation" class="select">
                            </select>
                        </section>
                        <section class="col col-6">
                            <label for="eshiftCallStatus">Select Shift Status</label>
                            <select id="eshiftCallStatus" name="eshiftCallStatus" class="select">
                                <option value="None" selected>Select Shift Status</option>
                                <option value="N/A">Not Available</option>
                                <option value="VOICEMAIL">Left Voice Mail</option>
                                <option value="NOANSWER">No Answer</option>
                                <option value="CANCELLATION WITH NOTICE">CANCELLATION WITH NOTICE</option>
                                <option value="CANCELLATION WITHOUT NOTICE">CANCELLATION WITHOUT NOTICE</option>
                                <option value="NO SHOW">NO SHOW</option>
                                <option value="CANCELLED BY AGENCY">CANCELLED BY AGENCY</option>
                            </select>
                        </section>
                    </div>
                    <div class="row">
                    	<section class="col col-12" style="width:100%;">
                        	<label class="textarea textarea-resizable">
                                   <textarea rows="8" class="custom-scroll" name="enote" id="enote" placeholder="Edit note to this shift .... "></textarea>
                            </label>
                        </section>
                    </div>     
                	</form>
                </div>
                <div id="firebaseBulkPopup" style="display:block">
                    <form name="frmFirebaseBulkPush" id="frmFirebaseBulkPush" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="row">
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendBulkFirebasePushBtn btn btn-info btn-sm" type="submit"><img src="img/firebase_push.png" width="25"/>&nbsp;Send Push Notification </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="firebaseconid" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <select id="firebaseBodyTxt" name="firebaseBodyTxt">
                                            <?php echo getSmsBodyTextList($mysqli); ?>
                                        </select><i></i>
                                    </section>
                                    <section class="col col-6">
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <span class="error" id="firebaseBulkTextCount"></span>
                                            <textarea rows="20" class="custom-scroll" name="firebaseBulkText" id="firebaseBulkText" placeholder="Bulk Push Notification Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsBulkRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div id="smsBulkPopup" style="display:block">
                    <form name="frmBulkSMS" id="frmBulkSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity:</label>
                                        <label class="select">
                                            <select id="bulkact" name="bulkact">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendBulkBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-envelope"></i>&nbsp;Send</button>&nbsp;&nbsp;<span class="error" id="smsBulkTextCost" style="font-weight: bold;font-size: 12pt;"></span>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="bulkalertMe" name="bulkalertMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="bulkconid" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsBulkAccount" name="smsBulkAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rBulkCanId" value=""/><input type="hidden" id="shiftid" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <select id="smsBodyTxt" name="smsBodyTxt">
                                             <?php echo getSmsBodyTextList($mysqli); ?>
                                        </select><i></i>
                                    </section>
                                    <section class="col col-6">
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <span class="error" id="smsBulkTextCount"></span>
                                            <textarea rows="20" class="custom-scroll" name="smsBulkText" id="smsBulkText" placeholder="Bulk SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsBulkRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div id="smsPopup" style="display:block">
                <form name="frmNewSMS" id="frmNewSMS" class="smart-form" method="post" action="">
                        <div>
                        	<fieldset>
                            	<div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                            	<div class="row"> 
                                    <section class="col col-6">
                                        <label class="label">Activity:</label>
                                        <label class="select">
                                            <select id="act" name="act">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>  
                                    </section>
                                    <section class="col col-6">
                                    	<div>
                                             <button class="sendBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-envelope"></i>&nbsp;Send</button>&nbsp;&nbsp;<span class="error" id="smsTextCost" style="font-weight: bold;font-size: 12pt;"></span>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">    
                                	<section class="col col-6">
                                       <label class="checkbox">
                                       <input type="checkbox" id="alertMe" name="alertMe" value="Yes" checked>
                                       <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                    	<div class="input-group-btn">
                                             <input type="hidden" id="conid" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">    
                                	<section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsAccount" name="smsAccount">
                                                <?php echo getSMSAccounts($mysqli); ?>
                                            </select><i></i>
                                        </label> 
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                    	<input type="hidden" id="rCanId" value=""/><input type="hidden" id="shiftid" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                	<section class="col col-12" style="width:100%;">
                                    	<label class="textarea textarea-resizable">
                                            <span class="error" id="smsTextCount"></span>
                                              <textarea rows="20" class="custom-scroll" name="smsText" id="smsText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                        <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                        	<table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                        <thead>	
                                            <tr>		                
                                                <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                                <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                                <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>     
                                            </tr>
                                        </thead>
                                        <tbody class="recipients">
                                           
                                        </tbody>
                                      </table>
                        </div>
                        </form>
                </div>
                <div id="smsAllPopup" style="display:block">
                    <form name="frmAllSMS" id="frmAllSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actAll" name="actAll">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendAllSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send All</button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsAllAccount" name="smsAllAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rAllCanId" value=""/><input type="hidden" id="allshiftid" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsAllText" id="smsAllText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                        <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                        <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <div id="smsCovPopup" style="display:block">
                    <form name="frmCovSMS" id="frmCovSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actCov" name="actCov">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendCovSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsCovAccount" name="smsCovAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rCovCanId" value=""/><input type="hidden" id="covshiftid" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsCovText" id="smsCovText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div id="smsVaccPopup" style="display:block">
                    <form name="frmVaccSMS" id="frmVaccSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actCov" name="actCov">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendVaccSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsVaccAccount" name="smsVaccAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rVaccCanId" value=""/><input type="hidden" id="vaccshiftid" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsVaccText" id="smsVaccText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
                <div id="smsOHSPopup" style="display:block;">
                    <form name="frmOHSSMS" id="frmOHSSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actCov" name="actCov">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendOHSSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsOHSAccount" name="smsOHSAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rOHSCanId" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsOHSText" id="smsOHSText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <!--<div id="smsAppVersionPopup" style="display:block;">
                    <form name="frmAppVersionSMS" id="frmAppVersionSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actCov" name="actCov">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendAppVersionSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php /*echo $_SESSION['userSession']; */?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsAppVersionAccount" name="smsAppVersionAccount">
                                                <?php /*echo getSMSAccounts($mysqli);*/?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rAppVersionCanId" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsAppVersionText" id="smsAppVersionText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>-->
                <div id="smsSurveyPopup" style="display:block;">
                    <form name="frmSurveySMS" id="frmSurveySMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actCov" name="actCov">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendSurveySMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsSurveyAccount" name="smsSurveyAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rSurveyCanId" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsSurveyText" id="smsSurveyText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                        <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                        <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <div id="smsSciclunaPopup" style="display:block;">
                    <form name="frmSciclunaSMS" id="frmSciclunaSMS" class="smart-form" method="post" action="">
                        <div>
                            <fieldset>
                                <div class="creditBalanceLabel">SMS CREDIT BALANCE&nbsp;<span class="creditBalance"></span></div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">Activity :</label>
                                        <label class="select">
                                            <select id="actCov" name="actCov">
                                                <option value="SMS">SMS</option>
                                            </select> <i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <div>
                                            <button class="sendSciclunaSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-phone"></i>&nbsp;Send </button>
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="checkbox">
                                            <input type="checkbox" id="alertAllMe" name="alertAllMe" value="Yes" checked>
                                            <i></i>Alert Me:</label>
                                    </section>
                                    <section class="col col-6">
                                        <div class="input-group-btn">
                                            <input type="hidden" id="consultant" value="<?php echo $_SESSION['userSession']; ?>">
                                        </div>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-6">
                                        <label class="label">SMS Account:</label>
                                        <label class="select">
                                            <select id="smsSciclunaAccount" name="smsSciclunaAccount">
                                                <?php echo getSMSAccounts($mysqli);?>
                                            </select><i></i>
                                        </label>
                                    </section>
                                    <section class="col col-6">
                                        <label class="supportInfo fa-support"></label>
                                        <input type="hidden" id="rSciclunaCanId" value=""/>
                                    </section>
                                </div>
                                <div class="row">
                                    <section class="col col-12" style="width:100%;">
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="20" class="custom-scroll" name="smsSciclunaText" id="smsSciclunaText" placeholder="SMS Text ....."></textarea>
                                        </label>
                                    </section>
                                </div>
                            </fieldset>
                        </div>
                        <div>
                            <div><i class="fa fa-fw fa-users txt-color-blue hidden-md hidden-sm hidden-xs"></i>Recipients(<span class="nRecipients" style="color:rgba(37,124,179,1.00); font-weight:bold"></span>)</div>
                            <table id="smsRecipients" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                <tr>
                                    <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>MOBILE</th>
                                    <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                                </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>

                <div class="controllers"></div>

                <div class="scheduleInfo"></div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
		<?php include "template/footer.php"; ?>
		<?php include "template/scripts.php"; ?>
        <!-- DATE RANGE PICKER -->
        <script type="text/javascript" src="js/daterangepicker/moment.js"></script>
      	<script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/additional-methods.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY FORM PLUGIN -->
        <script src="js/jqueryform/jquery.form.js"></script>
        <script src="js/rosterScript.js"></script>
        <!--<script src="js/floatTHead/jquery.floatThead.min.js"></script>-->
        <script src="js/fixTableHeader/jQuery.fixTableHeader.min.js"></script>
        <!-- JQUERY SEARCHABLE SELECt -->
        <script src="js/chosen_v1.8.7/chosen.jquery.js"></script>
        <script>
            $(document).ready(function (){
                function loadSMSCreditBalance(action){
                    $.ajax({
                        url: "balanceCheck.php",
                        type: "POST",
                        data: { action:action },
                        dataType: "text",
                        success: function(data) {
                            console.log('.....'+data);
                            if(action == 'WHOLESALE') {
                                $('.wholesaleBalance').html('');
                                $('.wholesaleBalance').html(data);
                            }else if(action == 'CELLCAST'){
                                $('.cellCastBalance').html('');
                                $('.cellCastBalance').html(data);
                            }
                        }
                    });
                }
                var wholesale  = 'WHOLESALE';
                var cellcast = 'CELLCAST';
                loadSMSCreditBalance(wholesale);
                loadSMSCreditBalance(cellcast);
            });
        </script>
	</body>
</html>