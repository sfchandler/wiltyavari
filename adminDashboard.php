<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'ADMIN')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <style>
        .ui-menu { width: 200px; }
        .ui-widget-header { padding: 0.2em; }
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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>User Management</h2>
        <div class="error"></div>
        <div class="row" style="padding-left: 15px">
            <form name="frmUser" id="frmUser" class="smart-form" method="post">
                <section class="col col-4">
                    <label for="userName" class="input">
                    <input class="pull-left" name="userName" id="userName" value="" placeholder="User Name"/>
                    </label>
                </section>
                <section class="col col-4">
                    <label for="password" class="input">
                    <input class="pull-left" name="password" id="password" type="password" value="" placeholder="User Password"/>
                    </label>
                </section>
                <section class="col col-4">
                    <label for="fullName" class="input">
                        <input class="pull-left" name="fullName" id="fullName" value="" placeholder="Name"/>
                    </label>
                </section>
                <section class="col col-4">
                    <label for="loginType" class="select">
                        <select name="loginType" id="loginType" class="select pull-left">
                            <option value="CONSULTANT">CONSULTANT</option>
                            <option value="ACCOUNTS">ACCOUNTS</option>
                            <option value="ALLOCATIONS">ALLOCATIONS</option>
                        </select>
                    </label>
                </section>
                <section class="col col-4">
                    <label for="email" class="input">
                        <input class="pull-left" name="email" id="email" value="" placeholder="User Email"/>
                    </label>
                </section>
                <section class="col col-4">
                    <label for="mobile" class="input">
                        <input class="pull-left" name="mobile" id="mobile" value="" placeholder="Mobile Number"/>
                    </label>
                </section>
                <section class="col col-4">
                        <button type="submit" class="addUserBtn btn btn-primary btn-sm" name="addUserBtn"><i class="glyphicon fa-lg fa fa-plus"></i>Add User</button>
                </section>
            </form>
        </div>
        <div class="filterPanel">
            <table class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                    <th>UserID</th>
                    <th>Username</th>
                    <th>User Type</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Mobile</th>
                    <th>Status</th>
                    <th>Session</th>
                    <th>Action</th>
                </thead>
                <tbody class="usrBody">
                </tbody>
            </table>
        </div>
        <div>

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
<script>
    $(document).ready(function(){
        getUsers();
        function getUsers() {
            $.ajax({
                url:"getUsers.php",
                method:"POST",
                dataType:"html",
                success:function(data)
                {
                    $('.usrBody').html();
                    $('.usrBody').html(data);
                }
            });
        }
        $(document).on('click','.DeActivate',function(){
            var $row = $(this).closest("tr");
            var usrName = $row.find('.btnRow').data('usr');
            var action  = 'DeActivate';
            $.ajax({
                url:"getUsers.php",
                method:"POST",
                data:{usrName:usrName,action:action},
                dataType:"html",
                success:function(data)
                {
                    getUsers();
                }
            });
        });
        $(document).on('click','.Activate',function(){
            var $row = $(this).closest("tr");
            var usrName = $row.find('.btnRow').data('usr');
            var action  = 'Activate';
            $.ajax({
                url:"getUsers.php",
                method:"POST",
                data:{usrName:usrName,action:action},
                dataType:"html",
                success:function(data)
                {
                    getUsers();
                }
            });
        });
        $(document).on('click','.delBtn',function(){
            var $row = $(this).closest("tr");
            var usrName = $row.find('.btnRow').data('usr');
            var action  = 'Delete';
            $.ajax({
                url:"getUsers.php",
                method:"POST",
                data:{usrName:usrName,action:action},
                dataType:"html",
                success:function(data)
                {
                    getUsers();
                }
            });
        });
        $(document).on('click','.killSessionBtn', function(){
            var $row = $(this).closest("tr");
            var username = $row.find('.killSessionBtn').data('usr');
            var user_id = $row.find('.killSessionBtn').data('usrid');
            $.ajax({
                url:"processUser.php",
                method:"POST",
                data:{username:username,user_id:user_id},
                dataType:"html",
                success:function(data)
                {

                }
            });
            $(this).text('Killed');
        });
        $(document).on('click','.addUserBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmUser = $("#frmUser").validate({
                errorClass	: errorClass,
                errorElement: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    userName: {
                        required: true
                    },
                    password:{
                      required:true
                    },
                    fullName: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    mobile:{
                        required:true
                    }
                },
                messages: {
                    userName:{
                        required: "Please enter UserName"
                    },
                    password:{
                        requred: "Please enter passsword"
                    },
                    fullName:{
                        required: "Please enter Full Name of User"
                    },
                    email:{
                        required: "Please enter email address"
                    },
                    mobile:{
                        required: "Please enter mobile number"
                    }
                },
                submitHandler: function (form) {
                    var userName = $('#userName').val();
                    var password = $('#password').val();
                    var fullName = $('#fullName').val();
                    var email = $('#email').val();
                    var loginType = $('#loginType').val();
                    var mobile = $('#mobile').val();
                    var action = 'AddUser';
                    $.ajax({
                        type: "POST",
                        url: "getUsers.php",
                        data: {userName : userName,password:password,fullName:fullName,email:email,loginType: loginType,mobile:mobile,action:action},
                        dataType: "text",
                        success: function (data) {
                            console.log('AA'+data);
                            if(data == 'MAILSENT'){
                                $('.error').html('User Added & Email Sent');
                                getUsers();
                            }else if(data == 'EXISTS'){
                                $('.error').html('User Already Exists');
                            }else if(data == 'FAILURE'){
                                $('.error').html('Email Sending Failed');
                            }else if(data == 'ERROR'){
                                $('.error').html('Error Adding User');
                            }
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