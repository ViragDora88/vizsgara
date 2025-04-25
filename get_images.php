<?php //képek lekérése adatbázisból
session_start();
require_once '../src/db.php';
$db = new Db();
$conn = $db->getConnection();

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(["error" => "Nincs bejelentkezve."]);
    exit;
}

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT id, filename FROM images WHERE user_id = ?");
$stmt->execute([$userId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($images);