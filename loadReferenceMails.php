<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");

$accountName = $_SESSION['accountName'];
$tableEmail = getTableEmail($mysqli, $accountName);
$color_table = getColorCategoryTableName($accountName);
$status = $_REQUEST['status'];
$inboxStatus = 0;
$mailList = $mysqli->prepare("SELECT 
										DISTINCT 
                                           {$tableEmail}.reference
									   FROM
										{$tableEmail} 
                                       INNER JOIN inbox_reference ON({$tableEmail}.reference = inbox_reference.reference)
                                       WHERE {$tableEmail}.reference IS NOT NULL 
                                       AND {$tableEmail}.date >= (DATE(NOW()) - INTERVAL 9 MONTH)
                                       AND {$tableEmail}.inbox_status = ?
                                       AND inbox_reference.status = ? ORDER BY {$tableEmail}.reference ASC") or die($mysqli->error);
$mailList->bind_param("ii",$inboxStatus,$status)or die($mysqli->error);
$mailList->execute();
$mailList->store_result();
$mailList->bind_result($reference) or die($mysqli->error);
$list='';
$referenceArray = array();
while ($mailList->fetch()) {
    $list = array('reference' => $reference);
    $referenceArray[] = $list;
}

$mailList->free_result();
$row = '';
foreach ($referenceArray as $ref) {
   $row = $row.'<tr>
                    <td><span class="referenceTitle" style="font-size:14px;	font-weight:500;color: #3377b1;">'.strtoupper($ref['reference']).'</span></td>
                    <td data-ref-code="'.$ref['reference'].'">
                        <input type="hidden" name="srchStatus" value="'.$status.'">
                        <input type="text" name="srchTxt" size="10" class="form-control-sm" placeholder="Search resume">
                        <input type="text" name="subjectSrchTxt" size="10" class="form-control-sm" placeholder="Search from subject">
                        <input type="text" name="fromSrchTxt" size="10" class="form-control-sm" placeholder="Search by from">
                        <button class="srchTxtBtn btn btn-sm btn-primary"><i class="fa fa-search"></i> Search</button>
                    </td>
                    <td> <span style="font-size:12px; font-weight: bold;color: black">APPLICANTS('.getMailCountByReferenceCode($mysqli,$ref['reference']).')</span></td>
                    <td><span style="font-size:12px; font-weight: bold; color: #78D168;">SUITABLE ('.getMailSuitableCount($mysqli,$ref['reference']).') </span></td>
                    <td><span style="font-size:12px; font-weight: bold; color: #E7A1A2;">NOT SUITABLE ('.getMailNotSuitableCount($mysqli,$ref['reference']).') </span></td>
                    <td><span style="font-size:12px; font-weight: bold; color: #faa307;">INTERVIEWED ('.getMailInterviewedCount($mysqli,$ref['reference']).') </span></td>
                    <td><span style="font-size:12px; font-weight: bold; color: #023e8a;">HIRED ('.getMailHiredCount($mysqli,$ref['reference']).') </span></td>
                    <td>
                        <button class="clpse btn btn-xs btn-primary" type="button" data-ref-code="'.$ref['reference'].'" data-toggle="collapse" data-target="#C'.$ref['reference'].'" aria-expanded="false" aria-controls="C'.$ref['reference'].'">
                           <i class="glyphicon glyphicon-eye-open"></i> View
                         </button>
                    </td>
                </tr>
                <tr><td colspan="8">
                    <div class="collapse" id="C'.$ref['reference'].'">
                        <div class="card card-body">
                            <table class="table table-bordered table-striped table-danger">
                                <thead>
                                    <th colspan="10"></th>
                                </thead>
                                <tbody id="S'.$ref['reference'].'"> 
                                    
                                </tbody>
                              </table>
                        </div>
                    </div>
                    </td>
                </tr>';
}
echo $row;
