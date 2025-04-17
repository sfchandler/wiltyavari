<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
//require_once("includes/TCPDF-master/clientsurvey/tcpdf_include.php");
require_once "includes/TCPDF-main/tcpdf.php";
date_default_timezone_set('Australia/Melbourne');
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/

$client_id = base64_decode($_REQUEST['client_id']);
$log_id = base64_decode($_REQUEST['log_id']);
$client_name = base64_decode($_REQUEST['client_name']);
$client_position = base64_decode($_REQUEST['client_position']);
$client_email = base64_decode($_REQUEST['client_email']);
$q1 = $_REQUEST['q1'];
$q1_exp = htmlspecialchars($_REQUEST['q1_exp'],ENT_QUOTES);
$q2 = $_REQUEST['q2'];
$q2_exp = htmlspecialchars($_REQUEST['q2_exp'],ENT_QUOTES);
$q3 = $_REQUEST['q3'];
$q3_exp = htmlspecialchars($_REQUEST['q3_exp'], ENT_QUOTES);
$q4 = $_REQUEST['q4'];
$q4_exp = htmlspecialchars($_REQUEST['q4_exp'], ENT_QUOTES);


class CLIENTSURVEYPDF extends TCPDF {
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
        $this->write2DBarcode(DOMAIN_URL, 'QRCODE,H', 5, 282, 14, 14, $style, 'N');
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
    if(!empty($client_id)){
        $clientName = getClientNameByClientId($mysqli,$client_id);
        $pdf = new CLIENTSURVEYPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(' ');
        $pdf->SetTitle('CLIENT SURVEY');
        $pdf->SetSubject('CLIENT SURVEY');
        $pdf->SetKeywords('CLIENT SURVEY');
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
        </style>
        <div align="left">
        '.$clientName.'
         <br>
        '.$client_name.'
         <br>
        '.$client_position.'
         <br>
        '.$client_email.'
         <br>
         Submitted at: '.date('Y-m-d H:i:s').'
        </div>
        <div align="center" style="width: 860px; padding: 5px 5px 5px 5px;">
                <h3>CLIENT SURVEY</h3>
                <table class="table">
                    <tbody>
                    <tr>
                        <td>
                            <ol>
                                <li>Recruitment Services:</li>
                                <p>Are you satisfied with the quality and fit of candidates we have sourced for your open positions?
                                    <br>
                                    <span style="color: blue"><b>'.$q1.'</b></span>
                                </p>
                                <p>
                                    Were there any specific areas where you feel we could improve?
                                    <br>
                                    <span style="color: blue"><b>'.$q1_exp.'</b></span>
                                 </p>
                                <li>After-Hours Service:</li>
                                <p>
                                    Did our after-hours service meet your expectations related to responsiveness and effectiveness in handling any issues or inquiries?
                                    <br>
                                    <span style="color: blue"><b>'.$q2.'</b></span>
                                 </p>
                                <p>
                                    Were there any specific areas where you feel we could improve?
                                    <br>
                                    <span style="color: blue"><b>'.$q2_exp.'</b></span>
                                 </p>
                                <li>Payroll Services:</li>
                                <p>Are you satisfied with the accuracy and timeliness of the payroll processing/Invoicing we provide?
                                <br>
                                    <span style="color: blue"><b>'.$q3.'</b></span>
                                 </p>
                                <p>
                                    Have there been any issues or areas for improvement that you have noticed?
                                    <br>
                                    <span style="color: blue"><b>'.$q3_exp.'</b></span>
                                 </p>
                                <li>Suggestions for Improvement:</li>
                                <p>Are there any additional services or changes you would suggest to improve our recruitment, after-hours support, or payroll services? We are always looking for ways to better serve you.
                                     <br>
                                    <span style="color: blue"><b>'.$q4.'</b></span>
                                    <br>
                                    <span style="color: blue"><b>'.$q4_exp.'</b></span>
                                </p>
                            </ol>
                        </td>
                    </tr>
                    </tbody>
                </table>
               </div>';

        if(!file_exists('clientDocuments/'.$client_id)){
            mkdir('./clientDocuments/'.$client_id, 0777);
            chown('./clientDocuments/' . $client_id,'chandler');
        }
        $time = time();
        $fileName = 'client-survey-'.$client_id.'_'.$time.'.pdf';
        $filePath = 'clientDocuments/'.$client_id.'/'.$fileName;
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->lastPage();
        $pdf->Output(__DIR__.'/clientDocuments/'.$client_id.'/'.$fileName, 'F');

        if(!empty($filePath)){
             updateClientSurveyLog($mysqli,$log_id,$fileName,$filePath);
             generateNotification('perryd@chandlerpersonnel.com.au','indikaw@chandlerpersonnel.com.au','virana@chandlerpersonnel.com.au','Client survey submitted by '.$clientName,DEFAULT_EMAIL,'Chandler Personnel','Client survey submitted by '.$clientName,$filePath,'');
             echo 'SUCCESS';
        }
    }
}catch (Exception $e){
    echo $e->getMessage();
}