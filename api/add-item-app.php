<?php
require 'db.php';  // db.php dosyasını dahil ediyorsunuz, burada veritabanı bağlantısı yapılacak.

$user_id = $_POST['user_id'] ?? null;
$category_name = $_POST['category_name'] ?? null;
$title = $_POST['title'] ?? null;
$description = $_POST['description'] ?? null;
$status = $_POST['status'] ?? null;
$location = $_POST['location'] ?? null;

// Fotoğraf dosyasının var olup olmadığını kontrol et
$photo = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../img/';
    $photoName = time() . '_' . basename($_FILES['photo']['name']);
    $photoPath = $uploadDir . $photoName;

    // Dosya taşınabiliyorsa, fotoğrafı yükle
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        $photo = $photoName; // Yüklenen dosyanın adını veritabanında saklayacağız
    } else {
        echo json_encode(['success' => false, 'message' => 'Fotoğraf yüklenemedi.']);
        exit;
    }
}

// Parametreleri kontrol et
if (!$user_id || !$category_name || !$title || !$description || !$status || !$location) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

// Veritabanına veri ekle
$sql = "INSERT INTO items (user_id, category_name, title, description, status, photo, location) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

try {
    if ($stmt->execute([$user_id, $category_name, $title, $description, $status, $photo, $location])) {
        echo json_encode(['success' => true, 'message' => 'Eşya başarıyla eklendi.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Eşya ekleme başarısız.']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Veritabanı hatası: ' . $e->getMessage()]);
}
?>

