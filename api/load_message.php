<?php
require 'db.php';

header('Content-Type: application/json');

$sender = $_GET['sender'];
$receiver = $_GET['receiver'];

$stmt = $conn->prepare("SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at");
$stmt->execute([$sender, $receiver, $receiver, $sender]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
