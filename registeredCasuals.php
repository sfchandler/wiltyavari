<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if(!empty($_POST['startDate']) && !empty($_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $consultant_id = $_POST['consultant_id'];
    $html = '';
    $dataSet = getRegisteredCasualsInformation($mysqli,$startDate,$endDate,$consultant_id);
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
    <style>
        .modal-backdrop{z-index: -1}
        .modal-holder.modal-backdrop{z-index: 100}
        .modal-dialog {z-index: 1000}
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
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <?php include "template/breadcrumblinks.php"; ?>
        </ol>
        <!-- end breadcrumb -->
    </div>
    <!-- MAIN CONTENT -->

    <div id="content" class="container-body" style="margin-bottom: 50px; overflow: scroll">

        <form name="frmReg" action="" method="post">
            <fieldset>
                <legend><h4>REGISTERED CASUALS</h4></legend>
                    <div class="row" style="padding-left: 15px;">
                        <section class="col col-8">
                            <label for="startDate" class="input">
                                <input type="text" name="startDate" id="startDate" value="" class="form-control" placeholder="From Date"/>
                            </label>
                            <label for="endDate" class="input">
                                <input type="text" name="endDate" id="endDate" value="" class="form-control" placeholder="To Date"/>
                            </label>
                            <label for="">
                                <select name="consultant_id" id="consultant_id" class="form-control">
                                    <?php echo getConsultantsForSelectMenu($mysqli); ?>
                                </select>
                            </label>
                            <label for="" class="input">
                                <button type="submit" id="filterBtn" class="btn btn-info"><i class="fa fa fa-filter"></i>&nbsp;Filter</button>
                            </label>
                        </section>
                    </div>
                <?php if(!empty($_POST['startDate']) && !empty($_POST['endDate'])){ echo 'Data range selected: '.$startDate.' to '.$endDate; } ?>
            </fieldset>
        </form>
        <table id="dataTbl" class="table table-striped table-bordered table-responsive" style="font-size: 9pt; width: 90%;">
            <thead>
              <tr>
                <th>EMPLOYEE ID</th>
                <th>FIRST NAME</th>
                <th>LAST NAME</th>
                <th>MOBILE NO</th>
                <th>EMAIL</th>
                <th>CONSULTANT</th>
                <th>FOUND US BY</th>
                <th>JOB REFERENCE APPLIED</th>
                <th>MAIL COLOR CODES</th>
                <th>POSITIONS ASSIGNED</th>
                <th>REGISTERED DATE</th>
                <th>SCREENED DATE</th>
                <th>PHONE SCREENED DATE</th>
                <th>REGPACK SENT TIME</th>
                <th>REGPACK RECEIVED TIME</th>
                <th>REGPACK STATUS</th>
                <th>RECRUITMENT STATUS</th>
                <th>CASUAL NOTES</th>
<!--                <th>DO NOT USE</th>
-->
                <th>STATUS UPDATED TIME</th>
                <th>EMPLOYEE STATUS</th>
                <th>AUDIT STATUS</th>
                <th>LAST SHIFT</th>
              </tr>
            </thead>
            <tbody class="tblBody">
            <?php
            if(!empty($dataSet)) {
                foreach ($dataSet as $data) {
                    $shiftInfo = explode(':',getLastShiftInfoByCandidateId($mysqli,$data['candidateId']));
                    $html = $html . '<tr';
                    if($data['gender'] == 'Male'){
                        $html = $html .' class="cellColorMale"';
                    }elseif ($data['gender'] == 'Female'){
                        $html = $html .' class="cellColorFeMale"';
                    }
                    $html = $html .'>
                <td><a href="candidateMain.php?canId=' . base64_encode($data['candidateId']) . '" target="_blank">' . $data['candidateId'] . '</a></td>
                <td>' . $data['firstName'] . '</td>
                <td>' . $data['lastName'] . '</td>
                <td>' . $data['mobileNo'] . '</td>
                <td>' . $data['email'] . '</td>
                <td>' . getConsultantName($mysqli, $data['consultantId']) . '</td>';
                $html = $html.'<td>'. getCandidateFoundHow($mysqli, $data['candidateId']) . '</td>';
                $html = $html.'<td>'. $data['ref_code_applied'].'</td>';
                $html = $html.'<td>';
                if(!empty($data['autoId']) && $data['autoId'] > 0) {
                    $html = $html.getMailColorCategories($mysqli, $data['autoId'], 'mail_color_category');
                }elseif(!empty($data['jb_id']) && $data['jb_id'] > 0) {
                    $html = $html.getMailColorCategories($mysqli, $data['jb_id'], 'jobboard_mail_color_category');
                }
                $html = $html.'</td>';
                $html = $html.'<td>';
                $posList = getEmployeePositionList($mysqli,$data['candidateId']);
                foreach ($posList as $pos){
                    $html = $html.$pos['positionName'].'<br>';
                }
                $html = $html . '</td>';
                $html = $html . '<td>'. $data['created_at'] . '</td>';
                $html = $html . '<td>'. $data['screenDate'] . '</td>';
                $html = $html . '<td>'. getCandidateDocumentDateByDocTypeId($mysqli,$data['candidateId'],35) . '</td>';
                $html = $html . '<td>'. getRegPackSentTime($mysqli, $data['candidateId']) . '</td>';
                $html = $html . '<td>'. getCandidateDocumentDateByDocTypeId($mysqli, $data['candidateId'],23) . '</td>';
                if ($data['reg_pack_status'] == 1) {
                    $regpack = 'RECEIVED';
                } else {
                    $regpack = '';
                }
                $html = $html . '<td>' . $regpack . '</td>';
                $html = $html . '<td>'.getRecruitmentStatusNameByCandidateId($mysqli,getRecruitmentStatusByCandidateId($mysqli,$data['candidateId'])).'</td>';
                $html = $html . '<td>';
                $html = $html.$data['casual_status'];
                $html = $html.'</td>';
/*                $html = $html . '<td>' . getAttributeCodeById($mysqli,getDONOTUSEAttribute($mysqli,$data['candidateId'])) . '</td>';*/
                $html = $html . '<td>' . $data['casual_status_update'] . '</td>';
                $html = $html . '<td>' . $data['empStatus'] . '</td>';
                if ($data['auditStatus'] == '1') {
                    $auditStatus = 'AUDITED';
                } else {
                    $auditStatus = 'N/A';
                }
                $html = $html . '<td>' . $auditStatus . '</td>';
                $html = $html . '<td>' . $shiftInfo[0] . '</td>';
                $html = $html . '</tr>';
                }
            }
            echo $html;
            ?>
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
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<!--<script src="js/datatables.min.js"></script>
<link href="css/datatables.css" rel="stylesheet">-->
<script>
    $(document).ready(function(){
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
        $('input[name="startDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="startDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.startDate.format('YYYY-MM-DD'));
            $('#startDate').val(picker.startDate.format('YYYY-MM-DD'));
        });
        $('input[name="endDate"]').daterangepicker({
            singleDatePicker: true,
            showDropdowns: true,
            autoUpdateInput: false
        });
        $('input[name="endDate"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.endDate.format('YYYY-MM-DD'));
            console.log('Date Selected'+picker.endDate.format('YYYY-MM-DD'));
            $('#endDate').val(picker.endDate.format('YYYY-MM-DD'));
        });

        $('#dataTbl thead th').each(function() {
            var title = $('#dataTbl thead th').eq($(this).index()).text();
            $(this).html(title+'\n<input type="text" />');
        });
        var table = $('#dataTbl').DataTable({
            "bPaginate": true,
            "bLengthChange": false, /* show entries off */
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "order": [[7, "desc"]],
            "pageLength": 100
        });
        table.columns().eq(0).each(function(colIdx) {
            $('input', table.column(colIdx).header()).on('keyup change', function() {
                table
                    .column(colIdx)
                    .search(this.value)
                    .draw();
            });

            $('input', table.column(colIdx).header()).on('click', function(e) {
                e.stopPropagation();
            });
        });

        $('#notesModal').on('show.bs.modal', function (event) {
            //Make sure the modal and backdrop are siblings (changes the DOM)
            $(this).before($('.modal-backdrop'));
            //Make sure the z-index is higher than the backdrop
            $(this).css("z-index", parseInt($('.modal-backdrop').css('z-index')) + 1);
            var button = $(event.relatedTarget) // Button that triggered the modal
            var candidate_id = button.data('id') // Extract info from data-* attributes
            $.ajax({
                type : 'post',
                url : 'fetch_notes.php', //Here you will fetch records
                data :  'candidate_id='+ candidate_id, //Pass $id
                success : function(data){
                    $('.fetched-data').html(data);//Show fetched data from database
                }
            });
        });
    });
</script>
<div class="modal fade" id="notesModal" tabindex="-1" role="dialog" aria-labelledby="notesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notesModalLabel">Notes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="fetched-data"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>