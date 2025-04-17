<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 2/05/2019
 * Time: 9:39 AM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

/*$files = array();
foreach (glob("../supervisor/*.xlsx") as $file) {
    $files[] = $file;
}*/
foreach (glob("supervisor/*.xlsx") as $filename) {
    unlink($filename);
}
/*var_dump($files);*/