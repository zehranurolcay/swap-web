<?php
require 'db.php';
require '../vendor/autoload.php'; 

use Dotenv\Dotenv;

$data = json_decode(file_get_contents('php://input'), true);

$user_id = $_GET['user_id'];
$c_password = $_GET['current_password'];
$n_password = $_GET['new_password'];

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($c_password, $user['password'])) {
    $hashed_password = password_hash($n_password, PASSWORD_DEFAULT);

    $update_sql = "UPDATE users SET password = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([$hashed_password, $user_id]);

    echo json_encode(['success' => true, 'message' => 'Şifre başarıyla güncellendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz mevcut şifre.']);
}
?>
