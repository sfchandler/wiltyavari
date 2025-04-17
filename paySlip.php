<?php

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'ACCOUNTS') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
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
    <div id="content" style="height: auto">
        <div class="content-body no-content-padding">
            <h2>Pay Slip Generation</h2>
            <div class="error"></div>
            <form name="frmpslip" id="frmpslip" class="smart-form" action="">
            <div style="margin-left: 20px; width: 100%">
                <div class="row">
                    <section class="col col-2">
                        <label for="payrollName">Select Payroll Name
                            <select name="payrollName" id="payrollName" class="form-control"></select>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label for="companyId">Select Company
                            <select name="companyId" id="companyId" class="form-control">
                                <option value="">Select Company</option>
                                <?php echo getCompanyDropdown($mysqli); ?>
                            </select>
                        </label>
                    </section>
                    <section class="col col-2">
                        <div class="compLogo"></div>
                    </section>
                    <section class="col col-6"></section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label for="candidateId">
                            <input type="text" name="candidateId" id="candidateId" value="" class="form-control" placeholder="Employee ID/Candidate ID"/>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label for="employeeName">
                            <input type="text" name="employeeName" id="employeeName" value="" class="form-control" placeholder="Employee Name"/>
                        </label>
                    </section>
                    <section class="col col-2"></section>
                    <section class="col col-6"></section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label for="weekWorked">Select Week worked with Pay run ID
                            <select name="weekWorked" id="weekWorked" class="form-control">
                                <?php echo getPayRunDates($mysqli); ?>
                            </select>
                        </label>
                    </section>
                    <section class="col col-2"></section>
                    <section class="col col-2"></section>
                    <section class="col col-6"></section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label for="startDate">Pay Slip Period Start Date
                            <input type="text" name="startDate" id="startDate" value="" class="form-control" placeholder="Pay Slip Start Date" required/>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label for="endDate">Pay Slip Period End Date
                            <input type="text" name="endDate" id="endDate" value="" class="form-control" placeholder="Pay Slip End Date" required/>
                        </label>
                    </section>
                    <section class="col col-2"></section>
                    <section class="col col-6"></section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label for="startPayDate">Pay Slip Date
                            <input type="text" name="startPayDate" id="startPayDate" value="" class="form-control" placeholder="Pay Slip Date" required/>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label for="payDate">Pay Date
                            <input type="text" name="payDate" id="payDate" value="" class="form-control" placeholder="Pay date"/>
                        </label>
                    </section>
                    <section class="col col-2"></section>
                    <section class="col col-6"></section>
                </div>
                <!--<div>
                    <label for="previewPrint">
                        <input type="checkbox" name="previewPrint" id="previewPrint" value=""/>
                        <i>Preview Print</i>
                    </label>
                </div>-->
                <div class="row">
                    <section class="col col-2">
                        <label for="emailSlips">
                            <input type="checkbox" name="emailSlips" id="emailSlips" value="Send"/><i>Email PaySlips</i>
                        </label>
                    </section>
                    <section class="col col-2"></section>
                    <section class="col col-2"></section>
                    <section class="col col-6"></section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label for="generateBtn">
                            <button name="generatePaymentBtn" id="generatePaymentBtn"
                                    class="generatePaymentBtn btn btn-primary btn-square btn-sm"><i
                                        class="glyphicon glyphicon fa fa-bank"></i>&nbsp; Generate PaySlips
                            </button>
                        </label>
                    </section>
                    <section class="col col-2"></section>
                    <section class="col col-2"></section>
                    <section class="col col-6"></section>
                </div>
                <div id="mailSentCount" class="error"></div>
            </div>
            </form>
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
    $(document).ready(function () {
        $('input[name="payDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="payDate"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected' + picker.startDate.format('YYYY-MM-DD'));
            $('#payDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="startPayDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startPayDate"]').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#startPayDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('.ui-autocomplete-input').css('width', '40px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function (event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#candidateId').val('');
                $('#candidateId').val(candidateId);
            }
        });
        $(document).on('change', '#companyId', function () {
            var action = 'LOGO';
            var companyId = $('#companyId :selected').val();
            $.ajax({
                type: "POST",
                url: "cmpInfoProcess.php",
                data: {action: action, companyId: companyId},
                dataType: "html",
                success: function (data) {
                    $('.compLogo').html('');
                    $('.compLogo').html(data);
                }
            });
        });
        $(document).on('click', '.generatePaymentBtn', function (e) {
            e.preventDefault();
            var candidateId = $('#candidateId').val();
            var paidDate = $('#weekWorked :selected').val();
            var payrollName = $('#payrollName :selected').val();
            var emailSlips = $('#emailSlips:checked').val();
            var companyId = $('#companyId :selected').val();
            var payDate = $('#payDate').val();
            var startPayDate = $('#startPayDate').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            if (payDate === '') {
                alert('Please select a Pay date');
            } else if (startPayDate === '') {
                alert('Please select a Start Pay Date');
            } else {
                generatePaySlip(candidateId, paidDate, payrollName, emailSlips, companyId, payDate, startPayDate, startDate, endDate);
            }
        });
        getPayrollNames();

        function getPayrollNames() {
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

        function generatePaySlip(candidateId, paidDate, payrollName, emailSlips, companyId, payDate, startPayDate, startDate, endDate) {
            $.ajax({
                url: "generatePaySlip.php",
                type: "POST",
                dataType: "html",
                data: {
                    candidateId: candidateId,
                    paidDate: paidDate,
                    payrollName: payrollName,
                    emailSlips: emailSlips,
                    companyId: companyId,
                    payDate: payDate,
                    startPayDate: startPayDate,
                    startDate: startDate,
                    endDate: endDate
                },
                success: function (data) {
                    console.log(data);
                    $('#mailSentCount').html('');
                    $('#mailSentCount').html(data);
                }
            });
        }
    });
</script>
</body>

</html>