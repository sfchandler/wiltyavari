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
    <title></title></head>
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
                <h2  class="semi-bold">Clients Supervisor Details</h2>
            </div>
            <div class="error"></div>
            <div style="width:100%">
                <div style="padding-left:20px;padding-bottom:50px;">
                    <form id="frmSupervisor" action="" class="smart-form" method="post">
                        <div class="row">
                            <fieldset>
                                <section class="col col-12">
                                    <div class="pull-left">
                                        <label for="departmentId" class="select">
                                            <select name="departmentId" id="departmentId"  class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                                            </select><i></i></label>
                                    </div>
                                    <div class="pull-left">
                                        <label for="supervisorName" class="input">
                                            <input id="supervisorName" name="supervisorName" type="text"  class="pull-left" placeholder="Supervisor Name"/>
                                        </label>
                                    </div>
                                    <div class="pull-left">
                                        <label for="supervisorPhone" class="input">
                                            <input id="supervisorPhone" name="supervisorPhone" type="tel"  class="pull-left" placeholder="Supervisor Phone"/>
                                        </label>
                                    </div>
                                    <div class="pull-left">
                                        <label for="supervisorEmail" class="input">
                                            <input id="supervisorEmail" name="supervisorEmail" type="email"  class="pull-left" placeholder="Supervisor Email"/>
                                        </label>
                                    </div>
                                    <div class="pull-left">
                                        <label for="supervisorPassword" class="input">
                                            <input id="supervisorPassword" name="supervisorPassword" type="password"  class="pull-left" placeholder="Supervisor Password"/>
                                        </label>
                                    </div>
                                    <div class="pull-left">
                                        <input type="hidden" name="supervisorId" id="supervisorId" value=""/><button class="addSupervisorBtn btn btn-primary btn-sm" type="submit"><i class="glyphicon glyphicon-user"></i>&nbsp;Add/Update Supervisor</button>
                                    </div>
                                </section>
                            </fieldset>
                        </div>
                    </form>
                    <div class="row">
                        <table id="supervisorsTbl" border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="100%">
                            <thead>
                            <tr>
                                <th data-class="expand"><i class="fa fa-fw fa-user txt-color-blue hidden-md hidden-sm hidden-xs"></i>NAME</th>
                                <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>PHONE</th>
                                <th data-hide="phone"><i class="fa fa-fw fa-mobile txt-color-blue hidden-md hidden-sm hidden-xs"></i>EMAIL</th>
                                <th data-hide="phone"><i class="fa fa-fw fa-remove txt-color-blue hidden-md hidden-sm hidden-xs"></i>ACTION</th>
                            </tr>
                            </thead>
                            <tbody class="supervisorsList">

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
        var status = 'add';
        populateClientDepartments();
        function populateClientDepartments(){
            $.ajax({
                url:"getClientDepartments.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#departmentId').html('');
                    $('#departmentId').html(data);
                }
            });
        }
        $(document).on('change','#departmentId',function () {
            var param = $('#departmentId :selected').val();
            getSupervisorsList(param);
        });
        function getSupervisorsList(param){
            $.ajax({
                url:"getSupervisorsList.php",
                type:"POST",
                data:{param:param},
                dataType:"html",
                success: function(data){
                    $('.supervisorsList').html('');
                    $('.supervisorsList').html(data);
                }
            });
        }
        function deleteSupervisor(supervisorId,param,deleteStatus){
            $.ajax({
                url:"supervisorUpdate.php",
                type:"POST",
                data:{supervisorId:supervisorId,deleteStatus:deleteStatus,param:param},
                dataType:"html",
                success: function(data){
                    $('.supervisorsList').html('');
                    $('.supervisorsList').html(data);
                }
            });
        }
        $(document).on('click','.editSupervisorBtn', function(){
            $('#supervisorId').val($(this).closest('td').attr('data-supervisorId'));
            $('#supervisorName').val($(this).closest('td').attr('data-supervisorName'));
            $('#supervisorPhone').val($(this).closest('td').attr('data-supervisorPhone'));
            $('#supervisorEmail').val($(this).closest('td').attr('data-supervisorEmail'));
            status = $(this).closest('td').attr('data-status');
        });
        $(document).on('click','.deleteSupervisorBtn',function () {
            var supervisorId = $(this).closest('td').attr('data-supervisorId');
            var param = $('#departmentId :selected').val();
            var deleteStatus = 1;
            deleteSupervisor(supervisorId,param,deleteStatus);
        });

        function updateSupervisor(supervisorId,param,supervisorName,supervisorPhone,supervisorEmail,supervisorPassword,status){
            $.ajax({
                url:"supervisorUpdate.php",
                type:"POST",
                data:{supervisorId:supervisorId,param:param,supervisorName:supervisorName,supervisorPhone:supervisorPhone,supervisorEmail:supervisorEmail,supervisorPassword:supervisorPassword,status:status},
                dataType:"html",
                success: function(data){
                    $('.error').html('Supervisor information updated and email generated');
                    $('.supervisorsList').html('');
                    $('.supervisorsList').html(data);
                }
            });
        }
       /* $(document).on('click', '.editSupervisorBtn', function(){
            $supervisorId = $(this).closest('td').attr('data-supervisorId');
            $('#supervisorId').val('');
            $('#supervisorId').val($supervisorId);
        });*/


        var errorClass = 'invalid';
        var errorElement = 'em';

        $("#frmSupervisor").validate({
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
                supervisorName: {
                    required: true
                },
                supervisorPhone:{
                    required: true
                },
                supervisorEmail: {
                    required: true
                },
                supervisorPassword: {
                    required: true
                }
            },
            messages: {
                supervisorName: {
                    required: "Please enter a name"
                },
                supervisorPhone:{
                    required: "Please enter phone number"
                },
                supervisorEmail:{
                    required: "Please enter email address"
                },
                supervisorPassword:{
                    required: "Please enter password"
                }
            },
            submitHandler: function (form) {
                var param = $('#departmentId :selected').val();
                var supervisorName = $('#supervisorName').val();
                var supervisorPhone = $('#supervisorPhone').val();
                var supervisorEmail = $('#supervisorEmail').val();
                var supervisorId = $('#supervisorId').val();
                var supervisorPassword = $('#supervisorPassword').val();
                console.log(supervisorId+param+supervisorName+supervisorPhone+supervisorEmail);
                updateSupervisor(supervisorId, param, supervisorName, supervisorPhone,supervisorEmail,supervisorPassword,status);
            },
            errorPlacement: function (error, element) {
                error.insertAfter(element.parent());
            }
        });
    });
</script>
</body>

</html>