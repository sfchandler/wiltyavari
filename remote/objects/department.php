<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 9/08/2018
 * Time: 10:55 AM
 */

class Department
{
    private $conn;
    private $table_name = 'department';

    public $deptId;
    public $clientId;
    public $stateId;
    public $department;

    public function __construct($db)
    {
        $this->conn = $db;

    }

    public function getDepartmentList(){
        $query = "SELECT deptId, department FROM ".$this->table_name." WHERE clientId = ? AND stateId = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->clientId);
        $stmt->bindParam(2,$this->stateId);
        $stmt->execute();
        $dept_arr = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $dept_item = array("deptId"=>$row['deptId'],"department"=>$row['department']);
            array_push($dept_arr,$dept_item);
        }
        return $dept_arr;
    }
}