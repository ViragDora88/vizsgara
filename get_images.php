<?php
session_start();
error_log("SESSION: " . print_r($_SESSION, true));
header('Content-Type: application/json');
require_once __DIR__ . '/src/db.php';

//print_r($_SESSION); // Debug: Ellenőrizzük, hogy a session elérhető-e

if (!isset($_SESSION['user_id'])) {
    echo json_encode([401]); // vagy: ["hiba" => "nincs bejelentkezve"]
    exit;
}

$userId = $_SESSION['user_id'];

$db = new Db();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT id, filename, user_id FROM images WHERE user_id = ?");
$stmt->execute([$userId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

//print_r($images); // Debug: Ellenőrizzük, hogy az adatbázis lekérdezés működik-e
if ($images === false) {
    echo json_encode([500]); // vagy: ["hiba" => "adatbázis hiba"]
    exit;
}
error_log(json_encode($images));
echo json_encode($images);
