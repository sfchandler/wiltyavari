<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 16/08/2017
 * Time: 10:38 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once("includes/TCPDF-master/chandlerpdf/tcpdf_include.php");
require_once("includes/PHPExcel-1.8/Classes/PHPExcel.php");

$clientid = $_POST['clientid'];
$positionid = $_REQUEST['positionid'];
$startDate = $_POST['startDate'];
$endDate = $_POST['endDate'];
$candidateId = $_POST['candidateId'];
$jobCode = getJobCodeByClientPosition($mysqli,$clientid,$positionid);
$payRule = getPayruleByJobCode($mysqli,$jobCode);


// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Swarnajith Fernando');
$pdf->SetTitle('TimeSheet Calculations');
$pdf->SetSubject('Chandler TimeSheet');
$pdf->SetKeywords('Chandler TimeSheet');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' generated on '.date('d/m/Y'), PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('helvetica', '', 10);

// add a page
$pdf->AddPage();


// define some HTML content with style
$html = $html.'
<style>
table {
    table-layout: auto;
    border-collapse: collapse;
    width: 100%;
    font-size: 8pt;
    /*table-layout:fixed;word-wrap:break-word;*/
}
td{
    white-space: nowrap;
    text-align: right;
}
</style>
<div>Employee ID:&nbsp;'.$candidateId.'&nbsp;&nbsp;&nbsp;Employee Name:&nbsp;'.getCandidateFirstNameByCandidateId($mysqli,$candidateId).' '.getCandidateLastNameByCandidateId($mysqli,$candidateId).'</div>
<table id="shiftTable" border="1">
                    <thead>
                        <tr>
                            <th align="center">Shift Day</th>
                            <th align="center">Shift Date</th>
                            <th align="center">Start Time</th>
                            <th align="center">Break</th>
                            <th align="center">End Time</th>
                            <th align="center">Total Hours</th>
                            <th align="center">Ordinary<br>Time(T1)/DAY</th>
                            <th align="center">AFTERNOON</th>
                            <th align="center">NIGHT</th>
                            <th align="center">SAT</th>
                            <th align="center">SUN</th>
                            <th align="center">Overtime(T1.5)</th>
                            <th align="center">Double Time(T2)</th>
                            <th align="center">Public Holiday(T2.5)</th>
                            <th class="expand" align="center">Week Ending Date</th>
                        </tr>
                    </thead>
                    <tbody class="shiftBody">';
$rows = $rows.getTimeSheetData($mysqli,$clientid,$candidateId,$positionid,$jobCode,$payRule,$startDate,$endDate);
$rows = $rows.payruleProcessing($mysqli,$clientid,$candidateId,$positionid,$jobCode,$payRule,$startDate,$endDate);
$html = $html.$rows;
$html = $html.'</tbody></table>';

// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');
// reset pointer to the last page
$pdf->lastPage();

// Close and output PDF document
// This method has several options, check the source code documentation for more information.
$fileNamePDF = 'timesheet_'.time().'.pdf';
$fileNameExcel = 'timesheet_'.time().'.xlsx';
$htmlFile = 'timesheet_'.time().'.html';
$pdf->Output(__DIR__.'/timesheet/'.$fileNamePDF, 'F');
echo './timesheet/'.$fileNamePDF;
?>