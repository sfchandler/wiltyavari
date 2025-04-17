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
            <div class="error"></div>
            <div style="float: left; width: 50%">
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
                            <label class="pull-left">JOBCODE:&nbsp;</label><label id="jobCodeLabel"></label>
                            <input type="hidden" id="jobCode" name="jobCode" value=""/>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label for="employeeName" class="input">Select Employee
                                <input id="employeeName" name="employeeName" type="text" placeholder="Employee Name"/>
                            </label><input type="hidden" name="empSelected" id="empSelected"/>
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
                    <div style="padding-bottom:20px; overflow-y: scroll; height: 300px;">
                        <table border="1" cellpadding="2" cellspacing="2" class="invoiceAddition table table-striped table-bordered table-hover sortable">
                            <thead>
                            <th>Client</th>
                            <th>WK Date</th>
                            <th>Description</th>
                            <th>Units</th>
                            <th>Amount</th>
                            <th>EmpId</th>
                            <th>JobCode</th>
                            <th>Action</th>
                            </thead>
                            <tbody class="additionDisplay"></tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="float: left; width: 50%">
            <h2>Invoice Creation</h2>
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
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label for="payrollName" class="input">Payroll Name:
                            <select name="payrollName" id="payrollName" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
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
            <div class="invoiceReprint">
                <form name="frmReprint" id="frmReprint" class="smart-form" method="post">
                    <div class="row">
                        <section class="col col-12">
                            <div style="padding-bottom:20px; overflow-y: scroll; height: 300px;">
                                <table border="1" cellpadding="2" cellspacing="2" class="invoicePaths table table-striped table-bordered table-hover sortable">
                                    <thead>
                                    <th>WeekendingDate</th>
                                    <th>Invoice File</th>
                                    <th>Generated Date</th>
                                    </thead>
                                    <tbody class="invoicePathDisplay"></tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                    <!--<div class="row">
                        <section class="col col-3">
                            <label for="reprintInvoiceBtn">
                                <button name="reprintInvoiceBtn" id="reprintInvoiceBtn" class="reprintInvoiceBtn btn btn-danger btn-square btn-sm"><i class="glyphicon glyphicon fa fa-print"></i>&nbsp; Reprint Audit Report</button>
                            </label>
                        </section>
                    </div>-->
                </form>
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
                    console.log('data'+wkDate+units+clientId+description+amount+candidateId+jobCode);
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

                        $.ajax({
                            type: "POST",
                            url: "./invoiceTest.php",
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
                                    window.open(data);
                                }
                            }
                        });
        });
        $(document).on('click','.createInvoiceBtn', function (ev) {
            console.log('CREATE'+$(this).val());
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
                            url: "./generateInvoices.php",
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
                                    window.open(data);
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