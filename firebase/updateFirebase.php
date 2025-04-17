<?php /** @noinspection PhpLanguageLevelInspection */
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 22/05/2019
 * Time: 5:00 PM
 */
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
require_once("../firebase/vendor/autoload.php");

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

try {
    $serviceAccount = ServiceAccount::fromJsonFile( "../firebase/secret/outapay-firebase-service-file.json");
    $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
    $database = $firebase->getDatabase();
}catch (Exception $e){
    echo $e->getMessage();
}
$dataArray = getFirebaseShifts($mysqli);
foreach ($dataArray as $key=>$value){
    $shiftId = $value['shiftId'];
    try {
        $shiftArray = getShiftDataByShiftId($mysqli, $shiftId);
    }catch (Exception $e1){
        echo $e1->getMessage();
    }
    try{
        $database->getReference('shifts')->push($shiftArray);
        removeFirebaseShift($mysqli,$shiftId);
    }catch (Exception $e2){
        echo $e2->getMessage();
    }
}

/*try {
    $serviceAccount = ServiceAccount::fromJsonFile( './firebase/secret/outapay-firebase-service-file.json');
    $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
    $database = $firebase->getDatabase();
}catch (Exception $e){
    echo $e->getMessage();
}
try {

    $dataArray = array('shiftId'=>4,'shiftDate'=>'2019-8-29','empId'=>'CHAN1033001311');
    $database->getReference('shifts')->push($dataArray);
   // $shifts = new FirebaseShift();
}catch (Exception $e){
    echo $e->getMessage();
}*/
/*try{
    $dataArray = array('shiftId'=>4,'shiftDate'=>'2019-8-29','empId'=>'CHAN1033001311');
   var_dump($shifts->insert($dataArray));
}catch (Exception $e){
    echo $e->getMessage();
}*/
