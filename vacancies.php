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
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2>Email/SMS Vacancies</h2>
        <div class="error"></div>
        <div class="row">
            <section class="col col-lg-3">
                <select name="positionid" id="positionid" class="form-control">
                </select><i></i>
            </section>
            <section class="col col-lg-3">
                <span id="excelLink"></span>
            </section>
        </div>
        <div class="row">
            <section class="col col-lg-6">
                <table class="table-responsive table-striped table-bordered" style="width: 100%; font-size: 8pt">
                    <thead>
                    <tr>
                        <th><input type="checkbox" class="chkSMSAll"></th>
                        <th>Employee ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Mobile No</th>
                        <th>Email</th>
                        <th>Last Date of Work</th>
                        <th>State</th>
                        <th>Suburb</th>
                    </tr>
                    </thead>
                    <tbody id="tblBody">

                    </tbody>
                </table>
            </section>
            <section class="col col-lg-6">
                <label for="smsText"><h6>SMS Text</h6></label>
                <textarea type="text" name="smsText" id="smsText" class="form-control" required></textarea><button name="sendSMSBtn" id="sendSMSBtn" class="btn btn-md btn-primary"><i class="fa fa-mobile-phone"></i>&nbsp;Send SMS</button>
                <br>
                <label for="emailBody"><h6>Email Body Text</h6></label>
                <textarea name="emailBody" id="emailBody" cols="30" rows="10" class="form-control" required></textarea>
                <button name="sendMailBtn" id="sendMailBtn" class="btn btn-md btn-primary"><i class="fa fa-paper-plane"></i>&nbsp;Send Mail</button>
            </section>
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
<!-- TINYMCE PLUGIN -->
<script src='js/tinymce/js/tinymce/tinymce.min.js'></script>
<script>
    $(document).ready(function(){

        /* AJAX loading animation */
        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });
        tinymce.init({
            selector: '#emailBody',
            height: 300,
            theme: 'modern',
            /*            plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
*/
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
        /* -  end  -*/
        loadPositions();
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
        $(document).on('change', '#positionid', function(){
            var positionId = $('#positionid :selected').val();
            var action = 'GET';
            $.ajax({
                url: "marketing_data.php",
                type: "GET",
                dataType: "html",
                data:{ positionId:positionId,action:action}
                ,success: function (data) {
                    $('#tblBody').html('');
                    $('#tblBody').html(data);
                }
            });
            generateMarketingDataExcel(positionId);
        });
        function generateMarketingDataExcel(positionId){
            var action = 'EXCEL';
            $.ajax({
                url: "marketing_data.php",
                type: "GET",
                dataType: "html",
                data:{ positionId:positionId,action:action}
                ,success: function (data) {
                    $('#excelLink').html('');
                    $('#excelLink').html(data);
                }
            });
        }
        $(document).on('click','.chkSMSAll',function(){
            if($(".chkSMSAll").is(':checked')){
                $(".chkSMS").prop('checked',true);
            }else{
                $(".chkSMS").prop('checked',false);
            }
        });
        $(document).on('click', '#sendMailBtn', function(){
            $(".chkSMS:checked").each(function () {
                var checkedVal = $(this).val();
                var email =  $(this).closest('td').attr('data-email');
                var action = 'EMAIL';
                var emailBody = tinyMCE.get('emailBody').getContent();
                $.ajax({
                    url: "marketing_data.php",
                    type: "GET",
                    dataType: "html",
                    data:{ email:email, action:action,emailBody:emailBody }
                    ,success: function (response) {
                        $('.error').html(response);
                    }
                });
            });
            /*$('.emailList').each(function(i, obj) {
                var email = $(this).text();
                var action = 'EMAIL';
                var emailBody = tinyMCE.get('emailBody').getContent();//$('textarea#emailBody').val();
                console.log('body'+emailBody);
                $.ajax({
                    url: "marketing_data.php",
                    type: "GET",
                    dataType: "html",
                    data:{ email:email, action:action,emailBody:emailBody }
                    ,success: function (data) {
                        $('.error').html(data);
                    }
                });
            });*/
        });
        $(document).on('click', '#sendSMSBtn', function(){
            var action = 'SMS';
            var smsText = $('textarea#smsText').val();
            $(".chkSMS:checked").each(function () {
                var checkedVal = $(this).val();
                var mobile = $(this).closest('td').attr('data-mobile');
                var email =  $(this).closest('td').attr('data-email');
                //console.log('...........'+checkedVal+mobile+email);
                $.ajax({
                    url: "marketing_data.php",
                    type: "GET",
                    dataType: "html",
                    data:{ mobile:mobile, action:action,smsText:smsText }
                    ,success: function (response) {
                        $('.error').html(response);
                    }
                });
            });
            /*$('.mobileNo').each(function(i, obj) {
                var mobile = $(this).text();

            });*/
        });
        //{"recipients": 1, "delivery_stats": {"delivered": 0, "bounced": 0, "responses": 0, "pending": 1, "optouts": 0}, "sms": 1, "cost": 1, "send_at": "2021-04-14 04:38:15", "error": {"code": "SUCCESS", "description": "OK"}, "message_id": 13669807};
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>