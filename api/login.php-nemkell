<?php
session_start(); // Szükséges a session kezeléshez
header('Content-Type: application/json');
require_once '../config/db.php';

// JSON bemenet olvasása
$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$username || !$password) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Hiányzó felhasználónév vagy jelszó']);
    exit;
}
// Kapcsolódás a "login" adatbázishoz 
$pdo = new PDO("mysql:host=localhost;dbname=login", "root", ""); //  adatbázis hitelesítés
// Felhasználó keresése
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Sikeres bejelentkezés
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user['username'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['id'] = $user['id'];

    echo json_encode([
        'status' => 'success',
        'message' => 'Sikeres bejelentkezés',
        'redirect' => $user['username'] === 'Admin' ? 'admin.php' : 'user.php'
    ]);
} else {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Hibás felhasználónév vagy jelszó']);
}
