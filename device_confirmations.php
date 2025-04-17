<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

?>
<!DOCTYPE html>
<html lang="en-us" id="extr-page">
<head>
    <meta charset="utf-8">
    <title><?php echo getCompanyName($mysqli).' '.DOMAIN_NAME; ?> </title>
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
    <link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">
    <!-- #FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <title><?php echo DOMAIN_NAME; ?> Two Factor Authentication Login</title>
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
            border-radius: 10px;
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
    <div class="login_container">
    <div class="login_form" style="padding: 20px;">
        <div class="form-content">
            <?php if (isset($_REQUEST['error_msg'])) { ?>
                <div style="text-align: center;"
                     class="ui-state-error"> <?php echo base64_decode($_REQUEST['error_msg']); ?></div><?php } ?>
        </div>
        <div class="form-input">
            <h2>Enter Verification Code</h2>
            <form name="reg" action="auth.php" method="POST">
                <div class="form-group">
                    <input type="text" name="v_code" id="v_code" autocomplete="off" value="" required>
                    <input type="hidden" name="user_id" value="<?php echo $_REQUEST['user_id'];?>"/>
                    <input type="hidden" name="user_name" value="<?php echo $_REQUEST['user_name'];?>"/>
                    <input type="hidden" name="typeLogin" value="<?php echo $_REQUEST['typeLogin'];?>"/>
                    <input type="hidden" name="accName" value="<?php echo $_REQUEST['accName']; ?>"/>
                    <input type="hidden" name="id" value="<?php echo $_REQUEST['id'];?>"/>
                </div>
                <div>
                    <button type="submit" class="btn btn-info"><i class="fa fa-lock"></i>&nbsp; Submit </button>
                </div>
            </form>
        </div>
    </div>
    </div>
</div>
</body>
</html>