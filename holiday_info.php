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
        <div class="content-body no-content-padding" style="height: 100%">

            <div style="padding-left:30px;" class="error"></div>
            <div style="width:100%">
                <div style="padding-left: 10px;">
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
        loadStates();
        $('#positionid').hide();
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
    });
</script>
</body>

</html>