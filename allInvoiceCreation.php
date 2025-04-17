<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 5/10/2017
 * Time: 1:03 PM
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
    <div id="content" class="container-body" style="margin-bottom: 50px; width: 100%">
        <div>
            <h2>Invoice Creation for all Clients</h2>
            <div class="error"></div>
            <form name="frmInvoice" id="frmInvoice" class="smart-form" method="post">
                <div class="row">
                    <section class="col col-3">
                        <label for="jobCode" class="input">Job Code:
                            <input type="text" name="jobCode" id="jobCode" value="" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%" placeholder="Enter Job Code"/>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="weekendingDate" class="input">Weekending Date
                            <input type="text" name="weekendingDate" id="weekendingDate" value="" class="pull-left" placeholder="Weekending Date" readonly/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="invoiceDate" class="input">Invoice Date
                            <input type="text" name="invoiceDate" id="invoiceDate" value="" class="pull-left" placeholder="Invoice Date" readonly/>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="resultLength" class="input">Number of Clients
                            <input type="text" name="resultLength" id="resultLength" readonly>
                        </label>
                    </section>
                    <section class="col col-1">
                        <label for="resultBreak" class="input">Break
                            <select name="resultBreak" id="resultBreak" class="form-control">
                                <option value=""></option>
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="15">15</option>
                                <option value="20">20</option>
                                <option value="25">25</option>
                                <option value="30">30</option>
                                <option value="35">35</option>
                                <option value="40">40</option>
                                <option value="45">45</option>
                                <option value="50">50</option>
                            </select>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="payrollName" class="input">Payroll Name:
                            <select name="payrollName" id="payrollName" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="clId" class="input">Client:
                            <select name="clId" id="clId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                            </select>
                        </label>
                    </section>
                    <section class="col col-3">
                        <label for="companyId" class="input">Company:
                            <select name="companyId" id="companyId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                <option value="">Select Company</option>
                                <?php echo getCompanyDropdown($mysqli); ?>
                            </select>
                        </label>
                    </section>
                    <section class="col col-3">
                        <div class="compLogo"></div>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label>
                            <button name="testInvoiceBtn" id="testInvoiceBtn" class="testInvoiceBtn btn btn-secondary btn-square btn-sm" value="test"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Test Invoice</button>
                            <button name="createInvoiceBtn" id="createInvoiceBtn" class="createInvoiceBtn btn btn-primary btn-square btn-sm" value="create"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp; Create Invoice</button>
                        </label>
                    </section>
                </div>
            </form>
            <div>
                    <div style="padding-bottom:20px; overflow-y: scroll; height: 400px;">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <th>WeekendingDate</th>
                            <th>ClientCode</th>
                            <th>Client Name</th>
                            <th>Invoice File</th>
                            <th>Generated Date</th>
                            <th>Sent Date</th>
                            <th>Timesheet</th>
                            <th>Email Addresses</th>
                            <th>Action</th>
                            </thead>
                            <tbody class="invoicePathDisplay"></tbody>
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
            var weekendingDate = $('#weekendingDate').val();
            var payrollName = $('#payrollName').val();
            var action = 'LENGTH';
            $.ajax({
                url: "invoice_check.php",
                type: "POST",
                dataType: "html",
                data:{weekendingDate : weekendingDate, payrollName : payrollName,action:action},
                success: function(data){
                    $('#resultLength').val('');
                    $('#resultLength').val(data);
                }
            });

        });

        $('input[name="invoiceDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="invoiceDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            $('#invoiceDate').val(picker.startDate.format('YYYY-MM-DD'));
            var weekendingDate = $('#weekendingDate').val();
            var payrollName = $('#payrollName').val();
            var action = 'CLIENT';
            $.ajax({
                url: "invoice_check.php",
                type: "POST",
                dataType: "html",
                data:{weekendingDate : weekendingDate, payrollName : payrollName,action:action},
                success: function(response){
                    $('#clId').html('');
                    $('#clId').html(response);
                }
            });
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
            let invoiceDate = $(this).closest('td').prev().prev().prev().prev().prev().find('.inv_date').text();
            let wk_date = $(this).closest('td').prev().prev().prev().prev().prev().find('.wk_date').text();
            let form = $(this).closest('.frmEmailSend').find('form').attr('id');
            let id = $(this).closest('td').find('#invId').val();
            var invCheckboxes = new Array();
            $(this).closest('td').prev().find('.clEmail:checkbox:checked').each(function () {
                invCheckboxes.push($(this).val());
            });
            let invTimesheet1 = $(this).closest('td').prev().prev().find('.invTimesheet1')[0].files[0];
            let invTimesheet2 = $(this).closest('td').prev().prev().find('.invTimesheet2')[0].files[0];
            let invTimesheet3 = $(this).closest('td').prev().prev().find('.invTimesheet3')[0].files[0];
            let invPath = $(this).closest('td').prev().prev().prev().prev().prev().find('.invPath').attr('href');
            var formData = new FormData(form);
            formData.append('invTimesheet1',invTimesheet1);
            formData.append('invTimesheet2',invTimesheet2);
            formData.append('invTimesheet3',invTimesheet3);
            formData.append('invCheckboxes',invCheckboxes);
            formData.append('invPath',invPath);
            formData.append('invoiceDate',invoiceDate);
            formData.append('wk_date',wk_date);
            formData.append('id',id);
            //console.log('wk_date'+wk_date+'id '+id+'invPath'+invPath+'invoiceDate'+invoiceDate);
            $.ajax({
                type: "POST",
                url: "./sendInvoices.php",
                dataType: "text",
                data: formData,
                mimeType: "multipart/form-data",
                contentType: false,
                processData: false,
                success: function (data) {
                    console.log('data '+data);
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
            var resultBreak = $('#resultBreak :selected').val();
            var clId = $('#clId :selected').val();
            $.ajax({
                type: "POST",
                url: "./testAllClientInvoice.php",
                data: {
                    jobCode: jobCode,
                    weekendingDate: weekendingDate,
                    invoiceDate: invoiceDate,
                    payrollName: payrollName,
                    companyId: companyId,
                    resultBreak:resultBreak,
                    clId:clId
                },
                dataType: "json",
                success: function (data) {
                    /*if (data == 'exists') {
                        $('.error').html('invoices are generated previously for the weekending')
                    } else {

                    }*/
                    $.each(data, function(key,value) {
                        //console.log('key'+key+'val'+value);
                        window.open(value);
                    });
                    $('#createInvoiceBtn').show();

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