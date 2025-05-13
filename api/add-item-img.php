<?php
require 'db.php';

$item_id = $_GET['item_id'] ?? null;
$photo = $_GET['photo'] ?? null;

$sql = "INSERT INTO item_images (item_id, image_url) 
        VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if (!$item_id) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

if ($stmt->execute([$item_id, $photo])) {
    echo json_encode(['success' => true, 'message' => 'Fotoğraf Eklendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Fotoğraf Ekleme Başarısız.']);
}
?>
