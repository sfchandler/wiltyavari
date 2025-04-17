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
        <h2>Timesheet Details</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">

                <div class="row">
                    <!--<section class="col col-12">
                            <label>Client: </label>
                            <label class="clientName"></label>
                            <label>Position: </label>
                            <label class="jobPosition"></label>
                            <input type="hidden" name="clientId" id="clientId" value=""/>
                            <input type="hidden" name="positionId" id="positionId" value=""/>
                    </section>-->
                    <section class="col col-3">
                        <label for="clientId" class="select">
                            <select name="clientId" id="clientId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="positionId" class="select">
                            <select name="positionId" id="positionId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="deptId" class="select">
                            <select name="deptId" id="deptId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="jobCode" class="input">
                            <label class="pull-left">JOBCODE:&nbsp;</label><label id="jobCodeLabel"></label>
                            <input type="text" name="jobCode" id="jobCode" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Job Code"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="weekendingDate" class="input">
                            <input type="text" name="weekendingDate" id="weekendingDate" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="candidateId" class="input">
                            <input type="text" name="candidateId" id="candidateId" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee ID/Candidate ID"/>
                        </label>
                        <label for="employeeName" class="input">
                            <input id="employeeName" name="employeeName" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
                        </label><input type="hidden" name="empSelected" id="empSelected"/>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label class="select">
                        <select name="transCode" id="transCode" class="transCodeMenu">

                        </select>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="transCodeAmount" class="input">
                            <input id="transCodeAmount" name="transCodeAmount" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Amount"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <button name="addBtn" id="addBtn" class="addBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-plus"></i>&nbsp; Add Detail</button>
                    </section>
                </div>
                <div>
                    <table id="tblTransCodes" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Transaction Code</th>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Transaction Code Description</th>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Transaction Code Amount</th>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action</th>
                        </tr>
                        </thead>
                        <tbody class="tblTransCodeBody">

                        </tbody>
                    </table>
                </div>
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
        /*$('.ui-autocomplete-input').css('width','40px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
            }
        });*/
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
        loadClients();
        $('#positionId').hide();
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
            var clientId = $('#clientId option:selected').val();
            var action = 'CLIENTBASED';
            $.ajax({
                url :"loadPositions.php",
                type:"POST",
                dataType:"html",
                data:{clientId:clientId,action:action},
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
            $('#jobCode').val('');
            loadPositions();
            loadDepartments();
        });

        $(document).on('change', '#positionId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            var deptId = $('#deptId :selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId, deptId:deptId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobCodeLabel').html('');
                $('#jobCodeLabel').html(data);
                $('#jobCode').val('');
                $('#jobCode').val(data);
            });
        });
        $(document).on('click', '#positionId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            var deptId = $('#deptId :selected').val();
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
                $('#jobCode').val('');
                $('#jobCode').val(data);
            });
        });
        $('#candidateId').donetyping(function() {
            var candidateId = $('#candidateId').val();
            var status = 'NAME';
            $.ajax({
                url:"getTimeSheetInfo.php",
                type:"POST",
                dataType:"html",
                data:{candidateId:candidateId,status:status},
                success: function(data){
                    $('.empName').html('');
                    $('.empName').html(data);
                }
            });
        });
        $('#jobCode').donetyping(function() {
            var jobCode = $('#jobCode').val();
            $.ajax({
                url:"getJobInfo.php",
                type:"POST",
                dataType:"json",
                data:{jobCode:jobCode},
                success: function(data){
                    $('#positionId').val('');
                    $('#positionId').val(data.positionId);
                    $('#clientId').val('');
                    $('#clientId').val(data.clientId);
                    $('.jobPosition').html('');
                    $('.jobPosition').html(data.position);
                    $('.clientName').html('');
                    $('.clientName').html(data.clientName);
                }
            });
        });
        /*$('.ui-autocomplete-input').css('width','400px')
        $('#employeeName').autocomplete({
            source: <?php // include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#candidateId').val('');
                $('#candidateId').val(candidateId);
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
                /*$('#empSelected').val('');
                $('#empSelected').val(candidateId);*/
                $('#candidateId').val(candidateId);
                return true;
            },
            focus: function(event, ui){
                var empName = ui.item.value;
                var candidateId = ui.item.id;
               /* $('#empSelected').val('');
                $('#empSelected').val(candidateId);*/
                $('#candidateId').val(candidateId);
                return true;
            },
        });
        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        loadTransactionCodes();
        function loadTransactionCodes(){
            var menu = 'dropdown';
            $.ajax({
                type: 'post',
                url: 'getTransCodesList.php',
                dataType: 'html',
                data:{menu:menu},
                success: function (data) {
                }
            }).done(function(data) {
                $('.transCodeMenu').html('');
                $('.transCodeMenu').html(data);
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
        $(document).on('change', '.transCodeMenu', function(){
            var candidateId = $('#candidateId').val();
            var jobCode = $('#jobCode').val();
            var weekendingDate = $('#weekendingDate').val();
            var action = 'Get';
            $.ajax({
                url: "processTimesheetDetail.php",
                type: "POST",
                dataType: "html",
                data: {action:action,candidateId: candidateId, jobCode:jobCode,weekendingDate:weekendingDate},
                success: function (data) {
                    $('.tblTransCodeBody').html('');
                    $('.tblTransCodeBody').html(data);
                    var transCodeAmt = $('.removeBtn').closest('td').attr('data-transCodeAmt');
                    $('#transCodeAmount').val(transCodeAmt);
                }
            });
        });
        $(document).on('click','.addBtn',function () {
            var transCode = $('#transCode :selected').val();
            var candidateId = $('#candidateId').val();
            var jobCode = $('#jobCode').val();
            var weekendingDate = $('#weekendingDate').val();
            var clientId = $('#clientId').val();
            var positionId = $('#positionId').val();
            var transCodeAmount = $('#transCodeAmount').val();
            var action = 'Add';
            if(transCode != 'None') {
                $.ajax({
                    url: "processTimesheetDetail.php",
                    type: "POST",
                    dataType: "html",
                    data: {action:action,transCodeAmount:transCodeAmount,candidateId: candidateId, transCode: transCode,jobCode:jobCode,weekendingDate:weekendingDate,clientId:clientId,positionId:positionId},
                    success: function (data) {
                        $('.tblTransCodeBody').html('');
                        $('.tblTransCodeBody').html(data);
                    }
                });
            }
        });
        $(document).on('click','#removeBtn',function(){
            var candidateId = $(this).closest('td').attr('data-candidateid');
            var transCode = $(this).closest('td').attr('data-transcode');
            var jobCode = $(this).closest('td').attr('data-jobcode');
            var weekendingDate = $(this).closest('td').attr('data-wkdate');
            var action = 'Remove';
            $.ajax({
                url: "processTimesheetDetail.php",
                type: "POST",
                dataType: "html",
                data: {action: action,candidateId: candidateId, transCode: transCode,jobCode:jobCode,weekendingDate:weekendingDate},
                success: function (data) {
                    $('.tblTransCodeBody').html('');
                    $('.tblTransCodeBody').html(data);
                }
            });
        });
    });
</script>
</body>

</html>