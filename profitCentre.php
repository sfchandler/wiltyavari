<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 9/10/2017
 * Time: 12:22 PM
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
        <h2>Profit Centre Details</h2>
        <div class="error"></div>
        <form name="frmProfit" id="frmProfit" class="smart-form" method="post">
            <div class="prCentre">
                <div class="row">
                    <section class="col col-4">
                        <label for="centreName" class="input">Name:
                            <input type="hidden" name="centreId" id="centreId" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                            <input type="text" name="centreName" id="centreName" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                    <section class="col col-4">
                        <label for="ecentreName" class="select">Select Centre Name
                            <select name="ecentreName" id="ecentreName" class="select">
                            </select>
                        </label>
                    </section>
                    <section class="col col-4">
                        <label class="input">&nbsp;</label>
                        <button type="button" name="chkBtn" id="chkBtn" class="chkBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-search"></i>&nbsp; Check</button>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="clientId" class="select">
                            <select id="clientId" name="clientId" class="select">
                            </select>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="address1" class="input">Address 1:
                            <input type="text" name="address1" id="address1" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="address2" class="input">Address 2:
                            <input type="text" name="address2" id="address2" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="address3" class="input">Address 3:
                            <input type="text" name="address3" id="address3" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="state" class="select">State:
                            <select id="stateId" name="stateId" class="select">

                            </select>
                        </label>
                    </section>
                    <section class="col col-4">
                        <label for="phone" class="input">Phone:
                            <input type="text" name="phone" id="phone" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="manager" class="input">Manager:
                            <input type="text" name="manager" id="manager" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="taxCalc" class="input">Payroll Tax Calc:
                            <input type="text" name="taxCalc" id="taxCalc" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                    <section class="col col-4">
                        <label for="taxPercentage" class="input">Payroll Tax %:
                            <input type="text" name="taxPercentage" id="taxPercentage" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="remittanceAddress">Invoice Remittance Address:
                            <textarea id="remittanceAddress" name="remittanceAddress" rows="10" style="width: 100%" class="textarea"></textarea>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label for="saveBtn">
                            <button type="reset" name="resetBtn" id="resetBtn" class="resetBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-eraser"></i>&nbsp; Reset</button>
                            <button name="saveBtn" id="saveBtn" class="saveBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-save"></i>&nbsp; Save</button>
                        </label>
                    </section>
                </div>
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

<script type="text/javascript">
    $(document).ready(function(){
        loadProfitCentres();
        function loadProfitCentres(){
            var action = 'GET';
            $.ajax({
                type: "POST",
                url: "./getProfitCentre.php",
                dataType: "html",
                data:{action:action},
                success: function (data) {
                    $('#ecentreName').html('');
                    $('#ecentreName').html(data);
                }
            });
        }
        loadClients();
        function loadClients(){
            $.ajax({
                type: "POST",
                url: "./getClients.php",
                dataType: "html",
                success: function (data) {
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        $(document).on('change','#clientId',function () {
            var clientId = $('#clientId :selected').val();
            $.ajax({
                type: "POST",
                url: "./getStateByClient.php",
                data:{clientId:clientId},
                dataType: "html",
                success: function (data) {
                    $('#stateId').html('');
                    $('#stateId').html(data);
                }
            });
        });
        $(document).on('click','#chkBtn', function () {
            var centreName = $('#ecentreName :selected').val();
            $.ajax({
                type: "POST",
                url: "./getProfitCentre.php",
                data:{centreName:centreName},
                dataType: "html",
                success: function (data) {
                }
            }).done(function (data) {
                if(data.length>0){
                    $('.prCentre').html('');
                    $('.prCentre').html(data);
                    loadProfitCentres();
                }else{
                    $("#frmProfit")[0].reset();
                }
            });
        });
        $(document).on('click','.saveBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmProfit = $("#frmProfit").validate({
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
                    centreName: {
                        required: true
                    },
                    phone:{
                        required:true
                    }
                },
                messages: {
                    centreName:{
                        required: "Please enter Profit Centre Name"
                    },
                    phone:{
                        required: "Please enter Phone number"
                    }
                },
                submitHandler: function (form) {
                    var centreId = $('#centreId').val();
                    var centreName = $('#centreName').val();
                    var clientId = $('#clientId').val();
                    var address1 = $('#address1').val();
                    var address2 = $('#address2').val();
                    var address3 = $('#address3').val();
                    var stateId = $('#stateId').val();
                    var phone = $('#phone').val();
                    var manager = $('#manager').val();
                    var taxCalc = $('#taxCalc').val();
                    var taxPercentage = $('#taxPercentage').val();
                    var remittanceAddress = $('textarea#remittanceAddress').val();
                    $.ajax({
                        type: "POST",
                        url: "./saveProfitCentre.php",
                        data: {centreId:centreId,centreName:centreName,clientId:clientId,address1:address1,address2:address2,address3:address3,stateId:stateId,phone:phone,manager:manager,taxCalc:taxCalc,taxPercentage:taxPercentage,remittanceAddress:remittanceAddress},
                        dataType: "html",
                        success: function (data) {
                            console.log('Profit Centre '+data);
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