<?php
session_start();
session_regenerate_id(true);
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
if (empty($_SESSION['userSession'])) {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}

?>
<!-- job adder page -->
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
        <img src="img/jobAdder.svg" width="142" height="24" alt=""/>
        <br>
        <table style="float: right;">
            <tbody>
              <tr>
                <td><a href="javascript:window.location.reload();" class="btn btn-info"><i class="fa fa-refresh"></i></a>
                    </td>
                <td>&nbsp;</td>
                <td><a href="<?php echo jobAdderAuth(); ?>" class="btn btn-info" target="_blank">Connect to JobAdder</a></td>
              </tr>
            </tbody>
          </table>
        <h3 style="padding-left: 5px;"><i class="glyphicon glyphicon-user"></i> Placements</h3>
        <div class="error"></div>
        <?php
        if(!empty($_SESSION['access_token'])) {
            $response_data = jobAdderConnect($_SESSION['access_token'],JA_AUTH_URL,JA_PLACEMENTS);
        ?>
        <div style="overflow-x: scroll">
            <table id="dataTbl" class="table table-striped table-bordered table-responsive">
                <thead>
                <tr>
                    <th>PLACEMENT ID</th>
                    <th>CANDIDATE ID</th>
                    <th>FIRST NAME</th>
                    <th>LAST NAME</th>
                    <th>EMAIL</th>
                    <th>MOBILE NO</th>
                    <th>JOB TITLE</th>
                    <th>COMPANY/WORKPLACE NAME</th>
                    <th>CREATED AT</th>
                    <th>JOB ID</th>
                    <th>OUTAPAY ID</th>
                </tr>
                </thead>
                <tbody class="tblBody">
                <?php
                $html = '';
                foreach ($response_data->items as $item) {
                    $html = $html.'<tr>
                                    <td>';
                                  if(!validateStaffPlacementInfo($mysqli,$item->placementId)) {
                                      $html = $html . '<a href="get_placement.php?placement_id=' . $item->placementId . '" target="_blank">' . $item->placementId . '</a>';
                                  }else{
                                      $html = $html . '<i class="fa fa-check" style="color: green"></i>'.$item->placementId;
                                  }
                    $html = $html.'</td>
                                     <td>'.$item->candidate->candidateId.'</td>
                                     <td>'.$item->candidate->firstName.'</td>
                                    <td>'.$item->candidate->lastName.'</td>
                                    <td>'.$item->candidate->email.'</td>
                                     <td>'.$item->candidate->mobile.'</td>
                                    <td>'.$item->job->jobTitle.'</td>
                                    <td>'.$item->job->company->name.'</td>
                                    <td>'.date('Y-m-d H:i:s',strtotime($item->createdAt)).'</td>
                                    <td>'.$item->job->jobId.'</td>
                                    <td><a href="candidateMain.php?canId='.base64_encode(getCandidateIdByJobAdderId($mysqli,$item->candidate->candidateId)).'" target="_blank">'.getCandidateIdByJobAdderId($mysqli,$item->candidate->candidateId).'</a></td>
                    </tr>';
                    // <td>'.getAuditStatusByEmail($mysqli,$item->candidate->email).'</td>
                }
                echo $html;
                ?>
                </tbody>
            </table>
        </div>
        <?php
        }
        ?>
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
        function dataTableInit(){
            var table = $('#dataTbl').DataTable({
                "bPaginate": true,
                "bLengthChange": false,
                "bFilter": true,
                "bInfo": true,
                "bAutoWidth": true,
                "order": [],
                "pageLength": 50
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
        dataTableInit();
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>