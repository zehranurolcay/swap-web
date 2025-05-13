<?php
require 'db.php';

$user_id = $_GET['user_id'];
$item_id = $_GET['item_id'];

if (!$user_id || !$item_id) {
    echo json_encode(['success' => false, 'message' => 'Favorilere eklemek için giriş yapmanız gerekiyor.']);
    exit;
}

header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM wishlist WHERE user_id = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $item_id]);
    $wishlistItem = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($wishlistItem) {
        echo json_encode(['success' => true, 'action' => 'added']);
    } else {
        echo json_encode(['success' => true, 'action' => 'removed']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
    exit();
}
?>
