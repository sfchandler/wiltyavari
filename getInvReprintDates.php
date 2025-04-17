<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 28/11/2017
 * Time: 10:59 AM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

echo getInvoiceReprintDates($mysqli);

?>