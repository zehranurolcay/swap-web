<?php
require 'db.php';

$sql = "SELECT * FROM item_images";
$stmt = $conn->prepare($sql);
$stmt->execute();
$item_images = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($item_images) {
    echo json_encode(['success' => true, 'item_images' => $item_images]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veri Yok.']);
}

?>
