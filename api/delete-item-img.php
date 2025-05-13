<?php
require 'db.php';

$image_id = $_GET['image_id'] ?? null;

if (!$image_id) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

$sql = "DELETE FROM item_images WHERE image_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt->execute([$image_id])) {
    echo json_encode(['success' => true, 'message' => 'Eşya Silindi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Eşya Silme Başarısız.']);
}
?>
