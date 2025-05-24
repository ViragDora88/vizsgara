<?php

class Db {

    private $host = 'localhost';
    private $dbname = 'login';  // Az adatbázis neve
    private $user = 'root';     // Az adatbázis felhasználó
    private $pass = '';         // Az adatbázis jelszó

    private $connection;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Hiba esetén leállítja a szkriptet és kiírja a hibaüzenetet
            die("Adatbázis kapcsolat hiba: " . $e->getMessage());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }
}

?>