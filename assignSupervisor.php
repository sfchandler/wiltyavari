<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 13/10/2017
 * Time: 3:32 PM
 */
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require 'includes/PHPMailer-master/src/Exception.php';
require 'includes/PHPMailer-master/src/PHPMailer.php';
require 'includes/PHPMailer-master/src/SMTP.php';

if(isset($_REQUEST['srchCanId'])&& isset($_REQUEST['supervisor'])&&($_REQUEST['type'] == 'ASSIGN')){
   echo assignSupervisor($mysqli,$_REQUEST['srchCanId'],$_REQUEST['supervisor'],$_SESSION['userSession']);
}else if(isset($_REQUEST['srchCanId'])&&($_REQUEST['type'] == 'MAKE')&& isset($_REQUEST['supervisorClient'])){
    echo makeSupervisor($mysqli,$_REQUEST['srchCanId'],$_REQUEST['supervisorClient']);
}/*else{
    echo 'data not set';
}*/

?>