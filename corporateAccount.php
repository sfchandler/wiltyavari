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
        <h2>Corporate Bank Account</h2>
        <div class="error"></div>
            <form name="frmBnkAcc" id="frmBnkAcc" class="smart-form" method="post">
                <div class="row">
                    <section class="col col-4">
                        <label for="accountName" class="input">Name:
                            <input type="hidden" name="accId" id="accId" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                            <input type="text" name="accountName" id="accountName" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Account Name"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                    <label for="accountNumber" class="input">Account Number:
                        <input type="text" name="accountNumber" id="accountNumber" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Account Number"/>
                    </label>
                    </section>
                    <section class="col col-4">
                    <label for="bsb" class="input">BSB:
                        <input type="text" name="bsb" id="bsb" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="BSB"/>
                    </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="userName" class="input">User Name:
                            <input type="text" name="userName" id="userName" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="User Name"/>
                        </label>
                    </section>
                    <section class="col col-4">
                        <label for="userCode" class="input">User Code:
                            <input type="text" name="userCode" id="userCode" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="User Code"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                    <label for="tradeCode" class="input">Trade Code:
                        <input type="text" name="tradeCode" id="tradeCode" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Trade Code"/>
                    </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="generateBtn">
                            <button type="reset" name="resetBankAccBtn" id="resetBankAccBtn" class="resetBankAccBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-plus"></i>&nbsp; Reset Form</button>
                            <button name="saveBankAccBtn" id="saveBankAccBtn" class="saveBankAccBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-bank"></i>&nbsp; Update Account</button>
                        </label>
                    </section>
                </div>
            </form>
            <div>
                <table class="table table-bordered">
                    <thead><tr><th>Account Name</th><th>Account Number</th><th>BSB</th><th>User Name</th><th>User Code</th><th>Trade Code</th><th>Action</th></tr></thead>
                    <tbody class="bnkAccounts"></tbody>
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
        getCorporateBankAccounts();
        function getCorporateBankAccounts(){
            var action = 'GET';
            $.ajax({
                type:"GET",
                url: "./saveCorporateBankAccount.php",
                data:{action:action},
                dataType: 'html',
                success: function (data) {
                    $('.bnkAccounts').html('');
                    $('.bnkAccounts').html(data);
                }
            });
        }
        $(document).on('click','.editBtn',function () {

            var accId =  $(this).closest('td').attr('data-accId');
            var accountName =  $(this).closest('td').attr('data-accountName');
            var accountNumber =  $(this).closest('td').attr('data-accountNumber');
            var bsb =  $(this).closest('td').attr('data-bsb');
            var userName =  $(this).closest('td').attr('data-userName');
            var userCode =  $(this).closest('td').attr('data-userCode');
            var tradeCode =  $(this).closest('td').attr('data-tradeCode');
            $('#accId').val(accId);
            $('#accountName').val(accountName);
            $('#accountNumber').val(accountNumber);
            $('#bsb').val(bsb);
            $('#userName').val(userName);
            $('#userCode').val(userCode);
            $('#tradeCode').val(tradeCode);
        });
        $(document).on('click','.deleteBtn', function () {
            var action = 'DELETE';
            var accId = $(this).closest('td').attr('data-accId');
            $.ajax({
                type:"GET",
                url: "./saveCorporateBankAccount.php",
                data:{accId:accId, action:action},
                dataType: 'html',
                success: function (data) {
                    $('.bnkAccounts').html('');
                    $('.bnkAccounts').html(data);
                }
            });
        });
        $('#bsb').keyup(function() {
            var bsb = $(this).val().split("-").join(""); // remove hyphens
            if (bsb.length > 0) {
                bsb = bsb.match(new RegExp('.{1,3}', 'g')).join("-");
            }
            $(this).val(bsb);
        });
        $(document).on('click','.saveBankAccBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmBnkAcc = $("#frmBnkAcc").validate({
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
                    accountName: {
                        required: true
                    },
                    accountNumber: {
                        required: true
                    },
                    bsb: {
                        required: true
                    },
                    userName:{
                        required:true
                    },
                    userCode:{
                        required:true
                    },
                    tradeCode:{
                        required:true
                    }
                },
                messages: {
                    accountName:{
                        required: "Please enter corporate Bank Account Name"
                    },
                    accountNumber:{
                        required: "Please enter corporate Bank Account Number"
                    },
                    bsb:{
                        required: "Please enter corporate Bank Account BSB"
                    },
                    userName:{
                        required: "Please enter corporate Bank Account User Name"
                    },
                    userCode:{
                        required: "Please enter corporate Bank Account User Code"
                    },
                    tradeCode:{
                        required: "Please enter corporate Bank Account Trade Code"
                    }
                },
                submitHandler: function (form) {
                    var accountName = $('#accountName').val();
                    var accountNumber = $('#accountNumber').val();
                    var bsb = $('#bsb').val();
                    var userName = $('#userName').val();
                    var userCode = $('#userCode').val();
                    var tradeCode = $('#tradeCode').val();
                    var accId = $('#accId').val();
                    var action = 'ADD';
                    if(accId.length>0){
                        action = 'UPDATE';
                    }

                    $.ajax({
                        type: "POST",
                        url: "./saveCorporateBankAccount.php",
                        data: {accountName:accountName,accountNumber:accountNumber,bsb:bsb,userName:userName,userCode:userCode,tradeCode:tradeCode,accId:accId,action:action},
                        dataType: "html",
                        success: function (data) {
                            $('.bnkAccounts').html('');
                            $('.bnkAccounts').html(data);
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