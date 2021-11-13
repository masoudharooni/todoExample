<?php
class Connection
{
    protected $conn;
    public function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=localhost;dbname=todoExample", "root", "");
        } catch (PDOException $error) {
            die("Connection isn't true , Error is : {$error->getMessage()}");
        }
    }
}
