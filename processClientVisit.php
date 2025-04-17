<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require("./includes/PHPExcel-1.8/Classes/PHPExcel.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$action = $_REQUEST['action'];
$id = $_REQUEST['id'];
if ($action == 'ADD') {
    if ($id == 0) {
        $consultantId = $_REQUEST['consultantId'];
        $client_visit_date = $_REQUEST['client_visit_date'];
        $client_id = $_REQUEST['client_id'];
        $notes = $_REQUEST['notes'];
        $issues = $_REQUEST['issues'];
        $follow_up_date = $_REQUEST['follow_up_date'];
        echo updateClientVisit($mysqli, $consultantId, $client_visit_date, $client_id, $notes, $issues, $follow_up_date, $id);
    }
} elseif ($action == 'DISPLAY') {
    echo displayClientVisits($mysqli);
} elseif ($action == 'UPDATE') {
    $consultantId = $_REQUEST['consultantId'];
    $client_visit_date = $_REQUEST['client_visit_date'];
    $client_id = $_REQUEST['client_id'];
    $notes = $_REQUEST['notes'];
    $issues = $_REQUEST['issues'];
    $follow_up_date = $_REQUEST['follow_up_date'];
    echo updateClientVisit($mysqli, $consultantId, $client_visit_date, $client_id, $notes, $issues, $follow_up_date, $id);
} elseif ($action == 'EXPORT') {
    $boldArray = array('font' => array('bold' => true),
        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '00D704'))
    );
    $boldText = array('font' => array('bold' => true),
        'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00'),'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => '000000'),
            ),
        ),)
    );
    $styleBorders = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('argb' => '000000'),
            ),
        ),
    );
    $doubleBorders = array(
        'borders' => array(
            'outline' => array(
                'style' => PHPExcel_Style_Border::BORDER_DOUBLE,
                'color' => array('argb' => '000000'),
            ),
        ),
    );
    $headingArray = array('font' => array('bold' => true),'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFFF00')));
    $dataSet = getClientVisitInformation($mysqli);
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->mergeCells('A1:H1');
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($headingArray);
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('A1', 'CLIENT VISITS');
    $objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $objPHPExcel->getActiveSheet()->setCellValue('A2', 'CLIENT');
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('B2', 'VISIT DATE');
    $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('C2', 'CONSULTANT');
    $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('D2', 'NOTES');
    $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('E2', 'ISSUES');
    $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('E2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('F2', 'FOLLOW UP DATE');
    $objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('F2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('G2', 'CREATED DATE');
    $objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('G2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setCellValue('H2', 'UPDATED DATE');
    $objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($boldText);
    $objPHPExcel->getActiveSheet()->getStyle('H2')->applyFromArray($styleBorders);
    $objPHPExcel->getActiveSheet()->setTitle('Client Visit Report');
    $rowCount = 2;
    foreach ($dataSet as $data) {
        $rowCount++;
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, getClientNameByClientId($mysqli, $data['client_id']));
        $objPHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['client_visit_date']);
        $objPHPExcel->getActiveSheet()->getStyle('B' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, getConsultantName($mysqli, $data['consultant_id']));
        $objPHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['notes']);
        $objPHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['issues']);
        $objPHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['follow_up_date']);
        $objPHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['created_at']);
        $objPHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->applyFromArray($styleBorders);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['updated_at']);
        $objPHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->applyFromArray($styleBorders);
    }
    $time = time();
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('./reports/client_visit_' . $time . '.xlsx');
    echo './reports/client_visit_' . $time . '.xlsx';
}