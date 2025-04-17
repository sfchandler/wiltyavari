<?php

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/


if(isset($_POST['action']) or isset($_GET['view'])) //show all events
{
    $consId = getConsultantId($mysqli,$_SESSION['userSession']);
    $consEmail = getConsultantEmail($mysqli,$consId);
    $empId = getCandidateIdByEmail($mysqli,trim(strtolower($consEmail)));
    $empImage = getCandidateImage($mysqli,$empId);
    $img = '';
    if(!empty($empImage)){
        $img = '<img src="data:image/png;base64,'.$empImage.'" width="128" height="128"/>';
    }else{
        $img = '<img src="img/interview.png" width="128" height="128"/>';
    }
    if(isset($_GET['view']))
    {
        header('Content-Type: application/json');
        $starttime = $mysqli->real_escape_string($_GET["start"]);
        $endtime = $mysqli->real_escape_string($_GET["end"]);
        $result = $mysqli->prepare("SELECT id,start,end,title,photo,consultant_id FROM events WHERE (start >= ? AND start <= ? AND consultant_id = ?) ")or die($mysqli->error);
        $result->bind_param("ssi",$starttime,$endtime,$consId)or die($mysqli->error);
        $result->execute();
        $result->store_result();
        $dataArray = array();
        $result->bind_result($id,$start,$end,$title,$photo,$consultant_id)or die($mysqli->error);
        while($result->fetch())
        {
            $row = array('id' => $id,'start'=>$start,'end'=>$end,'title'=>$title,'photo'=>$photo,'consultant_id');
            $dataArray[] = $row;
            //echo json_encode()
        }
        echo json_encode($dataArray);
        exit;
        //echo json_encode('yyyyy');
    }elseif($_POST['action'] == "add"){
        $title = $mysqli->real_escape_string($_POST["title"]);
        $startTime = $mysqli->real_escape_string(date('Y-m-d H:i:s',strtotime($_POST["start"])));
        $endTime = $mysqli->real_escape_string(date('Y-m-d H:i:s',strtotime($_POST["end"])));
        $ins = $mysqli->prepare("INSERT INTO events (title,start,end,photo,consultant_id) VALUES(?,?,?,?,?)")or die($mysqli->error);
        $ins->bind_param("ssssi",$title,$startTime,$endTime,$img,$consId)or die ($mysqli->error);
        $ins->execute();
        $nrows = $ins->affected_rows;
        if ($nrows >0) {
            header('Content-Type: application/json');
            echo '{"id":"' . $ins->insert_id . '"}';
            exit;
        }else{
            echo $mysqli->error;
        }
    }elseif($_POST['action'] == "update"){
        $start = $mysqli->real_escape_string(date('Y-m-d H:i:s',strtotime($_POST["start"])));
        $end = $mysqli->real_escape_string(date('Y-m-d H:i:s',strtotime($_POST["end"])));
        $id = $mysqli->real_escape_string($_POST["id"]);
        $up = $mysqli->prepare("UPDATE events SET start = ?, end = ?, consultant_id = ? WHERE id = ?")or die($mysqli->error);
        $up->bind_param("ssii",$start,$end,$consId,$id)or die($mysqli->error);
        $up->execute();
        $nrows = $up->affected_rows;
        if ($nrows >0) {
            echo "1";
        }else{
            echo $mysqli->error;
        }
        exit;
    }elseif($_POST['action'] == "delete"){
        $id = $mysqli->real_escape_string($_POST["id"]);
        $del = $mysqli->prepare("DELETE FROM events WHERE id = ?")or die($mysqli->error);
        $del->bind_param("i",$id)or die($mysqli->error);
        $del->execute();
        if ($del->affected_rows > 0) {
            $del->free_result();
            $ins = $mysqli->prepare("INSERT INTO events_delete(id)VALUES (?)") or die($mysqli->error);
            $ins->bind_param("i",$id)or die($mysqli->error);
            $ins->execute();
            echo "1";
        }
        exit;
    }
}