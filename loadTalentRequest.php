<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");

$accountName = 'talent';


if(isset($accountName)) {
    $tableEmail = getTableEmail($mysqli, $accountName);
    $limitStart = $_POST['limitStart'];
    $mailList = $mysqli->prepare("SELECT 
										autoid,
										messageid,
										mailfrom,
										mailto,
										subject,
                                        msgbody,
										date,
                                        reference
									  FROM
										{$tableEmail} ORDER BY date DESC") or die($mysqli->error);
    $mailList->execute();
    $mailList->bind_result($autoid, $messageid, $mailfrom, $mailto, $subject,$msgbody, $date,$reference) or die($mysqli->error);
    $mailList->store_result();
    $numRows = $mailList->num_rows;
    $row='';
    while ($mailList->fetch()) {
        $company = get_string_between($msgbody,'Company:',':');
        $row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '">
        <td class="tlmessageid" data-messageid="' . $messageid . '">
            <div class="mFrom">' . $mailfrom . '</div>
            <div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div>
            <div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div>
            <div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div>
        </td>
        <td><input type="text" name="consultant" class="talentConsultant" value="'.getTalentNote($mysqli,$autoid).'"/>
        <a href="#" class="talentBtn"><i class="fa fa-lg fa-save"></i></a></td>
        <!--<td>
            <label class="custom-file-label" for="talentFile">
                <input type="file" name="talentFile" class="form-control-file"/>
                <span class="btn btn-outline-info">Browse</span> 
            <button name="talentUploadBtn" class="talentUploadBtn btn btn-sm btn-outline-success">Upload</button>
        </td>
        <td><div id="output"></div></td>-->
        <td style="color: darkorange; font-weight: bold">'.$company.'</td>
        <td class="mailComm">
            <div class="mailComment">
            <a href="#" class="talentCommentLink" title="'.getTalentMailComment($mysqli,$autoid).'"><i class="fa fa-lg fa-comment"></i></a>
            </div>
        </td>
        <td align="right">' . $date . '</td>
        <td>
            <input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/>
        </td></tr>';
    }
    echo $row;
}
?>