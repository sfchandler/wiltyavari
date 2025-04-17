<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
$reference = $_REQUEST['ref_code'];
$accountName = 'jobboard';
$tableEmail = getTableEmail($mysqli, $accountName);
$color_table = getColorCategoryTableName($accountName);
$mailList = $mysqli->prepare("SELECT
										autoid,
										messageid,
										mailfrom,
										mailto,
										subject,
                                        msgbody,
										date
									  FROM
										{$tableEmail} 
                                      WHERE
                                        reference = ?
                                      ORDER BY date DESC") or die($mysqli->error);
$mailList->bind_param("s",$reference)or die($mysqli->error);
$mailList->execute();
$mailList->bind_result($autoid, $messageid, $mailfrom, $mailto, $subject,$msgbody, $date) or die($mysqli->error);
$mailList->store_result();
$numRows = $mailList->num_rows;
$row='';
while ($mailList->fetch()) {
    $row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '"><td class="messageid" data-messageid="' . $messageid . '"><div class="mFrom">' . $mailfrom . '</div><div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div><div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div><div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div><a style="text-decoration: none; padding-left: 5px;" class="viewBody"><i class="fa fa-eye"></i></a><div class="mailBodyText" style="display: none">'.$msgbody.'</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div></td><td align="right">' . $date . '</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
}
echo $row;