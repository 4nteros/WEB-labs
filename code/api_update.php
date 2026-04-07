<?php
session_start();
require_once 'ApiClient.php';

header('Content-Type: application/json');

$api = new ApiClient();
$url = 'https://api.hh.ru/areas';
$cacheFile = 'api_cache.json';

$apiData = $api->request($url);

if (isset($apiData['error'])) {
    echo json_encode(['success' => false, 'error' => $apiData['error']]);
    exit;
}

// Фильтруем данные, как и в process.php
$limitedData = array_map(function($item) {
    return ['id' => $item['id'], 'name' => $item['name']];
}, $apiData);

// Обновляем кеш и сессию
file_put_contents($cacheFile, json_encode($limitedData, JSON_UNESCAPED_UNICODE));
$_SESSION['api_data'] = $limitedData;

echo json_encode(['success' => true, 'data' => $limitedData]);