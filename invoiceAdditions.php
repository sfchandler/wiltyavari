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
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px; width: 100%">
        <div class="error"></div>
        <div>
            <h2>Invoice Additions</h2>
            <form name="frmAddition" id="frmAddition" class="smart-form" method="post">
                <div class="row">
                    <section class="col col-3">
                        <label for="clientId" class="select">Select Client
                            <select name="clientId" id="clientId" class="select">
                            </select></label>
                    </section>
                    <section class="col col-3">
                        <label for="positionId" class="select">Select Position
                            <select name="positionId" id="positionId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select><i></i>
                        </label>
                    </section>
                    <section class="col col-3">
                            <label for="deptId" class="select">Select Department
                                <select name="deptId" id="deptId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                </select><i></i></label>
                    </section>
                    <section class="col col-3">
                        <label class="pull-left">JOBCODE:&nbsp;</label><label id="jobCodeLabel"></label>
                        <input type="hidden" id="jobCode" name="jobCode" value=""/>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="employeeName" class="input">Select Employee
                            <input id="employeeName" name="employeeName" type="text" placeholder="Employee Name"/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="employeeID" class="input">Employee ID
                            <input type="text" name="empSelected" id="empSelected" readonly/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="wkDate" class="input">Weekending Date
                            <input type="text" name="wkDate" id="wkDate" value="" class="pull-left" placeholder="Weekending Date" readonly/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="description" class="input">Description
                            <input type="text" name="description" id="description" value="" placeholder="Description">
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="units" class="input">Units
                            <input type="text" name="units" id="units" value="" placeholder="units">
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="amount" class="input">Amount
                            <input type="text" name="amount" id="amount" value="" placeholder="Amount">
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="addBtn" class="input">
                            <button name="addBtn" id="addBtn" class="addBtn btn btn-primary btn-square btn-sm"><i class="glyphicon glyphicon fa fa-plus"></i>&nbsp; Add</button>
                        </label>
                    </section>
                </div>
            </form>
            <div style="padding-right: 10px">
                <div style="padding-bottom:20px; overflow-y: scroll; height: 500px; width: 100%;">
                    <table class="table table-striped table-bordered table-hover sortable">
                        <thead>
                        <th>Client</th>
                        <th>Weekending Date</th>
                        <th>Description</th>
                        <th>Units</th>
                        <th>Amount</th>
                        <th>Employee ID</th>
                        <th>Employee Name</th>
                        <th>JobCode</th>
                        <th>Action</th>
                        </thead>
                        <tbody class="additionDisplay"></tbody>
                    </table>
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

