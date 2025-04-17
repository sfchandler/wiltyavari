<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 20/05/2019
 * Time: 2:23 PM
 */
$payload = array(
          'to'=>'/topics/labourbank',
          'priority'=>'high',
          "mutable_content"=>true,
          "notification"=>array(
                      "title"=>'Firebase Test',
                      "body"=> 'Firebase is working'
          ),
          'data'=>array(
                'action'=>'models',
                'model_id'=>'2701',
              )
        );
    $headers = array('Authorization:key=AAAAQG0v9Gs:APA91bHB0-kI39bmIfPniOiTWI7dyCBgB0MLpU17pxW5wBGR1YVuKdpJejaKnqffHUk-3S6rwrKN7-FvXLlR5uDOGSGlbKg-6aWj6nysHirmRJSSwtxvop-mD5AiHIA5eMU5zlLLMMIc', 'Content-Type: application/json');
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://android.googleapis.com/gcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $payload ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    var_dump($result);exit;
?>