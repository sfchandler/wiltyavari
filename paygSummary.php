<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 24/01/2019
 * Time: 2:33 PM
 */

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
        <h2>PAYG SUMMARY Report</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label for="startDate" class="input">
                        <input type="text" name="startDate" id="startDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="From Date"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="endDate" class="input">
                        <input type="text" name="endDate" id="endDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="To Date"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="employeeName" class="input">
                        <input type="text" name="employeeName" id="employeeName" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
                    </label>
                    <label for="candidateId" class="input">
                        <input type="hidden" name="candidateId" id="candidateId" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="CandidateID"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="generateBtn">
                        <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-success btn-danger btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i> View/Download Report</button>
                        <button name="generateAmendedBtn" id="generateAmendedBtn" class="generateAmendedBtn btn btn-success btn-success btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i> Amended File Download</button>
                        <button name="generateExcelBtn" id="generateExcelBtn" class="generateExcelBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i> Excel Report</button>
                        <button name="generateTotalBtn" id="generateTotalBtn" class="generateTotalBtn btn btn-default btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i> Download Summary Totals</button>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-12">
                    <fieldset>
                        <legend>PAYG Mailing Lists</legend>
                    </fieldset>
                    <div style="padding-left:10px;padding-right:10px; padding-bottom:10px; width:100%; overflow-y: scroll; height: 300px;">
                        <div class="mailList">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <th data-class="expand"><i class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>EmployeeID</th>
                                <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Name</th>
                                <th data-class="expand"><i class="fa fa-fw fa-envelope txt-color-blue hidden-md hidden-sm hidden-xs"></i>Email</th>
                                <th data-class="expand"><i class="fa fa-fw fa-file-pdf-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>PAYGSummary</th>
                                <th data-class="expand"><i class="fa fa-fw fa-mail-forward txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action</th>
                                </thead>
                                <tbody id="mBody">
                                <?php echo getPAYGEmailList($mysqli); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <fieldset>
                        <legend>PAYG EMPDUPE FILE TO BE UPLOADED</legend>
                    </fieldset>
                    <div>
                        <?php echo getEMPDUPEFileList($mysqli);?>
                    </div>
                </section>
                <div id="swa"></div>
            </div>
            </fieldset>
        </div>
        <!--<table cellpadding="0" cellspacing="0" border="0">
            <thead></thead>
            <tbody class="paygRows">

            </tbody>
        </table>-->
        <div class="paygRows"></div>
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
        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            //console.log('Start Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            //console.log('End Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('.ui-autocomplete-input').css('width','40px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#candidateId').val('');
                $('#candidateId').val(candidateId);
            }
        });
        $(document).on('click','.generateBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var candidateId = $('#candidateId').val();
            if(startDate!='' && endDate != '') {
                $.ajax({
                    url: "genPaygSummary.php",
                    type: "POST",
                    dataType: "text",
                    data: {startDate: startDate, endDate: endDate,candidateId:candidateId},
                    success: function (data) {
                        console.log(data);
                        var responseData = data.split('^');
                        window.open(responseData[0]);
                        window.open(responseData[1]);
                    },
                    complete: function(){
                        $body.removeClass("loading");
                        loadMaillist();
                    }
                });
            }else{
                $('.error').html('Please fill all the fields');
            }
        });
        $(document).on('click','.generateAmendedBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            if(startDate!='' && endDate != '') {
                $.ajax({
                    url: "genAmendedPaygSummary.php",
                    type: "POST",
                    dataType: "text",
                    data: {startDate: startDate, endDate: endDate},
                    success: function (data) {
                        //$('#swa').html(data);
                        window.open(data);
                    },
                    complete: function(){
                        $body.removeClass("loading");
                        loadMaillist();
                    }
                });
            }else{
                $('.error').html('Please fill all the fields');
            }
        });
        $(document).on('click','.generateExcelBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            if(startDate!='' && endDate != '') {
                $.ajax({
                    url: "genPAYGExcel.php",
                    type: "POST",
                    dataType: "text",
                    data: {startDate: startDate, endDate: endDate},
                    success: function (data) {
                        console.log(data);
                        if(data != ''){
                            window.open(data);
                        }
                    }
                });
            }else{
                $('.error').html('Please fill all the fields');
            }
        });
        $(document).on('click','.generateTotalBtn',function () {
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            if(startDate!='' && endDate != '') {
                $.ajax({
                    url: "genPaygSummaryTotal.php",
                    type: "POST",
                    dataType: "text",
                    data: {startDate: startDate, endDate: endDate},
                    success: function (data) {
                        console.log(data);
                        if(data != ''){
                            window.open(data);
                        }
                    }
                });
            }else{
                $('.error').html('Please fill all the fields');
            }
        });
        function loadMaillist(){
            var action = 'GetAll';
            $.ajax({
                url: "sendPAYGMail.php",
                type: "POST",
                dataType: "text",
                data: {action:action},
                success: function (data) {
                    $('#mBody').html('');
                    $('#mBody').html(data);
                }
            });
        }
        $(document).on('click','.sendMailBtn',function () {
            var action = 'Send';
            var id = $(this).closest('td').attr('data-id');
            var empid = $(this).closest('td').attr('data-empid');
            var email = $(this).closest('td').attr('data-email');
            var path = $(this).closest('td').attr('data-path');
            $.ajax({
                url: "sendPAYGMail.php",
                type: "POST",
                dataType: "text",
                data: {id: id,empid:empid,email:email,path:path,action:action},
                success: function (data) {
                }
            });
        });
        $(document).on('click','.sendAllMailBtn',function () {
            var action = 'SendAll';
            $.ajax({
                url: "sendPAYGMail.php",
                type: "POST",
                dataType: "text",
                data: {action:action},
                success: function (data) {

                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>