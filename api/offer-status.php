<?php
require 'db.php';

$offer_id = $_GET['offer_id'] ?? null;
$status = $_GET['status'] ?? null;

header('Content-Type: application/json');

if (!$offer_id || !$status) {
    echo json_encode(['success' => false, 'message' => 'Eksik bilgiler mevcut.']);
    exit;
}

// Teklifin mevcut item_id ve offered_item_id değerlerini al
$sql = "SELECT item_id, offered_item_id FROM offers WHERE offer_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$offer_id]);
$offer = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$offer) {
    echo json_encode(['success' => false, 'message' => 'Teklif bulunamadı.']);
    exit;
}

// Öncelikle, teklifin durumunu güncelle
$sql = "UPDATE offers SET status = ? WHERE offer_id = ?";
$stmt = $conn->prepare($sql);
$updateSuccess = $stmt->execute([$status, $offer_id]);

if ($updateSuccess) {
    // Eğer teklif "Kabul Edildi" ise, aynı item_id veya offered_item_id içeren ve "Bekliyor" durumda olan teklifleri "Reddedildi" yap
    if ($status === "Kabul Edildi") {
        $sql = "UPDATE offers SET status = 'Reddedildi' 
                WHERE status = 'Bekliyor' 
                AND (item_id = ? OR item_id = ? OR offered_item_id = ? OR offered_item_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            $offer['item_id'], 
            $offer['offered_item_id'], 
            $offer['item_id'], 
            $offer['offered_item_id']
        ]);
    }

    echo json_encode(['success' => true, 'message' => 'Takas Durumu Güncellendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Takas Durumu Güncelleme Başarısız.']);
}
?>
