<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if($_SESSION['userType']!=='ACCOUNTS'){
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if(isset($_REQUEST['default'])){
    $_SESSION['clientIdFl'] = '';
    $_SESSION['subjectSearchTxt'] = '';
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
        <h2>Time Sheet Calculation</h2>
        <div class="error"></div>
        <div class="filterPanel">
            <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span></span> <b class="caret"></b>
                <input type="hidden" name="startDate" id="startDate">
                <input type="hidden" name="endDate" id="endDate">
                <input type="hidden" name="dateRange" id="dateRange">
            </div>
            <div class="pull-left">
                <label for="clientId" class="select">
                    <select name="clientId" id="clientId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="deptId" class="select">
                    <select name="deptId" id="deptId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="expPosition" class="select">
                    <select name="expPosition" id="expPosition" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="employeeName" class="input">
                    <input id="employeeName" name="employeeName" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
                </label><input type="hidden" name="empSelected" id="empSelected"/>
            </div>
            <div class="pull-left">
                <label for="dataType" class="select">
                    <select name="dataType" id="dataType"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <option value="TimeSheet">TimeSheet</option>
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="filterBtn">
                    <button name="filterBtn" id="filterBtn" class="filterBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon-spin"></i>&nbsp;Calculate TimeSheet</button>
                </label>
            </div>
            <!--<div class="pull-left">
                <label for="bulkFilterBtn">
                    <button name="bulkFilterBtn" id="bulkFilterBtn" class="bulkFilterBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon-spin"></i>&nbsp;Bulk Filter</button>
                </label>
            </div>-->
            <!--<div class="pull-left">
                    <label for="deductCodeDesc" class="input">
                        <input id="deductCodeDesc" name="deductCodeDesc" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Deduction Code Description"/>
                    </label><input type="hidden" name="deductCodeSelected" id="deductCodeSelected"/>
            </div>-->
            <div style="clear: both;"></div>

        </div>
        <!--<div id="deductionTable" width="60%"></div>-->
        <div class="timeSheetDiv">
            <div id="payruleAwd" style="color: red"></div>
            <form id="frmTimeSheet" method="post">
                <table id="shiftTable" border="1" cellpadding="2" cellspacing="2" class="timeSheetTable table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th data-class="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Employee ID</th>
                            <th data-class="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Shift Day</th>
                            <th data-class="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Shift Date</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Start Time</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Break</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>End Time</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Total Hours</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Breaking</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Day</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Early Morning</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Afternoon</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Night</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Saturday</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Sunday</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Public Holiday(T2.5)</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Overtime(T1.5)</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Doubletime(T2)</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Saturday Overtime</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Sunday Overtime</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Period Overtime</th>
                            <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Week Ending Date</th>
                        </tr>
                    </thead>
                    <tbody class="shiftBody">
                    </tbody>
                </table>


                <!-- <button type="button" name="exportPDFBtn" id="exportPDFBtn" class="exportPDFBtn pull-right btn btn-danger btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>Export PDF</button>
                 <button type="button" name="exportExcelBtn" id="exportExcelBtn" class="exportExcelBtn pull-right btn btn-success btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>Export Excel</button>
                 <button type="submit" name="saveTimeSheet" id="saveTimeSheet" class="saveTimeSheet pull-right btn btn-warning btn-sm"><i class="glyphicon glyphicon-file fa fa-save"></i>&nbsp; Save</button>-->
            </form>
        </div>
        <div id="timeSheetDisplay"></div>

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

        populateClients();
        function populateClients(){
            var dropSelect = 'N';
            $.ajax({
                url:"getClients.php",
                type:"POST",
                data:{dropSelect : dropSelect},
                dataType:"html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        function populateClientDepartments(clientid){
            var action = 'DEPARTMENTSFORJOBCODE';
            $.ajax({
                url:"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid,action:action},
                dataType:"html",
                success: function(data){
                    $('#deptId').html('');
                    $('#deptId').html(data);
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
            populateClientDepartments(clientid);
            populateClientPositions(clientid);
        });

        /*populateCandidatePositions();
        function populateCandidatePositions(){
            var dropSelect = 'N';
            $.ajax({
                url:"getCandidatePositionList.php",
                type:"POST",
                data:{dropSelect : dropSelect},
                dataType:"html",
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        }*/
        function loadJobCode(element,clientId,positionId){
            $.ajax({
                url:"getJobCode.php",
                type:"POST",
                dataType:"text",
                data:{ clientId : clientId, positionId : positionId},
                success: function(data){
                    if(data.length>0){
                        //$('#saveTimeSheet').show();
                            console.log('GREATER'+data.length);
                        element.closest('td').next().find('input').val('');
                        element.closest('td').next().find('span').html('');
                        element.closest('td').next().find('input').val(data);
                        element.closest('td').next().find('span').html(data);
                    }else if(data.length==0){
                        console.log('EQUAL'+data.length);
                        element.closest('td').next().find('input').val('');
                        element.closest('td').next().find('span').html('');
                        element.closest('td').next().find('input').val('None');
                        element.closest('td').next().find('span').html('None');
                        //$('#saveTimeSheet').hide();
                    }else{
                        console.log('ELSE'+data.length);
                        element.closest('td').next().find('input').val('');
                        element.closest('td').next().find('span').html('');
                        element.closest('td').next().find('input').val('None');
                        element.closest('td').next().find('span').html('None');
                        //$('#saveTimeSheet').hide();
                    }
                }
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
        $('#deductCodeDesc').autocomplete({
            source: <?php include "./deductCodeList.php"; ?>,
            select: function(event, ui) {
                var transCodeDesc = ui.item.value;
                var transCode = ui.item.id;
                $('#deductCodeSelected').val('');
                $('#deductCodeSelected').val(transCode);
                var candidateId = $('#empSelected').val();
                var weekendingDate = $('.wkendDate').val();
                updateDeductionCode(candidateId,transCode,weekendingDate);
            }
        });
        function loadDeductionCodes(candidateId,weekendingDate){
            var status = 'display';
            $.ajax({
                url:"updateEmployeeDeductionCodes.php",
                type:"POST",
                data:{candidateId:candidateId,weekendingDate:weekendingDate,status:status},
                dataType:"html",
                success: function(data){
                    $('#deductionTable').html('');
                    $('#deductionTable').html(data);
                }
            });
        }
        function updateDeductionCode(candidateId,transCode,weekendingDate){
            var status = 'add';
            $.ajax({
                url:"updateEmployeeDeductionCodes.php",
                type:"POST",
                data:{candidateId:candidateId,transCode : transCode,weekendingDate:weekendingDate,status:status},
                dataType:"html",
                success: function(data){
                    $('#deductionTable').html('');
                    $('#deductionTable').html(data);
                }
            });
        }
        $(document).on('click','.removeTransCodeBtn',function () {
            var candidateId = $(this).closest('td').attr('data-canid');
            var weekendingDate = $(this).closest('td').attr('data-wkdate');
            var did = $(this).closest('td').attr('data-did');
            var status = 'delete';
            //console.log('REMOVE'+candidateId+weekendingDate+did+status);
            $.ajax({
                url:"updateEmployeeDeductionCodes.php",
                type:"POST",
                data:{candidateId:candidateId,weekendingDate:weekendingDate,did:did,status:status},
                dataType:"html",
                success: function(data){
                    $('#deductionTable').html('');
                    $('#deductionTable').html(data);
                }
            });
        });
        $(document).on('change','.clid', function(){
            var clid = $(this).find('option:selected').val();
            var posid = $(this).closest('td').next().find('select').val();
            var nextElement = $(this).closest('td').next();
            loadJobCode(nextElement,clid,posid);
        });
        $(document).on('change','.posid', function(){
            var posid = $(this).find('option:selected').val();
            var clid = $(this).closest('td').prev().find('select').val();
            loadJobCode($(this),clid,posid);
        });
        $(document).on('click', '.filterBtn', function(){
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var deptId = $('#deptId :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            var dataType = $('#dataType :selected').val();
            if(dataType=='TimeSheet'){
                $.ajax({
                    url: "getTimeSheetData.php",
                    type: "POST",
                    dataType: "html",
                    data: {
                        clientid: clientid,
                        candidateId: candidateId,
                        positionid: positionid,
                        deptId:deptId,
                        startDate: startDate,
                        endDate: endDate
                    },
                    success: function (data) {
                        $('.shiftBody').html(data);
                        //loadDeductionCodes(candidateId,$('.wkendDate').val());
                    }
                });
            }
            $.ajax({
                url: "getPayRuleAward.php",
                type: "POST",
                dataType: "text",
                data: {
                    clientid: clientid,
                    positionid: positionid,
                    deptId:deptId
                },
                success: function (data) {
                    $('#payruleAwd').html(data);
                }
            });
        });
        /*$(document).on('click', '.bulkFilterBtn', function(){
            $('#saveTimeSheet').hide();
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var dataType = $('#dataType :selected').val();
            var bulkFilter = 1;
            if(dataType=='TimeSheet'){
                $.ajax({
                    url: "getTimeSheetData.php",
                    type: "POST",
                    dataType: "html",
                    data: {
                        clientid: clientid,
                        positionid: positionid,
                        startDate: startDate,
                        endDate: endDate,
                        bulkFilter:bulkFilter
                    },
                    success: function (data) {
                        $('.shiftBody').html(data);
                        //loadDeductionCodes(candidateId,$('.wkendDate').val());
                    }
                });
            }
        });*/
        $(document).on('click','#selectAll', function(){
            $(this).closest('table').find('td input:checkbox').prop('checked', this.checked);
        });


        $(document).on('click','.exportPDFBtn',function(){
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "genTimeSheetPdf.php",
                type: "POST",
                dataType: "text",
                data: {clientid : clientid, positionid : positionid,startDate : startDate, endDate : endDate,candidateId:candidateId},
                success: function(data) {
                    window.open(data);
                }
            });
        });
        $(document).on('click','.exportExcelBtn',function(){
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url: "genTimeSheetExcel.php",
                type: "POST",
                dataType: "text",
                data: {clientid : clientid, positionid : positionid,startDate : startDate, endDate : endDate,candidateId:candidateId},
                success: function(data) {
                    window.open(data);
                }
            });
        });
        $(document).on('click','.saveAllBtn', function(){
            $('.saveTotalBtn').trigger('click');
        });
        $(document).on('click','.saveTotalBtn',function () {
            var clientId = $('#clientId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var deptId = $('#deptId :selected').val();
            var candidateId = $(this).closest('td').attr('data-empid');
            var earlyMorningTotal = $(this).closest('td').attr('data-emgtotal');
            var ordTotal = $(this).closest('td').attr('data-ordtotal');
            var aftTotal = $(this).closest('td').attr('data-afttotal');
            var nightTotal = $(this).closest('td').attr('data-nighttotal');
            var rdoTotal = '0.00';
            var ovtTotal = $(this).closest('td').attr('data-ovttotal');
            var dblTotal = $(this).closest('td').attr('data-dbltotal');
            var satTotal = $(this).closest('td').attr('data-sattotal');
            var sunTotal = $(this).closest('td').attr('data-suntotal');
            var hldTotal = $(this).closest('td').attr('data-holtotal');
            var satovtTotal = $(this).closest('td').attr('data-satovttotal');
            var sunovtTotal = $(this).closest('td').attr('data-sunovttotal');
            var povtTotal = $(this).closest('td').attr('data-povttotal');
            var startDate = $(this).closest('td').attr('data-startdate');
            var endDate = $(this).closest('td').attr('data-enddate');
            var wkendDate = $('.wkendDate').val();
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetCalculation.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,deptId:deptId,candidateId:candidateId,ordTotal:ordTotal,earlyMorningTotal:earlyMorningTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,hldTotal:hldTotal,satovtTotal:satovtTotal,sunovtTotal:sunovtTotal,povtTotal:povtTotal,startDate:startDate,endDate:endDate,wkendDate:wkendDate},
                success: function (data) {
                    console.log('response..'+data);
                    if(data == 'inserted' || data == 'updated'){
                        //location.reload();
                        $('.error').html('');
                        $('.error').html('Saved');
                    }else{
                        console.log('Error..'+data);
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            });
        });
        $('form#frmTimeSheet').on('submit', function (e) {
            e.preventDefault();
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            var clientId = $('#clientId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var earlyMorningTotal = $('.earlyMorningTotal').val();
            var ordTotal = $('.ordTotal').val();
            var aftTotal = $('.aftTotal').val();
            var nightTotal = $('.ngtTotal').val();
            var satTotal = $('.saturdayTotal').val();
            var sunTotal = $('.sundayTotal').val();
            var ovtTotal = $('.ovtTotal').val();
            var dblTotal = $('.dblTotal').val();
            var hldTotal = $('.hldTotal').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var wkendDate = $('.wkendDate').val();

            /*$.ajax({
                type: 'post',
                url: 'saveTimeSheetCalculation.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,candidateId:candidateId,ordTotal:ordTotal,earlyMorningTotal:earlyMorningTotal,aftTotal:aftTotal,nightTotal:nightTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,hldTotal:hldTotal,startDate:startDate,endDate:endDate,wkendDate:wkendDate},
                success: function (data) {
                    if(data == 'inserted' || data == 'updated'){
                        location.reload();
                    }else{
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            });*/
        });
        /*$('#someInput').on('input', function() {
            $(this).val() // get the current value of the input field.
        });*/
        /*function generateRosterTableBody(param,num_th,positionid){
            $.ajax({
                url:"getAllocatedEmployees.php",
                type:"POST",
                dataType: "html",
                data:{param : param, num_th : num_th, headerGlobal : headerGlobal,positionid : positionid},
                success: function(data){
                    $('.rosterTableBody').html('');
                    $('.rosterTableBody').html(data);
                }
            }).done(function(){
                $('html, body').animate({scrollTop: rowCandidateId.offset().top }, 'slow');
            });
        }
        $(document).on('click', '#departmentId', function(){
            var param = $('#departmentId :selected').val();
            var num_th = $('.rosterTableHead th').length;
            var positionid = $('#expPosition :selected').val();
            generateRosterTableBody(param,num_th,positionid);
        });*/
    });
</script>
</body>

</html>