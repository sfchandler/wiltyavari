<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 24/01/2018
 * Time: 4:34 PM
 */

session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");

	$tableEmail = getTableEmail($mysqli,$_SESSION['accountName']);
    $color_table = getColorCategoryTableName($_SESSION['accountName']);
    $firstRow = $_POST['firstRow'];
    $mailList = $mysqli->prepare("SELECT 
										autoid,
										messageid,
										mailfrom,
										mailto,
										subject,
										date
									  FROM
										{$tableEmail}
									  WHERE autoid > ? ORDER BY date DESC LIMIT 0,1")or die($mysqli->error);
    $mailList->bind_param("i",$firstRow)or die($mysqli->error);
    $mailList->execute();
    $mailList->store_result();
    $mailList->bind_result($autoid, $messageid, $mailfrom, $mailto, $subject, $date) or die($mysqli->error);
    $attr = array();
    while($mailList->fetch()){
        $mcolorCat = getMailColorCategories($mysqli,$autoid,$color_table);
        if($mcolorCat == NULL){
            $mcolorCat = '';
        }
        $row = array('autoid'=>$autoid,'accname'=>$_SESSION['accountName'],'messageid'=>$messageid,'mailfrom'=>$mailfrom,'subject'=>substr($subject,0,60).'...','mailto'=>$mailto,'maildate'=>$date,'mailcolor'=>$mcolorCat);
        //$row = $row.'<tr id="'.autoid.'" class="rowId" data-acc="'.$_SESSION['accountName'].'"><td class="messageid" data-messageid="'.$messageid.'"><div class="mFrom">'.$mailfrom.'</div><div class="subject"><strong>Subj:</strong>&nbsp;'.substr($subject,0,60).'...'.'</div><div class="mTo"><strong>To:</strong>&nbsp;'.$mailto.'</div></td><td class="categoryStatus"><div id="'.$autoid.'" class="category">'.getMailColorCategories($mysqli,$autoid,$color_table).'</div></td><td align="right">'.$date.'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
        //$row = $row.'<tr id="'.$autoid.'" class="rowId" data-acc="'.$_SESSION['accountName'].'"><td class="messageid" data-messageid="'.$messageid.'"><div class="mFrom">'.$mailfrom.'</div><div class="subject"><strong>Subj:</strong>&nbsp;'.substr($subject,0,60).'...'.'</div><div class="mTo"><strong>To:</strong>&nbsp;'.$mailto.'</div></td><td class="categoryStatus"><div id="'.$autoid.'" class="category">'.getMailColorCategories($mysqli,$autoid,$color_table).'</div></td><td align="right">'.$date.'</td><td><button id="checkBtn" class="checkBtn btn btn-xs btn-warning" type="button"><i class="fa fa-eye-slash"></i> Check</button></td><td><input type="hidden" name="messageid" id="messageid" value="'.$messageid.'"/><button id="callBtn" class="callBtn btn btn-xs btn-success" type="button"><i class="fa fa-phone"></i> Call</button></td></tr>';
        $attr[] = $row;
    }
    /*function date_compare($a, $b)
    {
        $t1 = strtotime($a['maildate']);
        $t2 = strtotime($b['maildate']);
        return $t2 - $t1;
    }
    usort($attr, 'date_compare');*/
    echo json_encode($attr);
?>