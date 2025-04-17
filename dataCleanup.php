<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 4/07/2017
 * Time: 11:01 AM
 */
require_once ("includes/db_conn.php");
require_once ("includes/functions.php");
//ini_set('memory_limit', '3078M');
ini_set('max_execution_time', 10000000000);

//$emailAccount = $_POST['emailAccount'];
melbourneEmailCleanup($mysqli);
/*sydneyEmailCleanup($mysqli);*/
/*if($emailAccount == 'melbourne'){
    echo melbourneEmailCleanup($mysqli);
}else if($emailAccount == 'sydney'){
    echo sydneyEmailCleanup($mysqli);
}*/

?>