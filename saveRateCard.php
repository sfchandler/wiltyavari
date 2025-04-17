<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionid'];
$jobCode = $_REQUEST['jobcode'];
$html = '';
$status = '';
foreach($_POST['payRate'] as $cnt => $payRate) {
    try {
        $status = saveRateCard($mysqli, $clientId, $positionId, $jobCode, $_POST['payCatCode'][$cnt], $_POST['payRate'][$cnt], $_POST['chargeRate'][$cnt]);
    }catch (Exception $e){
        $e->getMessage();
    }

}

if($status){
    $html = getSavedRateCard($mysqli,$clientId,$positionId,$jobCode);
    echo '<div class="alert alert-success alert-dismissible">
            <strong>Success!</strong> Current Rates Saved
        </div>';
}else{
    echo '<div class="alert alert-danger alert-dismissible">
            <strong>Error!</strong> Error Saving Current Rates
        </div>';
}
//echo $html;
?>