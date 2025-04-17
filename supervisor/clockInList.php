<?php
session_start();

require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
if(!isset($_SESSION['supervisorName'])){
    echo $_SESSION['supervisorName'];
}
/*if(!isset( $_SESSION['userSession'])|| time() - $_SESSION['login_time'] > 43200)
{
    updateLoggedInTime($mysqli,$_SESSION['userSession'],date("Y-m-d H:i:s"),'LOGIN EXPIRED');
    session_destroy();
}*/
if ($_SESSION['usrSession'] == '' &&  $_SESSION['user_type']!='SUPERVISOR')
{
    $msg = base64_encode("Access Denied");
    header("Location:index.php?error_msg=$msg");
}
/*$supervisorLock = getSuperviorStatus($mysqli,$_SESSION['supervisorId']);
if($supervisorLock == 'INACTIVE'){
    session_destroy();
    header("Location: lock.html");
}*/
?>
<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title><?php echo DOMAIN_NAME; ?> Admin</title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui.css">
    <!-- JQUERY UI AUTO COMPLETE STYLES -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery.ui.autocomplete.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/font-awesome.min.css">
    <!-- Jquery UI date range picker -->
    <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker.css" />
    <!-- Jquery UI date time picker -->
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-skins.min.css">

    <!-- Chandler Services Admin RTL Support -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-rtl.min.css">

    <!-- We recommend you use "your_style.css" to override Chandler Services Admin
         specific styles this will also ensure you retrain your customization with each Chandler Services Admin update.
    <link rel="stylesheet" type="text/css" media="screen" href="css/your_style.css"> -->

    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/demo.min.css">

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="../img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../img/favicon/favicon.ico" type="image/x-icon">

</head>

<body>
<header id="header">
    <div id="logo-group">
        <span id="logo"><img src="../img/logo.png"><i class=""></i></span>
    </div>
    <span id="extr-page-header-space"><span><?php echo 'Welcome '.$_SESSION['usrSession'].'....'; ?></span><span class="hidden-mobile hiddex-xs"></span> <a href="log_out.php">Logout</a> </span>
</header>

<div id="main" role="main" style="padding-top: 0px; margin-top: 0px">
    <!-- MAIN CONTENT -->
    <div id="content">
        <div style="padding: 0px 0px 5px 0px">
            <div style="font-size: 11px; font-weight: bold; color: #2a6395">N - UNCONFIRMED, E - EDIT, Y - CONFIRMED</div>
            <div align="center" class="error" style="color: red"><?php if(isset($_REQUEST['data'])) echo $_REQUEST['data']; echo $_REQUEST['test'];?></div>
            <div class="row" style="padding: 0px 0px 15px 0px">
                <div class="col col-sm-1" style="padding:0px 5px 0px 10px;">
                    <button id="exportPDFBtn" name="exportPDFBtn" class="btn btn-info pull-left"><i class="fa fa-file-pdf-o"></i> Export to PDF</button>
                </div>
                <div class="col col-sm-1" style="padding:0px 0px 0px 10px;">
                    <button id="exportBtn" name="exportBtn" class="btn btn-info pull-left"><i class="fa fa-file-excel-o"></i> Export Confirmed</button>
                </div>
                &nbsp;
                <div class="col col-sm-1" style="padding:0px 0px 0px 10px;">
                    <button id="exportUnConfirmedBtn" name="exportUnConfirmedBtn" class="btn btn-danger pull-left"><i class="fa fa-file-excel-o"></i> Export UnConfirmed</button>
                </div>
                &nbsp;
                <div class="col col-sm-1" style="padding:0px 0px 0px 20px;">
                    <button id="rosterBtn" name="rosterBtn" class="btn btn-info pull-left"><i class="fa fa-download"></i> Download Roster</button>
                </div>
            </div>
            <div class="row">
                        <section class="col col-sm-8">
                            <label class="input">
                                <select name="filterStatus" id="filterStatus" class="select pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                    <option value="N">UNCONFIRMED</option>
                                    <!-- <option value="E">EDIT</option>-->
                                    <option value="Y">CONFIRMED</option>
                                </select>
                                <select name="selector" id="selector" class="select pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">
                                    <!--<option value="All">All</option>-->
                                    <option value="Range">Range</option>
                                </select>
                                <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span></span> <b class="caret"></b>
                                    <input type="hidden" name="startDate" id="startDate">
                                    <input type="hidden" name="endDate" id="endDate">
                                    <input type="hidden" name="dateRange" id="dateRange">
                                </div>
                                <select name="deptId" id="deptId" class="select pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">

                                </select>
                                <select name="positionId" id="positionId" class="select pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc;">

                                </select>
                                <div class="pull-left">
                                    <input id="employeeName" name="employeeName" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
                                    <input type="hidden" name="empSelected" id="empSelected"/>
                                </div>
                                <button id="filterBtn" name="filterBtn" class="btn btn-info pull-left"><i class="glyphicon glyphicon-filter"></i>Filter</button>
                            </label>
                        </section>
                <section class="col col-sm-4">
                </section>
            </div>
            <form name="frmClockIn" id="frmClockIn" method="post">
                <div class="dataDisplay">
                </div>
            </form>
        </div>




    </div>
