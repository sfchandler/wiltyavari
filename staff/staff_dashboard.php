<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
include "session_checker.php";
if (!isset($_SESSION['staff_username'])) {
    $msg = base64_encode('Session Expired Access denied!');
    header("Location: login.php?error_msg=$msg");
}

$candidateId = getCandidateIdByEmail($mysqli,$_SESSION['staff_username']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <title>STAFF TIMESHEET</title>
    <script src="../js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui.css">
    <!-- JQUERY UI AUTO COMPLETE STYLES -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/jquery.ui.autocomplete.css">
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="../css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="../css/font-awesome.min.css">
    <!-- Jquery UI date range picker -->
    <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker.css"/>
    <!-- Jquery UI date time picker -->
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.css">
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="../img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="../img/favicon/favicon.ico" type="image/x-icon">
    <!-- BOOTSTRAP JS -->
    <script src="../js/bootstrap/bootstrap.min.js"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
    <script src="../js/libs/jquery-ui-1.10.3.min.js"></script>
    <!-- you load jquery somewhere before jSignature...-->
    <!-- Jquery Form Validator -->
    <script type="text/javascript" src="../js/jquery.validate.js"></script>
    <script type="text/javascript" src="../js/validation_messages.js"></script>
    <script type="text/javascript" src="../js/jquery.base64.js"></script>
    <script type="text/javascript" src="../js/jquery.timepicker.min.js"></script>
    <script type="text/javascript" src="../js/daterangepicker/moment.js"></script>
    <script type="text/javascript" src="../js/daterangepicker/daterangepicker.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="../js/jqueryform/jquery.form.js"></script>
    <style>
        .error {
            color: red;
        }

        label {
            font-weight: normal;
        }
        .sign-panel {
            margin: 0 auto;
            padding: 10px 5px 10px 5px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 100%;
            height: 100%;
            min-height: 650px;
            /* overflow-x: auto;
             min-height: .01%;*/
        }
        /* ------------- ajax loading styles ---------- */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('../img/page-loading.gif') 50% 50% no-repeat;
        }
        .loadDisplay {
            display: none;
            position: fixed;
            z-index: 1000;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url('../img/page-loading.gif') 50% 50% no-repeat;
        }
        body.loading {
            overflow: hidden;
        }
        body.loading .modal {
            display: block;
        }
        body.ajaxLoader {
            overflow: hidden;
        }
        body.ajaxLoader .loadDisplay {
            display: block;
        }
        .tab-pane {
            border-radius: 4px;
        }
        .nav-tabs > li > a {
            border-radius: 2px;
        }
        /* ------------  end ajax styles -------------*/
    </style>
</head>
<body class="receive-item">

