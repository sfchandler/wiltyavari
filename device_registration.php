<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 20/05/2019
 * Time: 2:07 PM
 */
      if(!isset($_GET['device_token'])){
        echo "device token needed!!!";
        exit;
      }
      $server_key = 'AAAATl2-Vzw:APA91bFIi_TgoBmJEVJXrowhvMq0-uRurX977-XZ9Cl3yOsAGweMSpVfUJsSnAq5c5kmbe7V6hUrFkejXyAlbv32F92WGZzJ_u-vyO1Pw4wUm7ww4Izf21eZ4G0qxXpX37O6CaERwQ0T';

      $url = 'https://iid.googleapis.com/iid/v1:batchAdd';
      //$url = "https://iid.googleapis.com/iid/v1:batchRemove";
      $fields['registration_tokens'] = array($_GET['device_token']);
      $fields['to'] = '/topics/my-app';
      $headers = array(
          'Content-Type:application/json',
          'Authorization:key='.$server_key
      );

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
      $result = curl_exec($ch);
      curl_close($ch);
      var_dump($result);exit;
?>