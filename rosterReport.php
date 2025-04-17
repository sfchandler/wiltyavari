<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
/*if($_SESSION['userType']!=='ACCOUNTS'){
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}*/
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <style>
        .ui-menu { width: 200px; }
        .ui-widget-header { padding: 0.2em; }
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
        <h2>Roster Shifts Report</h2>
        <div class="error"></div>
        <div class="filterPanel">
            <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                <span></span> <b class="caret"></b>
                <input type="hidden" name="startDate" id="startDate">
                <input type="hidden" name="endDate" id="endDate">
                <input type="hidden" name="dateRange" id="dateRange">
            </div>
            <div class="pull-left">
                <label for="clientId" class="select">
                    <select name="clientId" id="clientId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="industryId" class="select">
                    <select name="industryId" id="industryId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="stateId" class="select">
                    <select name="stateId" id="stateId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="expPosition" class="select">
                    <select name="expPosition" id="expPosition"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="shiftStatus" class="select">
                    <select name="shiftStatus" id="shiftStatus"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <option value="ALL">ALL</option>
                        <option value="OPEN" selected>OPEN</option>
                        <option value="CONFIRMED" selected>CONFIRMED</option>
                        <option value="UNFILLED">UNFILLED</option>
                    </select><i></i></label>
            </div>
            <div class="pull-left">
                <label for="employeeName" class="input">
                    <input id="employeeName" name="employeeName" type="text" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Employee Name"/>
                </label><input type="hidden" name="empSelected" id="empSelected"/>
            </div>
            <div class="pull-left">
                <label for="generateBtn">
                    <button name="generateBtn" id="generateBtn" class="generateBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon-export"></i>&nbsp;Generate Report</button>
                </label>
            </div>
            <div id="downloadReport" class="pull-left">
                <label for="downloadBtn">
                    <a href="" name="downloadBtn" id="downloadBtn" class="downloadBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon-download"></i>&nbsp;Download Report</a>
                </label>
            </div>
            <div style="clear: both;"></div>
        </div>
        <div class="rosterReportDiv">
            <form id="frmrosterReport" method="post">
                <table id="rosterReportTable" border="1" cellpadding="2" cellspacing="2" class="rosterReportTable table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th data-class="phone"><i class="fa fa-fw fa-calendar txt-color-blue hidden-md hidden-sm hidden-xs"></i>Shift Date</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Client</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Industry</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-country txt-color-blue hidden-md hidden-sm hidden-xs"></i>State</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-indent txt-color-blue hidden-md hidden-sm hidden-xs"></i>Department</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Employee</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i>Employee Mobile</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i>Smart Phone Status</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-phone txt-color-blue hidden-md hidden-sm hidden-xs"></i>Email</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-certificate txt-color-blue hidden-md hidden-sm hidden-xs"></i>Position</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Start Time</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>End Time</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Break</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-clock-o txt-color-blue hidden-md hidden-sm hidden-xs"></i>Hours Worked</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-exclamation txt-color-blue hidden-md hidden-sm hidden-xs"></i>Shift Status</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-secret txt-color-blue hidden-md hidden-sm hidden-xs"></i>Employee ID</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-secret txt-color-blue hidden-md hidden-sm hidden-xs"></i>Employee Type</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-secret txt-color-blue hidden-md hidden-sm hidden-xs"></i>Visa Type</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>DOB</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Gender</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>Clock IN</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>Clock Out</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Supervisor Status</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>Consultant</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-medkit txt-color-blue hidden-md hidden-sm hidden-xs"></i>Vaccine 1</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-medkit txt-color-blue hidden-md hidden-sm hidden-xs"></i>Vaccine 2</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-medkit txt-color-blue hidden-md hidden-sm hidden-xs"></i>Vaccine 3</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>OHS Sent Time</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>OHS Submitted Date</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>OHS Check Status</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>OHS Checked By</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>OHS Checked at</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>Need Discussion</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>Max Student Shifts</th>
                        <th data-hide="phone"><i class="fa fa-fw fa-user-times txt-color-blue hidden-md hidden-sm hidden-xs"></i>Emp Variation Agreement</th>
                    </tr>
                    </thead>
                    <tbody class="rosterReportBody">
                    </tbody>
                </table>
            </form>
        </div>
        <div id="rosterReportDisplay"></div>
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
        $('#downloadReport').hide();
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

        populateClients();
        function populateClients(){
            $.ajax({
                url:"getReportClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            }).done(function () {
                populateStates();
            });
        }
        $(document).on('change','#clientId',function () {
            populateIndustries();
            populateStates();
        });
        populateIndustries();
        function populateIndustries(){
            var clientId = $('#clientId :selected').val();
            $.ajax({
                url:"getIndustries.php",
                type:"POST",
                data:{clientId:clientId},
                dataType:"html",
                success: function(data){
                    $('#industryId').html('');
                    $('#industryId').html(data);
                }
            });
        }
        populateStates();
        function populateStates(){
            //var clientId = $('#clientId :selected').val();
            $.ajax({
                url:"getStatesDropdown.php",
                type:"POST",
               /* data:{clientId:clientId},*/
                dataType:"html",
                success: function(data){
                    $('#stateId').html('');
                    $('#stateId').html(data);
                }
            });
        }
        populateCandidatePositions();
        function populateCandidatePositions(){
            $.ajax({
                url:"getPositionListForReport.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#expPosition').html('');
                    $('#expPosition').html(data);
                }
            });
        }
        $('.ui-autocomplete-input').css('width','40px')
        $('#employeeName').autocomplete({
            source: <?php include "./employeeList.php"; ?>,
            select: function(event, ui) {
                var empName = ui.item.value;
                var candidateId = ui.item.id;
                $('#empSelected').val('');
                $('#empSelected').val(candidateId);
                //console.log('canID'+candidateId+'empName'+empName);
            }
        });
        $(document).on('click', '.generateBtn', function(){
            var clientId = $('#clientId :selected').val();
            var stateId = $('#stateId :selected').val();
            var industryId = $('#industryId :selected').val();
            var positionId = $('#expPosition :selected').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var shiftStatus = $('#shiftStatus :selected').val();
            var candidateId;
            if($('#employeeName').val() === ''){
                candidateId = '';
            }else{
                candidateId = $('#empSelected').val();
            }
            $.ajax({
                url:"generateRosterReport.php",
                type:"POST",
                dataType:"json",
                data:{clientId : clientId,stateId:stateId, industryId:industryId, positionId : positionId, candidateId : candidateId, startDate : startDate, endDate : endDate, shiftStatus : shiftStatus},
                success: function(data){
                    console.log('Arr'+data);
                    if(data.length!=0){
                        var row ='';
                        $.each(data, function(index, element) {
                            row +='<tr>' +
                                '<td>'+element.shiftDay+' '+element.shiftDate+'</td>' +
                                '<td>'+element.client+'</td>' +
                                '<td>'+element.industry+'</td>' +
                                '<td>'+element.state+'</td>' +
                                '<td>'+element.department+'</td>' +
                                '<td>'+element.candidate+'</td>' +
                                '<td>'+element.candidatePhone+'</td>' +
                                '<td>'+element.noPhone+'</td>' +
                                '<td>'+element.email+'</td>' +
                                '<td>'+element.position+'</td>' +
                                '<td>'+element.shiftStart+'</td>' +
                                '<td>'+element.shiftEnd+'</td>' +
                                '<td>'+element.workBreak+'</td>' +
                                '<td>'+element.hrsWorked+'</td>' +
                                '<td>'+element.shiftStatus+'</td>' +
                                '<td>'+element.candidateId+'</td>' +
                                '<td>'+element.employee_type+'</td>' +
                                '<td>'+element.visaType+'</td>' +
                                '<td>'+element.dob+'</td>' +
                                '<td>'+element.gender+'</td>' +
                                '<td>'+element.checkIn+'</td>' +
                                '<td>'+element.checkOut+'</td>' +
                                '<td>'+element.supervisorEdit+'</td>'+
                                '<td>'+element.consultantName+'</td>' +
                                '<td>'+element.vacc1+'</td>' +
                                '<td>'+element.vacc2+'</td>' +
                                '<td>'+element.vacc3+'</td>' +
                                '<td>'+element.ohsTime+'</td>' +
                                '<td>'+element.ohsSubmittedTime+'</td>' +
                                '<td>'+element.ohsCheckStatus+'</td>' +
                                '<td>'+element.ohsCheckedBy+'</td>' +
                                '<td>'+element.ohsCheckedTime+'</td>' +
                                '<td>'+element.feedback+'</td>' +
                                '<td>'+element.maxStudentShiftIndicator+'</td>' +
                                '<td>'+element.empVariationSubmission+'</td>' +
                                '</tr>';
                        });
                        $('.rosterReportBody').html('');
                        $('.rosterReportBody').html(row);
                        $('#downloadBtn').attr('href','./roster/rosterReport.xlsx');
                        $('#downloadReport').show();
                    }else{
                        $('#downloadReport').hide();
                        $('.rosterReportBody').html('No Data Available');
                    }
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>