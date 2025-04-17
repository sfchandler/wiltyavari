<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (!isset($_SESSION['token'])) {
    $token = md5(uniqid(rand(), TRUE));
    $_SESSION['token'] = $token;
    $_SESSION['token_time'] = time();
} else {
    $token = $_SESSION['token'];
}
if(isset($_POST['username'])) {

    try {
        $user_name = $mysqli->real_escape_string($_POST['username']);
        $usrPassword = $mysqli->real_escape_string($_POST['password']);
        $empStatus = 'ACTIVE';
        $res = $mysqli->prepare("SELECT fullName, password FROM candidate WHERE email = ? AND empStatus = ?") or die($mysqli->error);
        $res->bind_param("ss", $user_name, $empStatus) or die($res->error);
        $res->execute();
        $res->bind_result($fullName,$password);

        while ($res->fetch()) {
            $hash_code = $password;
            $full_name = $fullName;
        }
        if (password_verify($usrPassword,$hash_code)) {
            session_start();
            $_SESSION['staff_username'] = $user_name;
            $_SESSION['staff_name'] = $full_name;
            $_SESSION['LAST_ACTIVITY'] = time();
            $id = session_id();

            $verification_code = substr(number_format(time() * rand(),0,'',''),0,6);
            updateStaffVerificationCode($mysqli,$user_name,$verification_code);
            try {
                $mailStatus = generateStaffVerificationNotification($user_name,'','',DOMAIN_NAME.' Login Verification',ADMIN_EMAIL,DOMAIN_NAME,'<br>Your login verification code is '.$verification_code,'','');
            }catch (Exception $e){
                $msg = base64_encode("Email Generation Error ".$e->getMessage());
                header("Location:login.php?error_msg=$msg");
                exit();
            }
            $usrName = base64_encode($user_name);
            $uid = base64_encode($id);
            $msg = base64_encode("2FA Authentication");
            header("Location:device_confirmations.php?user_name=$usrName&id=$uid&error_msg=$msg");
            exit();
            /*
            $usrName = base64_encode($user_name);
            $uid = base64_encode($id);
            $msg = base64_encode("Logged in successfully");
            header("Location:staff_dashboard.php?user_name=$usrName&id=$uid&error_msg=$msg");
            exit();*/
        } else {
            $msg = base64_encode("Invalid Username or Password");
            header("Location:login.php?error_msg=$msg");
            exit();
        }
    }catch (Exception $e){
        echo 'Error: '.$e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title><?php echo getCompanyName($mysqli).' '. DOMAIN_NAME; ?> </title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-skins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/smartadmin-rtl.min.css">
    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/demo.min.css">
    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="../img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../img/favicon/favicon.ico" type="image/x-icon">
    <style>
        body {
            margin: 0;
            padding: 0;
        }
        .login_container {
            max-width: 400px;
            margin: 100px auto;
            background-color: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.24);
        }
        .login_container input[type="text"],
        .login_container input[type="password"] {
            width: 100%;
            padding: 5px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }
        .login_container button {
            width: 100%;
            padding: 5px;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        @media (max-width: 480px) {
            .login_container {
                margin-top: 50px;
            }
        }
        .sign-panel{
            margin: 0 auto;
            padding: 50px 50px 50px 50px;
            border-radius: 2vh;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 20%;
        }
        .btn-info{
            background-color: #2f357f;
            border: 1px solid #2f357f;
            color: white;
        }
        .btn-info:hover{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info:active:focus{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info:active{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info.reverse{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info.reverse:active:focus{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info.reverse:hover{
            background-color: #2f357f;
            border: 1px solid #2f357f;
            color: white;
        }
    </style>
</head>
<body>
<div id="main" role="main" style="padding-top: 100px;">
    <div class="login_container" style="padding: 10px;">
    <div class="login_form">
        <div style="text-align: center;">
            <img src="../img/logo.png" width="280" height="35" alt="">
        </div>
        <h3 style="text-align: center">STAFF LOGIN</h3>
        <form name="login-form" id="login-form" method="post" action="login.php"
              class="smart-form client-form">
            <?php if (isset($_REQUEST['error_msg'])) { ?>
                <div style="text-align: center;"
                     class="ui-state-error"> <?php echo base64_decode($_REQUEST['error_msg']); ?></div><?php } ?>
            <fieldset>
                <section>
                    <label class="label">Username</label>
                    <label class="input"> <i class="icon-append fa fa-user"></i>
                        <input type="username" name="username" autocomplete="off">
                        <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i>
                            Please enter username</b></label>
                </section>
                <section>
                    <label class="label">Password</label>
                    <label class="input"> <i class="icon-append fa fa-lock"></i>
                        <input type="password" name="password" autocomplete="off">
                        <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter
                            your password</b> </label>
                </section>
                <section>
                    <input type="submit" name="loginBtn" id="loginBtn" class="btn btn-lg btn-info"
                           value="Sign In"/>
                </section>
            </fieldset>
        </form>
    </div>
    </div>
</div>

<!--================================================== -->

<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script> if (!window.jQuery) {
        document.write('<script src="../js/libs/jquery-2.1.1.min.js"><\/script>');
    } </script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script> if (!window.jQuery.ui) {
        document.write('<script src="../js/libs/jquery-ui-1.10.3.min.js"><\/script>');
    } </script>

<!-- IMPORTANT: APP CONFIG -->
<script src="../js/app.config.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="../js/bootstrap/bootstrap.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="../js/plugin/jquery-validate/jquery.validate.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="../js/plugin/masked-input/jquery.maskedinput.min.js"></script>

<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="../js/app.js"></script>

<script type="text/javascript">
    runAllForms();

    $(function () {
        // Validation
        $("#login-form").validate({
            // Rules for form validation
            rules: {
                username: {
                    required: true,
                    username: true
                },
                password: {
                    required: true,
                    minlength: 3,
                    maxlength: 20
                }
            },

            // Messages for form validation
            messages: {
                username: {
                    required: 'Please enter your username',
                    username: 'Please enter a VALID username'
                },
                password: {
                    required: 'Please enter your password'
                }
            },

            // Do not change code below
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>

</body>
</html>