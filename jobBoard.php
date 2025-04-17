<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
if ($_SESSION['userSession'] == '') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <style>
        body{
            font-size: 8pt;
        }
    </style>
</head>
<body>
<header id="header">
    <?php include "template/top_menu.php"; ?>
</header>
<aside id="left-panel">
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <?php include "template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
		<i class="fa fa-arrow-circle-left hit"></i>
	</span>
</aside>
<!-- END NAVIGATION -->
<!-- MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <span class="ribbon-button-alignment">
        </span>
    </div>
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h4>JOB BOARD APPLICATIONS</h4>
        <div class="error"></div>
        <div style="overflow-x: scroll">
        <table id="dataTbl" class="table table-striped table-bordered table-responsive">
            <thead>
                <tr>
                    <th>APPLIED TIME</th>
                    <th>POSITION APPLIED</th>
                    <th>PROFILE IMAGE</th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>EMAIL</th>
                    <th>MOBILE NO</th>
                    <th>SUBURB</th>
                    <th>EXPERIENCE</th>
                    <th>STATE</th>
                    <th>REGION</th>
                    <th>POSITIONS</th>
                    <th>GENDER</th>
                    <th>ACTION</th>
                    <th>RELEVANT LICENCES</th>
                    <th>WORK RIGHTS</th>
                    <th>RESUME</th>
                    <th>ACTION</th>
                </tr>
            </thead>
            <tbody class="tblBody">
            </tbody>
        </table>
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
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script>
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
        loadJobBoardTable();
        function loadJobBoardTable(){
            var action = 'LOAD';
            $.ajax({
                url:"processJobBoard.php",
                method:"POST",
                data:{action:action},
                dataType:"html",
                success:function(data)
                {
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
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": true,
                "order": [],
                "pageLength": 100
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
        $(document).on('click','.crProBtn', function (){
            let jb_id = $(this).closest('tr').attr('id');
            let refcode = $(this).closest('tr').find('.shortlist_para').find('#ref_code').val();
            let fN = $(this).closest('tr').find('.shortlist_para').find('#fN').val();
            let lN = $(this).closest('tr').find('.shortlist_para').find('#lN').val();
            let email = $(this).closest('tr').find('.shortlist_para').find('#email').val();
            let phone_number = $(this).closest('tr').find('.shortlist_para').find('#phone_number').val();
            let action = 'JOBBOARD';
            //console.log('refcode'+refcode+fN+lN+email+phone_number+jb_id);
            window.location = './candidateReview.php?refcode='+refcode+'&fN='+fN+'&lN='+lN+'&eml='+email+'&mbl='+phone_number+'&action='+action+'&jb_id='+jb_id;
            /*let id = $(this).attr('data-id');
            var action = 'CREATE';
            $.ajax({
                url:"processJobBoard.php",
                method:"POST",
                data:{action:action,id:id},
                dataType:"text",
                success:function(data)
                {
                    if(data === 'INSERTED') {
                        location.reload();
                    }else {
                        $('.error').html('');
                        $('.error').html(data);
                    }
                }
            }).done(function () {
            });*/
        });
        $(document).on('click','.unsuccessfulBtn', function (){
            let id = $(this).attr('data-id');
            var action = 'UNSUCCESSFUL';
            $.ajax({
                url:"processJobBoard.php",
                method:"POST",
                data:{action:action,id:id},
                dataType:"text",
                success:function(data)
                {
                    $('.error').html('');
                    $('.error').html('Refresh the page to remove Unsuccessful');
                    //location.reload();
                }
            }).done(function () {
            });
        });
        $(document).on('click','.shortlistBtn', function (){
            let arr = [];
            $(this).closest('td').prev().prev().find("input[name='positionChk']:checked").each(function (){
                arr.push($(this).val());
            });
            let jb_id = $(this).closest('tr').attr('id');
            let state_id = $(this).closest('td').prev().prev().prev().prev().find('#state_id :selected').val();
            let region = $(this).closest('td').prev().prev().prev().find('#region :selected').val();
            let gender = $(this).closest('td').prev().find('#gender :selected').val();
            let applied_date = $(this).closest('td').find('#applied_date').val();
            let ref_code = $(this).closest('td').find('#ref_code').val();
            let msg_id = $(this).closest('td').find('#msg_id').val();
            let account_name = $(this).closest('td').find('#account_name').val();
            let inbox_type = 'JOBBOARD';
            //console.log('.........'+jb_id+'st '+state_id+'rg '+region+'pos'+arr+'gender '+gender+'app date '+applied_date+'ref '+ref_code+'msg '+msg_id+'acc '+account_name+'inb '+inbox_type);
            $.ajax({
                type:"POST",
                url: "./updateShortList.php",
                dataType: 'text',
                data:{
                    jb_id: jb_id,
                    state_id: state_id,
                    positions: arr,
                    region: region,
                    gender: gender,
                    applied_date: applied_date,
                    ref_code: ref_code,
                    msg_id: msg_id,
                    account_name: account_name,
                    inbox_type:inbox_type
                },
                success: function (data) {
                    console.log('DATA '+data);
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>