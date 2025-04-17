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
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Survey Document Listing</h2>
        <div class="error"></div>
        <div class="selectPanel">
            <fieldset class="smart-form">
            <div class="row">
                <section class="col col-3">
                    <label class="input">
                        <label for="cons_id" class="input">Consultant Name:
                            <select id="cons_id" name="cons_id" type="text" class="form-control">
                                <?php echo getConsultantListDropdown($mysqli); ?>
                            </select>
                        </label>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-6">
                    <label for="listDocuments">
                        <button name="listDocuments" id="listDocuments" class="listDocuments btn btn-info btn-sm"><i class="glyphicon glyphicon fa fa-file-pdf-o"></i>&nbsp;&nbsp;View List </button>
                    </label>
                </section>
            </div>
            </fieldset>
        </div>
        <div class="documentList" style="height: 450px; overflow-y: scroll">

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
    $(document).ready(function(){
        /* AJAX loading animation */
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        /* -  end  -*/
        $(document).on('click','.listDocuments',function () {
            var cons_id =  $('#cons_id').val();
            var action = 'VIEW';
            $.ajax({
                url :"getSurveyDocuments.php",
                type:"POST",
                data:{cons_id:cons_id,action:action},
                dataType:"html",
                success: function(data) {
                    $('.documentList').html('');
                    $('.documentList').html(data);
                }
            });
        });

    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>