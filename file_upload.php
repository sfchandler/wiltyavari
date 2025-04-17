<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$output_dir = 'mms/';
$action = $_REQUEST['UPLOAD'];
if(isset($_FILES['file']))
{
    if ($_FILES['file']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else
    {
        if(!file_exists('mms/')){
            mkdir('mms/', 0777);
        }
        $fileName = $_FILES['file']['name'];
        $filePath = $output_dir. $_FILES['file']['name'];
        if(move_uploaded_file($_FILES['file']['tmp_name'],$output_dir. $_FILES['file']['name'])){
            echo DOMAIN_URL.'/'.$filePath;
        }else{
            echo 'Error Uploading';
        }
    }
}
