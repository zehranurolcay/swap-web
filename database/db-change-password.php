<?php
session_start();

$baseUrl = "http://localhost/api/change-password";
$params = [
    'user_id' => $_POST['user_id'],
    'current_password' => $_POST['current_password'],
    'new_password' => $_POST['new_password']
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    $_SESSION['message'] = $responseData['message'];
    $_SESSION['message_type'] = "success"; 
    header("Location: /my-account");
    exit;
} else {
    $_SESSION['message'] = $responseData['message'];
    $_SESSION['message_type'] = "error";
    header("Location: /my-account");
    exit;
}
?>
