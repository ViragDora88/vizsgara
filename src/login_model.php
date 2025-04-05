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
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Jelszó hash-elése
        $query = "INSERT INTO users (username, password) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$name, $hashedPassword]);
    }

    // Felhasználó hitelesítése
    public function login($username, $password) {
        // Fix admin hitelesítés (Admin/admin87)
        if ($username === 'Admin' && $password === 'admin87') {
            return ['username' => 'Admin'];  // Ha az admin bejelentkezett
        }

        // Normál felhasználó hitelesítése
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Ellenőrizzük a jelszót
        if ($user && password_verify($password, $user['password'])) {
            return $user;  // Ha a jelszó helyes, visszatérünk a felhasználó adataival
        }

        return false;  // Sikertelen hitelesítés
    }
}

?>