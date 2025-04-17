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
        <h2>MYOB CSV Generation</h2>
        <div class="error"></div>
        <form name="frmInvoice" id="frmInvoice" class="smart-form" method="post">
            <div class="row">
                <section class="col col-3">
                    <label for="myobacc" class="input">MYOB Account No:
                        <input type="text" name="myobacc" id="myobacc" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="MYOB Account No"/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="weekendingDate" class="input">
                        <input type="text" name="weekendingDate" id="weekendingDate" value="" class="pull-left" placeholder="Weekending Date" readonly/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="invoiceDate" class="input">
                        <input type="text" name="invoiceDate" id="invoiceDate" value="" class="pull-left" placeholder="Invoice Date" readonly/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="createCSVBtn">
                        <button name="createCSVBtn" id="createCSVBtn" class="createCSVBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>&nbsp; Create CSV</button>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="createPDFBtn">
                        <button name="createPDFBtn" id="createPDFBtn" class="createPDFBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Create PDF</button>
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
        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="invoiceDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="invoiceDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#invoiceDate').val(picker.startDate.format('YYYY-MM-DD'));
        });

        $(document).on('click','.createCSVBtn', function () {
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
                    weekendingDate:{
                        required: true
                    },
                    invoiceDate: {
                        required: true
                    },
                    myobacc:{
                        required: true
                    }
                },
                messages: {
                    weekendingDate:{
                        required: "Please select Weekending Date"
                    },
                    invoiceDate:{
                        required: "Please select Invoice Date"
                    },
                    myobacc:{
                        required: "Please enter Account No"
                    }
                },
                submitHandler: function (form) {
                    var myobacc = $('#myobacc').val();
                    var invoiceDate = $('#invoiceDate').val();
                    var weekendingDate = $('#weekendingDate').val();
                    var action = 'csv';
                    $.ajax({
                        type: "POST",
                        url: "./generateMYOBCSV.php",
                        data: {myobacc:myobacc,weekendingDate:weekendingDate,invoiceDate:invoiceDate,action:action},
                        dataType: "text",
                        success: function (data) {
                            console.log('myob'+data);
                            window.open(data);
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
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
                    weekendingDate:{
                        required: true
                    },
                    invoiceDate: {
                        required: true
                    },
                    myobacc:{
                        required: true
                    }
                },
                messages: {
                    weekendingDate:{
                        required: "Please select Weekending Date"
                    },
                    invoiceDate:{
                        required: "Please select Invoice Date"
                    },
                    myobacc:{
                        required: "Please enter Account No"
                    }
                },
                submitHandler: function (form) {
                    var myobacc = $('#myobacc').val();
                    var invoiceDate = $('#invoiceDate').val();
                    var weekendingDate = $('#weekendingDate').val();
                    var action = 'pdf';
                    $.ajax({
                        type: "POST",
                        url: "./generateMYOBCSV.php",
                        data: {myobacc:myobacc,weekendingDate:weekendingDate,invoiceDate:invoiceDate,action:action},
                        dataType: "text",
                        success: function (data) {
                            console.log('myobpdf'+data);
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