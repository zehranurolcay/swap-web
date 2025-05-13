<?php
session_start();

$baseUrl = "http://localhost/api/delete-item-img";
$params = [
    'image_id' => $_GET['image_id'],
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    header("Location: /my-account#tab-my-item");
    exit;
} else {
    header("Location: /my-account#tab-my-item");
    exit;
}
?>