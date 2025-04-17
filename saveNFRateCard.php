<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$clientId = $_REQUEST['clientId'];
$positionId = $_REQUEST['positionid'];
$jobCode = $_REQUEST['jobcode'];
$html = '';
$status = '';
foreach($_POST['payRateNF'] as $cnt => $payRate) {
    try {
        $status = saveNFRateCard($mysqli, $clientId, $positionId, $jobCode, $_POST['payCatCodeNF'][$cnt], $_POST['payRateNF'][$cnt], $_POST['chargeRateNF'][$cnt]);
    }catch (Exception $e){
        $e->getMessage();
    }
}
if($status){
    //$html = getSavedNFRateCard($mysqli,$clientId,$positionId,$jobCode);
    echo '<div class="alert alert-success alert-dismissible">
        <strong>Success!</strong> New Financial Year Rates Saved
    </div>';
}else{
    echo '<div class="alert alert-danger alert-dismissible">
    <strong>Error!</strong> Error saving New Financial Year Rates.
</div>';
}

?>