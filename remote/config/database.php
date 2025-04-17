<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 27/07/2018
 * Time: 3:34 PM
 */

class Database{

    private $host = 'localhost';
    private $db_name = 'cl22-cservices';
    private $username = 'root';
    private $password = '2ShwChpnzipe#fqkM7wbV8';
    public $conn;


    public function getConnection(){
        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=".$this->host.";dbname=".$this->db_name,$this->username,$this->password);
            //$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        }catch (PDOException $exception){
            echo "Connection error: ".$exception->getMessage();
        }
        return $this->conn;
    }
}