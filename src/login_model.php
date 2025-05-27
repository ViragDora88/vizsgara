<?php

class login_model
{

    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db->getConnection();  // Az adatbázis kapcsolatot átadjuk
    }

    // Felhasználók lekérése
    public function getUsers()
    {
        try {
            $query = "SELECT id, nev, email, username, password, is_locked FROM users";
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Adatbázis hiba: " . $e->getMessage());
        }
    }

    // Felhasználó hozzáadása
    public function addUser($nev, $username, $password, $email)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Jelszó hash-elése
        $query = "INSERT INTO users (nev, email, username, password) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$nev, $email, $username, $hashedPassword]);
    }

    // Felhasználó hitelesítése
    public function login($username, $password)
{
    error_log("login_model: login hívás - felhasználó: $username");

    // Fix admin hitelesítés (Admin/admin87)
    if ($username === 'Admin' && $password === 'admin87') {
        error_log("login_model: Admin belépés sikeres");
        return ['id' => 0, 'username' => 'Admin'];  // Ha az admin bejelentkezett
    }

    // Normál felhasználó hitelesítése
    $query = "SELECT * FROM users WHERE username = :username";
    $stmt = $this->db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    error_log("login_model: adatbázisból visszakapott user: " . print_r($user, true));

    // Ellenőrizzük a felhasználót (és a jelszavát)
    if ($user && password_verify($password, $user['password'])) {
        if ($user['is_locked'] == 1) {
            error_log("login_model: felhasználó le van tiltva: $username");
            return ['locked' => true];
        }
        error_log("login_model: sikeres bejelentkezés: $username");
        return [
            'id' => $user['Id'],  // hozzáadva az ID
            'username' => $user['username']
        ];  // Sikeres hitelesítés
    }

    error_log("login_model: sikertelen bejelentkezés: $username");
    return false;  // Sikertelen hitelesítés
}
    public function deleteUserById($id)
    {
        $query = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function lockUserById($id)
    {
        $query = "UPDATE users SET is_locked = 1 WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function userExists($username)
    {
        $query = "SELECT id FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetch() !== false;
    }
    public function getOrders()
    {
        try {
            // ÖSSZEKAPCSOLJUK A felhasználó és kép adatait is, hogy ne csak az ID-k jelenjenek meg.
            $query = "SELECT o.id, o.user_id, o.image_id,  u.username, i.image_name
                    FROM orders o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN images i ON o.image_id = i.id
                    ;

            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Adatbázis hiba: " . $e->getMessage());
        }
    }
}
