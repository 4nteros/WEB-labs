<?php
session_start();
require_once 'ApiClient.php';

$username = htmlspecialchars($_POST['student_name'] ?? '');
$age = htmlspecialchars($_POST['age'] ?? '');
$faculty = htmlspecialchars($_POST['faculty'] ?? '');
$education_type = htmlspecialchars($_POST['education_type'] ?? '');

$errors = [];

if (empty($username)) {
    $errors[] = "Имя студента не может быть пустым.";
}

if (empty($age) || !is_numeric($age) || $age < 16) {
    $errors[] = "Возраст должен быть числом не меньше 16 лет.";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: index.php");
    exit();
}

$_SESSION['username'] = $username;
$_SESSION['age'] = $age;

setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

$api = new ApiClient();
$url = 'https://api.hh.ru/areas';
$cacheFile = 'api_cache.json';
$cacheTtl = 300; // 5 минут

if (file_exists($cacheFile) && time() - filemtime($cacheFile) < $cacheTtl) {
    $cached = json_decode(file_get_contents($cacheFile), true);
    $_SESSION['api_data'] = $cached;
} else {
    $apiData = $api->request($url);
    
    if (!isset($apiData['error'])) {
        $limitedData = array_map(function($item) {
            return ['id' => $item['id'], 'name' => $item['name']];
        }, $apiData);
        
        file_put_contents($cacheFile, json_encode($limitedData, JSON_UNESCAPED_UNICODE));
        $_SESSION['api_data'] = $limitedData;
    } else {
        $_SESSION['api_data'] = $apiData;
    }
}

// Формат: Имя;Возраст;Факультет;Форма
$line = $username . ";" . $age . ";" . $faculty . ";" . $education_type . "\n";
file_put_contents("data.txt", $line, FILE_APPEND);

header("Location: index.php");
exit();