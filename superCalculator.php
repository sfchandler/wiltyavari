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
        .fixed_header{
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            padding: 2px 2px 2px 2px;
        }

        .fixed_header tbody{
            display:block;
            width: 100%;
            overflow: auto;
            height: 400px;
        }

        .fixed_header thead tr {
            display: block;
        }

        .fixed_header thead {
            background: #fff;
            color:black;
        }

        .fixed_header th, .fixed_header td {
            padding: 5px;
            width: 400px;
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
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Super Calculate for each month & Save</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset style="padding-left: 20px;">
                <div class="row">
                    <section class="col col-3">
                        <label for="superStart" class="select">Super Start Date
                            <input type="text" name="superStart" id="superStart" value="" class="form-control"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="superEnd" class="select">Super End Date
                            <input type="text" name="superEnd" id="superEnd" value="" class="form-control"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="limit" class="input">Threshold(e.g. 450)
                            <input type="text" name="limit" id="limit" value="450" class="form-control"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <label for="calculateBtn">
                        <button name="calculateBtn" id="calculateBtn" class="calculateBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-calculator"></i>&nbsp; Calculate Super</button>
                        &nbsp;
                        <button name="saveAllBtn" id="saveAllBtn" class="saveAllBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-save"></i>&nbsp; Save All</button>
                    </label>
                </div>
            </fieldset>
                    <table class="fixed_header table table-bordered">
                        <thead>
                          <tr>
                            <th>Candidate ID</th>
                            <th>Firstname</th>
                            <th>Lastname</th>
                            <th>Gross Total</th>
                            <th>Super Amount</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody id="dataDisplay">

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
        /* AJAX loading animation */
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /* -  end  -*/
        $('input[name="superStart"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="superStart"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#superStart').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="superEnd"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="superEnd"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#superEnd').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $(document).on('click','.calculateBtn', function () {
            var superStart = $('#superStart').val();
            var superEnd = $('#superEnd').val();
            var payrollName = $('#payrollName :selected').val();
            var companyId = $('#companyId :selected').val();
            var action = 'GET';
            var limit = $('#limit').val();
            calculateSuper(superStart,superEnd,payrollName,companyId,action,limit);
        });
        $(document).on('click','.saveAllBtn', function (){
            $('.saveBtn').trigger('click');
        });
        $(document).on('click','.saveBtn', function (){
            let wkStartDate = $(this).closest('td').attr('data-wkdate');
            let canid = $(this).closest('td').attr('data-canid');
            let superamt = $(this).closest('td').attr('data-superamt');
            let action = 'SAVE';
            $.ajax({
                type: "POST",
                url: "processSuperCalculate.php",
                data:{ wkStartDate:wkStartDate,canid:canid,superamt:superamt, action:action},
                dataType:"text",
                success: function(data){
                    console.log('response'+data);
                    $('.error').html('');
                    $('.error').html(data);
                }
            });

        });
        function calculateSuper(superStart,superEnd,payrollName,companyId,action,limit){
            $.ajax({
                url:"processSuperCalculate.php",
                type:"POST",
                dataType:"html",
                data:{superStart:superStart,superEnd:superEnd,payrollName:payrollName,companyId:companyId,action:action,limit:limit},
                success: function(data){
                    console.log(data);
                    $('#dataDisplay').html('');
                    $('#dataDisplay').html(data);
                }
            });
        }
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>
</html>