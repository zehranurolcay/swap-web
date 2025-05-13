<?php
require 'db.php';

$user_id = $_GET['user_id'];

$sql = "SELECT * FROM items WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($items) {
    echo json_encode(['success' => true, 'items' => $items]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veri Yok.', 'items' => '']);
}

?>
