<?php
session_start();
ini_set('memory_limit', '3078M');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
$tableAttahment = getTableAttachment($mysqli,$_SESSION['accountName']);
$tableEmail = getTableEmail($mysqli,$_SESSION['accountName']);
$color_table;
if($_SESSION['accountName'] == 'melbourne'){
    $color_table = 'mail_color_category';
}else if($_SESSION['accountName'] == 'health'){
    $color_table = 'health_mail_color_category';
}else if($_SESSION['accountName'] == 'sydney'){
    $color_table = 'sydney_mail_color_category';
}
$_SESSION['searchTxt'] = $_REQUEST['searchTxt'];
$_SESSION['subjectSearchTxt'] = $_REQUEST['subjectSearchTxt'];
$_SESSION['fromSearchTxt'] = $_REQUEST['fromSearchTxt'];
if(isset($_REQUEST['searchTxt']) || isset($_REQUEST['subjectSearchTxt']) || isset($_REQUEST['fromSearchTxt'])){

    $searchTxt = $_REQUEST['searchTxt'];
    $searchTerms = explode(',', $searchTxt);
    $searchTermsBits = array();

    echo $subjectSearchTxt = $_REQUEST['subjectSearchTxt'];
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
    }
    else if(!empty($searchString) && empty($subjectString)){
        $sqlString = $searchString;
    }
    else{
        $sqlString = $searchString;
    }

    if(empty($searchString) && empty($subjectString)&& empty($_REQUEST['fromSearchTxt'])){
        echo "Please enter search phrase to search";
    }else if(!empty($_REQUEST['fromSearchTxt'])&& empty($searchString) && empty($subjectString)){
        $fromSearchTxt = $_REQUEST['fromSearchTxt'];
        $searchClause = "SELECT 
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
							{$tableEmail}.mailfrom LIKE '%{$fromSearchTxt}%'
						  ORDER BY
							{$tableEmail}.date DESC	  
							";
        $matchingList = $mysqli->prepare($searchClause)or die($mysqli->error);
        $matchingList->execute();
        $matchingList->bind_result($autoid,$messageid, $uid, $msgno, $mailfrom, $mailto, $subject, $msgbody, $date,$filepath,$contents) or die($mysqli->error);
        $matchingList->store_result();
        $numRows = $matchingList->num_rows;
        $mailArray = array();
        $row;
        while($matchingList->fetch()){
            /*$row = $row.'<tr><td class="messageid" data-messageid="'.$messageid.'"><div>'.$mailfrom.'</div><div>'.substr($subject,0,60).'...</div><div>'.$mailto.'</div></td><td align="right">'.$date.'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><button id="diaryNotesBtn" class="diaryNotesBtn btn btn-xs btn-info" type="button"><i class="fa fa-inbox"></i> Notes</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td></td></tr>';*/
            $row = $row.'<tr class="rowId"><td class="messageid" data-messageid="'.$messageid.'"><div>'.$mailfrom.'</div><div>'.substr($subject,0,60).'...</div><div>'.$mailto.'</div></td><td class="categoryStatus"><div id="'.$autoid.'" class="category">'.getMailColorCategories($mysqli,$autoid,$color_table).'</div></td><td align="right">'.$date.'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td></td></tr>';
        }
        echo $row.'<input type="hidden" id="rowCount" value="'.$numRows.'"/>';
    }else {
        $searchClause = "SELECT 
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
							".$sqlString."
						  ORDER BY
							{$tableEmail}.date DESC	  
							";
        $matchingList = $mysqli->prepare($searchClause)or die($mysqli->error);
        $matchingList->execute();
        $matchingList->bind_result($autoid,$messageid, $uid, $msgno, $mailfrom, $mailto, $subject, $msgbody, $date,$filepath,$contents) or die($mysqli->error);
        $matchingList->store_result();
        $numRows = $matchingList->num_rows;
        $mailArray = array();
        $row;
        while($matchingList->fetch()){
            /*$row = $row.'<tr><td class="messageid" data-messageid="'.$messageid.'"><div>'.$mailfrom.'</div><div>'.substr($subject,0,60).'...</div><div>'.$mailto.'</div></td><td align="right">'.$date.'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><button id="diaryNotesBtn" class="diaryNotesBtn btn btn-xs btn-info" type="button"><i class="fa fa-inbox"></i> Notes</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td></td></tr>';*/
            $row = $row.'<tr class="rowId"><td class="messageid" data-messageid="'.$messageid.'"><div>'.$mailfrom.'</div><div>'.substr($subject,0,60).'...</div><div>'.$mailto.'</div></td><td class="categoryStatus"><div id="'.$autoid.'" class="category">'.getMailColorCategories($mysqli,$autoid,$color_table).'</div></td><td align="right">'.$date.'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td><td></td></tr>';
        }
        echo $row.'<input type="text" id="rowCount" value="'.$numRows.'"/>';
    }
}else{
    echo "Please enter search phrase to search. No result for your search";
}
?>

