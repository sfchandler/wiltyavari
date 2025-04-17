<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once ('includes/fpdf182/fpdf.php');
require_once ('includes/FPDI-2.3.2/src/autoload.php');
require_once ('includes/FPDI-2.3.2/src/FpdfTpl.php');
use setasign\Fpdi\Fpdi;
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
define('TERMS', 'rates/Outapay_Terms_Conditions_Labour_Hire.pdf');

$output_dir = 'rates/';
$action = $_REQUEST['action'];
if($action == 'LOGO') {
    if (isset($_FILES['file'])) {
        if ($_FILES['file']['error'] > 0) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        } else {
            if (!file_exists('rates/')) {
                mkdir('rates/', 0777);
            }
            $fileName = $_FILES['file']['name'];
            $filePath = $output_dir . $_FILES['file']['name'];
            if (move_uploaded_file($_FILES['file']['tmp_name'], $output_dir . $_FILES['file']['name'])) {
                echo $filePath;
            } else {
                echo 'Error Uploading';
            }
        }
    }
}else if($action == 'SENDRATES'){
    $client = $_POST['client'];
    $position = $_POST['position'];
    $position2 = $_POST['position2'];
    $position3 = $_POST['position3'];
    $position4 = $_POST['position4'];
    $award = $_POST['award'];
    $proof1 = $_FILES['proof1'];
    $proof2 = $_FILES['proof2'];
    $proof3 = $_FILES['proof3'];
    $payment_terms = $_POST['payment_terms'];
    try {
        $pdf = new Fpdi();
        $pdf->AddPage();
        $pdf->setSourceFile(TERMS);
        $page1 = $pdf->importPage(1);
        $pdf->useTemplate($page1);
        $pdf->AddPage();
        $page2 = $pdf->importPage(2);
        $pdf->useTemplate($page2);
        $pdf->AddPage();
        $page3 = $pdf->importPage(3);
        $pdf->useTemplate($page3);
        $pdf->SetFont("arial", "", 11);
        $pdf->Text(105,193, $payment_terms);
        $pdf->AddPage();
        $page4 = $pdf->importPage(4);
        $pdf->useTemplate($page4);
        $pdf->AddPage();
        $page5 = $pdf->importPage(5);
        $pdf->useTemplate($page5);
        $pdf->AddPage();
        $page6 = $pdf->importPage(6);
        $pdf->useTemplate($page6);
        $pdf->AddPage();
        $page7 = $pdf->importPage(7);
        $pdf->useTemplate($page7);
        $pdf->AddPage();
        $page8 = $pdf->importPage(8);
        $pdf->useTemplate($page8);
        $pdf->AddPage();
        $page9 = $pdf->importPage(9);
        $pdf->useTemplate($page9);
        $newFileName = DOMAIN_NAME.'_Terms_Conditions_Recruitment_'.$client.'_'.mt_rand().'pdf';
        $termsFilePath = 'rates/'.$newFileName;
        $pdf->Output('rates/'.$newFileName, 'F');
    }catch (Exception $e0){
        $e0->getMessage();
    }
    try {
        $rates = $_POST['rates_file'];
        $merged_file_name = DOMAIN_NAME.'_tc_rates_'.$client.'_'.mt_rand().'.pdf';
        $merged_file_path = 'rates/'.$merged_file_name;
        shell_exec('gs -dNOPAUSE -sDEVICE=pdfwrite -sOUTPUTFILE="'.$merged_file_path.'" -dBATCH "'.$termsFilePath.'" "'.$rates.'"');
    }catch (Exception $e1){
        $e1->getMessage();
    }
    try {
        $positions = $position.' '.$position2.' '.$position3.' '.$position4;
        $saveStatus = saveGeneratedHireRates($mysqli, $client,$positions,$award, $_POST['client_email'], $merged_file_path,  date('Y-m-d H:i:s'));
    }catch (Exception $e){
       echo $e->getMessage();
    }
    if($saveStatus != false){
        if(!empty($merged_file_path)) {
            try {
                echo generateHireRatesNotification($_POST['client_email'], 'outapay@outapay.com.au', '', 'Labour Hire Rates', 'sales@outapay.com.au', DOMAIN_NAME, $_POST['email_body'], $merged_file_path,$proof1,$proof2,$proof3, $client, $award, $saveStatus);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }else{
            echo 'Error - Rates file not attached';
        }
    }else{
        echo 'Error - Generated Rates Could not be saved';
    }
}else if($action == 'DISPLAY'){
    echo displayGeneratedHireRates($mysqli);
}