<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 7/09/2017
 * Time: 4:42 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['candidateId'] <> ''){
   echo getCandidateTFN($mysqli,$_REQUEST['candidateId']);
}