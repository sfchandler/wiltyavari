<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'ADMIN')
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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Attribute Management</h2>
        <div class="error"></div>
        <div class="row" style="padding-left: 15px">
            <section class="col col-4">
                <input class="input" name="attributeName" id="attributeName" value="" placeholder="Attribute Name"/><input class="input" name="attributeCode" id="attributeCode" value="" placeholder="Attribute Code"/><input type="hidden" name="attributeId" id="attributeId"/><button class="addBtn btn btn-default" name="addBtn"><i class="glyphicon fa-lg fa fa-plus"></i>Add</button><button class="updateBtn btn btn-default" name="updateBtn"><i class="glyphicon fa-lg fa fa-pencil"></i>Update</button>
            </section>
        </div>
        <div class="filterPanel">
            <table class="table table-striped table-bordered table-hover" width="100%">
                <thead>
                    <th>Attribute Name</th>
                    <th>Action</th>
                </thead>
                <tbody class="attrBody">
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
        displayAttributes();
        $('.updateBtn').hide();
        function displayAttributes(){
            $.ajax({
                url:"listAttributes.php",
                method:"POST",
                dataType:"html",
                success:function(data)
                {
                    $('.attrBody').html('');
                    $('.attrBody').html(data);
                }
            });
        }
        $(document).on('click','.editBtn',function(){
            $('.addBtn').hide();
            $('.updateBtn').show();
            var $row = $(this).closest("tr");
            var attrId = $row.find('.attr').data('attr');
            var attrType = $row.find('.attr').data('attrtype');
            var attrCode = $row.find('.attr').data('attrcode');
            $('#attributeName').val(attrType);
            $('#attributeCode').val(attrCode);
            $('#attributeId').val(attrId);
        });
        $(document).on('click','.updateBtn',function(){
            var attributeName = $('#attributeName').val();
            var attributeId = $('#attributeId').val();
            var attributeCode = $('#attributeCode').val();
            var action = 'Update';
            $.ajax({
                url:"listAttributes.php",
                method:"POST",
                data:{attributeName:attributeName,attributeId:attributeId,attributeCode:attributeCode,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayAttributes();
                }
            });
        });
        $(document).on('click','.addBtn',function(){
            var attributeName = $('#attributeName').val();
            var attributeId = $('#attributeId').val();
            var attributeCode = $('#attributeCode').val();
            var action  = 'Add';
            $.ajax({
                url:"listAttributes.php",
                method:"POST",
                data:{attributeName:attributeName,attributeId:attributeId,attributeCode:attributeCode,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayAttributes();
                }
            });
        });
        $(document).on('click','.delBtn',function(){
            var $row = $(this).closest("tr");
            var attrId = $row.find('.attr').data('attr');
            var action  = 'Delete';
            $.ajax({
                url:"listAttributes.php",
                method:"POST",
                data:{attrId:attrId,action:action},
                dataType:"html",
                success:function(data)
                {
                    displayAttributes();
                }
            });
        });
    });
</script>
</body>

</html>