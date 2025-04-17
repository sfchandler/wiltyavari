<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if(!empty($_POST['startDate']) && !empty($_POST['endDate'])) {
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $html = '';
    $dataSet = getShiftCount($mysqli,$startDate,$endDate);
    if(!empty($dataSet)){
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CONSULTANT NAME');
        $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CONFIRMED SHIFT COUNT');
        $objPHPExcel->getActiveSheet()->setTitle('Shift Count Export');
        $rowCount = 1;
        arsort($dataSet);
        foreach ($dataSet as $data) {
            $rowCount++;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['consultantName']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['confirmedShiftCount']);
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $filePath = './reports/shiftCountExport_' .time(). '.xlsx';
        $objWriter->save($filePath);
    }
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
                <legend><h4>SHIFT COUNT REPORT</h4></legend>
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
                <?php if(!empty($_POST['startDate']) && !empty($_POST['endDate'])){ echo 'Data range selected: '.$startDate.' to '.$endDate; } ?>
                <?php
                if(!empty($filePath)){
                    ?>
                <a href="<?php echo $filePath; ?>" target="_blank" style="cursor: pointer" class="btn btn-info">Download Excel</a>
                <?php } ?>
            </fieldset>
        </form>
        <table id="dataTbl" class="table table-striped table-bordered table-responsive" style="font-size: 9pt; width: 90%;">
            <thead>
            <tr>
                <th>CONSULTANT NAME</th>
                <th>CONFIRMED SHIFT COUNT</th>
            </tr>
            </thead>
            <tbody class="tblBody">
                <?php
                foreach ($dataSet as $data){
                    $html = $html.'<tr><td>'.$data['consultantName'].'</td><td>'.$data['confirmedShiftCount'].'</td></tr>';
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
            "order": [[1, "desc"]],
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
    });
</script>

<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>