<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 2/08/2018
 * Time: 5:15 PM
 */

class Timeclock
{
    private $conn;
    private $table_name = 'timeclock';

    public $shiftId;
    public $candidateId;
    public $shiftDate;
    public $shiftDay;
    public $clientId;
    public $positionId;
    public $deptId;
    public $jobCode;
    public $checkIn;
    public $checkOut;
    public $workBreak;
    public $wrkhrs;
    public $supervicerId;
    public $supervisorCheck;
    public $supervisor;
    public $approvalTime;
    public $transport;


    public function __construct($db)
    {
        $this->conn = $db;

    }

    public function saveCheckIn(){
        //return $this->shiftId.$this->candidateId.$this->shiftDate.$this->shiftDay.$this->clientId.$this->positionId.$this->deptId.$this->jobCode.$this->checkIn.$this->checkOut.$this->workBreak.$this->wrkhrs.$this->supervicerId.$this->supervisor;
        //$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $query = "INSERT INTO " . $this->table_name . " SET shiftId=:shiftId, candidateId=:candidateId, shiftDate=:shiftDate, shiftDay=:shiftDay,clientId=:clientId,positionId=:positionId,deptId=:deptId,jobCode=:jobCode,checkIn=:checkIn,checkOut=:checkOut,workBreak=:workBreak,wrkhrs=:wrkhrs,supervicerId=:supervicerId,supervisor=:supervisor,transport=:transport";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":shiftId", $this->shiftId);
            $stmt->bindParam(":candidateId", $this->candidateId);
            $stmt->bindParam(":shiftDate", $this->shiftDate);
            $stmt->bindParam(":shiftDay", $this->shiftDay);
            $stmt->bindParam(":clientId", $this->clientId);
            $stmt->bindParam(":positionId", $this->positionId);
            $stmt->bindParam(":deptId", $this->deptId);
            $stmt->bindParam(":jobCode", $this->jobCode);
            $stmt->bindParam(":checkIn", $this->checkIn);
            $stmt->bindParam(":checkOut", $this->checkOut);
            $stmt->bindParam(":workBreak", $this->workBreak);
            $stmt->bindParam(":wrkhrs", $this->wrkhrs);
            $stmt->bindParam(":supervicerId", $this->supervicerId);
            $stmt->bindParam(":supervisor", $this->supervisor);
            $stmt->bindParam(":transport",$this->transport);
            try{
                $stmt->execute();
                $numRows = $stmt->rowCount();
                if($numRows > 0){
                    return true;
                }else{
                    return false;
                }
            }catch (Exception $e){
                return $stmt->errorInfo();
            }
        }catch (PDOException $e1){
            return 'Error'.$e1->getMessage();
        }
    }
    function getTimeClock(){
        $query = "SELECT shiftId, candidateId, shiftDate, shiftDay, clientId, positionId,deptId,jobCode,checkIn,checkOut,workBreak FROM ".$this->table_name." WHERE shiftId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->shiftId);
        $stmt->execute();
        return $stmt;
    }
    function getCheckInTime(){
        $query = "SELECT checkIn FROM ".$this->table_name." WHERE shiftId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->shiftId);
        $stmt->execute();
        return $stmt;
    }
    function getCheckOutTime(){
        $query = "SELECT checkOut FROM ".$this->table_name." WHERE shiftId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->shiftId);
        $stmt->execute();
        return $stmt;
    }
    public function updateCheckOut(){
        $query = "UPDATE ".$this->table_name." SET checkOut=:checkOut, wrkhrs=:wrkhrs, supervisorCheck=:supervisorCheck WHERE shiftId=:shiftId";
        $numRows;
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":checkOut", $this->checkOut);
            $stmt->bindParam(":wrkhrs", $this->wrkhrs);
            $stmt->bindParam(":supervisorCheck", $this->supervisorCheck);
            $stmt->bindParam(":shiftId", $this->shiftId);
            $stmt->execute();
            $numRows = $stmt->rowCount();
            if($numRows>0){
                return true;
            }else{
                return false;
            }
        }catch (PDOException $e){
            return "Exception: ".$e->getMessage();
        }
    }
}