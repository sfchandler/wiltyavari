<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 2/09/2019
 * Time: 12:06 PM
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
        <h2>Invoice Report</h2>
        <div class="error"></div>
        <form name="frmInvoice" id="frmInvoice" class="smart-form" method="post">
            <div class="row">
                <section class="col col-3">
                    <label for="startDate" class="input">
                        <input type="text" name="startDate" id="startDate" value="" class="pull-left" placeholder="Start Date" readonly/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="endDate" class="input">
                        <input type="text" name="endDate" id="endDate" value="" class="pull-left" placeholder="End Date" readonly/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="createExcelBtn">
                        <button name="createExcelBtn" id="createExcelBtn" class="createExcelBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Generate Excel Report</button>
                    </label>
                    <label for="createPDFBtn">
                        <button name="createPDFBtn" id="createPDFBtn" class="createPDFBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Generate PDF Report</button>
                    </label>
                </section>
            </div>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.endDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $(document).on('click','.createPDFBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmInvoice = $("#frmInvoice").validate({
                errorClass	: errorClass,
                errorElement: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    startDate:{
                        required: true
                    },
                    endDate: {
                        required: true
                    }
                },
                messages: {
                    startDate:{
                        required: "Please select Start Date"
                    },
                    endDate:{
                        required: "Please select End Date"
                    }
                },
                submitHandler: function (form) {
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var action = 'pdf';
                    $.ajax({
                        type: "POST",
                        url: "./generateInvoiceReport.php",
                        data: {startDate:startDate,endDate:endDate,action:action},
                        dataType: "text",
                        success: function (data) {
                            window.open(data);
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','.createExcelBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmInvoice = $("#frmInvoice").validate({
                errorClass	: errorClass,
                errorElement: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    startDate:{
                        required: true
                    },
                    endDate: {
                        required: true
                    }
                },
                messages: {
                    startDate:{
                        required: "Please select Start Date"
                    },
                    endDate:{
                        required: "Please select End Date"
                    }
                },
                submitHandler: function (form) {
                    var startDate = $('#startDate').val();
                    var endDate = $('#endDate').val();
                    var action = 'excel';
                    $.ajax({
                        type: "POST",
                        url: "./generateInvoiceReport.php",
                        data: {startDate:startDate,endDate:endDate,action:action},
                        dataType: "text",
                        success: function (data) {
                            window.open(data);
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

    });
</script>
</body>

</html>