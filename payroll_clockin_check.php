<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
/*if($_SESSION['userType']!=='ACCOUNTS'){
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}*/
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
</head>
<body>
<header id="header">
    <?php include "template/top_menu.php"; ?>
</header>
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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Payroll ClockIN ClockOut Check </h2>
        <div class="error"></div>
        <form name="frmClockInFilter" id="frmClockInFilter" class="smart-form" method="post">
            <div class="row">
                <section class="col col-lg-1">
                        <select name="filterStatus" id="filterStatus" class="form-control">
                            <option value="N">UNCONFIRMED</option>
                            <option value="Y">CONFIRMED</option>
                        </select>
                </section>
                <section class="col col-lg-3">
                        <div id="reportrange" class="form-control"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span></span> <b class="caret"></b>
                            <input type="hidden" name="startDate" id="startDate">
                            <input type="hidden" name="endDate" id="endDate">
                            <input type="hidden" name="dateRange" id="dateRange">
                        </div>
                </section>
                <section class="col col-lg-1">
                    <select name="clientId" id="clientId" class="form-control">
                    </select>
                </section>
                <!--<section class="col col-lg-1">
                    <select name="deptId" id="deptId" class="form-control">
                    </select>
                </section>
                <section class="col col-lg-1">
                    <select name="positionId" id="positionId" class="form-control">
                    </select>
                </section>-->
                <section class="col col-lg-1">
                             <!--<input id="employeeName" name="employeeName" type="text" class="form-control" placeholder="Employee Name"/>
                            <input type="hidden" name="empSelected" id="empSelected"/>-->
                            <button id="filterBtn" type="button" name="filterBtn" class="btn btn-sm btn-info"><i class="fa fa-filter"></i>&nbsp;Filter</button>
                </section>
            </div>
        </form>
        <form name="frmClockIn" id="frmClockIn" method="post">
        <div class="dataDisplay" style="overflow-x: scroll"></div>
        </form>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<script type="text/javascript" src="js/daterangepicker/moment.js"></script>
<script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<script src="js/jqueryform/jquery.form.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        let start = moment();
        let end = moment();
        let weekday=new Array(7);
        weekday[0]="Sun";
        weekday[1]="Mon";
        weekday[2]="Tue";
        weekday[3]="Wed";
        weekday[4]="Thu";
        weekday[5]="Fri";
        weekday[6]="Sat";
        let headerGlobal = [];
        let headerReturn = [];
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
            autoApply:true,
            startDate: start,
            endDate: end
            /*
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }*/
        }, dateCalendar);
        dateCalendar(start, end);
        $(document).on('click','#selector',function() {
            let selector = $('#selector').val();
            if (selector == 'All') {
                $('#startDate').val('');
                $('#endDate').val('');
                $('#reportrange').hide();
            } else {
                $('#reportrange').show();
            }
        });
        $('body').on('keypress', 'input', function(e) {
            var code = e.keyCode || e.which;
            if (code == '9') {
                var shiftDate = $(this).closest('tr').find('td').find('.shiftDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shiftStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shiftEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.wrkBreak').val();
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
                        totalHrs.text('');
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }
        });
        $('body').on('keydown', 'input', function(e) {
            var code = e.keyCode || e.which;
            if (code == '9') {
                var shiftDate = $(this).closest('tr').find('td').find('.shiftDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shiftStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shiftEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.wrkBreak').val();
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
                        totalHrs.text('');
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }
        });
        $("body").on('blur','input',function () {
            var shiftDate = $(this).closest('tr').find('td').find('.shiftDate').val();
            var shiftStart = $(this).closest('tr').find('td').find('.shiftStart').val();
            var shiftEnd = $(this).closest('tr').find('td').find('.shiftEnd').val();
            var shiftBreak = $(this).closest('tr').find('td').find('.wrkBreak').val();
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
                    totalHrs.text('');
                    totalHrs.text(data);
                    hrs.val(data);
                }
            });
        });
        $(document).on('click','#selectAll', function(){
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
            $('.checkTick:checkbox:checked').each(function () {
                var shiftDate = $(this).closest('tr').find('td').find('.shiftDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shiftStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shiftEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.wrkBreak').val();
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
        });
        $(document).on('change','.checkTick', function(){
            if($(this).is(":checked")) {
                var shiftDate = $(this).closest('tr').find('td').find('.shiftDate').val();
                var shiftStart = $(this).closest('tr').find('td').find('.shiftStart').val();
                var shiftEnd = $(this).closest('tr').find('td').find('.shiftEnd').val();
                var shiftBreak = $(this).closest('tr').find('td').find('.wrkBreak').val();
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
                        totalHrs.text('');
                        totalHrs.text(data);
                        hrs.val(data);
                    }
                });
            }
        });
        $(document).on('click', '.breakTick', function () {
            if($(this).is(":checked")) {
                $(this).closest('tr').find('td').find('.wrkBreak').val(0);
            }else{
                $(this).closest('tr').find('td').find('.wrkBreak').val(30);
            }
        });
        $(document).on('click','.checkOutTick', function () {
            if($(this).is(":checked")) {
                $(this).closest('tr').find('td').find('.shiftEnd').val($(this).closest('tr').find('td').find('.actualCheckOut').val());
            }/*else{
            $(this).closest('tr').find('td').find('. ').val(30);
        }*/
        });
        getClients();
        function getClients(){
            $.ajax({
                url: "getClients.php",
                type: "POST",
                dataType: "html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        function clockInDataDisplay(filterStatus,startDate,endDate,clientId,){
            $.ajax({
                url: "time_clock_info.php",
                type: "POST",
                dataType: "html",
                data: {filterStatus:filterStatus,startDate:startDate,endDate:endDate,clientId:clientId},
                success: function(data){
                    $('.dataDisplay').html('');
                    $('.dataDisplay').html(data);
                }
            });
        }
        $(document).on('click','#filterBtn',function(){
            let filterStatus = $('#filterStatus :selected').val();
            let startDate = $('#startDate').val();
            let endDate = $('#endDate').val();
            let clientId = $('#clientId :selected').val();
            clockInDataDisplay(filterStatus,startDate,endDate,clientId);
        });
        $('form#frmClockIn').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'approve_payroll_clockin_check.php',
                dataType: "text",
                data: $('form').serialize(),
                success: function (data) {
                    console.log('response '+data);
                    /*if(data == 'updated'){
                        $('.error').html('');
                        $('.error').html(data);
                        //location.reload();
                    }else if(data == 'login') {
                        $('.error').html('');
                        $('.error').html('Error in Validation, Please re-login');
                    }else{
                        $('.error').html('');
                        $('.error').html(data);
                    }*/
                }
            });
        });
    });
</script>
<div class="modal"></div>
</body>
</html>