<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
error_reporting(E_ERROR | E_PARSE);
if ($_SESSION['userSession'] == '')
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

        /*table.payrollTable {
            !* width: 100%; *! !* Optional *!
            !* border-collapse: collapse; *!
            border-spacing: 0;
            !*border: 2px solid black;*!
        }

        table.payrollTable tbody,
        table.payrollTable thead { display: block; }

        thead tr th {
            height: 30px;
            line-height: 30px;
            !* text-align: left; *!
        }

        table.payrollTable tbody {
            height: 500px;
            overflow-y: auto;
            overflow-x: auto;
        }

        tbody {!* border-top: 2px solid black;*! }

        tbody td, thead th {
            !* width: 20%; *! !* Optional *!
            !*border-right: 1px solid black;*!
            !* white-space: nowrap; *!
        }

        tbody td:last-child, thead th:last-child {
            border-right: none;
        }*/
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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <div class="error"></div>
        <form name="frmPayrollCheck" id="frmPayrollCheck" class="smart-form" method="post">
            <h2>Payroll Check</h2>
            <div>
                <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; border: 1px solid #ccc; width: 15%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                    <input type="hidden" name="startDate" id="startDate">
                    <input type="hidden" name="endDate" id="endDate">
                    <input type="hidden" name="dateRange" id="dateRange">
                </div>
                <div class="pull-left">
                    <label for="clientId" class="select">
                        <select name="clientId" id="clientId"  class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        </select><i></i></label>
                </div>
                <div class="pull-left">
                    <label for="expPosition" class="select">
                        <select name="expPosition" id="expPosition"  class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        </select><i></i></label>
                </div>
                <div class="pull-left">
                    <label for="expDepartment" class="select">
                        <select name="expDepartment" id="expDepartment"  class="form-control" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        </select><i></i></label>
                </div>
                <div class="pull-left">
                    <label for="employeeName" class="input">
                        <input id="employeeName" name="employeeName" type="text" style="background: #fff; cursor: pointer; width: 100%;" placeholder="Employee Name"/>
                    </label><input type="hidden" name="empSelected" id="empSelected"/>
                </div>
                <div class="pull-left">
                    <label for="weekendingDate" class="input">
                        <input type="text" name="weekendingDate" id="weekendingDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date"/>
                    </label>
                </div>
                <div class="pull-left">
                    <label for="timesheetStatus" class="input">
                        <select name="timesheetStatus" id="timesheetStatus" class="form-control">
                            <option value="All">All</option>
                            <option value="Saved">Timesheet Saved</option>
                            <option value="UnSaved">Timesheet UnSaved</option>
                        </select>
                    </label>
                </div>
                <div class="pull-left">
                    <label for="filterBtn" class="">
                        <button name="filterBtn" id="filterBtn" class="filterBtn btn btn-info reverse btn-square btn-sm"><i class="glyphicon glyphicon-filter"></i>&nbsp;Filter</button>
                    </label>
                </div>
                <div class="pull-left" style="padding: 0px 10px 0px 10px"><button name="genExcel" id="genExcel" class="genExcel pull-right btn btn-info btn-sm"><i class="glyphicon glyphicon-export fa fa-file-excel-o"></i>&nbsp;Generate Excel</button></div>

                <div style="clear: both;"></div>
            </div>
            <div>
                <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                    <div style="text-align: right; padding: 10px 10px 10px 10px"><button type="submit" name="submitBtn" id="saveParollCheck" class="saveParollCheck pull-right btn btn-info reverse btn-sm" value="SAVE"><i class="glyphicon glyphicon-file fa fa-save"></i>&nbsp; Save/Update</button></div>
                <?php } ?>

                <div style="width: fit-content">
                <table class="payrollTable table table-striped table-bordered" style="width: 100%; font-size: 10pt;">
                    <thead>
                    <tr>
                        <th><i class="fa fa-fw fa-calendar"></i>Shift Date</th>
                        <th><i class="fa fa-fw fa-user"></i>Employee</th>
                        <th><i class="fa fa-fw fa-user"></i>Client</th>
                        <th><i class="fa fa-fw fa-certificate"></i>Position &nbsp;
                            <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                                <label for="posCheck">Change All Positions</label> <input type="checkbox" name="posCheck" id="posCheck" value=""/>
                            <?php } ?>
                        </th>
                        <th><i class="fa fa-fw fa-indent"></i>Department &nbsp;
                            <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                                <label for="depCheck">Change All Departments</label> <input type="checkbox" name="depCheck" id="depCheck" value=""/>
                            <?php } ?>
                        </th>
                        <th><i class="fa fa-fw fa-tag"></i>JobCode</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Roster Start</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Roster End</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Roster WorkHours</th>
                        <th></th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Check In</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Check Out</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>ClockIn WorkHours</th>
                        <th></th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Supervisor Check In</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Supervisor Check Out</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>ClockIN Break / Supervisor Break time</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Supervisor Check WorkHours</th>
                        <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                        <th><input type="checkbox" id="selectAllSupervisor" /><i class="fa fa-fw fa-check"></i>SelectAll</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Payroll Start Time</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Payroll End Time</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Payroll Work Break</th>
                        <th><i class="fa fa-fw fa-clock-o"></i>Hours Worked</th>
                        <?php } ?>
                        <th><i class="fa fa-fw fa-clock-o"></i>Timesheet Saved Work Hours</th>
                        <th><i class="fa fa-fw fa-comment"></i>Comments</th>
                        <th>
                            <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                                <input type="checkbox" id="selectAll" /><i class="fa fa-fw fa-check"></i>SelectAll
                            <?php } ?>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="shiftBody">
                    </tbody>
                </table>
                    <input type="hidden" name="wkendingDate" id="wkendingDate" value=""/>
                </div>

                <br/><br/><br/><br/>

            </div>
            <div id="timeSheetDisplay"></div>
        </form>
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
<!-- PAGE RELATED PLUGIN(S)
<script src="js/tablesorter/jquery.tablesorter.min.js"></script> -->
<!--<script src="js/sorttable/sorttable.js"></script>-->
<script>
    $(document).ready(function(){
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });

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
            $('#dateRange').val(dateRange);
            $('#startDate').val(start.format('YYYY-MM-DD'));
            $('#endDate').val(end.format('YYYY-MM-DD'));
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


        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#wkendingDate').val(picker.startDate.format('YYYY-MM-DD'));
        });

        populateClients();
        function populateClients(){
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }

        function populateClientPositions(clientid){
            $.ajax({
                url:"getClientPositionsList.php",
                type:"POST",
                data:{clientid:clientid},
                dataType:"html",
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        }
        $(document).on('change','#clientId',function () {
            var clientid = $('#clientId :selected').val();
            console.log('CLIENT ID'+clientid);
            populateClientPositions(clientid);
            populateClientDepartments(clientid);
        });
        function populateClientDepartments(clientid){
            $.ajax({
                url:"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid},
                dataType:"html",
                success: function (data) {
                    $('#expDepartment').html('');
                    $('#expDepartment').html(data);
                }
            })
        }
        function loadJobCode(element,clientId,positionId,deptId){
            $.ajax({
                url:"getJobCode.php",
                type:"POST",
                dataType:"text",
                data:{ clientId : clientId, positionId : positionId,deptId:deptId},
                success: function(data){
                    console.log('GREATER'+data);
                    if(data.length>0){
                        //$('#saveParollCheck').show();
                        //console.log('GREATER'+data.length);

                        element.closest('td').next().find('input').val('');
                        element.closest('td').next().find('span').html('');
                        element.closest('td').next().find('input').val(data);
                        element.closest('td').next().find('span').html(data);

                    }else if(data.length==0){
                        //console.log('EQUAL'+data.length);
                        element.closest('td').next().find('input').val('');
                        element.closest('td').next().find('span').html('');
                        element.closest('td').next().find('input').val('None');
                        element.closest('td').next().find('span').html('None');
                        //$('#saveParollCheck').hide();
                    }else{
                        //console.log('ELSE'+data.length);
                        element.closest('td').next().find('input').val('');
                        element.closest('td').next().find('span').html('');
                        element.closest('td').next().find('input').val('None');
                        element.closest('td').next().find('span').html('None');
                        //$('#saveParollCheck').hide();
                    }
                }
            });
        }
        $('.ui-autocomplete-input').css('width','100px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
            }
        });
        $(document).on('change','.clid', function(){
            var clid = $(this).find('option:selected').val();
            var posid = $(this).closest('td').next().find('select').val();
            var deptid = $(this).closest('td').next().next().find('select').val();
            var nextElement = $(this).closest('td').next().next();
            loadJobCode(nextElement,clid,posid,deptid);
        });
        $(document).on('change','.posid', function(){
            var posid = $(this).find('option:selected').val();
            var clid = $(this).closest('td').prev().find('select').val();
            var dpid = $(this).closest('td').next().find('select').val();
            var nextElement = $(this).closest('td').next();
            loadJobCode(nextElement,clid,posid,dpid);
        });
        $(document).on('change','.deptId', function(){
            var dpid = $(this).find('option:selected').val();
            var posid = $(this).closest('td').prev().find('select').val();
            var clid = $(this).closest('td').prev().prev().find('select').val();
            console.log(dpid+posid+clid);
            loadJobCode($(this),clid,posid,dpid);
        });
        $(document).on('click','.deptId', function(){
            var dpid = $(this).find('option:selected').val();
            var posid = $(this).closest('td').prev().find('select').val();
            var clid = $(this).closest('td').prev().prev().find('select').val();
            console.log(dpid+posid+clid);
            loadJobCode($(this),clid,posid,dpid);
        });
        $(document).on('click', '.filterBtn', function(e){
            e.preventDefault();
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var deptid = $('#expDepartment :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var timesheetStatus = $('#timesheetStatus :selected').val();
            var candidateId;
            var action = 'GET';
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "getPayrollCheckData.php",
                type: "POST",
                dataType: "html",
                data: {
                    clientid: clientid,
                    positionid: positionid,
                    deptid:deptid,
                    startDate: startDate,
                    endDate: endDate,
                    candidateId:candidateId,
                    timesheetStatus:timesheetStatus,
                    action:action
                },
                success: function (data) {
                    $('.shiftBody').html(data);
                }
            }).done(function () {
            });
        });

        $(document).on('click', '.genExcel', function(e){
            e.preventDefault();
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var deptid = $('#expDepartment :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var timesheetStatus = $('#timesheetStatus :selected').val();
            var candidateId;
            var action = 'EXCEL';
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "getPayrollCheckData.php",
                type: "POST",
                dataType: "html",
                data: {
                    clientid: clientid,
                    positionid: positionid,
                    deptid:deptid,
                    startDate: startDate,
                    endDate: endDate,
                    candidateId:candidateId,
                    action:action,
                    timesheetStatus:timesheetStatus
                },
                success: function (data) {
                    window.open(data);
                }
            }).done(function () {
            });
        });

        $(document).on('click', '.pick_roster_time', function () {
            if($(this).is(":checked")) {
                $(this).closest('tr').find('td').find('.shStart').val($(this).closest('tr').find('td').find('.rosterStart').val());
                $(this).closest('tr').find('td').find('.shEnd').val($(this).closest('tr').find('td').find('.rosterEnd').val());
                $(this).closest('tr').find('td').find('.break').val(30);
                $(this).closest('tr').find('td').find('.hrs').val('');
                $(this).closest('tr').find('td').find('.totalHrs').text('');
                $(this).closest('tr').find('td').find('.pick_check_in_out_time').not(this).prop('checked', false);
                $(this).closest('tr').find('td').find('.pick_supervisor_time').not(this).prop('checked', false);
                /*$('input.pick_check_in_out_time').not(this).prop('checked', false);
                $('input.pick_supervisor_time').not(this).prop('checked', false);*/
                var shiftDate = $(this).closest('tr').find('td').find('.shDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.break').val();
                var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                var hrs = $(this).closest('tr').find('td').find('.hrs');
                $.ajax({
                    url: "getWrkHours.php",
                    type: "POST",
                    dataType: "text",
                    data: {
                        shiftDate: shiftDate,
                        shiftStart: shiftStart,
                        shiftEnd: shiftEnd,
                        shiftBreak: shiftBreak
                    },
                    success: function (data) {
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }
        });
        $(document).on('click', '.pick_check_in_out_time', function () {
            if($(this).is(":checked")) {
                $(this).closest('tr').find('td').find('.shStart').val($(this).closest('tr').find('td').find('.checkIn').val());
                $(this).closest('tr').find('td').find('.shEnd').val($(this).closest('tr').find('td').find('.checkOut').val());
                $(this).closest('tr').find('td').find('.break').val(30);
                $(this).closest('tr').find('td').find('.hrs').val('');
                $(this).closest('tr').find('td').find('.totalHrs').text('');
                $(this).closest('tr').find('td').find('.pick_roster_time').not(this).prop('checked', false);
                $(this).closest('tr').find('td').find('.pick_supervisor_time').not(this).prop('checked', false);
                /*$('input.pick_roster_time').not(this).prop('checked', false);
                $('input.pick_supervisor_time').not(this).prop('checked', false);*/
                var shiftDate = $(this).closest('tr').find('td').find('.shDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.break').val();
                var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                var hrs = $(this).closest('tr').find('td').find('.hrs');
                $.ajax({
                    url: "getWrkHours.php",
                    type: "POST",
                    dataType: "text",
                    data: {
                        shiftDate: shiftDate,
                        shiftStart: shiftStart,
                        shiftEnd: shiftEnd,
                        shiftBreak: shiftBreak
                    },
                    success: function (data) {
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }
        });
        $(document).on('click', '.pick_supervisor_time', function () {
            if($(this).is(":checked")) {
                $(this).closest('tr').find('td').find('.shStart').val($(this).closest('tr').find('td').find('.supervisorCheckIn').val());
                $(this).closest('tr').find('td').find('.shEnd').val($(this).closest('tr').find('td').find('.supervisorCheckOut').val());
                $(this).closest('tr').find('td').find('.break').val($(this).closest('tr').find('td').find('.supervisorBreakTime').val());

                $(this).closest('tr').find('td').find('.hrs').val('');
                $(this).closest('tr').find('td').find('.totalHrs').text('');
                $(this).closest('tr').find('td').find('.pick_roster_time').not(this).prop('checked', false);
                $(this).closest('tr').find('td').find('.pick_check_in_out_time').not(this).prop('checked', false);
                /*$('input.pick_roster_time').not(this).prop('checked', false);
                $('input.pick_check_in_out_time').not(this).prop('checked', false);*/
                var shiftDate = $(this).closest('tr').find('td').find('.shDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.break').val();
                var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                var hrs = $(this).closest('tr').find('td').find('.hrs');
                $.ajax({
                    url: "getWrkHours.php",
                    type: "POST",
                    dataType: "text",
                    data: {
                        shiftDate: shiftDate,
                        shiftStart: shiftStart,
                        shiftEnd: shiftEnd,
                        shiftBreak: shiftBreak
                    },
                    success: function (data) {
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }else{
                $(this).closest('tr').find('td').find('.shStart').val('');
                $(this).closest('tr').find('td').find('.shEnd').val('');
                $(this).closest('tr').find('td').find('.break').val('');
                $(this).closest('tr').find('td').find('.hrs').val('');
                $(this).closest('tr').find('td').find('.pick_roster_time').not(this).prop('checked', false);
                $(this).closest('tr').find('td').find('.pick_check_in_out_time').not(this).prop('checked', false);
            }
        });
        $(document).on('click','#selectAllSupervisor', function(){
            $('.pick_supervisor_time').trigger('click');
            /*$(this).closest('table').find('td .pick_supervisor_time').prop('checked', this.checked); //input:checkbox
            $('.pick_supervisor_time:checkbox:checked').each(function () {
                if($(this).is(":checked")) {
                    console.log('checked');
                }
            });
            $('.pick_supervisor_time:checkbox:unchecked').each(function () {
                $(this).prop('checked',false);
            });*/
        });
        $(document).on('click','#selectAll', function(){
            $(this).closest('table').find('td .timeSheetTick').prop('checked', this.checked); //input:checkbox
            $('.timeSheetTick:checkbox:checked').each(function () {
                if($(this).is(":checked")) {
                    var shiftDate = $(this).closest('tr').find('td').find('.shdate').val();
                    var shiftStart = $(this).closest('tr').find('td').find('.shStart').val();
                    var shiftEnd = $(this).closest('tr').find('td').find('.shEnd').val();
                    var shiftBreak = $(this).closest('tr').find('td').find('.break').val();
                    var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                    var hrs = $(this).closest('tr').find('td').find('.hrs');
                    if(shiftBreak === ''){
                        shiftBreak = 0;
                    }
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        dataType: "text",
                        data: {
                            shiftDate: shiftDate,
                            shiftStart: shiftStart,
                            shiftEnd: shiftEnd,
                            shiftBreak: shiftBreak
                        },
                        success: function (data) {
                            totalHrs.text(data);
                            hrs.val(data);
                        }
                    }).done(function () {

                    });
                }
            });
            $('.timeSheetTick:checkbox:unchecked').each(function () {
                if($(this).prop('checked') == false){
                    var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                    var hrs = $(this).closest('tr').find('td').find('.hrs');
                    totalHrs.text('');
                    hrs.val('');
                }
            });
        });

        $(document).on('change','.timeSheetTick', function(){
            if($(this).is(":checked")) {
                var shiftDate = $(this).closest('tr').find('td').find('.shdate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.break').val();
                var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                var hrs = $(this).closest('tr').find('td').find('.hrs');

                $.ajax({
                    url: "getWrkHours.php",
                    type: "POST",
                    dataType: "text",
                    data: {
                        shiftDate: shiftDate,
                        shiftStart: shiftStart,
                        shiftEnd: shiftEnd,
                        shiftBreak: shiftBreak
                    },
                    success: function (data) {
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }else{
                var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
                var hrs = $(this).closest('tr').find('td').find('.hrs');
                totalHrs.text('');
                hrs.val('');
            }
        });

        /*$("body").on('blur','input',function () {
            var shiftDate = $(this).closest('tr').find('td').find('.shdate').val();
            var shiftStart = $(this).closest('tr').find('td').find('.shStart').val();
            var shiftEnd = $(this).closest('tr').find('td').find('.shEnd').val();
            var shiftBreak = $(this).closest('tr').find('td').find('.break').val();
            var totalHrs = $(this).closest('tr').find('td').find('.totalHrs');
            var hrs = $(this).closest('tr').find('td').find('.hrs');

            $.ajax({
                url: "getWrkHours.php",
                type: "POST",
                dataType: "text",
                data: {
                    shiftDate: shiftDate,
                    shiftStart: shiftStart,
                    shiftEnd: shiftEnd,
                    shiftBreak: shiftBreak
                },
                success: function (data) {
                    totalHrs.text(data);
                    hrs.val(data);
                }
            });
        });
*/
        $.validator.addMethod('customValidation',function (value,element) {
            return value != 'All';
        },'Please select a value other than All');

        $(document).on('click','#saveParollCheck',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmPayrollCheck = $("#frmPayrollCheck").validate({
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
                    weekendingDate: {
                        required: true
                    },
                    expPosition: {
                        customValidation:true
                    },
                    expDepartment: {
                        customValidation:true
                    }
                },
                messages: {
                    weekendingDate: {
                        required: "Please select Weekending Date"
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        type: 'POST',
                        url: 'savePayrollCheck.php',
                        data: $('form#frmPayrollCheck').serialize(),
                        dataType:'text',
                        success: function (data) {
                            if(data == 'CHECKBOX'){
                                $('.error').html('Please tick check boxes');
                            }else if(data != 'DELETED') {
                                window.open(data);
                            }else{
                                $('.error').html(data);
                            }
                        }
                    });
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

        $(document).on('change','#posid0',function () {
            var selectedPosition = $('#posid0 :selected').val();
            if($('#posCheck').is(":checked")){
                $('.posid').each(function () {
                    $(this).val(selectedPosition).attr('selected');
                    var posid = $(this).find('option:selected').val();
                    var clid = $(this).closest('td').prev().find('select').val();
                    var dpid = $(this).closest('td').next().find('select').val();
                    loadJobCode($(this),clid,posid,dpid);
                });
            }
        });
        $(document).on('change','#deptId0',function () {
            var selectedDepartment = $('#deptId0 :selected').val();
            if($('#depCheck').is(":checked")){
                $('.deptId').each(function () {
                    $(this).val(selectedDepartment).attr('selected');
                    var dpid = $(this).find('option:selected').val();
                    var posid = $(this).closest('td').prev().find('select').val();
                    var clid = $(this).closest('td').prev().prev().find('select').val();
                    loadJobCode($(this),clid,posid,dpid);
                });
            }
        });
        $(document).on('click','.commentEdit', function(e){
            e.preventDefault();
            var comments = $(this).closest('td').find('textarea').val();
            var shift_id = $(this).attr('data-shift-id');
            $.ajax({
                type: 'POST',
                url: 'payroll-comments.php',
                data: {
                    shift_id:shift_id,
                    comments:comments
                },
                dataType:'text',
                success: function (data) {
                    console.log('response'+data);
                }
            });
        });

        // Change the selector if needed
        var $table = $('table.scroll'),
            $bodyCells = $table.find('tbody tr:first').children(),
            colWidth;

// Adjust the width of thead cells when window resizes
        $(window).resize(function() {
            // Get the tbody columns width array
            colWidth = $bodyCells.map(function() {
                return $(this).width();
            }).get();

            // Set the width of thead columns
            $table.find('thead tr').children().each(function(i, v) {
                $(v).width(colWidth[i]);
            });
        }).resize(); // Trigger resize handler


    });
</script>
<div class="modal"></div>
</body>

</html>