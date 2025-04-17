<?php
include("includes/db_conn.php");
include("includes/functions.php");
include("includes/PdfToText.php");
require_once("includes/pdf2text/pdf2text.php");
require_once("includes/filetotext.php");
require_once("includes/ForceUTF8/Encoding.php");
use \ForceUTF8\Encoding;
ini_set('max_execution_time', 1000000);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
mysqli_set_charset($mysqli, "utf8");
$query = 'SELECT messageid,filepath FROM attachment WHERE contents IS NULL';
$fileInfo = array();
$docTextSubstring = '';
if($stmt = $mysqli->prepare($query)){
    $stmt->execute();
    $stmt->store_result();
    $num_of_rows = $stmt->num_rows;
    $stmt->bind_result($messageid, $filepath);
    $count = 0;
    while ($stmt->fetch()) {
        $count++;
        $fullPath = __DIR__.str_replace('./','/',$filepath);
        try{
            $docObj = new Filetotext($fullPath);
            $docText = $docObj->convertToText();
            $docTextSubstring = substr($docText,0,10000);
            echo 'file path '.$fullPath.'<br>';
        } catch (Exception $e1) {
            echo 'ERRORS >>>>'.$e1->getMessage();
        }
        $fileInfo[] = array('messageid'=>$messageid,'filepath'=>$filepath,'docText'=>$docTextSubstring);
    }
    $stmt->free_result();
    $stmt->close();
}
$update = $mysqli->prepare("UPDATE attachment SET contents = ? WHERE filepath = ?") or die($mysqli->error);
foreach ($fileInfo as $file) {
    $docTxt = trim(preg_replace('/\s+/', ' ', $file['docText']));
    $msgid = $mysqli->real_escape_string($file['messageid']);
    $fpath = $mysqli->real_escape_string($file['filepath']);
    $utf8_string = Encoding::fixUTF8($docTxt);
    if (empty($utf8_string)) {
        $utf8_string = 'CONVERSION-ERROR';
    }
    $extension = pathinfo(basename($fpath), PATHINFO_EXTENSION);
    if ($extension == 'pdf' || $extension == 'doc' || $extension == 'docx' || $extension == 'rtf') {
        $update->bind_param("ss", $utf8_string, $fpath) or die($mysqli->error);
        $update->execute() or die($mysqli->error);
        $update->fetch();
        $nrows = $update->affected_rows;
        echo $fpath.' '.$nrows.'<br>';
    }
}
$update->close();
