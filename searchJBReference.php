<?php
session_start();
//ini_set('memory_limit', '4096M');
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
$accountName = 'jobboard';
$tableAttahment = getTableAttachment($mysqli,$accountName);
$tableEmail = getTableEmail($mysqli,$accountName);
$color_table = getColorCategoryTableName($accountName);
$limitStart = $_POST['limitStart'];
$limitCount = 10;
$_SESSION['searchTxt'] = $_REQUEST['srchTxt'];
$_SESSION['subjectSearchTxt'] = $_REQUEST['subjectSrchTxt'];
$_SESSION['fromSearchTxt'] = $_REQUEST['fromSrchTxt'];
$ref_code = $_REQUEST['ref_code'];

if(!empty($_REQUEST['srchTxt']) || !empty($_REQUEST['subjectSrchTxt']) || !empty($_REQUEST['fromSrchTxt'])){

    $searchTxt = $_REQUEST['srchTxt'];
    $searchTerms = explode(',', $searchTxt);
    $searchTermsBits = array();

    $subjectSearchTxt = $_REQUEST['subjectSrchTxt'];
    $subjectSearchTerms = explode(',', $subjectSearchTxt);
    $subjectTermsBits = array();

    foreach($searchTerms as $term){
        $term = trim($term);
        $escapedString = $mysqli->real_escape_string($term);
        if(!empty($escapedString)){
            if(!empty($searchTxt) && !empty($subjectSearchTxt)){
                $searchTermsBits[] = "{$tableAttahment}.contents LIKE '%{$escapedString}%'";
            }else if(!empty($searchTxt) && empty($subjectSearchTxt)){
                $searchTermsBits[] = "{$tableAttahment}.contents LIKE '%{$escapedString}%'";
            }

        }
    }

    foreach($subjectSearchTerms as $subjectTerm){
        $subjectTerm = trim($subjectTerm);
        $excapedSubjectString = $mysqli->real_escape_string($subjectTerm);
        if(!empty($excapedSubjectString)){
            if(!empty($searchTxt) && !empty($subjectSearchTxt)){
                $subjectTermsBits[] = "{$tableEmail}.subject LIKE '%{$excapedSubjectString}%'";
            }else if(empty($searchTxt) && !empty($subjectSearchTxt)){
                $subjectTermsBits[] = "{$tableEmail}.subject LIKE '%{$excapedSubjectString}%'";
            }
        }
    }

    $searchString = implode(' AND ', $searchTermsBits);
    $subjectString = implode(' AND ', $subjectTermsBits);
    if(!empty($searchString) && !empty($subjectString)){
        $sqlString = $searchString.' AND '.$subjectString;
    }else if(empty($searchString) && !empty($subjectString)){
        $sqlString = $subjectString;
    }else if(!empty($searchString) && empty($subjectString)){
        $sqlString = $searchString;
    }else{
        $sqlString = $searchString;
    }

    if(empty($searchString) && empty($subjectString)&& empty($_REQUEST['fromSrchTxt'])){
        echo "Please enter search phrase to search";
    }else if(!empty($_REQUEST['fromSrchTxt']) && empty($searchString) && empty($subjectString)){
        $fromSearchTxt = $_REQUEST['fromSrchTxt'];
        $searchClause = "SELECT SQL_CALC_FOUND_ROWS
							{$tableEmail}.autoid,
							{$tableEmail}.messageid,
							{$tableEmail}.uid,
							{$tableEmail}.msgno,
							{$tableEmail}.mailfrom,
							{$tableEmail}.mailto,
							{$tableEmail}.subject,
							{$tableEmail}.msgbody,
							{$tableEmail}.date,
							{$tableAttahment}.filepath,
							{$tableAttahment}.contents
						  FROM
							{$tableEmail}
							LEFT OUTER JOIN {$tableAttahment} ON ({$tableEmail}.messageid = {$tableAttahment}.messageid)
						  WHERE
						    reference = ?
						  AND
						     {$tableEmail}.mailfrom LIKE '%{$fromSearchTxt}%'  
						  ORDER BY
							{$tableEmail}.date DESC";
        $matchingList = $mysqli->prepare($searchClause)or die($mysqli->error);
        $matchingList->bind_param("s",$ref_code)or die($mysqli->error);
        $matchingList->execute();
        $matchingList->bind_result($autoid,$messageid, $uid, $msgno, $mailfrom, $mailto, $subject, $msgbody, $date,$filepath,$contents) or die($mysqli->error);
        $matchingList->store_result();
        $numRows = $matchingList->num_rows;
        $query="SELECT FOUND_ROWS()";
        $stmt = $mysqli->prepare($query);
        $stmt->execute();
        $stmt->bind_result($num);
        while($stmt->fetch()){
            $count=$num;
        }
        $mailArray = array();
        $row;
        while($matchingList->fetch()){
            //$row = $row.'<tr id="'.$autoid.'" class="rowId"><td class="messageid" data-messageid="'.$messageid.'"><div class="mFrom">'.$mailfrom.'</div><div class="subject"><strong>Subj:</strong>&nbsp;'.substr($subject,0,60).'...</div><div class="mTo"><strong>To:</strong>&nbsp;'.$mailto.'</div></td><td class="categoryStatus"><div id="'.$autoid.'" class="category">'.getMailColorCategories($mysqli,$autoid,$color_table).'</div></td><td align="right">'.$date.'</td><td><button class="jotBtn btn btn-xs btn-dark" type="button"><i class="fa fa-send"></i> Send Jot Form</button></td><td><button class="formsBtn btn btn-xs btn-dark" type="button"><i class="fa fa-send"></i> Send Forms link</button></td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td></td></tr>';
            $row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '"><td class="messageid" data-messageid="' . $messageid . '"><div class="mFrom">' . $mailfrom . '</div><div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div><div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div><div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div></td><td align="right">' . $date . '</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
        }
        echo $row.'<input type="hidden" id="rowCount" value="'.$count.'"/>';
    }else {
        $searchClause = "SELECT SQL_CALC_FOUND_ROWS
							{$tableEmail}.autoid,
							{$tableEmail}.messageid,
							{$tableEmail}.uid,
							{$tableEmail}.msgno,
							{$tableEmail}.mailfrom,
							{$tableEmail}.mailto,
							{$tableEmail}.subject,
							{$tableEmail}.msgbody,
							{$tableEmail}.date,
							{$tableAttahment}.filepath,
							{$tableAttahment}.contents
						  FROM
							{$tableEmail}
							LEFT OUTER JOIN {$tableAttahment} ON ({$tableEmail}.messageid = {$tableAttahment}.messageid)
						  WHERE
						    reference = ? AND    
							".$sqlString."
						  ORDER BY
							{$tableEmail}.date DESC	  
							";
        $matchingList = $mysqli->prepare($searchClause)or die($mysqli->error);
        $matchingList->bind_param("s",$ref_code)or die($mysqli->error);
        $matchingList->execute();
        $matchingList->bind_result($autoid,$messageid, $uid, $msgno, $mailfrom, $mailto, $subject, $msgbody, $date,$filepath,$contents) or die($mysqli->error);
        $matchingList->store_result();
        $numRows = $matchingList->num_rows;
        //get total number of rows.
        $query="SELECT FOUND_ROWS()";
        $stmt = $mysqli->prepare($query);
        $stmt->execute();
        $stmt->bind_result($num);
        while($stmt->fetch()){
            $count=$num;
        }
        $mailArray = array();
        $row;
        while($matchingList->fetch()){
            //$row = $row.'<tr id="'.$autoid.'" class="rowId"><td class="messageid" data-messageid="'.$messageid.'"><div class="mFrom">'.$mailfrom.'</div><div class="subject"><strong>Subj:</strong>&nbsp;'.substr($subject,0,60).'...</div><div class="mTo"><strong>To:</strong>&nbsp;'.$mailto.'</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="'.$autoid.'" class="category">'.getMailColorCategories($mysqli,$autoid,$color_table).'</div></td><td align="right">'.$date.'</td><td><button class="jotBtn btn btn-xs btn-dark" type="button"><i class="fa fa-send"></i> Send Jot Form</button></td><td><button class="formsBtn btn btn-xs btn-dark" type="button"><i class="fa fa-send"></i> Send Forms link</button></td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td></td></tr>';
            $row = $row . '<tr id="' . $autoid . '" class="rowId" data-acc="' . $accountName . '"><td class="messageid" data-messageid="' . $messageid . '"><div class="mFrom">' . $mailfrom . '</div><div class="subject"><strong>Subj:</strong>&nbsp;' . substr($subject, 0, 60) . '...' . '</div><div class="mTo"><strong>To:</strong>&nbsp;' . $mailto . '</div><div class="mailAttachments">&nbsp;'.listAttachments($mysqli,$messageid,$accountName).'</div></td><td class="mailComm"><div class="mailComment"><a href="#" class="commentLink"><i class="fa fa-lg fa-comment"></i></a></div></td><td class="categoryStatus"><div id="' . $autoid . '" class="category">' . getMailColorCategories($mysqli, $autoid, $color_table) . '</div></td><td align="right">' . $date . '</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="' . $messageid . '"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
        }
        echo $row.'<input type="hidden" id="rowCount" value="'.$count.'"/>';
    }
}else{
    echo "Please enter search phrase to search. No result for your search";
}
?>

