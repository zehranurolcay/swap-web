<?php
require 'db.php';

$user_id = $_GET['user_id'] ?? null;
$item_id= $_GET['item_id'] ?? null;

if (!$user_id || !$item_id) {
    echo json_encode(['success' => false, 'message' => 'İzinsiz Giriş.']);
    exit;
}

header('Content-Type: application/json');

try {
    $sql = "SELECT * FROM offers WHERE offered_by = ? AND item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user_id, $item_id]);
    $offer_checked = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($offer_checked) {
        if($offer_checked["status"] == "Bekliyor")
        {
            echo json_encode(['success' => true]);
        }
        else
        {
            echo json_encode(value: ['success' => false]);

        }
        
    } else {
        echo json_encode(value: ['success' => false]);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Hata: ' . $e->getMessage()]);
    exit();
}
?>
