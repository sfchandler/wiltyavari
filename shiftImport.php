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
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");


if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
?>
<?php

if(isset($_POST['submit'])) {
    if (($_POST['departmentId'] <> 'None') && isset($_POST['departmentId'])) {
        $param = $_POST['departmentId'];
        //if($param <> 'None' && isset($param) && isset($num_th)) {
        $ps = explode('-', $param);
        $clientId = $ps[0];
        $stateId = $ps[1];
        $deptId = $ps[2];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $dateRange = $_POST['dateRange'];
        if (isset($_FILES['uploadFile']['name']) && $_FILES['uploadFile']['name'] != "") {
            $allowedExtensions = array("xls", "xlsx");
            $temp = explode(".", $_FILES['uploadFile']['name']);
            $newFileName = round(microtime(true)) . '_' . date('Y-m-d') . '_' . $_SESSION['userSession'] . '.' . end($temp);
            $ext = pathinfo($newFileName, PATHINFO_EXTENSION);
            if (in_array($ext, $allowedExtensions)) {
                $file_size = $_FILES['uploadFile']['size'] / 1024;
                if ($file_size < 50) {
                    $file = "imports/" . $newFileName;
                    $isUploaded = copy($_FILES['uploadFile']['tmp_name'], $file);
                    if ($isUploaded) {
                        try {
                            //Load the excel(.xls/.xlsx) file
                            $objPHPExcel = PHPExcel_IOFactory::load($file);
                        }
                        catch (Exception $e) {
                            $errMsg = 'Error loading file "' . pathinfo($file, PATHINFO_BASENAME . '": ' . $e->getMessage());
                        }
                        //specify which sheet need to read or work with.
                        $sheet = $objPHPExcel->getSheet(0);
                        //It returns the highest number of rows
                        $total_rows = $sheet->getHighestRow();
                        //It returns the highest number of columns
                        $total_columns = $sheet->getHighestColumn();
                        //Loop through each row of the worksheet
                        for ($row = 2; $row <= $total_rows; $row++) {
                            //Read a single row of data and store it as a array.
                            //This line of code selects range of the cells like A1:D1
                            $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, FALSE, TRUE);
                            foreach ($single_row as $key => $value) {
                                $positionId = getPositionIdByPosition($mysqli, $value[2]);
                                if(!checkUnavailability($mysqli,trim($value[0]),trim($value[4]))) {
                                    $status = saveAndDisplayShift($mysqli, $value[4], dayOfWeek($value[4]), $clientId, $stateId, $deptId, $value[0], date("H:i", strtotime($value[5])), date("H:i", strtotime($value[6])), '', 'IMPORT', '', $startDate, $endDate, $dateRange, $positionId, 'OPEN', 0);
                                    $json = json_decode($status, true);
                                    //echo 'DATA>>>>>>>'.date("H:i",strtotime($value[5])) . ' End Time ' . date("H:i",strtotime($value[6])).'<br>';
                                    $msgArray[] = array('rec' => 'ID ' . $value[0] . ' Date ' . $value[4] . ' Start Time ' . date("H:i", strtotime($value[5])) . ' End Time ' . date("H:i", strtotime($value[6])), 'status' => $json[0]['status']);
                                    logShiftImport($mysqli, $value[0], $value[4], $value[5], $value[6], $json[0]['status'], $_SESSION['userSession'], date('Y-m-d H:i:s'));
                                }
                            }
                        }
                        //unlink($file);
                    } else {
                        $errMsg = 'File not uploaded!';
                    }
                } else {
                    $errMsg = 'Maximum file size should not cross 50 KB on size!';
                }
            } else {
                $errMsg = 'This type of file not allowed!';
            }
        } else {
            $errMsg = 'Select an excel file first!';
        }
    } else {
        $errMsg = 'Please select a department';
    }
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
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
    <!-- RIBBON -->
    <div id="ribbon">
        <span class="ribbon-button-alignment">
        </span>
        <!-- breadcrumb -->
        <ol class="breadcrumb">
        </ol>
        <!-- end breadcrumb -->
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body">
        <div class="error">
            <?php if(!empty($errMsg)){ echo $errMsg; } ?>
        </div>
        <h1>Roster Shift Upload</h1>
        <form class="smart-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <div class="row">
                    <h5>Please Correctly select date range according to excel sheet(Only one department per file)</h5>
                    <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 18%">
                        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                        <span></span> <b class="caret"></b>
                        <input type="hidden" name="startDate" id="startDate">
                        <input type="hidden" name="endDate" id="endDate">
                        <input type="hidden" name="dateRange" id="dateRange">
                    </div>
                    <div class="pull-left">
                        <label for="departmentId" class="select">
                            <select name="departmentId" id="departmentId" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%; height: 40px; font-size: 9pt">
                            </select><i></i></label>
                    </div>
                    <div class="pull-left">
                        <input type="file" name="uploadFile" id="uploadFile"/><label for="uploadFile">Select excel file (*.xlsx)</label>
                        <input type="submit" name="submit" value="Upload" class="btn btn-sm btn-warning"/>
                    </div>
                </div>
            </fieldset>
        </form>
        <?php
        if(!empty($msgArray)){
            ?>
            <h3>Upload Information</h3>
            <div style="width: 60%">
                <table border="1" cellpadding="2" cellspacing="2" class="table table-striped table-bordered table-hover" width="60%">
                    <thead>
                    <th>Description</th>
                    <th>Status</th>
                    </thead>
                    <tbody>
                    <?php
                    foreach($msgArray as $key=>$val){
                        echo '<tr><td>'.$val['rec'].'</td><td style="color: red">'.$val['status'].'</td></tr>';
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        <?php
        }
        ?>
        <div style="height: 100px;">&nbsp;</div>
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
<script type="text/javascript">
    $(document).ready(function(){
        var start = moment();
        var end = moment();
        var weekday=new Array(7);
        weekday[0]="Sun";
        weekday[1]="Mon";
        weekday[2]="Tue";
        weekday[3]="Wed";
        weekday[4]="Thu";
        weekday[5]="Fri";
        weekday[6]="Sat";
        var headerGlobal = [];
        var headerReturn = [];
        function dateCalendar(start, end) {
            var dateRange = [];
            var days = [];
            var date = [];
            var header = [];
            headerGlobal.length = 0;
            headerReturn.length = 0;
            $('#days').html('');
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            var startDate = start.format('YYYY-MM-DD');
            var endDate = new Date(end.format('YYYY-MM-DD'));
            var currentDate = new Date(startDate);
            while (currentDate <= endDate) {
                var dateFormat = new Date(currentDate);
                dateRange.push(dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate());
                days.push(weekday[dateFormat.getDay()]);
                date.push(dateFormat.getDate());
                header.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
                headerReturn.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate(),'headerDate': dateFormat.getDate(), 'headerDay': weekday[dateFormat.getDay()]});
                headerGlobal.push({'headerFullDate': dateFormat.getFullYear()+'-'+(dateFormat.getMonth()+1)+'-'+dateFormat.getDate()});
                currentDate.setDate(currentDate.getDate() + 1);
            }
            $('#dateRange').val(dateRange);
            $('#startDate').val(start.format('YYYY-MM-DD'));
            $('#endDate').val(end.format('YYYY-MM-DD'));
        }
        $('#reportrange').daterangepicker({
            "autoApply": true,
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Day': [moment().subtract(6, 'days'), moment()]
            }
        }, dateCalendar);
        dateCalendar(start, end);
        populateClientDepartments();
        function populateClientDepartments(){
            $.ajax({
                url:"getClientDepartments.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#departmentId').html('');
                    $('#departmentId').html(data);
                }
            });
        }
    });
</script>
</body>

</html>