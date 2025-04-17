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
        <h2>Add/Edit Payrule Definition</h2>
        <div class="error"></div>
        <form name="frmPayrule" id="frmPayrule" class="smart-form" method="post">
            <div class="row">
                <section class="col col-3">
                    <label for="clientId" class="select">
                            <select name="clientId" id="clientId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i></label>
                </section>
                <section class="col col-3">
                    <label for="deptId" class="select">
                        <select name="deptId" id="deptId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        </select><i></i></label>
                </section>
                <section class="col col-3">
                    <label for="expPosition" class="select">
                            <select name="expPosition" id="expPosition" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i></label>
                </section>
                <section class="col col-3">
                    <label class="pull-left">JobCode :&nbsp;</label>
                        <input type="text" name="jobCode" id="jobCode" readonly/>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                        <label for="payAwdCode" class="input">Pay Award Code:
                        <input name="payAwdCode" id="payAwdCode" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="payAwdCodeDesc" class="textarea">Pay Award Description:
                        <textarea name="payAwdCodeDesc" id="payAwdCodeDesc" class="textarea"></textarea>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="avgNormalHrs" class="input">Average Normal Hours:(e.g. 38)
                        <input name="avgNormalHrs" id="avgNormalHrs" value="" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="spreadStart" class="input">Spread Start Time:
                        <input name="spreadStart" id="spreadStart" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="spreadEnd" class="input">Spread End Time:
                        <input name="spreadEnd" id="spreadEnd" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="spreadDuration" class="input">Spread Duration:
                        <input name="spreadDuration" id="spreadDuration" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="firstEightHours" class="input">First Eight Hours:
                        <input name="firstEightHours" id="firstEightHours" value="8:00" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="minimumHrs" class="input">Minimum Hours:
                        <input name="minimumHrs" id="minimumHrs" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="overtimeAfterHrs" class="input">Overtime Weekdays After Hours:
                        <input name="overtimeAfterHrs" id="overtimeAfterHrs" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="overtimeSatAfterHrs" class="input">Overtime Saturday After Hours:
                        <input name="overtimeSatAfterHrs" id="overtimeSatAfterHrs" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="overtimeSunAfterHrs" class="input">Overtime Sunday After Hours:
                        <input name="overtimeSunAfterHrs" id="overtimeSunAfterHrs" value="" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="earlyMorningShiftStartTime" class="input">Early Morning Shift Start Time:
                        <input name="earlyMorningShiftStartTime" id="earlyMorningShiftStartTime" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="earlyMorningShiftEndTime" class="input">Early Morning Shift End Time:
                        <input name="earlyMorningShiftEndTime" id="earlyMorningShiftEndTime" value="" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="dayShiftStartTime" class="input">Day Shift Start Time:
                        <input name="dayShiftStartTime" id="dayShiftStartTime" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="dayShiftEndTime" class="input">Day Shift End Time:
                        <input name="dayShiftEndTime" id="dayShiftEndTime" value="" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="afternoonShiftStartTime" class="input">Afternoon Shift Finishes After:
                        <input name="afternoonShiftStartTime" id="afternoonShiftStartTime" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="afternoonShiftEndTime" class="input">Afternoon Shift Finishes at or before:
                        <input name="afternoonShiftEndTime" id="afternoonShiftEndTime" value="" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="nightShiftStartTime" class="input">Night Shift Finishes After:
                        <input name="nightShiftStartTime" id="nightShiftStartTime" value="" class="input"/>
                        <i></i></label>
                </section>
                <section class="col col-3">
                    <label for="nightShiftEndTime" class="input">Night Shift Finishes at or before:
                        <input name="nightShiftEndTime" id="nightShiftEndTime" value="" class="input"/>
                        <i></i></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-12">
                    <input type="submit" name="payruleBtn" id="payruleBtn" class="payruleBtn btn btn-primary btn-square btn-sm" value="Save Payrule"/>
                    <input type="submit" name="payruleUpdateBtn" id="payruleUpdateBtn" class="payruleUpdateBtn btn btn-primary btn-square btn-sm" value="Update Payrule"/>
                    <input type="reset" name="payruleCancelBtn" id="payruleCancelBtn" class="payruleCancelBtn btn btn-primary btn-square btn-sm" value="Reset/Cancel"/>
                </section>
            </div>
        </form>
        <div>
            <table id="tblPayrules" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Pay Award Code</th>
                        <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Pay Award Description</th>
                        <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>JobCode</th>
                        <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action</th>
                    </tr>
                </thead>
                <tbody class="tblPayrulesBody">
                </tbody>
            </table>
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
    runAllForms();
    $(document).ready(function(){

        $('#payruleUpdateBtn').hide();
        $(document).on('click','.payruleCancelBtn',function () {
           location.reload();
        });
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
        function populateClientPositions(){
            var dropSelect = 'N';
            var clientid = $('#clientId :selected').val();
            $.ajax({
                url:"getClientPositionsList.php",
                type:"POST",
                data:{dropSelect : dropSelect,clientid:clientid},
                dataType:"html",
                success: function(data){
                }
            }).done(function(data) {
                $('#expPosition').html('');
                $('#expPosition').html(data);
            });
        }
        function loadJobCode(clientId,positionId,deptId){
            $.ajax({
                url:"getJobCode.php",
                type:"POST",
                dataType:"text",
                data:{ clientId : clientId, positionId : positionId,deptId:deptId},
                success: function(data){
                    if(data.length>0){
                        /*$('#jobCodeLabel').html('');
                        $('#jobCodeLabel').html(data);*/
                        $('#jobCode').val('');
                        $('#jobCode').val(data);
                    }else if(data.length == 0){
                        $('#jobCode').val('');
                        $('#jobCode').val(data);
                    }else{
                        $('#jobCode').val('');
                        $('#jobCode').val(data);
                    }
                }
            });
        }
        loadPayrules();
        function loadPayrules(){
            $.ajax({
                url: "getPayrules.php",
                type: "POST",
                dataType: "html",
                success: function (data) {
                    $('.tblPayrulesBody').html('');
                    $('.tblPayrulesBody').html(data);
                }
            });
        }
        var ButtonValue;

        $('input[type="submit"]').click(function(e){
            ButtonValue = $(this).val();
        });
        $(document).on('click','.editPayruleBtn',function(){
            $('#payruleBtn').hide();
            $('#payruleUpdateBtn').show();

            var jbCode = $(this).closest('td').attr('data-jobcode');
            $('#jobCode').val(jbCode);
            $('#jobCodeLabel').text(jbCode);

            var posId = $(this).closest('td').attr('data-positionid');
            //$('#expPosition').prop('selectedIndex', posId);
            $('#expPosition').hide();
            //$('#expPosition').prop('readonly',true);
            var clId = $(this).closest('td').attr('data-clientid');
            //$('#clientId').prop("selectedIndex", clId);
            $('#clientId').hide();
            //$('#clientId').prop('readonly',true);

            var payaward = $(this).closest('td').attr('data-payaward');
            $('#payAwdCode').val(payaward);
            var payawarddesc = $(this).closest('td').attr('data-payawarddesc');
            $('#payAwdCodeDesc').text(payawarddesc);
            var avghrs = $(this).closest('td').attr('data-avghrs');
            $('#avgNormalHrs').val(avghrs);
            var spreadstart = $(this).closest('td').attr('data-spreadstart');
            $('#spreadStart').val(spreadstart);
            var spreadend = $(this).closest('td').attr('data-spreadend');
            $('#spreadEnd').val(spreadend);
            var spreadduration = $(this).closest('td').attr('data-spreadduration');
            $('#spreadDuration').val(spreadduration);
            var firsteighthours = $(this).closest('td').attr('data-firsteighthours');
            $('#firstEightHours').val(firsteighthours);
            var minimumhrs = $(this).closest('td').attr('data-minimumhrs');
            $('#minimumHrs').val(minimumhrs);
            var earlymorningstart = $(this).closest('td').attr('data-earlystart');
            $('#earlyMorningShiftStartTime').val(earlymorningstart);
            var earlymorningend = $(this).closest('td').attr('data-earlyend');
            $('#earlyMorningShiftEndTime').val(earlymorningend);
            var daystart = $(this).closest('td').attr('data-daystart');
            $('#dayShiftStartTime').val(daystart);
            var dayend = $(this).closest('td').attr('data-dayend');
            $('#dayShiftEndTime').val(dayend);
            var aftstart = $(this).closest('td').attr('data-aftstart');
            $('#afternoonShiftStartTime').val(aftstart);
            var aftend = $(this).closest('td').attr('data-aftend');
            $('#afternoonShiftEndTime').val(aftend);
            var nightstart = $(this).closest('td').attr('data-nightstart');
            $('#nightShiftStartTime').val(nightstart);
            var nightend = $(this).closest('td').attr('data-nightend');
            $('#nightShiftEndTime').val(nightend);
            var ovtafthrs = $(this).closest('td').attr('data-ovtafthrs');
            $('#overtimeAfterHrs').val(ovtafthrs);
            var ovtsatafthrs = $(this).closest('td').attr('data-ovtsatafthrs');
            $('#overtimeSatAfterHrs').val(ovtsatafthrs);
            var ovtsunafthrs = $(this).closest('td').attr('data-ovtsunafthrs');
            $('#overtimeSunAfterHrs').val(ovtsunafthrs);
        });
        $(document).on('change','#clientId', function(){
            var clientid = $('#clientId :selected').val();
            populateClientPositions();
            loadDepartments();
            var positionid = $('#expPosition :selected').val();
            var deptId = $('#deptId :selected').val();
            loadJobCode(clientid,positionid,deptId);
        });
        $(document).on('click','#clientId', function(){
            var clientid = $('#clientId :selected').val();
            populateClientPositions();
            loadDepartments();
            var positionid = $('#expPosition :selected').val();
            var deptId = $('#deptId :selected').val();
            loadJobCode(clientid,positionid,deptId);
        });
        $(document).on('change','#expPosition', function(){
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var deptId = $('#deptId :selected').val();
            loadJobCode(clientid,positionid,deptId);
        });
        $(document).on('click','#expPosition', function(){
            var clientid = $('#clientId :selected').val();
            var positionid = $('#expPosition :selected').val();
            var deptId = $('#deptId :selected').val();
            loadJobCode(clientid,positionid,deptId);
        });

        $('#spreadStart').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#spreadEnd').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#earlyMorningShiftStartTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#earlyMorningShiftEndTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#dayShiftStartTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#dayShiftEndTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#afternoonShiftStartTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#afternoonShiftEndTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#nightShiftStartTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});
        $('#nightShiftEndTime').timepicker({'step': 5 , 'timeFormat': 'H:i:s'});


        $(document).on('click','#payruleBtn', function(evt) {

            var errorClass = 'invalid';
            var errorElement = 'em';
            var frm = $("#frmPayrule").validate({
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
                    jobCode:{
                      required: true
                    },
                    payAwdCode: {
                        required: true
                    },
                    payAwdCodeDesc:{
                        required: true
                    },
                    avgNormalHrs:{
                        required: true
                    },
                    spreadStart:{
                        required: true
                    },
                    spreadEnd: {
                        required: true
                    },
                    spreadDuration:{
                        required: true
                    },
                    firstEightHours: {
                        required:true
                    },
                    minimumHrs:{
                        required: true
                    },
                    overtimeAfterHrs:{
                        required: true
                    },
                    overtimeSatAfterHrs:{
                        required: true
                    },
                    overtimeSunAfterHrs:{
                        required: true
                    }
                },
                messages: {
                    jobCode:{
                      required: "JobCode is required"
                    },
                    payAwdCode: {
                        required: "Please enter pay Award Code"
                    },
                    payAwdCodeDesc:{
                        required: "Please enter pay Award Description"
                    },
                    avgNormalHrs:{
                        required: "Please enter Average Normal Hours"
                    },
                    spreadStart:{
                        required: "Please enter spread of Hours Start"
                    },
                    spreadEnd:{
                        required: "Please enter spread of Hours End"
                    },
                    spreadDuration:{
                        required: "Please enter spread duration"
                    },
                    firstEightHours:{
                        required: "Please enter first eight hours or not"
                    },
                    minimumHrs:{
                        required: "Please enter minimum Hours limit"
                    },
                    overtimeAfterHrs:{
                        required: "Please enter overtime after hours"
                    },
                    overtimeSatAfterHrs:{
                        required: "Please enter overtime Saturday after hours"
                    },
                    overtimeSunAfterHrs:{
                        required: "Please enter overtime Sunday after hours"
                    }
                },
                submitHandler: function (form) {
                    if(ButtonValue == 'Save Payrule'){
                        $.ajax({
                            type: 'post',
                            url: 'processPayrule.php',
                            data: $('form').serialize(),
                            success: function (data) {
                                console.log('>>>>>>>>>>>>>>>>>>>'+data);
                                if (data == 'inserted') {
                                    loadPayrules();
                                    location.reload();
                                }
                            }
                        });
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','#payruleUpdateBtn',function(ev){

            var jobCode = $('#jobCode').val();
            var payAwdCode = $('#payAwdCode').val();
            var payAwdCodeDesc = $('textarea#payAwdCodeDesc').val();
            var avgNormalHrs = $('#avgNormalHrs').val();
            var spreadStart = $('#spreadStart').val();
            var spreadEnd = $('#spreadEnd').val();
            var spreadDuration = $('#spreadDuration').val();
            var firstEightHours = $('#firstEightHours').val();
            var minimumHrs = $('#minimumHrs').val();
            var overtimeAfterHrs = $('#overtimeAfterHrs').val();
            var overtimeSatAfterHrs = $('#overtimeSatAfterHrs').val();
            var overtimeSunAfterHrs = $('#overtimeSunAfterHrs').val();
            var earlyMorningShiftStartTime = $('#earlyMorningShiftStartTime').val();
            var earlyMorningShiftEndTime = $('#earlyMorningShiftEndTime').val();
            var dayShiftStartTime = $('#dayShiftStartTime').val();
            var dayShiftEndTime = $('#dayShiftEndTime').val();
            var afternoonShiftStartTime = $('#afternoonShiftStartTime').val();
            var afternoonShiftEndTime = $('#afternoonShiftEndTime').val();
            var nightShiftStartTime = $('#nightShiftStartTime').val();
            var nightShiftEndTime = $('#nightShiftEndTime').val();

            var errorClass = 'invalid';
            var errorElement = 'em';
            var frm = $("#frmPayrule").validate({
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
                    jobCode:{
                        required: true
                    },
                    payAwdCode: {
                        required: true
                    },
                    payAwdCodeDesc:{
                        required: true
                    },
                    avgNormalHrs:{
                        required: true
                    },
                    spreadStart:{
                        required: true
                    },
                    spreadEnd: {
                        required: true
                    },
                    spreadDuration:{
                        required: true
                    },
                    firstEightHours:{
                        required:true
                    },
                    minimumHrs:{
                        required: true
                    },
                    overtimeAfterHrs:{
                        required: true
                    },
                    overtimeSatAfterHrs:{
                        required: true
                    },
                    overtimeSunAfterHrs:{
                        required: true
                    }
                },
                messages: {
                    jobCode:{
                        required: "JobCode is required"
                    },
                    payAwdCode: {
                        required: "Please enter pay Award Code"
                    },
                    payAwdCodeDesc:{
                        required: "Please enter pay Award Description"
                    },
                    avgNormalHrs:{
                        required: "Please enter Average Normal Hours"
                    },
                    spreadStart:{
                        required: "Please enter spread of Hours Start"
                    },
                    spreadEnd:{
                        required: "Please enter spread of Hours End"
                    },
                    spreadDuration:{
                        required: "Please enter spread duration"
                    },
                    firstEightHours:{
                        required: "Please enter first eight hours or not"
                    },
                    minimumHrs:{
                        required: "Please enter minimum Hours limit"
                    },
                    overtimeAfterHrs:{
                        required: "Please enter overtime after hours"
                    },
                    overtimeSatAfterHrs:{
                        required: "Please enter overtime Saturday after hours"
                    },
                    overtimeSunAfterHrs:{
                        required: "Please enter overtime Sunday after hours"
                    }
                },
                submitHandler: function (form) {
                    console.log('ButtonValue' + ButtonValue+jobCode);
                    if(ButtonValue == 'Update Payrule'){
                        var updatePayrule = 'update';
                        $.ajax({
                            type: 'post',
                            url: 'processPayrule.php',
                            data: {
                                updatePayrule:updatePayrule,
                                jobCode: jobCode,
                                payAwdCode: payAwdCode,
                                payAwdCodeDesc: payAwdCodeDesc,
                                avgNormalHrs: avgNormalHrs,
                                spreadStart: spreadStart,
                                spreadEnd: spreadEnd,
                                spreadDuration: spreadDuration,
                                firstEightHours:firstEightHours,
                                minimumHrs: minimumHrs,
                                overtimeAfterHrs: overtimeAfterHrs,
                                overtimeSatAfterHrs: overtimeSatAfterHrs,
                                overtimeSunAfterHrs: overtimeSunAfterHrs,
                                earlyMorningShiftStartTime:earlyMorningShiftStartTime,
                                earlyMorningShiftEndTime:earlyMorningShiftEndTime,
                                dayShiftStartTime: dayShiftStartTime,
                                dayShiftEndTime: dayShiftEndTime,
                                afternoonShiftStartTime: afternoonShiftStartTime,
                                afternoonShiftEndTime: afternoonShiftEndTime,
                                nightShiftStartTime: nightShiftStartTime,
                                nightShiftEndTime: nightShiftEndTime
                            },
                            success: function (data) {
                                console.log('update return' + data);
                                if (data == 'updated') {
                                    loadPayrules();
                                    location.reload();
                                }
                            }
                        });
                    }
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
</body>

</html>