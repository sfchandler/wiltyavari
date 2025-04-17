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
$fullName = base64_decode($_POST['superFullName']);
$mobileNo = base64_decode($_POST['superMobileNo']);
$conEmail = $_REQUEST['conEmail'];
$imgData = $_POST['imageSuperSrc'];
$b64 = 'data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$filename = 'signature-'.time().'.png';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}

$signaturePath = './super/'.$filename;

file_put_contents($signaturePath, $fileData);

$pdf = new Fpdi();
$pdf->AddPage();
$old_pdf = base64_decode($_POST['superFileSubmitted']);
$new_pdf = "super/SUPER17983Superannuation_standard_choice_form_".time().".pdf";

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
$page5 = $pdf->importPage(5);
$pdf->useTemplate($page5);
$pdf->AddPage();

$tplId = $pdf->importPage(6);
// use the imported page and place it at point 10,10 with a width of 100 mm
$pdf->useTemplate($tplId);
// The new content
$fontSize = '10';
$fontColor = '255,0,0';
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
$pdf->Image($signaturePath,15,180,100,10,'png');

$pdf->Text(178,192,$year[0]);
$pdf->Text(184,192,$year[1]);
$pdf->Text(189,192,$year[2]);
$pdf->Text(194,192,$year[3]);

$pdf->Text(163,192,$month[0]);
$pdf->Text(168,192,$month[1]);

$pdf->Text(148,192,$day[0]);
$pdf->Text(153,192,$day[1]);

$pdf->AddPage();
$page7 = $pdf->importPage(7);
$pdf->useTemplate($page7);

$newFileName = 'superForm-'.time().'.pdf';
$filePath = __DIR__.'/super/'.$newFileName;
//see the results
try {
    $pdf->Output(__DIR__.'/super/'.$newFileName, 'F');
    unlink($old_pdf);
    unlink($new_pdf);
    unlink($signaturePath);
}catch (Exception $e1){
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
         updateCandidateDocs($mysqli, $canId, 45, $newFileName, './documents/' . $canId . '/'.$newFileName, '', '', '', '');
     }
}catch(Exception $e2){
    echo $e2->getMessage();
}
try {
    $mailStatus = generateSuperannuationFormMail('ChandlerAccounts@chandlerservices.com.au',__DIR__.'/super/'.$newFileName,$fullName,$mobileNo,$conEmail);
    if($mailStatus == 'SUCCESS'){
        echo 'Superannuation Form Submitted Successfully';
    }else{
        echo 'Error Submitting Superannuation Form';
    }
}catch (Exception $e3){
    $e3->getMessage();
}
?>

