<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/
$docTypeId = $_POST['docTypeId'];
$candid = $_POST['candid'];
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
        $notes = $_POST['notes'];
    }

    $allowed = array('jpg','JPG','jpeg','JPEG','png','PNG','gif','GIF','rtf','RTF','doc','DOC','docx','DOCX','pdf','PDF');
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
                    /*if ($docTypeId == 17) {
                        employeeImageUpdate($mysqli, addslashes(base64_encode(file_get_contents($_FILES['file']['tmp_name']))), $candid);
                    }*/
                    try {
                        $update = updateCandidateDocs($mysqli, $candid, $docTypeId, $fileName, $filePath, $validFrom, $validTo, $reviewDate, $notes);
                        if (($update == 'Added') || ($update == 'Updated')) {
                            try {
                                if (move_uploaded_file($_FILES['file']['tmp_name'], $output_dir . $_FILES['file']['name'])) {
                                    if ($action == 'VACCINE') {
                                        $msg = 'Vaccination Result Uploaded';
                                        $firstName = getCandidateFirstNameByCandidateId($mysqli, $candid);
                                        $lastName = getCandidateLastNameByCandidateId($mysqli, $candid);
                                        generateCovidVaccinationMail($mysqli, $candid, $firstName, $lastName);
                                        header("Location:vaccination.php?msg=$msg&username=$candid");
                                    } else {
                                        if(!empty($_SESSION['userType'])){
                                            $userType = $_SESSION['userType'];
                                        }else{
                                            $userType = 'CANDIDATE';
                                        }
                                        echo getCandidateDocuments($mysqli, $candid,$userType);
                                    }
                                } else {
                                    throw new Exception('Could not move file' . $_FILES['file']['error']);//echo 'Error Uploading' . $_FILES['file']['error'];
                                }
                            } catch (Exception $e) {
                                echo $e->getMessage();
                            }
                        } else {
                            if ($action == 'VACCINE') {
                                $msg = 'Error uploading Vaccination Result';
                                header("Location:vaccination.php?msg=$msg&username=$candid");
                            }
                            echo 'Error Uploading' . $update;
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