<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");

    $accountName = $_SESSION['accountName'];
if(isset($accountName)) {
    $tableEmail = getTableEmail($mysqli, $accountName);
    $limitStart = $_POST['limitStart'];
    $limitCount = 10;
    $color_table = getColorCategoryTableName($accountName);

    if (isset($limitStart) || !empty($limitStart)) {
        $mailList = $mysqli->prepare("SELECT 
										autoid,
										messageid,
										mailfrom,
										mailto,
										subject,
										date
									  FROM
										{$tableEmail} ORDER BY date DESC LIMIT $limitStart, $limitCount") or die($mysqli->error);
        $mailList->execute();
        $mailList->bind_result($autoid, $messageid, $mailfrom, $mailto, $subject, $date) or die($mysqli->error);
        $mailList->store_result();
        $numRows = $mailList->num_rows;
        $row='';
        while ($mailList->fetch()) {
            //$row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '"><td class="messageid" data-messageid="' . $messageid . '"><div class="mFrom">' . $mailfrom . '</div><div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div><div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div><div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div></td><td align="right">' . $date . '</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
              $row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '"><td class="messageid" data-messageid="' . $messageid . '"><div class="mFrom">' . $mailfrom . '</div><div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div><div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div></td><td align="right">' . $date . '</td><td><!--<button class="jotBtn btn btn-xs btn-dark" type="button"><i class="fa fa-send"></i> Send Jot Form</button>--></td><td><!--<button class="formsBtn btn btn-xs btn-dark" type="button"><i class="fa fa-send"></i> Send Forms link</button>--></td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/></td></tr>';//<button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button>
        }
        //$row = $row.'<tr class="inboxBottom"><td colspan="5"></td></tr>';
        //echo $row.'<input type="hidden" id="rowCount" value="'.$numRows.'"/>';
        echo $row;
    }
}
?>