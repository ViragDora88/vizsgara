<?php
require_once(__DIR__ . '/src/db.php'); 

// A lekérdezés logikája 
try {
    $db = new Db();
    $conn = $db->getConnection();

    $query = "
        SELECT o.id, o.user_id, o.image_id, o.ordered_at,
               u.username, i.filename AS image_name
        FROM orders o
        JOIN users u ON o.user_id = u.id
        JOIN images i ON o.image_id = i.id
        ORDER BY o.user_id, o.ordered_at
    ";

    $stmt = $conn->query($query);
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($orders);
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Adatbázis hiba: ' . $e->getMessage()]);
} 
