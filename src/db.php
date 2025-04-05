<?php

class Db {

    private $host = 'localhost';
    private $dbname = 'login';  // Az adatbázis neve
    private $user = 'root';     // Az adatbázis felhasználó
    private $pass = '';         // Az adatbázis jelszó

    private $connection;

    public function __construct() {
        try {
            $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Adatbázis kapcsolat hiba: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

?>