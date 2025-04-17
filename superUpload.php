<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$output_dir = 'super/';
if(isset($_FILES['superFile']))
{
    if ($_FILES['superFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['superFile']['error'] . '<br>';
    }
    else
    {
        if(!file_exists('super/')){
            mkdir('super/', 0777);
            chown('./super/','chandler');
        }
        if(file_exists($_FILES['superFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = $_FILES['superFile']['name'];
            $fileExt = pathinfo($_FILES['superFile']['name'], PATHINFO_EXTENSION);
            //$filePath = $output_dir . $fileName;
            $newFileName = $fileName . time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['superFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['superFile']['error'];
            }
        }
    }
}

?>