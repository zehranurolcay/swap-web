<?php
require 'db.php';

$sql = "SELECT * FROM offers";
$stmt = $conn->prepare($sql);
$stmt->execute();
$offers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($offers) {
    echo json_encode(['success' => true, 'offers' => $offers]);
} else {
    echo json_encode(['success' => false, 'message' => 'Veri Yok.']);
}

?>
