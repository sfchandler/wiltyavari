<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$reference = $_REQUEST['ref_code'];
$accountName = $_SESSION['accountName'];
$tableEmail = getTableEmail($mysqli, $accountName);
$color_table = getColorCategoryTableName($accountName);
$status = $_REQUEST['status'];
$inboxStatus = 0;
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
                                      AND 
                                        status = ?    
                                      AND 
                                          {$tableEmail}.inbox_status = ?
                                      AND 
                                          {$tableEmail}.date >= (DATE(NOW()) - INTERVAL 6 MONTH)
                                      ORDER BY date DESC") or die($mysqli->error);
$mailList->bind_param("sii",$reference,$status,$inboxStatus)or die($mysqli->error);
$mailList->execute();
$mailList->bind_result($autoid, $messageid, $mailfrom, $mailto, $subject,$msgbody, $date) or die($mysqli->error);
$mailList->store_result();
$numRows = $mailList->num_rows;
$row='';
while ($mailList->fetch()) {
    /*$breakEmail = explode('Email',$msgbody);
    data-email="'.$breakEmail[1].'"*/
    $states = getStatesForResumeShortListDropdown($mysqli,$autoid);
    $regions = getRegionsForDropdown($mysqli,$autoid);
    $positions = getResumeShortListPositions($mysqli,$autoid);
    $selectedGender = getResumeSelectedGenderForCandidate($mysqli,$autoid);
    $row = $row . '<tr id="' . $autoid . '" class="rowId ';
    if($selectedGender == 'Male'){
         $row = $row.'cellColorMale';
    }elseif($selectedGender == 'Female'){
         $row = $row.'cellColorFeMale';
    }
    $row = $row.'" data-acc="' . $accountName . '">
                        <td class="messageid" data-messageid="' . $messageid . '" data-refcode="'.$reference.'" style="width: 30%">
                            <div class="mFrom">' . $mailfrom . '</div>
                            <div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div>
                            <div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div>
                            <div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div>
                                <a style="text-decoration: none; padding-left: 5px;" class="viewBody"><i class="fa fa-eye"></i></a>
                            <div class="mailBodyText" style="display: none"></div>
                        </td>
                        <td class="mailComm">
                            <div class="mailComment">
                                <a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a>
                            </div>
                        </td>
                        <td class="categoryStatus">
                            <div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div>
                        </td>
                        <td class="states">
                            <select name="state_id" id="state_id" class="form-control-sm">
                                '.$states.'
                            </select>
                        </td>
                        <td class="region">
                            <select name="region" id="region" class="form-control-sm">
                                '.$regions.'
                            </select>
                        </td>
                        <td class="ps" style="width: 40%">
                            <div style="height: 100px; overflow-y: scroll">
                            '.$positions.'
                            </div>
                        </td>
                        <td class="gender">
                            <select name="gender" id="gender" class="form-control-sm">
                                <option value="Male"';
                        if($selectedGender == 'Male'){
                            $row = $row.' selected';
                        }
                        $row = $row.'>M</option>
                                                    <option value="Female"';
                        if($selectedGender == 'Female'){
                            $row = $row.' selected';
                        }
                        $row = $row.'>F</option>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="applied_date" id="applied_date" value="'.$date.'"/>
                            <input type="hidden" name="ref_code" id="ref_code" value="'.$reference.'"/>
                            <input type="hidden" name="msg_id" id="msg_id" value="'.$messageid.'"/>
                            <input type="hidden" name="account_name" id="account_name" value="'.$accountName.'"/>';
                            if(!empty(getPositionsByResumeShortListAutoId($mysqli,$autoid))) {
                                $row = $row . '<button type="button" class="shortlistBtn btn btn-xs btn-success"><i class="fa fa-check"></i> Talent Pool</button>';
                            }else{
                                $row = $row . '<button type="button" class="shortlistBtn btn btn-xs btn-info"><i class="fa fa-plus"></i> Talent Pool</button>';
                            }
                        $row = $row.'</td>
                        <td align="right">' . $date . '</td>
                        <td>
                            <input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/>
                            <button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button>
                        </td>
                    </tr>';
    //<button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button>
}
echo $row;