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
        <h2>Pay Slip Message</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset>
                <div>
                    <label for="payslipmsg" class="textarea">Enter PaySlip Message
                        <textarea type="textarea" name="payslipmsg" id="payslipmsg" class="textarea" rows="10" style="width: 100%"></textarea>
                    </label>
                </div>
            </fieldset>
            <div>
                <label for="saveBtn">
                    <button name="saveBtn" id="saveBtn" class="saveBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-sticky-note"></i>&nbsp; Save PaySlip Messsage</button>
                </label>
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
    $(document).ready(function(){
        $(document).on('click','.saveBtn', function () {
            var payslipmsg = $('textarea#payslipmsg').val();
            $.ajax({
                url:"savePaySlipMessage.php",
                type:"POST",
                dataType:"text",
                data:{payslipmsg:payslipmsg},
                success: function(data){
                    if(data=='updated') getPaySlipMessage();
                }
            });
        });
        getPaySlipMessage();
        function getPaySlipMessage(){
            var get = 1;
            $.ajax({
                url:"savePaySlipMessage.php",
                type:"POST",
                dataType:"html",
                data:{get:get},
                success: function(data){
                    $('#payslipmsg').html(data);
                }
            });
        }
    });
</script>
</body>

</html>