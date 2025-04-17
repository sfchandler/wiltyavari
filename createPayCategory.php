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
                <h2 class="semi-bold">Add/Remove Pay Category</h2>
            </div>
            <div style="padding-left:30px;" class="error"></div>
            <div style="width:100%">
                <div style="float:left; padding-left:20px;padding-bottom:50px; width:50%">
                    <form id="payCatFrm" class="smart-form" method="post">
                        <fieldset>
                            <div class="row">
                                <section class="col col-4">
                                    <label class="label">Pay Category:</label>
                                        <label class="input"> <i class="icon-append fa fa-indent"></i>
                                            <input type="text" name="payCategory" id="payCategory" placeholder="Pay Category">
                                        </label>
                                </section>
                                <section class="col col-4">
                                    <label class="label">&nbsp;</label>
                                    <button class="payCatBtn btn btn-primary btn-sm" type="button" value="CreatePayCategory"><i class="glyphicon glyphicon-plus"></i>&nbsp;Create Pay Category</button>
                                </section>
                            </div>
                        </fieldset>
                    </form>
                    <div class="payCategoryList">
                        <table width="100%" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Pay Category Code</th>
                                    <th>Pay Category Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="payCatBody">
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
        $(document).bind('keypress', function(e) {
            if(e.target.tagName != 'TEXTAREA') {
                if(e.keyCode==13){
                    e.preventDefault();
                    $('.payCatBtn').trigger('click');
                }
            }
        });
        loadPayCategories();
        function loadPayCategories(){
            $.ajax({
                url :"loadPayCategories.php",
                type:"POST",
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('.payCatBody').html('');
                $('.payCatBody').html(data);
            });
        }
        $(document).on('click','.payCatBtn', function(){
            var payCategory = $('#payCategory').val();
            if($('#payCategory').val().length == 0){
                $('.error').html('');
                $('.error').html('Please enter Pay Category Name');
            }else{
                $.ajax({
                    url :"addPayCategory.php",
                    type:"POST",
                    dataType:"html",
                    data:{payCategory : payCategory},
                    success: function(data) {
                        $('.payCatBody').html('');
                        $('.payCatBody').html(data);
                    }
                });
            }

        });
        $(document).on('click', '.deletePayCatBtn', function(){
            var payCatCode = $(this).closest('td').attr('data-paycatcode');
            $.ajax({
                url:"removePayCategory.php",
                type:"POST",
                dataType:"html",
                data:{payCatCode : payCatCode},
                success:function(data){
                }
            }).done(function(data){
                $('.payCatBody').html('');
                $('.payCatBody').html(data);
            });
        });
    });
</script>
</body>

</html>