
<?php // képek megrendelése
session_start();
require_once '../src/db.php';
$db = new Db();
$conn = $db->getConnection();

$userId = $_SESSION['user_id'];  // Feltételezzük, hogy be van lépve
$imageId = $_POST['image_id'];

$stmt = $conn->prepare("INSERT INTO orders (user_id, image_id) VALUES (?, ?)");
$stmt->execute([$userId, $imageId]);

echo "Rendelés sikeres!";
?>