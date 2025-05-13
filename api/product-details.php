<?php
require 'db.php';

$item_id = $_GET['item_id'];

$sql = "SELECT * FROM items WHERE item_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$item_id]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($items) {
    echo json_encode(['success' => true, 'items' => $items]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veri Yok.']);
}

?>
