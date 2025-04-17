<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Australia/Melbourne');
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consultants = getConsultants($mysqli);
$activityTypes = getActivityList($mysqli);
$sessionId = session_id();
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
        <div style="height: 100%; background-color: white;">
                <div>
                    <h2>Interview Email Content</h2>
                    <div class="erMsg"></div>

                        <fieldset>
                            <div>To add Interview date and time to content, copy & paste: &nbsp; INTERVIEWTIME </div>
                            <br>
                            <div>To add first name and last name of candidate to mail content, copy & paste:&nbsp; FIRSTNAME &nbsp; LASTNAME </div>
                            <?php
                            $mailArray = getInterviewEmailContent($mysqli);
                            foreach($mailArray as $mc){
                            ?>
                            <form name="frmMail<?php echo $mc['accountName']; ?>" id="frmMail<?php echo $mc['accountName']; ?>" class="smart-form" method="post">
                            <div class="row">
                                <section class="col col-6">
                                    <label for="<?php echo $mc['accountName']; ?>">Enter Interview Email Content for <?php echo $mc['accountName']; ?></label>
                                    <textarea id="<?php echo $mc['accountName']; ?>" name="<?php echo $mc['accountName']; ?>"><?php echo $mc['mailContent']; ?></textarea>
                                    <input type="hidden" name="account<?php echo $mc['accountName']; ?>" id="account<?php echo $mc['accountName']; ?>" value="<?php echo $mc['accountName']; ?>"/>
                                </section>
                                <section class="col col-6">
                                    <button name="<?php echo $mc['accountName']; ?>Btn" id="<?php echo $mc['accountName']; ?>Btn" class="<?php echo $mc['accountName']; ?>Btn btn btn-primary btn-lg"><i class="glyphicon glyphicon fa fa-envelope-o"></i>&nbsp;Save</button>
                                </section>
                            </div>
                            </form>
                            <?php } ?>
                            <hr>
                            <br>
                        </fieldset>

                </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->

<!-- PAGE FOOTER -->
<?php include "template/footer.php"; ?>
<!-- END PAGE FOOTER -->
<!-- END SHORTCUT AREA -->
<?php include "template/scripts.php"; ?>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<!-- TINYMCE PLUGIN -->
<script src='js/tinymce/js/tinymce/tinymce.min.js'></script>
<script type="text/javascript">
    tinymce.init({
        selector: '#melbourne',
        height: 500,
        theme: 'modern',
        plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
    tinymce.init({
        selector: '#sydney',
        height: 500,
        theme: 'modern',
        plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
    tinymce.init({
        selector: '#queensland',
        height: 500,
        theme: 'modern',
        plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
        toolbar1: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
        image_advtab: true,
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ]
    });
    runAllForms();
    $(function() {
        $(document).on('click','#melbourneBtn',function (evt) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var $frmMail = $("#frmMailmelbourne").validate({
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
                    content: {
                        required: true
                    }
                },
                messages: {
                    content:{
                        required: "Please enter mail content"
                    }
                },
                submitHandler: function (form) {
                    var content = $('textarea#melbourne').val();
                    var account = $('#accountmelbourne').val();
                    $.ajax({
                        url:"updateInterviewContent.php",
                        type:"POST",
                        data:{account:account,content:content},
                        dataType:"text",
                        success: function(data) {
                            if (data == 'Updated'){
                                location.reload();
                            }else{
                                $('#erMsg').html('');
                                $('#erMsg').html(data);
                            }
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','#sydneyBtn',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var $frmMail = $("#frmMailsydney").validate({
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
                    content: {
                        required: true
                    }
                },
                messages: {
                    content:{
                        required: "Please enter mail content"
                    }
                },
                submitHandler: function (form) {
                    var content = $('textarea#sydney').val();
                    var account = $('#accountsydney').val();
                    $.ajax({
                        url:"updateInterviewContent.php",
                        type:"POST",
                        data:{account:account,content:content},
                        dataType:"text",
                        success: function(data) {
                            if (data == 'Updated'){
                                location.reload();
                            }else{
                                $('#erMsg').html('');
                                $('#erMsg').html(data);
                            }
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','#queenslandBtn',function () {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var $frmMail = $("#frmMailqueensland").validate({
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
                    content: {
                        required: true
                    }
                },
                messages: {
                    content:{
                        required: "Please enter mail content"
                    }
                },
                submitHandler: function (form) {
                    var content = $('textarea#queensland').val();
                    var account = $('#accountqueensland').val();
                    $.ajax({
                        url:"updateInterviewContent.php",
                        type:"POST",
                        data:{account:account,content:content},
                        dataType:"text",
                        success: function(data) {
                            if (data == 'Updated'){
                                location.reload();
                            }else{
                                $('#erMsg').html('');
                                $('#erMsg').html(data);
                            }
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
</body>
</html>