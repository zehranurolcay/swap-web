<?php
session_start();

$baseUrl = "http://localhost/api/login";
$params = [
    'email' => $_POST['singin-email'],
    'password' => $_POST['singin-password']
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    $_SESSION['user'] = $responseData['user'];
    $_SESSION['jwt'] = $responseData['jwt']; 
    header("Location: /");
    exit;
} else {
    $_SESSION['message'] = "Giriş Başarısız.";
    $_SESSION['message_type'] = "error";
    header("Location: /login");
    echo "login başarısız";
    exit;
}
?>
