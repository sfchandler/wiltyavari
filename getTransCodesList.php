<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 17/08/2017
 * Time: 2:39 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if($_REQUEST['menu'] == 'dropdown'){
    echo getTransactionCodesForDropdown($mysqli);
}else {
    echo getTransCodeList($mysqli);
}
?>