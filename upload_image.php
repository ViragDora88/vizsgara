<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
require_once __DIR__ . '/src/db.php';

$db = new Db();
$conn = $db->getConnection();

echo "<pre>";
print_r($_POST);
print_r($_FILES);
echo "</pre>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && isset($_POST['user_id'])) {
    $userId = intval($_POST['user_id']);
    $file = $_FILES['image'];

    $uploadDir = __DIR__ . "/contest/$userId/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $filename = time() . "_" . basename($file['name']);
    $targetPath = $uploadDir . $filename;

    // MIME ellenőrzés
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if ($mime !== 'image/jpeg') {
        die(" Csak JPG képek tölthetők fel.");
    }

    // EXIF ellenőrzés
    $exif = @exif_read_data($file['tmp_name']);
    if (!$exif || !isset($exif['Model'])) {
        die(" A kép nem tartalmaz EXIF adatot.");
    }

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        try {
            $stmt = $conn->prepare("INSERT INTO images (filename, user_id, uploaded_at) VALUES (?, ?, NOW())");
            $stmt->execute([$filename, $userId]);
            echo " Kép sikeresen feltöltve és elmentve az adatbázisba!";
        } catch (PDOException $e) {
            echo " Adatbázis hiba: " . $e->getMessage();
        }
    } else {
        echo " Nem sikerült feltölteni a képet.";
    }
} else {
    echo " Hibás kérés: hiányzik a kép vagy a felhasználó.";
}
if (file_exists($targetPath)) {
    echo "Fájl sikeresen feltöltve: $targetPath";
} else {
    echo "Fájl nem található: $targetPath";
}
echo " Script vége elérve.";

?>