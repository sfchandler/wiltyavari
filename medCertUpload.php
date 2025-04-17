<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$docTypeId = 72;
$candid = base64_decode($_POST['canId']);
if(!empty($candid)) {
    $output_dir = './documents/' . $candid . '/';

    $action = $_POST['action'];
    if (empty($_POST['validFrom'])) {
        $validFrom = '';
    } else {
        $validFrom = $_POST['validFrom'];
    }
    if (empty($_POST['validTo'])) {
        $validTo = '';
    } else {
        $validTo = $_POST['validTo'];
    }
    if (empty($_POST['reviewDate'])) {
        $reviewDate = '';
    } else {
        $reviewDate = $_POST['reviewDate'];
    }
    if (empty($_POST['notes'])) {
        $notes = '';
    } else {
        $notes = base64_decode($_POST['notes']);
    }

    $allowed = array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','rtf','RTF','doc','DOC','docx','DOCX','pdf','PDF','mp3','MP3');
    $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    if(in_array($ext,$allowed)) {
        if (!empty($candid)) {
            if (isset($_FILES['file'])) {
                if ($_FILES['file']['error'] > 0) {
                    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
                } else {
                    if (!is_dir('documents/' . $candid)) {
                        mkdir('documents/' . $candid, 0777);
                    }
                    $fileName = $_FILES['file']['name'];
                    $filePath = $output_dir . $_FILES['file']['name'];
                    try {

                        $update = updateCandidateDocs($mysqli, $candid, $docTypeId, $fileName, $filePath, $validFrom, $validTo, $reviewDate, $notes);
                        if (($update == 'Added') || ($update == 'Updated')) {
                            try {
                                if (move_uploaded_file($_FILES['file']['tmp_name'], $output_dir . $_FILES['file']['name'])) {
                                    //if(!validateCandidateDocumentByDocTypeId($mysqli,$candid,72)) {
                                        generateNotification('outapay@outapay.com', '', '', 'Medical Certificate - Sick leave upload', DEFAULT_EMAIL, DOMAIN_NAME, 'Candidate ' . getCandidateFullName($mysqli, $candid) . '(' . $candid . ') has submitted Medical Certificate', '', '');
                                    //}
                                    echo 'SUCCESS';
                                } else {
                                    throw new Exception('Could not move file' . $_FILES['file']['error']); //echo 'Error Uploading' . $_FILES['file']['error'];
                                }
                            }catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                }
            }
        } else {
            echo 'Error Uploading';
        }
    }else{
        echo 'File type not allowed';
    }
}
?>