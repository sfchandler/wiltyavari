<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 31/10/2019
 * Time: 11:00 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once("includes/PHPExcel-1.8/Classes/PHPExcel.php");

$startDate = $_REQUEST['startDate'];
$endDate = $_REQUEST['endDate'];
$shiftSMSStatus = 1;
$boldArray = array('font'=>array('bold'=>true),
    'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00D704'))
);
$styleBorders = array(
    'borders' => array(
        'outline' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000000'),
        ),
    ),
);
$headingArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '00D704')));
$maleArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => '9ED6FF')));
$femaleArray = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' => 'FFB8F8')));
if (isset($startDate) && isset($endDate)) {
        $dataSet = generateRosterSMSStatusData($mysqli, $startDate, $endDate, $shiftSMSStatus);
        if (!empty($dataSet)) {
            $objPHPExcel = new PHPExcel();

            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'FIRST NAME');
            $objPHPExcel->getActiveSheet()->setCellValue('B1', 'LAST NAME');
            $objPHPExcel->getActiveSheet()->setCellValue('C1', 'CANDIDATE ID');
            $objPHPExcel->getActiveSheet()->setCellValue('D1', 'SHIFT DATE');
            $objPHPExcel->getActiveSheet()->setCellValue('E1', 'SHIFT DAY');
            $objPHPExcel->getActiveSheet()->setCellValue('F1', 'SHIFT START');
            $objPHPExcel->getActiveSheet()->setCellValue('G1', 'SHIFT END');
            $objPHPExcel->getActiveSheet()->setCellValue('H1', 'WORK BREAK');
            $objPHPExcel->getActiveSheet()->setCellValue('I1', 'SHIFT SMS STATUS');
            $objPHPExcel->getActiveSheet()->setCellValue('J1', 'POSITION');
            $objPHPExcel->getActiveSheet()->setCellValue('K1', 'CLIENT');
            $objPHPExcel->getActiveSheet()->setCellValue('L1', 'DEPARTMENT');

            $objPHPExcel->getActiveSheet()->setTitle('Roster SMS Status Report');

            $rowCount = 1;
            $client = '';
            foreach ($dataSet as $data) {
                $rowCount++;
                $client = $data['client'];
                $objPHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, $data['firstName']);
                $objPHPExcel->getActiveSheet()->setCellValue('B' . $rowCount, $data['lastName']);
                $objPHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $data['candidateId']);
                $objPHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $data['shiftDate']);
                $objPHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $data['shiftDay']);
                $objPHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $data['shiftStart']);
                $objPHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $data['shiftEnd']);
                $objPHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $data['workBreak']);
                $objPHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $data['shiftSMSStatus']);
                $objPHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $data['positionName']);
                $objPHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $data['client']);
                $objPHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $data['department']);
            }
            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            $objWriter->save('./roster/rosterSMSReport- ' . $startDate . ' to ' . $endDate . $client. '.xlsx');
            echo './roster/rosterSMSReport- ' . $startDate . ' to ' . $endDate . $client . '.xlsx';
        }
}

?>