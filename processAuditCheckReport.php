<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
require_once "includes/TCPDF-main/tcpdf.php";
date_default_timezone_set('Australia/Melbourne');

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$action = $_REQUEST['action'];
if($action == 'CONSULTANTEXCEL') {
    try {
        $consultantInfo = getAuditCheckData($mysqli, $startDate, $endDate);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'POSITION');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CONSULTANT');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'JOB ORDER NOTIFIED');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'PAYROLL OFFICER');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'PAYROLL OFFICER VERIFIED TIME');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'AUDIT CHECK SUBMITTED TIME');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', 'AUDIT CHECK TYPE');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', 'AUDIT CHECK CONSULTANT CHECK');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', 'AUDIT CHECK PAYROLL CHECK');

    $empExId = '';
    $rowCount = 1;
    foreach ($consultantInfo as $data) {
        $rowCount++;

        if(empty($empExId)) {
            $empExId = $data['candidateId'];
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount,$data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getCandidatePositionNameById($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['consultant']);
            if ($data['jobOrderNotify'] == 1) {
                $jobOrderNotify = 'Yes';
            } else {
                $jobOrderNotify = 'No';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $jobOrderNotify);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['payroll_officer']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['verified_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('j' . $rowCount, $data['checked_time']);
        }elseif($empExId != $data['candidateId']){
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getCandidatePositionNameById($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['consultant']);
            if ($data['jobOrderNotify'] == 1) {
                $jobOrderNotify = 'Yes';
            } else {
                $jobOrderNotify = 'No';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $jobOrderNotify);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['payroll_officer']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['verified_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('j' . $rowCount, $data['checked_time']);
            $empExId = $data['candidateId'];
        }else{
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('F' .  $rowCount,'');
            if ($data['jobOrderNotify'] == 1) {
                $jobOrderNotify = 'Yes';
            } else {
                $jobOrderNotify = 'No';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, '');
        }
        $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, getAuditCheckListType($mysqli, $data['chkType']));
        $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['status']);
        $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $data['payroll_status']);
    }

    $time = time();
    $filePath = './reports/auditCheckReport-' . $time . '.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filePath);
    echo $filePath;
}elseif($action == 'ALLEXCEL') {
    try {
        $consultantInfo = getAuditCheckAllData($mysqli, $startDate, $endDate);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'POSITION');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'CONSULTANT');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'JOB ORDER NOTIFIED');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'PAYROLL OFFICER');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'PAYROLL OFFICER VERIFIED TIME');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', 'AUDIT CHECK SUBMITTED TIME');


    $empExId = '';
    $rowCount = 1;
    foreach ($consultantInfo as $data) {
        $rowCount++;

        if(empty($empExId)) {
            $empExId = $data['candidateId'];
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount,$data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getCandidatePositionNameById($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['consultant']);
            if ($data['jobOrderNotify'] == 1) {
                $jobOrderNotify = 'Yes';
            } else {
                $jobOrderNotify = 'No';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $jobOrderNotify);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['payroll_officer']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['verified_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('j' . $rowCount, $data['checked_time']);
        }elseif($empExId != $data['candidateId']){
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getCandidatePositionNameById($mysqli, $data['positionId']));
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getClientNameByClientId($mysqli, $data['clientId']));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['consultant']);
            if ($data['jobOrderNotify'] == 1) {
                $jobOrderNotify = 'Yes';
            } else {
                $jobOrderNotify = 'No';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $jobOrderNotify);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['payroll_officer']);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['verified_time']);
            $objPHPExcel->getActiveSheet()->setCellValue('j' . $rowCount, $data['checked_time']);
            $empExId = $data['candidateId'];
        }else{
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('F' .  $rowCount,'');
            if ($data['jobOrderNotify'] == 1) {
                $jobOrderNotify = 'Yes';
            } else {
                $jobOrderNotify = 'No';
            }
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, '');
            $objPHPExcel->getActiveSheet()->setCellValue('j' . $rowCount, '');
        }
    }
    $time = time();
    $filePath = './reports/auditCheckReport-' . $time . '.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filePath);
    echo $filePath;
}elseif($action == 'POLICECHECK') {
    try {
        $auditChkInfo = getAuditCheckPoliceCheckData($mysqli, $startDate, $endDate);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'CANDIDATE NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'VISA TYPE');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'LEVEL/POSITION');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'CLIENT');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'AUDIT COMPLETE DATE');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'PAYROLL OFFICER');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'POLICE CHECK TO BE DONE');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', 'COMPLETED DATE');
    $objPHPExcel->getActiveSheet()->setCellValue('J1','LAST WEEKENDING WORKED');
    $objPHPExcel->getActiveSheet()->setCellValue('K1','POLICE CHECK DEDUCTION');
    $objPHPExcel->getActiveSheet()->setCellValue('L1','INTERNAL POLICE CHECK CLEARANCE');
    $objPHPExcel->getActiveSheet()->setCellValue('M1','EXTERNAL POLICE CHECK');
    $objPHPExcel->getActiveSheet()->setCellValue('N1','EXTERNAL POLICE CHECK RECEIPT');

    $empExId = '';
    $rowCount = 1;
    $auditStatus = '';
    $auditedTime = '';
    foreach ($auditChkInfo as $data) {
            $rowCount++;
            $auditLog = getAuditCompletedLastRecord($mysqli,$data['candidateId']);
            $lastWeekendingWorked = getLastPayWeekending($mysqli,$data['candidateId']);
            $internalPoliceClearanceCheck = validateFinanceCheckType($mysqli,$data['candidateId'],1);
            $externalPoliceCheck = validateFinanceCheckType($mysqli,$data['candidateId'],2);
            $externalPoliceCheckReceipt = validateFinanceCheckType($mysqli,$data['candidateId'],3);
            foreach($auditLog as $aud){
                $auditStatus = $aud['auditStatus'];
                $auditedTime = $aud['auditedTime'];
            }
            if(empty($empExId)) {
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['firstName'].' '.$data['lastName'].'('.$data['nickname'].')');
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['visaType']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['positionName']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['client']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $auditedTime);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['payroll_officer']);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['payroll_status']);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['verified_time']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $lastWeekendingWorked);
                $polChk = getPoliceCheckDeduction($mysqli,$data['candidateId'],$startDate,$endDate,1);
                $refund = ' ';
                foreach ($polChk as $pch) {
                    $refund = $refund.' '.$pch['deduction'];
                }
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $refund);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $internalPoliceClearanceCheck);
                $objPHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $externalPoliceCheck);
                $objPHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $externalPoliceCheckReceipt);
            }elseif($empExId != $data['candidateId']){
                $empExId = $data['candidateId'];
            }else{

            }

    }
    $time = time();
    $filePath = './reports/auditpPoliceCheckReport-' . $time . '.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filePath);
    echo $filePath;
}elseif($action == 'CONSULTANT') {
    class AUDITPDF extends TCPDF {
        public function Header() {
            $image_file = K_PATH_IMAGES.'logo.png';
            $this->Image($image_file, 10, 5, 30, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        }
    }
    try {
        $consultantInfo = getAuditCheckData($mysqli, $startDate, $endDate);

        $pdf = new AUDITPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(' ');
        $pdf->SetTitle('AUDIT CHECK');
        $pdf->SetSubject('AUDIT CHECK');
        $pdf->SetKeywords('AUDIT CHECK');
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(5, 30, 2);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8);
        $pdf->AddPage();
        $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
        $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->Line(0, 0, 0, $pdf->getPageHeight());
        $html = $html . '<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
}
td.cellWidth{
    text-align: right;
    width: 8%;
}
td.shortWidth{
    text-align: right;
    width: 5%;
}
td.empId{
    text-align: left;
    width:12%;
}
td.desc{
    text-align: left;
    width:25%;
}
th.tbl_head{
background-color: #2a6395;
color: white;
text-transform: uppercase;
}
.title{
    margin-top: 0;
    padding-top: 0;
    text-align: left;
    text-transform: uppercase;
    font-weight: bold;
}
.pageTitle{
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 11pt;
}

.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}


