<?php
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
        <div class="rosterColorDefinition"><div class="rosterLabel">Default:&nbsp;</div><div class="rosterDefault">&nbsp;</div><div class="rosterLabel">SMS Sent:&nbsp;</div><div class="rosterSMSSent">&nbsp;</div><div class="rosterLabel">Shift Confirmed:&nbsp;</div><div class="rosterShiftConfirmed">&nbsp;</div><div class="rosterLabel">Shift Cancelled:&nbsp;</div><div class="rosterShiftCancelled">&nbsp;</div><div class="rosterLabel">Not Available For Roster:&nbsp;</div><div class="rosterNotAvailable">N/A</div><div class="rosterLabel">Shift Unfilled:&nbsp;</div><div class="rosterShiftUnfilled">&nbsp;</div></div>
        <div style="clear:both;"></div>
        <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
            <span></span> <b class="caret"></b>
        </div>
        <div class="pull-left">
            <label for="expPosition" class="select">
                <select name="expPosition" id="expPosition"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                </select><i></i></label>
        </div>
        <div class="pull-left">
            <label for="departmentId" class="select">
                <select name="departmentId" id="departmentId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                </select><i></i></label>
        </div>
        <div class="pull-left">
            <label for="employeeName" class="input">
                <input id="employeeName" name="employeeName" type="text"   class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
            </label><input type="hidden" name="empSelected" id="empSelected"/>
        </div>
        <div class="pull-left">
            <label for="filterBtn">
                <button name="scheduleBtn" id="scheduleBtn" class="scheduleBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon-time"></i>&nbsp;Schedule</button>
            </label>
        </div>
        <div class="pull-left" id="searchedPerson"></div>
        <div class="pull-right">
            <button class="genExcelBtn btn btn-success btn-sm" type="button"><i class="glyphicon glyphicon-file fa fa-file-excel-o"></i>&nbsp;Generate Excel</button>
        </div>
        <div id="rosterTable">
            <table class="rosterTable table table-striped table-bordered table-hover" width="100%" border="1" cellspacing="0" cellpadding="0">
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
                    <section class="col col-12" style="width:100%;">
                        <label class="textarea textarea-resizable">
                            <textarea rows="8" class="custom-scroll" name="enote" id="enote" placeholder="Edit note to this shift .... "></textarea>
                        </label>
                    </section>
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
        var targetRowId;
        var tableContainer = $('div #rosterTable');
        var addShiftDialog;
        var editShiftDialog;
        var smsDialog;
        var rosterNoteDialog;
        var rowCandidateId = null;
        var form;
        var eform;
        var smsForm;
        var smsAllForm;
        var start = moment().subtract(29, 'days');
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
        function generateRosterTableHeader(header){
            var row = '';
            for(var headerItem in header){
                row += '<th data-date="'+header[headerItem]['headerFullDate']+'" data-day="'+header[headerItem]['headerDay']+'" class="rosterTableHeaderCell">'+header[headerItem]['headerDay']+'<br>'+header[headerItem]['headerDate']+'</th>';
            }
            $('.rosterTableHead').html('');
            $('.rosterTableBody').html('');
            $('.rosterTableHead').html('<th class="rosterTableHeaderCell">&nbsp;&nbsp;&nbsp;Roster Scheduling&nbsp;&nbsp;&nbsp;</th>'+row+'<th class="rosterTableAction">&nbsp;</th>');
            $('.rosterTable').css("width","95%");
            $('.rosterTable').css("overflow","auto");
        }

        $('#reportrange').daterangepicker({
            "autoApply": true,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
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
            $.ajax({
                url:"getCandidatePositionList.php",
                type:"POST",
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
                type:"POST",
                dataType: "html",
                data:{param : param, num_th : num_th, headerGlobal : headerGlobal,positionid : positionid, candidateId : candidateId},
                success: function(data){
                    $('.rosterTableBody').html('');
                    $('.rosterTableBody').html(data);
                    sortRosterTableBody();
                }
            }).done(function(){
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
                    //console.log(currentRowId+'has no class');
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
                    //console.log('newStartDate'+timeStamp);
                    shiftInfo.push({rowId:currentRowId, stTime: timeStamp});
                }else{
                    shiftInfo.push({rowId:currentRowId, stTime: 123456789123456789});
                }
                /*if ($(this).find('td .shiftDisplay').length >0){
                 console.log(currentRowId+' has class');
                 console.log('start>>>'+$(this).find('td .shiftDisplay').attr('data-shiftstart'));
                 console.log('startNext>>>'+$(this).next().find('td .shiftDisplay').attr('data-shiftstart'));
                 var thisStartTime = $(this).find('td .shiftDisplay').attr('data-shiftstart');
                 var nextStartTime = $(this).next().find('td .shiftDisplay').attr('data-shiftstart');
                 var stt = new Date($(this).find('td .shiftDisplay').attr('data-shiftdate')+thisStartTime);
                 var ntt = new Date($(this).next().find('td .shiftDisplay').attr('data-shiftdate')+nextStartTime);
                 if(thisStartTime != undefined && nextStartTime != undefined){
                 if(stt.getTime() > ntt.getTime()){
                 $('#'+currentRowId).insertAfter('#'+nextRowId);
                 }else if(ntt.getTime() < stt.getTime()){
                 $('#'+nextRowId).insertAfter('#'+currentRowId);
                 }else if(ntt.getTime() > stt.getTime()){
                 $('#'+nextRowId).insertAfter('#'+currentRowId);
                 }
                 }else if(thisStartTime == undefined && nextStartTime != undefined){
                 $('#'+currentRowId).insertAfter('#'+nextRowId);
                 }else if(thisStartTime != undefined && nextStartTime == undefined){
                 $('#'+nextRowId).insertAfter('#'+currentRowId);
                 }else if(thisStartTime == undefined && nextStartTime == undefined){
                 $('#'+currentRowId).insertAfter('#'+lastRowId);
                 $('#'+nextRowId).insertAfter('#'+lastRowId);
                 }
                 }*/
            });
            shiftInfo.sort(function(obj1,obj2){
                return obj1.stTime - obj2.stTime;
            });
            $.each(shiftInfo, function (index, value) {
                //console.log( value.rowId + ' : ' + value.stTime);
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
        /*$(document).on('click', '#departmentId', function(){
         var param = $('#departmentId :selected').val();
         var num_th = $('.rosterTableHead th').length;
         var positionid = $('#expPosition :selected').val();
         generateRosterTableBody(param,num_th,positionid);
         });*/
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
                    data: {shDate : shDate, shDay : shDay, clid : clid, stid : stid, did : did, canid : canid, shiftStart : shiftStart, shiftEnd : shiftEnd, workBreak : workBreak, note : note, shiftCopy : shiftCopy, startDate : startDate, endDate : endDate, dateRange : dateRange, positionid : positionid},
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
                    $('#shiftFrm').submit();

                },
                Cancel: function() {
                    addShiftDialog.dialog("close");
                }
            }
        });

        $(document).on('click', '.editshift', function(){
            $('.erMsg').html('');
            var empName = $(this).closest('td').attr('data-empName');
            var shiftid = $(this).closest('div').attr('data-shiftid');
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
            editShiftDialog.data('shiftid',shiftid);
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
                var eshiftStart = $('#eshiftStart').val();
                var eshiftEnd = $('#eshiftEnd').val();
                var eworkBreak = $('#ebreak').val();
                var enote = $('textarea#enote').val();
                var shiftStatus = $('input[name=shiftStatus]:checked', '#editshiftFrm').val();
                var candidateId;
                if($('#employeeName').val() === ''){
                    candidateId = '';
                }else{
                    candidateId = $('#empSelected').val();
                }
                $.ajax({
                    url: "updateShift.php",
                    type:"POST",
                    dataType:"json",
                    data: {shiftid : shiftid, eshDate : eshDate, eclid : eclid, estid : estid, edid : edid, ecanid : ecanid, eshiftStart : eshiftStart, eshiftEnd : eshiftEnd, eworkBreak : eworkBreak, enote : enote, shiftStatus : shiftStatus},
                    success: function(data){
                        $.each(data, function(index, element) {
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
                            }else{
                                console.log('URROR'+element.status);
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
                var estid = $('#estid').val(editShiftDialog.data('estid'));
                var edid = $('#edid').val(editShiftDialog.data('edid'));
                var ecanid = $('#ecanid').val(editShiftDialog.data('ecanid'));
                var eempName = $('#eempName').html(editShiftDialog.data('eempName'));
                var eshiftStart = $('#eshiftStart').val(editShiftDialog.data('eshiftstart'));
                var eshiftEnd = $('#eshiftEnd').val(editShiftDialog.data('eshiftend'));
                var ebreak = $('#ebreak').val(editShiftDialog.data('ebreak'));
                var enote = $('textarea#enote').val(editShiftDialog.data('shiftnote'));//$('#enote').text(editShiftDialog.data('shiftnote'));
                $('#eshiftStart').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});
                $('#eshiftEnd').timepicker({ 'step': 15 , 'timeFormat': 'H:i'});



                if(editShiftDialog.data('shiftStatus')=='CONFIRMED'){
                    $('#shiftStatus').prop('checked', true);
                }else{
                    $('#shiftStatus').prop('checked', false);
                }
                $("#editshiftPopup").css({'overflow':'hidden'});
            },
            buttons: {
                Save: function(){
                    $('#editshiftFrm').submit();
                },
                Delete: function(){
                    removeShift(editShiftDialog.data('shiftid'));
                },
                Cancel: function() {
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
                data: {shiftdate : shiftdate, client : client, shiftDay : shiftDay, shiftstart : shiftstart, shiftend : shiftend, consultant : consultant,rCanId:rCanId},
                success: function(data){

                }
            }).done(function(data){
                $('#smsText').html('');
                //$('#smsText').html('Hello, below is your shift for '+smsDialog.data('shiftdate')+'\n at '+smsDialog.data('clientName')+' \n'+smsDialog.data('shiftday')+': '+smsDialog.data('shiftstart')+' - '+smsDialog.data('shiftend')+'\nPLEASE REPLY TO CONFIRM ASAP\nTHAT you will be able to work on this day at the specified time above or please call if you can\'t work. Regards '+$('#conid').val()+' @ Chandler');
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
                $('#smsAllText').html(data);//smsAllDialog.data('smsAllText')
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
            $.ajax({
                url: "genRosterExcel.php",
                type: "POST",
                dataType: "text",
                data: {param : param, num_th : num_th,startDate : startDate, endDate : endDate},
                success: function(data) {
                    window.open(data);
                }
            });
        });

        /*$( "*", document.body ).click(function( event ) {
         var offset = $(this).offset();
         offset({ top: offset.top, left: offset.left});
         console.log('OFFSET'+offset.left + ',' + offset.top)
         });*/
    });
</script>
</body>

</html>