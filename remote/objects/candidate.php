<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 30/07/2018
 * Time: 12:44 PM
 */
class Candidate{

    private $conn;
    private $table_name = 'candidate';

    public $clockPin;
    public $candidateId;
    public $candidate_no;
    public $firstName;
    public $lastName;

    public function __construct($db)
    {
        $this->conn = $db;
    }


    function checkPIN(){
        $query = "SELECT clockPin, candidateId, firstName, lastName FROM ".$this->table_name." WHERE clockPin = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->clockPin);
        $stmt->execute();
        return $stmt;
    }
    function getSupervisorId(){
        $query = "SELECT supervicerId FROM ".$this->table_name." WHERE candidateId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->candidateId);
        $stmt->execute();
        return $stmt;
    }
    function getSupervisor(){
        $query = "SELECT candidateId FROM ".$this->table_name." WHERE candidate_no = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->candidate_no);
        $stmt->execute();
        return $stmt;
    }
}