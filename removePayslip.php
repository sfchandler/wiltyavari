<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 18/01/2019
 * Time: 4:16 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$candidateId = $_REQUEST['candidateId'];
$removeId = $_REQUEST['removeId'];

removePaySlip($mysqli,$removeId);
echo getCandidatePaySlips($mysqli,$candidateId);