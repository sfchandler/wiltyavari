<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once("firebase/vendor/autoload.php");
require_once("includes/Carbon.php");
//date_default_timezone_set('Australia/Melbourne');


use Carbon\Carbon;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

try {
    $dataArray = getConfirmedShiftsNotCheckedInForReminder($mysqli);
    if(!empty($dataArray)) {
        foreach ($dataArray as $key => $value) {
            $shiftId = $value['shift_id'];
            $shiftStart = $value['shift_start'];
            $shiftDate = $value['shift_date'];

            $currentDateTime = Carbon::createFromFormat('Y-m-d H:i', date('Y-m-d H:i'));
            $fiveMinutesBefore = Carbon::createFromFormat('Y-m-d H:i', $shiftDate . ' ' . $shiftStart)->subMinutes(5);
            $rosterStart = Carbon::createFromFormat('Y-m-d H:i', $shiftDate . ' ' . $shiftStart);
            if ($currentDateTime->between($fiveMinutesBefore, $rosterStart)) {
                // 5 minutes before shift start
                //$shiftArray = getShiftDataByShiftId($mysqli, $shiftId);
                $shiftInfo = getShiftInfoForReminderNotification($mysqli,$shiftId);
                $client = '';
                $shiftDate = '';
                $shiftStart = '';
                $shiftEnd = '';
                foreach ($shiftInfo as $k => $v) {
                    if ($k == 'client') {
                        $client = $v;
                    }
                    if ($k == 'shiftDate') {
                        $shiftDate = $v;
                    }
                    if ($k == 'shiftStart') {
                        $shiftStart = $v;
                    }
                    if ($k == 'shiftEnd') {
                        $shiftEnd = $v;
                    }
                }
                $title = 'Reminder Please ClockIn';
                $body = $client.' - '.$shiftStart;
                $notification = Notification::fromArray(['title' => $title, 'body' => $body]);
                $notification = Notification::create($title, $body);
                $data = array_map('strval', $shiftInfo);
                $idTokenString = getuid($mysqli, getCandidateIdByShiftId($mysqli, $shiftId));
                if (!empty($idTokenString)) {
                    $deviceToken = $idTokenString;
                    $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . "/firebase/secret/labourbank-firebase-service-file.json");
                    $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
                    $messaging = $firebase->getMessaging();
                    $message = CloudMessage::withTarget('token', $deviceToken)->withNotification($notification)->withData($data);
                    $messaging->send($message);
                    //notifyOnFirebase('MATCHING SHIFT NOTIFICATION SENT '.$shiftId.' '.$shiftStart.' '.$shiftDate);
                    //echo 'CLOUD MESSAGE SENT'.$shiftId.' '.$shiftStart.' '.$shiftDate.'<br>';
                }
            }else{
                //echo 'SHIFT NOT MATCHING';
            }
        }
    }else{
        //notifyOnFirebase('NO CONFIRMED SHIFT DATA ');
        //echo 'EMPTY DATA ARRAY <br>';
    }
}catch (Exception $e){
    //echo 'ERROR '.$e->getMessage();
    error_log('ERROR REMINDER --> '.$e->getMessage());
    //notifyOnFirebase('ERROR REMINDER --> '.$e->getMessage());
}

function notifyOnFirebase($note)
{
    require_once("includes/PHPMailer-master-old/PHPMailerAutoload.php");
    $mail = new PHPMailer();
    $mail->CharSet = "utf-8";
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->Username = DEFAULT_EMAIL;
    $mail->Password = DEFAULT_EMAIL_PASSWORD;
    $mail->SMTPSecure = "tls";
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    $mail->Host = "outlook.office365.com";
    $mail->LE = "\r\n";
    $mail->setFrom(DEFAULT_EMAIL, DOMAIN_NAME);
    $subject = 'Firebase CRON UPDATE';
    $mail->AddAddress('swarnajithf@chandlerpersonnel.com.au');
    $mail->AddEmbeddedImage("../img/chandler-logo-mail.jpg", "chandlerLogo", "chandler-logo-mail.jpg");
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $body = 'Firebase CRON '.$note;
    $mail->Body = $body;
    $mail->send();
    if ($mail) {
        echo "MAILSENT";
    } else {
        echo "FAILURE";
    }
}