<?php
require 'db.php';

$user_id = $_GET['user_id'] ?? null;
$category_name= $_GET['category_name'] ?? null;
$title = $_GET['title'] ?? null;
$description = $_GET['description'] ?? null;
$status = $_GET['status'] ?? null;
$photo = $_GET['photo'] ?? null;
$location = $_GET['location'] ?? null;

$sql = "INSERT INTO items (user_id, category_name, title, description, status, photo, location) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

if (!$user_id || !$category_name || !$title || !$description || !$status) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

if ($stmt->execute([$user_id, $category_name, $title, $description, $status, $photo, $location])) {
    echo json_encode(['success' => true, 'message' => 'Eşya Eklendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Eşya Ekleme Başarısız.']);
}
?>
