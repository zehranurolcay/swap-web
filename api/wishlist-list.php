<?php
require 'db.php';

$user_id = $_GET['user_id'];

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Eksik parametreler.']);
    exit;
}

try {
    $sql = "SELECT * FROM wishlist WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id]);
    $wishlistItem = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($wishlistItem) {
        echo json_encode(['success' => true, 'wishlist' => $wishlistItem]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Veri Yok.']);
    }
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
    exit();
}
?>
