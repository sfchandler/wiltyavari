<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Timesheet Manual Entry Totals</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
                <form id="frmTimesheetTotals" method="post" class="smart-form">
                    <div class="row">
                        <section class="col col-3">
                            <label for="clientId" class="select">CLIENT</label>
                                <select name="clientId" id="clientId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                </select><i></i>

                        </section>
                        <section class="col col-3">
                            <label for="deptId" class="select">DEPARTMENT</label>
                                <select name="deptId" id="deptId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                </select><i></i>

                        </section>
                        <section class="col col-3">
                            <label for="positionId" class="select">POSITION</label>
                                <select name="positionId" id="positionId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                </select><i></i>

                        </section>
                        <section class="col col-3">
                            <label class="pull-left">JOBCODE&nbsp;</label>
                            <br>
                            <label id="jobCodeLabel"></label>
                            <input type="hidden" id="jobCode" name="jobCode" value=""/>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="employeeName" class="input">EMPLOYEE NAME
                                <input id="employeeName" name="employeeName" type="text" placeholder="Employee Name"/>
                            </label><input type="hidden" name="empSelected" id="empSelected"/>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 60%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                                <input type="hidden" name="startDate" id="startDate">
                                <input type="hidden" name="endDate" id="endDate">
                                <input type="hidden" name="dateRange" id="dateRange">
                            </div>
                        </section>
                        <section class="col col-3">
                            <label for="weekendingDate" class="input">
                                <input type="text" name="weekendingDate" id="weekendingDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date"/>
                            </label>
                        </section>
                    </div>
                    <!--<button name="insertRowBtn" id="insertRowBtn" class="btn btn-sm btn-default"><i class="glyphicon glyphicon-pencil fa fa-plus"></i> InsertRow</button>-->
                    <div class="row">
                        <table border="1" cellpadding="2" cellspacing="2" class="timesheetDataTable table table-striped table-bordered table-hover">
                            <thead>
                                <th>EmployeeID</th>
                                <th>Early <br> Morning</th>
                                <th>Day/ <br> Ordinary</th>
                                <th>Afternoon</th>
                                <th>Night</th>
                                <th>Rostered <br>Day Off</th>
                                <th>Saturday</th>
                                <th>Sunday</th>
                                <th>Overtime</th>
                                <th>Doubletime</th>
                                <th>Holiday</th>
                                <th>Holiday 2</th>
                                <th>Saturday <br> Overtime</th>
                                <th>Sunday <br> Overtime</th>
                                <th>Period <br> Overtime</th>
                                <!--<th>Department</th>-->
                                <th>Action</th>
                            </thead>
                            <tbody id="tblBody">
                                <tr>
                                    <td><input type="text" name="candidateId1" id="candidateId1" value="" size="18" readonly/></td>
                                    <td><input type="text" name="emTotal1" class="emTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="ordTotal1" class="ordTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="aftTotal1" class="aftTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="nightTotal1" class="nightTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="rdoTotal1" class="rdoTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="satTotal1" class="satTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="sunTotal1" class="sunTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="ovtTotal1" class="ovtTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="dblTotal1" class="dblTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="holTotal1" class="holTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="hol_total1" class="hol_total1" value="" size="3"/></td>
                                    <td><input type="text" name="satOvertimeTotal1" class="satOvertimeTotal1" value="" size="3"/></td>
                                    <td><input type="text" name=sunOvertimeTotal1" class="sunOvertimeTotal1" value="" size="3"/></td>
                                    <td><input type="text" name="periodOvertimeTotal1" class="periodOvertimeTotal1" value="" size="3"/></td>
                                    <!--<td><select name="department1" id="department1"  class="department pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        </select><i></i></td>-->
                                    <td><button type="submit" name="addTotalEntryBtn" id="addTotalEntryBtn"  class="addTotalEntryBtn btn btn-sm btn-default"><i class="glyphicon glyphicon-plus"></i> Add</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <br>
                        <div style="width: 100%">
                            <div style="float: left; width: 600px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                      <tr>
                                        <th>Date</th>
                                        <th>Department</th>
                                        <th>Action</th>
                                      </tr>
                                    </thead>
                                    <tbody id="departmentAssignment">

                                    </tbody>
                                </table>
                            </div>
                            &nbsp;&nbsp;
                            <div style="float: left; width: 500px">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Assigned Date</th>
                                        <th>Assigned Department</th>
                                    </tr>
                                    </thead>
                                    <tbody id="assignedDepartments">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
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
        /* AJAX loading animation */
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /* -  end  -*/

        $.fn.extend({
            donetyping: function(callback,timeout){
                timeout = timeout || 1e3; // 1 second default timeout
                var timeoutReference,
                    doneTyping = function(el){
                        if (!timeoutReference) return;
                        timeoutReference = null;
                        callback.call(el);
                    };
                return this.each(function(i,el){
                    var $el = $(el);
                    // Chrome Fix (Use keyup over keypress to detect backspace)
                    // thank you @palerdot
                    $el.is(':input') && $el.on('keyup keypress paste',function(e){
                        // This catches the backspace button in chrome, but also prevents
                        // the event from triggering too preemptively. Without this line,
                        // using tab/shift+tab will make the focused element fire the callback.
                        if (e.type=='keyup' && e.keyCode!=8) return;
                        // Check if timeout has been set. If it has, "reset" the clock and
                        // start over again.
                        if (timeoutReference) clearTimeout(timeoutReference);
                        timeoutReference = setTimeout(function(){
                            // if we made it here, our timeout has elapsed. Fire the
                            // callback
                            doneTyping(el);
                        }, timeout);
                    }).on('blur',function(){
                        // If we can, fire the event since we're leaving the field
                        doneTyping(el);
                    });
                });
            }
        });
        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            getTimesheetTotalsRecord();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var weekendingDate = $('#weekendingDate').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            displayAssignedDepartments(startDate,endDate,clientId,positionId,jobCode,empId,weekendingDate);
            getTimesheetTotalsRecord();
            $('#weekendingDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="payDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="payDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#payDate').val(picker.startDate.format('YYYY-MM-DD'));
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
        var dateArr = [];

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
            var rows = '';
            for(var i =0; i < dateRange.length; i++) {
                rows = rows+'<tr><td>'+dateRange[i]+'</td><td><select name="department" id="department'+i+'" class="department form-control" data-date="'+dateRange[i]+'"></select></td><td data-date="'+dateRange[i]+'"><button type="button" class="addDateBtn btn btn-sm btn-default">Update</button></td></tr>';
            }
            $('#departmentAssignment').html('');
            $('#departmentAssignment').html(rows);
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var weekendingDate = $('#weekendingDate').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            populateClientDepartments(clientId);

            displayAssignedDepartments(startDate,endDate,clientId,positionId,jobCode,empId,weekendingDate);
        }

        function displayAssignedDepartments(startDate,endDate,clientId,positionId,jobCode,empId,weekendingDate){
            var action = 'ASSIGNEDDEPT';
            $.ajax({
                url:"getClientDepartmentsList.php",
                type:"POST",
                data:{startDate:startDate,endDate:endDate,clientId:clientId,positionId:positionId,jobCode:jobCode,empId:empId,weekendingDate:weekendingDate,action:action},
                dataType:"html",
                success: function (data) {

                    $('#assignedDepartments').html('');
                    $('#assignedDepartments').html(data);
                }
            })
        }
        function populateClientDepartments(clientid){
            var action = 'DEPT';
            $.ajax({
                url:"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid,action:action},
                dataType:"html",
                success: function (data) {
                    $('.department').html('');
                    $('.department').html(data);
                }
            })
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
        loadClients();
        $('#positionId').hide();
        $('.ui-autocomplete-input').css('width','40px')
        /*$('#employeeName').autocomplete({
            source: <?php //include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                $('#candidateId').val(candidateId);
                $('#candidateId2').val(candidateId);
                $('#candidateId3').val(candidateId);
                $('#candidateId4').val(candidateId);
                $('#candidateId5').val(candidateId);
            }
        });*/
        $('#employeeName').autocomplete({
            source: function( request, response ) {
                $.ajax({
                    url: "empList.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term,
                        clientId: $('#clientId :selected').val(),
                        positionId: $('#positionId :selected').val(),
                    },
                    success: function( data ) {
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                $('#candidateId1').val(candidateId);
               /* $('#candidateId2').val(candidateId);
                $('#candidateId3').val(candidateId);
                $('#candidateId4').val(candidateId);
                $('#candidateId5').val(candidateId);*/
                return true;
            },
            focus: function(event, ui){
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                $('#candidateId1').val(candidateId);
               /* $('#candidateId2').val(candidateId);
                $('#candidateId3').val(candidateId);
                $('#candidateId4').val(candidateId);
                $('#candidateId5').val(candidateId);*/
                return true;
            },
        });
        function loadClients(){
            $.ajax({
                url :"loadClients.php",
                type:"POST",
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#clientId').html('');
                $('#clientId').html(data);
                if($('#clientId :selected').val() == 'All'){
                    $('#clientId option[value="All"]').text('Select Client');
                    $('#clientId :selected').val('Select Client');
                }
            });
        }
        function loadPositions(){
            var action = 'CLIENTPOSITION';
            var clientId = $('#clientId :selected').val();
            $.ajax({
                url :"loadPositions.php",
                type:"POST",
                dataType:"html",
                data:{action:action,clientId:clientId},
                success: function(data) {
                }
            }).done(function(data){
                $('#positionId').html('');
                $('#positionId').html(data);
            });
        }
        function loadDepartments(){
            var clientid = $('#clientId :selected').val();
            var action = 'DEPARTMENTSFORJOBCODE';
            $.ajax({
                url :"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid,action:action},
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#deptId').html('');
                $('#deptId').html(data);
            });
        }
        $(document).on('change', '#clientId', function(){
            $('#positionId').show();
            $('#jobCodeLabel').html('');
            loadPositions();
            var clientId = $('#clientId :selected').val();
            loadDepartments()
            populateClientDepartments(clientId);
        });

        $(document).on('change', '#positionId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            var deptId = $('#deptId option:selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId,deptId:deptId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobCodeLabel').html('');
                $('#jobCodeLabel').html(data);
                $('#jobCode').val(data);
            });
        });
        $(document).on('click', '#positionId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            var deptId = $('#deptId option:selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId,deptId:deptId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobCodeLabel').html('');
                $('#jobCodeLabel').html(data);
                $('#jobCode').val(data);
            });
        });
        $(document).on('click','.addDateBtn', function(){
            var workDate = $(this).closest('td').attr('data-date');
            var department = $(this).closest('td').prev('td').find('select option:selected').val();
            var deptId = $('#deptId :selected').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var weekendingDate = $('#weekendingDate').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'Department';
            $.ajax({
               url: "saveTimeSheetTotals.php",
               type: "POST",
               dataType: "text",
               data: {clientId:clientId,positionId:positionId,department:department,deptId:deptId,jobCode:jobCode,empId:empId,workDate:workDate,weekendingDate:weekendingDate,startDate:startDate,endDate:endDate,action:action},
               success: function (data){
                   console.log('..........'+data);
                   $('.error').html('');
                   $('.error').html(data);
                   displayAssignedDepartments(startDate,endDate,clientId,positionId,jobCode,empId,weekendingDate);
               }
            });
        });
       /* getPayrollNames();
        function getPayrollNames(){
            $.ajax({
                url: "getPayrollNames.php",
                type: "POST",
                dataType: "html",
                success: function (data) {
                    $('#payrollName').html('');
                    $('#payrollName').html(data);
                }
            });
        }*/

        getPayrollNames();
        function getPayrollNames(){
            $.ajax({
                url: "getPayrollNames.php",
                type: "POST",
                dataType: "html",
                success: function (data) {
                    $('#payrollName').html('');
                    $('#payrollName').html(data);
                }
            });
        }


        /* $(document).on('click','#insertRowBtn',function () {
             var clientId = $('#clientId :selected').val();
             var positionId = $('#positionId :selected').val();
             var jobCode = $('#jobCode').val();
             var empId = $('#empSelected').val();
          /* var htmlRow = '<tr data-clientId="'+clientId+'" data-positionId="'+positionId+'" data-jobCode="'+jobCode+'" data-empId="'+empId+'"><td><input type="text" name="candidateId" value="'+empId+'" readonly/></td><td><input type="text" name="emTotal" value="" size="5"/></td><td><input type="text" name="dayTotal" value="" size="5"/></td><td><input type="text" name="aftTotal" value="" size="5"/></td><td><input type="text" name="nightTotal" value="" size="5"/></td><td><input type="text" name="rdoTotal" value="" size="5"/></td><td><input type="text" name="satTotal" value="" size="5"/></td><td><input type="text" name="sunTotal" value="" size="5"/></td><td><input type="text" name="ovtTotal" value="" size="5"/></td><td><input type="text" name="dblTotal" value="" size="5"/></td><td><input type="text" name="holTotal" value="" size="5"/></td><td><input type="text" name="satOvrtimeTotal" value="" size="5"/></td><td><input type="text" name=sunOvertimeTotal" value="" size="5"/></td><td><input type="text" name="periodOvertimeTotal" value="" size="5"/></td><td><button>Delete</button></td></tr>';
             $('#tblBody').append(htmlRow);
        });*/
        $(document).on('click','#addTotalEntryBtn',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmTimesheetTotals = $("#frmTimesheetTotals").validate({
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
                    clientId: {
                        required:true
                    },
                    positionId: {
                        required:true
                    },
                    jobCode:{
                      required:true
                    },
                    empSelected: {
                        required:true
                    },
                    employeeName:{
                        required:true
                    }
                },
                messages: {
                    weekendingDate: {
                        required: "Please select Weekending Date"
                    },
                    clientId:{
                        required: "Please select client"
                    },
                    positionId:{
                        required: "Please select position"
                    },
                    jobCode:{
                        required: "Please add a jobCode for the position"
                    },
                    employeeName:{
                        required: "Please type a name"
                    },
                    empSelected: {
                        required: "Please select employee name"
                    }
                },
                submitHandler: function (form) {
                    var clientId = $('#clientId :selected').val();
                    var positionId = $('#positionId :selected').val();
                    var deptId = $('#deptId :selected').val();
                   // var department = $('#department :selected').val();
                    var jobCode = $('#jobCode').val();
                    var empId = $('#empSelected').val();
                    var emTotal = $('.emTotal1').val();
                    var ordTotal = $('.ordTotal1').val();
                    var aftTotal = $('.aftTotal1').val();
                    var nightTotal = $('.nightTotal1').val();
                    var rdoTotal = $('.rdoTotal1').val();
                    var satTotal = $('.satTotal1').val();
                    var sunTotal = $('.sunTotal1').val();
                    var ovtTotal = $('.ovtTotal1').val();
                    var dblTotal = $('.dblTotal1').val();
                    var holTotal = $('.holTotal1').val();
                    var hol_total = $('.hol_total1').val();
                    var satOvertimeTotal = $('.satOvertimeTotal1').val();
                    var sunOvertimeTotal = $('.sunOvertimeTotal1').val();
                    var periodOvertimeTotal = $('.periodOvertimeTotal1').val();
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var weekendingDate = $('#weekendingDate').val();
                    var action = 'Add';
                    $.ajax({
                        type: 'post',
                        url: 'saveTimeSheetTotals.php',
                        dataType: "text",
                        data: {clientId:clientId,positionId:positionId,deptId:deptId,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                        success: function (data) {
                            if(data == 'inserted'){
                                $('.error').html('');
                                $('.error').html(data);
                                //location.reload();
                            }
                        }
                    })
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

       /* $(document).on('click','#addTotalEntryBtn2',function (e) {
            e.preventDefault();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department2 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal2').val();
            var ordTotal = $('.ordTotal2').val();
            var aftTotal = $('.aftTotal2').val();
            var nightTotal = $('.nightTotal2').val();
            var rdoTotal = $('.rdoTotal2').val();
            var satTotal = $('.satTotal2').val();
            var sunTotal = $('.sunTotal2').val();
            var ovtTotal = $('.ovtTotal2').val();
            var dblTotal = $('.dblTotal2').val();
            var holTotal = $('.holTotal2').val();
            var hol_total = $('.hol_total2').val();
            var satOvertimeTotal = $('.satOvertimeTotal2').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal2').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal2').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'Add';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('data.................'+data);
                    if(data == 'inserted'){
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            });
        });

        $(document).on('click','#addTotalEntryBtn3',function (e) {
            e.preventDefault();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department3 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal3').val();
            var ordTotal = $('.ordTotal3').val();
            var aftTotal = $('.aftTotal3').val();
            var nightTotal = $('.nightTotal3').val();
            var rdoTotal = $('.rdoTotal3').val();
            var satTotal = $('.satTotal3').val();
            var sunTotal = $('.sunTotal3').val();
            var ovtTotal = $('.ovtTotal3').val();
            var dblTotal = $('.dblTotal3').val();
            var holTotal = $('.holTotal3').val();
            var hol_total = $('.hol_total3').val();
            var satOvertimeTotal = $('.satOvertimeTotal3').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal3').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal3').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'Add';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('data.................'+data);
                    if(data == 'inserted'){
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            });
        });

        $(document).on('click','#addTotalEntryBtn4',function (e) {
            e.preventDefault();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department4 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal4').val();
            var ordTotal = $('.ordTotal4').val();
            var aftTotal = $('.aftTotal4').val();
            var nightTotal = $('.nightTotal4').val();
            var rdoTotal = $('.rdoTotal4').val();
            var satTotal = $('.satTotal4').val();
            var sunTotal = $('.sunTotal4').val();
            var ovtTotal = $('.ovtTotal4').val();
            var dblTotal = $('.dblTotal4').val();
            var holTotal = $('.holTotal4').val();
            var hol_total = $('.hol_total4').val();
            var satOvertimeTotal = $('.satOvertimeTotal4').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal4').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal4').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'Add';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('data.................'+data);
                    if(data == 'inserted'){
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            });
        });

        $(document).on('click','#addTotalEntryBtn5',function (e) {
            e.preventDefault();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department5 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal5').val();
            var ordTotal = $('.ordTotal5').val();
            var aftTotal = $('.aftTotal5').val();
            var nightTotal = $('.nightTotal5').val();
            var rdoTotal = $('.rdoTotal5').val();
            var satTotal = $('.satTotal5').val();
            var sunTotal = $('.sunTotal5').val();
            var ovtTotal = $('.ovtTotal5').val();
            var dblTotal = $('.dblTotal5').val();
            var holTotal = $('.holTotal5').val();
            var hol_total = $('.hol_total5').val();
            var satOvertimeTotal = $('.satOvertimeTotal5').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal5').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal5').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'Add';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('data.................'+data);
                    if(data == 'inserted'){
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            });
        });*/



        /*$(document).on('click','#updateTotalEntryBtn1',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmTimesheetTotals = $("#frmTimesheetTotals").validate({
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
                    clientId: {
                        required:true
                    },
                    positionId: {
                        required:true
                    },
                    jobCode:{
                        required:true
                    },
                    empSelected: {
                        required:true
                    },
                    employeeName:{
                        required:true
                    }
                },
                messages: {
                    weekendingDate: {
                        required: "Please select Weekending Date"
                    },
                    clientId:{
                        required: "Please select client"
                    },
                    positionId:{
                        required: "Please select position"
                    },
                    jobCode:{
                        required: "Please add a jobCode for the position"
                    },
                    employeeName:{
                        required: "Please type a name"
                    },
                    empSelected: {
                        required: "Please select employee name"
                    }
                },
                submitHandler: function (form) {
                    var clientId = $('#clientId :selected').val();
                    var positionId = $('#positionId :selected').val();
                    var department = $('#department :selected').val();
                    var jobCode = $('#jobCode').val();
                    var empId = $('#empSelected').val();
                    var emTotal = $('.emTotal').val();
                    var ordTotal = $('.ordTotal').val();
                    var aftTotal = $('.aftTotal').val();
                    var nightTotal = $('.nightTotal').val();
                    var rdoTotal = $('.rdoTotal').val();
                    var satTotal = $('.satTotal').val();
                    var sunTotal = $('.sunTotal').val();
                    var ovtTotal = $('.ovtTotal').val();
                    var dblTotal = $('.dblTotal').val();
                    var holTotal = $('.holTotal').val();
                    var hol_total = $('.hol_total').val();
                    var satOvertimeTotal = $('.satOvertimeTotal').val();
                    var sunOvertimeTotal = $('.sunOvertimeTotal').val();
                    var periodOvertimeTotal = $('.periodOvertimeTotal').val();
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var weekendingDate = $('#weekendingDate').val();
                    var action = 'update';
                    $.ajax({
                        type: 'post',
                        url: 'saveTimeSheetTotals.php',
                        dataType: "text",
                        data: {clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                        success: function (data) {
                            console.log('response....'+data);
                           if(data == 'updated'){

                               //location.reload();
                           }
                        }
                    })
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });*/
        $(document).on('click','#updateTotalEntryBtn1',function (e) {
            e.preventDefault();
                    var totId =  $('.totId1').val();
                    var clientId = $('#clientId :selected').val();
                    var positionId = $('#positionId :selected').val();
                   // var department = $('#department1 :selected').val();
                    var jobCode = $('#jobCode').val();
                    var empId = $('#empSelected').val();
                    var emTotal = $('.emTotal1').val();
                    var ordTotal = $('.ordTotal1').val();
                    var aftTotal = $('.aftTotal1').val();
                    var nightTotal = $('.nightTotal1').val();
                    var rdoTotal = $('.rdoTotal1').val();
                    var satTotal = $('.satTotal1').val();
                    var sunTotal = $('.sunTotal1').val();
                    var ovtTotal = $('.ovtTotal1').val();
                    var dblTotal = $('.dblTotal1').val();
                    var holTotal = $('.holTotal1').val();
                    var hol_total = $('.hol_total1').val();
                    var satOvertimeTotal = $('.satOvertimeTotal1').val();
                    var sunOvertimeTotal = $('.sunOvertimeTotal1').val();
                    var periodOvertimeTotal = $('.periodOvertimeTotal1').val();
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var weekendingDate = $('#weekendingDate').val();
                    var action = 'update';
                    $.ajax({
                        type: 'post',
                        url: 'saveTimeSheetTotals.php',
                        dataType: "text",
                        data: {totId:totId,clientId:clientId,positionId:positionId,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                        success: function (data) {
                            console.log('response....'+data);
                            if(data == 'updated'){
                                $('.error').html('');
                                $('.error').html(data);
                                //location.reload();
                            }
                        }
                    });
        });
        /*$(document).on('click','#updateTotalEntryBtn2',function (e) {
            e.preventDefault();
            var totId =  $('.totId2').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department2 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal2').val();
            var ordTotal = $('.ordTotal2').val();
            var aftTotal = $('.aftTotal2').val();
            var nightTotal = $('.nightTotal2').val();
            var rdoTotal = $('.rdoTotal2').val();
            var satTotal = $('.satTotal2').val();
            var sunTotal = $('.sunTotal2').val();
            var ovtTotal = $('.ovtTotal2').val();
            var dblTotal = $('.dblTotal2').val();
            var holTotal = $('.holTotal2').val();
            var hol_total = $('.hol_total2').val();
            var satOvertimeTotal = $('.satOvertimeTotal2').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal2').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal2').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'update';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {totId:totId,clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('response....'+data);
                    if(data == 'updated'){

                    }
                }
            });
        });
        $(document).on('click','#updateTotalEntryBtn3',function (e) {
            e.preventDefault();
            var totId =  $('.totId3').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department3 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal3').val();
            var ordTotal = $('.ordTotal3').val();
            var aftTotal = $('.aftTotal3').val();
            var nightTotal = $('.nightTotal3').val();
            var rdoTotal = $('.rdoTotal3').val();
            var satTotal = $('.satTotal3').val();
            var sunTotal = $('.sunTotal3').val();
            var ovtTotal = $('.ovtTotal3').val();
            var dblTotal = $('.dblTotal3').val();
            var holTotal = $('.holTotal3').val();
            var hol_total = $('.hol_total3').val();
            var satOvertimeTotal = $('.satOvertimeTotal3').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal3').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal3').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'update';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {totId:totId,clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('response....'+data);
                    if(data == 'updated'){

                    }
                }
            });
        });
        $(document).on('click','#updateTotalEntryBtn4',function (e) {
            e.preventDefault();
            var totId =  $('.totId4').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department4 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal4').val();
            var ordTotal = $('.ordTotal4').val();
            var aftTotal = $('.aftTotal4').val();
            var nightTotal = $('.nightTotal4').val();
            var rdoTotal = $('.rdoTotal4').val();
            var satTotal = $('.satTotal4').val();
            var sunTotal = $('.sunTotal4').val();
            var ovtTotal = $('.ovtTotal4').val();
            var dblTotal = $('.dblTotal4').val();
            var holTotal = $('.holTotal4').val();
            var hol_total = $('.hol_total4').val();
            var satOvertimeTotal = $('.satOvertimeTotal4').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal4').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal4').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'update';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {totId:totId,clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('response....'+data);
                    if(data == 'updated'){

                    }
                }
            });
        });
        $(document).on('click','#updateTotalEntryBtn5',function (e) {
            e.preventDefault();
            var totId =  $('.totId5').val();
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var department = $('#department5 :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var emTotal = $('.emTotal5').val();
            var ordTotal = $('.ordTotal5').val();
            var aftTotal = $('.aftTotal5').val();
            var nightTotal = $('.nightTotal5').val();
            var rdoTotal = $('.rdoTotal5').val();
            var satTotal = $('.satTotal5').val();
            var sunTotal = $('.sunTotal5').val();
            var ovtTotal = $('.ovtTotal5').val();
            var dblTotal = $('.dblTotal5').val();
            var holTotal = $('.holTotal5').val();
            var hol_total = $('.hol_total5').val();
            var satOvertimeTotal = $('.satOvertimeTotal5').val();
            var sunOvertimeTotal = $('.sunOvertimeTotal5').val();
            var periodOvertimeTotal = $('.periodOvertimeTotal5').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'update';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {totId:totId,clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('response....'+data);
                    if(data == 'updated'){

                    }
                }
            });
        });*/
        function getTimesheetTotalsRecord(){
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var deptId = $('#deptId :selected').val();
            var jobCode = $('#jobCode').val();
            var empId = $('#empSelected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'verify';
            $.ajax({
                type: 'post',
                url: 'saveTimeSheetTotals.php',
                dataType: "text",
                data: {clientId:clientId,positionId:positionId,deptId:deptId,jobCode:jobCode,empId:empId,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                success: function (data) {
                    console.log('status'+data);
                    if(data != ''){
                        $('#tblBody').html(' ');
                        $('#tblBody').html(data);
                    }
                }
            })
        }
        $(document).on('blur','#weekendingDate',function () {
            getTimesheetTotalsRecord();
        });
        $(document).on('blur','#positionId',function () {
            getTimesheetTotalsRecord();
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>