<?php
session_start();

$uploadDir = '../img/product_img/';
$photoPath = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photoName = time() . '_' . basename($_FILES['photo']['name']); 
    $photoPath = $uploadDir . $photoName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        $photoUrl = $photoName; 
    } else {
        $_SESSION['message_add_item_img'] = "Fotoğraf yüklenemedi.";
        $_SESSION['message_type_add_item_img'] = "error";
        header("Location: /my-account#tab-my-item");
        exit;
    }
} else {
    $photoUrl = null; 
}

$baseUrl = "http://localhost/api/add-item-img";
$params = [
    'item_id' => $_POST['item_id'],
    'photo' => $photoUrl, 
];

$url = $baseUrl . '?' . http_build_query($params);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 

if ($responseData['success'] === true) {
    $_SESSION['message_add_item_img'] = $responseData['message'];
    $_SESSION['message_type_add_item_img'] = "success"; 
    header("Location: /my-account#tab-my-item");
    exit;
} else {
    $_SESSION['message_add_item_img'] = $responseData['message'];
    $_SESSION['message_type_add_item_img'] = "error";
    header("Location: /my-account#tab-my-item");
    exit;
}
?>