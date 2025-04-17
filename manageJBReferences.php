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
    <div id="content" class="container-body" style="margin-top:50px;margin-bottom: 100px;">
        <h2>Add/Edit/Activate/Deactivate Job Board References</h2>
        <div class="error"></div>
        <div class="row" style="padding-left: 15px">
            <div class="pull-left">
                <input type="text" class="form-control" name="referenceCode" id="referenceCode" value="" placeholder="Reference Code"/>
                <input class="hidden" name="referenceId" id="referenceId" value="" />
            </div>
            <div class="pull-left">
                <button class="addBtn btn btn-default" name="addBtn"><i class="glyphicon fa-lg fa fa-plus"></i>Add</button>
                <button class="updateBtn btn btn-default" name="updateBtn"><i class="glyphicon fa-lg fa fa-pencil"></i>Update</button>
            </div>
        </div>
        <br>
        <div class="filterPanel">
            <table class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                    <th>Job Board/Vacancy Reference Code</th>
                    <th>Action</th>
                    <th>Status</th>
                </thead>
                <tbody class="referenceBody">
                </tbody>
            </table>
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
    $(document).ready(function(){
        displayReferences();
        $('.updateBtn').hide();
        function displayReferences(){
            $.ajax({
                url:"jobBoardReferenceProcessing.php",
                method:"POST",
                dataType:"html",
                success:function(data)
                {
                    $('.referenceBody').html('');
                    $('.referenceBody').html(data);
                }
            });
        }
        $(document).on('click','.editBtn',function(){
            $('.addBtn').hide();
            $('.updateBtn').show();
            var $row = $(this).closest("tr");
            var id = $row.find('.refCode').data('id');
            var refcode = $row.find('.refCode').data('refcode');
            $('#referenceCode').val(refcode);
            $('#referenceId').val(id);
        });
        $(document).on('click','.updateBtn',function(){
            var referenceCode = $('#referenceCode').val();
            var referenceId = $('#referenceId').val();
            var action = 'Update';
            $.ajax({
                url:"jobBoardReferenceProcessing.php",
                method:"POST",
                data:{referenceCode:referenceCode,referenceId:referenceId,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayReferences();
                }
            });
        });
        $(document).on('click','.addBtn',function(){
            var referenceCode = $('#referenceCode').val();
            var referenceId = $('#referenceId').val();
            var action  = 'Add';
            $.ajax({
                url:"jobBoardReferenceProcessing.php",
                method:"POST",
                data:{referenceCode:referenceCode,referenceId:referenceId,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayReferences();
                }
            });
        });
        $(document).on('click','.delBtn',function(){
            var $row = $(this).closest("tr");
            var referenceId = $row.find('.refCode').data('id');
            var action  = 'Delete';
            $.ajax({
                url:"jobBoardReferenceProcessing.php",
                method:"POST",
                data:{referenceId:referenceId,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayReferences();
                }
            });
        });
        $(document).on('click','.DeActivate',function(){
            var $row = $(this).closest("tr");
            var referenceCode = $row.find('.refCode').data('refcode');
            var action  = 'DeActivate';
            $.ajax({
                url:"jobBoardReferenceProcessing.php",
                method:"POST",
                data:{referenceCode:referenceCode,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayReferences();
                }
            });
        });
        $(document).on('click','.Activate',function(){
            var $row = $(this).closest("tr");
            var referenceCode = $row.find('.refCode').data('refcode');
            var action  = 'Activate';
            $.ajax({
                url:"jobBoardReferenceProcessing.php",
                method:"POST",
                data:{referenceCode:referenceCode,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayReferences();
                }
            });
        });
    });
</script>
</body>
</html>