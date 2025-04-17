<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 8/08/2017
 * Time: 3:44 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$jobCode = $_REQUEST['jobCode'];
$payAwdCode = $_REQUEST['payAwdCode'];
$payAwdCodeDesc = $_REQUEST['payAwdCodeDesc'];
$avgNormalHrs = $_REQUEST['avgNormalHrs'];
$spreadStart = $_REQUEST['spreadStart'];
$spreadEnd = $_REQUEST['spreadEnd'];
$spreadDuration = $_REQUEST['spreadDuration'];
$firstEightHours = $_REQUEST['firstEightHours'];
$minimumHrs = $_REQUEST['minimumHrs'];
$overtimeAfterHrs = $_REQUEST['overtimeAfterHrs'];
$overtimeSatAfterHrs = $_REQUEST['overtimeSatAfterHrs'];
$overtimeSunAfterHrs = $_REQUEST['overtimeSunAfterHrs'];
$earlyMorningShiftStartTime = $_REQUEST['earlyMorningShiftStartTime'];
$earlyMorningShiftEndTime = $_REQUEST['earlyMorningShiftEndTime'];
$dayShiftStartTime = $_REQUEST['dayShiftStartTime'];
$dayShiftEndTime = $_REQUEST['dayShiftEndTime'];
$afternoonShiftStartTime = $_REQUEST['afternoonShiftStartTime'];
$afternoonShiftEndTime = $_REQUEST['afternoonShiftEndTime'];
$nightShiftStartTime = $_REQUEST['nightShiftStartTime'];
$nightShiftEndTime = $_REQUEST['nightShiftEndTime'];
$updateStatus = $_REQUEST['updatePayrule'];
if(isset($jobCode) && isset($payAwdCode) && isset($avgNormalHrs) && isset($spreadStart) && isset($spreadEnd)&&isset($spreadDuration)&& isset($minimumHrs)){
    echo savePayrule($mysqli,$jobCode,$payAwdCode,$payAwdCodeDesc,$avgNormalHrs,$spreadStart,$spreadEnd,$spreadDuration,$firstEightHours,$minimumHrs,$overtimeAfterHrs,$overtimeSatAfterHrs,$overtimeSunAfterHrs,$earlyMorningShiftStartTime,$earlyMorningShiftEndTime,$dayShiftStartTime,$dayShiftEndTime,$afternoonShiftStartTime,$afternoonShiftEndTime,$nightShiftStartTime,$nightShiftEndTime);
}else if($updateStatus == 'update'){
    echo savePayrule($mysqli,$jobCode,$payAwdCode,$payAwdCodeDesc,$avgNormalHrs,$spreadStart,$spreadEnd,$spreadDuration,$firstEightHours,$minimumHrs,$overtimeAfterHrs,$overtimeSatAfterHrs,$overtimeSunAfterHrs,$earlyMorningShiftStartTime,$earlyMorningShiftEndTime,$dayShiftStartTime,$dayShiftEndTime,$afternoonShiftStartTime,$afternoonShiftEndTime,$nightShiftStartTime,$nightShiftEndTime);
}


?>