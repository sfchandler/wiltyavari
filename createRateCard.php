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
    <div id="content">
        <div class="content-body no-content-padding">
            <div style="padding-left:20px;">
                <h2 class="semi-bold">Rate Card</h2>
            </div>
            <div style="padding-left:30px;" class="error"></div>
            <div style="width:100%">
                <div style="float:left; padding-left:30px;padding-bottom:50px; width:50%">
                    <form name="frmRateCard" method="post" action="">
                        <div class="row">
                            <section class="col col-6">
                            <label for="clientId" class="select">CLIENT:</label>
                                <select name="clientId" id="clientId"  class="form-control" style=" cursor: pointer; width: 60%">
                                </select><i></i>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-6">
                            <label for="positionid" class="select">POSITION:</label>
                                <select name="positionid" id="positionid"  class="form-control" style=" cursor: pointer; width: 60%">
                                </select><i></i>
                            </section>
                        </div>
                        <div class="row">
                            <label>JOB CODE:&nbsp;</label><label id="jobcodeLabel"></label>
                            <input type="hidden" id="jobcode" name="jobcode" value=""/>
                            <div id="archive_display" style="border: 1px dashed grey; padding: 3px">
                                <fieldset>
                                    <legend>Archive records</legend>
                                <input type="text" name="snapYear" id="snapYear" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 20%" placeholder="Only Select the Year"/>
                                <button type="button" id="snapshotBtn" class="btn btn-sm btn-info">Archive For Year</button>
                                <button type="button" id="viewSnapshotBtn" class="btn btn-sm btn-info">View Archived Rates</button>
                                </fieldset>
                            </div>
                        </div>
                        <div class="row">
                            <br>
                        </div>
                        <!--<div class="row">
                            <div><label>Archives</label></div><div id="archived"></div>
                        </div>-->
                        <div id="rateCardDiv">
                            <fieldset>
                                <legend>Active records</legend>
                            <table id="rateCardTable" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                                <thead>
                                    <tr>
                                        <th data-class="expand"><i class="fa fa-fw fa-indent txt-color-blue hidden-md hidden-sm hidden-xs"></i>Pay Category</th>
                                        <th data-hide="phone"><i class="fa fa-fw fa-dollar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Pay Rate</th>
                                        <th data-hide="phone"><i class="fa fa-fw fa-dollar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Charge Rate</th>
                                    </tr>
                                </thead>
                                <tbody class="rateCardBody">
                                </tbody>
                            </table>
                            </fieldset>
                        </div>
                        <button type="submit" name="saveRateCard" id="saveRateCard" class="saveRateCard btn btn-success btn-sm"><i class="glyphicon glyphicon-file fa fa-save"></i>&nbsp; Save/Update</button>
                    </form>
                </div>
                <div id="rateCardDisplay" style="float:left; padding-left:20px;padding-bottom:50px; width:50%"></div>
                <div class="rateCardSnapView" style="display: none"></div>
                <div style="float:left; padding-left:20px;padding-bottom:20px; width:50%">
                    <div class="pull-left">
                        <label for="pubHoliday" class="input">Add Public Holiday
                            <input type="text" name="pubHoliday" id="pubHoliday" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Select Holiday"/>
                        </label>
                    </div>
                    <div class="pull-left">
                        <label for="stateId" class="input">Select State
                            <select name="stateId" id="stateId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </div>
                    <div class="pull-left">
                        <label for="addHolidayBtn" style="padding-top: 22px">&nbsp;</label>
                        <button name="addHolidayBtn" id="addHolidayBtn" class="addHolidayBtn btn btn-success btn-square btn-sm"><i class="glyphicon glyphicon fa fa-plus"></i>&nbsp; Add Public Holiday</button>

                    </div>
                </div>
                <div id="pubHolidayDisplay" style="float:left; padding-left:20px;padding-bottom:50px; width:50%;">Public Holidays
                    <div style="overflow: auto; height: 300px;">
                    <table id="hTable" class="table table-responsive" style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <th style="position: sticky; top: 0; z-index: 1;"><i class="fa fa-fw fa-calendar-times-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Date</th>
                            <th style="position: sticky; top: 0; z-index: 1;"><i class="fa fa-fw fa-calendar-times-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>State</th>
                            <th style="position: sticky; top: 0; z-index: 1;"><i class="fa fa-fw fa-try txt-color-blue hidden-md hidden-sm hidden-xs"></i>Action</th>
                        </thead>
                        <tbody class="phBody">
                        </tbody>
                    </table>
                    </div>
                </div>


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
    $(function(){
        loadClients();
        loadStates();
        $('#positionid').hide();
        $('#saveRateCard').hide();
        $('#rateCardDiv').hide();
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
            });
        }
        function loadStates(){
            var action = 'HOLIDAY';
            $.ajax({
                url :"getStatesDropdown.php",
                type:"POST",
                dataType:"html",
                data:{action:action},
                success: function(data) {
                }
            }).done(function(data){
                $('#stateId').html('');
                $('#stateId').html(data);
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
        $('input[name="pubHoliday"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="pubHoliday"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#pubHoliday').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="snapYear"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="snapYear"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY'));
            $('#snapYear').val(picker.startDate.format('YYYY'));
        });

        loadPublicHolidays();
        function loadPublicHolidays(){
            $.ajax({
                url :"processPublicHoliday.php",
                type:"POST",
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('.phBody').html('');
                $('.phBody').html(data);
            });
        }
        $(document).on('click','#addHolidayBtn', function () {
            var publicHoliday = $('#pubHoliday').val();
            var action = 'add';
            var stateId = $('#stateId :selected').val();
            $.ajax({
                url :"processPublicHoliday.php",
                type:"POST",
                dataType:"html",
                data:{publicHoliday: publicHoliday,action:action,stateId:stateId},
                success: function(data) {
                }
            }).done(function(data){
                $('.phBody').html('');
                $('.phBody').html(data);
            });
        });
        $(document).on('click','.deletePubHolidayBtn',function () {
            var publicHolidayId = $(this).closest('td').attr('data-pholidayid');
            var action = 'delete';
            $.ajax({
                url :"processPublicHoliday.php",
                type:"POST",
                dataType:"html",
                data:{publicHolidayId: publicHolidayId,action:action},
                success: function(data) {
                }
            }).done(function(data){
                $('.phBody').html('');
                $('.phBody').html(data);
            });
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
                displayRateCardYears(clientId,positionId,data,status);
            });
            $('#rateCardDiv').show();
            $('#saveRateCard').show();
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
                displayRateCardYears(clientId,positionId,data,status);
            });
            $('#rateCardDiv').show();
            $('#saveRateCard').show();
        });
        /*function submitForm(form){
            var url = form.attr("action");
            var formData = {};
            $(form).find("input[name]").each(function (index, node) {
                formData[node.name] = node.value;
            });
            $.post(url, formData).done(function (data) {
                $('#rateCardDisplay').html('');
                $('#rateCardDisplay').html(data);
            });
        }*/
        $('form').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'post',
                url: 'saveRateCard.php',
                data: $('form').serialize(),
                success: function (data) {
                    $('#rateCardDisplay').html('');
                    $('#rateCardDisplay').html(data);
                }
            });

        });
    });
</script>
</body>

</html>