<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 30/01/2018
 * Time: 12:00 PM
 */

session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");

$tableEmail = getTableEmail($mysqli,$_SESSION['accountName']);
$mailAll = $mysqli->prepare("SELECT 
                                            autoid,
                                            messageid,
                                            mailfrom,
                                            mailto,
                                            subject,
                                            date
                                          FROM
                                            {$tableEmail} ORDER BY autoid DESC")or die($mysqli->error);
$mailAll->execute();
$mailAll->store_result();
$mailAll->fetch();
$numRows = $mailAll->num_rows;
echo $numRows;