<?php

class Db {

    private $host = 'localhost';
    private $dbname = 'login';  // Itt a megfelelő adatbázist add meg
    private $user = 'root';         // Az adatbázis felhasználó
    private $pass = '';             // Az adatbázis jelszó

    private $pdo;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->user, $this->pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);  // Hibaüzenetek kezelése
        } catch (PDOException $e) {
            echo "Nem sikerült kapcsolódni: " . $e->getMessage();
            exit();
        }
    }

    public function getConnection() {
        return $this->pdo;  // Az adatbázis kapcsolatot visszaadja
    }
}
?>
