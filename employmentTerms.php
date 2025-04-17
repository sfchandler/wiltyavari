<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if($_SESSION['userType']==''){
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consultantEmail = $_POST['conEmail'];
$employeeEmail = $_POST['casualEmail'];
if(!empty($consultantEmail)&& !empty($employeeEmail)){
    $mailBody = 'Hi, Please fill and sign the Employment Terms Agreement online and submit by clicking the following link. <a href="'.DOMAIN_URL.'/empTerms.php?conEmail='.base64_encode($consultantEmail).'">Sign Your Terms agreement here</a>';
    $subject = 'Chandler Personnel Employment Terms Agreement';
    if(generateMailNotification($subject,$consultantEmail,$employeeEmail,$mailBody)){
        $errorMsg = 'Email Generated Successfully';
    }else{
        $errorMsg = 'Error in email generation';
    }
}else{
    $errorMsg = 'Please enter consultant and employee emails';
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
    <div id="content">
        <h3>Send Employment Terms Agreement to Casuals</h3>
        <span class="error"><?php if(!empty($errorMsg)){ echo $errorMsg;} ?></span>
        <form action="" method="post" name="frmTerms" id="frmTerms">
            <div class="row">
                <section class="col col-sm-3">
                    <label for="conEmail">Consultant Email:</label>
                    <input type="text" class="form-control" name="conEmail" id="conEmail" value="<?php echo getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession'])); ?>"/>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-3">
                    <label for="">Casual's Email:</label>
                    <input class="form-control" name="casualEmail" id="casualEmail" type="text" value=""/>
                </section>
            </div>
            <div class="row">
                <section class="col col-sm-3">
                    <br>
                    <button name="casualEmailBtn" id="casualEmailBtn" type="submit" class="casualEmailBtn btn btn-info"><i class="fa fa-paper-plane"></i> Send</button>
                </section>
            </div>
        </form>
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
    $(function(){
        /*$(document).on('click','.casualEmailBtn', function (evt) {
            var frmTerms = $('#frmTerms').validate({
                rules: {
                    conEmail: {
                        required: true
                    },
                    casualEmail: {
                        required: true
                    }
                },
                messages:{
                    conEmail: {
                        required: "Required input"
                    },
                    casualEmail:{
                        required: "Required input"
                    }
                },
                submitHandler: function(form) {

                }
            });
        });*/
    });
</script>
</body>
</html>