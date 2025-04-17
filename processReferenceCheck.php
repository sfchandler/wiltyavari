<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once "includes/TCPDF-main/tcpdf.php";
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$candidate_id = base64_decode($_POST['id']);
$candidate_name = base64_decode($_POST['candidate_name']);
$company_name = base64_decode($_POST['company_name']);
$referee_name = base64_decode($_POST['referee_name']);
$position = base64_decode($_POST['position']);

$phone_number = base64_decode($_POST['phone_number']);
$work_capacity = base64_decode($_POST['work_capacity']);
$your_work_role = base64_decode($_POST['your_work_role']);
$candidate_role = base64_decode($_POST['candidate_role']);
$work_period = base64_decode($_POST['work_period']);
$duties = base64_decode($_POST['duties']);
$initiative = base64_decode($_POST['initiative']);
$work_independent = base64_decode($_POST['work_independent']);
$conflict = base64_decode($_POST['conflict']);
$fast_paced_work = base64_decode($_POST['fast_paced_work']);
$performance = base64_decode($_POST['performance']);
$leave_company = base64_decode($_POST['leave_company']);
$overall_attitude = base64_decode($_POST['overall_attitude']);
$attendance_record = base64_decode($_POST['attendance_record']);
$work_cover = base64_decode($_POST['work_cover']);

$id = base64_decode($_POST['id']);
$conEmail = base64_decode($_POST['conEmail']);
$imgData = $_POST['imageSrc'];
$b64 = 'data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAAD...';
$dat = explode(',',$imgData);
$filename = 'signature-'.time().'.png';
if (($fileData = base64_decode($dat[1])) === false) {
    exit('Base64 decoding error.');
}
$signaturePath = './documents/'.$id.'/'.$filename;
file_put_contents($signaturePath, $fileData);

class REFERENCEPDF extends TCPDF {
    //Page header
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 5, 40, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 9);
        $this->Ln();
        $this->Ln();
        $this->Cell(0, '', 'REFERENCE CHECK QUESTIONNAIRE', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Ln();
        $this->Cell(0, '', 'LABOUR HIRE', 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->SetFont('helvetica', 'I', 8);
        $this->Ln();
        $this->Ln();
        $this->Cell(0,'','___________________________________________________________________________________________________________________________________________________________________________________________________________________________________',0,false, 'C', 0, '', 0, false, 'T', 'M');

    }
    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0,10,'___________________________________________________________________________________________________________________________________________________________',0,false, 'C', 0, '', 0, false, 'T', 'M');
        $this->SetY(-10);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0,10,'REFERENCE CHECK QUESTIONNAIRE - LABOUR HIRE V1',0,false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf = new REFERENCEPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('REFERENCE CHECK QUESTIONNAIRE');
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
$pdf->SetFont('helvetica', '', 9);
$pdf->AddPage();
$html = $html.'
<style>
table {
    table-layout: auto;
    border-collapse: collapse;
    width: 100%;
    font-size: 9pt;
}
td{
    width: 100%;
    white-space: nowrap;
    text-align: justify;
}
</style>';
$html = $html.'<div><b>Candidate Name: </b>'.$candidate_name.'</div>
                <div><b>Company: </b>'.$company_name.'</div>
                <div><b>Referee Name: </b>'.$referee_name.'</div>
                <div><b>Position Held: </b>'.$position.'</div>
                <div><b>Telephone Number: </b>'.$phone_number.'</div>
                <div><b><u>QUESTIONS</u></b></td></div>
                <div><b>1.	In what capacity have you worked with the applicant? What was your role?  What was the role of the candidate?</b></div>
                <div>'.$work_capacity.'</div>
                <div><b>2.	What was your role?</b></div>
                <div>'.$your_work_role.'</div>
                <div><b>3.	What was the role of the candidate?</b></div>
                <div>'.$candidate_role.'</div>
                <div><b>4.	How long have you worked with the applicant?</b></div>
                <div>'.$work_period.'</div>
                <div><b>5.	What were the main duties and responsibilities carried out by (him/her) in their role?</b></div>
                <div>'.$duties.'</div>
                <div><b>6.	How would you describe (his/her) initiative on the job?</b></div>
                <div>'.$initiative.'</div>
                <div><b>7.	Can you please describe (his/her) ability to work independently, as well as in a team?</b></div>
                <div>'.$work_independent.'</div>
                <div><b>8.	Are you aware of them having been involved in any conflict in the workplace?</b></div>
                <div>'.$conflict.'</div>
                <div><b>9.	How does (he/she) handle fast-paced work, stress and conflicts?</b></div>
                <div>'.$fast_paced_work.'</div>
                <div><b>10.	How would you assess (his/her) performance in the job? Consider:</b></div>
                <ul>
                    <li>Strengths</li>
                    <li>Weaknesses?</li>
                    <li>Key accomplishments?</li>
                </ul>
                <div>'.$performance.'</div>
                <div><b>11.	Why did (he/she) leave the company?</b></div>
                <div>'.$leave_company.'</div>
                <div><b>12.	How would you describe (his/her) overall attitude to work?</b></div>
                <div>'.$overall_attitude.'</div>
                <div><b>13. How would you describe (his/her) attendance record, reliability, and honesty?</b></div>
                <div>'.$attendance_record.'</div>
                <div><b>14.	Did (he/she) ever claim WorkCover while employed with you? Did they have any medical conditions which restricted their ability to work?</b></div>
                <div>'.$work_cover.'</div>
                <br>
                <div>
                I hereby declare that the information provided here is correct and accurate.
                <br>
                <br>
                <b>Signature</b>
                <div id="signature">
                   <img src="' . $signaturePath . '"/>    
                    <br>
                   <b>Date:</b> '.date('d/m/Y').'
                </div>
             </div>';
$html = utf8_decode($html);
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->lastPage();
$fileNamePDF = 'ref_check_'.$id.'_'.$company_name.'_'.$position.'_'.time().'.pdf';
$filePath = './documents/'.$id.'/'.$fileNamePDF;
if (!file_exists('documents/' . $id)) {
    mkdir('documents/' . $id, 0777);
}
$pdf->Output(__DIR__.'/documents/'.$id.'/'.$fileNamePDF, 'F');
updateCandidateDocs($mysqli,$id,22,$fileNamePDF,$filePath,'','','','SIGNED');
$mailBody = '<br>Referee Check Questionnaire of ' . $candidate_name . '(' . $id . '), has been submitted for '.$company_name.'-'.$position.' Online<br/><br/>';
echo 'SUCCESS';
?>