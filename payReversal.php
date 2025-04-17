<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 14/09/2018
 * Time: 11:04 AM
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
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Pay Reversal</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <form id="frmPayReversal" class="smart-form" method="post">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label for="employeeName" class="input">Employee Name
                        <input id="employeeName" name="employeeName" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
                    </label>
                </section>
                <section class="col col-3">
                    <label for="empSelected" class="input">Employee ID
                        <input type="text" name="empSelected" id="empSelected" readonly/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="weekWorked" class="select">Select Pay Run
                        <select name="weekWorked" id="weekWorked">
                            <?php echo getPayRunDates($mysqli); ?>
                        </select>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="reversalDate" class="input">Reversal Date
                        <input type="text" name="reversalDate" id="reversalDate" value=""class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Reversal date"/>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-3">
                    <label for="payReverseBtn">
                        <button name="payReverseBtn" id="payReverseBtn" type="submit" class="payReverseBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-minus-circle"></i>&nbsp; Pay Reverse</button>
                    </label>
                </section>
            </div>
            </fieldset>
            </form>
        </div>
        <div class="balanceReportDisplay">

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
        $('input[name="reversalDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="reversalDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#reversalDate').val(picker.startDate.format('YYYY-MM-DD'));
        });

        $('.ui-autocomplete-input').css('width','40px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
            }
        });
        $(document).on('click','.payReverseBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmPayReversal = $("#frmPayReversal").validate({
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
                    employeeName: {
                        required: true
                    },
                    empSelected:{
                        required: true
                    },
                    reversalDate: {
                        required:true
                    }
                },
                messages: {
                    employeeName: {
                        required: "Please enter Employee Name"
                    },
                    empSelected:{
                        required: "Please enter and select employee Name correctly on first field"
                    },
                    reversalDate:{
                        required: "Please enter reversal Date"
                    }
                },
                submitHandler: function (form) {
                    var candidateId = $('#empSelected').val();
                    var reverseInfo = $('#weekWorked :selected').val();
                    var reversalDate = $('#reversalDate').val();
                    payReverse(candidateId,reverseInfo,reversalDate);
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        function payReverse(candidateId,reverseInfo,reversalDate){
            $.ajax({
                url:"processPayReversal.php",
                type:"POST",
                dataType:"text",
                data:{candidateId:candidateId,reverseInfo:reverseInfo,reversalDate:reversalDate},
                success: function(data){
                    console.log(data);
                    $('.error').html('');
                    $('.error').html(data);
                }
            });
        }
    });
</script>
</body>

</html>