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
        <h2>Candidate Balance Report</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label for="clientId" class="select">
                        <select id="clientId" name="clientId" class="select">
                            <?php echo getClientListForDropDown($mysqli,$clientId); ?>
                        </select>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label class="select">Profit Centre:
                        <select name="profitCentre" id="profitCentre" class="select"></select></label>
                </section>
                <section class="col col-3">
                    <label for="state" class="select">Select statewise
                        <select id="state" name="state" class="select">
                            <option value="None" selected>None</option>
                            <option value="ACT">ACT</option>
                            <option value="NSW">NSW</option>
                            <option value="NT">NT</option>
                            <option value="QLD">QLD</option>
                            <option value="SA">SA</option>
                            <option value="TAS">TAS</option>
                            <option value="VIC">VIC</option>
                            <option value="WA">WA</option>
                        </select>
                    </label>
                </section>
            </div>
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
                <section class="col col-3">
                    <label for="generateBtn">
                        <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-search"></i>&nbsp; View Report</button>
                        <button name="generatePDFBtn" id="generatePDFBtn" class="generatePDFBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp;&nbsp;Generate Report PDF</button>
                    </label>
                </section>
            </div>
            </fieldset>
        </div>
        <div class="balanceReportDisplay">

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

        function generateCandidateBalanceReport(startDate,endDate,profitCentre,clientId,state){
            $.ajax({
                url:"genCandidateBalance.php",
                type:"POST",
                dataType:"html",
                data:{startDate:startDate,endDate:endDate,profitCentre:profitCentre,clientId:clientId,state:state},
                success: function(data){
                    console.log(data);
                    $('.balanceReportDisplay').html('');
                    $('.balanceReportDisplay').html(data);
                }
            });
        }
        $(document).on('click','.generateBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var profitCentre = $('#profitCentre').val();
            var clientId = $('#clientId').val();
            var state = $('#state :selected').val();
            if((startDate.length >0) && (endDate.length>0)){
                generateCandidateBalanceReport(startDate,endDate, profitCentre, clientId,state);
            }else {
                alert('Please select Date range');
            }
        });
        $(document).on('click','.generatePDFBtn',function(){
            var filePathPDF = $('.filePath').attr('data-filePathPDF');
            console.log('FPDF'+filePathPDF);
            window.open(filePathPDF);
        });

    });
</script>
</body>

</html>