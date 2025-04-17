<?php

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
    <div id="content" class="container-body" style="margin-bottom: 50px;height: 100%">
        <h2>Workcover Industry Classifications</h2>
        <div class="error"></div>
            <form name="frmWIC" id="frmWIC" class="smart-form">
                <div class="row">
                    <section class="col col-lg-12">
                        <label for="year">Year:
                            <input type="text" name="year" id="year" size="10" class="form-control"/>
                        </label>
                    </section>
                    <section class="col col-lg-12">
                        <label for="wicCode">Code:
                            <input type="text" name="wicCode" id="wicCode" size="10" class="form-control"/>
                        </label>
                    </section>
                    <section class="col col-lg-12">
                        <label for="classification">Classification:
                            <input type="text" name="classification" id="classification" size="40" class="form-control"/>
                        </label>
                    </section>
                    <section class="col col-lg-12">
                        <label for="rate">Rate:
                            <input type="text" name="rate" id="rate" size="10" class="form-control"/>
                        </label>
                        <button name="addBtn" id="addBtn" class="addBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-plus"></i>&nbsp; Add</button>
                    </section>
                </div>
            </form>
            <div style="height: 400px; overflow-y: scroll">
                <table id="tblWicRate" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>CODE</th>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>CLASSIFICATION</th>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>RATE</th>
                            <th><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>YEAR</th>
                        </tr>
                    </thead>
                    <tbody class="tblWicRateBody" style="height: 300px; overflow-y: scroll">
                    </tbody>
                </table>
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
        $('input[name="year"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="year"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#year').val(picker.startDate.format('YYYY-MM-DD'));
        });
        loadClassifications();
        function loadClassifications(){
            let action = 'LOAD';
            $.ajax({
                url:"updateWIC.php",
                type:"POST",
                dataType:"html",
                data:{action:action},
                success:function(data){
                    $('.tblWicRateBody').html('');
                    $('.tblWicRateBody').html(data);
                }
            });
        }
        $(document).on('click','#addBtn',function(){
            let errorClass = 'invalid';
            let errorElement = 'em';
            let frmWIC = $("#frmWIC").validate({
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
                    year: {
                        required:true
                    },
                    wicCode: {
                        required:true
                    },
                    classification:{
                        required:true
                    },
                    rate:{
                        required:true
                    }
                },
                messages: {
                    year: {
                        required: "Please enter rate year"
                    },
                    wicCode: {
                        required: "Please enter WIC code"
                    },
                    classification: {
                        required: "Please enter classification name"
                    },
                    rate:{
                        required: "Please enter rate"
                    }

                },
                submitHandler: function (form) {
                    let year = $('#year').val();
                    let wicCode = $('#wicCode').val();
                    let classification = $('#classification').val();
                    let rate = $('#rate').val();
                    let action = 'ADD';
                    $.ajax({
                        url:"updateWIC.php",
                        type:"POST",
                        dataType:"text",
                        data:{year:year,wicCode:wicCode,classification:classification,rate:rate,action:action},
                        success:function(data){
                            console.log('.....'+data);
                            if(data == 'Added'){
                                loadClassifications();
                            }
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','.removeBtn', function(){
            let action = 'DELETE';
            let wic_id = $(this).closest('tr').attr('id');
            $.ajax({
                url:"updateWIC.php",
                type:"POST",
                dataType:"text",
                data:{action:action,wic_id:wic_id},
                success:function(data){
                    loadClassifications();
                }
            });
        });
    });
</script>
</body>

</html>