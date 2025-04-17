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
$positionId = $_POST['positionid'];
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
                            $objPHPExcel = PHPExcel_IOFactory::load($file);
                        }
                        catch (Exception $e) {
                            $errMsg = 'Error loading file "' . pathinfo($file, PATHINFO_BASENAME . '": ' . $e->getMessage());
                        }
                        $sheet = $objPHPExcel->getSheet(0);
                        $total_rows = $sheet->getHighestRow();
                        $total_columns = $sheet->getHighestColumn();
                        for ($row = 2; $row <= $total_rows; $row++) {
                            $single_row = $sheet->rangeToArray('A' . $row . ':' . $total_columns . $row, NULL, FALSE, TRUE);
                            foreach ($single_row as $key => $value) {
                                $explodeName = explode(' ',trim($value[0]));
                                $firstName = $explodeName[0];
                                $lastName = $explodeName[1];
                                $status = importCandidate($mysqli,$firstName,$lastName,trim($value[1]),trim($value[2]),$positionId);
                                $json = json_decode($status, true);
                                $msgArray[] = array('rec' =>  $value[0] , 'status' => $json[0]['status']);
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

function importCandidate($mysqli,$firstName,$lastName,$email,$mobile,$positionId){
    if(!validateCandidateByEmail($mysqli,$email)) {
        $canId = getNewCandidateId($mysqli);
        $email = str_replace(' ', '', trim($email));
        $consId = 0;
        $mobile = str_replace(' ', '', trim($mobile));
        $nickname = '';
        $gender = '';
        $dob = '';
        $address = '';
        $street_number = '';
        $street_name = '';
        $suburb = '';
        $state = '';
        $postcode = '';
        $foundhow = '';
        $password = '';
        $username = $canId;
        $hash = '';
        if (!empty($dob)) {
            $password = trim(str_replace('/', '', $dob));
            $options = [
                'cost' => 12,
            ];
            $hash = password_hash($password, PASSWORD_BCRYPT, $options);
        }
        if (!validateCandidateId($mysqli, $canId)) {
            $pin = null;
            try {
                $pin = generateOnePIN($mysqli, getCandidateNoById($mysqli, $canId));
            } catch (Exception $e) {
                $msgArray[] = array('status' => 'PIN generateion Error ' . $e->getMessage());
                return json_encode($msgArray);
            }
            $ins = $mysqli->prepare("INSERT INTO candidate(candidateId,clockPin,firstName,nickname,lastName,mobileNo,email,sex,consultantId,dob,address,street_number,street_name,suburb,state,postcode,screenDate,username,password,foundhow)VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?,?,?)") or die($mysqli->error);
            $ins->bind_param("sissssssissssssisss", $canId, $pin, $firstName, $nickname, $lastName, $mobile, $email, $gender, $consId, $dob, $address, $street_number, $street_name, $suburb, $state, $postcode, $canId, $hash, $foundhow) or die($mysqli->error);
            $ins->execute();
            $nrow = $ins->affected_rows;
            if ($nrow > 0) {
                if (!file_exists('./documents/' . $canId)) {
                    mkdir('./documents/' . $canId, 0777);
                    chown('./documents/' . $canId, 'chandler');
                }
                if (!empty($dob)) {
                    updateUsernamePassword($mysqli, $canId, $dob);
                }
                assignPositionEmpoloyee($mysqli, $canId, $positionId);
                $msgArray[] = array('status' => $canId . ' Inserted');
                return json_encode($msgArray);
            }
        }
    }else{
        $msgArray[] = array('status' => $email.' exists');
        return json_encode($msgArray);
    }
}
function allocateEmployee($mysqli,$canId,$clientId,$stateId,$deptId){
    $ins = $mysqli->prepare("INSERT INTO employee_allocation(candidateId,clientId,stateId,deptId) VALUES(?,?,?,?)")or die($mysqli->error);
    $ins->bind_param("siii",$canId,$clientId,$stateId,$deptId)or die($mysqli->error);
    $ins->execute();
    $nrows = $ins->affected_rows;
    if($nrows == '1'){
        echo 'ALLOCATED';
    }else{
        echo $mysqli->error;
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
    <div id="content" class="container-body" style="padding-bottom: 200px;">
        <div class="error">
            <?php if(!empty($errMsg)){ echo $errMsg; } ?>
        </div>
        <h1>Candidate Upload</h1>
        <div>
            <div>
                <ul style="color: red">
                    <li>Remove all unnecessary formatting and delete empty rows underneath the data and also the columns towards right side with empty cells</li>
                    <li>The excel sheet cannot contain any kind of excel formatting or styling</li>
                    <li>File type must be .xlsx</li>
                    <li><a href="imports/sample/candidateImport.xlsx" target="_blank">Click here</a> for sample Excel Sheet for reference.</li>
                </ul>
            </div>
        </div>
        <form class="smart-form" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
            <fieldset>
                <div class="row">
                    <section class="col col-4">
                        <label class="label">Select Position</label>
                        <label class="select">
                            <select name="positionid" id="positionid" class="form-control">
                            </select> <i></i> </label>
                    </section>
                    <section class="col col-4">
                        <input type="file" name="uploadFile" id="uploadFile" class="form-control-file"/>
                        <label for="uploadFile">Select excel file (*.xlsx)</label>
                        <input type="submit" name="submit" value="Upload" class="btn btn-sm btn-warning"/>
                    </section>
                </div>
            </fieldset>
        </form>
        <?php
        if(!empty($msgArray)){
            ?>
            <h3>Upload Information</h3>
            <div style="width: 60%">
                <table class="table table-striped table-bordered table-hover">
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
        <br><br><br><br><br><br><br><br><br><br><br><br>

    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php //include "template/footer.php"; ?>
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
    $(function(){
        populatePositions();
        function populatePositions(){
            $.ajax({
                url:"getCandidatePositionList.php",
                type:"POST",
                dataType:"html",
                success: function(data){
                    $('#positionid').html('');
                    $('#positionid').html(data);
                }
            });
        }
    });
</script>
</body>
</html>