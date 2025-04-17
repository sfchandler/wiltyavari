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
            <div style="padding-left:10px;">
                <h1>Recruitment Job Descriptions</h1>
                <div id="msg" class="error"></div>
            </div>
            <div style="width:100%">
                <div>
                    <form name="frmRecJobDesc" id="frmRecJobDesc" class="smart-form" method="post">
                        <div class="row" style="padding: 20px 20px 20px 20px">
                            <section class="col col-lg-2">
                                <label for="client">Client</label>
                                <select name="client_id" id="client_id" class="form-control"></select>
                            </section>
                            <section class="col col-lg-2">
                                <label for="position_id">Position</label>
                                <select name="position_id" id="position_id" class="form-control"></select>
                            </section>
                            <section class="col col-lg-4">
                                <label for="job_description">Job Description</label><span style="color: red">*</span>
                                <textarea name="job_description" id="job_description" cols="30" rows="20" class="form-control"></textarea>
                            </section>
                            <section class="col col-lg-2">
                                <br>
                                <input type="hidden" name="action" id="action" value="ADD"/>
                                <button type="submit" name="updateJobDescBtn" id="updateJobDescBtn" class="btn btn-lg btn-info">ADD/UPDATE INFO</button>
                            </section>
                        </div>
                    </form>
                </div>
                <div id="recJobDesc" style="padding: 20px 20px 20px 20px">
                    <table id="dataTbl" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Position</th>
                                <th>Recruitment Job Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="tblBody">
                            <?php
                                echo getRecruitmentJobDescription($mysqli);
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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
<script type="text/javascript">
    $(document).ready(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
        tinymce.init({
            selector: '#job_description',
            height: 200,
            theme: 'modern',
            plugins: [
                'textcolor',
                'paste wordcount'
            ],
            mobile: {
                theme: 'mobile'
            },
            toolbar: 'undo redo | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
            image_advtab: true,
            templates: [
                { title: 'Test template 1', content: 'Test 1' },
                { title: 'Test template 2', content: 'Test 2' }
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            branding: false
        });
        getClients();
        loadJobDescriptions();
        function getClients(){
            let action = 'SINGLESELECT';
            $.ajax({
                url:"getClients.php",
                type:"POST",
                data:{action:action},
                dataType:"html",
                success: function(data){
                    $('#client_id').html('');
                    $('#client_id').html(data);
                }
            });
        }
        $(document).on('change', '#client_id', function(){
            getPositionByClient();
        });
        $(document).on('click', '#client_id', function(){
            getPositionByClient();
        });
        getPositionByClient();
        function getPositionByClient(){
            var action = 'CLIENTPOSITION';
            var clientId = $('#client_id :selected').val();
            $.ajax({
                url :"loadPositions.php",
                type:"POST",
                dataType:"html",
                data:{action:action,clientId:clientId},
                success: function(data) {
                }
            }).done(function(data){
                $('#position_id').html('');
                $('#position_id').html(data);
            });
        }
        function loadJobDescriptions(){
            let action = 'DISPLAY';
            $.ajax({
                url:"processJobDescription.php",
                type:"POST",
                data:{
                    action: action
                },
                dataType:"html",
                success: function(data){
                    $('.tblBody').html('');
                    $('.tblBody').html(data);
                }
            }).done(function () {
                dataTableInit();
            });
        }
        function dataTableInit(){
            var table = $('#dataTbl').DataTable({
                "bPaginate": true,
                "bLengthChange": false, /* show entries off */
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                "order": [[1, "desc"]],
                "pageLength": 100,
                "bDestroy": true
            });
            $('#dataTbl thead th').each(function() {
                var title = $('#dataTbl thead th').eq($(this).index()).text();
                $(this).html(title+'\n<input type="text" />');
            });
            table.columns().eq(0).each(function (colIdx) {
                $('input', table.column(colIdx).header()).on('keyup change', function () {
                    table
                        .column(colIdx)
                        .search(this.value)
                        .draw();
                });

                $('input', table.column(colIdx).header()).on('click', function (e) {
                    e.stopPropagation();
                });
            });
        }
        $(document).on('click', '#updateJobDescBtn', function() {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmRecJobDesc = $("#frmRecJobDesc").validate({
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
                    job_description: {
                        required:true
                    }
                },
                messages: {
                    job_description: {
                        required: "Please enter Job Description"
                    }
                },
                submitHandler: function (form) {
                    let action = $('#action').val();
                    let client_id = $('#client_id :selected').val();
                    let position_id = $('#position_id :selected').val();
                    let job_description = $('textarea#job_description').val();
                    $.ajax({
                        url:"processJobDescription.php",
                        type:"POST",
                        data:{
                            action: action,
                            client_id: client_id,
                            position_id:position_id,
                            job_description: job_description
                        },
                        dataType:"html",
                        success: function(data){
                            $('#msg').html('');
                            $('#msg').html(data);
                            loadJobDescriptions();
                            $('#updateJobDescBtn').html('ADD INFO');
                            $('#frmRecJobDesc').trigger('reset');
                            //location.reload();
                        }
                    });
                },
                errorPlacement : function(error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
        $(document).on('click','.editJBBtn',function (){
            $('#updateJobDescBtn').html('UPDATE INFO');
            let id = $(this).closest('tr').attr('id');
            $('#id').val(id);
            $('#action').val('UPDATE');
            let client_id = $(this).closest('tr').attr('data-client-id');
            $('#client_id').val(client_id);
            getPositionByClient();
            let position_id = $(this).closest('tr').attr('data-pos-id');
            $('#position_id').val(position_id);
            let job_description = $(this).closest('tr').attr('data-jb-desc');
            tinymce.activeEditor.setContent(job_description);
            //$('textarea#job_description').val(job_description);
        });
        $(document).on('click','#exportReport', function(){
            let action = 'EXPORT';
            $.ajax({
                url:"processJobDescription.php",
                type:"POST",
                data:{
                    action: action
                },
                dataType:"html",
                success: function(data){
                    console.log('.......'+data);
                    window.open(data);
                }
            });
        });


    });
</script>
</body>

</html>