<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
error_reporting(E_ALL);
ini_set('display_errors', true);
$output_dir = './jot/';
if(isset($_FILES['passportFile']))
{
    if ($_FILES['passportFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['passportFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['passportFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['passportFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['passportFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName . time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['passportFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['passportFile']['error'];
            }
        }
    }
}else if(isset($_FILES['birthFile']))
{
    if ($_FILES['birthFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['birthFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['birthFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['birthFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['birthFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName . time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['birthFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['birthFile']['error'];
            }
        }
    }
}else if(isset($_FILES['citizenFile']))
{
    if ($_FILES['citizenFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['citizenFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['citizenFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['citizenFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['citizenFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName . time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['citizenFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['citizenFile']['error'];
            }
        }
    }
}else if(isset($_FILES['drivingFile']))
{
    if ($_FILES['drivingFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['drivingFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['drivingFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['drivingFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['drivingFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName.'-drv-'. time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['drivingFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['drivingFile']['error'];
            }
        }
    }
}else if(isset($_FILES['medicareFile']))
{
    if ($_FILES['medicareFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['medicareFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['medicareFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['medicareFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['medicareFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName.'-med-'. time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['medicareFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['medicareFile']['error'];
            }
        }
    }
}else if(isset($_FILES['studentFile']))
{
    if ($_FILES['studentFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['studentFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['studentFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['studentFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['studentFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName.'-st-'. time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['studentFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['studentFile']['error'];
            }
        }
    }
}else if(isset($_FILES['policeFile']))
{
    if ($_FILES['policeFile']['error'] > 0)
    {
        echo 'Error: ' . $_FILES['policeFile']['error'] . '<br>';
    }
    else
    {
        if(file_exists($_FILES['policeFile']['name'])){
            echo 'Please rename your file to a different name';
        }else {
            $fileName = pathinfo($_FILES['policeFile']['name'],PATHINFO_FILENAME);
            $fileExt = pathinfo($_FILES['policeFile']['name'], PATHINFO_EXTENSION);
            $newFileName = $fileName.'-pc-'. time() . "." . $fileExt;
            $filePath = $output_dir . $newFileName;
            if (move_uploaded_file($_FILES['policeFile']['tmp_name'], $output_dir .$newFileName)) {
                echo base64_encode($filePath);
            } else {
                echo 'Error Uploading' . $_FILES['policeFile']['error'];
            }
        }
    }
}
