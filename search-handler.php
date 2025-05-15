<?php
$searchQuery = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';

$baseUrl = "http://localhost/api/all-items";
$ch = curl_init($baseUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$responseData = json_decode($response, true); 
$items = $responseData['items'];

$filteredItems = [];

if ($searchQuery !== '') {
    foreach ($items as $item) {
        if (
            strpos(strtolower($item['title']), $searchQuery) !== false ||
            strpos(strtolower($item['description']), $searchQuery) !== false ||
            strpos(strtolower($item['category_name']), $searchQuery) !== false ||
            strpos(strtolower($item['location']), $searchQuery) !== false
        ) {
            $filteredItems[] = $item;
        }
    }
}

if (empty($filteredItems)) {
    echo "<p style='padding: 10px;'>Sonuç bulunamadı.</p>";
} else {
    foreach ($filteredItems as $item) {
        echo "
        <a href='/product-details?pid={$item['item_id']}' style='display: flex; align-items: center; gap: 10px; padding: 10px; border-bottom: 1px solid #ccc; text-decoration: none; color: black;'>
           <img src='/img/{$item['photo']}' alt='' style='flex-shrink: 0; width: 60px; height: 60px; object-fit: contain; border-radius: 5px; background-color: #f0f0f0;'>



            <div style='flex: 1; font-size: 16px; font-weight: 500; overflow: hidden; white-space: nowrap; text-overflow: ellipsis;'>
                ".htmlspecialchars($item["title"])."
            </div>
        </a>
        ";
    }
}
?>
