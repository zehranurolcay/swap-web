<?php
session_start();

$baseUrl = "http://localhost/api/delete-item";
$params = [
    'user_id' => $_GET['user_id'],
    'item_id' => $_GET['item_id'],
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    $_SESSION['message_add_item'] = $responseData['message'];
    $_SESSION['message_type_add_item'] = "success"; 
    header("Location: /my-account#tab-my-item");
    exit;
} else {
    $_SESSION['message_add_item'] = $responseData['message'];
    $_SESSION['message_type_add_item'] = "error";
    header("Location: /my-account#tab-my-item");
    exit;
}
?>