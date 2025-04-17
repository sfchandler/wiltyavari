<?php

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$wkendDate = '2020-02-16';
$empId = 'CHAN0000019699';
if(strtotime('july', strtotime($wkendDate)) > strtotime($wkendDate)){
    $currentJuly = date('Y-m-d',strtotime('1st july', strtotime($wkendDate)));
    $yearStartDate = date('Y-m-d',strtotime('-1 year', strtotime($currentJuly)));
}else{
    $yearStartDate = date('Y-m-d',strtotime('1st july', strtotime($wkendDate)));
}
echo $yearStartDate;
$yearToDate = getYearToDateData($mysqli,$empId,$yearStartDate,$wkendDate);
$html = $html.'<table width="50%" cellpadding="1" cellspacing="1"><thead><tr><th></th><th>Hours/Qty</th><th>Amount</th></tr></thead><tbody>';
$html = $html.'<tr><td colspan="3" class="rowTitle" style="font-weight: bold">This Year</td></tr>';
foreach ($yearToDate as $yearData) {
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Hourly</td><td>'.number_format($yearData['totalUnits'],2) . '</td><td>' . number_format($yearData['totalGross'],2) . '</td></tr>';
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Tax</td><td></td><td>-'.number_format($yearData['totalTax'],2).'</td></tr>';
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Net</td><td></td><td>'.number_format($yearData['totalNet'],2).'</td></tr>';
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Post Tax Deduction</td><td></td><td>'.number_format($yearData['totalDedcution'],2).'</td></tr>';
    $html = $html . '<tr class="zebra'.($i++ & 1).'"><td class="rowTitle">Superannuation Accrual</td><td>'.number_format($yearData['superCount'],2).'</td><td>'.number_format($yearData['totalSuper'],2).'</td></tr>';
}
$html = $html.'</tbody></table>';
echo $html;