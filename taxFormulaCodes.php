<?php
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
    <style>
        .ui-menu { width: 200px; }
        .ui-widget-header { padding: 0.2em; }
        .input{
            width: 200px;
            height: 30px;
            padding: 0px 0px 0px 0px;

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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Tax Formula Codes</h2>
        <div class="error"></div>
        <form name="frmTaxCode" id="frmTaxCode" class="smart-form" method="post">
            <div id="ratesHtml" class="row">
                <fieldset>
                    <div class="row">
                        <section class="col col-3">
                            <div class="pull-left">
                            <label for="taxCode" class="input">Tax Code:
                                <input type="text" name="taxCode" id="taxCode" value="" class="input"/>
                            </label>
                            </div>
                            <div class="pull-left"><label for="chkBtn"><div>&nbsp;</div>
                                    <input tabindex="1" type="button" name="chkBtn" id="chkBtn" class="chkBtn btn btn-primary btn-square btn-sm" value="Check Tax Scale"/>
                                </label>
                            </div>
                        </section>
                    </div>
                    <br/>
                    <div class="row">
                        <section class="col col-3">
                            <label for="taxCodeDesc" class="input" style="width: 350px;">TaxCode Description:
                                <input type="text" name="taxCodeDesc" id="taxCodeDesc" value="" class="input"/>
                            </label>
                        </section>
                    </div>
                </fieldset>
                <fieldset>
                    <legend>Weekly Scale</legend>

                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan1" class="input">Less Than
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate1" class="input">Rate
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj1" class="input">Adjustment
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <br/>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan2" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate2" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj2" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan3" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate3" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj3" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan4" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate4" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj4" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan5" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate5" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj5" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan6" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate6" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj6" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan7" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate7" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj7" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan8" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate8" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj8" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan9" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate9" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj9" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-3">
                                <label for="lessThan10" class="input">
                                    <input type="text" name="lessThan[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="rate10" class="input">
                                    <input type="text" name="rate[]" value="" class="input"/>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label for="adj10" class="input">
                                    <input type="text" name="adj[]" value="" class="input"/>
                                </label>
                            </section>
                        </div>
                </fieldset>
            </div>
            <div class="row">
                <!--<section class="col col-12">
                    <input type="submit" name="taxRateSaveBtn" id="taxRateSaveBtn" class="taxRateSaveBtn btn btn-primary btn-square btn-sm" value="Save TaxRates"/>
                    <input type="reset" name="resetBtn" id="resetBtn" class="resetBtn btn btn-primary btn-square btn-sm" value="Reset/Cancel"/>
                </section>-->
            </div>
        </form>

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
    runAllForms();
    $(document).ready(function(){

        $(document).keypress(function(e) {
            if(e.which == 13) {
                $('.chkBtn').click();
            }
        });
        $(document).on('click','.chkBtn',function(){
            var taxCode = $('#taxCode').val();
            $.ajax({
                url: "getTaxWeeklyScale.php",
                type: "POST",
                dataType: "html",
                data:{ taxCode:taxCode },
                success: function(data) {

                }
            }).done(function (data) {
                if(data.length>0){
                    $('#ratesHtml').html('');
                    $('#ratesHtml').html(data);
                }
            });
        });
        $(document).on('click','#resetBtn',function(){
           location.reload();
        });
        $(document).on('click','#taxRateSaveBtn', function(evt) {

            var errorClass = 'invalid';
            var errorElement = 'em';
            var frm = $("#frmTaxCode").validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    taxCode:{
                        required: true
                    },
                    taxCodeDesc:{
                        required: true
                    }
                },
                messages: {
                    taxCode:{
                        required: "Please enter tax code"
                    },
                    taxCodeDesc:{
                        required: "Please enter tax code description"
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        type: 'post',
                        url: 'updateTaxRate.php',
                        data: $('form').serialize(),
                        success: function (data) {
                            if (data == 'inserted' || data == 'updated') {
                                location.reload();
                            }
                        }
                    });
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

    });
</script>
</body>

</html>