<?php

namespace App\Http\Controllers;
require '../mobileAPI/vendor/autoload.php';
use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\Message;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseController extends Controller
{
    public function index(){
        $serviceAccount = ServiceAccount::fromJsonFile('../mobileAPI/labourbank-firebase-adminsdk-yxgw5-a9e11375a3.json');
        //$serviceAccount = ServiceAccount::discover();
        //$serviceAccount = ServiceAccount::withProjectIdAndServiceAccountId('labourbank','firebase-adminsdk-xcjgc@labourbank.iam.gserviceaccount.com');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://labourbank.firebaseio.com')
            ->create();

        $database = $firebase->getDatabase();

        $newPost = $database->getReference('mobileApp/messages')->push(['candidateId' => 'CHAN0000011473','UID' => 'ttvhskanskaaee234sjs']);
        $newPost->getKey(); // => -KVr5eu8gcTv7_AHb-3-
        $newPost->getUri(); // => https://my-project.firebaseio.com/blog/posts/-KVr5eu8gcTv7_AHb-3-
        $newPost->getChild('title')->set('Firebase Messages');
        $newPost->getValue(); // Fetches the data from the realtime database
        //$newPost->remove();
        return var_dump($newPost->getValue());
    }
    public function displayData(){
        $serviceAccount = ServiceAccount::fromJsonFile('../mobileAPI/labourbank-firebase-adminsdk-yxgw5-a9e11375a3.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->withDatabaseUri('https://labourbank.firebaseio.com')
            ->create();
        $database = $firebase->getDatabase();
        $reference = $database->getReference('/mobileApp');
        //$snapshot = $reference->getSnapshot();
        $value = $reference->getValue();
        return $value;
    }

    public function cloudMessaging(){
        $serviceAccount = ServiceAccount::fromJsonFile('../mobileAPI/secret/labourbank-b79c3280d986.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $database = $firebase->getDatabase();
        //die(print_r($database));
    }
}
