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
    <style>
        .ui-menu {
            width: 200px;
        }

        .ui-widget-header {
            padding: 0.2em;
        }
    </style>
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
    <section id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Change Password</h2>
        <div class="error"></div>
        <div class="row">
            <section class="col col-lg-3">
                <form name="frmChgPass" id="frmChgPass" class="smart-form">
                    <div class="row">
                        <section class="col col-md-4">
                            <label for="exPassword" class="select">Current Password:
                                <input type="password" name="exPassword" id="exPassword" class="input"/>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-md-4">
                            <label for="newPassword" class="select">New Password:
                                <input type="password" name="newPassword" id="newPassword" class="input"/>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-md-4">
                            <label for="confPassword" class="select">Confirm Password:
                                <input type="password" name="confPassword" id="confPassword" class="input"/>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-md-4">
                            <label for="changePassBtn">
                                <button name="changePassBtn" id="changePassBtn"
                                        class="changePassBtn btn btn-primary btn-square btn-sm"><i
                                        class="glyphicon glyphicon-lock"></i>&nbsp;Change Password
                                </button>
                            </label>
                        </section>
                    </div>
                </form>
            </section>
            <section class="col col-lg-8">
                <h5>Guideline for Creating a Strong Password</h5>
                <div style="text-align: justify">
                    <p>
                        <ul>
                            <li>
                            Never use personal information such as your name, birthday, user name, or email address. This type of information is often publicly available, which makes it easier for someone to guess your password.
                            </li>
                            <li>
                                Use a longer password. Your password should be at least 12 characters long, although for extra security it should be even longer.
                            </li>
                            <li>
                                Include numbers, symbols/special characters and both uppercase and lowercase letters.
                            </li>
                            <li>Avoid using words that can be found in the dictionary. For example, swimming1 would be a weak password.</li>
                            <li>Random passwords are the strongest. e.g. cW3#fE7#eR5@pR9</li>
                        </ul>
                    </p>
                </div>
            </section>
        </div>
    </section>
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
<script>
    $(document).ready(function () {
        $('#frmChgPass').on('submit', function (event) {
            event.preventDefault();
            if ($('#exPassword').val() != '' && $('#newPassword').val() != '' && $('#confPassword').val() != '') {

                var form_data = $(this).serialize();
                /*var exPassword = $('#exPassword').val();
                var newPassword = $('#newPassword').val();
                var confPassword = $('#confPassword').val();*/
                $.ajax({
                    url: "changePass.php",
                    method: "POST",
                    data: form_data,
                    dataType: "text",
                    success: function (data) {
                        if (data == 'updated') {
                            $('.error').html('');
                            $('.error').html('Password Changed!');
                        } else {
                            $('.error').html('');
                            $('.error').html(data);
                        }
                    }
                });
            } else {
                $('.error').html('');
                $('.error').html('All Fields are Required!');
                //alert("All Fields are Required");
            }
        });
    });
</script>
</body>

</html>