</div>

<!--================================================== -->
<link rel="stylesheet" type="text/css" media="screen" href="../css/styles.css">
<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script> if (!window.jQuery) { document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');} </script>
<!--<script src="../js/jquery-3.1.1.js"></script>-->
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script> if (!window.jQuery.ui) { document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');} </script>
<!-- IMPORTANT: APP CONFIG -->
<script src="../js/app.config.js"></script>
<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->
<!-- BOOTSTRAP JS -->
<script src="../js/bootstrap/bootstrap.min.js"></script>
<!-- MAIN APP JS FILE -->
<script src="../js/app.js"></script>
<!-- DATE RANGE PICKER -->
<script type="text/javascript" src="../js/daterangepicker/moment.js"></script>
<script type="text/javascript" src="../js/daterangepicker/daterangepicker.js"></script>
<!-- Login Checker -->
<!--<script src="loginChecker.js"></script>-->
<style>
    .tblContainer {
        overflow-y: auto;
        height: 350px;
        width: 100%;
    }
</style>
<script type="text/javascript">

$(document).ready(function () {
    console.log('jquery loading...');
    /* AJAX loading animation */
    $body = $("body");

    $(document).on({
        ajaxStart: function() { $body.addClass("loading"); },
        ajaxStop: function() { $body.removeClass("loading"); }
    });
    /* -  end  -*/

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
                url: "../getWrkHours.php",
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
                url: "../getWrkHours.php",
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
            url: "../getWrkHours.php",
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
    $('.ui-autocomplete-input').css('width','40px')
    $('#employeeName').autocomplete({
            source: <?php include "../supervisor/casualsList.php"; ?>,
            select: function(event, ui) {
            var empName = ui.item.value;
            var candidateId = ui.item.id;
            $('#empSelected').val('');
            $('#empSelected').val(candidateId);
        }
    });

    //var filterStatus = 'N';
    //var selector = 'All';
   // dataDisplay(filterStatus,selector);
    getDepartments();
    function getDepartments(){
        $.ajax({
            url: "departmentList.php",
            type: "POST",
            dataType: "html",
            success: function(data){
                console.log('d..........'+data);
                $('#deptId').html('');
                $('#deptId').html(data);
            }
        });
    }
    function dataDisplay(filterStatus,selector,startDate,endDate,empSelected,deptId,positionId){
        $.ajax({
            url: "dataDisplay.php",
            type: "POST",
            dataType: "html",
            data: {filterStatus:filterStatus,selector:selector,startDate:startDate,endDate:endDate,empSelected:empSelected,deptId:deptId,positionId:positionId},
            success: function(data){
                $('.dataDisplay').html('');
                $('.dataDisplay').html(data);
            }
        });
    }
    $(document).on('change','#deptId',function(){
        var deptId = $('#deptId :selected').val();
        $.ajax({
            url: "positionList.php",
            type: "POST",
            data:{deptId:deptId},
            dataType: "html",
            success: function(data){
                $('#positionId').html('');
                $('#positionId').html(data);
            }
        });
    });
    $(document).on('click','#filterBtn',function(){
        var empName = $('#employeeName').val();
        if(empName == ''){
            $('#empSelected').val('');
        }
        var filterStatus = $('#filterStatus :selected').val();
        var selector = $('#selector :selected').val();
        var startDate = $('#startDate').val();
        var endDate = $('#endDate').val();
        var empSelected = $('#empSelected').val();
        var deptId = $('#deptId :selected').val();
        var positionId = $('#positionId :selected').val();
        dataDisplay(filterStatus,selector,startDate,endDate,empSelected,deptId,positionId);
    });

    //var start = moment().subtract(29, 'days');
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

    $('#reportrange').hide();
    $(document).on('click','#selector',function() {
        var selector = $('#selector').val();
        if (selector == 'All') {
            $('#startDate').val('');
            $('#endDate').val('');
            $('#reportrange').hide();
        } else {
            $('#reportrange').show();
        }
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
                url: "../getWrkHours.php",
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
                url: "../getWrkHours.php",
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

    $('form#frmClockIn').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'approveTimesheets.php',
            dataType: "text",
            data: $('form').serialize(),
            success: function (data) {
                if(data == 'updated'){
                    $('.error').html('');
                    $('.error').html(data);
                    location.reload();
                }else if(data == 'login') {
                    $('.error').html('');
                    $('.error').html('Error in Supervisor Validation, Please re-login');
                }else{
                    $('.error').html('');
                    $('.error').html(data);
                }
            }
        });
    });

    $(document).on('click','#exportBtn', function () {
        var selector = $('#selector').val();
        var empSelected = $('#empSelected').val();
        var deptId = $('#deptId :selected').val();
        var startDate;
        var endDate;
        var action = 'EXCEL';
        if (selector == 'All') {
            startDate = '';
            endDate = '';
        }else{
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
        }
        if(empSelected == ''){
            empSelected = '';
        }

        $.ajax({
            url: "exportClockIn.php",
            type: "POST",
            dataType: "text",
            data: {
                startDate:startDate,
                endDate: endDate,
                empSelected: empSelected,
                deptId:deptId,
                action:action
            },
            success: function (data) {
                window.open(data);
            }
        });
    });
    $(document).on('click','#exportUnConfirmedBtn', function () {
        var selector = $('#selector').val();
        var empSelected = $('#empSelected').val();
        var deptId = $('#deptId :selected').val();
        var positionId = $('#positionId :selected').val();
        var startDate;
        var endDate;
        var action = 'EXCEL';
        if (selector == 'All') {
            startDate = '';
            endDate = '';
        }else{
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
        }
        if(empSelected == ''){
            empSelected = '';
        }

        $.ajax({
            url: "exportUnConfirmedClockIn.php",
            type: "POST",
            dataType: "text",
            data: {
                startDate:startDate,
                endDate: endDate,
                empSelected: empSelected,
                deptId:deptId,
                positionId:positionId,
                action:action
            },
            success: function (data) {
                window.open(data);
            }
        });
    });
    $(document).on('click','#exportPDFBtn', function () {
        var selector = $('#selector').val();
        var empSelected = $('#empSelected').val();
        var deptId = $('#deptId :selected').val();
        var startDate;
        var endDate;
        var action = 'PDF';
        if (selector == 'All') {
            startDate = '';
            endDate = '';
        }else{
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
        }
        if(empSelected == ''){
            empSelected = '';
        }

        $.ajax({
            url: "exportClockIn.php",
            type: "POST",
            dataType: "text",
            data: {
                startDate:startDate,
                endDate: endDate,
                empSelected: empSelected,
                deptId:deptId,
                action:action
            },
            success: function (data) {
                window.open(data);
            }
        });
    });
    $(document).on('click','#rosterBtn', function () {
        var selector = $('#selector').val();
        var empSelected = $('#empSelected').val();
        var deptId = $('#deptId :selected').val();
        var startDate;
        var endDate;
        var action = 'ROSTER';
        if (selector == 'All') {
            startDate = '';
            endDate = '';
        }else{
            startDate = $('#startDate').val();
            endDate = $('#endDate').val();
        }
        if(empSelected == ''){
            empSelected = '';
        }

        $.ajax({
            url: "exportClockIn.php",
            type: "POST",
            dataType: "text",
            data: {
                startDate:startDate,
                endDate: endDate,
                empSelected: empSelected,
                deptId:deptId,
                action:action
            },
            success: function (data) {
                window.open(data);
            }
        });
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
});
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>