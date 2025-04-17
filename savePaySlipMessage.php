<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 22/09/2017
 * Time: 10:45 AM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['get']==1){
    echo getPaySlipMessage($mysqli);
}
if(isset($_POST['payslipmsg']) && !empty($_POST['payslipmsg'])){
    echo savePaySlipMessage($mysqli,$_POST['payslipmsg']);
}
?>