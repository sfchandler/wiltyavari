<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Australia/Melbourne');
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consultants = getConsultants($mysqli);
$activityTypes = getActivityList($mysqli);
$sessionId = session_id();
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
        <div style="height: 100%; background-color: white;">
                <div>
                    <h2>Consultant KPI's</h2>
                    <div class="erMsg"><?php echo base64_decode($_REQUEST['msg']); ?></div>
                    <form name="frmKPI" id="frmKPI" class="smart-form" method="post" action="genKPIReport.php" style="height: 100%;">
                        <fieldset>
                            <div class="row">
                                <section class="col col-3">
                                    <label>Select Note Created date range</label>
                                    <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; border: 1px solid #ccc; width: 90%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                        <span></span> <b class="caret"></b>
                                        <input type="hidden" name="startDate" id="startDate">
                                        <input type="hidden" name="endDate" id="endDate">
                                        <input type="hidden" name="dateRange" id="dateRange">
                                    </div>
                                </section>
                                <section class="col col-6">
                                    <div>
                                        <label class="checkbox">
                                            <input type="checkbox" name="consultantId" value="0">
                                            <i></i><?php echo 'All'; ?></label>
                                        <?php
                                        foreach($consultants as $cT) {
                                            ?>
                                            <section class="col col-4">
                                                <label class="checkbox">
                                                    <input type="checkbox" name="consultantId[]" value="<?php echo $cT['consultantId']; ?>">
                                                    <i></i><?php echo $cT['name']; ?></label></section>
                                        <?php } ?>
                                    </div>
                                </section>
                            </div>
                            <hr>
                            <div class="row">
                                <h2>Select Activity Types</h2>
                                <section class="col col-6">
                                    <?php
                                    foreach($activityTypes as $aT) {
                                        ?>
                                        <section class="col col-4">
                                            <label class="checkbox">
                                                <input type="checkbox" name="actId[]" value="<?php echo $aT['activityId']; ?>">
                                                <i></i><?php echo $aT['activityType']; ?></label></section>
                                    <?php } ?>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <button name="genReportBtn" id="genReportBtn" class="genReportBtn pull-left btn btn-primary btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>Generate KPI Report</button>
                                </section>
                            </div>
                        </fieldset>
                    </form>
                </div>
            <div style="height: 100px;">&nbsp;</div>
        </div>

    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<!-- PAGE FOOTER -->
<?php include "template/footer.php"; ?>
<!-- END PAGE FOOTER -->
<!-- END SHORTCUT AREA -->
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
    $(function(){
        var start = moment().subtract(29, 'days');
        var end = moment();
        var weekday=new Array(7);
        weekday[0]="Sun";
        weekday[1]="Mon";
        weekday[2]="Tue";
        weekday[3]="Wed";
        weekday[4]="Thu";
        weekday[5]="Fri";
        weekday[6]="Sat";
        var headerGlobal = [];
        var headerReturn = [];
        function dateCalendar(start, end) {
            var dateRange = [];
            var days = [];
            var date = [];
            var header = [];
            headerGlobal.length = 0;
            headerReturn.length = 0;
            $('#days').html('');
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var startDate = start.format('YYYY-MM-DD');
            var endDate = new Date(end.format('YYYY-MM-DD'));
            var currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                var dateFormat = new Date(currentDate);
                dateRange.push(dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate());
                days.push(weekday[dateFormat.getDay()]);
                date.push(dateFormat.getDate());
                header.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
                headerReturn.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
                headerGlobal.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate()});
                currentDate.setDate(currentDate.getDate() + 1);
            }
            $('#dateRange').val(dateRange);
            $('#startDate').val(start.format('YYYY-MM-DD'));
            $('#endDate').val(end.format('YYYY-MM-DD'));
        }
        $('#reportrange').daterangepicker({
            "autoApply": true,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, dateCalendar);
        dateCalendar(start, end);
        $(document).on('click','.genReportBtn',function () {
           $('.erMsg').html('');
        });
    });
</script>
</body>
</html>