<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 24/10/2019
 * Time: 11:35 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("includes/PHPExcel-1.8/Classes/PHPExcel.php");
$wkendingDate = $_POST['weekendingDate'];
$visaData = getTempVisaData($mysqli,$wkendingDate);

$boldArray = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFDAB9'))
);
$headerBackgroundArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFDAB9')));
$styleBorders = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000000'),
        ),
    ),
);
$objPHPExcel = new PHPExcel();
$objPHPExcel->setActiveSheetIndex(0);
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleBorders);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($boldArray);
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($headerBackgroundArray);
$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleBorders);
$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleBorders);
$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleBorders);
$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleBorders);
$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleBorders);


$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Candidate ID');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Full Name');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Hours');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Visa Type');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Expiry Date');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Weekending Date');

$objPHPExcel->getActiveSheet()->setTitle('TEMP VISA  REPORT');

$rowCount = 1;
$canId = '';
$visaType ='';
$expDate = '';

$totalHours = 0;
$i = 0;
$len = count($visaData);
foreach ($visaData as $data){

    if(empty($canId)){
        $canId = $data['candidateId'];
        $visaType = $data['visaType'];
        $expDate = $data['expiryDate'];
    }
    if($canId != $data['candidateId']){
        if($visaType == 'STUDENT'){
            if($totalHours >20) {
                $rowCount++;
                $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $canId);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli,$canId));
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $totalHours);
                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $visaType);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $expDate);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['weekendingDate']);
            }
        }else{
            $rowCount++;
            $objPHPExcel->getActiveSheet()->getStyle('A'.$rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('B'.$rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('C'.$rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('D'.$rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('E'.$rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('F'.$rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $canId);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli,$canId));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $totalHours);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $visaType);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, date('d/m/Y',strtotime($expDate)));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['weekendingDate']);
        }
        $totalHours = 0;
        $canId = $data['candidateId'];
        $visaType = $data['visaType'];
        $expDate = $data['expiryDate'];
    }
    $totalHours = $totalHours + $data['units'];

    if($i == $len - 1){
        if($visaType == 'STUDENT') {
            if ($totalHours > 20) {
                $rowCount++;
                $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($styleBorders);
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $canId);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli, $canId));
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $totalHours);
                $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $visaType);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $expDate);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['weekendingDate']);
            }
        }else{
            $rowCount++;
            $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($styleBorders);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $canId);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, getCandidateFullName($mysqli, $canId));
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $totalHours);
            $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $visaType);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, date('d/m/Y',strtotime($expDate)));
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['weekendingDate']);
        }
    }
    $i++;
}
try {
    $time = time();
    $filePath = './reports/tempvisaReport-'.$time.'.xlsx';
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save($filePath);
    echo $filePath;
}catch (Exception $e){
    echo $e->getMessage();
}