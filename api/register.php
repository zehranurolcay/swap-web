<?php
require 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = $_GET['name'];
$email = $_GET['email'];
$password = password_hash($_GET['password'], PASSWORD_BCRYPT);

$sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt->execute([$name, $email, $password])) {
    echo json_encode(['success' => true, 'message' => 'Kayıt başarılı.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Kayıt başarısız.']);
}
?>