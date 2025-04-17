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
        <h4 style="color: red">JOB BOARD UNSUCCESSFUL APPLICATIONS</h4>
        <div class="error"></div>
        <table id="dataTbl" class="table table-striped table-bordered">
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
            var action = 'LOADUNSUCCESSFUL';
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
                "bLengthChange": false, /* show entries off */
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": false,
                "order": [[1, "desc"]],
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
            let id = $(this).attr('data-id');
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
            });
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
                    location.reload();
                }
            }).done(function () {
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>