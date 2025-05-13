<?php
$baseUrl = "http://localhost/api/wishlist";
$params = [
    'user_id' => $_GET['user_id'],
    'item_id' => $_GET['item_id']
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    header("Location: /wishlist");
    exit;
} else {
    header("Location: /wishlist");
    exit;
}
?>