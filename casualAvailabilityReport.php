<?php
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
        <h2><i class="fa fa-lg fa-fw fa-hospital-o"></i> Casual Shift Availability Report</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
                <div class="row">
                    <section class="col col-3">
                        <label for="startDate" class="input">
                            <label class="select">
                                <select class="select" name="clientId" id="clientId">
                                </select>
                            </label>
                        </label>
                    </section>
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
                    <section class="col col-3">
                        <label for="generateBtn">
                            <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file"></i> View/Download Report</button>
                        </label>
                    </section>
                </div>
            </fieldset>
        </div>
        <div class="timeclockRows"></div>
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
        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        getClients();
        function getClients(){
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        $(document).on('click','.generateBtn',function(){
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var clientId = $('#clientId :selected').val();
            if(startDate!='' && endDate != '') {
                $.ajax({
                    url: "generateAvailableCasualsReport.php",
                    type: "POST",
                    dataType: "text",
                    data: {startDate: startDate, endDate: endDate,clientId:clientId},
                    success: function (data) {
                            window.open(data);
                    },
                    complete: function(){
                        $body.removeClass("loading");
                    }
                });
            }else{
                $('.error').html('Please fill all the fields');
            }
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>