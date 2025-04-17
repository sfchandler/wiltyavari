<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 18/10/2019
 * Time: 5:14 PM
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
        <h2>PAYROLL REPORT</h2>
        <div class="error"></div>
        <form name="frmPayroll" id="frmPayroll" class="smart-form" method="post">
            <div class="row">
                <section class="col col-3">
                    <label for="clientId" class="select">Select Client
                        <select name="clientId" id="clientId" class="select">
                        </select></label>
                </section>
                <section class="col col-3">
                    <label for="weekendingDate" class="input">Select Weekending Date
                        <input type="text" name="weekendingDate" id="weekendingDate" value="" class="pull-left" placeholder="Weekending Date" readonly/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="payrollReportBtn">
                        <button name="payrollReportBtn" id="payrollReportBtn" class="payrollReportBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>&nbsp;generate Report</button>
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
        /* AJAX loading animation */
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /* -  end  -*/
        populateClients();
        function populateClients(){
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDate').val(picker.startDate.format('YYYY-MM-DD'));
        });

        $(document).on('click','.payrollReportBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmPayroll = $("#frmPayroll").validate({
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
                    weekendingDate:{
                        required: true
                    }
                },
                messages: {
                    weekendingDate:{
                        required: "Please select Weekending Date"
                    }
                },
                submitHandler: function (form) {
                    var clientId = $('#clientId').val();
                    var weekendingDate = $('#weekendingDate').val();
                    $.ajax({
                        type: "POST",
                        url: "./genPayrollReport.php",
                        data: {clientId:clientId,weekendingDate:weekendingDate},
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