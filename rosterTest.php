<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
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
    <div id="logo-group">
        <span id="logo"> <img src="img/logo.png" alt=" <?php echo DOMAIN_NAME; ?> Admin"> </span>
    </div>
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
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <?php include "template/breadcrumblinks.php"; ?>
        </ol>
        <!-- end breadcrumb -->
    </div>

    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body">
        <div class="rosterColorDefinition"><div class="rosterLabel">Default:&nbsp;</div><div class="rosterDefault">&nbsp;</div><div class="rosterLabel">SMS Sent:&nbsp;</div><div class="rosterSMSSent">&nbsp;</div><div class="rosterLabel">Shift Confirmed:&nbsp;</div><div class="rosterShiftConfirmed">&nbsp;</div><div class="rosterLabel">Shift Cancelled:&nbsp;</div><div class="rosterShiftCancelled">&nbsp;</div><div class="rosterLabel">Not Available For Roster:&nbsp;</div><div class="rosterNotAvailable">N/A</div><div class="rosterLabel">Not Available For Shift:&nbsp;</div><div class="rosterShiftNotAvailable"></div><div class="rosterLabel">Existing Shift:&nbsp;</div><div class="rosterExistingShift"></div><div class="rosterLabel">Shift Unfilled:&nbsp;</div><div class="rosterShiftUnfilled">&nbsp;</div></div>
        <div style="clear:both;"></div>
        <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span></span> <b class="caret"></b>
        </div>
        <div class="pull-left">
            <label for="expPosition" class="select">
                <select name="expPosition" id="expPosition"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt">
                </select><i></i></label>
        </div>
        <div class="pull-left">
            <label for="departmentId" class="select">
                <select name="departmentId" id="departmentId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt">
                </select><i></i></label>
        </div>
        <div class="pull-left">
            <label for="employeeName" class="input">
                <input id="employeeName" name="employeeName" type="text"   class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt" placeholder="Employee Name"/>
            </label><input type="hidden" name="empSelected" id="empSelected"/>
        </div>
        <div class="pull-left">
            <label for="filterBtn">
                <button name="scheduleBtn" id="scheduleBtn" class="scheduleBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon-time"></i>&nbsp;Schedule</button>
            </label>
        </div>
        <div class="pull-left" style="padding-left:10px;">
            <label for="supervisorId" class="select">
                <select name="supervisorId" id="supervisorId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt">
                </select><i></i></label>
        </div>
        <div class="pull-left">
            <label id="supervisorDetails"></label>
        </div>
        <div class="pull-left" id="searchedPerson"></div>
        <div class="pull-right">
            <button class="genExcelBtn btn btn-success btn-sm" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i>&nbsp;Export Confirmed</button>
        </div>
        <div class="pull-right">
            <button class="genAllExcelBtn btn btn-success btn-sm" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i> Export All</button>
        </div>
        <div id="rosterTable">
            <table id="tblRoster" class="rosterTable table table-striped table-bordered table-hover" width="100%" border="1" cellspacing="0" cellpadding="0">
                <thead class="rosterTableHead">

                </thead>
                <tbody class="rosterTableBody">
                </tbody>
            </table>
        </div>
        <div style="padding-top: 50px;"></div>
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
                            <input name="shiftStart" id="shiftStart" type="text" size="20">
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="shiftEnd">Finish Time</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            <input name="shiftEnd" id="shiftEnd" type="text" size="20">
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
                    <section class="col col-12">
                        <label for="shiftLocation">Select Shift Location</label>
                        <select id="shiftLocation" name="shiftLocation" class="select">
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
                    <section class="col col-12">
                        <label for="eshiftLocation">Select Shift Location</label>
                        <select id="eshiftLocation" name="eshiftLocation" class="select">
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
                                    <button class="sendBulkBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-envelope"></i>&nbsp;Send</button>
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
                                        <option value="1">ChandlerWholeSale</option>
                                        <option value="2">ChandlerMessageMedia</option>
                                    </select><i></i>
                                </label>
                            </section>
                            <section class="col col-6">
                                <input type="hidden" id="rBulkCanId" value=""/><input type="hidden" id="shiftid" value=""/>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-12" style="width:100%;">
                                <label class="textarea textarea-resizable">
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
                                    <button class="sendBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-envelope"></i>&nbsp;Send</button>
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
                                        <option value="1">ChandlerWholeSale</option>
                                        <option value="2">ChandlerMessageMedia</option>
                                    </select><i></i>
                                </label>
                            </section>
                            <section class="col col-6">
                                <input type="hidden" id="rCanId" value=""/><input type="hidden" id="shiftid" value=""/>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-12" style="width:100%;">
                                <label class="textarea textarea-resizable">
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
                                    <button class="sendAllSMSBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-envelope"></i>&nbsp;Send All</button>
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
                                        <option value="1">Chandler</option>
                                    </select><i></i>
                                </label>
                            </section>
                            <section class="col col-6">
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
        <div class="controllers"></div>
        <div class="scheduleInfo"></div><div id="mydiv" class="foo bar "></div>
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
<script>
    $(document).ready(function(){
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("ajaxLoader");    },
            ajaxStop: function() { $body.removeClass("ajaxLoader"); }
        });

        var targetRowId;
        var tableContainer = $('div #rosterTable');
        var addShiftDialog;
        var editShiftDialog;
        var smsDialog;
        var chkArray = [];
        var smsBulkDialog;
        var rosterNoteDialog;
        var rowCandidateId = null;
        var form;
        var eform;
        var smsForm;
        var smsAllForm;
        var start = moment();
        var end = moment();
        var weekday=new Array(7);
        weekday[0]="Sun";
        weekday[1]="Mon";
        weekday[2]="Tue";
        weekday[3]="Wed";
        weekday[4]="Thu";
        weekday[5]="Fri";
        weekday[6]="Sat";
        var headerGlobal = [];
        var headerReturn = [];

        function dateCalendar(start, end) {
            var dateRange = [];
            var days = [];
            var date = [];
            var header = [];
            headerGlobal.length = 0;
            headerReturn.length = 0;
            $('#days').html('');
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var startDate = start.format('YYYY-MM-DD');
            var endDate = new Date(end.format('YYYY-MM-DD'));
            var currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                var dateFormat = new Date(currentDate);
                dateRange.push(dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate());
                days.push(weekday[dateFormat.getDay()]);
                date.push(dateFormat.getDate());
                header.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
                headerReturn.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
                headerGlobal.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate()});
                currentDate.setDate(currentDate.getDate() + 1);
            }
            generateRosterTableHeader(header);
            $('#dateRange').val(dateRange);
            $('#startDate').val(start.format('YYYY-MM-DD'));
            $('#endDate').val(end.format('YYYY-MM-DD'));
        }

        $(document).tooltip({
            position: {
                my: "center bottom-20",
                at: "center top",
                using: function( position, feedback ) {
                    $( this ).css( position );
                    $( "<div>" )
                        .addClass( "arrow" )
                        .addClass( feedback.vertical )
                        .addClass( feedback.horizontal )
                        .appendTo( this );
                }
            }
        });

        function generateRosterTableHeader(header){
            var row = '';
            for(var headerItem in header){
                row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="rosterTableHeaderCell">'+header[headerItem]['headerDay']+'<span class="shiftCount"></span><br>'+header[headerItem]['headerDate']+'</th>';
            }
            $('.rosterTableHead').html('');
            $('.rosterTableBody').html('');
            $('.rosterTableHead').html('<th class="rosterTableHeaderCell">&nbsp;&nbsp;&nbsp;Roster Scheduling&nbsp;&nbsp;&nbsp;<button name="bulkSMS" id="bulkSMS" class="btn btn-sm"><i class="glyphicon glyphicon-phone"></i>Bulk SMS</button></th>'+row+'<th class="rosterTableAction">&nbsp;</th>');
            $('.rosterTable').css("width","95%");
            $('.rosterTable').css("overflow","auto");
        }
        function tableCounter(){
            var shCounter = new Array();
            $("table#tblRoster tr").each(
                function (i,e)
                {
                    $(e).find("td").each(
                        function (i,e)
                        {
                            if (!shCounter[i]) shCounter[i] = 0;
                            //if ($(e).hasClass("shiftDisplay")) shiftCounter[i]++;
                            if (($(e).find('div.shiftDisplay.shiftConfirmed').length)>0)shCounter[i]++;
                        }
                    );
                }
            );
            var lastrow = '<tr>';
            var column = 0;
            var totalShifts = 0;
            var len = shCounter.length;
            $.each(shCounter, function(i,e) {
                if(e>0){
                    lastrow+='<td  class="rosterTableHeaderCell">'+e+'</td>';
                    totalShifts = totalShifts + e;
                }else{
                    if(column == 0){
                        lastrow+='<td class="rosterTableHeaderCell">Shift Counter</td>';
                    }else if((column == len - 1)){
                        lastrow+='<td class="rosterTableHeaderCell">Total of Shifts '+totalShifts+'</td>';
                    }else{
                        lastrow+='<td></td>';
                    }
                }
                column++;
            });
            lastrow+='</tr>';
            $("table#tblRoster tbody").prepend(lastrow);
        }
        $('#reportrange').daterangepicker({
            "autoApply": true,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Day': [moment().subtract(6, 'days'), moment()]/*,
                     'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                     'This Month': [moment().startOf('month'), moment().endOf('month')],
                     'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]*/
            }
        }, dateCalendar);
        dateCalendar(start, end);

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
        populateCandidatePositions();
        function populateCandidatePositions(){
            var dropSelect = 'N';
            $.ajax({
                url:"getCandidatePositionList.php",
                type:"POST",
                data:{dropSelect:dropSelect},
                dataType:"html",
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        }
        function generateRosterTableBody(param,num_th,positionid,candidateId){
            $.ajax({
                url:"getAllocatedEmployeesTest.php",
                type:"GET",
                dataType: "html",
                data:{param : param, num_th : num_th, headerGlobal : headerGlobal,positionid : positionid, candidateId : candidateId},
                success: function(data){
                    $('.rosterTableBody').html('');
                    $('.rosterTableBody').html(data);
                    sortRosterTableBody();
                }
            }).done(function(){
                sortRosterTableBody();
                tableCounter();
                rowCandidateId = $("#"+targetRowId);
                $('html, body').animate({scrollTop: rowCandidateId.offset().top }, 'slow');
            });
        }

        function sortRosterTableBody(){
            var lastRowId = $('.rosterTable').find('tr:last').attr('id');
            $('.rosterTable > tbody > tr').each(function(){
                var currentRowId = $(this).attr('id');
                var nextRowId = $(this).next().attr('id');
                if ($(this).find('td div.shiftDisplay').length >0){
                }else{
                    $('#'+currentRowId).insertAfter('#'+lastRowId);
                }
            });
            var shiftInfo = [];

            $('.rosterTable > tbody > tr').each(function(){
                var currentRowId = $(this).attr('id');
                if($(this).find('td div.shiftDisplay:first').attr('data-shiftstart') != undefined){
                    var thisStartTime = $(this).find('td div.shiftDisplay:first').attr('data-shiftstart');
                    var startDate = $(this).find('td div.shiftDisplay:first').attr('data-shiftdate');
                    var dateBreak = startDate.split("-");
                    var newStartDate = dateBreak[0]+"/"+dateBreak[1]+"/"+dateBreak[2];
                    var timeStamp = new Date(newStartDate+' '+thisStartTime).getTime();
                    shiftInfo.push({rowId:currentRowId, stTime: timeStamp});
                }
                else{
                    shiftInfo.push({rowId:currentRowId, stTime: 123456789123456789});
                }

            });
            shiftInfo.sort(function(obj1,obj2){
                return obj1.stTime - obj2.stTime;
            });
            $.each(shiftInfo, function (index, value) {
                $('#'+value.rowId).insertBefore('#'+lastRowId);
            });
        }
        $('.ui-autocomplete-input').css('width','40px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
            }
        });
        $(document).on('click','.scheduleBtn', function(){
            var param = $('#departmentId :selected').val();
            var num_th = $('.rosterTableHead th').length;
            var positionid = $('#expPosition :selected').val();
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
                searchTxt = '';
            }else{
                candidateId = $('#empSelected').val();
                searchTxt = '&nbsp;Schedule For '+$('#employeeName').val();
            }
            generateRosterTableBody(param,num_th,positionid,candidateId);
            $('#searchedPerson').html('');
            $('#searchedPerson').html(searchTxt);
        });
        function populateSupervisors(param,dropdown){
            $.ajax({
                url:"getSupervisorsList.php",
                type:"POST",
                data:{param:param, dropdown:dropdown},
                dataType:"html",
                success: function(data){
                    $('#supervisorId').html('');
                    $('#supervisorId').html(data);
                }
            });
        }
        $(document).on('change', '#departmentId', function(){
            $('#supervisorDetails').html('');
            var param = $('#departmentId :selected').val();
            var dropdown = 1;
            /*var num_th = $('.rosterTableHead th').length;
            var positionid = $('#expPosition :selected').val();
            generateRosterTableBody(param,num_th,positionid);*/
            populateSupervisors(param,dropdown);
        });
        $(document).on('change', '#supervisorId', function(){
            var supervisorId = $('#supervisorId :selected').val();
            $.ajax({
                url:"getSupervisorsList.php",
                type:"POST",
                data:{supervisorId:supervisorId},
                dataType:"html",
                success: function(data){
                    $('#supervisorDetails').html('');
                    $('#supervisorDetails').html(data);
                }
            });
        });
        function getShiftLocationsDropDown(clientId){
            $.ajax({
                url:"getClientShiftLocationsDropdown.php",
                type:"POST",
                data:{clientId:clientId},
                dataType:"html",
                success: function(data){
                    $('#shiftLocation').html('');
                    $('#shiftLocation').html(data);
                }
            });
        }
        function getEditShiftLocationsDropDown(clientId,addressId){
            $.ajax({
                url:"getClientShiftLocationsDropdown.php",
                type:"POST",
                data:{clientId:clientId,addressId:addressId},
                dataType:"html",
                success: function(data){
                    $('#eshiftLocation').html('');
                    $('#eshiftLocation').html(data);
                }
            });
        }
        var addShiftClick = null;
        $(document).on('click', '.addshift', function(e){
            $('.erMsg').html('');
            var tdDate = $(this).closest('td').attr('data-tddate');
            var canid = $(this).closest('td').attr('data-canid');
            var clid = $(this).closest('td').attr('data-clid');
            var stid = $(this).closest('td').attr('data-stid');
            var did = $(this).closest('td').attr('data-did');
            var empName = $(this).closest('td').attr('data-empName');
            var thDay =  $(this).closest('table').find('th').eq($(this).closest('td').index()).attr('data-day');

            var $row = $(this).closest("tr");
            targetRowId = $row.attr('id');
            rowCandidateId = $("#"+targetRowId);
            $('html, body').animate({scrollTop: rowCandidateId.offset().top }, 'slow');

            addShiftClick = $(this);
            addShiftDialog.data('tDate',tdDate);
            addShiftDialog.data('thDay',thDay);
            addShiftDialog.data('canid',canid);
            addShiftDialog.data('clid',clid);
            addShiftDialog.data('stid',stid);
            addShiftDialog.data('did',did);
            addShiftDialog.data('empName',empName);
            addShiftDialog.dialog("open");
            addShiftDialog.dialog("option", "title", 'New Shift On '+tdDate);
            var target = $(this);
            addShiftDialog.dialog('option', 'position', {
                my: 'top', at: 'top',of: target
            });
        });
        var errorClass = 'invalid';
        var errorElement = 'em';
        $("#shiftFrm").validate({
            errorClass: errorClass,
            errorElement: errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            rules: {
                shiftStart: {
                    required: true
                },
                shiftEnd: {
                    required: true
                }
            },
            messages: {
                shiftStart:{
                    required: "Please enter a note"
                },
                shiftEnd:{
                    required: "Please enter a note"
                }
            },
            submitHandler: function (form) {
                var shDate = $('#shiftDate').val();
                var shDay = $('#shiftDay').val();
                var clid = $('#clid').val();
                var stid = $('#stid').val();
                var did = $('#did').val();
                var canid = $('#canid').val();
                var shiftStart = $('#shiftStart').val();
                var shiftEnd = $('#shiftEnd').val();
                var workBreak = $('#break').val();
                var note = $('textarea#note').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var dateRange = $('#dateRange').val();
                var shiftCopy = $('input[name=shiftCopy]:checked', '#shiftFrm').val();
                var positionid = $('#expPosition :selected').val();
                var shStatus = $('#shStatus').val();
                var addressId = $('#shiftLocation :selected').val();
                var candidateId;
                if($('#employeeName').val() === ''){
                    candidateId = '';
                }else{
                    candidateId = $('#empSelected').val();
                }
                $.ajax({
                    url: "saveShift.php",
                    type:"POST",
                    dataType:"json",
                    data: {shDate : shDate, shDay : shDay, clid : clid, stid : stid, did : did, canid : canid, shiftStart : shiftStart, shiftEnd : shiftEnd, workBreak : workBreak, note : note, shiftCopy : shiftCopy, startDate : startDate, endDate : endDate, dateRange : dateRange, positionid : positionid,shStatus:shStatus,addressId:addressId},
                    success: function(data){
                        $.each(data, function(index, element) {
                            if(element.status=='shiftOverlap'){
                                console.log('OVERLAP'+element.status);
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                $('.erMsg').html('');
                                $('.erMsg').html('Shift Overlaps existing');
                            }else if(element.status=='shiftAdded'){
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                addShiftDialog.dialog("close");
                            }else if(element.status=='maxShifts'){
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                $('.erMsg').html('');
                                $('.erMsg').html('Employee is having two shifts in same date');
                            }else{
                                console.log('ERROR'+element.status);
                            }
                        });
                    }
                });
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        addShiftDialog = $("#shiftPopup").dialog({
            autoOpen: false,
            height: 400,
            width: 550,
            modal: true,
            open: function(event, ui) {

                $('#shiftDate').val(addShiftDialog.data('tDate'));
                $('#shiftDay').val(addShiftDialog.data('thDay'));
                $('#clid').val(addShiftDialog.data('clid'));
                getShiftLocationsDropDown(addShiftDialog.data('clid'));
                $('#stid').val(addShiftDialog.data('stid'));
                $('#did').val(addShiftDialog.data('did'));
                $('#canid').val(addShiftDialog.data('canid'));
                $('#empName').html(addShiftDialog.data('empName'));
                var clid = addShiftDialog.data('clid');
                var stid = addShiftDialog.data('stid');
                $('#shiftStart').timepicker({'step': 15 , 'timeFormat': 'H:i'});
                $('#shiftEnd').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});
                $("#shiftPopup").css({'overflow':'hidden'});
            },
            buttons: {
                Save: function(){
                    $('#shStatus').val('');
                    $('#shiftFrm').submit();
                },
                Cancel: function() {
                    $('#shStatus').val('');
                    addShiftDialog.dialog("close");
                },
                NotAvailable: function(){
                    $('#shStatus').val('N/A');
                    $('#shiftFrm').submit();
                }
            }
        });

        $(document).on('click', '.editshift', function(){
            $('.erMsg').html('');
            var empName = $(this).closest('td').attr('data-empName');
            var shiftid = $(this).closest('div').attr('data-shiftid');
            var addressId = $(this).closest('div').attr('data-addressId');
            var shiftdate = $(this).closest('div').attr('data-shiftdate');
            var clid = $(this).closest('div').attr('data-clid');
            var stid = $(this).closest('div').attr('data-stid');
            var did = $(this).closest('div').attr('data-did');
            var canid = $(this).closest('div').attr('data-canid');
            var shiftstart = $(this).closest('div').attr('data-shiftstart');
            var shiftend = $(this).closest('div').attr('data-shiftend');
            var ebreak = $(this).closest('div').attr('data-break');
            var shiftnote = $(this).closest('div').attr('data-shiftnote');
            var shiftStatus = $(this).closest('div').attr('data-shiftStatus');
            var shiftSMSStatus = $(this).closest('div').attr('data-shiftSMSStatus');
            getEditShiftLocationsDropDown(clid,addressId);
            editShiftDialog.data('shiftid',shiftid);
            editShiftDialog.data('addressId',addressId);
            editShiftDialog.data('eshiftDate',shiftdate);
            editShiftDialog.data('ecanid',canid);
            editShiftDialog.data('eclid',clid);
            editShiftDialog.data('estid',stid);
            editShiftDialog.data('edid',did);
            editShiftDialog.data('eshiftstart',shiftstart);
            editShiftDialog.data('eshiftend',shiftend);
            editShiftDialog.data('ebreak',ebreak);
            editShiftDialog.data('shiftnote',shiftnote);
            editShiftDialog.data('shiftStatus',shiftStatus);
            editShiftDialog.data('shiftSMSStatus',shiftSMSStatus);
            editShiftDialog.data('eempName',empName);
            editShiftDialog.dialog("open");
            editShiftDialog.dialog("option", "title", 'Edit Shift On '+shiftdate);
            var target = $(this);
            editShiftDialog.dialog('option', 'position', {
                my: 'top', at: 'top',of: target
            });
            var $row = $(this).closest("tr");
            targetRowId = $row.attr('id');
            rowCandidateId = $("#"+targetRowId);
            $('html, body').animate({scrollTop: rowCandidateId.offset().top }, 'slow');
        });
        var eerrorClass = 'invalid';
        var eerrorElement = 'em';
        $("#editshiftFrm").validate({
            eerrorClass: errorClass,
            eerrorElement: errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            rules: {
                eshiftStart: {
                    required: true
                },
                eshiftEnd: {
                    required: true
                }
            },
            messages: {
                eshiftStart:{
                    required: "Please enter a note"
                },
                eshiftEnd:{
                    required: "Please enter a note"
                }
            },
            submitHandler: function (form) {
                var shiftid = $('#shiftid').val();
                var eshDate = $('#eshiftDate').val();
                var eclid = $('#eclid').val();
                var estid = $('#estid').val();
                var edid = $('#edid').val();
                var ecanid = $('#ecanid').val();
                var eshiftEnd = $('#eshiftEnd').val();
                var eshiftStart = $('#eshiftStart').val();
                var eworkBreak = $('#ebreak').val();
                var enote = $('textarea#enote').val();
                var shiftStatus = $('input[name=shiftStatus]:checked', '#editshiftFrm').val();
                var candidateId = '';
                var addressId = $('#eshiftLocation :selected').val();
                if($('#employeeName').val() === ''){
                    candidateId = '';
                }else{
                    candidateId = $('#empSelected').val();
                }
                $.ajax({
                    url: "updateShift.php",
                    type:"POST",
                    dataType:"json",
                    data: {shiftid : shiftid, eshDate : eshDate, eclid : eclid, estid : estid, edid : edid, ecanid : ecanid, eshiftStart : eshiftStart, eshiftEnd : eshiftEnd, eworkBreak : eworkBreak, enote : enote, shiftStatus : shiftStatus,addressId:addressId},
                    success: function(data){
                        $.each(data, function(index, element) {
                            console.log('Elements>>>>>>'+element.status);
                            if(element.status=='shiftOverlap'){
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                $('.erMsg').html('');
                                $('.erMsg').html('Shift Overlaps existing');
                            }else if(element.status=='shiftUpdated'){
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                editShiftDialog.dialog("close");
                            }else if(element.status=='maxShifts'){
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                $('.erMsg').html('');
                                $('.erMsg').html('Employee is having two shifts in same date');
                            }else if(element.status == ''){
                                generateRosterTableHeader(headerReturn);
                                generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                                editShiftDialog.dialog("close");
                            }else{
                                console.log('UPDATE ERROR'+element.status);
                            }
                        });
                    }
                });
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        function removeShift(shiftid){
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "removeShift.php",
                type:"POST",
                dataType:"json",
                data: {shiftid : shiftid},
                success: function(data){
                    $.each(data, function(index, element) {
                        if(element.status=='removed'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            editShiftDialog.dialog("close");
                        }
                    });
                }
            });
        }
        $('#shiftStatus').hide();
        editShiftDialog = $("#editshiftPopup").dialog({
            autoOpen: false,
            height: 400,
            width: 550,
            modal: true,
            open: function(event, ui) {
                var shiftid = $('#shiftid').val(editShiftDialog.data('shiftid'));
                var eshiftDate = $('#eshiftDate').val(editShiftDialog.data('eshiftDate'));
                var eclid = $('#eclid').val(editShiftDialog.data('eclid'));
                console.log('EXADDRESSID'+editShiftDialog.data('addressId'));
                var estid = $('#estid').val(editShiftDialog.data('estid'));
                var edid = $('#edid').val(editShiftDialog.data('edid'));
                var ecanid = $('#ecanid').val(editShiftDialog.data('ecanid'));
                var eempName = $('#eempName').html(editShiftDialog.data('eempName'));
                var eshiftStart = $('#eshiftStart').val(editShiftDialog.data('eshiftstart'));
                var eshiftEnd = $('#eshiftEnd').val(editShiftDialog.data('eshiftend'));
                var ebreak = $('#ebreak').val(editShiftDialog.data('ebreak'));
                var enote = $('textarea#enote').val(editShiftDialog.data('shiftnote'));//$('#enote').text(editShiftDialog.data('shiftnote'));
                $('#eshiftStart').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});//'disableTextInput':true
                $('#eshiftEnd').timepicker({'step': 15 , 'timeFormat': 'H:i'});

                if(editShiftDialog.data('shiftStatus')=='CONFIRMED'){
                    $('#shiftStatus').prop('checked', true);
                }else{
                    $('#shiftStatus').prop('checked', false);
                }
                $("#editshiftPopup").css({'overflow':'hidden'});
            },
            buttons: {
                Save: function(){
                    $('#shStatus').val('');
                    $('#editshiftFrm').submit();
                },
                Delete: function(){
                    $('#shStatus').val('');
                    removeShift(editShiftDialog.data('shiftid'));
                },
                CancelShift: function() {
                    $('#shStatus').val('');
                    cancelShift($('#shiftid').val(),'CANCELLED',$('textarea#enote').val(),$('#econsultant').val());
                    editShiftDialog.dialog("close");
                }
            }
        });


        function cancelShift(shiftid,shiftStatus,shiftNote,consultant){
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "cancelShift.php",
                type:"POST",
                dataType:"text",
                data: {shiftid : shiftid,shiftStatus : shiftStatus,shiftNote :shiftNote,consultant : consultant},
                success: function(data){
                    if(data) {
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#departmentId :selected').val(), $('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                    }
                }
            });
        }
        /* sms Popup */
        loadSMSCreditBalance();
        function loadSMSCreditBalance(){
            $.ajax({
                url: "balanceCheck.php",
                type: "POST",
                dataType: "html",
                success: function(data) {
                    $('.creditBalance').html('');
                    $('.creditBalance').html(data);
                }
            });
        }
        function loadRecipients(cid,attempt){
            $.ajax({
                url: "smsList.php",
                type: "POST",
                dataType: "html",
                data: { cid : cid,attempt : attempt},
                success: function(data) {
                    $('.recipients').html('');
                    $('.recipients').html(data);
                    $('.nRecipients').html('');
                    $('.nRecipients').html($('#recipientCount').val());
                }
            });
        }
        function updateShiftSMS(shiftid){
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "updateShiftSMS.php",
                type: "POST",
                dataType: 'json',
                data: { shiftid : shiftid},
                success: function(data) {
                    $.each(data, function(index, element) {
                        if(element.status == 'Updated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            smsDialog.dialog("close");
                        }else if(element.status == 'AlreadyUpdated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            smsDialog.dialog("close");
                        }else{
                            $('.errMsg').html(data);
                            smsDialog.dialog("close");
                        }
                    });
                }
            });
        }

        smsDialog = $("#smsPopup").dialog({
            autoOpen: false,
            height: 500,
            width: 550,
            modal: true,
            open: function(event, ui) {
                $('#rCanId').val(smsDialog.data('rCanId'));
                $('#shiftid').val(smsDialog.data('shiftid'));

            },
            close: function(event, ui){
                $('#smsText').html('');
                $(this).dialog("close");
                $(this).find('form')[0].reset();
            }
        });

        $(document).on('click','.smsShiftLink', function(){
            var rCanId = $(this).closest('td').attr('data-canid');
            var shiftid = $(this).closest('div').attr('data-shiftid');
            var shiftdate = $(this).closest('div').attr('data-shiftdate');
            var shiftstart = $(this).closest('div').attr('data-shiftstart');
            var shiftend = $(this).closest('div').attr('data-shiftend');
            var client = $(this).closest('div').attr('data-client');
            var shiftDay = $(this).closest('div').attr('data-shiftday');
            var consultant = $('#conid').val();
            smsDialog.data('rCanId',rCanId);
            smsDialog.data('shiftid',shiftid);
            smsDialog.data('shiftdate',shiftdate);
            smsDialog.data('shiftstart',shiftstart);
            smsDialog.data('shiftend',shiftend);
            smsDialog.data('clientName',client);
            smsDialog.data('shiftday',shiftDay);
            $.ajax({
                url: "getShiftTimeInfo.php",
                type: "POST",
                dataType: "text",
                data: {shiftdate : shiftdate, client : client, shiftDay : shiftDay, shiftstart : shiftstart, shiftend : shiftend, consultant : consultant},
                success: function(data){
                }
            }).done(function(data){
                $('#smsText').html('');
                $('#smsText').html(data);
                loadRecipients(rCanId,0);
                smsDialog.dialog("open");
                smsDialog.dialog("option", "title", 'Send SMS');
            });
            var $row = $(this).closest("tr");
            targetRowId = $row.attr('id');
        });
        var smserrorClass = 'invalid';
        var smserrorElement = 'em';
        var smsFrm = $("#frmNewSMS").validate({
            smserrorClass	: errorClass,
            smserrorElement	: errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            rules: {
                smsText: {
                    required: true,
                    rangelength:[1,600]
                }
            },
            messages: {
                smsText: {
                    rangelength: function(range, input) {
                        return [
                            'You are only allowed between ',
                            range[0],
                            'and ',
                            range[1],
                            ' characters. ',
                            ' You have typed ',
                            $('#smsText').val().length,
                            ' characters'
                        ].join('');
                    }
                }
            },
            submitHandler: function (form) {
                var act = $('#act').val();
                var alertMe = $('input[name=alertMe]:checked', '#frmNewSMS').val();
                var smsAccount = $('#smsAccount option:selected').val();
                var smsText = $('textarea#smsText').val();
                var rcanid = $('#rCanId').val();
                var shiftid = $('#shiftid').val();
                $.ajax({
                    url: "sendSMS.php",
                    type: "POST",
                    dataType: "html",
                    data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                    success: function(data) {
                        if(data == 'MSGSENT'){
                            updateShiftSMS(shiftid);
                            $('#smsText').html('');
                        }else if(data == 'NORECIPIENTS'){
                            $('.errMsg').html(data);
                        }else{
                            console.log(data);
                        }
                    }
                });
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        smsAllDialog = $("#smsAllPopup").dialog({
            autoOpen: false,
            height: 500,
            width: 550,
            modal: true,
            open: function(event, ui) {
                $('#rAllCanId').val(smsAllDialog.data('rCanId'));
            },
            close: function(event, ui){
                $('#smsAllText').html('');
                $(this).dialog("close");
                $(this).find('form')[0].reset();
            }
        });
        $(document).on('click','.sendAllBtn', function(){
            $('#smsAllText').html('');
            smsAllDialog.data('smsAllText','');
            var rCanId = $(this).closest('td').attr('data-allcanid');
            var clid = $(this).closest('td').attr('data-clid');
            var stid = $(this).closest('td').attr('data-stid');
            var did = $(this).closest('td').attr('data-did');
            var strdate = $(this).closest('td').attr('data-strdate');
            var enddate = $(this).closest('td').attr('data-enddate');
            var consultant = $('#consultant').val();
            smsAllDialog.data('rAllCanId',rCanId);

            var $row = $(this).closest("tr");
            targetRowId = $row.attr('id');

            $.ajax({
                url: "getShiftId.php",
                type: "POST",
                dataType: "json",
                data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate},
                success: function(data){

                }
            }).done(function(data){
                smsAllDialog.data('allShiftId',data);
            });

            $.ajax({
                url: "getShiftData.php",
                type: "POST",
                dataType: "text",
                data: { rCanId : rCanId,consultant : consultant, clid : clid, stid : stid, did : did, strdate : strdate, enddate : enddate},
                success: function(data){

                }
            }).done(function(data){
                $('#smsAllText').html('');
                $('#smsAllText').html(data);
                smsAllDialog.data('smsAllText','');
                smsAllDialog.data('smsAllText',data);
                loadRecipients(rCanId,0);
                smsAllDialog.dialog("open");
                smsAllDialog.dialog("option", "title", 'Send All SMS');
            });
        });
        var smserrorClass = 'invalid';
        var smserrorElement = 'em';
        var smsAllFrm = $("#frmAllSMS").validate({
            smserrorClass	: errorClass,
            smserrorElement	: errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            rules: {
                smsAllText: {
                    required: true,
                    rangelength:[1,600]
                }
            },
            messages: {
                smsAllText: {
                    rangelength: function(range, input) {
                        return [
                            'You are only allowed between ',
                            range[0],
                            'and ',
                            range[1],
                            ' characters. ',
                            ' You have typed ',
                            $('#smsAllText').val().length,
                            ' characters'
                        ].join('');
                    }
                }
            },
            submitHandler: function (form) {
                var act = $('#actAll').val();
                var alertMe = $('input[name=alertAllMe]:checked', '#frmAllSMS').val();
                var smsAccount = $('#smsAllAccount option:selected').val();
                var smsText = $('textarea#smsAllText').val();
                var rcanid = $('#rAllCanId').val();
                var allshifts = smsAllDialog.data('allShiftId');

                $.ajax({
                    url: "sendSMS.php",
                    type: "POST",
                    dataType: "html",
                    data: { act : act, alertMe : alertMe, smsAccount : smsAccount, smsText : smsText},
                    success: function(data) {
                        if(data == 'MSGSENT'){
                            $.each(allshifts, function(index, element) {
                                updateAllShiftsSMS(element.shiftid);
                            });
                            $('#smsAllText').html('');
                            smsAllDialog.data('smsAllText','');
                        }else if(data == 'NORECIPIENTS'){
                            $('.errMsg').html(data);
                        }else{
                            console.log(data);
                        }
                    }
                });
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        function updateAllShiftsSMS(shiftid){
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "updateShiftSMS.php",
                type: "POST",
                dataType: 'json',
                data: { shiftid : shiftid},
                success: function(data) {
                    $.each(data, function(index, element) {
                        if(element.status == 'Updated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            smsAllDialog.dialog("close");
                        }else if(element.status == 'AlreadyUpdated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                            smsAllDialog.dialog("close");
                        }else{
                            $('.errMsg').html(data);
                            smsAllDialog.dialog("close");
                        }
                    });
                }
            });
        }
        $(document).on('click','.confirmAllBtn', function() {
            var canid = $(this).closest('td').attr('data-allcanid');
            var clid = $(this).closest('td').attr('data-clid');
            var stid = $(this).closest('td').attr('data-stid');
            var did = $(this).closest('td').attr('data-did');
            var strdate = $(this).closest('td').attr('data-strdate');
            var enddate = $(this).closest('td').attr('data-enddate');
            var consultant = $('#consultant').val();

            var $row = $(this).closest("tr");
            targetRowId = $row.attr('id');
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "confirmAllShifts.php",
                type: "POST",
                dataType: "text",
                data: {canid : canid,clid : clid,stid : stid,did : did,strdate : strdate, enddate : enddate, consultant : consultant},
                success: function(data) {
                    if(data){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                    }
                }
            });
        });
        $(document).on('click','.deleteAllBtn', function(){
            var canid = $(this).closest('td').attr('data-allcanid');
            var clid = $(this).closest('td').attr('data-clid');
            var stid = $(this).closest('td').attr('data-stid');
            var did = $(this).closest('td').attr('data-did');
            var strdate = $(this).closest('td').attr('data-strdate');
            var enddate = $(this).closest('td').attr('data-enddate');
            var consultant = $('#consultant').val();

            var $row = $(this).closest("tr");
            targetRowId = $row.attr('id');
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "deleteAllShifts.php",
                type: "POST",
                dataType: "text",
                data: {canid : canid,clid : clid,stid : stid,did : did,strdate : strdate, enddate : enddate, consultant : consultant},
                success: function(data) {
                    if(data){
                        generateRosterTableHeader(headerReturn);
                        generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val(),candidateId);
                    }
                }
            });
        });
        rosterNoteDialog = $("#rosterNotePopup").dialog({
            autoOpen: false,
            height: 500,
            width: 550,
            modal: true,
            open: function(event, ui) {

            }
        });
        $(document).on('click', '.rosterNote', function(){
            var roscanid = $(this).closest('span').attr('data-roscanid');
            $.ajax({
                url: "getRosterNotes.php",
                type: "POST",
                dataType: "text",
                data: {roscanid : roscanid},
                success: function(data) {
                    if(data.length > 0){
                        $('#rosterNoteTxt').html('');
                        $('#rosterNoteTxt').html(data);
                        rosterNoteDialog.dialog("open");
                        rosterNoteDialog.dialog("option", "title", 'Roster Notes');
                    }
                }
            });

        });
        /* Generate Roster Excel Sheet */
        $(document).on('click', '.genExcelBtn', function(){
            var param = $('#departmentId :selected').val();
            var num_th = $('.rosterTableHead th').length;
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var status = 'CONFIRMED';
            $.ajax({
                url: "genRosterExcel.php",
                type: "POST",
                dataType: "text",
                data: {param : param, num_th : num_th,startDate : startDate, endDate : endDate, status : status},
                success: function(data) {
                    window.open(data);
                }
            });
        });
        /* Generate Roster Excel with Unconfirmed Data */
        $(document).on('click', '.genAllExcelBtn', function(){
            var param = $('#departmentId :selected').val();
            var num_th = $('.rosterTableHead th').length;
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var status = 'UNCONFIRMED';
            $.ajax({
                url: "genRosterExcel.php",
                type: "POST",
                dataType: "text",
                data: {param : param, num_th : num_th,startDate : startDate, endDate : endDate,status:status},
                success: function(data) {
                    window.open(data);
                }
            });
        });
        /* Check roster by Candidate rows */
        function updateBulkShiftSMS(candidateId){
            var param = $('#departmentId :selected').val();
            var num_th = $('.rosterTableHead th').length;
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            $.ajax({
                url: "updateBulkSMS.php",
                type: "POST",
                dataType: 'json',
                data: { param : param,num_th:num_th,startDate:startDate,endDate:endDate,candidateId:candidateId},
                success: function(data) {
                    $.each(data, function(index, element) {
                        if(element.status == 'Updated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val());
                            smsBulkDialog.dialog("close");
                        }else if(element.status == 'AlreadyUpdated'){
                            generateRosterTableHeader(headerReturn);
                            generateRosterTableBody($('#departmentId :selected').val(),$('.rosterTableHead th').length,$('#expPosition :selected').val());
                            smsBulkDialog.dialog("close");
                        }else{
                            $('.errMsg').html(data);
                            smsBulkDialog.dialog("close");
                        }
                    });
                }
            });
        }
        smsBulkDialog = $('#smsBulkPopup').dialog({
            autoOpen: false,
            height:500,
            width:550,
            modal:true,
            closeText: false,
            open: function(event,ui){
                //$(".ui-dialog-titlebar-close").hide();
            },
            close: function(event, ui){
                $('#smsBulkText').html('');
                $(this).dialog("close");
                $(this).find('form')[0].reset();
            }
        });
        var smserrorClass = 'invalid';
        var smserrorElement = 'em';
        var smsFrm = $("#frmBulkSMS").validate({
            smserrorClass	: errorClass,
            smserrorElement	: errorElement,
            highlight: function(element) {
                $(element).parent().removeClass('state-success').addClass("state-error");
                $(element).removeClass('valid');
            },
            unhighlight: function(element) {
                $(element).parent().removeClass("state-error").addClass('state-success');
                $(element).addClass('valid');
            },
            rules: {
                smsBulkText: {
                    required: true,
                    rangelength:[1,600]
                }
            },
            messages: {
                smsBulkText: {
                    rangelength: function(range, input) {
                        return [
                            'You are only allowed between ',
                            range[0],
                            'and ',
                            range[1],
                            ' characters. ',
                            ' You have typed ',
                            $('#smsBulkText').val().length,
                            ' characters'
                        ].join('');
                    }
                }
            },
            submitHandler: function (form) {
                var bulkact = $('#bulkact').val();
                var bulkalertMe = $('input[name=bulkalertMe]:checked', '#frmBulkSMS').val();
                var smsBulkAccount = $('#smsBulkAccount option:selected').val();
                var smsBulkText = $('textarea#smsBulkText').val();
                $.ajax({
                    url: "sendSMS.php",
                    type: "POST",
                    dataType: "html",
                    data: { act : bulkact, alertMe : bulkalertMe, smsAccount : smsBulkAccount, smsText : smsBulkText},
                    success: function(data) {
                        if(data == 'MSGSENT'){
                            $.each(chkArray,function (index, value) {
                                updateBulkShiftSMS(value);
                            });
                            $('#smsBulkText').html('');
                            smsBulkDialog.dialog("close");
                        }else if(data == 'NORECIPIENTS'){
                            $('.errMsg').html(data);
                        }else{
                            console.log(data);
                        }
                    }
                });
            },
            errorPlacement : function(error, element) {
                error.insertAfter(element.parent());
            }
        });
        $(document).on('click','.chRow',function () {
            chkArray = [];
            $(".chRow:checked").each(function() {
                chkArray.push($(this).val());
            });
            $(".chRow:checkbox:not(:checked)").each(function () {
                loadRecipients(1,$(this).val());
            });
        });

        $(document).on('click','#bulkSMS',function () {
            var count = 0;
            $.each(chkArray,function (index, value) {
                loadRecipients(value,count++);
            });
            smsBulkDialog.dialog("open");
            smsBulkDialog.dialog("option", "title", 'Send Bulk SMS');
        });
        function removeRecipient(cid,sessId){
            $.ajax({
                url: "removeRecipients.php",
                type: "POST",
                dataType: "html",
                data: { cid : cid, sessId : sessId },
                success: function(data) {
                    $('.recipients').html('');
                    $('.recipients').html(data);
                    $('.nRecipients').html('');
                    $('.nRecipients').html($('#recipientCount').val());
                }
            });
        }
        $(document).on('click', '.recipientRemove', function(){
            var $row = $(this).closest("tr");
            var cand = $row.find('.cand').data('cand');
            var sessid = $row.find('.sessid').data('sessid');
            removeRecipient(cand,sessid);
        });
    });
</script>
<div class="loadDisplay"><!-- Place at bottom of page --></div>
</body>

</html>