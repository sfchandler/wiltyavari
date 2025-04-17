<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 7/09/2017
 * Time: 1:59 PM
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
        <h2>Manual Payroll Entry</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
                <div class="row">
                    <section class="col col-3">
                        <label for="payrollName" class="select">Select Payroll Name
                            <select name="payrollName" id="payrollName" class="select"></select>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="clientId" class="select">
                            <select name="clientId" id="clientId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="positionId" class="select">
                            <select name="positionId" id="positionId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label class="pull-left">JOBCODE:&nbsp;</label><label id="jobCodeLabel"></label>
                        <input type="hidden" id="jobCode" name="jobCode" value=""/>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="employeeName" class="input">
                            <input id="employeeName" name="employeeName" type="text" placeholder="Employee Name"/>
                        </label><input type="hidden" name="empSelected" id="empSelected"/>
                    </section>
                    <section class="col col-3">
                        <label for="weekendingDate" class="input">
                            <input type="text" name="weekendingDate" id="weekendingDate" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Week ending date" autocomplete="off"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <table border="1" cellpadding="2" cellspacing="2" class="payrollDataTable table table-striped table-bordered table-hover" width="90%">
                            <thead>
                            <th>PayrunId</th>
                            <th>Weekending Date</th>
                            <th>CandidateId</th>
                            <th>Category</th>
                            <th>Transaction Code</th>
                            <th>Units</th>
                            <th>Rate</th>
                            <th>Amount</th>
                            <th>Charge Rate</th>
                            <th>Charge Amount</th>
                            <th>Gross</th>
                            <th>Net Wages</th>
                            <th>PAYG Tax</th>
                            <th>Deduction</th>
                            <th>Super Annuation</th>
                            </thead>
                            <tbody id="mPayrollBody">
                            <tr>
                                <td><input type="text" name="payrunId" id="payrunId" value="3" size="4"/></td>
                                <td><input type="text" name="wkending" id="wkending" value="" size="8"/></td>
                                <td><input type="text" name="candidateId" id="candidateId" value="" size="8"/></td>
                                <td><select name="category" id="category"></select></td>
                                <td><input type="text" name="transCode" id="transCode" value="0" size="5"/></td>
                                <td><input type="text" name="units" id="units" value="0" size="5"/></td>
                                <td><input type="text" name="rate" id="rate" value="0.00" size="5"/></td>
                                <td><input type="text" name="amount" id="amount" value="0.00" size="8"/></td>
                                <td><input type="text" name="chargeRate" id="chargeRate" value="0.00" size="5"/></td>
                                <td><input type="text" name="chargeAmount" id="chargeAmount" value="0.00" size="8"/></td>
                                <td><input type="text" name="gross" id="gross" value="0.00" size="8"/></td>
                                <td><input type="text" name="net" id="net" value="0.00" size="8"/></td>
                                <td><input type="text" name="tax" id="tax" value="0.00" size="8"/></td>
                                <td><input type="text" name="deduction" id="deduction" value="0.00" size="8"/></td>
                                <td><input type="text" name="super" id="super" value="0.00" size="8"/></td>
                            </tr>
                            </tbody>
                        </table>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="addBtn">
                            <button name="addBtn" id="addBtn" class="addBtn btn-info btn-sm"><i class="fa fa-plus fa-x"></i>&nbsp;Add</button>
                        </label>
                    </section>
                </div>
            </fieldset>
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
        loadClients();
        $('#positionId').hide();
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                $('#candidateId').val(candidateId);
            }
        });
        function loadClients(){
            $.ajax({
                url :"loadClients.php",
                type:"POST",
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#clientId').html('');
                $('#clientId').html(data);
                if($('#clientId :selected').val() == 'All'){
                    $('#clientId option[value="All"]').text('Select Client');
                    $('#clientId :selected').val('Select Client');
                }
            });
        }
        function loadPositions(){
            $.ajax({
                url :"loadPositions.php",
                type:"POST",
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#positionId').html('');
                $('#positionId').html(data);
            });
        }
        $(document).on('change', '#clientId', function(){
            $('#positionId').show();
            $('#jobCodeLabel').html('');
            loadPositions();
        });

        $(document).on('change', '#positionId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobCodeLabel').html('');
                $('#jobCodeLabel').html(data);
                $('#jobCode').val(data);
            });
        });
        getPayrollNames();
        function getPayrollNames(){
            $.ajax({
                url: "getPayrollNames.php",
                type: "POST",
                dataType: "html",
                success: function (data) {
                    $('#payrollName').html('');
                    $('#payrollName').html(data);
                }
            });
        }
        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDate').val(picker.startDate.format('YYYY-MM-DD'));
            $('#wkending').val($('#weekendingDate').val());
        });


        $(document).on('click','.addBtn',function(){
            var weekEndingDate = $('#weekendingDate').val();
            var payrollName = $('#payrollName :selected').val();
            var candidateId = $('#candidateId').val();
            var jobCode = $('#jobCode').val();
            var clientId = $('#clientId').val();
            var positionId = $('#positionId').val();
            var wkending = $('#wkending').val();
            var category = $('#category :selected').val();
            var transCode = $('#transCode').val();
            var units = $('#units').val();
            var rate = $('#rate').val();
            var amount = $('#amount').val();
            var chargeRate = $('#chargeRate').val();
            var chargeAmount = $('#chargeAmount').val();
            var gross = $('#gross').val();
            var net = $('#net').val();
            var tax = $('#tax').val();
            var deduction = $('#deduction').val();
            var superAnnuation = $('#super').val();
            var payrunId = $('#payrunId').val();
            var action = 'add';
            //console.log('VALUES'+weekEndingDate+payrollName+candidateId+jobCode+clientId+positionId+wkending+category+action);
            if(weekEndingDate.length>0){
                $.ajax({
                    url: "manualPayrollFeed.php",
                    type: "POST",
                    data:{weekEndingDate:weekEndingDate,
                        payrollName:payrollName,
                        candidateId:candidateId,
                        jobCode:jobCode,
                        clientId:clientId,
                        positionId:positionId,
                        wkending:wkending,
                        category:category,
                        transCode:transCode,
                        units:units,
                        rate:rate,
                        amount:amount,
                        chargeRate:chargeRate,
                        chargeAmount:chargeAmount,
                        gross:gross,
                        net:net,
                        tax:tax,
                        deduction:deduction,
                        superAnnuation:superAnnuation,
                        payrunId:payrunId,
                        action:action},
                    dataType: "text",
                    success: function (data) {
                        $('.error').html('');
                        $('.error').html(data);
                        $('#transCode').val(0);
                        $('#units').val(0.00);
                        $('#rate').val(0.00);
                        $('#amount').val(0.00);
                        $('#chargeRate').val(0.00);
                        $('#chargeAmount').val(0.00);
                        $('#gross').val(0.00);
                        $('#net').val(0.00);
                        $('#tax').val(0.00);
                        $('#deduction').val(0.00);
                        $('#super').val(0.00);
                    }
                });
            }else {
                alert('Please select weekending Date');
            }
        });

        /*loadPayrunId();
        function loadPayrunId(){
            var action = 'payrunId';
            $.ajax({
                url: "manualPayrollFeed.php",
                type: "POST",
                data:{action:action},
                dataType: "text",
                success: function (data) {
                    $('#payrunId').val('');
                    $('#payrunId').val(data);
                }
            });
        }*/
        loadCategories();
        function loadCategories(){
            var action = 'category';
            $.ajax({
                url: "manualPayrollFeed.php",
                type: "POST",
                data:{action:action},
                dataType: "html",
                success: function (data) {
                    $('#category').html('');
                    $('#category').html(data);
                }
            });
        }


    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>