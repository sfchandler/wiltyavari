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
    $html = '';

    $dataSet = getResumesShortListed($mysqli,$startDate,$endDate);
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
    </div>
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px; overflow: scroll">
        <form name="frmReg" action="" method="post">
            <fieldset>
                <legend><h4>INBOX RESUME SHORT LIST</h4></legend>
                    <div class="row" style="padding-left: 15px;">
                        <section class="col col-8">
                            <label for="startDate" class="input">
                                <input type="text" name="startDate" id="startDate" value="" class="form-control" placeholder="From Date"/>
                            </label>
                            <label for="endDate" class="input">
                                <input type="text" name="endDate" id="endDate" value="" class="form-control" placeholder="To Date"/>
                            </label>
                            <label for="" class="input">
                                <button type="submit" id="filterBtn" class="btn btn-info"><i class="fa fa fa-filter"></i>&nbsp;Filter</button>
                            </label>
                        </section>
                    </div>
                <?php  if(!empty($_POST['startDate']) && !empty($_POST['endDate'])){ echo 'Data range selected: '.$startDate.' to '.$endDate; } ?>
            </fieldset>
        </form>
        <table id="dataTbl" class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>POSITIONS SUITED</th>
                <th>REFERENCE CODE</th>
                <th>INBOX COMMENTS</th>
                <th>STATE</th>
                <th>REGION</th>
                <th>GENDER</th>
                <th>APPLIED DATE</th>
                <th>DAYS AGO</th>
                <th>RESUME</th>
                <th>SHORTLISTED TIME</th>
                <th>SHORTLIST UPDATED TIME</th>
                <th>CONSULTANT</th>
                <th>ACTION</th>
              </tr>
            </thead>
            <tbody class="tblBody">
            <?php
            if(!empty($dataSet)) {
                foreach ($dataSet as $data) {
                    $tableEmail = getTableEmail($mysqli, $data['account_name']);
                    $createdDate = new DateTime($data['applied_date']);
                    $today = new DateTime();
                    $daysAgo = $createdDate->diff($today)->days.' days ago';
                    $html = $html.'<tr id="'.$data['id'].'" class="shortlistRow" data-msg-id="'.$data['msg_id'].'" data-ref-code="'.$data['ref_code'].'" data-email="'.$data['email'].'" data-phone="'.$data['phone'].'" data-first-name="'.$data['first_name'].'" data-last-name="'.$data['last_name'].'">
                                    <td>'.$data['positions'].'</td>
                                    <td>'.$data['ref_code'].'</td>
                                    <td>';
                    if(!empty($data['auto_id'])){
                        $html = $html.getMailComment($mysqli,$data['auto_id']);
                    }
                    $html = $html.'</td>
                                    <td>'.getStateById($mysqli,$data['state_id']).'</td>
                                    <td>'.getRegionById($mysqli,$data['region']).'</td>
                                    <td>'.$data['gender'].'</td>
                                    <td>'.$data['applied_date'].'</td>
                                    <td>'.$daysAgo.'</td>                                    
                                    <td>';
                    if(!empty($data['auto_id'])){
                        $html = $html.getResumeInformationByAutoId($mysqli,$data['auto_id'],$tableEmail).'<br>'.listAttachments($mysqli,htmlentities($data['msg_id']),$data['account_name']).'</td>';
                    }elseif (!empty($data['jb_id'])){
                        $jobBoardResumeInfo = getJobBoardResumeInfoById($mysqli,$data['jb_id']);
                        foreach ($jobBoardResumeInfo as $jb) {
                            $html = $html.$jb['first_name'].' '.$jb['last_name'].'<br>'.$jb['applied_position'].'<br><a href="'.$jb['resume_path'].'" target="_blank">RESUME</a>';
                        }
                    }
                    $html = $html.'</td>';
                    $html = $html.'<td>'.$data['created_at'].'</td>
                                <td>'.$data['updated_at'].'</td>
                                <td>'.getConsultantName($mysqli, $data['consultant_id']).'</td>
                                <td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td>
                                </tr>';
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
        $(document).on('click', '.checkBtn', function(e) {
            let messageid = $(this).closest('tr').attr('data-msg-id');
            let refcode = $(this).closest('tr').attr('data-ref-code');
            let email = $(this).closest('tr').attr('data-email');
            let phone = $(this).closest('tr').attr('data-phone');
            let first_name = $(this).closest('tr').attr('data-first-name');
            let last_name = $(this).closest('tr').attr('data-last-name');
            window.open('./candidateReview.php?messageid='+messageid+'&refcode='+refcode+'&eml='+email+'&mbl='+phone+'&fN='+first_name+'&lN='+last_name,'_blank');
        });

    });
</script>
</body>
</html>