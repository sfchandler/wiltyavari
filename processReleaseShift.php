<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
require_once("firebase/vendor/autoload.php");
require_once("includes/Carbon.php");

use Carbon\Carbon;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

/*$client_id = trim($_POST['client_id']);
$state_id = trim($_POST['state_id']);
$dept_id = trim($_POST['dept_id']);
$rel_date = trim($_POST['rel_date']);
$position_id = trim($_POST['position_id']);
$address_id = trim($_POST['address_id']);
$rel_start = trim($_POST['rel_start']);
$rel_end = trim($_POST['rel_end']);
$release_candidates = trim($_POST['checkbox_value']);
$candidate_ids = array_filter(explode(",", trim($_POST['checkbox_value'])));*/
try {
    if(empty($_POST['client_id'])) {
        $errors[] = 'Client not selected <br>';
    }else{
        $client_id = inputValidation($_POST['client_id']);
    }
    if(empty($_POST['state_id'])) {
        $errors[] = 'State not selected <br>';
    }else{
        $state_id = inputValidation($_POST['state_id']);
    }
    if(empty($_POST['dept_id'])) {
        $errors[] = 'Department not selected <br>';
    }else{
        $dept_id = inputValidation($_POST['dept_id']);
    }
    if(empty($_POST['rel_date'])) {
        $errors[] = 'Release date not selected <br>';
    }else{
        $rel_date = inputValidation($_POST['rel_date']);
    }
    if(empty($_POST['position_id'])) {
        $errors[] = 'Position not selected <br>';
    }else{
        $position_id = inputValidation($_POST['position_id']);
    }
    if(empty($_POST['address_id'])) {
        $errors[] = 'Location not selected <br>';
    }else{
        $address_id = inputValidation($_POST['address_id']);
    }
    if($_POST['rel_start'] == '00:00') {
        $errors[] = 'Shift start time not selected <br>';
    }else{
        $rel_start = inputValidation($_POST['rel_start']);
    }
    if($_POST['rel_end'] == '00:00') {
        $errors[] = 'Shift end time not selected <br>';
    }else{
        $rel_end = inputValidation($_POST['rel_end']);
    }
    if(empty($_POST['checkbox_value'])) {
        $errors[] = 'Please select a candidate to release shifts <br>';
    }else{
        $release_candidates = inputValidation($_POST['checkbox_value']);
    }
    if(!empty($errors)){
        $dataArray[] = array('p_id' => '', 'o_id' => '', 'msg' => $errors);
    }else {
        $candidate_ids = array_filter(explode(",", $release_candidates));
        $msg = '';
        //if (!validateReleasedShift($mysqli, $client_id, $state_id, $dept_id, $rel_date, $rel_start, $rel_end, $address_id)) {
            $shiftInfo = saveReleasedShift($mysqli, $client_id, $state_id, $dept_id, $position_id, $rel_date, $rel_start, $rel_end, $address_id, $release_candidates);
            $client = getClientNameByClientId($mysqli, $client_id);

            $dataArray = array();
            foreach ($candidate_ids as $canId) {
                if (!validateShiftOverlap($mysqli, $canId, $rel_date, $rel_start, $rel_end)) {
                    $title = 'Shift Release';
                    $body = $client . ' - ' . $rel_date . ' - ' . $rel_start;
                    $notification = Notification::fromArray(['title' => $title, 'body' => $body]);
                    $notification = Notification::create($title, $body);
                    $relShiftInfo = array('rel_shift_id' => $shiftInfo, 'client' => $client, 'rel_shift_date' => $rel_date, 'rel_shift_start' => $rel_start, 'rel_shift_end' => $rel_end);
                    $data = array_map('strval', $relShiftInfo);
                    $idTokenString = getuid($mysqli, $canId);
                    if (!empty($idTokenString)) {
                        $deviceToken = $idTokenString;
                        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . "/firebase/secret/labourbank-firebase-service-file.json");
                        $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
                        $messaging = $firebase->getMessaging();
                        $message = CloudMessage::withTarget('token', $deviceToken)->withNotification($notification)->withData($data);
                        $messaging->send($message);
                        $dataArray[] = array('p_id' => $canId, 'o_id' => '', 'msg' => 'Shift released');
                    }
                } else {
                    $dataArray[] = array('p_id' => '', 'o_id' => $canId, 'msg' => 'Overlapping shift detected');
                }
            }
        /*} else {
            $dataArray[] = array('p_id' => '', 'o_id' => '', 'msg' => 'shift already released');
        }*/
    }
    echo json_encode($dataArray);
} catch (Exception $e) {
    error_log('ERROR SHIFT RELEASE --> ' . $e->getMessage());
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
    $mail->AddAddress('');
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $body = 'Firebase CRON ' . $note;
    $mail->Body = $body;
    $mail->send();
    if ($mail) {
        echo "MAILSENT";
    } else {
        echo "FAILURE";
    }
}