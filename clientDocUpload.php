<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$clientId = $_POST['clientId'];
$output_dir = 'clientDocuments/'.$clientId.'/';
$docDesc = $_POST['docDesc'];
$notes = $_POST['notes'];

if(!empty($clientId) && ($clientId != 'None')){
    $allowed = array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','rtf','RTF','doc','DOC','docx','DOCX','pdf','PDF','mp3','MP3');
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if(in_array($ext,$allowed)) {
        if (isset($_FILES['file'])) {
            if ($_FILES['file']['error'] > 0) {
                echo 'Error: ' . $_FILES['file']['error'] . '<br>';
            } else {
                if (!is_dir('clientDocuments/' . $clientId)) {
                    mkdir('clientDocuments/' . $clientId, 0777);
                }
                $fileName = $_FILES['file']['name'];
                $filePath = $output_dir . $_FILES['file']['name'];

                $update = updateClientDocs($mysqli, $clientId, $docDesc, $fileName, $filePath, $notes);

                if (($update == 'Added') || ($update == 'Updated')) {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $output_dir . $_FILES['file']['name'])) {
                        echo getClientDocuments($mysqli, $clientId);
                    } else {
                        echo 'Error Uploading';
                    }
                } else {
                    echo 'Error Uploading';
                }
            }
        }
    }
}else{
    echo 'Error Uploading';
}
?>