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
                                $clientId = $value[0];
                                $positionId = $value[1];
                                $jobCode = $value[2];
                                $payCatCode = $value[3];
                                $payRate = $value[4];
                                $chargeRate = $value[5];
                                $status = saveRateCard($mysqli,trim($clientId),trim($positionId),trim(strtoupper($jobCode)),trim(strtoupper($payCatCode)),trim($payRate),trim($chargeRate));
                                $json = json_decode($status, true);
                                //echo 'DATA>>>>>>>'.$value[0].$value[1].$value[2].$value[3].$value[4].$value[5].'<br>';
                                $msgArray[] = array('rec' => 'ID ' . $value[0] . '  ' . $value[1] . '  ' . $value[2] . '  ' . $value[3] . '  ' . $value[4] . '  ' . $value[5],  'status'=> $json[0]['status']);
                                //logRateCardImport($mysqli, $value[0], $value[4], $value[5], $value[6], $json[0]['status'], $_SESSION['userSession'], date('Y-m-d H:i:s'));
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
        <h1>Rate Card Upload</h1>
        <br>
        Note: make sure there are no spaces on excel cells with data and make all the text, format cells -> text , before upload and delete paycatcode and paycat description columns
        <br>
        <div class="row">
            <section class="col col-sm-2">
                <a href="./imports/ratecard.xlsx" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i>&nbsp;Sample Rate Card Import Excel</a>
            </section>
        </div>
        <div class="row">
            <section class="col col-sm-3">
                <form class="smart-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
                    <fieldset>
                        <div class="row">
                            <h5>Please select rate card excel sheet</h5>
                            <div class="pull-left">
                                <input type="file" name="uploadFile" id="uploadFile"/><label for="uploadFile">Select excel file (*.xlsx)</label>
                                <input type="submit" name="submit" value="Upload" class="btn btn-sm btn-warning"/>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </section>
        </div>
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

        <div class="row">
            <section class="col col-sm-3">
                <fieldset>
                    <legend>JobCode List</legend>
                </fieldset>
                <table width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>JobCode</th>
                        <th>ClientID</th>
                        <th>PositionID</th>
                        <th>Client Name</th>
                        <th>Position Name</th>
                    </tr>
                    </thead>
                    <tbody class="jobCodeBody">
                    </tbody>
                </table>
            </section>
            <section class="col col-sm-2">

            </section>
            <section class="col col-sm-3">
                <fieldset>
                    <legend>PayCategories List</legend>
                </fieldset>
                <table width="100%" class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Pay Category Code</th>
                        <th>Pay Category Name</th>
                    </tr>
                    </thead>
                    <tbody class="payCatBody">

                    </tbody>
                </table>
            </section>

        </div>
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
        /*getCandidatePositions();
        function getCandidatePositions(){
            $.ajax({
                url:"getCandidatePositions.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('.positionsBody').html('');
                    $('.positionsBody').html(data);
                }
            });
        }*/
        loadPayCategories();
        function loadPayCategories(){
            var action = 'display';
            $.ajax({
                url :"loadPayCategories.php",
                type:"POST",
                data:{ action: action },
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('.payCatBody').html('');
                $('.payCatBody').html(data);
            });
        }
        loadJobCodes();
        function loadJobCodes(){
            var action = 'display';
            $.ajax({
                url :"loadJobCodes.php",
                type:"POST",
                data:{ action: action },
                dataType:"html",
                success: function(data) {
                }
            }).done(function(data){
                $('.jobCodeBody').html('');
                $('.jobCodeBody').html(data);
            });
        }
    });
</script>
</body>

</html>