<div class="container-fluid">
    <br><br>
    <div class="sign-panel">
        <div style="text-align: center"><img src="../img/logo.png" alt=""></div>

        <div style="padding: 5px 50px 50px 50px;">
        <div style="float: right; padding-right: 20px;"><a href="logout.php" class="btn-link">Logout</a></div>
        <div style="clear: both"></div>
        <br>
        <div class="error" style="margin: 0 auto"></div>

        <div class="sign-panel" style="padding: 10px 10px 10px 10px">
            <h2 style="text-align: center">Staff Timesheet Management</h2>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist" id="staffTab">
                <li class="active">
                    <a href="#timesheetTab" role="tab" data-toggle="tab">
                        <i class="fa fa-clock-o"></i> Timesheet
                    </a>
                </li>
                <li><a href="#bankTab" role="tab" data-toggle="tab">
                        <i class="fa fa-money"></i> Bank Details
                    </a>
                </li>
                <li>
                    <a href="#taxTab" role="tab" data-toggle="tab">
                        <i class="fa fa-institution"></i> Tax Details
                    </a>
                </li>
                <li>
                    <a href="#superTab" role="tab" data-toggle="tab">
                        <i class="fa fa-institution"></i> Super Details
                    </a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade active in" id="timesheetTab">
                    <h3 style="text-align: center"><i class="fa fa-x fa-clock-o"></i> MY TIMESHEET</h3>
                    <table style="width: 50%; margin-left: 50px;">
                        <tbody>
                        <tr>
                            <td>
                                <select name="clientId" id="clientId" class="form-control">
                                    <?php echo getAssignedClientsForJobAdder($mysqli,$candidateId); ?>
                                </select>
                                <input type="hidden" name="candidateId" id="candidateId" value="<?php echo $candidateId;?>">
                            </td>
                            <td>
                                <select name="positionId" id="positionId" class="form-control" style="display: none">
                                    <?php
                                    $empPositions = getEmployeePositionList($mysqli, $candidateId);
                                    foreach ($empPositions as $position) {
                                        echo '<option value="'.$position['positionId'].'">'.$position['positionName'].'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <div id="reportrange" style="background: #fff; cursor: pointer;padding-left: 5px; padding-top: 10px; border: 1px solid lightgrey; height: 32px;">
                                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                    <span></span> <b class="caret"></b>
                                </div>
                                <input type="hidden" name="startDate" id="startDate">
                                <input type="hidden" name="endDate" id="endDate">
                            </td>
                            <td>
                                <button class="btn btn-info viewBtn" name="viewBtn" id="viewBtn">View</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <section class="col col-12" style="padding: 20px 20px 20px 20px">
                            <table id="dataTbl" class="table table-striped table-bordered table-striped table-responsive" style="width: 95%; margin: 0 auto">
                                <thead>
                                <tr>
                                    <th style="width: 5%">Date</th>
                                    <th style="width: 5%" class="text-center">Day</th>
                                    <th style="width: 5%" class="text-center">Start</th>
                                    <th style="width: 5%" class="text-center">Finish</th>
                                    <th style="width: 5%" class="text-center">Breaks</th>
                                    <th style="width: 5%" class="text-center">Work hours</th>
                                    <th style="width: 5%" class="text-center">Notes</th>
                                    <th style="width: 2%">Action</th>
                                </tr>
                                </thead>
                                <tbody id="timesheet_tbl_body">

                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="7"> </td>
                                    <td class="text-center">
                                        <button name="finaliseBtn" class="btn btn-info finaliseBtn">Finalise</button>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </section>
                    </div>
                </div>
                <div class="tab-pane fade" id="bankTab">
                    <h3 style="text-align: center"><i class="fa fa-x fa-building"></i> Financial Information </h3>
                    <table class="table table-bordered table-striped table-responsive text-center" style="width: 90%; margin: 0 auto">
                        <thead>
                          <tr>
                            <th class="text-center">Bank Name</th>
                            <th class="text-center">Account Name</th>
                            <th class="text-center">Account Number</th>
                            <th class="text-center">BSB Number</th>
                            <th class="text-center">Edit/Update</th>
                          </tr>
                        </thead>
                        <tbody id="bank_info">
                          <?php
                          $bank_info = getEmployeeBankAccountRows($mysqli,$candidateId);
                          if(!empty($bank_info)){
                            echo $bank_info;
                          }else{
                          ?>
                          <tr id="new_bank_info">
                            <td class="text-center"><input type="text" name="bank_name" id="bank_name" value="" class="form-control" required placeholder="Bank Name"/></td>
                            <td class="text-center"><input type="text" name="account_name" id="account_name" value="" class="form-control" required placeholder="Account Name"/></td>
                            <td class="text-center"><input type="number" name="account_number" id="account_number" value="" class="form-control" required placeholder="Account Number"/></td>
                            <td class="text-center"><input type="text" name="bsb" id="bsb" value="" class="form-control" required placeholder="BSB"/></td>
                            <td class="text-center"><button name="updateBankInfoBtn" id="updateBankInfoBtn" class="btn btn-info">Update</button></td>
                          </tr>
                          <?php } ?>
                        </tbody>
                      </table>
                </div>
                <div class="tab-pane fade" id="taxTab">
                    <h3 style="text-align: center"><i class="fa fa-x fa-info"></i> Tax Information</h3>
                    <table class="table table-bordered table-striped table-responsive text-center" style="width: 90%; margin: 0 auto">
                        <thead>
                        <tr>
                            <th class="text-center">Tax file number</th>
                            <th class="text-center">Tax Code</th>
                            <th class="text-center">Edit/Update</th>
                        </tr>
                        </thead>
                        <tbody id="tfn_info">
                        <?php
                        $tfn = getCandidateTFN($mysqli,$candidateId);
                        $taxCode = getTaxCodeDescriptionByCode($mysqli,getEmployeeTaxCode($mysqli,$candidateId));
                        if(!empty($tfn) && !empty($taxCode)){
                            echo '<tr><td class="text-center">'.$tfn.'</td><td class="text-center">'.$taxCode.'</td></tr>';
                        }else{
                        ?>
                        <tr id="new_tfn_info">
                            <td class="text-center"><input type="number" name="tax_file_no" id="tax_file_no" value="" class="form-control" required placeholder="Tax file number"/></td>
                            <td class="text-center">
                                <select name="tax_code" id="tax_code" class="form-control">
                                    <?php echo getTaxFormulaTypes($mysqli); ?>
                                </select>
                            </td>
                            <td class="text-center"><button name="updateTaxInfoBtn" id="updateTaxInfoBtn" class="btn btn-info">Update</button></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="superTab">
                    <h3 style="text-align: center"><i class="fa fa-x fa-building"></i> Superfund Information</h3>
                    <table class="table table-bordered table-striped table-responsive text-center" style="width: 90%; margin: 0 auto">
                        <thead>
                        <tr>
                            <th class="text-center">Super Fund Name</th>
                            <th class="text-center">Super Fund USI/SPIN ID</th>
                            <th class="text-center">Super Member Number</th>
                            <th class="text-center">Edit/Update</th>
                        </tr>
                        </thead>
                        <tbody id="bank_info">
                        <?php
                        $superMemberInfo = getCandidateSuperMemberInformation($mysqli,$candidateId);
                        if(count($superMemberInfo)>0){
                            foreach ($superMemberInfo as $superInfo){
                                echo  '<tr><td class="text-center">' . $superInfo['superFundName']. '</td><td class="text-center">' . $superInfo['superMemberNo']. '</td><td class="text-center">' . $superInfo['superUSINo'] . '</td></tr>';
                            }
                        }else{
                            ?>
                            <form name="frmSuper" id="frmSuper" action="" method="post">
                            <tr id="new_super_info">
                                <td class="text-center"><input type="text" name="super_fund_name" id="super_fund_name" value="" class="form-control" required placeholder="Super Fund Name"/></td>
                                <td class="text-center"><input type="text" name="usi_no" id="usi_no" value="" class="form-control" required placeholder="Super Fund USI No"/></td>
                                <td class="text-center"><input type="number" name="super_member_no" id="super_member_no" value="" class="form-control" required placeholder="Super Member Number"/></td>
                                <td class="text-center"><button type="submit" name="updateSuperInfoBtn" id="updateSuperInfoBtn" class="btn btn-info">Update</button></td>
                            </tr>
                            </form>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>
    <br><br>
</div>
<!--<script type="text/javascript" src="../js/staff_script.js"></script>-->
<script type="text/javascript">
    $(document).ready(function () {

        $.fn.extend({
            donetyping: function(callback,timeout){
                timeout = timeout || 1e3;
                var timeoutReference,
                    doneTyping = function(el){
                        if (!timeoutReference) return;
                        timeoutReference = null;
                        callback.call(el);
                    };
                return this.each(function(i,el){
                    var $el = $(el);
                    $el.is(':input') && $el.on('keyup keypress paste',function(e){
                        if (e.type=='keyup' && e.keyCode!=8) return;
                        if (timeoutReference) clearTimeout(timeoutReference);
                        timeoutReference = setTimeout(function(){
                            doneTyping(el);
                        }, timeout);
                    }).on('blur',function(){
                        doneTyping(el);
                    });
                });
            }
        });

        var start = moment().startOf('isoWeek'); //moment().subtract(29, 'days');
        var end =  moment().startOf('isoWeek').add(6, 'days'); //moment();
        var weekday = new Array(7);
        weekday[0] = "Sun";
        weekday[1] = "Mon";
        weekday[2] = "Tue";
        weekday[3] = "Wed";
        weekday[4] = "Thu";
        weekday[5] = "Fri";
        weekday[6] = "Sat";
        var headerGlobal = [];
        var headerReturn = [];
        var period = [];
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
                dateRange.push(dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate());
                days.push(weekday[dateFormat.getDay()]);
                date.push(dateFormat.getDate());
                header.push({
                    'headerFullDate': dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate(),
                    'headerDate': dateFormat.getDate(),
                    'headerDay': weekday[dateFormat.getDay()]
                });
                headerReturn.push({
                    'headerFullDate': dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate(),
                    'headerDate': dateFormat.getDate(),
                    'headerDay': weekday[dateFormat.getDay()]
                });
                headerGlobal.push({'headerFullDate': dateFormat.getFullYear() + '-' + (dateFormat.getMonth() + 1) + '-' + dateFormat.getDate()});
                currentDate.setDate(currentDate.getDate() + 1);
            }
            period = dateRange;
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
                'This Week': [moment().startOf('isoWeek'), moment().startOf('isoWeek').add(6, 'days').toDate()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, dateCalendar);
        dateCalendar(start, end);

        $(document).on('click','.viewBtn', function () {
            let start_date = $('#startDate').val();//start.format('YYYY-MM-DD');
            let end_date = $('#endDate').val();//end.format('YYYY-MM-DD');
            let positionId = $('#positionId :selected').val();
            let clientId = $('#clientId :selected').val();
            let stateId = 2;
            let deptId = 1;
            $.ajax({
                url: "getStaffTimeclock.php",
                type: "POST",
                data: {period: period, start_date: start_date, end_date: end_date,positionId:positionId,clientId:clientId,stateId:stateId,deptId:deptId},
                dataType: "text",
                success: function (data) {
                    $('#timesheet_tbl_body').html('');
                    $('#timesheet_tbl_body').html(data);
                }
            }).done(function () {
                $('.sh_end').donetyping(function() {
                    let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
                    let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
                    let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
                    let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
                    let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs');
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        data: {shiftDate: shiftDate, shiftStart: shiftStart, shiftEnd: shiftEnd,shiftBreak:shiftBreak},
                        dataType: "text",
                        success: function (data) {
                            console.log(data);
                            workHours.val(data);
                        }
                    });
                });
                $(document).on('change','.sh_end',function() {
                    let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
                    let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
                    let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
                    let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
                    let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs');
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        data: {shiftDate: shiftDate, shiftStart: shiftStart, shiftEnd: shiftEnd,shiftBreak:shiftBreak},
                        dataType: "text",
                        success: function (data) {
                            console.log(data);
                            workHours.val(data);
                        }
                    });
                });
                $('.sh_break').donetyping(function() {
                    let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
                    let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
                    let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
                    let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
                    let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs');
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        data: {shiftDate: shiftDate, shiftStart: shiftStart, shiftEnd: shiftEnd,shiftBreak:shiftBreak},
                        dataType: "text",
                        success: function (data) {
                            console.log(data);
                            workHours.val(data);
                        }
                    });
                });
                $(document).on('change','.sh_break',function() {
                    let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
                    let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
                    let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
                    let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
                    let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs');
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        data: {shiftDate: shiftDate, shiftStart: shiftStart, shiftEnd: shiftEnd,shiftBreak:shiftBreak},
                        dataType: "text",
                        success: function (data) {
                            console.log(data);
                            workHours.val(data);
                        }
                    });
                });
                /*$('.sh_end').on( "focusout", function() {
                    let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
                    let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
                    let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
                    let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
                    let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs');
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        data: {shiftDate: shiftDate, shiftStart: shiftStart, shiftEnd: shiftEnd,shiftBreak:shiftBreak},
                        dataType: "text",
                        success: function (data) {
                            console.log(data);
                            workHours.val(data);
                        }
                    });
                }).on( "blur", function() {
                    let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
                    let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
                    let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
                    let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
                    let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs');
                    $.ajax({
                        url: "getWrkHours.php",
                        type: "POST",
                        data: {shiftDate: shiftDate, shiftStart: shiftStart, shiftEnd: shiftEnd,shiftBreak:shiftBreak},
                        dataType: "text",
                        success: function (data) {
                            console.log(data);
                            workHours.val(data);
                        }
                    });
                });*/
                $('.sh_start').timepicker({'step': 1 , 'timeFormat': 'H:i'});
                $('.sh_end').timepicker({'step': 1 , 'timeFormat': 'H:i'});
                $('.saveAllRow').hide();
                $('.saveAllBtn').hide();
                if($('.saveBtn').is(':visible')){
                    $('.saveAllBtn').show();
                    $('.saveAllRow').show();
                }
                dataTableInit();
            });
        });

        function dataTableInit(){
            var table = $('#dataTbl').DataTable({
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": true,
                "order": [],
                "pageLength": 100
            });
            $('#dataTbl thead th').each(function() {
                var title = $('#dataTbl thead th').eq($(this).index()).text();
                $(this).html(title+'\n<input type="text" />');
            });
            table.columns().eq(0).each(function (colIdx) {
                $('input', table.column(colIdx).header()).on('keyup change', function () {
                    table
                        .column(colIdx)
                        .search(this.value)
                        .draw();
                });

                $('input', table.column(colIdx).header()).on('click', function (e) {
                    e.stopPropagation();
                });
            });
        }


        $(document).on('click','.saveAllBtn', function () {
            $('.saveBtn').trigger('click');
        });
        $(document).on('click', '.saveBtn', function () {
            let shiftDate = $(this).closest('tr').find('td').find('.sh_date').val();
            let shiftStart = $(this).closest('tr').find('td').find('.sh_start').val();
            let shiftEnd = $(this).closest('tr').find('td').find('.sh_end').val();
            let shiftBreak = $(this).closest('tr').find('td').find('.sh_break').val();
            let workHours =  $(this).closest('tr').find('td').find('.sh_wrkhrs').val();
            let shiftNote = $(this).closest('tr').find('td').find('.shift_note').val();
            let candidateId = $('#candidateId').val();
            let positionId = $('#positionId :selected').val();
            let action = 'ADD';
            let clientId = $('#clientId :selected').val();
            let stateId = 2;
            let deptId = 1;
            let shiftStatus = 'CONFIRMED';
            if(shiftStart !== '' && shiftEnd !== '' && workHours !== '') {
                $.ajax({
                    url: "getStaffTimeclock.php",
                    type: "POST",
                    data: {
                        shiftDate: shiftDate,
                        shiftStart: shiftStart,
                        shiftEnd: shiftEnd,
                        shiftBreak: shiftBreak,
                        workHours: workHours,
                        clientId: clientId,
                        stateId: stateId,
                        deptId: deptId,
                        candidateId: candidateId,
                        shiftNote: shiftNote,
                        positionId: positionId,
                        shiftStatus: shiftStatus,
                        action: action
                    },
                    dataType: "json",
                    success: function (data) {
                        console.log(data);
                    }
                });
            }
        });

        $(document).on('click','.finaliseBtn', function () {
            let start_date = $('#startDate').val();
            let end_date = $('#endDate').val();
            let positionId = $('#positionId :selected').val();
            let action = 'FINALISE';
            let clientId = $('#clientId :selected').val();
            let stateId = 2;
            let deptId = 1;
            let shiftStatus = 'CONFIRMED';
            $.ajax({
                url: "getStaffTimeclock.php",
                type: "POST",
                data: {start_date: start_date, end_date: end_date,positionId:positionId,clientId:clientId,stateId:stateId,deptId:deptId,action:action,shiftStatus:shiftStatus},
                dataType: "text",
                success: function (data) {
                    $('.error').html('');
                    $('.error').html(data);
                    $('html, body').animate({scrollTop: '0px'}, 300);
                }
            });
        });
        $('#staffTab a').click(function(e) {
            e.preventDefault();
            $(this).tab('show');
        });
        // store the currently selected tab in the hash value
        $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
            var id = $(e.target).attr("href").substring(1);
            window.location.hash = id;
        });
        // on load of the page: switch to the currently selected tab
        var hash = window.location.hash;
        $('#staffTab a[href="' + hash + '"]').tab('show');

        $(document).on('click','#updateBankInfoBtn', function () {
            let action = 'BANKUPDATE';
            let bank_name = $('#bank_name').val();
            let account_name = $('#account_name').val();
            let bsb = $('#bsb').val();
            let account_number = $('#account_number').val();
            $.ajax({
                url: "getStaffTimeclock.php",
                type: "POST",
                data: {action:action, bank_name:bank_name,account_name:account_name,bsb:bsb,account_number:account_number},
                dataType: "text",
                success: function (data) {
                    $('.error').html('');
                    $('.error').html(data);
                    location.reload();
                    $('html, body').animate({scrollTop: '0px'}, 300);
                }
            });
        });

        $(document).on('click','#updateTaxInfoBtn', function () {
            let action = 'TAXUPDATE';
            let tax_file_no = $('#tax_file_no').val();
            let tax_code = $('#tax_code :selected').val();
            $.ajax({
                url: "getStaffTimeclock.php",
                type: "POST",
                data: {action:action,tax_file_no:tax_file_no,tax_code:tax_code},
                dataType: "text",
                success: function (data) {
                    $('.error').html('');
                    $('.error').html(data);
                    location.reload();
                    $('html, body').animate({scrollTop: '0px'}, 300);
                }
            });
        });

        $(document).on('click','#updateSuperInfoBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmSuper = $('#frmSuper').validate({
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
                    super_fund_name: {
                        required: true
                    },
                    usi_no: {
                        required: true
                    },
                    super_member_no: {
                        required: true
                    }
                },
                messages: {
                    super_fund_name: {
                        required: "Please enter super fund name"
                    },
                    usi_no: {
                        required: "Please enter super fund USI No"
                    },
                    super_member_no: {
                        required: "Please enter Super member number"
                    }
                },
                submitHandler: function (form) {
                    var action = 'SUPERUPDATE';
                    var super_fund_name = $('#super_fund_name').val();
                    var usi_no = $('#usi_no').val();
                    var super_member_no = $('#super_member_no').val();

                    $.ajax({
                        url: "getStaffTimeclock.php",
                        type: "POST",
                        data: {
                            action: action,
                            super_fund_name: super_fund_name,
                            usi_no: usi_no,
                            super_member_no: super_member_no
                        },
                        dataType: "text",
                        success: function (data) {
                            $('.error').html('');
                            $('.error').html(data);
                            location.reload();
                            $('html, body').animate({scrollTop: '0px'}, 300);
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
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>