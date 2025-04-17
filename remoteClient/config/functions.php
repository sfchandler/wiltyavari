<?php
define('DEFAULT_EMAIL',openssl_decrypt('cB3jX2gukzdvsnopSdjeDNK3VNJIGKlijiN0Dg==',"AES-128-CTR","Lf#6291YsndKaen^mdsHdfJ"));
define('DEFAULT_EMAIL_PASSWORD',openssl_decrypt('fwT5Xmssjilxu0w8RsLLGg==',"AES-128-CTR","Lf#6291YsndKaen^mdsHdfJ"));
function calculateHoursWorked($shiftDate, $shiftStart, $shiftEnd, $workBreak)
{
    if ($shiftEnd < $shiftStart) {
        $shiftEndDate = date('Y-m-d', strtotime($shiftDate . ' + 1 day'));
    } else {
        $shiftEndDate = $shiftDate;
    }

    $starttime = strtotime($shiftDate . ' ' . $shiftStart . ':00');
    $endtime = strtotime($shiftEndDate . ' ' . $shiftEnd . ':00');
    $diff = $endtime - $starttime;
    $breaks = $workBreak * 60;
    $hours = ($diff - $breaks) / 60 / 60;

    $info = $info . 'clocked in: ' . $shiftDate . ' ' . $shiftStart . ':00' . '>>';
    $info = $info . 'clocked out: ' . $shiftEndDate . ' ' . $shiftEnd . ':00' . '>>';
    $info = $info . 'breaks: ' . $breaks . ' minutes >>';
    $info = $info . 'hours worked: ' . number_format($hours, 2) . ' ';
    return number_format($hours, 2);
}
function genRemoteMailNotification($subject,$recipientEmail,$mailBody){
    $mail = new PHPMailer();
    $mail->CharSet =  "utf-8";
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
    $mail->setFrom(DEFAULT_EMAIL, 'Test notification');
    $mail->AddAddress($recipientEmail);
    $mail->AddBCC('swarnajithf@chandlerservices.com.au');
    $mail->Subject = $subject;
    $mail->IsHTML(true);
    $body = $mailBody.'<br><br><br/><br/>';
    $mail->Body = $body;
    $mail->send();
    /*** send email end ***/
    if($mail){
        return "SUCCESS";
    }else{
        return "FAILURE";
    }
}