<script type="text/javascript">
    $(document).ready(function(){
        $('#createInvoiceBtn').hide();
        /* AJAX loading animation */
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /* -  end  -*/
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
        getClientsDroprown();
        function getClientsDroprown(){
            var action = 'invoiceDropdown';
            $.ajax({
                url :"getClients.php",
                type:"POST",
                dataType:"html",
                data:{action:action},
                success: function(data) {
                }
            }).done(function(data){
                $('#clientid').html('');
                $('#clientid').html(data);
            });
        }
        function populateClientDepartments(clientid){
            var action = 'DEPARTMENTSFORJOBCODE';
            $.ajax({
                url:"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid,action:action},
                dataType:"html",
                success: function(data){
                    $('#deptId').html('');
                    $('#deptId').html(data);
                }
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
                $('#positionId').html('');
                $('#positionId').html(data);
            });
        }
        $(document).on('change', '#clientId', function(){
            var clientid = $('#clientId :selected').val();
            $('#positionId').show();
            $('#jobCodeLabel').html('');
            loadPositions();
            populateClientDepartments(clientid);
        });

        $(document).on('change', '#deptId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            var deptId = $('#deptId option:selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId, deptId:deptId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobCodeLabel').html('');
                $('#jobCodeLabel').html(data);
                $('#jobCode').val(data);
            });
        });
        $(document).on('click', '#deptId', function(){
            var clientId = $('#clientId option:selected').val();
            var positionId = $('#positionId option:selected').val();
            var deptId = $('#deptId option:selected').val();
            $.ajax({
                url: "getJobCode.php",
                type: "POST",
                dataType: "html",
                data:{clientId : clientId, positionId : positionId,deptId:deptId},
                success: function(){
                }
            }).done(function(data) {
                $('#jobCodeLabel').html('');
                $('#jobCodeLabel').html(data);
                $('#jobCode').val(data);
            });
        });
        $('.error').html('');
        populateClients();
        function populateClients(){
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#clientId').html('');
                    $('#clientId').html(data);
                }
            });
        }
        getPayrollNames();
        function getPayrollNames(){
            $.ajax({
                type: "POST",
                url: "./getPayrollNames.php",
                dataType: "html",
                success: function (data) {
                    $('#payrollName').html('');
                    $('#payrollName').html(data);
                }
            });
        }
        $('input[name="wkDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="wkDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#wkDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="weekendingDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="weekendingDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#weekendingDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="invoiceDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="invoiceDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#invoiceDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        getReprintDates();
        function getReprintDates(){
            $.ajax({
                type: "POST",
                url: "./getInvReprintDates.php",
                dataType: "html",
                success: function (data) {
                    $('#reprintDate').html('');
                    $('#reprintDate').html(data);
                }
            });
        }
        loadInvoicePaths();
        function loadInvoicePaths(){
            var action = 'path';
            $.ajax({
                type: "POST",
                url: "./invoiceAddition.php",
                data:{action:action},
                dataType: "html",
                success: function (data) {
                    $('.invoicePathDisplay').html('');
                    $('.invoicePathDisplay').html(data);
                }
            });
        }
        $.fn.extend({
            donetyping: function(callback,timeout){
                timeout = timeout || 1e3; // 1 second default timeout
                var timeoutReference,
                    doneTyping = function(el){
                        if (!timeoutReference) return;
                        timeoutReference = null;
                        callback.call(el);
                    };
                return this.each(function(i,el){
                    var $el = $(el);
                    // Chrome Fix (Use keyup over keypress to detect backspace)
                    // thank you @palerdot
                    $el.is(':input') && $el.on('keyup keypress paste',function(e){
                        // This catches the backspace button in chrome, but also prevents
                        // the event from triggering too preemptively. Without this line,
                        // using tab/shift+tab will make the focused element fire the callback.
                        if (e.type=='keyup' && e.keyCode!=8) return;

                        // Check if timeout has been set. If it has, "reset" the clock and
                        // start over again.
                        if (timeoutReference) clearTimeout(timeoutReference);
                        timeoutReference = setTimeout(function(){
                            // if we made it here, our timeout has elapsed. Fire the
                            // callback
                            doneTyping(el);
                        }, timeout);
                    }).on('blur',function(){
                        // If we can, fire the event since we're leaving the field
                        doneTyping(el);
                    });
                });
            }
        });
        $('#jobCode').donetyping(function(){
            $('#jobCode').val();
            //get position and client
            $.ajax({

            });
        });
        $(document).on('change','#companyId',function(){
            var action = 'LOGO';
            var companyId = $('#companyId :selected').val();
            $.ajax({
                type: "POST",
                url: "cmpInfoProcess.php",
                data:{action:action,companyId:companyId},
                dataType: "html",
                success: function (data) {
                    $('.compLogo').html('');
                    $('.compLogo').html(data);
                }
            });
        });
        displayAdditionDisplay();
        function displayAdditionDisplay(){
            var action = 'get';
            $.ajax({
                type: "POST",
                url: "invoiceAddition.php",
                data:{action:action},
                dataType: "html",
                success: function (data) {
                    $('.additionDisplay').html('');
                    $('.additionDisplay').html(data);
                }
            });
        }
        $(document).on('click','.removeBtn', function(){
            var remove_id  = $(this).closest('td').attr('data-id');
            var action = 'remove';
            console.log('data'+remove_id+action);
            $.ajax({
                type: "POST",
                url: "invoiceAddition.php",
                data:{remove_id:remove_id,action:action},
                dataType: "html",
                success: function (data) {
                    $('.additionDisplay').html('');
                    $('.additionDisplay').html(data);
                }
            });
        });
        $(document).on('click','.invSend', function(e){
            e.preventDefault();
            let invoiceDate = $(this).closest('td').prev().prev().prev().prev().find('.inv_date').text();
            let wk_date = $(this).closest('td').prev().prev().prev().prev().find('.wk_date').text();
            let form = $(this).closest('.frmEmailSend').find('form').attr('id');
            var invCheckboxes = new Array();
            $(this).closest('td').prev().find('.clEmail:checkbox:checked').each(function () {
                invCheckboxes.push($(this).val());
            });
            let invTimesheet = $(this).closest('td').prev().prev().find('.invTimesheet')[0].files[0];
            let invPath = $(this).closest('td').prev().prev().prev().prev().find('.invPath').attr('href');
            var formData = new FormData(form);
            formData.append('invTimesheet',invTimesheet);
            formData.append('invCheckboxes',invCheckboxes);
            formData.append('invPath',invPath);
            formData.append('invoiceDate',invoiceDate);
            formData.append('wk_date',wk_date);
            console.log('wk_date'+wk_date);
            $.ajax({
                type: "POST",
                url: "./sendInvoices.php",
                dataType: "text",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (data) {
                    if(data === 'SUCCESS'){
                        alert('Emails sent successfully');
                    }else{
                        alert('Error sending emails');
                    }
                }
            });

        });
        $(document).on('click','.addBtn', function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmAddition = $("#frmAddition").validate({
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
                    wkDate:{
                        required: true
                    },
                    units: {
                        required: true
                    },
                    description: {
                        required:true
                    },
                    amount:{
                        required: true
                    },
                    employeeName:{
                        required:true
                    },
                    jobCode: {
                        required:true
                    }
                },
                messages: {
                    wkDate:{
                        required: "Please select Weekending Date"
                    },
                    units:{
                        required: "Please insert units"
                    },
                    description:{
                        required: "Please enter description"
                    },
                    amount:{
                        required: "Please enter amount"
                    },
                    employeeName: {
                        required: "Please select an employee"
                    },
                    jobCode:{
                        required: "Please select position jobcode"
                    }
                },
                submitHandler: function (form) {
                    var wkDate = $('#wkDate').val();
                    var units = $('#units').val();
                    var clientId = $('#clientId :selected').val();
                    var description = $('#description').val();
                    var amount = $('#amount').val();
                    var candidateId = $('#empSelected').val();
                    var jobCode = $('#jobCode').val();
                    var action = 'add';
                    $.ajax({
                        type: "POST",
                        url: "./invoiceAddition.php",
                        data: {wkDate:wkDate,units:units,clientId:clientId,description:description,amount:amount,candidateId:candidateId,jobCode:jobCode,action:action},
                        dataType: "html",
                        success: function (data) {
                            $('.additionDisplay').html('');
                            $('.additionDisplay').html(data);
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','.testInvoiceBtn', function (ev) {
            ev.preventDefault();
            //console.log('TEST'+$(this).val());
            var jobCode = $('#jobCode').val();
            var invoiceDate = $('#invoiceDate').val();
            var weekendingDate = $('#weekendingDate').val();
            var payrollName = $('#payrollName :selected').val();
            var companyId = $('#companyId :selected').val();
            //var clientid = $('#clientid :selected').val();
            $.ajax({
                type: "POST",
                url: "./testAllClientInvoice.php",
                data: {
                    jobCode: jobCode,
                    weekendingDate: weekendingDate,
                    invoiceDate: invoiceDate,
                    payrollName: payrollName,
                    companyId: companyId
                },
                dataType: "html",
                success: function (data) {
                    if (data == 'exists') {
                        $('.error').html('invoices are generated previously for the weekending')
                    } else {
                        $('#createInvoiceBtn').show();
                       // window.open(data);
                    }
                }
            });
        });
        $(document).on('click','.createInvoiceBtn', function (ev) {
            //console.log('CREATE'+$(this).val());
            if($(this).val() == 'create') {
                var errorClass = 'invalid';
                var errorElement = 'em';
                var frmInvoice = $("#frmInvoice").validate({
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
                        weekendingDate: {
                            required: true
                        },
                        invoiceDate: {
                            required: true
                        },
                        payrollName: {
                            required: true
                        },
                        companyId: {
                            required: true
                        }
                    },
                    messages: {
                        weekendingDate: {
                            required: "Please select Weekending Date"
                        },
                        invoiceDate: {
                            required: "Please select Invoice Date"
                        },
                        payrollName: {
                            required: "Please enter Payroll Name"
                        },
                        companyId: {
                            required: "Please select Company"
                        }
                    },
                    submitHandler: function (form) {
                        var jobCode = $('#jobCode').val();
                        var invoiceDate = $('#invoiceDate').val();
                        var weekendingDate = $('#weekendingDate').val();
                        var payrollName = $('#payrollName :selected').val();
                        var companyId = $('#companyId :selected').val();
                        $.ajax({
                            type: "POST",
                            url: "./genAllClientInvoice.php",
                            data: {
                                jobCode: jobCode,
                                weekendingDate: weekendingDate,
                                invoiceDate: invoiceDate,
                                payrollName: payrollName,
                                companyId: companyId
                            },
                            dataType: "text",
                            success: function (data) {
                                console.log('....respone'+data);
                                if (data == 'exists') {
                                    $('.error').html('invoices are generated previously for the weekending')
                                } else {
                                    location.reload();
                                }
                            }
                        });
                    },
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.parent());
                    }
                });
            }
        });
        $(document).on('click','.reprintInvoiceBtn',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmReprint = $("#frmReprint").validate({
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
                    reprintDate: {
                        required: true
                    }
                },
                messages: {
                    reprintDate:{
                        required: "Please select Reprint Date"
                    }
                },
                submitHandler: function (form) {
                    var creationNo = $('#creationNo').val();
                    var reprintDate = $('#reprintDate :selected').val();
                    $.ajax({
                        type: "POST",
                        url: "./generateInvoiceAudit.php",
                        data: {creationNo:creationNo,reprintDate:reprintDate},
                        dataType: "html",
                        success: function (data) {
                            window.open(data);
                            console.log('INV'+data);
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>