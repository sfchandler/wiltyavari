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
$conEmail = $_REQUEST['conEmail'];
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
        $source_pdf = 'docform/Mission_Visitors_Induction_Instructions.pdf';
        $new_pdf = 'junk/Mission_Visitor_Induction_' . $canId . '_' . time() . '.pdf';
        shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $new_pdf . '" "' . $source_pdf . '"');
        $pageCount = $pdf->setSourceFile($new_pdf);
        $pdf->SetAutoPageBreak(false);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
            $pdf->SetFont("arial", "", 10);
            if ($pageNo == 2) {
                $pdf->SetFont("arial", "B", 11);
                $pdf->Text(32, 248, 'Name:   '.$candidateName);
                $pdf->Image($signaturePath, 40, 243, 75, 15, 'png');
                $pdf->Text(45, 267, date('d/m/Y'));
            }
        }
        $newFileName = 'Mission_Visitor_Induction_' . $canId . '_' . time() . '.pdf';
        $filePath = __DIR__ . '/documents/' . $canId . '/' . $newFileName;
        $pdf->Output(__DIR__ . '/documents/' . $canId . '/' . $newFileName, 'F');
        updateCandidateDocs($mysqli, $canId, 78, $newFileName, './documents/' . $canId . '/' . $newFileName, '', '', '', 'SIGNED');
        generateNotification(base64_decode($conEmail),' ','','Mission Foods Visitor Induction Submission',DEFAULT_EMAIL,DOMAIN_NAME,$candidateName.' ('.$canId.') has submitted Mission Foods Visitor Induction','','');
        echo 'SUCCESS';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
