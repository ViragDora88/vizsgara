<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/src/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$userId = $_SESSION['user_id'];

$db = new Db();
$conn = $db->getConnection();

$stmt = $conn->prepare("SELECT id, filename, user_id FROM images WHERE user_id = ?");
$stmt->execute([$userId]);
$images = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($images);
