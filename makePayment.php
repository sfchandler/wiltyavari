<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 19/09/2017
 * Time: 12:43 PM
 */

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
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
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Make Payment</h2>
        <h3 style="color: red; font-weight: bold">* Please ensure all the employees Bank Account details are added and accurate before running make payments</h3>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-4">
                <label for="payrunId" class="input">PayrunID
                    <select name="payrunId" id="payrunId"></select>
                </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-4">
                <label for="bankAccount" class="select">Select Corporate Bank Account
                    <select type="text" name="bankAccount" id="bankAccount" class="select"></select>
                </label>
                </section>
            </div>
            <!--<div class="row">
                <section class="col col-4">
                <label for="fileLocation" class="input">File Location
                    <input type="text" name="fileLocation" id="fileLocation" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder=""/>
                </label>
                </section>
            </div>-->
            <!--<div class="row">
                <section class="col col-4">
                    <label for="payrunId" class="select">Reprint RunNo
                        <select name="rpayrunId" id="rpayrunId" class="select"></select>
                    </label>
                </section>
            </div>-->
            <div class="row">
                <section class="col col-4">
                <label for="generateBtn">
                    <button name="generatePaymentBtn" id="generatePaymentBtn" class="generatePaymentBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-bank"></i>&nbsp; Generate Payment</button>
                    <button name="generatePDFBtn" id="generatePDFBtn" class="generatePDFBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Generate PDF</button>
<!--                    <button name="downloadBtn" id="downloadBtn" class="downloadBtn btn btn-warning btn-square btn-sm"><i class="glyphicon glyphicon fa fa-files-o"></i>&nbsp; Download Bank File</button>
-->                    <a class="bnkFl btn btn-warning btn-square btn-sm" download="" target="_blank"><i class="glyphicon glyphicon fa fa-download"></i>Download</a>
                </label>
                </section>
            </div>
            </fieldset>
            <div class="makePayDisplay">

            </div>
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
        populateCorporateBankAccounts();
        function populateCorporateBankAccounts(){
            $.ajax({
                type: "POST",
                url: "loadCorporateBankAccounts.php",
                dataType: "html",
                success: function (data) {
                    $('#bankAccount').html('');
                    $('#bankAccount').html(data);
                }
            });
        }
        populatePayRunID();
        function populatePayRunID(){
            $.ajax({
                type: "POST",
                url: "loadPayRunID.php",
                dataType: "html",
                success: function (data) {
                    $('#payrunId').html('');
                    $('#payrunId').html(data);
                }
            });
        }

        $(document).on('click','.generatePaymentBtn', function () {

            var payrunId = $('#payrunId :selected').val();
            var bankAccount = $('#bankAccount :selected').val();
            var bankfilePath;
            $.ajax({
                type: "POST",
                url: "generateMakePaymentsAuditReport.php",
                dataType: "html",
                data:{payrunId:payrunId,bankAccount:bankAccount},
                success: function (data) {
                    console.log('response '+data);
                    $('.makePayDisplay').html('');
                    $('.makePayDisplay').html(data);
                    bankfilePath = $('.filePath').attr('data-bankFile');
                    $('.bnkFl').attr('href','');
                    $('.bnkFl').attr('downolad','');
                    $('.bnkFl').attr('href',bankfilePath);
                    $('.bnkFl').attr('downolad',bankfilePath);

                }
            });
        });
        $(document).on('click','.generatePDFBtn',function(){
            var filePathPDF = $('.filePath').attr('data-filePathPDF');
            window.open(filePathPDF);
        });
        /*$(document).on('click','.downloadBtn',function(){
            var bankfilePath = $('.filePath').attr('data-bankFile');
            window.open(bankfilePath,'_blank');
        });*/
    });
</script>
</body>

</html>