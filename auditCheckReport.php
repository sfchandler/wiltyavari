<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 12/09/2018
 * Time: 10:51 AM
 */

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
        <h2>Audit Check Report</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label for="startDate" class="input">
                        <input type="text" name="startDate" id="startDate" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="From Date"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="endDate" class="input">
                        <input type="text" name="endDate" id="endDate" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="To Date"/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-12">
                    <button name="generateConExcelBtn" id="generateConExcelBtn" class="generateConExcelBtn btn btn-info btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>&nbsp; View Consultant Report</button>
                    &nbsp;&nbsp;
                    <button name="generateConBtn" id="generateConBtn" class="generateConBtn btn btn-danger btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; View Consultant Report</button>
                    &nbsp;&nbsp;
                    <button name="generateAllBtn" id="generateAllBtn" class="generateAllBtn btn btn-danger btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; View All Report</button>
                    &nbsp;&nbsp;
                    <button name="generateAccBtn" id="generateAccBtn" class="generateAccBtn btn btn-info btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>&nbsp; View Payroll Report</button>
                    <button name="generateAccPdfBtn" id="generateAccPdfBtn" class="generateAccPdfBtn btn btn-danger btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; View Payroll Report</button>

                    <button name="generatePoliceCheckBtn" id="generatePoliceCheckBtn" class="generatePoliceCheckBtn btn btn-warning btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Generate Police Check Report</button>

                </section>
            </div>
            </fieldset>
        </div>
        <div class="auditCheckReportDisplay">

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

        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
                console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.endDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.endDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $(document).on('click','.generateConBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'CONSULTANT';
            if((startDate.length >0) && (endDate.length>0)){
                $.ajax({
                    type:"POST",
                    url: "./processAuditCheckReport.php",
                    data: { startDate:startDate,endDate:endDate,action:action},
                    dataType: 'text',
                    success: function (data) {
                        window.open(data);
                    }
                });
            }else {
                alert('Please select Date range');
            }
        });

        $(document).on('click','.generateAccPdfBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'PAYROLLPDF';
            if((startDate.length >0) && (endDate.length>0)){
                $.ajax({
                    type:"POST",
                    url: "./processAuditCheckReport.php",
                    data: { startDate:startDate,endDate:endDate,action:action},
                    dataType: 'text',
                    success: function (data) {
                        window.open(data);
                    }
                });
            }else {
                alert('Please select Date range');
            }
        });
        $(document).on('click','.generateConExcelBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'CONSULTANTEXCEL';
            if((startDate.length >0) && (endDate.length>0)){
                $.ajax({
                    type:"POST",
                    url: "./processAuditCheckReport.php",
                    data: { startDate:startDate,endDate:endDate,action:action},
                    dataType: 'text',
                    success: function (data) {
                        window.open(data);
                    }
                });
            }else {
                alert('Please select Date range');
            }
        });
        $(document).on('click','.generateAllBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'ALLEXCEL';
            if((startDate.length >0) && (endDate.length>0)){
                $.ajax({
                    type:"POST",
                    url: "./processAuditCheckReport.php",
                    data: { startDate:startDate,endDate:endDate,action:action},
                    dataType: 'text',
                    success: function (data) {
                        window.open(data);
                    }
                });
            }else {
                alert('Please select Date range');
            }
        });
        $(document).on('click','.generatePoliceCheckBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'POLICECHECK';
            if((startDate.length >0) && (endDate.length>0)){
                $.ajax({
                    type:"POST",
                    url: "./processAuditCheckReport.php",
                    data: { startDate:startDate,endDate:endDate,action:action},
                    dataType: 'text',
                    success: function (data) {
                        window.open(data);
                    }
                });
            }else {
                alert('Please select Date range');
            }
        });
        $(document).on('click','.generateAccBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var action = 'PAYROLL';
            if((startDate.length >0) && (endDate.length>0)){
                $.ajax({
                    type:"POST",
                    url: "./processAuditCheckReport.php",
                    data: { startDate:startDate,endDate:endDate,action:action},
                    dataType: 'text',
                    success: function (data) {
                        window.open(data);
                    }
                });
            }else {
                alert('Please select Date range');
            }
        });
    });
</script>
</body>

</html>