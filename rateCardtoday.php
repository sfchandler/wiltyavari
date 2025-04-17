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
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px; width: 100%">
        <h2>Rate Card</h2>
        <div class="msg_display"></div>
        <div id="left" style="float:left; width: 35%;">
                <form name="frmRateCard" id="frmRateCard" class="smart-form" method="post">
                <div class="row">
                    <section class="col col-5">
                        <label for="clientId" class="select">CLIENT:</label>
                        <select name="clientId" id="clientId"  class="form-control" style=" cursor: pointer; width: 100%">
                        </select><i></i>
                    </section>
                    <div id="archive_display">
                        <section class="col col-3">
                                <h4>Archive records</h4>
                                <input type="text" name="snapYear" id="snapYear" value="" size="10" class="form-control" style="cursor: pointer;" placeholder="Only Select the Year"/>
                        </section>
                        <section class="col col-4">
                            <br>
                            <button type="button" id="snapshotBtn" class="btn btn-sm btn-info">Archive For Year</button>
                            &nbsp;
                            <button type="button" id="viewSnapshotBtn" class="btn btn-sm btn-info">View Archived</button>
                        </section>
                    </div>
                </div>
                <div class="row">
                    <section class="col col-5">
                        <label for="positionid" class="select">POSITION:</label>
                        <select name="positionid" id="positionid"  class="form-control" style=" cursor: pointer; width: 100%">
                        </select><i></i>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-10">
                        <label>AWARD:&nbsp;</label><b><label id="awardLabel" style="font-weight: bold"></label></b>
                        <input type="hidden" id="award" name="award" value=""/>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label>JOB CODE:&nbsp;</label><label id="jobcodeLabel" style="font-weight: bold"></label>
                        <input type="hidden" id="jobcode" name="jobcode" value=""/>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label>PayrollTax:&nbsp;</label><b><span id="payrollTaxLabel"></span>%</b>
                        <input type="hidden" id="payrollTax" name="payrollTax" value=""/>
                    </section>
                    <section class="col col-2">
                        <label>MHWS:&nbsp;</label><br><b><span id="mhwsLabel"></span>%</b>
                        <input type="hidden" id="mhws" name="mhws" value=""/>
                    </section>
                    <section class="col col-3">
                        <label>Select Margin type:&nbsp;</label><label id="marginSelection" style="font-weight: bold"></label>
                        <select name="margin_select" id="margin_select" class="form-control">
                            <option value="value" selected>Value</option>
                            <option value="percentage">Percentage</option>
                        </select>
                    </section>
                    <section class="col col-2">
                        <label>Margin:&nbsp;</label><label id="marginValueLabel" style="font-weight: bold"></label>
                        <input type="text" id="margin" name="margin" value="" size="8" class="form-control"/>
                    </section>
                    <section class="col col-2">
                        <br>
                        <button id="calculateBtn" class="btn btn-sm btn-info">Calculate</button>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-2">
                        <label>Workcover:&nbsp;</label><b><span id="workcoverLabel"></span>%</b>
                        <input type="hidden" id="workcover" name="workcover" value=""/>
                    </section>
                    <section class="col col-2">
                        <label>Super Percentage:&nbsp;</label><b><span id="super_percentageLabel"></span>%</b>
                        <input type="hidden" id="super_percentage" name="super_percentage" value=""/>
                    </section>
                    <section class="col col-3">
                        <label>Hourly rate:&nbsp;</label><label id="hourly_rateLabel" style="font-weight: bold"></label>
                        <input type="text" id="hourly_rate" name="hourly_rate" class="form-control" value=""/>
                    </section>
                    <section class="col col-2">
                        <label>Increment:&nbsp;</label>
                        <label class="input">
                            <i class="icon-append fa fa-percent"></i>
                            <input type="text" id="increment_percentage" name="increment_percentage" value="" size="10"/>
                        </label>
                    </section>
                </div>
                <div id="rateCardDisplay" style="padding-right: 5px"></div>
                <div class="rateCardSnapView" style="display: none"></div>
                </form>
            </div>
        <div id="left" style="float: left;width: 33%">
            <form name="frmRateCard" id="frmRateCard" class="smart-form" method="post">
            <div class="row">
                <section class="col col-12">
                    <div id="rateCardDiv">
                        <h4>Current/Active records</h4>
                        <table id="rateCardTable" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                                <tr>
                                    <th>Pay Category <button id="copyRatesBtn" class="btn btn-sm btn-info">Copy Rates</button></th>
                                    <th>Pay Rate</th>
                                    <th>New Pay Rate</th>
                                    <th>Charge Rate</th>
                                    <th>New Charge Rate</th>
                                </tr>
                            </thead>
                            <tbody class="rateCardBody">
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row">
                <section class="col col-4">
                    <button type="submit" name="saveRateCard" id="saveRateCard" class="saveRateCard btn btn-success btn-sm"><i class="glyphicon glyphicon-file fa fa-save"></i>&nbsp; Save/Update</button>
                </section>
            </div>
            </form>
        </div>
        <div id="right" style="float: left;width: 30%; padding-left: 2px;">
            <form name="frmNFRateCard" id="frmNFRateCard" class="smart-form" method="post">
            <div class="row">
                <section class="col col-12">
                    <div id="rateCardNFDiv">
                        <h4>New Financial Year Rates </h4>
                        <table class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th>Pay Category <button id="copyNFRatesBtn" class="btn btn-sm btn-info">Copy Rates</button></th>
                                <th><i class="fa fa-fw fa-dollar txt-color-blue hidden-md hidden-sm hidden-xs"></i> Pay Rate</th>
                                <th><i class="fa fa-fw fa-dollar txt-color-blue hidden-md hidden-sm hidden-xs"></i> Charge Rate</th>
                            </tr>
                            </thead>
                            <tbody class="rateCardNFBody">
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
            <div class="row">
                <section class="col col-4">
                    <button type="submit" name="saveNFRateCard" id="saveNFRateCard" class="saveNFRateCard btn btn-success btn-sm"><i class="glyphicon glyphicon-file fa fa-save"></i>&nbsp; Save/Update</button>
                </section>
            </div>
            </form>
        </div>
        <div style="clear: both"></div>
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
    $(function(){
        loadClients();
        $('#positionid').hide();
        $('#saveRateCard').hide();
        $('#saveNFRateCard').hide();
        $('#rateCardDiv').hide();
        $('#rateCardNFDiv').hide();
        function loadClients(){
            let action = 'SINGLESELECT';
            $.ajax({
                url :"loadClients.php",
                type:"POST",
                data:{action:action},
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#clientId').html('');
                $('#clientId').html(data);
            });
        }
        function loadPositions(){
            var action = 'CLIENTPOSITION';
            var clientId = $('#clientId :selected').val();
            $.ajax({
                url :"loadPositions.php",
                type:"POST",
                dataType:"html",
                data:{action:action,clientId:clientId},
                success: function(data) {
                }
            }).done(function(data){
                $('#positionid').html('');
                $('#positionid').html(data);
            });
        }
        $('input[name="snapYear"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: true,
            autoApply: false,

        });
        $('input[name="snapYear"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY'));
            $('#snapYear').val(picker.startDate.format('YYYY'));
        });
        function generateRateCardTable(clientId,positionId,jobCode){
            $.ajax({
                url :"generateRateCardTable.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,positionId:positionId,jobCode:jobCode},
                success: function(data) {
                }
            }).done(function(data){
                $('.rateCardBody').html('');
                $('.rateCardBody').html(data);
            });
        }
        function generateNewFinancialYearRateCardTable(clientId,positionId,jobCode){
            let action = 'NEWFINANCIALYEAR';
            $.ajax({
                url :"generateRateCardTable.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,positionId:positionId,jobCode:jobCode,action:action},
                success: function(data) {
                }
            }).done(function(data){
                $('.rateCardNFBody').html('');
                $('.rateCardNFBody').html(data);
            });
        }
        function displayRateCardYears(clientId,positionId,jobCode,status){
            $.ajax({
                url :"displayRateCardSnapshot.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,positionId:positionId,jobCode:jobCode,status:status},
                success: function(data) {
                    $('#archived').html('');
                    $('#archived').html(data);
                }
            });
        }
        function getAward(clientId,positionId){
            let action = 'AWARD';
            $.ajax({
                url :"rateUpdate.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,positionId:positionId,action:action},
                success: function(data) {
                }
            }).done(function(data){
                $('#awardLabel').html('');
                $('#awardLabel').html(data);
                $('#award').val(data);
            });
        }
        function getClientRates(clientId){
            let action = 'CLIENTRATES';
            $.ajax({
                url :"rateUpdate.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,action:action},
                success: function(data) {
                }
            }).done(function(data){
                let arr = data.split('-');
                $('#payrollTaxLabel').html('');
                $('#payrollTaxLabel').html(arr[0]);
                $('#payrollTax').val(arr[0]);
                $('#workcoverLabel').html('');
                $('#workcoverLabel').html(arr[1]);
                $('#workcover').val(arr[1]);
                $('#super_percentageLabel').html('');
                $('#super_percentageLabel').html(arr[2]);
                $('#super_percentage').val(arr[2]);
                $('#mhwsLabel').html('');
                $('#mhwsLabel').html(arr[3]);
                $('#mhws').val(arr[3]);
            });
        }
        $(document).on('click','#snapshotBtn',function () {
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionid option:selected').val();
            var jobCode = $('#jobcode').val();
            var snapYear = $('#snapYear').val();
            var action = 'SAVE';
            $.ajax({
                url :"saveRateCardSnapshot.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,positionId:positionId,jobCode:jobCode,snapYear:snapYear,action:action},
                success: function(data) {

                }
            });
        });
        $(document).on('click','#viewSnapshotBtn',function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionid option:selected').val();
            var jobCode = $('#jobcode').val();
            var snapYear = $('#snapYear').val();
            var action = 'VIEW';
            $.ajax({
                url :"saveRateCardSnapshot.php",
                type:"POST",
                dataType:"html",
                data:{clientId: clientId,positionId:positionId,jobCode:jobCode,snapYear:snapYear,action:action},
                success: function(data) {
                    console.log('........'+data);
                    //$('.rateCardSnapView').html(data);
                    /*$('.rateCardSnapView').dialog({
                        autoOpen: true,
                        height: 800,
                        width: 600});*/
                    var w = window.open('about:blank');
                    w.document.open();
                    w.document.write(data);
                    w.document.close();

                }
            });
        });
        $(document).on('change', '#clientId', function(){
            $('#positionid').show();
            $('#jobcodeLabel').html('');
            $('#awardLabel').html('');
            loadPositions();
        });
        $(document).on('click','.archived',function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionid option:selected').val();
            var jobCode = $('#jobcode').val();
            var status = 'display';
            var year = $(this).html();
            $.ajax({
                url: "displayRateCardSnapshot.php",
                type: "POST",
                dataType: "html",
                data:{clientId: clientId,positionId:positionId,jobCode:jobCode,status:status,year:year},
                success: function(data){
                    console.log('ARCHIVE'+data);
                    $('#rateCardDisplay').html('');
                    $('#rateCardDisplay').html(data);
                }
            });
        });
        $(document).on('click', '#positionid', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionid option:selected').val();
            var status = 'list';
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId},
                success: function(){
                }
            }).done(function(data) {
                //console.log('JOBCODE'+data);
                $('#jobcodeLabel').html('');
                $('#jobcodeLabel').html(data);
                $('#jobcode').val(data);
                $('#rateCardDisplay').html('');
                generateRateCardTable(clientId,positionId,data);
                generateNewFinancialYearRateCardTable(clientId,positionId,data);
                displayRateCardYears(clientId,positionId,data,status);
                getAward(clientId,positionId);
                getClientRates(clientId);
            });
            $('#rateCardDiv').show();
            $('#rateCardNFDiv').show();
            $('#saveRateCard').show();
            $('#saveNFRateCard').show();
        });
        $(document).on('change', '#positionid', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionid option:selected').val();
            var status = 'list';
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobcodeLabel').html('');
                $('#jobcodeLabel').html(data);
                $('#jobcode').val(data);
                $('#rateCardDisplay').html('');
                generateRateCardTable(clientId,positionId,data);
                generateNewFinancialYearRateCardTable(clientId,positionId,data);
                displayRateCardYears(clientId,positionId,data,status);
                getAward(clientId,positionId);
                getClientRates(clientId);
            });
            $('#rateCardDiv').show();
            $('#rateCardNFDiv').show();
            $('#saveRateCard').show();
            $('#saveNFRateCard').show();
        });

        $("#frmRateCard").on('click', '#saveRateCard', function(e) {
                //$('form').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: 'saveRateCard.php',
                    data: $('form').serialize(),
                    success: function (data) {
                       /* $('#rateCardDisplay').html('');
                        $('#rateCardDisplay').html(data);*/
                        $('.msg_display').html('');
                        $('.msg_display').html(data);
                    }
                });
                //});
        });
        $("#frmNFRateCard").on('click', '#saveNFRateCard', function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'post',
                    url: 'saveNFRateCard.php',
                    data: $('form').serialize(),
                    success: function (data) {
                        $('.msg_display').html('');
                        $('.msg_display').html(data);
                    }
                });
        });
        $(document).on('click','#calculateBtn', function(e){
            e.preventDefault();
            let clientId = $('#clientId :selected').val();
            let positionId = $('#positionid :selected').val();
            let award = $('#award').val();
            let payrollTax = $('#payrollTax').val();
            let mhws = $('#mhws').val();
            let workcover = $('#workcover').val();
            let super_percentage = $('#super_percentage').val();
            let margin_select = $('#margin_select :selected').val();
            let margin = $('#margin').val();
            let hourly_rate = $('#hourly_rate').val();
            let increment_percentage = $('#increment_percentage').val();

            if(hourly_rate == ''){
                alert("Enter hourly rate");
            }else if(margin == ''){
                alert("Enter margin");
            }else {
                $.ajax({
                    type: 'post',
                    url: 'calculateRate.php',
                    data: {
                        clientId: clientId,
                        positionId: positionId,
                        award: award,
                        payrollTax: payrollTax,
                        mhws: mhws,
                        workcover: workcover,
                        super_percentage: super_percentage,
                        margin_select: margin_select,
                        margin: margin,
                        hourly_rate: hourly_rate,
                        increment_percentage: increment_percentage
                    },
                    dataType: "text",
                    success: function (data) {
                        let arr = data.split('-');
                        $('#ordinarynewpay').val(arr[0]);
                        $('#ordinarynewcharge').val(arr[1]);
                        $('#overtimenewpay').val(arr[2]);
                        $('#overtimenewcharge').val(arr[3]);
                        $('#doubletimenewpay').val(arr[4]);
                        $('#doubletimenewcharge').val(arr[5]);
                        $('#publicholidaynewpay').val(arr[6]);
                        $('#publicholidaynewcharge').val(arr[7]);
                        $('#earlymorningnewpay').val(arr[8]);
                        $('#earlymorningnewcharge').val(arr[9]);
                        $('#afternoonnewpay').val(arr[10]);
                        $('#afternoonnewcharge').val(arr[11]);
                        $('#nightnewpay').val(arr[12]);
                        $('#nightnewcharge').val(arr[13]);
                        $('#saturdaynewpay').val(arr[14]);
                        $('#saturdaynewcharge').val(arr[15]);
                        $('#sundaynewpay').val(arr[16]);
                        $('#sundaynewcharge').val(arr[17]);
                        console.log('.........' + data);
                    }
                });
            }
        });
        $(document).on('click','#copyRatesBtn',function(e){
            e.preventDefault();
            $('#ordinarypay').val($('#ordinarynewpay').val());
            $('#ordinarycharge').val($('#ordinarynewcharge').val());
            $('#overtimepay').val($('#overtimenewpay').val());
            $('#overtimecharge').val($('#overtimenewcharge').val());
            $('#doubletimepay').val($('#doubletimenewpay').val());
            $('#doubletimecharge').val($('#doubletimenewcharge').val());
            $('#publicholidaypay').val($('#publicholidaynewpay').val());
            $('#publicholidaycharge').val($('#publicholidaynewcharge').val());
            $('#earlymorningpay').val($('#earlymorningnewpay').val());
            $('#earlymorningcharge').val($('#earlymorningnewcharge').val());
            $('#afternoonpay').val($('#afternoonnewpay').val());
            $('#afternooncharge').val($('#afternoonnewcharge').val());
            $('#nightpay').val($('#nightnewpay').val());
            $('#nightcharge').val($('#nightnewcharge').val());
            $('#saturdaypay').val($('#saturdaynewpay').val());
            $('#saturdaycharge').val($('#saturdaynewcharge').val());
            $('#sundaypay').val($('#sundaynewpay').val());
            $('#sundaycharge').val($('#sundaynewcharge').val());
        });

        $(document).on('click','#copyNFRatesBtn',function(e){
            e.preventDefault();
            $('#ordinarypay').val($('#ordinarypayNF').val());
            $('#ordinarycharge').val($('#ordinarychargeNF').val());
            $('#overtimepay').val($('#overtimepayNF').val());
            $('#overtimecharge').val($('#overtimechargeNF').val());
            $('#doubletimepay').val($('#doubletimepayNF').val());
            $('#doubletimecharge').val($('#doubletimechargeNF').val());
            $('#publicholidaypay').val($('#publicholidaypayNF').val());
            $('#publicholidaycharge').val($('#publicholidaychargeNF').val());
            $('#earlymorningpay').val($('#earlymorningpayNF').val());
            $('#earlymorningcharge').val($('#earlymorningchargeNF').val());
            $('#afternoonpay').val($('#afternoonpayNF').val());
            $('#afternooncharge').val($('#afternoonchargeNF').val());
            $('#nightpay').val($('#nightpayNF').val());
            $('#nightcharge').val($('#nightchargeNF').val());
            $('#saturdaypay').val($('#saturdaypayNF').val());
            $('#saturdaycharge').val($('#saturdaychargeNF').val());
            $('#saturdaywithsuperpay').val($('#saturdaywithsuperpayNF').val());
            $('#saturdaywithsupercharge').val($('#saturdaywithsuperchargeNF').val());
            $('#sundaypay').val($('#sundaypayNF').val());
            $('#sundaycharge').val($('#sundaychargeNF').val());
            $('#sundaywithsuperpay').val($('#sundaywithsuperpayNF').val());
            $('#sundaywithsupercharge').val($('#sundaywithsuperchargeNF').val());
            $('#rdopay').val($('#rdopayNF').val());
            $('#rdocharge').val($('#rdochargeNF').val());
            $('#saturdayovertimepay').val($('#saturdayovertimepayNF').val());
            $('#saturdayovertimecharge').val($('#saturdayovertimechargeNF').val());
            $('#sundayovertimepay').val($('#sundayovertimepayNF').val());
            $('#sundayovertimecharge').val($('#sundayovertimechargeNF').val());
            $('#periodovertimepay').val($('#periodovertimepayNF').val());
            $('#periodovertimecharge').val($('#periodovertimechargeNF').val());
            $('#publicholiday2pay').val($('#publicholiday2payNF').val());
            $('#publicholiday2charge').val($('#publicholiday2chargeNF').val());
        });
    });
</script>
</body>
</html>