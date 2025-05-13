<?php
session_start();

$uploadDir = '../img/';
$photoPath = null;

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photoName = time() . '_' . basename($_FILES['photo']['name']); 
    $photoPath = $uploadDir . $photoName;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        $photoUrl = $photoName; 
    } else {
        $_SESSION['message_add_item'] = "Fotoğraf yüklenemedi.";
        $_SESSION['message_type_add_item'] = "error";
        header("Location: /my-account#tab-add-item");
        exit;
    }
} else {
    $photoUrl = null; 
}

$baseUrl = "http://localhost/api/add-item";
$params = [
    'user_id' => $_POST['user_id'],
    'category_name' => $_POST['category_name'],
    'title' => $_POST['title'],
    'description' => $_POST['description'],
    'status' => $_POST['status'],
    'photo' => $photoUrl, 
    'location' => $_POST['location']
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
    header("Location: /my-account#tab-add-item");
    exit;
} else {
    $_SESSION['message_add_item'] = $responseData['message'];
    $_SESSION['message_type_add_item'] = "error";
    header("Location: /my-account#tab-add-item");
    exit;
}
?>