<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 22/05/2019
 * Time: 11:39 AM
 */
require_once '../firebase/vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class FirebaseShift
{
    protected $database;
    protected $dbtable = 'shift';

    public function __construct()
    {
        try {
            $serviceAccount = ServiceAccount::fromJsonFile( '../firebase/secret/outapay-firebase-service-file.json');
            $firebase = (new Factory)->withServiceAccount($serviceAccount)->create();
            $this->database = $firebase->getDatabase();
            //die(print_r($this->database));
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }
    public function get(int $shiftId){
        if(empty($shiftId) || !isset($shiftId)){
            return FALSE;
        }

        if($this->database->getReference($this->dbtable)->getSnapshot()->hasChild($shiftId)){
            return $this->database->getReference($this->dbtable)->getChild($shiftId)->getValue();
        }else{
            return FALSE;
        }
    }

    public function insert(array $data){
        if(empty($data) || !isset($data)){
            return FALSE;
        }
        /*foreach ($data as $key=>$value){
            $this->database->getReference($this->dbtable)->getChild($key)->set($value);
        }*/
        $this->database->getReference($this->dbtable)->push($data);
        return TRUE;
    }

    public function delete(int $shiftId){
        if(empty($shiftId) || !isset($shiftId)){
            return FALSE;
        }
        if($this->database->getReference($this->dbtable)->getSnapshot()->hasChild($shiftId)){
            $this->database->getReference($this->dbtable)->getChild($shiftId)->remove();
            return TRUE;
        }else{
            return FALSE;
        }
    }
}
/*$shifts = new FirebaseShift();
try{
    $dataArray = array('shiftId'=>4,'shiftDate'=>'2019-8-29','empId'=>'CHAN1033001311');
   var_dump($shifts->insert($dataArray));
}catch (Exception $e){
    echo $e->getMessage();
}
var_dump($shifts->insert([
    '1'=>'2019-05-22',
    '2'=>'2019-05-20',
    '3'=>'2019-05-19'
]));*/

/*var_dump($shifts->get(3));*/

/*var_dump($shifts->delete(1));*/
/*$dataArray = array('shiftId'=>4,'shiftDate'=>'2019-8-29','empId'=>'CHAN1033001311');
var_dump($shifts->insert($dataArray));*/
