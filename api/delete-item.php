<?php
require 'db.php';

$item_id = $_GET['item_id'] ?? null;
$user_id = $_GET['user_id'] ?? null;

if (!$item_id || !$user_id) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

$sql = "DELETE FROM items WHERE item_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

$sql1 = "DELETE FROM item_images WHERE item_id = ?";
$stmt1 = $conn->prepare($sql1);

if ($stmt->execute([$item_id, $user_id])) {
    $stmt1->execute([$item_id]);
    echo json_encode(['success' => true, 'message' => 'Silindi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Silme Başarısız.']);
}
?>
