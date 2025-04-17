<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 19/09/2017
 * Time: 3:18 PM
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
        <h2>Pay Slip Message</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset>
                <div>
                    <label for="payrollId" class="input">Select Existing Payroll Name:
                        <select name="payrollId" id="payrollId"></select>
                    </label>
                </div>
                <div>
                    <label for="payrollName" class="input">Payroll Name:
                        <input type="text" name="payrollName" id="payrollName"/><input type="hidden" name="id" id="id"/>
                    </label>
                </div>
                <div>
                    <label for="profitCentre" class="input">Profit Centre:
                        <select name="profitCentre" id="profitCentre"></select>
                    </label>
                </div>
                <div>
                    <label for="yearStartDate" class="input">Year Start Date
                        <input type="text" name="yearStartDate" id="yearStartDate"/>
                    </label>
                </div>
                <div>
                    <label for="yearEndDate" class="input">Year End Date
                        <input type="text" name="yearEndDate" id="yearEndDate"/>
                    </label>
                </div>
                <div>
                    <label for="frequency" class="select">Frequency:
                        <select name="frequency" id="frequency" class="select">
                            <option value="Weekly">Weekly</option>
                        </select>
                    </label>
                </div>
                <div>
                    <label for="periodEndDay" class="select">Frequency:
                        <select name="periodEndDay" id="periodEndDay" class="select">
                            <option value="Sunday">Sunday</option>
                            <option value="Monday">Monday</option>
                        </select>
                    </label>
                </div>
                <div>
                    <label for="payslipmsg" class="textarea">Enter PaySlip Message
                        <textarea type="textarea" name="payslipmsg" id="payslipmsg" class="textarea" rows="10" style="width: 100%"></textarea>
                    </label>
                </div>
            </fieldset>
            <div>
                <label for="saveBtn">
                    <button name="saveBtn" id="saveBtn" class="saveBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-sticky-note"></i>&nbsp; Save</button>
                </label>
            </div>
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
        getProfitCentres();
        function getProfitCentres() {
            var action = 'GET';
            $.ajax({
                url: "getProfitCentre.php",
                type: "POST",
                dataType: "html",
                data:{action:action},
                success: function (data) {
                    $('#profitCentre').html('');
                    $('#profitCentre').html(data);
                }
            });
        }
        $('input[name="yearStartDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="yearStartDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#yearStartDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="yearEndDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="yearEndDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#yearEndDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $(document).on('click','.saveBtn', function () {
            var payslipmsg = $('textarea#payslipmsg').val();
            var payrollName = $('#payrollName').val();
            var profitCentre = $('#profitCentre :selected').val();
            var yearStartDate = $('#yearStartDate').val();
            var yearEndDate = $('#yearEndDate').val();
            var frequency = $('#frequency').val();
            var periodEndDay = $('#periodEndDay :selected').val();
            var id = $('#id').val();
            $.ajax({
                url:"savePayrollDetails.php",
                type:"POST",
                dataType:"text",
                data:{payrollName:payrollName,profitCentre:profitCentre,yearStartDate:yearStartDate,yearEndDate:yearEndDate,frequency:frequency,periodEndDay:periodEndDay,payslipmsg:payslipmsg},
                success: function(data){
                    console.log('MSG'+data);
                    //if(data=='updated') getPaySlipMessage();
                }
            });
        });
        $(document).on('click','.editBtn', function () {
            var payslipmsg = $('textarea#payslipmsg').val();
            var payrollName = $('#payrollName').val();
            var profitCentre = $('#profitCentre :selected').val();
            var yearStartDate = $('#yearStartDate').val();
            var yearEndDate = $('#yearEndDate').val();
            var frequency = $('#frequency').val();
            var periodEndDay = $('#periodEndDay :selected').val();
            var id = $('#id').val();
            $.ajax({
                url:"savePayrollDetails.php",
                type:"POST",
                dataType:"text",
                data:{payrollName:payrollName,profitCentre:profitCentre,yearStartDate:yearStartDate,yearEndDate:yearEndDate,frequency:frequency,periodEndDay:periodEndDay,payslipmsg:payslipmsg,id:id},
                success: function(data){
                    console.log('MSG'+data);
                    //if(data=='updated') getPaySlipMessage();
                }
            });
        });
        $(document).on('change','#payrollId',function(){
            var id = $('#payrollId :selected').val();
            $.ajax({
                url:"getExistingPayrollName.php",
                type:"POST",
                dataType:"html",
                data:{id:id},
                success: function(data){
                    data = $.parseJSON(data);
                    $.each(data, function(index, element) {
                        $('textarea#payslipmsg').val(element.payslipMessage);
                        $('#payrollName').val(element.payrollName);
                        $('#profitCentre :selected').val(element.profitCentre);
                        $('#yearStartDate').val(element.yearStartDate);
                        $('#yearEndDate').val(element.yearEndDate);
                        $('#frequency').val(element.frequency);
                        $('#periodEndDay :selected').val(element.periodEndDay);
                        $('#id').val(element.id);
                    });
                }
            });
        });
        getPayrollDetails();
        function getPayrollDetails(){
            $.ajax({
                url:"getPayrollNamesDropDown.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#payrollId').html('');
                    $('#payrollId').html(data);
                }
            });
        }
    });
</script>
</body>

</html>