<?php
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
            <div style="width:100%">
                <div style="float:left; padding-left:20px;padding-bottom:50px; width:70%">
                    <div style="padding-left:30px;">
                        <h2 class="semi-bold">Add/Remove Job Codes</h2>
                    </div>
                    <form id="positionFrm" class="smart-form" method="post">
                        <fieldset>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Client:</label>
                                    <label class="select">
                                        <select id="clientId" name="clientId">
                                        </select><i></i>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">Position:</label>
                                    <label class="select">
                                        <select id="positionid" name="positionid">
                                        </select><i></i>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Department:</label>
                                    <label class="select">
                                        <select id="deptId" name="deptId">
                                        </select><i></i>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">Award:</label>
                                    <label class="select">
                                        <select id="awardId" name="awardId">
                                            <?php echo getAwardsList($mysqli); ?>
                                        </select><i></i>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">&nbsp;</label>
                                    <button class="createJobCodeBtn btn btn-primary btn-sm" type="button" value="CreateJobCode"><i class="glyphicon glyphicon-plus"></i>&nbsp;Create JobCode</button>
                                </section>
                            </div>
                        </fieldset>
                    </form>
                    <div class="jobCodeList" style="float:left; padding-left:20px;padding-bottom:50px; width:90%; overflow-y:scroll; height: 900px;">
                        <label class="input">Search By Client Name
                            <input type="text" id="clientSearch" name="clientSearch" class="text" placeholder="Search client"/>
                            <button type="button" id="searchBtn" class="button"><i class="icon-append fa fa-search"></i> </button>
                        </label>
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>JobCode</th>
                                <th>ClientID</th>
                                <th>PositionID</th>
                                <th>DepartmentID</th>
                                <th>Client</th>
                                <th>Position</th>
                                <th>Award</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="jobCodeBody">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="float:left; padding-left:20px;padding-bottom:50px; width:30%">
                    <div style="padding-left:30px;">
                        <h2 class="semi-bold" id="jobdetailHeading">Job Details</h2>
                    </div>
                    <form id="frmJbDetails" class="smart-form" method="post">
                        <fieldset>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Job Code:</label>
                                    <label class="input">
                                        <input type="text" name="jobCode" id="jobCode" value="" class="input" readonly/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Client Code:</label>
                                    <label class="input">
                                        <input type="text" name="clientCode" id="clientCode" value="" class="input"/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">Client Name:</label>
                                    <label class="input">
                                        <input type="hidden" name="clId" id="clId" value="" class="input"/>
                                        <input type="text" name="clientName" id="clientName" value="" class="input"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Contact First Name:</label>
                                    <label class="input"><span class="error">*</span>
                                        <input type="text" name="contactFirstName" id="contactFirstName" value="" class="input"/>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">Contact Last Name:</label>
                                    <label class="input"><span class="error">*</span>
                                        <input type="text" name="contactLastName" id="contactLastName" value="" class="input"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-8">
                                    <label class="label">Description:</label>
                                    <label class="input"><span class="error">*</span>
                                        <input type="text" name="description" id="description" value="" class="input"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Start Date:</label>
                                    <label class="input">
                                        <input type="text" name="startDate" id="startDate" value="" class="input"/>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Payroll Name:</label>
                                    <label class="input">
                                        <select name="payrollName" id="payrollName" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                        </select>
                                    </label>
                                </section>
                            </div>
                        </fieldset>
                        <fieldset>
                            <legend>Address</legend>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Invoice To:</label>
                                    <label class="input">
                                        <textarea name="invoiceTo" id="invoiceTo" class="textarea"></textarea>
                                    </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">Work Address:</label>
                                    <label class="input">
                                        <textarea name="workAddress" id="workAddress" class="textarea"></textarea>
                                    </label>
                                </section>
                            </div>
                            <button class="addJobDetailBtn btn btn-primary btn-sm" type="submit" value="Add Job Detail"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Job Detail</button>
                            <button class="updateJobDetailBtn btn btn-primary btn-sm" type="submit" value="Update Job Detail"><i class="glyphicon glyphicon-pencil"></i>&nbsp;Update Job Detail</button>
                        </fieldset>
                    </form>
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
        loadPositions();
        var clientSearch = $('#clientSearch').val();
        $(document).on('click','#searchBtn',function(){
            clientSearch = $('#clientSearch').val();
            loadJobCodesForSearch(clientSearch);
        });
        loadProfitCentres();
        $('.updateJobDetailBtn').hide();
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
                loadDepartments();
                loadJobCodes($('#clientId option:selected').val());
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
                $('#positionid').html('');
                $('#positionid').html(data);
            });
        }
        function loadDepartments(){
            var clientid = $('#clientId :selected').val();
            var action = 'DEPARTMENTSFORJOBCODE';
            $.ajax({
                url :"getClientDepartmentsList.php",
                type:"POST",
                data:{clientid:clientid,action:action},
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#deptId').html('');
                $('#deptId').html(data);
            });
        }
        function loadJobCodes(clId){
            $.ajax({
                url :"loadJobCodes.php",
                type:"POST",
                data:{ clId: clId },
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('.jobCodeBody').html('');
                $('.jobCodeBody').html(data);
                loadDepartments();
            });
        }
        function loadJobCodesForSearch(clientSearch){
            $.ajax({
                url :"loadJobCodes.php",
                type:"POST",
                data:{ clientSearch: clientSearch },
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                console.log('LOAD JOBCODES BY SEARCH...'+data);
                $('.jobCodeBody').html('');
                $('.jobCodeBody').html(data);
            });
        }
        function loadProfitCentres(){
            var action = 'GET';
            $.ajax({
                url :"getProfitCentre.php",
                type:"POST",
                data:{action:action},
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('#prCentre').html('');
                $('#prCentre').html(data);
            });
        }
        getPayrollNames();
        function getPayrollNames(){
            $.ajax({
                type: "POST",
                url: "getPayrollNames.php",
                dataType: "html",
                success: function (data) {
                    $('#payrollName').html('');
                    $('#payrollName').html(data);
                }
            });
        }
        $(document).on('change','#clientId', function(){
            loadJobCodes($('#clientId option:selected').val());
        });
        $(document).on('click','#clientId', function(){
            loadJobCodes($('#clientId option:selected').val());
        });
        $(document).on('click','.createJobCodeBtn', function(){
            let clientId = $('#clientId option:selected').val();
            let positionId = $('#positionid option:selected').val();
            let deptId = $('#deptId :selected').val();
            let awardId = $('#awardId option:selected').val();
            $.ajax({
                url :"generateJobCode.php",
                type:"POST",
                dataType:"html",
                data:{clientId : clientId,positionId : positionId,deptId:deptId,awardId:awardId},
                success: function(data) {
                    $('.jobCodeBody').html('');
                    $('.jobCodeBody').html(data);
                    location.reload();
                }
            });
        });
        $(document).on('click', '.deleteJobCodeBtn', function(){
            var jobCode = $(this).closest('td').attr('data-jobcode');
            $.ajax({
                url:"removeJobCode.php",
                type:"POST",
                dataType:"html",
                data:{jobCode : jobCode},
                success:function(){}
            }).done(function(data){
                $('.jobCodeBody').html('');
                $('.jobCodeBody').html(data);
            });
        });
        $(document).on('click','.updateAwardBtn', function(){
            var awId = $(this).closest('td').find('.awId :selected').val();
            var jobcode = $(this).closest('tr').find('.jbc').attr('data-jbcode');
            $.ajax({
                url: "updateJobCodeAward.php",
                type: "POST",
                data: {awId:awId,jobcode: jobcode},
                success: function (data) {
                    location.reload();
                }
            });
        });
        $(document).on('click','.editJobDetailBtn',function () {
            var jobcode = $(this).closest('td').attr('data-jobcode');
            $('#jobCode').val(jobcode);
            $.ajax({
                url:"getJobDetail.php",
                type:"POST",
                dataType:"json",
                data:{jobcode : jobcode},
                success:function(data){
                    if(data.length>0) {
                        $('#jobdetailHeading').html('');
                        $('#jobdetailHeading').html('Edit Job Detail');
                        $.each(data, function (index, element) {
                            $('#jobCode').val(element.jobcode);
                            $('#clientCode').val(element.clientCode);
                            $('#clId').val(element.clId);
                            $('#clientName').val(element.clientName);
                            $('#contactFirstName').val(element.contactFirstName);
                            $('#contactLastName').val(element.contactLastName);
                            $('#description').val(element.description);
                            $('#startDate').val(element.startDate);
                            $('#payrollName').val(element.payrollName);
                            $('#invoiceTo').val(element.invoiceTo);
                            $('#workAddress').val(element.workAddress);
                        });
                        $('.addJobDetailBtn').hide();
                        $('.updateJobDetailBtn').show();
                    }else{
                        $('#jobdetailHeading').html('');
                        $('#jobdetailHeading').html('New Job Detail');
                    }
                }
            });
        });
        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            drops:'up'
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false,
            drops:'up'
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $(document).on('click','.jbLink', function () {
            $('#frmJbDetails').trigger("reset");
            $('#jobdetailHeading').html('');
            $('#jobdetailHeading').html('New Job Detail');
            $('#jobCode').val($(this).closest('td').attr('data-jbCode'));
            $('#clId').val($(this).closest('td').attr('data-clId'));
            $('#clientName').val($(this).closest('td').attr('data-clName'));
            $('#clientCode').val($(this).closest('td').attr('data-clientCode'));
        });
        $(document).on('click', '.addJobDetailBtn', function(evt) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmJbDetails = $("#frmJbDetails").validate({
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
                    contactFirstName: {
                        required:true
                    },
                    contactLastName: {
                        required:true
                    },
                    accountManager:{
                        required:true
                    },
                    description:{
                        required:true
                    }
                },
                messages: {
                    contactFirstName: {
                        required: "Please enter contact First Name"
                    },
                    contactLastName: {
                        required: "Please enter contact Last Name"
                    },
                    accountManager: {
                        required: "Please enter account Manager"
                    },
                    description:{
                        required: "Please enter description"
                    }

                },
                submitHandler: function (form) {
                    var jobCode = $('#jobCode').val();
                    var clientId = $('#clId').val();
                    var clientCode = $('#clientCode').val();
                    var clientName = $('#clientName').val();
                    var contactFirstName = $('#contactFirstName').val();
                    var contactLastName = $('#contactLastName').val();
                    var description =$('#description').val();
                    var startDate = $('#startDate').val();
                    var payrollName = $('#payrollName :selected').val();
                    var invoiceTo = $('textarea#invoiceTo').val();
                    var workAddress = $('textarea#workAddress').val();


                    $.ajax({
                        url:"saveJobDetail.php",
                        type:"POST",
                        dataType:"html",
                        data:{jobCode : jobCode,clientId:clientId,clientCode:clientCode,clientName:clientName,contactFirstName:contactFirstName,contactLastName:contactLastName,description:description,startDate:startDate,payrollName:payrollName,invoiceTo:invoiceTo,workAddress:workAddress},
                        success:function(data){
                            if(data == 'Added'){
                                location.reload();
                            }
                        }
                    }).done(function(data){
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });

        $(document).on('click','.updateJobDetailBtn',function(evt){
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmJbDetails = $("#frmJbDetails").validate({
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
                    contactFirstName: {
                        required:true
                    },
                    contactLastName: {
                        required:true
                    },
                    accountManager:{
                        required:true
                    },
                    description:{
                        required:true
                    }
                },
                messages: {
                    contactFirstName: {
                        required: "Please enter contact First Name"
                    },
                    contactLastName: {
                        required: "Please enter contact Last Name"
                    },
                    accountManager: {
                        required: "Please enter account Manager"
                    },
                    description:{
                        required: "Please enter description"
                    }

                },
                submitHandler: function (form) {
                    var jobCode = $('#jobCode').val();
                    var clientId = $('#clId').val();
                    var clientCode = $('#clientCode').val();
                    var clientName = $('#clientName').val();
                    var contactFirstName = $('#contactFirstName').val();
                    var contactLastName = $('#contactLastName').val();
                    var description =$('#description').val();
                    var startDate = $('#startDate').val();
                    var payrollName = $('#payrollName :selected').val();
                    var invoiceTo = $('textarea#invoiceTo').val();
                    var workAddress = $('textarea#workAddress').val();
                    var updateStatus = 'update';
                    $.ajax({
                        url:"saveJobDetail.php",
                        type:"POST",
                        dataType:"text",
                        data:{jobCode:jobCode,clientId:clientId,clientCode:clientCode,clientName:clientName,contactFirstName:contactFirstName,contactLastName:contactLastName,description:description,startDate:startDate,payrollName:payrollName,invoiceTo:invoiceTo,workAddress:workAddress,updateStatus:updateStatus},
                        success:function(data){
                            location.reload();
                        }
                    }).done(function(data){
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
</body>

</html>