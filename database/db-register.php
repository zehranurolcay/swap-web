<?php
session_start();

$baseUrl = "http://localhost/api/register";
$params = [
    'name' => $_POST['register-name'],
    'email' => $_POST['register-email'],
    'password' => $_POST['register-password']
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    $_SESSION['message'] = "Kayıt başarılı.";
    $_SESSION['message_type'] = "success"; 
    header("Location: /login");
    exit;
} else {
    $_SESSION['message'] = "Kayıt başarısız.";
    $_SESSION['message_type'] = "error";
    header("Location: /login");
    exit;
}
?>