.totalRow{
    font-weight: bold;
}
</style>';
        $html = $html . '<table class="table" border="1">
            <thead>
              <tr>
                <th class="tbl_head">CANDIDATE ID</th>
                <th class="tbl_head">FIRST NAME</th>
                <th class="tbl_head">LAST NAME</th>
                <th class="tbl_head">POSITION</th>
                <th class="tbl_head">CLIENT</th>
                <th class="tbl_head">CONSULTANT</th>
                <th class="tbl_head">JOB ORDER NOTIFIED</th>
                <th class="tbl_head">PAYROLL OFFICER</th>
                <th class="tbl_head">PAYROLL OFFICER VERIFIED TIME</th>
              </tr>
            </thead>
            <tbody>';
        $rowCount = 0;
        $empId = '';
        foreach ($consultantInfo as $data) {
            $rowCount++;
            $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
            if(empty($empId)){
                $empId = $data['candidateId'];
                $html = $html.'<td>'.$data['candidateId'].'</td>
                <td>'.getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidateLastNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidatePositionNameById($mysqli, $data['positionId']).'</td>
                <td>'.getClientNameByClientId($mysqli, $data['clientId']).'</td>';
                $html = $html.'<td>'.$data['consultant'].'</td>';
                if ($data['jobOrderNotify'] == 1) {
                    $jobOrderNotify = 'Yes';
                } else {
                    $jobOrderNotify = 'No';
                }
                $html = $html.'<td>'.$jobOrderNotify.'</td>';
                $html = $html.'<td>'.$data['payroll_officer'].'</td>';
                $html = $html.'<td>'.$data['verified_time'].'</td>';
            }elseif($empId != $data['candidateId']){
                $html = $html.'<td>'.$data['candidateId'].'</td>
                <td>'.getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidateLastNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidatePositionNameById($mysqli, $data['positionId']).'</td>
                <td>'.getClientNameByClientId($mysqli, $data['clientId']).'</td>';
                $html = $html.'<td>'.$data['consultant'].'</td>';
                if ($data['jobOrderNotify'] == 1) {
                    $jobOrderNotify = 'Yes';
                } else {
                    $jobOrderNotify = 'No';
                }
                $html = $html.'<td>'.$jobOrderNotify.'</td>';
                $html = $html.'<td>'.$data['payroll_officer'].'</td>';
                $html = $html.'<td>'.$data['verified_time'].'</td>';
                $empId = $data['candidateId'];
            }else{
                $html = $html.'<td></td><td></td><td></td><td></td><td></td>';
                $html = $html.'<td></td>';
                if ($data['jobOrderNotify'] == 1) {
                    $jobOrderNotify = 'Yes';
                } else {
                    $jobOrderNotify = 'No';
                }
                $html = $html.'<td></td>';
                $html = $html.'<td></td>';
                $html = $html.'<td></td>';
            }
            $html = $html.'</tr>';
        }
            $html = $html.'</tbody>
          </table>';
        $time = time();
        $fileName = 'auditCheckReport-'.$time.'.pdf';
        $filePath = './reports/auditCheckReport-'.$time.'.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->lastPage();
        $pdf->Output(__DIR__.'/reports/auditCheckReport-'.$time.'.pdf', 'F');
        echo $filePath;
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}elseif($action == 'PAYROLLPDF') {
    class AUDITPDF extends TCPDF {
        public function Header() {
            $image_file = K_PATH_IMAGES.'logo.png';
            $this->Image($image_file, 10, 5, 30, '', 'PNG', '', 'R', false, 300, '', false, false, 0, false, false, false);
        }
    }
    try {
        $payrollPDFInfo = getAuditCheckDataPayroll($mysqli, $startDate, $endDate);

        $pdf = new AUDITPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->setHeaderTemplateAutoreset(true);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(' ');
        $pdf->SetTitle('AUDIT CHECK');
        $pdf->SetSubject('AUDIT CHECK');
        $pdf->SetKeywords('AUDIT CHECK');
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', 8));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetMargins(5, 30, 2);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        //$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
            require_once(dirname(__FILE__) . '/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        $pdf->SetFont('helvetica', '', 8);
        $pdf->AddPage();
        $pdf->Line(0, 0, $pdf->getPageWidth(), 0);
        $pdf->Line($pdf->getPageWidth(), 0, $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->Line(0, $pdf->getPageHeight(), $pdf->getPageWidth(), $pdf->getPageHeight());
        $pdf->Line(0, 0, 0, $pdf->getPageHeight());
        $html = $html . '<style>
table {
    table-layout: fixed;
    width: 100%;
    white-space: nowrap;
    border-collapse: collapse;
    font-size: 8pt;
    /*word-wrap:break-word;*/
}
td.cellWidth{
    text-align: right;
    width: 8%;
}
td.shortWidth{
    text-align: right;
    width: 5%;
}
td.empId{
    text-align: left;
    width:12%;
}
td.desc{
    text-align: left;
    width:25%;
}
th.tbl_head{
background-color: #2a6395;
color: white;
text-transform: uppercase;
}
.title{
    margin-top: 0;
    padding-top: 0;
    text-align: left;
    text-transform: uppercase;
    font-weight: bold;
}
.pageTitle{
    text-align: center;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 11pt;
}

.zebra0{
    background-color: #cbd2d5;
}
.zebra1{
    background-color: white;
}


.totalRow{
    font-weight: bold;
}
</style>';
        $html = $html . '<table class="table" border="1">
            <thead>
              <tr>
                <th class="tbl_head">CANDIDATE ID</th>
                <th class="tbl_head">FIRST NAME</th>
                <th class="tbl_head">LAST NAME</th>
                <th class="tbl_head">POSITION</th>
                <th class="tbl_head">CLIENT</th>
                <th class="tbl_head">CONSULTANT</th>
                <th class="tbl_head">JOB ORDER NOTIFIED</th>
                <th class="tbl_head">PAYROLL OFFICER</th>
                <th class="tbl_head">PAYROLL OFFICER VERIFIED TIME</th>
                <th class="tbl_head">LAST SHIFT DATE</th>
                <th class="tbl_head">LAST SHIFT DEPARTMENT</th>
                <th class="tbl_head">INTERNAL POLICE CHECK CLEARANCE</th>
                <th class="tbl_head">EXTERNAL POLICE CHECK</th>
                <th class="tbl_head">LAST SHIFT DEPARTMENT</th>
              </tr>
            </thead>
            <tbody>';
        $rowCount = 0;
        $empId = '';
        foreach ($payrollPDFInfo as $data) {
            $shiftData = getLastShiftInfoByCandidateId($mysqli,$data['candidateId']);
            $shiftDate = '';
            $shiftDepartment = '';
            $internalPoliceClearanceCheck = validateFinanceCheckType($mysqli,$data['candidateId'],1);
            $externalPoliceCheck = validateFinanceCheckType($mysqli,$data['candidateId'],2);
            $externalPoliceCheckReceipt = validateFinanceCheckType($mysqli,$data['candidateId'],3);
            if (!empty($shiftData)) {
                $lastShiftData = explode(':',$shiftData);
                $shiftDate = $lastShiftData[0];
                $shiftDepartment = $lastShiftData[1];
            }
            $rowCount++;
            $html = $html . '<tr class="zebra' . ($i++ & 1) . '">';
            if(empty($empId)){
                $empId = $data['candidateId'];
                $html = $html.'<td>'.$data['candidateId'].'</td>
                <td>'.getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidateLastNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidatePositionNameById($mysqli, $data['positionId']).'</td>
                <td>'.getClientNameByClientId($mysqli, $data['clientId']).'</td>';
                $html = $html.'<td>'.$data['consultant'].'</td>';
                if ($data['jobOrderNotify'] == 1) {
                    $jobOrderNotify = 'Yes';
                } else {
                    $jobOrderNotify = 'No';
                }
                $html = $html.'<td>'.$jobOrderNotify.'</td>';
                $html = $html.'<td>'.$data['payroll_officer'].'</td>';
                $html = $html.'<td>'.$data['verified_time'].'</td>';
                $html = $html.'<td>'.$shiftDate.'</td>';
                $html = $html.'<td>'.$shiftDepartment.'</td>';
            }elseif($empId != $data['candidateId']){
                $html = $html.'<td>'.$data['candidateId'].'</td>
                <td>'.getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidateLastNameByCandidateId($mysqli, $data['candidateId']).'</td>
                <td>'.getCandidatePositionNameById($mysqli, $data['positionId']).'</td>
                <td>'.getClientNameByClientId($mysqli, $data['clientId']).'</td>';
                $html = $html.'<td>'.$data['consultant'].'</td>';
                if ($data['jobOrderNotify'] == 1) {
                    $jobOrderNotify = 'Yes';
                } else {
                    $jobOrderNotify = 'No';
                }
                $html = $html.'<td>'.$jobOrderNotify.'</td>';
                $html = $html.'<td>'.$data['payroll_officer'].'</td>';
                $html = $html.'<td>'.$data['verified_time'].'</td>';
                $html = $html.'<td>'.$shiftDate.'</td>';
                $html = $html.'<td>'.$shiftDepartment.'</td>';
                $html = $html.'<td>'.$internalPoliceClearanceCheck.'</td>';
                $html = $html.'<td>'.$externalPoliceCheck.'</td>';
                $html = $html.'<td>'.$externalPoliceCheckReceipt.'</td>';
                $empId = $data['candidateId'];
            }
            $html = $html.'</tr>';
        }
        $html = $html.'</tbody>
          </table>';
        $time = time();
        $fileName = 'auditCheckReportPayroll-'.$time.'.pdf';
        $filePath = './reports/auditCheckReportPayroll-'.$time.'.pdf';
        $pdf->writeHTML($html, true, false, false, false, '');
        $pdf->lastPage();
        $pdf->Output(__DIR__.'/reports/auditCheckReportPayroll-'.$time.'.pdf', 'F');
        echo $filePath;
    } catch (Exception $e) {
        echo $e->getMessage();
    }

}elseif($action == 'PAYROLL') {
    try {
        $consultantInfo = getAuditCheckPayrollData($mysqli, $startDate, $endDate);
    } catch (Exception $e) {
        echo $e->getMessage();
    }

    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CANDIDATE ID');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', 'FIRST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', 'LAST NAME');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', 'POSITION');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', 'DOCUMENT TYPE');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', 'DOCUMENT STATUS');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', 'CONSULTANT');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', 'NOTIFIED');

    $rowCount = 1;
    foreach ($consultantInfo as $data) {
        $rowCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['candidateId']);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFirstNameByCandidateId($mysqli, $data['candidateId']));
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getCandidateLastNameByCandidateId($mysqli, $data['candidateId']));
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, getCandidatePositionNameById($mysqli, $data['positionId']));
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, getAuditCheckListType($mysqli, $data['chkType']));
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['status']);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['consultant']);
        if ($data['jobOrderNotify'] == 1) {
            $jobOrderNotify = 'Yes';
        } else {
            $jobOrderNotify = 'No';
        }
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $jobOrderNotify);
    }
    $time = time();
    $filePath = './reports/auditCheckReportPayroll-' . $time . '.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filePath);
    echo $filePath;
}