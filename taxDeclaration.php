<?php
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once ('includes/fpdf182/fpdf.php');
require_once ('includes/FPDI-2.3.2/src/autoload.php');
require_once ('includes/FPDI-2.3.2/src/FpdfTpl.php');
use setasign\Fpdi\Fpdi;
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
header('Content-Type: application/json');
$headers = apache_request_headers();
if (isset($headers['CsrfToken'])) {
    if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
        exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
} else {
    exit(json_encode(['error' => 'No CSRF token.']));
}
$fullName = base64_decode($_POST['fullName']);
$mobileNo = base64_decode($_POST['mobileNo']);
$conEmail = $_REQUEST['conEmail'];
$imgData = $_POST['imageSrc'];
$b64 = 'data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$filename = 'signature-'.time().'.png';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}

$signaturePath = './tax/'.$filename;

file_put_contents($signaturePath, $fileData);

$pdf = new Fpdi();
$pdf->AddPage();
//$old_pdf = "tax/TFN_declaration_form_N3092.pdf";
$old_pdf = base64_decode($_POST['fileSubmitted']);
$new_pdf = "tax/CPS_TFN_declaration_form_".time().".pdf";

//exec( "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=".$new_pdf." ".$old_pdf."");
shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="'.$new_pdf.'" "'.$old_pdf.'"');
//exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="'.$new_pdf.'" "'.$old_pdf.'"');

$pdf->setSourceFile($new_pdf);

$page1 = $pdf->importPage(1);
$pdf->useTemplate($page1);
$pdf->AddPage();
$page2 = $pdf->importPage(2);
$pdf->useTemplate($page2);
$pdf->AddPage();
$page3 = $pdf->importPage(3);
$pdf->useTemplate($page3);
$pdf->AddPage();
$page4 = $pdf->importPage(4);
$pdf->useTemplate($page4);
$pdf->AddPage();

$tplId = $pdf->importPage(5);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplId);
// The new content
$fontSize = '10';
$fontColor = `255,0,0`;
$yleft = 185;
$ytop = 135;
$year = date('Y');
$month = date('m');
$day = date('d');

//set the font, colour and text to the page.
$pdf->SetFont("arial", "", 10);
//$pdf->SetTextColor($fontColor);
$ext = pathinfo($filename, PATHINFO_EXTENSION);
//$image_format = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));
$pdf->Image($signaturePath,105,140,70,10,'png');

$pdf->Text(188,149,$year[0]);
$pdf->Text(193,149,$year[1]);
$pdf->Text(198,149,$year[2]);
$pdf->Text(203,149,$year[3]);

$pdf->Text(175,149,$month[0]);
$pdf->Text(180,149,$month[1]);

$pdf->Text(162,149,$day[0]);
$pdf->Text(167,149,$day[1]);

$pdf->AddPage();
$page6 = $pdf->importPage(6);
$pdf->useTemplate($page6);
//$pdf->Write(0,"AAAA");
$newFileName = 'taxForm-'.time().'.pdf';
$filePath = __DIR__.'/tax/'.$newFileName;
//see the results
try {
    $pdf->Output(__DIR__.'/tax/'.$newFileName, 'F');
    unlink($old_pdf);
    unlink($new_pdf);
    unlink($signaturePath);
}catch (Exception $e1){
    generateErrorNotification('TAX FORM ERROR ',$e1->getMessage());
    $e1->getMessage();
}
try {
    $canId = getCandidateIdByMobileNo($mysqli,$mobileNo);
    if(!file_exists('documents/'.$canId)){
        mkdir('documents/'.$canId, 0777);
        chown('./documents/' . $canId,'chandler');
    }
    if(!empty($canId)) {
        copy($filePath, './documents/' . $canId . '/' . $newFileName);
        updateCandidateDocs($mysqli, $canId, 46, $newFileName, './documents/' . $canId . '/'.$newFileName, '', '', '', '');
    }
}catch(Exception $e2){
    generateErrorNotification('TAX FORM ERROR ',$e2->getMessage());
    $e2->getMessage();
}
try {
    $mailStatus = generateTaxFormMail('ChandlerAccounts@chandlerservices.com.au',__DIR__.'/tax/'.$newFileName,$fullName,$mobileNo,$conEmail);
    if($mailStatus == 'SUCCESS'){
        echo 'Tax Form Submitted Successfully';
    }else{
        generateErrorNotification('TAX FORM ERROR ',$mailStatus);
        echo 'Error Submitting Tax Form';
    }
}catch (Exception $e3){
    generateErrorNotification('TAX FORM ERROR ',$e3->getMessage());
    $e3->getMessage();
}



?>

