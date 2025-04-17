<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$output_dir = 'tax/';
if(isset($_FILES['file']))
{
        if ($_FILES['file']['error'] > 0)
        {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        }
        else
        {
            if(!file_exists('tax/')){
                mkdir('tax/', 0777);
                chown('./tax/','chandler');
            }
            if(file_exists($_FILES['file']['name'])){
                echo 'Please rename your file to a different name';
            }else {
                $fileName = $_FILES['file']['name'];
                $fileExt = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                //$filePath = $output_dir . $fileName;
                $newFileName = $fileName . time() . "." . $fileExt;
                $filePath = $output_dir . $newFileName;
                if (move_uploaded_file($_FILES['file']['tmp_name'], $output_dir .$newFileName)) {
                    echo base64_encode($filePath);
                } else {
                    echo 'Error Uploading' . $_FILES['file']['error'];
                }
            }
        }
}

?>