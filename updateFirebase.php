<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once("firebase/vendor/autoload.php");

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Firebase\Auth\Token\Exception\InvalidToken;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

$dataArray = getFirebaseShifts($mysqli);
foreach ($dataArray as $key=>$value){

    $shiftId = $value['shiftId'];
    try {
         $idTokenString = getuid($mysqli,getCandidateIdByShiftId($mysqli,$shiftId));
        if(!empty($idTokenString)) {
            $deviceToken = $idTokenString;
            try {
                $serviceAccount = ServiceAccount::fromJsonFile( __DIR__."/firebase/secret/wiltyavari-firebase-services.json");
                $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
                $messaging = $firebase->getMessaging();
            }catch (\Kreait\Firebase\Exception\Messaging\NotFound $e){
                echo 'error'.$e->getMessage();
            }
            $client = '';
            try {
                $shiftArray = getShiftDataByShiftIdForPushNotification($mysqli, $shiftId);

                $client = '';
                $shiftDate = '';
                $shiftStart = '';
                $shiftEnd = '';

                foreach ($shiftArray as $k=>$v){
                    if($k == 'client'){
                        $client = $v;
                    }
                    if($k == 'shiftDate'){
                        $shiftDate = $v;
                    }
                    if($k == 'shiftStart'){
                        $shiftStart = $v;
                    }
                    if($k == 'shiftEnd'){
                        $shiftEnd = $v;
                    }
                }
                //$client = implode( ", ", $shiftArray);
                $title = DOMAIN_NAME.' Shift Information';
                $body = $client.' '.$shiftDate.' '.$shiftStart.'-'.$shiftEnd;
                $notification = Notification::fromArray(['title' => $title,'body' => $body]);
                $notification = Notification::create($title,$body);
                $data = array_map('strval',$shiftArray);
            }catch (\Kreait\Firebase\Exception\Messaging\NotFound $e2){
                echo 'e2'.$e2->getMessage();
            }

            try{
                $message = CloudMessage::withTarget('token',$deviceToken)->withNotification($notification)->withData($data);
                $response = $messaging->send($message);
                //notifyOnFirebase(json_encode($response));
                removeFirebaseShift($mysqli,$shiftId);
                removeAllFirebaseShifts($mysqli);
            }catch (\Kreait\Firebase\Exception\Messaging\NotFound $e3){
                echo 'e3--'.$e3->getMessage();
            }

        }
    } catch (InvalidToken $e4) {
        echo $e4->getMessage();
    }
}
$deleteArray = getFirebaseRemovalShifts($mysqli);
foreach ($deleteArray as $k=>$v) {
    $deleteShiftId = $v['shiftId'];

    $deleteTokenString = getuid($mysqli, getCandidateIdByShiftId($mysqli, $deleteShiftId));
    if (!empty($deleteTokenString)) {
        $deviceToken = $deleteTokenString;
        try {
            $serviceAccount = ServiceAccount::fromJsonFile( __DIR__."/firebase/secret/wiltyavari-firebase-services.json");
            $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
            $messaging = $firebase->getMessaging();
        }catch (Exception $e5){
            echo $e5->getMessage();
        }
        $title = DOMAIN_NAME.' Shift Delete Information';
        $body = DOMAIN_NAME.' Shift Delete Notification';
        $notification = Notification::fromArray(['title' => $title, 'body' => $body]);
        $notification = Notification::create($title, $body);
        try {
            $shiftArray = getShiftDataByShiftId($mysqli, $deleteShiftId);
            $data = array_map('strval', $shiftArray);
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e6) {
            echo $e->getMessage();
        }
        try {
            $message = CloudMessage::withTarget('token', $deviceToken)->withNotification($notification)->withData($data);
            $messaging->send($message);
            deleteFirebaseshiftsFromTemp($mysqli,$deleteShiftId);
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e7) {
            echo $e7->getMessage();
        }
    }
}
removeAllFirebaseShifts($mysqli);

