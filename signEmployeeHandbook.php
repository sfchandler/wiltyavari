<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once('includes/fpdf182/fpdf.php');
require_once('includes/FPDI-2.3.2/src/autoload.php');
require_once('includes/FPDI-2.3.2/src/FpdfTpl.php');
use setasign\Fpdi\Fpdi;

$action = $_REQUEST['action'];
$canId = base64_decode($_REQUEST['canId']);
$candidateName = getCandidateFullName($mysqli, $canId);
$conEmail = base64_decode($_REQUEST['conEmail']);
if ($action == 'SUBMIT') {
    try {
        $imgData = $_POST['imageSrc'];
        $b64 = 'data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
        $dat = explode(',', $imgData);
        $filename = 'signature-' . time() . '.png';
        if (($fileData = base64_decode($dat[1])) === false) {
            exit('Base64 decoding error.');
        }
        $signaturePath = './junk/' . $filename;
        file_put_contents($signaturePath, $fileData);
        $pdf = new Fpdi();
        $source_pdf = 'docform/EmployeeHandbook.pdf';
        $new_pdf = 'junk/EmployeeHandbook_' . $canId . '_' . time() . '.pdf';
        shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $new_pdf . '" "' . $source_pdf . '"');
        $pageCount = $pdf->setSourceFile($new_pdf);
        $pdf->SetAutoPageBreak(false);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
            $pdf->SetFont("arial", "", 10);
            if ($pageNo == 47) {
                $pdf->SetFont("arial", "", 11);
                $pdf->Text(32, 70, $candidateName);
                $pdf->Image($signaturePath, 45, 73, 80, 20, 'png');
                $pdf->Text(45, 101, date('d/m/Y H:i:s'));
            }
        }
        $newFileName = 'EmployeeHandbook_' . $canId . '_' . time() . '.pdf';
        $filePath = __DIR__ . '/documents/' . $canId . '/' . $newFileName;
        $pdf->Output(__DIR__ . '/documents/' . $canId . '/' . $newFileName, 'F');
        updateCandidateDocs($mysqli, $canId, 83, $newFileName, './documents/' . $canId . '/' . $newFileName, '', '', '', 'SIGNED');
        generateNotification(base64_decode($conEmail),'outapay@outapay.com','','Employee Handbook Submission',DEFAULT_EMAIL,DOMAIN_NAME,$candidateName.' ('.$canId.') has submitted Employee Handbook.','','');
        echo 'SUCCESS';
    } catch (Exception $e) {
        echo 'ERROR'.$e->getMessage();
    }
}
