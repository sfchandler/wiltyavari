<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$consultantId = getConsultantId($mysqli,$_SESSION['userSession']);
$jobOrderId = $_POST['jobId'];
$output_dir = 'joborder/'.$jobOrderId.'/';

    if(isset($_FILES['file']))
    {
        if ($_FILES['file']['error'] > 0)
        {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else
        {
            if(!file_exists('joborder/'.$jobOrderId)){
                mkdir('joborder/'.$jobOrderId, 0777);
                chown('joborder/' . $jobOrderId,'chandler');
            }

            $fileName = pathinfo($_FILES['file']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName . time() . "." . $fileExt;

            $filePath = $output_dir. $newFileName;

            $update = updateJobOrderAttachment($mysqli,$jobOrderId,$consultantId,$newFileName,$filePath);
            if(($update == 'Added')){
                try {
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $output_dir.$newFileName)) {
                        echo 'SUCCESS';
                    } else {
                        throw new Exception('Could not move file');
                    }
                }catch (Exception $e){
                    echo $e->getMessage();
                }
            }else{
                echo 'Error Uploading'.$jobOrderId,$consultantId,$newFileName,$filePath;
            }
        }
    }

?>