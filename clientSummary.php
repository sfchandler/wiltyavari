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
        <h2>Client Summary Report</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
                <div class="row">
                    <section class="col col-3">
                        <div class="row">
                            <section class="col col-6">
                                <label for="weekendingDateStart" class="input">
                                    <input type="text" name="weekendingDateStart" id="weekendingDateStart" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date start"/>
                                </label>
                            </section>
                            <section class="col col-6">
                                <label for="weekendingDateEnd" class="input">
                                    <input type="text" name="weekendingDateEnd" id="weekendingDateEnd" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date end"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label for="generateBtn">
                                    <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>&nbsp; View Report</button>
                                </label>
                            </section>
                        </div>
                    </section>
                    <section class="col col-3">
                        <div class="row">
                            <section class="col col-6">
                                <label for="lastYearStartDate" class="input">
                                    <input type="text" name="lastYearStartDate" id="lastYearStartDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Previous Year Week ending date start"/>
                                </label>
                            </section>
                            <section class="col col-6">
                                <label for="lastYearEndDate" class="input">
                                    <input type="text" name="lastYearEndDate" id="lastYearEndDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Previous Year Week ending date end"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-2">
                                <label for="genPrevYearReportBtn">
                                    <button name="genPrevYearReportBtn" id="genPrevYearReportBtn" class="genPrevYearReportBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-excel-o"></i>&nbsp; View Previous Year Report</button>
                                </label>
                            </section>
                        </div>
                    </section>
                    <section class="col col-3"></section>
                    <section class="col col-3"></section>
                </div>
            </fieldset>
        </div>
        <div class="reportDisplay">

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

        $('input[name="weekendingDateStart"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDateStart"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDateStart').val(picker.startDate.format('YYYY-MM-DD'));
        });

        $('input[name="weekendingDateEnd"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDateEnd"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDateEnd').val(picker.startDate.format('YYYY-MM-DD'));
        });

        $('input[name="lastYearStartDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="lastYearStartDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#lastYearStartDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="lastYearEndDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="lastYearEndDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#lastYearEndDate').val(picker.startDate.format('YYYY-MM-DD'));
        });

        function generateClientSummary(weekEndingDateStart,weekEndingDateEnd){
            let action = 'CURRENTYEAR';
            $.ajax({
                url:"genClientSummary.php",
                type:"POST",
                dataType:"text",
                data:{weekEndingDateStart:weekEndingDateStart,weekEndingDateEnd:weekEndingDateEnd,action:action},
                success: function(data){
                    window.open(data);
                }
            });
        }
        function generateClientSummaryLastYear(weekEndingDateStart,weekEndingDateEnd,lastYearStartDate,lastYearEndDate){
            let action = 'LASTYEAR';
            $.ajax({
                url:"genClientSummary.php",
                type:"POST",
                dataType:"text",
                data:{action:action,weekEndingDateStart:weekEndingDateStart,weekEndingDateEnd:weekEndingDateEnd,lastYearStartDate:lastYearStartDate,lastYearEndDate:lastYearEndDate},
                success: function(data){
                    console.log('.....'+data);
                    window.open(data);
                }
            });
        }
        $(document).on('click','.generateBtn',function(){
            var weekEndingDateStart = $('#weekendingDateStart').val();
            var weekEndingDateEnd = $('#weekendingDateEnd').val();
            /*var lastYearStartDate = $('#lastYearStartDate').val();
            var lastYearEndDate = $('#lastYearEndDate').val();*/
            if(weekEndingDateStart.length>0){
                generateClientSummary(weekEndingDateStart,weekEndingDateEnd);
            }else {
                alert('Please select weekending Date');
            }
        });
        $(document).on('click','.genPrevYearReportBtn', function (){
            var weekEndingDateStart = $('#weekendingDateStart').val();
            var weekEndingDateEnd = $('#weekendingDateEnd').val();
            var lastYearStartDate = $('#lastYearStartDate').val();
            var lastYearEndDate = $('#lastYearEndDate').val();
            if(lastYearStartDate.length>0){
                generateClientSummaryLastYear(weekEndingDateStart,weekEndingDateEnd,lastYearStartDate,lastYearEndDate);
            }else {
                alert('Please select weekending Date');
            }
        });

        $(document).on('click','.generatePDFBtn',function(){
            var filePathPDF = $('.filePath').attr('data-filePathPDF');
            //console.log('FPDF'+filePathPDF);
            window.open(filePathPDF);
        });
        $(document).on('click','.generateEXCELBtn',function(){
            var filePathXLSX = $('.filePath').attr('data-filePathXLSX');
            //console.log('FPXLSX'+filePathXLSX);
            window.open(filePathXLSX);
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>

</html>