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
        <h2>Transaction Code Details</h2>
        <div class="error"></div>
        <div style="width:100%">
            <div style="padding-left:20px;padding-bottom:45px; width:45%; float: left;">
                <form name="frmTransCode" id="frmTransCode" class="smart-form" method="post">
                    <div class="row">
                        <section class="col col-3">
                            <label for="transCode" class="input">Transaction Code:
                                <input type="text" name="transCode" id="transCode" value="" class="input"/>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="transCodeDesc" class="input">Transaction Description:
                                <input type="text" name="transCodeDesc" id="transCodeDesc" value="" class="input"/>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">Transaction Code Type:
                            <label for="transCodeType" class="select">
                                <select name="transCodeType" id="transCodeType" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                </select><i></i></label>
                        </section>
                        <section class="col col-3">Before/After Tax:
                            <label for="taxorder" class="select">
                                <select name="taxorder" id="taxorder" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                    <option value="before">Before</option>
                                    <option value="after">After</option>
                                </select><i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="payslipOrder" class="input">PaySlip Order:
                                <input name="payslipOrder" id="payslipOrder" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="groupCertFormat" class="input">Group Cert Format:
                                <input name="groupCertFormat" id="groupCertFormat" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="printOnPaySlip" class="input">Print On PaySlip:
                                <input name="printOnPaySlip" id="printOnPaySlip" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="printOnReports" class="input">Print On Reports:
                                <input name="printOnReports" id="printOnReports" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="defaultPercent" class="input">Default Percent:
                                <input name="defaultPercent" id="defaultPercent" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="defaultAmount" class="input">Default Amount:
                                <input name="defaultAmount" id="defaultAmount" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="addUnitsAsHours" class="input">Add units as Hours:
                                <input name="addUnitsAsHours" id="addUnitsAsHours" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="autoReduceCode" class="input">Auto Reduce Code:
                                <input name="autoReduceCode" id="autoReduceCode" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="autoBillPercent" class="input">Auto Bill Percentage:
                                <input name="autoBillPercent" id="autoBillPercent" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="autoBillCode" class="input">Auto Bill Code:
                                <input name="autoBillCode" id="autoBillCode" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="superfundABN" class="input">Superfund ABN:
                                <input name="superfundABN" id="superfundABN" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="superfundSPINID" class="input">Superfund SPIN ID:
                                <input name="superfundSPINID" id="superfundSPINID" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="usi" class="input">USI:
                                <input name="usi" id="usi" value="" class="input"/>
                                <i></i></label>
                        </section>
                        <section class="col col-3">
                            <label for="product_name" class="input">Product Name:
                                <input name="product_name" id="product_name" value="" class="input"/>
                                <i></i></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12">
                            <input type="submit" name="transCodeSaveBtn" id="transCodeSaveBtn" class="transCodeSaveBtn btn btn-primary btn-square btn-sm" value="Save TransCode"/>
                            <input type="reset" name="resetBtn" id="resetBtn" class="resetBtn btn btn-primary btn-square btn-sm" value="Reset/Cancel"/>
                        </section>
                    </div>
                </form>
                </div>
                <div style="padding-left:20px; padding-bottom:50px; width:55%; float: left; overflow-y: scroll; height: 500px;">
                    <div style="padding-left:10px; padding-bottom:20px; width:100%; float: left; overflow-y: scroll; height: 500px;">
                        <table id="tblTransCodes" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Transaction Code</th>
                                <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Transaction Code Description</th>
                                <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Product Name</th>
                                <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action</th>
                            </tr>
                            </thead>
                            <tbody class="tblTransCodeBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
        </div>
    <!-- END MAIN CONTENT -->
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
        loadTransCodeTypes();
        loadTransactionCodes();
        function loadTransactionCodes(){
            $.ajax({
                type: 'post',
                url: 'getTransCodesList.php',
                dataType: 'html',
                success: function (data) {
                }
            }).done(function(data) {
                $('.tblTransCodeBody').html('');
                $('.tblTransCodeBody').html(data);
            });
        }
        function loadTransCodeTypes(){
            $.ajax({
                type: 'post',
                url: 'getTransCodeType.php',
                dataType: 'html',
                success: function (data) {
                }
            }).done(function(data) {
                $('#transCodeType').html('');
                $('#transCodeType').html(data);
            });
        }
        $(document).on('click','#removeTransCodeBtn',function () {
            var transCode = $(this).closest('td').attr('data-transCode');
            $.ajax({
                type: 'post',
                url: 'removeTransCode.php',
                data:{transCode: transCode},
                dataType: 'html',
                success: function (data) {
                }
            }).done(function(data) {
                $('.tblTransCodeBody').html('');
                $('.tblTransCodeBody').html(data);
            });
        });
        $(document).on('click','#transCodeSaveBtn', function(evt) {

            var errorClass = 'invalid';
            var errorElement = 'em';
            var frm = $("#frmTransCode").validate({
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
                    transCode:{
                        required: true
                    },
                    transCodeDesc: {
                        required: true
                    },
                    defaultPercent:{
                        required: true
                    },
                    autoBillPercent:{
                        required: true
                    },
                    superfundABN:{
                        required: true
                    }
                },
                messages: {
                    transCode:{
                        required: "TransCode is required"
                    },
                    transCodeDesc: {
                        required: "Please enter Transaction Code Description"
                    },
                    defaultPercent:{
                        required: "Please enter default Percentage"
                    },
                    autoBillPercent:{
                        required: "Please enter Auto Bill Percentage"
                    },
                    superfundABN:{
                        required: "Please enter SuperFund ABN"
                    }
                },
                submitHandler: function (form) {
                        $.ajax({
                            type: 'post',
                            url: 'processTransCode.php',
                            data: $('form').serialize(),
                            success: function (data) {
                                console.log('return' + data);
                                if (data == 'inserted' || data == 'updated') {
                                    location.reload();
                                }else if(data.length == 0){
                                    location.reload();
                                }
                            }
                        });
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','#editTransCodeBtn',function(ev){
            var transCode = $(this).closest('td').attr('data-transCode');
            $('#transCode').val(transCode);
            var transCodeDesc = $(this).closest('td').attr('data-transCodeDesc');
            $('#transCodeDesc').val(transCodeDesc);
            var transCodeType = $(this).closest('td').attr('data-transCodeType');
            $('#transCodeType').val(transCodeType);
            var taxOrder = $(this).closest('td').attr('data-taxOrder');
            $('#taxorder').val(taxOrder);
            var payslipOrder = $(this).closest('td').attr('data-payslipOrder');
            $('#payslipOrder').val(payslipOrder);
            var groupCertFormat = $(this).closest('td').attr('data-groupCertFormat');
            $('#groupCertFormat').val(groupCertFormat);
            var printOnPaySlip = $(this).closest('td').attr('data-printOnPaySlip');
            $('#printOnPaySlip').val(printOnPaySlip);
            var printOnReports = $(this).closest('td').attr('data-printOnReports');
            $('#printOnReports').val(printOnReports);
            var defaultPercent = $(this).closest('td').attr('data-defaultPercent');
            $('#defaultPercent').val(defaultPercent);
            var defaultAmount = $(this).closest('td').attr('data-defaultAmount');
            $('#defaultAmount').val(defaultAmount);
            var addUnitsAsHours = $(this).closest('td').attr('data-addUnitsAsHours');
            $('#addUnitsAsHours').val(addUnitsAsHours);
            var autoReduceCode = $(this).closest('td').attr('data-autoReduceCode');
            $('#autoReduceCode').val(autoReduceCode);
            var autoBillPercent = $(this).closest('td').attr('data-autoBillPercent');
            $('#autoBillPercent').val(autoBillPercent);
            var autoBillCode = $(this).closest('td').attr('data-autoBillCode');
            $('#autoBillCode').val(autoBillCode);
            var superfundABN = $(this).closest('td').attr('data-superfundABN');
            $('#superfundABN').val(superfundABN);
            var superfundSPINID = $(this).closest('td').attr('data-superfundSPINID');
            $('#superfundSPINID').val(superfundSPINID);
            var usi = $(this).closest('td').attr('data-usi');
            $('#usi').val(usi);
            var product_name = $(this).closest('td').attr('data-product_name');
            $('#product_name').val(product_name);
        });
    });
</script>
</body>

</html>