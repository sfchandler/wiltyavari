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
        <h2>Client Term Categories</h2>
        <div class="error"></div>
        <form name="frmTerms" id="frmTerms" class="smart-form" method="post">
            <div class="row">
                <section class="col col-4">
                    <label for="days" class="input">Days:
                        <input type="hidden" name="termId" id="termId" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        <input type="text" name="days" id="days" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Account Name"/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-4">
                    <label for="description" class="input">Description:
                        <input type="text" name="description" id="description" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Description"/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-4">
                    <label for="">
                        <button type="reset" name="resetTermsBtn" id="resetTermsBtn" class="resetTermsBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-plus"></i>&nbsp; Reset</button>
                        <button name="saveTermsBtn" id="saveTermsBtn" class="saveTermsBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-legal"></i>&nbsp; Add Terms</button>
                    </label>
                </section>
            </div>
        </form>
        <div>
            <table class="table table-bordered">
                <thead><tr><th>Day</th><th>Description</th><th>Action</th></tr></thead>
                <tbody class="termsList"></tbody>
            </table>
        </div>

    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>

<script type="text/javascript">
    $(document).ready(function(){
        listClientTerms();
        function listClientTerms(){
            var action = 'GET';
            $.ajax({
                type:"GET",
                url: "./saveClientTerms.php",
                data:{action:action},
                dataType: 'html',
                success: function (data) {
                    $('.termsList').html('');
                    $('.termsList').html(data);
                }
            });
        }

        $(document).on('click','.removeTermBtn', function () {
            var action = 'DELETE';
            var termId = $(this).closest('tr').attr('data-termId');
            $.ajax({
                type:"GET",
                url: "./saveClientTerms.php",
                data:{termId:termId, action:action},
                dataType: 'html',
                success: function (data) {
                    console.log('TERMID'+data);
                    $('.termsList').html('');
                    $('.termsList').html(data);
                }
            });
        });
        $(document).on('click','.saveTermsBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmTerms = $("#frmTerms").validate({
                errorClass	: errorClass,
                errorElement	: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    days: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                },
                messages: {
                    days:{
                        required: "Please enter corporate Bank Account Name"
                    },
                    description:{
                        required: "Please enter corporate Bank Account Number"
                    }
                },
                submitHandler: function (form) {
                    var days = $('#days').val();
                    var description = $('#description').val();
                    var action = 'ADD';
                    $.ajax({
                        type: "POST",
                        url: "./saveClientTerms.php",
                        data: {days:days,description:description,action:action},
                        dataType: "html",
                        success: function (data) {
                            $('.termsList').html('');
                            $('.termsList').html(data);
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
</body>

</html>