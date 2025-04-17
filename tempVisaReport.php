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
<aside id="left-panel">
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <?php include "template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
		<i class="fa fa-arrow-circle-left hit"></i>
	</span>
</aside>
<div id="main" role="main">
    <div id="ribbon">
				<span class="ribbon-button-alignment">
				</span>
        <ol class="breadcrumb">
            <?php include "template/breadcrumblinks.php"; ?>
        </ol>
    </div>
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Visa Report</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label for="weekendingDate" class="input">
                        <input type="text" name="weekendingDate" id="weekendingDate" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date"/>
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
</div>
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<script type="text/javascript" src="js/daterangepicker/moment.js"></script>
<script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<script src="js/jqueryform/jquery.form.js"></script>
<script>
    $(document).ready(function(){
        $body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
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
        $(document).on('click','.generateBtn',function () {
            var weekendingDate = $('#weekendingDate').val();
            $.ajax({
                url :"genTempVisaReport.php",
                type:"POST",
                data:{weekendingDate:weekendingDate},
                dataType:"html",
                success: function(data) {
                   window.open(data);
                }
            })
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>