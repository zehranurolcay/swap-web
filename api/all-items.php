<?php
require 'db.php';

$sql = "SELECT * FROM items ORDER BY RAND()";
$stmt = $conn->prepare($sql);
$stmt->execute();
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($items) {
    echo json_encode(['success' => true, 'items' => $items]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veri Yok.']);
}
?>
