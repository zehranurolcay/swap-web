<?php
require 'db.php';

header('Content-Type: application/json');

$sender = $_GET['sender'];

$stmt = $conn->prepare("SELECT DISTINCT receiver_id FROM messages WHERE sender_id = ? UNION SELECT DISTINCT sender_id FROM messages WHERE receiver_id = ?");
$stmt->execute([$sender, $sender]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kullanıcı ID'lerini al
$user_ids = array_map(function($user) { return $user['receiver_id']; }, $users);
$user_ids = implode(',', $user_ids);

if ($user_ids) {
    // Veritabanından bu kullanıcıların bilgilerini al
    $stmt = $conn->prepare("SELECT user_id, name FROM users WHERE user_id IN ($user_ids)");
    $stmt->execute();
    $user_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($user_list);
} else {
    echo json_encode([]);  // Hiç mesajlaşma geçmişi yoksa boş döner
}
?>