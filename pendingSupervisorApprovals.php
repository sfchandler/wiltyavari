<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if ($_SESSION['userSession'] !== 'Viran'){
    if($_SESSION['userType']!=='ACCOUNTS'){
        $msg = base64_encode("Access Denied");
        header("Location:login.php?error_msg=$msg");
    }
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
        <h2>ClockIn Report - Pending Supervisor Approval</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
                <div class="row">
                    <section class="col col-3">
                        <label for="clientId" class="select">Select Client</label>
                        <select name="clientId" id="clientId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        </select><i></i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="startWkDate" class="input">
                            <input type="text" name="startWkDate" id="startWkDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Start Week ending date"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="endWkDate" class="input">
                            <input type="text" name="endWkDate" id="endWkDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="End Week ending date"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-6">
                        <label for="generateBtn">
                            <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-info btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp;&nbsp;Generate Report</button>
                        </label>
                    </section>
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
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        $('input[name="startWkDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endWkDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startWkDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#startWkDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endWkDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#endWkDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        populateClients();
        function populateClients(){
            var dropSelect = 'N';
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                data:{dropSelect:dropSelect},
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        $(document).on('click','.generateBtn',function () {
            var startWkDate = $('#startWkDate').val();
            var endWkDate = $('#endWkDate').val();
            var clientId = $('#clientId :selected').val();
            $.ajax({
                url : "genPendingSupervisorApproval.php",
                type: "POST",
                data:{startWkDate:startWkDate,endWkDate:endWkDate,clientId:clientId},
                dataType: "text",
                success: function(data) {
                    if(data == 'NODATA'){
                        $('.error').html('No data found');
                    }else {
                        window.open(data);
                    }
                }
            })
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>