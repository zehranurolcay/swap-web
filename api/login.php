<?php
require 'db.php';
require '../vendor/autoload.php'; 

use Dotenv\Dotenv;

$data = json_decode(file_get_contents('php://input'), true);

$email = $_GET['email'];
$password = $_GET['password'];

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $jwt = $_ENV['JWT']; 
    echo json_encode(['success' => true, 'user' => $user, 'jwt' => $jwt]);
} else {
    echo json_encode(['success' => false, 'message' => 'Geçersiz email veya şifre.']);
}
?>
