<?php
require_once('includes/db_conn.php');
require_once('includes/functions.php');
require_once "includes/TCPDF-main/tcpdf.php";
require_once('includes/fpdf182/fpdf.php');
require_once('includes/FPDI-2.3.2/src/autoload.php');
require_once('includes/FPDI-2.3.2/src/FpdfTpl.php');
ini_set('max_execution_time', 180);
ini_set('pcre.backtrack_limit', 1000000);
date_default_timezone_set('Australia/Melbourne');

use setasign\Fpdi\Fpdi;
$statPdf = new Fpdi();
$statPdf->AddPage();
$stat_source_pdf = "docform/StatutoryDeclaration_Criminal_Convictions_v3.pdf";
$stat_pdf = "docform/StatutoryDeclaration_" . time() . ".pdf";
shell_exec('gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $stat_pdf . '" "' . $stat_source_pdf . '"');
$statPdf->setSourceFile($stat_pdf);
$page1 = $statPdf->importPage(1);
$statPdf->useTemplate($page1);
$statPdf->SetFont("Times", "", 12);
$fontSize = '12';
$fontColor = '0,0,0';
$statPdf->SetTextColor($fontColor);

$neverConvicted = 'X';
$neverImprisonment = 'X';

$statPdf->Text(27, 117, $neverConvicted);
$statPdf->Text(27, 127, $neverImprisonment);
$statPdf->Text(46, 214, date('d'));
$statPdf->Text(70, 214, date('M'));
$statPdf->Text(80, 214, date('Y'));

$stFileName = 'statDecSample_'. time() . '.pdf';
$stFilePath = __DIR__ . '/docform/' . $stFileName;
$statPdf->Output(__DIR__ . '/docform/' . $stFileName, 'I');