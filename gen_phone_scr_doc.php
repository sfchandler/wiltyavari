<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');
require_once "includes/TCPDF-main/tcpdf.php";
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$candidate_id = $_REQUEST['candidate_id'];
$q1 = $_REQUEST['q1'];
$q2 = $_REQUEST['q2'];
$q2_comment = $_REQUEST['q2_comment'];
$q3_comment = $_REQUEST['q3_comment'];
$q4_comment = $_REQUEST['q4_comment'];
$q5_comment = $_REQUEST['q5_comment'];
$q6 = $_REQUEST['q6'];
$q6_comment = $_REQUEST['q6_comment'];
$q7 = $_REQUEST['q7'];
$q8_answer = $_REQUEST['q8_answer'];
$q9_comment = $_REQUEST['q9_comment'];
$q10_textbox = $_REQUEST['q10_textbox'];
$q11_textbox = $_REQUEST['q11_textbox'];
$q12_textbox = $_REQUEST['q12_textbox'];
$q13_textbox = $_REQUEST['q13_textbox'];
$q14_textbox = $_REQUEST['q14_textbox'];
$q15_textbox = $_REQUEST['q15_textbox'];
$q16_comment = $_REQUEST['q16_comment'];
$decision = $_REQUEST['decision'];
$other_comments = $_REQUEST['other_comments'];
$client_id = $_REQUEST['client_id'];
$position_id = $_REQUEST['position_id'];
$jb_desc = $_REQUEST['jb_desc'];
$cons_id = $_REQUEST['cons_id'];

