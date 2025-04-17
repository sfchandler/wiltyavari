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
        $accountName = 'jobboard';
        $table = getColorCategoryTableName($accountName);

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
                echo getMailColorCategories($mysqli, $autoid,$table);
            } else {
                echo getMailColorCategories($mysqli, $autoid,$table);
            }
        }
    }
}
?>