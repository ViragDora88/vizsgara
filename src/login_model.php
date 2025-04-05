<?php

class login_model {

    private $db;

    public function __construct(Db $db) {
        $this->db = $db->getConnection();  // Az adatbázis kapcsolatot átadjuk
    }

    // Felhasználók lekérése
    public function getUsers() {
        $query = "SELECT * FROM users";  // Az SQL lekérdezés
        $stmt = $this->db->query($query);  // Lekérdezés végrehajtása
        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Az eredmény visszaadása tömb formájában
    }

    // Felhasználó hozzáadása
    public function addUser($name, $password) {
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name, $password]);
    }
    
    public function login($username, $password) {
    // Fix admin hitelesítés (Admin/admin87)
    if ($username === 'Admin' && $password === 'admin87') {
        return ['username' => 'Admin'];  // Ha az admin bejelentkezett
    }else{
    
    
    $query = "SELECT * FROM users WHERE username = :username AND password = :password";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $user;  // Ha van találat, visszatér az adatokkal
    }
    }
}
?>

