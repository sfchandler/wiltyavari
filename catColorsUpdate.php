<?php 
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");
// divert according to mail account
if(($_SESSION['userSession']<>'')&& ($_SESSION['accountName'] <> '')) {
    if (isset($_REQUEST['autoid']) && isset($_REQUEST['catid']) && isset($_SESSION['userSession'])) {
        $autoid = $_REQUEST['autoid'];
        $catid = $_REQUEST['catid'];
        $username = $_SESSION['userSession'];
        $accountName = $_SESSION['accountName'];
        $table = getColorCategoryTableName($accountName);

        //Remove Not Suitable mail from inbox
        if($catid == 7){
            $inb_status = 1;
            $status = 0;
            $update = $mysqli->prepare('UPDATE resume SET inbox_status = ?, status = ? WHERE autoid = ?') or die($mysqli->error);
            $update->bind_param("iii",$inb_status,$status,$autoid)or die($mysqli->error);
            $update->execute();
            $update->free_result();
        }

        $stmt = $mysqli->prepare("SELECT autoid,catid FROM {$table} WHERE autoid = ? AND catid = ?") or die($mysqli->error);
        $stmt->bind_param("ii", $autoid, $catid) or die($mysqli->error);
        $stmt->execute();
        $stmt->store_result();
        $stmt->fetch();
        $num_of_rows = $stmt->num_rows;
        if ($num_of_rows > 0) {
            $delete = $mysqli->prepare("DELETE FROM {$table} WHERE autoid = ? AND catid = ?") or die($mysqli->error);
            $delete->bind_param("ii", $autoid, $catid) or die($mysqli->error);
            $delete->execute();
            $nr = $delete->affected_rows;
            if ($nr > 0) {
                echo getMailColorCategories($mysqli, $autoid,$table);
            } else {
                echo getMailColorCategories($mysqli, $autoid,$table);
            }
        } else if ($catid == '1') {
            $del = $mysqli->prepare("DELETE FROM {$table} WHERE autoid = ?") or die($mysqli->error);
            $del->bind_param("i", $autoid) or die($mysqli->error);
            $del->execute();
            $nrows = $del->affected_rows;
            if ($nrows > 0) {
                echo getMailColorCategories($mysqli, $autoid,$table);
            } else {
                echo getMailColorCategories($mysqli, $autoid,$table);
            }
        } else {
            $update = $mysqli->prepare("INSERT INTO {$table}(catid,autoid,username,modifiedDate)VALUES(?,?,?,NOW())") or die ($mysqli->error);
            $update->bind_param("iis", $catid, $autoid, $_SESSION['userSession']) or die($mysqli->error);
            $update->execute();
            $nrows = $update->affected_rows;
            if ($nrows == '1') {
                //addToUserTracking($mysqli,$autoid,$catid,'',$_SESSION['userSession']);
                echo getMailColorCategories($mysqli, $autoid,$table);
            } else {
                echo getMailColorCategories($mysqli, $autoid,$table);
            }
        }
    }
}
?>