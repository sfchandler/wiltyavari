<?php 
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
  $col = $mysqli->prepare("SELECT catid, catcolor, category FROM color_category") or die($mysqli->error);
  $col->execute();
  $col->bind_result($catid,$catcolor,$category) or die($mysqli->error);
  $colorArray = array();
  while($col->fetch()){
	  $row = array('catid'=>$catid,'catcolor'=>$catcolor,'category'=>$category);
	  $colorArray[] = $row;
  }
echo json_encode($colorArray);
?>