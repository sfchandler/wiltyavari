<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 27/07/2018
 * Time: 5:11 PM
 */
class Shift{

    private $conn;
    private $table_name = 'shift';

    //object properties
    public $shiftId;
    public $tandaShiftId;
    public $tandaTimesheetId;
    public $shiftDate;
    public $shiftDay;
    public $clientId;
    public $stateId;
    public $departmentId;
    public $candidateId;
    public $shiftStart;
    public $shiftEnd;
    public $workBreak;
    public $shiftNote;
    public $shiftStatus;
    public $shiftSMSStatus;
    public $consultantId;
    public $positionId;
    public $timeSheetStatus;
    public $addressId;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function read(){
        /*$arr = ['OPEN','CONFIRMED'];
        $in  = str_repeat('?,', count($arr) - 1) . '?';*/
        $shiftStartDate = date('Y-m-d', strtotime($this->shiftDate . ' - 1 day'));
        $openStatus = 'OPEN';
        $confirmedStatus = 'CONFIRMED';
        $query = "SELECT shiftId,
                          tandaShiftId,
                          tandaTimesheetId,
                          shiftDate,
                          shiftDay,
                          clientId,
                          stateId,
                          departmentId,
                          candidateId,
                          shiftStart,
                          shiftEnd,
                          workBreak,
                          shiftNote,
                          shiftStatus,
                          shiftSMSStatus,
                          consultantId,
                          positionId,
                          timeSheetStatus,
                          addressId 
                    FROM ".$this->table_name." 
                    WHERE candidateId = ? 
                    AND shiftDate BETWEEN ? AND ?
                    AND shiftStatus IN(?,?) 
                    ORDER BY shiftId ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1,$this->candidateId);
        $stmt->bindParam(2,$shiftStartDate);
        $stmt->bindParam(3,$this->shiftDate);
        $stmt->bindParam(4,$openStatus);
        $stmt->bindParam(5,$confirmedStatus);
        $stmt->execute();
        return $stmt;
    }
    function getShift(){
        $query = "SELECT shiftId, candidateId, shiftDate, shiftDay, clientId, positionId,departmentId,workBreak FROM ".$this->table_name." WHERE shiftId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->shiftId);
        $stmt->execute();
        return $stmt;
    }
}