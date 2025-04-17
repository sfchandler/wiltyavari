<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
require_once "includes/TCPDF-main/tcpdf.php";
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$candidate_id = $_POST['candidate_id'];
$cons_id = $_POST['cons_id'];
$referee_name = $_POST['referee_name'];
$referee_email = $_POST['referee_email'];
$company_name = $_POST['company_name'];
$position_held = $_POST['position_held'];
$phone_number = $_POST['phone_number'];
$q1 = $_POST['q1'];
$q2 = $_POST['q2'];
$q3 = $_POST['q3'];
$q4 = $_POST['q4'];
$q5 = $_POST['q5'];
$q6 = $_POST['q6'];
$q7 = $_POST['q7'];
$q8 = $_POST['q8'];
$q9 = $_POST['q9'];
$q10 = $_POST['q10'];
$q11 = $_POST['q11'];
$q12 = $_POST['q12'];
$q13 = $_POST['q13'];

class REFERENCECHECKPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 5, 40, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 11);
        $this->Ln(5);
        $this->SetTextColor(0,0,0);
        $this->Ln(10);
        $this->Cell(0, '', '____________________________________________________________________________________________________________________________________________', 0, $ln = 0, 'R', 0, '', 0, false, 'B', 'B');
    }
    public function Footer()
    {
        $style = array(
            'border' => 2,
            'vpadding' => 'auto',
            'hpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255)
            'module_width' => 1, // width of a single module in points
            'module_height' => 1 // height of a single module in points
        );
        $this->Line(5, 280, 205, 280);
        $this->write2DBarcode(DOMAIN_URL.'/', 'QRCODE,H', 5, 282, 14, 14, $style, 'N');
        $this->SetFont('helvetica', 'R', 8);
        $this->SetY(-19);
        $this->Cell(0, 10,'                      '.COMPANY_AND_ABN, 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-15);
        $this->Cell(0, 10, '                      '.COMPANY_ADDRESS, 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-11);
        $this->Cell(0, 10, '                      '.COMPANY_EMAIL_AND_PHONE, 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->SetY(-4);
    }
}
try {
    $pdf = new REFERENCECHECKPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('REFERENCE CHECK');
    $pdf->SetSubject('REFERENCE CHECK');
    $pdf->SetKeywords('REFERENCE CHECK');
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetMargins(5, 20, 2);
    $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
        require_once(dirname(__FILE__) . '/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->SetFont('helvetica', '', 11);
    $pdf->AddPage();
    //$pdf->SetLineStyle(array('width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 4, 'color' => array(175, 175, 175)));
    $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
    $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
    $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
    $pdf->Line(0, 0, 0, $pdf->getPageHeight());
    $html = $html.'<style>
        table {
            table-layout: auto;
            border-collapse: collapse;
            width: 100%;
            font-size: 10pt;
            /*table-layout:fixed;word-wrap:break-word;*/
        }
        th{
            font-weight: bold;
            color: #2a6395;
            background-color: #c9cccf;
        }
        td{
            white-space: nowrap;
            text-align: justify;
        }
        .q_title{
            text-align: left; 
            font-weight: bold;  
            color:#0c7cd5;
        }
    </style><div align="center" style="width: 860px; padding: 5px 5px 5px 5px;">
            <h3>REFERENCE CHECK</h3>
            <table border="1">
                <tbody>';
    $html = $html.'<tr><td class="q_title">Candidate : '.getCandidateFullName($mysqli,$candidate_id).' ('.$candidate_id.')</td><td>'.date('Y-m-d H:i:s').'</td></tr>';
    $html = $html.'<tr><td class="q_title">Referee Name : '.$referee_name.'</td><td></td></tr>';
    $html = $html.'<tr><td class="q_title">Referee Email : '.$referee_email.'</td><td></td></tr>';
    $html = $html.'<tr><td class="q_title">Company :</td><td>'.$company_name.'</td></tr>';
    $html = $html.'<tr><td class="q_title"></td><td>Position Held(Important to Get) '.$position_held.'</td></tr>';
    $html = $html.'<tr><td></td><td>Telephone Number : '.$phone_number.'</td></tr>';
    $html = $html.'<tr><td class="q_title">Questions</td><td></td></tr>';
    $html = $html.'<tr><td>
                            1.	What was the name of the company that you worked with the applicant and what was your role?  What was the role of the candidate?
                       </td>
                       <td>
                            <b>'.$q1.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            2.	Are you still working for that company?
                       </td>
                       <td>
                            <b>'.$q2.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            3.	How long did you work with the applicant for and how long has the applicant worked in the company (Period of employment)?
                       </td>
                       <td>
                            <b>'.$q3.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            4.	What were the main duties and responsibilities carried out by (him/her) in their role and please describe a typical day of work for the candidate?
                       </td>
                       <td>
                            <b>'.$q4.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            5.	How would you describe (his/her) initiative/attitude on the job?
                       </td>
                       <td>
                            <b>'.$q5.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            6.	How would you describe (his/her) attendance record, reliability, and honesty?
                       </td>
                       <td>
                            <b>'.$q6.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            7.	Can you please describe (his/her) ability to work independently, as well as in a team?
                       </td>
                       <td>
                            <b>'.$q7.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            8.	Are you aware of them having been involved in any conflict in the workplace?
                       </td>
                       <td>
                            <b>'.$q8.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            9.	How does (he/she) handle fast-paced work/stressful situations?
                       </td>
                       <td>
                            <b>'.$q9.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                            10.	How would you assess (his/her) performance in the job? Consider:
                              Strengths?
                              Weaknesses?
                              Key accomplishments?
                       </td>
                       <td>
                            <b>'.$q10.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                           11.	Why did (he/she) leave the company?
                       </td>
                       <td>
                            <b>'.$q11.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                           12.	Did (he/she) ever get injured at work? If Yes, did they claim Work-cover?
                       </td>
                       <td>
                            <b>'.$q12.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td>
                           13.	Are you currently using labour hire or are you looking for help with your current staffing requirements?
                       </td>
                       <td>
                            <b>'.$q13.'</b>
                       </td>
                   </tr>';
    $html = $html.'<tr><td colspan="2">
                           Reference check done by '.getConsultantName($mysqli,$cons_id).'
                       </td>
                   </tr>';
    $html = $html.'</tbody>
              </table>
           </div>';

    saveReferenceCheck($mysqli,$candidate_id,$cons_id,$referee_name,$referee_email,$company_name,$position_held,$phone_number,$q1,$q2,$q3,$q4,$q5,$q6,$q7,$q8,$q9,$q10,$q11,$q12);

    if(!file_exists('documents/'.$candidate_id)){
        mkdir('documents/'.$candidate_id, 0777);
        chown('./documents/' . $candidate_id,'chandler');
    }
    $time = time();
    $fileName = 'ref_check_'.$candidate_id.'_'.$time.'.pdf';
    $filePath = './documents/'.$candidate_id.'/'.$fileName;
    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__.'/documents/'.$candidate_id.'/'.$fileName, 'F');
    if(!empty($filePath)){
        copy($filePath,'./documents/' . $candidate_id.'/'.$fileName);
        updateCandidateDocs($mysqli,$candidate_id, 22,$fileName,'./documents/' . $candidate_id.'/'.$fileName,'','','','');
        generateNotification('','','','Reference Check submission', DEFAULT_EMAIL, 'Chandler','Reference check submitted by'.getConsultantName($mysqli,$cons_id),$filePath,'');
        echo 'SUCCESS';
    }
}catch (Exception $e){
    echo $e->getMessage();
}