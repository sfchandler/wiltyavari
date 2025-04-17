<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 20/10/2017
 * Time: 12:16 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getPayrollNamesForDropdown($mysqli);

?>