<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '') {
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
    <div id="content" class="container-fluid" style="margin-bottom: 50px; background-color: white">
        <div style="width: 50%; padding-left: 20px;">
            <h2>Rate Card view</h2>
            <div class="error"></div>
            <div class="row">
                <section class="col-sm-6">
                    <select name="clientId" id="clientId" class="form-control">
                    </select>
                </section>
                <section class="col-sm-4"></section>
                <section class="col-sm-2"></section>
            </div>
            <br>
            <div class="row">
                <section class="col-sm-6">
                    <select name="deptId" id="deptId" class="form-control">
                    </select>
                </section>
                <section class="col-sm-4"></section>
                <section class="col-sm-2"></section>
            </div>
            <br>
            <div class="row">
                <section class="col-sm-6">
                    <select name="positionId" id="positionId" class="form-control">
                    </select>
                </section>
                <section class="col-sm-4"></section>
                <section class="col-sm-2"></section>
            </div>
            <br>
            <div class="row">
                <section class="col-sm-6">
                    <label for="">JobCode:</label> <span id="jbCode"></span>
                    <input type="hidden" name="jobCode" id="jobCode" value="">
                </section>
                <section class="col-sm-4"></section>
                <section class="col-sm-2"></section>
            </div>
            <br>
            <div class="row">
                <section class="col-sm-2">
                    <button name="viewBtn" id="viewBtn" class="btn btn-info form-control"><i class="fa fa-eye"></i>View</button>
                    <section class="col-sm-4"></section>
                    <section class="col-sm-6"></section>
                </section>
            </div>
        </div>
        <br>
        <div style="width: 60%; padding-left: 20px">
            <div class="row">
                <section class="col col-6">
                    <div class="rateDisplay">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Pay Category</th>
                                <th>Pay Rate</th>
                            </tr>
                            </thead>
                            <tbody id="rateCardView">
                            </tbody>
                        </table>
                    </div>
                </section>
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
    $(document).ready(function () {
        /* AJAX loading animation */
        /*$body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });*/
        /* -  end  -*/
        loadClients();

        function loadClients() {
            var action = 'scheduling';
            $.ajax({
                url: "getClients.php",
                type: "POST",
                dataType: "html",
                data: {action: action},
                success: function (data) {
                }
            }).done(function (data) {
                $('#clientId').html('');
                $('#clientId').html(data);
            });
        }

        function getJobCode() {
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var deptId = $('#deptId :selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data: {clientId: clientId, positionId: positionId,deptId:deptId},
                success: function (data) {
                }
            }).done(function (data) {
                $('#jbCode').html('');
                $('#jbCode').html(data);
                $('#jobCode').val('');
                $('#jobCode').val(data);
            });
        }
        function loadDepartments(){
            var clientid = $('#clientId :selected').val();
            var action = 'DEPARTMENTSFORJOBCODE';
            $.ajax({
                url :"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid,action:action},
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#deptId').html('');
                $('#deptId').html(data);
            });
        }
        $(document).on('click', '#clientId', function () {
            var clientId = $('#clientId :selected').val();
            var action = 'scheduling';
            $.ajax({
                url: "getClientPositionsList.php",
                type: "POST",
                dataType: "html",
                data: {action: action, clientId: clientId},
                success: function (data) {
                }
            }).done(function (data) {
                $('#positionId').html('');
                $('#positionId').html(data);
                getJobCode();
                loadDepartments();
            });
        });
        $(document).on('change', '#positionId', function () {
            getJobCode();
        });
        $(document).on('click', '#positionId', function () {
            getJobCode();
        });
        $(document).on('click', '#viewBtn', function () {
            var clientId = $('#clientId :selected').val();
            var positionId = $('#positionId :selected').val();
            var jobCode = $('#jobCode').val();
            var action = 'VIEW';
            $.ajax({
                url: "generateRateCardTable.php",
                type: "POST",
                dataType: "html",
                data: {action: action, clientId: clientId, positionId: positionId, jobCode: jobCode},
                success: function (data) {
                }
            }).done(function (data) {
                $('#rateCardView').html('');
                $('#rateCardView').html(data);
            });
        });
    });
</script>
<!--<div class="modal"></div>-->

</body>

</html>