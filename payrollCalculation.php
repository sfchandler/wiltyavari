<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 7/09/2017
 * Time: 1:59 PM
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
        <h2>PRE Payroll Calculation Report VIEW</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label for="payrollName" class="select">Select Payroll Name
                        <select name="payrollName" id="payrollName" class="select"></select>
                    </label>
                    <br>
                    <label for="weekendingDate" class="input">Weekending Date
                        <input type="text" name="weekendingDate" id="weekendingDate" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date"/>
                    </label>
                    <br>
                    <br>
                    <button name="previewBtn" id="previewBtn" class="previewBtn btn btn-warning btn-square btn-sm"><i class="glyphicon glyphicon fa fa-search"></i>&nbsp; Preview Report</button>
                    <br>
                    <br>
                    <button name="validateBtn" id="validateBtn" class="validateBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-question"></i>&nbsp; Validate Casuals Info</button>
                    <br>
                    <br>
                    <label for="generateBtn">
                        <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-eye"></i>&nbsp; Generate Report</button>
                    </label>
                    &nbsp;<br>
                    <br>
                    <button name="generatePDFBtn" id="generatePDFBtn" class="generatePDFBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp;&nbsp;View PDF Report</button>
                </section>
                <section class="col col-3"></section>
                <section class="col col-6">
                    <h2>Generated Reports</h2>
                    <div style="width:100%; height:100%; overflow-y: scroll; height: 500px;">
                        <div class="reportsList">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <th data-class="expand"><i class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Payrun ID</th>
                                    <th data-class="expand"><i class="fa fa-fw fa-empire txt-color-blue hidden-md hidden-sm hidden-xs"></i>Weekending Date</th>
                                    <th data-class="expand">Download Link</th>
                                </thead>
                                <tbody>
                                    <?php echo getPayrollCalculationReportsList($mysqli); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
            <!--<div class="row">
                <section class="col col-3">
                    <label for="candidateId" class="input">
                        <input type="text" name="candidateId" id="candidateId" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee ID"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label>Name: </label>
                    <label class="empName"></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="jobCode" class="input">
                        <input type="text" name="jobCode" id="jobCode" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Job Code"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label>Position: </label>
                    <label class="jobPosition">
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label class="select">Profit Centre:
                        <select name="profitCentre" id="profitCentre" class="select"></select></label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="clientCode" class="input">
                        <input type="text" name="clientCode" id="clientCode" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Client Code"/>
                    </label>
                </section>
            </div>-->
            <div class="row">
                <section class="col col-3">

                </section>
            </div>
            <div class="row">
                <section class="col col-3">

                </section>
            </div>
            </fieldset>
        </div>
        <div class="validationDisplay">

        </div>
        <div class="auditReportDisplay">

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
        /* AJAX loading animation */
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /* -  end  -*/

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
            var status = 'JOBCODE';
            $.ajax({
                url:"getTimeSheetInfo.php",
                type:"POST",
                dataType:"html",
                data:{jobCode:jobCode,status:status},
                success: function(data){
                    $('.jobPosition').html('');
                    $('.jobPosition').html(data);
                }
            });
        });
        getPayrollNames();
        function getPayrollNames(){
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
        getPayrollNames();
        function getPayrollNames(){
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
        $('input[name="payDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="payDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#payDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        function generatePayrollCalculationReport(weekEndingDate,payDate,payrollName,candidateId,jobCode,profitCentre,clientCode,action){
            $.ajax({
                url:"genPayrollCalculation.php",
                type:"POST",
                dataType:"html",
                data:{weekEndingDate:weekEndingDate,payDate:payDate,payrollName:payrollName,candidateId:candidateId,jobCode:jobCode,profitCentre:profitCentre,clientCode:clientCode,action:action},
                success: function(data){
                    console.log(data);
                    $('.validationDisplay').html('');
                    $('.auditReportDisplay').html('');
                    $('.auditReportDisplay').html(data);
                }
            });
        }
        function validatePayrollInformation(weekEndingDate){
            $.ajax({
                url:"validatePayroll.php",
                type:"POST",
                dataType:"html",
                data:{weekEndingDate:weekEndingDate},
                success: function(data){
                    console.log(data);
                    $('.validationDisplay').html('');
                    $('.validationDisplay').html(data);
                }
            });
        }
        $(document).on('click','.validateBtn',function(){
            var weekEndingDate = $('#weekendingDate').val();
            if(weekEndingDate.length>0){
                validatePayrollInformation(weekEndingDate);
            }else {
                alert('Please select weekending Date');
            }
        });
        $(document).on('click','.previewBtn', function (){
            var weekEndingDate = $('#weekendingDate').val();
            var payDate = $('#payDate').val();
            var payrollName = $('#payrollName :selected').val();
            var candidateId = $('#candidateId').val();
            var jobCode = $('#jobCode').val();
            var profitCentre = $('#profitCentre').val();
            var clientCode = $('#clientCode').val();
            var action = 'PREVIEW';

            $.ajax({
                url:"genPayrollCalculation.php",
                type:"POST",
                dataType:"html",
                data:{
                    weekEndingDate:weekEndingDate,payDate:payDate,payrollName:payrollName,candidateId:candidateId,jobCode:jobCode,profitCentre:profitCentre,clientCode:clientCode,action:action
                },
                success: function(data){
                    $('.validationDisplay').html('');
                    $('.auditReportDisplay').html('');
                    $('.auditReportDisplay').html(data);
                }
            });
        });

        $(document).on('click','.generateBtn',function(){
            var weekEndingDate = $('#weekendingDate').val();
            var payDate = $('#payDate').val();
            var payrollName = $('#payrollName :selected').val();
            var candidateId = $('#candidateId').val();
            var jobCode = $('#jobCode').val();
            var profitCentre = $('#profitCentre').val();
            var clientCode = $('#clientCode').val();
            var action = 'GENERATE';
            if(weekEndingDate.length>0){
                generatePayrollCalculationReport(weekEndingDate, payDate, payrollName, candidateId, jobCode, profitCentre, clientCode,action);
            }else {
                alert('Please select weekending Date');
            }
        });
        $(document).on('click','.generatePDFBtn',function(){
            var filePathPDF = $('.filePath').attr('data-filePathPDF');
            console.log('FPDF'+filePathPDF);
            window.open(filePathPDF);
        });
        $(document).on('click','.generateEXCELBtn',function(){
            var filePathXLSX = $('.filePath').attr('data-filePathXLSX');
            console.log('FPXLSX'+filePathXLSX);
            window.open(filePathXLSX);
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>