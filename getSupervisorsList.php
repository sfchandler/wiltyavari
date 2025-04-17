<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 18/07/2017
 * Time: 2:38 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$param = $_REQUEST['param'];
$dropdown = $_REQUEST['dropdown'];
$supervisorId = $_REQUEST['supervisorId'];
if($param <> 'None' && $dropdown <> '1' && !isset($supervisorId)) {
    $ps = explode('-', $param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo getSupervisors($mysqli,$clientId,$stateId,$deptId);
}else if($param<>'None' && $dropdown == '1'&& !isset($supervisorId)){
    $ps = explode('-', $param);
    $clientId = $ps[0];
    $stateId = $ps[1];
    $deptId = $ps[2];
    echo getSupervisorsForDropdown($mysqli,$clientId,$stateId,$deptId);
}else if(isset($supervisorId)&& ($supervisorId <> 'None')){
    echo getSupervisorDetailsById($mysqli,$supervisorId);
}
?>