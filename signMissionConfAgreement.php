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
        $source_pdf = 'docform/CONFIDENTIALITY AGREEMENT- MASTER.pdf';
        $new_pdf = 'junk/Mission_Confidentiality_Agreement_' . $canId . '_' . time() . '.pdf';
        shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $new_pdf . '" "' . $source_pdf . '"');
        $pageCount = $pdf->setSourceFile($new_pdf);
        $pdf->SetAutoPageBreak(false);
        for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
            $templateId = $pdf->importPage($pageNo);
            $pdf->AddPage();
            $pdf->useTemplate($templateId);
            $pdf->SetFont("arial", "", 10);
            if ($pageNo == 5) {
                $pdf->SetFont("arial", "B", 11);
                $pdf->Text(80, 117, $candidateName);
                $pdf->Image($signaturePath, 68, 123, 100, 30, 'png');
                $pdf->Text(50, 159, date('d/m/Y'));
                $pdf->Text(122, 159, date('H:i:s'));
            }
        }
        $newFileName = 'Mission_Confidentiality_Agreement_' . $canId . '_' . time() . '.pdf';
        $filePath = __DIR__ . '/documents/' . $canId . '/' . $newFileName;
        $pdf->Output(__DIR__ . '/documents/' . $canId . '/' . $newFileName, 'F');
        updateCandidateDocs($mysqli, $canId, 79, $newFileName, './documents/' . $canId . '/' . $newFileName, '', '', '', 'SIGNED');
        generateNotification(base64_decode($conEmail),'outapay@outapay.com','','Mission Foods Confidentiality Agreement Submission',DEFAULT_EMAIL,DOMAIN_NAME,$candidateName.' ('.$canId.') has submitted Mission Foods Confidentiality Agreement.','','');
        echo 'SUCCESS';
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
