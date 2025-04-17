<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 2/08/2018
 * Time: 5:26 PM
 */

class Jobcode
{
    private $conn;

    private $table_name = 'jobcode';

    public $jobNo;
    public $jobCode;
    public $clientId;
    public $positionId;


    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getJobCode(){
        $query = "SELECT jobCode FROM ".$this->table_name." WHERE clientId = ? AND positionId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->clientId);
        $stmt->bindParam(2,$this->positionId);
        $stmt->execute();
        return $stmt;
    }
}