<?php

class login_model {

    private $db;

    public function __construct(Db $db) {
        $this->db = $db->getConnection();  // Az adatbázis kapcsolatot átadjuk
    }

    // Felhasználók lekérése
    public function getUsers() {
        try {
            $query = "SELECT id, nev, email, username, password, image_count, is_locked FROM users";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Adatbázis hiba: " . $e->getMessage());
        }
    }

    // Felhasználó hozzáadása
    public function addUser($username, $password, $email) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Jelszó hash-elése
        $query = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username, $hashedPassword, $email]);
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
    public function deleteUserById($id) {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    public function lockUserById($id) {
        $query = "UPDATE users SET is_locked = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

?>