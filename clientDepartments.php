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
            <div style="padding-left:30px;">
                <h2  class="semi-bold">Client Departments</h2>
            </div>
            <div style="width:100%">
                <div style="float:left; padding-left:20px;padding-bottom:50px; width:35%">
                    <form id="areasFrm" class="smart-form" method="post">
                        <header>
                            Add Areas of Work/Departments
                        </header>
                        <fieldset>
                            <div class="row">
                                <section class="col col-6">
                                    <label class="label">Select Client/Location</label>
                                    <label class="select">
                                        <select name="clientId" id="clientId" class="clientsMenu">
                                        </select> <i></i> </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-6">
                                    <label class="label">Select State</label>
                                    <label class="select">
                                        <select name="stateId" id="stateId">
                                            <?php
                                            $states = getStates($mysqli);
                                            foreach($states as $stat){
                                                ?>
                                                <option value="<?php echo $stat['stateId']; ?>" <?php if($stat['stateId'] == '2'){?>selected<?php } ?>><?php echo $stat['state'];?></option>
                                            <?php } ?>
                                        </select> <i></i> </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-6">
                                    <label class="input"> <i class="icon-append fa fa-building-o"></i>
                                        <input type="hidden" name="departmentId" id="departmentId" />
                                        <input type="text" name="department" id="department" placeholder="Areas of Work/Departments" class="areaGroup">
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-6">
                                    <label class="input"> <i class="icon-append fa fa-phone"></i>
                                        <input type="text" name="phone" id="phone" placeholder="Phone Number">
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-lg-12">
                                    <label class="textarea"><i class="icon-append fa fa-info"></i>
                                        <textarea name="note" id="note" placeholder="Notes" class="textarea" rows="5"></textarea>
                                    </label>
                                </section>
                            </div>
                            <div class="row">
                                <section class="col col-6">
                                    <div class="input-group-btn">
                                        <button class="addDept btn btn-primary btn-sm" type="submit" value="AddDept"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add Department</button>
                                        <button class="editDept btn btn-primary btn-sm" type="submit" value="UpdateDept"><i class="glyphicon glyphicon-pencil"></i>&nbsp;Update Department</button>
                                    </div>
                                </section>
                                <section class="col col-6"></section>
                            </div>
                        </fieldset>
                    </form>
                    <div style="float:left; padding-left:15px;padding-bottom:50px; width:35%">
                        <header>
                            Add/Assign Client Positions
                        </header>
                        <label for="depId">
                            <select name="depId" id="depId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt">
                            </select><i></i>
                        </label>
                        <label for="depId">
                            <select name="posId" id="posId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; font-size: 9pt">
                            </select><i></i>
                        </label>
                        <button class="addPositionBtn btn btn-primary btn-sm" type="submit" value="AddPosition"><i class="glyphicon glyphicon-plus"></i>&nbsp;Add/Client Position</button>
                    </div>
                </div>
                <div class="areasList" style="float:left; padding-left:20px;padding-bottom:50px; width:65%; overflow-y:scroll; height: 500px;">
                    <label class="input">Search By Client Name
                        <input type="text" id="clientSearch" name="clientSearch" class="text" placeholder="Search client"/>
                        <button type="button" id="searchBtn" class="button"><i class="icon-append fa fa-search"></i> </button>
                    </label>
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>State</th>
                                    <th>Area/Department/Role</th>
                                    <th>Positions</th>
                                    <th>Phone</th>
                                    <th>Notes</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="areaBody">
                            </tbody>
                        </table>
                </div>
                <div style="clear: both"></div>
            </div><br><br><br>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
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

        $('.editDept').hide();
        var clientSearch = $('#clientSearch').val();
        listDepartments(clientSearch);
        retrieveClients();
        getClients();
        populateClientDepartments();
        function populateClientDepartments(){
            $.ajax({
                url:"getClientDepartments.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#depId').html('');
                    $('#depId').html(data);
                }
            });
        }
        populateCandidatePositions();
        function populateCandidatePositions(){
            $.ajax({
                url:"getCandidatePositionList.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#posId').html('');
                    $('#posId').html(data);
                }
            });
        }
        function getClients(){
            var action = 'department';
            $.ajax({
                url:"getClients.php",
                type:"POST",
                dataType:"html",
                data:{action:action},
                success: function(data){
                    $('.clientsMenu').html('');
                    $('.clientsMenu').html(data);
                }
            });
        }
        function retrieveClients(){
            $.ajax({
                url:"retrieveClients.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('.clientBody').html('');
                    $('.clientBody').html(data);
                }
            });
        }

        function addDepartment(clientId,stateId,department,phone,note){
            $.ajax({
                url:"addDepartment.php",
                type:"POST",
                dataType:"html",
                data: {clientId : clientId, stateId : stateId, department : department, phone : phone, note : note},
                success: function(data){
                    $('.areaBody').html('');
                    $('.areaBody').html(data);
                }
            }).done(function(){
                location.reload();
            });
        }
        function listDepartments(clientSearch){
            $.ajax({
                url:"listDepartments.php",
                type:"POST",
                data: {clientSearch : clientSearch},
                dataType:"html",
                success: function(data){
                    $('.areaBody').html('');
                    $('.areaBody').html(data);
                }
            });
        }
        $(document).on('click','#searchBtn',function(){
            clientSearch = $('#clientSearch').val();
            listDepartments(clientSearch);
        });
        $(document).on('click', '.addDept', function(){
            var errorClass = 'invalid';
            var errorElement = 'em';
            var areasFrm = $("#areasFrm").validate({
                errorClass	: errorClass,
                errorElement	: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    department: {
                        require_from_group: [1, ".areaGroup"]
                    }
                },
                messages: {
                    department: {
                        required: "Please enter Area of Work/Department/Role"
                    }
                },
                submitHandler: function (form) {
                    var clientId = $('#clientId :selected').val();
                    var stateId = $('#stateId :selected').val();
                    var department = $('#department').val();
                    var phone = $('#phone').val();
                    var note = $('#note').val();
                    addDepartment(clientId,stateId,department,phone,note);
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','.removeDeptBtn', function(){
            var $row = $(this).closest("tr");
            var clientId = $row.find('.clid').data('clid');
            var stateId = $row.find('.stid').data('stid');
            var departmentId = $row.find('.id').data('id');
            $.ajax({
                url:"removeClientDepartment.php",
                type:"POST",
                dataType:"html",
                data: {departmentId : departmentId,clientId : clientId, stateId : stateId},
                success: function(data){
                    listDepartments(clientSearch);
                }
            });
        });
        $(document).on('click','.addPositionBtn', function(){
            var param = $('#depId :selected').val();
            var positionid = $('#posId :selected').val();
            var status = 'Add';
            console.log('param'+param+'PosId'+positionid);
            $.ajax({
                url:"updateClientPosition.php",
                type:"POST",
                dataType:"text",
                data: {param : param, positionid : positionid,status : status},
                success: function(data){
                    if(data == 'added'){
                        listDepartments(clientSearch);
                    }else if(data == 'error'){
                        listDepartments(clientSearch);
                    }else if(data == 'exists'){
                        listDepartments(clientSearch);
                    }
                    location.reload();
                }
            });
        });
        $(document).on('click','.posDelete', function(){
            var posid = $(this).closest('div').attr('data-posid');
            var clid = $(this).closest('div').attr('data-clid');
            var stid = $(this).closest('div').attr('data-stid');
            var depid = $(this).closest('div').attr('data-depid');
            var status = 'Delete';
            $.ajax({
                url:"updateClientPosition.php",
                type:"POST",
                dataType:"text",
                data: {posid : posid, clid : clid, stid : stid, depid : depid, status : status},
                success: function(data){
                    if(data == 'deleted'){
                        listDepartments(clientSearch);
                    }else if(data == 'error'){
                        listDepartments(clientSearch);
                    }
                }
            });
        });
        $(document).on('click', '.editDeptBtn', function(){
            $('.addDept').hide();
            $('.editDept').show();
            var $row = $(this).closest("tr");
            var clientId = $row.find('.clid').data('clid');
            var stateId = $row.find('.stid').data('stid');
            var departmentId = $row.find('.id').data('id');
            var department = $row.find('.id').data('department');
            var phone = $row.find('.phone').data('phone');
            var note = $row.find('.notes').data('note');
            $('#clientId').val(clientId).attr('disabled', true);
            $('#stateId').val(stateId).attr('disabled', true);
            $('#departmentId').val(departmentId);
            $('#department').val(department);
            $('#phone').val(phone);
            $('textarea#note').val(note);
        });
        function updateDepartment(clientId,stateId,departmentId,department,phone,note){
            $.ajax({
                url:"updateClientDepartment.php",
                type:"POST",
                dataType:"html",
                data: {clientId : clientId,stateId : stateId,departmentId : departmentId,department : department,phone : phone,note:note},
                success: function(data){
                    listDepartments(clientSearch);
                    $('.addDept').show();
                    $('.editDept').hide();
                    location.reload();
                }
            });
        }
        $(document).on('click', '.editDept', function(){
            var errorClass = 'invalid';
            var errorElement = 'em';
            var areasFrm = $("#areasFrm").validate({
                errorClass	: errorClass,
                errorElement	: errorElement,
                highlight: function(element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function(element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    department: {
                        require_from_group: [1, ".areaGroup"]
                    }
                },
                messages: {
                    department: {
                        required: "Please enter Area of Work/Department/Role"
                    }
                },
                submitHandler: function (form) {
                    var clientId = $('#clientId :selected').val();
                    var stateId = $('#stateId :selected').val();
                    var departmentId = $('#departmentId').val();
                    var department = $('#department').val();
                    var phone = $('#phone').val();
                    var note = $('textarea#note').val();
                    var candidateId = $('#canId').val();
                    updateDepartment(clientId,stateId,departmentId,department,phone,note);
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