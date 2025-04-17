<!doctype html>
<html>
<head>
    <title>Add Customer</title>
</head>
<body>
<?php
require_once "./includes/messagemedia-rest-api-php-sdk-master/autoload.php";

use MessageMedia\RESTAPI\Configuration;
use MessageMedia\RESTAPI\Api\MessagingApi;
use MessageMedia\RESTAPI\Model\NewMessage;
use MessageMedia\RESTAPI\Model\Messages;

Configuration::getDefaultConfiguration()->setUsername('NzvV1XBqx3jzjeDbv7rW');
Configuration::getDefaultConfiguration()->setPassword('qhDqlOxihClD2w7GQXEdTHMnngyX9D');
$countryCode = '+61';
    try {
        $messagingApi = new MessagingApi;
        $messagingApi->sendMessages(new Messages([
            'messages' => [
                new NewMessage([
                    'content' => "Hello Swarne!!!!!!",
                    'destination_number' => $countryCode.'0484012477',
                    'delivery_report' => true
                ])
            ]
        ]));
    } catch (Exception $e) {
        echo "<p>Failed texting the customer.</p>".$e->getMessage();
    }
?>
</body>
</html>