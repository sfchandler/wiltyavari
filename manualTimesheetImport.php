<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}


if(isset($_POST['submit'])) {
    if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
        $allowedExtensions = array("xls", "xlsx");
        $temp = explode(".", $_FILES['uploadFile']['name']);
        $newFileName = round(microtime(true)) . '_' . date('Y-m-d') . '_' . $_SESSION['userSession'] . '.' . end($temp);
        $ext = pathinfo($newFileName, PATHINFO_EXTENSION);
        if (in_array($ext, $allowedExtensions)) {
            $file_size = $_FILES['uploadFile']['size'] / 1024;
            if ($file_size < 50) {
                $file = "imports/" . $newFileName;
                $isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
                if ($isUploaded) {
                    try {
                        $objPHPExcel = PHPExcel_IOFactory::load($file);
                    }
                    catch (Exception $e) {
                        $errMsg = 'Error loading file "' . pathinfo($file, PATHINFO_BASENAME . '": ' . $e->getMessage());
                    }
                    $sheet = $objPHPExcel->getSheet(0);
                    $total_rows = $sheet->getHighestRow();
                    $total_columns = $sheet->getHighestColumn();
                    for ($row = 2; $row <= $total_rows; $row++) {
                        $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, FALSE, TRUE);
                        foreach ($single_row as $key => $value) {
                            $startDate = $_POST['startDate'];
                            $endDate = $_POST['endDate'];
                            $weekendingDate = $_POST['weekendingDate'];

                            $status = saveTimeSheetCalculation($mysqli,
                                $value[4],
                                $value[5],
                                $value[6],
                                $value[7],
                                $value[9],
                                $value[10],
                                $value[11],
                                $value[12],
                                '0.00',
                                $value[13],
                                $value[14],
                                $value[16],
                                $value[17],
                                $value[15],
                                '0.00',
                                '0.00',
                                '0.00',
                                '0.00',
                                $startDate,
                                $endDate,
                                $value[1],
                                $value[2]);
                            $json = json_decode($status, true);
                            $msgArray[] = array('clientId' =>$value[4],
                                'positionId'=>$value[5],
                                'deptId'=>$value[6],
                                'jobCode'=>$value[7],
                                'startDate'=>$startDate,
                                'endDate'=>$endDate,
                                'fullName'=>$value[0],
                                'candidateId'=>$value[1],
                                'wkDate'=>$value[2],
                                'level'=>$value[3],
                                'total'=>$value[8],
                                'em'=>$value[9],
                                'day'=>$value[10],
                                'aft'=>$value[11],
                                'nd'=>$value[12],
                                'sat'=>$value[13],
                                'sun'=>$value[14],
                                'ph'=>$value[15],
                                'overtime'=>$value[16],
                                'doubletime'=>$value[17],
                                'doubleOvertime'=>$value[18],
                                'status'=>$status);
                        }
                    }
                } else {
                    $errMsg = 'File not uploaded!';
                }
            } else {
                $errMsg = 'Maximum file size should not cross 50 KB on size!';
            }
        } else {
            $errMsg = 'This type of file not allowed!';
        }
    } else {
        $errMsg = 'Select an excel file first!';
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
        <h2>Manual Timesheet Import</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <div class="row">
                <section class="col col-sm-2">
                    <a href="./imports/sample/manual_timesheet_import.xlsx" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i>&nbsp;Sample Timesheet Import Excel</a>
                </section>
            </div>
            <br>
            <fieldset class="smart-form">
                <form id="frmTimesheetTotals" class="smart-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                    <div style="color: red;font-size: 12pt">Please set correct clientID, Position ID and JobCode in excel sheet before upload</div>
                    <div class="row">
                        <section class="col col-3">
                            <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 60%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span></span> <b class="caret"></b>
                                <input type="hidden" name="startDate" id="startDate">
                                <input type="hidden" name="endDate" id="endDate">
                                <input type="hidden" name="dateRange" id="dateRange">
                            </div>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12">
                            <h5>Please select excel sheet</h5>
                            <div class="pull-left">
                                <input type="file" name="uploadFile" id="uploadFile"/><label for="uploadFile">Select excel file (*.xlsx)</label>
                                <input type="submit" name="submit" value="Upload" class="btn btn-sm btn-warning"/>
                            </div>
                        </section>
                    </div>
                </form>
            </fieldset>
        </div>
        <?php
        if(!empty($msgArray)){
            ?>
            <h3>Upload Information</h3>
            <div style="width: 60%">
                <table border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="60%">
                    <thead>
                    <th>Client</th>
                    <th>Position</th>
                    <th>JobCode</th>
                    <th>StartDate</th>
                    <th>EndDate</th>
                    <th>FullName</th>
                    <th>EmployeeID</th>
                    <th>Weekending Date</th>
                    <th>Level</th>
                    <th>Total</th>
                    <th>Early Morning</th>
                    <th>Day</th>
                    <th>Afternoon</th>
                    <th>Night</th>
                    <th>Saturday</th>
                    <th>Sunday</th>
                    <th>Public Holiday</th>
                    <th>Overtime</th>
                    <th>Doubletime</th>
                    <th>DoubleOvertime</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach($msgArray as $key=>$val){
                        echo '<tr>
                                <td>'.getClientNameByClientId($mysqli,$val['clientId']).'</td>
                                <td>'.getCandidatePositionNameById($mysqli,$val['positionId']).'</td>
                                <td>'.$val['jobCode'].'</td>
                                <td>'.$val['startDate'].'</td>
                                <td>'.$val['endDate'].'</td>
                                <td>'.$val['fullName'].'</td>
                                <td>'.$val['candidateId'].'</td>
                                <td>'.$val['wkDate'].'</td>
                                <td>'.$val['level'].'</td>
                                <td>'.$val['total'].'</td>
                                <td>'.$val['em'].'</td>
                                <td>'.$val['day'].'</td>
                                <td>'.$val['aft'].'</td>
                                <td>'.$val['nd'].'</td>
                                <td>'.$val['sat'].'</td>
                                <td>'.$val['sun'].'</td>
                                <td>'.$val['ph'].'</td>
                                <td>'.$val['overtime'].'</td>
                                <td>'.$val['doubletime'].'</td>  
                                <td>'.$val['doubleOvertime'].'</td>
                                <td class="error">'.$val['status'].'</td>
                              </tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
            <?php
        }
        ?>
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
                // Set selection
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                $('#candidateId1').val(candidateId);

                return true;
            },
            focus: function(event, ui){
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                $('#candidateId1').val(candidateId);

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
        $(document).on('change', '#clientId', function(){
            $('#positionId').show();
            $('#jobCodeLabel').html('');
            loadPositions();
            var clientId = $('#clientId :selected').val();
            populateClientDepartments(clientId);
        });

        $(document).on('change', '#positionId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId},
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
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId},
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
                data: {clientId:clientId,positionId:positionId,department:department,jobCode:jobCode,empId:empId,workDate:workDate,weekendingDate:weekendingDate,startDate:startDate,endDate:endDate,action:action},
                success: function (data){
                    console.log('..........'+data);
                    $('.error').html('');
                    $('.error').html(data);
                    displayAssignedDepartments(startDate,endDate,clientId,positionId,jobCode,empId,weekendingDate);
                }
            });
        });

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
                        data: {clientId:clientId,positionId:positionId,jobCode:jobCode,empId:empId,emTotal:emTotal,ordTotal:ordTotal,aftTotal:aftTotal,nightTotal:nightTotal,rdoTotal:rdoTotal,satTotal:satTotal,sunTotal:sunTotal,ovtTotal:ovtTotal,dblTotal:dblTotal,holTotal:holTotal,hol_total:hol_total,satOvertimeTotal:satOvertimeTotal,sunOvertimeTotal:sunOvertimeTotal,periodOvertimeTotal:periodOvertimeTotal,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
                        success: function (data) {
                            if(data == 'inserted'){
                                $('.error').html('');
                                $('.error').html(data);
                            }
                        }
                    })
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

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
        function getTimesheetTotalsRecord(){
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
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
                data: {clientId:clientId,positionId:positionId,jobCode:jobCode,empId:empId,startDate:startDate,endDate:endDate,weekendingDate:weekendingDate,action:action},
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