<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once ('includes/fpdf182/fpdf.php');
require_once ('includes/FPDI-2.3.2/src/autoload.php');
require_once ('includes/FPDI-2.3.2/src/FpdfTpl.php');
use setasign\Fpdi\Fpdi;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$headers = apache_request_headers();
if (isset($headers['CsrfToken'])) {
    if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
        exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
} else {
    exit(json_encode(['error' => 'No CSRF token.']));
}
$client_name = base64_decode($_POST['client_name']);
$client_title = base64_decode($_POST['client_title']);
$client = base64_decode($_POST['client']);
$client_abn = base64_decode($_POST['client_abn']);
$client_address = base64_decode($_POST['client_address']);
$client_phone = base64_decode($_POST['client_phone']);
$client_fax = base64_decode($_POST['client_fax']);
$id = base64_decode($_POST['id']);
$ip = base64_decode($_POST['ip']);
$imgData = $_POST['imageSrc'];
$b64 = 'data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$filename = 'signature-'.time().'.png';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}
$signaturePath = './rates/'.$filename;
file_put_contents($signaturePath, $fileData);

$pdf = new Fpdi();

$old_pdf = base64_decode($_POST['r_file']);
$new_pdf = "rates/rate_s_".time().mt_rand().".pdf";
shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="'.$new_pdf.'" "'.$old_pdf.'"');

$pageCount = $pdf->setSourceFile($new_pdf);
$pdf->SetAutoPageBreak(false);
for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
    $templateId = $pdf->importPage($pageNo);
    $pdf->AddPage();
    $pdf->useTemplate($templateId);
    $pdf->SetFont("arial", "", 10);
    if($pageNo == 2){
        $pdf->Text(77, 36, date('F'));
        $pdf->Text(106, 36, date('jS'));
        $pdf->Text(145, 36, date('Y'));
        $pdf->SetFont("arial", "", 9);
        $pdf->Text(30, 60, strtoupper($client));
        $pdf->Text(128, 60, $client_abn);
        $pdf->Text(20, 65, $client_address);
    }
    if($pageNo == 9){
        $pdf->SetFont("arial", "", 10);
        $pdf->Text(55, 83, $client_name);
        $pdf->Text(55, 90, $client_title);
        $pdf->Text(55, 97, $client);
        $pdf->Text(55, 105, $client_address);
        $pdf->Text(55, 112, $client_phone);
        $pdf->Text(55, 120, $client_fax);

        $pdf->Text(25, 214, strtoupper($client));
        $pdf->Text(45, 230, $client_abn);
        $pdf->Image($signaturePath,45,231,80,10,'png');
        $pdf->Text(45, 245, $client_title);
        $pdf->Text(45, 253, $client_name);
        $pdf->Text(45,262,date('d/m/Y'));
    }
   /* if($pageNo == $pageCount){
        $pdf->SetFont("arial", "", 10);
        $pdf->Text(55, 83, $client_name);
        $pdf->Text(55, 83, $client_name);
        $pdf->Text(55, 90, $client_title);
        $pdf->Text(55, 97, $client);
        $pdf->Image($signaturePath,45,231,80,10,'png');
        $pdf->Text(45,262,date('d/m/Y'));
    }*/
}
$newFileName = 'chandlerpacific_tc_rates_signed_'.$client.'_'.time().'.pdf';
$filePath = __DIR__.'/rates/'.$newFileName;
try {
    $pdf->Output(__DIR__.'/rates/'.$newFileName, 'F');
}catch (Exception $e1){
    echo $e1->getMessage();
}

try {
    $signed_rate_file = 'rates/'.$newFileName;
    $updateStatus = updateGeneratedHireRates($mysqli,$id,$ip,$signed_rate_file,date('Y-m-d H:i:s'));
    if($updateStatus) {
        echo generateNotification('chandleraccounts@chandlerservices.com.au','outapay@outapay.com','','Hire Rate Signed Submission by '.$client_name,DEFAULT_EMAIL,'Hire Rate Submission','Labour Hire Rate Signed & Submitted by '.$client_name.' for '.$client,$filePath,'');
    }else{
        echo 'Error - Updating Submission';
    }
}catch (Exception $e3){
    echo $e3->getMessage();
}
?>

