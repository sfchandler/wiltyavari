<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 23/11/2018
 * Time: 10:13 AM
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
        <h2><img src="img/fingerprint.png" width="100" height="80" border="0"/>Single Touch CSV - Regular</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
                <div class="row">
                    <section class="col col-3">
                        <label for="payrollName" class="select">Select Payroll Name
                            <select name="payrollName" id="payrollName"></select>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="payrollName" class="select">Select Company
                            <select name="companyId" id="companyId">
                                <option value="">Select Company</option>
                                <?php echo getCompanyDropdown($mysqli); ?>
                            </select>
                        </label>
                    </section>
                </div>
                <div class="compLogo row"></div>
                <div class="row">
                    <section class="col col-3">
                        <label for="financialYearStart" class="select">Financial Year Start Date
                            <input type="text" name="financialYearStart" id="financialYearStart" value="" class="form-control"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="financialYearEnd" class="select">Financial Year End Date
                            <input type="text" name="financialYearEnd" id="financialYearEnd" value="" class="form-control"/>
                        </label>
                    </section>
                </div>
            </fieldset>
            <div>
                <label for="generateCSVBtn">
                    <button name="generateCSVBtn" id="generateCSVBtn" class="generateCSVBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-bank"></i>&nbsp; Generate CSV</button>
                </label>
            </div>
            <div id="dataDisplay"></div>
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
        $('input[name="financialYearStart"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="financialYearStart"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#financialYearStart').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="financialYearEnd"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="financialYearEnd"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#financialYearEnd').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $(document).on('change','#companyId',function(){
            var action = 'LOGO';
            var companyId = $('#companyId :selected').val();
            $.ajax({
                type: "POST",
                url: "cmpInfoProcess.php",
                data:{action:action,companyId:companyId},
                dataType: "html",
                success: function (data) {
                    $('.compLogo').html('');
                    $('.compLogo').html(data);
                }
            });
        });
        $(document).on('click','.generateCSVBtn', function () {
            var financialYearStart = $('#financialYearStart').val();
            var financialYearEnd = $('#financialYearEnd').val();
            var payrollName = $('#payrollName :selected').val();
            var companyId = $('#companyId :selected').val();
            generateCSV(financialYearStart,financialYearEnd,payrollName,companyId);
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
        function generateCSV(financialYearStart,financialYearEnd,payrollName,companyId){
            $.ajax({
                url:"singletouchRegular.php",
                type:"POST",
                dataType:"text",
                data:{financialYearStart:financialYearStart,financialYearEnd:financialYearEnd,payrollName:payrollName,companyId:companyId},
                success: function(data){
                    console.log(data);
                    window.open(data);
                }
            });
        }
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>
</html>