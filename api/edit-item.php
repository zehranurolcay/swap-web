<?php
require 'db.php';

$item_id = $_GET['item_id'] ?? null;
$user_id = $_GET['user_id'] ?? null;
$category_name = $_GET['category_name'] ?? null;
$title = $_GET['title'] ?? null;
$description = $_GET['description'] ?? null;
$status = $_GET['status'] ?? null;

if (!$item_id || !$user_id || !$category_name || !$title || !$description || !$status) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

$sql = "UPDATE items SET category_name = ?, title = ?, description = ?, status = ? WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt->execute([$category_name, $title, $description, $status, $item_id, $user_id])) {
    echo json_encode(['success' => true, 'message' => 'Eşya Güncellendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Eşya Güncelleme Başarısız.']);
}
?>
