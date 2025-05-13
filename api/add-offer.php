<?php
require 'db.php';

$user_id = $_GET['user_id'] ?? null;
$item_id= $_GET['item_id'] ?? null;
$offered_item_id = $_GET['offered_item_id'] ?? null;

if (!$user_id || !$item_id || !$offered_item_id) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

$item_owner_query = "SELECT user_id FROM items WHERE item_id = ?";
$stmt_owner = $conn->prepare($item_owner_query);
$stmt_owner->execute([$item_id]);
$item_owner = $stmt_owner->fetchColumn();

if (!$item_owner) {
    echo json_encode(['success' => false, 'message' => 'Geçersiz item_id.']);
    exit;
}


$sql = "INSERT INTO offers (item_id, offered_by, offered_item_id, status) 
        VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);



if ($stmt->execute([$item_id, $user_id, $offered_item_id, "Bekliyor"])) {

    $offer_id = $conn->lastInsertId();

    $message = "Takas teklifi yapıldı.";
    $message_sql = "INSERT INTO messages (offer_id,receiver_id, sender_id, message) VALUES (?,?, ?, ?)";
    $stmt_msg = $conn->prepare($message_sql);
    $stmt_msg->execute([$offer_id,$item_owner, $user_id, $message]);

    echo json_encode(['success' => true, 'message' => 'Takas Oluşturuldu.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Takas Oluşturma Başarısız.']);
}
?>