class PHONESCREENPDF extends TCPDF {
    public function Header() {
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 5, 40, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('helvetica', 'B', 11);
        $this->Ln(5);
        $this->SetTextColor(0,0,0);
        $this->Ln(5);
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
    $pdf = new PHONESCREENPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->setHeaderTemplateAutoreset(true);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor(' ');
    $pdf->SetTitle('PHONE INTERVIEW');
    $pdf->SetSubject('PHONE INTERVIEW');
    $pdf->SetKeywords('PHONE INTERVIEW');
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
            <h3>PHONE SCREENING INTERVIEW ANSWERS</h3>
            <table border="1">
                 <thead>
                      <tr>
                          <th align="center">Question</th>
                          <th align="center">Answer</th>
                      </tr>
                </thead>
                <tbody>';
    $html = $html.'<tr><td class="q_title">Profile ID : '.$candidate_id.'  Mobile No : '.getCandidateMobileNoByCandidateId($mysqli,$candidate_id).'</td><td>'.date('Y-m-d H:i:s').'</td></tr>';
    $html = $html.'<tr><td class="q_title">Introduction</td><td></td></tr>';
    $html = $html.'<tr><td><p>Good morning '.getCandidateFullName($mysqli,$candidate_id).', I am '.getConsultantName($mysqli,$_REQUEST['consId']).' calling from Chandler Personnel regarding a job opening that you have applied</p>
                            <b> Do you have 10-20 minutes to talk?</b>
                        </td>
                        <td>
                        <b>'.$q1.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr><td class="q_title">Inform about call recording</td><td></td></tr>';
    $html = $html.'<tr><td>Ok, great! Just to let you know, this call is being recorded for quality and training purposes.
                            <b> Is that okay?</b>
                        </td>
                        <td>
                        <b>'.$q2.'</b>
                        <b>'.$q2_comment.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr><td class="q_title">Call Overview</td><td></td></tr>';
    $html = $html.'<tr><td>During this call, we\'ll discuss the role, your relevant experience, location preferences, availability, and pay expectations. I will be happy to answer any questions you may have about the position or the hiring process at the end of the call.                            
                        </td>
                        <td></td>
                    </tr>';
    $html = $html.'<tr><td class="q_title">Ask about current employment status</td><td></td></tr>';
    $html = $html.'<tr><td>Can you tell me about your current employment status? And why you are looking for a new position.</td><td><b>'.$q3_comment.'</b></td></tr>';
    $html = $html.'<tr><td>Describe the casual role (Refer to the job description on right side)     
                             Thanks for sharing with me,
So, the role I am calling about is ...
                               Does that sound like something you would be interested in?
                        </td>
                        <td>
                            <b>'.$q4_comment.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr>    
                        <td>
                        '.getClientNameByClientId($mysqli,$client_id).' <br>'.getCandidatePositionNameById($mysqli,$position_id).'
                        </td>
                        <td><div class="q_title">Job Description</div>
                        '.trim(strip_tags($jb_desc)).'</td>
                    </tr>';
    $html = $html.'<tr><td>Discuss relevant experience<br>Can you tell me about any relevant experience you have had in the past that would contribute to your success in this casual role?
                        </td>
                        <td>
                        <b>Consultant : '.getConsultantName($mysqli,$_REQUEST['cons_id']).'</b>
                        <b>Reason for suitability : '.getCandidateReasonForSuitability($mysqli,$_REQUEST['candidate_id']).'</b>
                        <br>
                        <b>'.$q5_comment.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr>    
                        <td class="q_title">
                               Medical/Police Check
                        </td><td></td>
                    </tr>';
    $html = $html.'<tr><td>Do you have any prior or pending criminal convictions that may affect your application?</td>
                        <td>
                         <b>'.$q6.'</b>
                         <b>'.$q6_comment.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr><td>As per our company policy you are required to have a police check done within the last 3 years. Therefore do you have a valid police check done within the last 3 years? 
                        </td>
                        <td>
                            <b>'.$q7.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr><td>
If not, are you happy to do one? It will only be taken off your first pay slip ($49), and we will provide you a copy.                     
                        </td>
                        <td>
                            <b>'.$q8_answer.'</b>
                        </td>
                    </tr>';
    $html = $html.'<tr><td>Do you have any medical conditions that might affect your work or impair your ability to perform manual handling tasks, such as lifting?
                        </td>
                        <td>
                         '.$q9_comment.'
                        </td>
                    </tr>';
    $html = $html.'<tr><td class="q_title">
                                Inquire about location and travel preferences     
                        </td><td></td>
                     </tr>';
    $html = $html.'<tr><td>Could you please tell me what is your current location (suburb)?
                        </td>
                        <td>
                            <b>'.$q10_textbox.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td>If you are successful in this role, will you be travelling by public transport or driving a car?
                        </td>
                        <td>
                            <b>'.$q11_textbox.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td> How long are you willing to travel for? Example: 30-45 minutes 
                       </td>
                        <td>
                            <b>'.$q12_textbox.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td class="q_title">
                                Inquire about location and travel preferences
                        </td><td></td></tr>';
    $html = $html.'<tr><td> What is your general availability like?
                        </td>
                        <td>
                        <b>'.$q13_textbox.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td>Are you able to work on short notice, and do you have any preferred days or hours?
                        </td>
                        <td>
                        <b>'.$q14_textbox.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td> Are you looking for morning, afternoon or night shifts? (Depending on role)  
                       </td>
                        <td>
                        <b>'.$q15_textbox.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td class="q_title">
                            Address any questions from the candidate
                        </td><td></td>
                     </tr>';
    $html = $html.'<tr><td> Now that we\'ve discussed the main aspects of the role, do you have any questions about the casual position, the company, or the hiring process that I can help answer for you?                           
                        </td>
                        <td>
                            <b>'.$q16_comment.'</b>
                        </td>
                     </tr>';
    $html = $html.'<tr><td class="q_title">
                            Next steps
                        </td><td></td>
                        </tr>';
    $html = $html.'<tr>
                        <td>   
                              So, '.getCandidateFullName($mysqli,$candidate_id).', the next step is to review your application with our hiring team. I will get back to you within 24 hours. If you are successful, I will send you a registration link via email.
                                You can call me on the general line during office hours (8:30am – 5pm, Monday - Friday) if you need any assistance with filling it out.
                            After we have your documents, we will be in touch to organise the next steps.
                        </td><td></td>
                     </tr>';
    $html = $html.'<tr>    
                        <td class="q_title">
                            Suitable/Unsuitable
                        </td><td><b>'.$decision.'</b></td>
                     </tr>';
    $html = $html.'<tr>    
                        <td>
                            <p>
                             Any Other Comments
                            </p>
                        </td>
                        <td>
                            <b>'.$other_comments.'</b>
                        </td>
                     </tr>
                </tbody>
              </table>
           </div>';

    if(!file_exists('documents/'.$candidate_id)){
        mkdir('./documents/'.$candidate_id, 0777);
        chown('./documents/' . $candidate_id,'chandler');
    }
    $time = time();
    $fileName = 'phonescr-'.$candidate_id.'_'.$time.'.pdf';
    $filePath = './documents/'.$candidate_id.'/'.$fileName;
    $pdf->writeHTML($html, true, false, false, false, '');
    $pdf->lastPage();
    $pdf->Output(__DIR__.'/documents/'.$candidate_id.'/'.$fileName, 'F');
    if(!empty($filePath)){
        copy($filePath,'./documents/' . $candidate_id.'/'.$fileName);
        updateCandidateDocs($mysqli,$candidate_id, 35,$fileName,'./documents/' . $candidate_id.'/'.$fileName,'','','','');
        assignPositionEmpoloyee($mysqli,$candidate_id,$position_id);
        echo 'SUCCESS';
        /*$canId = base64_encode($candidate_id);
        header("Location: candidateMain.php?canId=$canId");*/
    }
}catch (Exception $e){
    echo $e->getMessage();
}