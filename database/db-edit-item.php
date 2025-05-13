<?php
session_start();

$baseUrl = "http://localhost/api/edit-item";
$params = [
    'user_id' => $_POST['user_id'],
    'item_id' => $_POST['item_id'],
    'category_name' => $_POST['category_name'],
    'title' => $_POST['title'],
    'description' => $_POST['description'],
    'status' => $_POST['status'],
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
    $_SESSION['edit-item'] = false;
    header("Location: /my-account#tab-my-item");
    exit;
} else {
    $_SESSION['message_add_item'] = $responseData['message'];
    $_SESSION['message_type_add_item'] = "error";
    $_SESSION['edit-item'] = true;
    header("Location: /my-account#tab-my-item");
    exit;
}
?>