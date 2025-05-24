<?php
session_start();
require_once __DIR__ . '/src/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_images'])) {
    $userId = $_SESSION['user_id'];
    $imageIds = $_POST['selected_images'];

    // Példa feldolgozás
    $db = new Db();
    $conn = $db->getConnection();

    $stmt = $conn->prepare("INSERT INTO orders (user_id, image_id, ordered_at) VALUES (?, ?, NOW())");

    foreach ($imageIds as $imageId) {
        $stmt->execute([$userId, $imageId]);
    }

    echo "Megrendelés sikeresen rögzítve!";
} else {
    echo "Nincs kiválasztva kép.";
}
