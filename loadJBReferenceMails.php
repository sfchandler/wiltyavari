<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
/*ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$accountName = 'jobboard';
$tableEmail = getTableEmail($mysqli, $accountName);
$color_table = getColorCategoryTableName($accountName);
$mailList = $mysqli->prepare("SELECT 
										DISTINCT 
                                            reference
									   FROM
										{$tableEmail} 
                                       WHERE reference IS NOT NULL ORDER BY reference ASC") or die($mysqli->error);
$mailList->execute();
$mailList->store_result();
$mailList->bind_result($reference) or die($mysqli->error);
$numRows = $mailList->num_rows;
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
                    <td>'.$ref['reference'].'</td>
                    <td data-ref-code="'.$ref['reference'].'">
                        <input type="text" name="srchTxt" class="form-control-sm" placeholder="Search resume">
                        <input type="text" name="subjectSrchTxt" class="form-control-sm" placeholder="Search from subject">
                        <input type="text" name="fromSrchTxt" class="form-control-sm" placeholder="Search by from">
                        <button class="srchJBTxtBtn btn btn-xs btn-primary"><i class="fa fa-search"></i> Search</button>
                    </td>
                    <td colspan="2"><span style="font-weight: bold; color: #78D168;">Suitable ('.getMailSuitableCount($mysqli,$ref['reference']).') </span><span style="font-weight: bold; color: #E7A1A2;">Not Suitable ('.getMailNotSuitableCount($mysqli,$ref['reference']).') </span><span style="font-weight: bold; color: #023e8a;">Suitable ('.getMailHiredCount($mysqli,$ref['reference']).') </span></td>
                    <td style="float: right">
                        <button class="jbclpse btn btn-xs btn-primary" type="button" data-ref-code="'.$ref['reference'].'" data-toggle="collapse" data-target="#JC'.$ref['reference'].'" aria-expanded="false" aria-controls="JC'.$ref['reference'].'">
                           <i class="glyphicon glyphicon-eye-open"></i> View
                         </button>
                    </td>
                </tr>
                <tr><td colspan="5">
                    <div class="collapse" id="JC'.$ref['reference'].'">
                    <div class="card card-body">
                        <table class="table table-borderless table-striped">
                            <thead></thead>
                            <tbody id="JS'.$ref['reference'].'"> 
                                
                            </tbody>
                          </table>
                    </div>
            </div></td></tr>';
}
echo $row;
//'.displayReferencedEmails($mysqli,$tableEmail,$accountName,$color_table,$ref['reference']).'
function getMailSuitableCount($mysqli,$ref_code){
    $suitable = 10;
    $mailList = $mysqli->prepare("SELECT 
                                      resume.autoid,
                                      mail_color_category.catid
                                    FROM
                                      resume
                                      INNER JOIN mail_color_category ON (resume.autoid = mail_color_category.autoid)
                                    WHERE
                                      resume.reference = ? AND 
                                      mail_color_category.catid = ?") or die($mysqli->error);
    $mailList->bind_param("si",$ref_code,$suitable)or die($mysqli->error);
    $mailList->execute();
    $mailList->store_result();
    $numRows = $mailList->num_rows;
    return $numRows;
}
function getMailHiredCount($mysqli,$ref_code){
    $hired = 13;
    $mailList = $mysqli->prepare("SELECT 
                                      resume.autoid,
                                      mail_color_category.catid
                                    FROM
                                      resume
                                      INNER JOIN mail_color_category ON (resume.autoid = mail_color_category.autoid)
                                    WHERE
                                      resume.reference = ? AND 
                                      mail_color_category.catid = ?") or die($mysqli->error);
    $mailList->bind_param("si",$ref_code,$hired)or die($mysqli->error);
    $mailList->execute();
    $mailList->store_result();
    $numRows = $mailList->num_rows;
    return $numRows;
}
function getMailNotSuitableCount($mysqli,$ref_code){
    $notSuitable = 7;
    $mailList = $mysqli->prepare("SELECT 
                                      resume.autoid,
                                      mail_color_category.catid
                                    FROM
                                      resume
                                      INNER JOIN mail_color_category ON (resume.autoid = mail_color_category.autoid)
                                    WHERE
                                      resume.reference = ? AND 
                                      mail_color_category.catid = ?") or die($mysqli->error);
    $mailList->bind_param("si",$ref_code,$notSuitable)or die($mysqli->error);
    $mailList->execute();
    $mailList->store_result();
    $numRows = $mailList->num_rows;
    return $numRows;
}
function displayReferencedEmails($mysqli,$tableEmail,$accountName,$color_table,$reference){
    $mailList = $mysqli->prepare("SELECT
										autoid,
										messageid,
										mailfrom,
										mailto,
										subject,
										date
									  FROM
										{$tableEmail} 
                                      WHERE
                                        reference = ?
                                      ORDER BY date DESC") or die($mysqli->error);
    $mailList->bind_param("s",$reference)or die($mysqli->error);
    $mailList->execute();
    $mailList->bind_result($autoid, $messageid, $mailfrom, $mailto, $subject, $date) or die($mysqli->error);
    $mailList->store_result();
    $numRows = $mailList->num_rows;
    $row='';
    while ($mailList->fetch()) {
        $row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '"><td class="messageid" data-messageid="' . $messageid . '"><div class="mFrom">' . $mailfrom . '</div><div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div><div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div><div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div></td><td align="right">' . $date . '</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
    }
    return $row;
    //return $numRows.$mysqli->error;
}