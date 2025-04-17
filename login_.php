<?php
session_start();
require_once(__DIR__."/includes/db_conn.php");
require_once(__DIR__."/includes/functions.php");
include_once __DIR__.'/includes/GoogleAuthenticator-2.x/src/FixedBitNotation.php';
include_once __DIR__.'/includes/GoogleAuthenticator-2.x/src/GoogleAuthenticatorInterface.php';
include_once __DIR__.'/includes/GoogleAuthenticator-2.x/src/GoogleAuthenticator.php';
date_default_timezone_set('Australia/Melbourne');

$googleAuth = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
$accounts = getEmailAccounts($mysqli);
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
        $accountName = $_POST['emailAccount'];
        $status = 1;
        $res = $mysqli->prepare("SELECT user_id,username, password, email,type_login,verification_code FROM ch_users WHERE username=? AND status = ?") or die($mysqli->error);
        $res->bind_param("si", $user_name, $status);
        $res->execute();
        $res->bind_result($user_id,$username, $password, $email,$type_login, $verification_code);

        while ($res->fetch()) {
            $user_name = $username;
            $hash_code = $password;
            $email = $email;
            $type_login = $type_login;
            $verification_code = $verification_code;
        }
        $test_hash = extract_hash($hash_code, $usrPassword);
        if ($user_name == $username && $hash_code === $test_hash) {
            $secret = $googleAuth->generateSecret();
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['secret'] = $secret;
            $id = session_id();

            $verification_code = substr(number_format(time() * rand(),0,'',''),0,6);
            updateVerificationCode($mysqli,$user_id,$verification_code);
            try {
                $mailStatus = generateVerificationNotification($email,'','',DOMAIN_NAME.' Login Verification',ADMIN_EMAIL,DOMAIN_NAME,'<br>Your login verification code is '.$verification_code,'','');
            }catch (Exception $e){
                $msg = base64_encode("Email Generation Error ".$e->getMessage());
                header("Location:login.php?error_msg=$msg");
                exit();
            }
            $usrId = base64_encode($user_id);
            $usrName = base64_encode($user_name);
            $uid = base64_encode($id);
            $typeLogin = base64_encode($type_login);
            $accName = base64_encode($accountName);
            $msg = base64_encode("2FA Authentication");
            header("Location:device_confirmations.php?user_id=$usrId&user_name=$usrName&id=$uid&typeLogin=$typeLogin&accName=$accName&error_msg=$msg");
            exit();
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
    <title><?php echo getCompanyName($mysqli). DOMAIN_NAME; ?> </title>
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- #CSS Links -->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">

    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.min.css">

    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css">


    <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">

    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">

    <!-- #APP SCREEN / ICONS -->
    <!-- Specifying a Webpage Icon for Web Clip
         Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
    <link rel="apple-touch-icon" href="img/splash/sptouch-icon-iphone.png">
    <link rel="apple-touch-icon" sizes="76x76" href="img/splash/touch-icon-ipad.png">
    <link rel="apple-touch-icon" sizes="120x120" href="img/splash/touch-icon-iphone-retina.png">
    <link rel="apple-touch-icon" sizes="152x152" href="img/splash/touch-icon-ipad-retina.png">

    <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">

    <!-- Startup image for web apps -->
    <link rel="apple-touch-startup-image" href="img/splash/ipad-landscape.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
    <link rel="apple-touch-startup-image" href="img/splash/ipad-portrait.png"
          media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
    <link rel="apple-touch-startup-image" href="img/splash/iphone.png" media="screen and (max-device-width: 320px)">

</head>

<body class="animated fadeInDown">
<header id="header" style="background: white">
    <div id="logo-group">

    </div>
</header>

<div id="main" role="main">

    <!-- MAIN CONTENT -->
    <div id="content" class="container">

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4 hidden-xs hidden-sm">

            </div>
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
                <div class="well no-padding" style="border-color: black;background: white">
                    <div style="text-align: center; padding-top: 10px;">
                        <br>
                        <img src="img/lhr_logo.png" width="180" height="88" alt="">
                        <br>
                        <b><i>Powered by</i></b>
                        <br><br>
                        <img src="img/logo.png" width="220" height="50"/>
                    </div>
                    <form name="login-form" id="login-form" method="post" action="login.php"
                          class="smart-form client-form">
                        <br>
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
                                <label class="label">Select Email Account</label>
                                <label class="select">
                                    <i class="icon-append fa fa-email"></i>
                                    <select name="emailAccount" class="" readonly>
                                        <?php
                                        foreach ($accounts as $acc) {
                                            ?>
                                            <option value="<?php echo $acc['accountName']; ?>"><?php echo $acc['accountName']; ?></option>
                                        <?php } ?>
                                    </select>
                                </label>
                            </section>
                        </fieldset>
                        <footer>
                            <input type="submit" name="loginBtn" id="loginBtn" class="btn btn-primary"
                                   style="background-color: #1B2839; color: white" value="Sign In"/>
                        </footer>
                    </form>
                </div>
            </div>
        </div>


    </div>

</div>

<!--================================================== -->

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script src="js/plugin/pace/pace.min.js"></script>

<!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script> if (!window.jQuery) {
        document.write('<script src="js/libs/jquery-2.1.1.min.js"><\/script>');
    } </script>

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script> if (!window.jQuery.ui) {
        document.write('<script src="js/libs/jquery-ui-1.10.3.min.js"><\/script>');
    } </script>

<!-- IMPORTANT: APP CONFIG -->
<script src="js/app.config.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!-- BOOTSTRAP JS -->
<script src="js/bootstrap/bootstrap.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>

<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>

<!--[if IE 8]>

<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>

<![endif]-->

<!-- MAIN APP JS FILE -->
<script src="js/app.js"></script>

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