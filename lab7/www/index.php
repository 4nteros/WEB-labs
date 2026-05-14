<?php
function getQueueCount($queueName) {
    $url = "http://guest:guest@rabbitmq:15672/api/queues/%2f/$queueName";
    $res = @file_get_contents($url);
    if (!$res) return 0;
    $data = json_decode($res, true);
    return $data['messages'] ?? 0;
}

$mainCount = getQueueCount('main_tasks');
$errorCount = getQueueCount('error_tasks');
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель управления очередями</title>
    <style>
        .stat-card { border: 1px solid #ccc; padding: 10px; display: inline-block; margin-right: 10px; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Статистика очередей</h2>
    <div class="stat-card">
        <b>Основная очередь:</b> <span><?php echo $mainCount; ?> сообщений</span>
    </div>
    <div class="stat-card">
        <b class="error">Очередь ошибок:</b> <span><?php echo $errorCount; ?> сообщений</span>
    </div>

    <hr>

    <h3>Отправить задачу</h3>
    <form action="send.php" method="POST">
        <input type="text" name="name" placeholder="Имя (напиши 'error' для сбоя)">
        <button type="submit">Отправить в очередь</button>
    </form>

    <p><small>Обнови страницу, чтобы увидеть актуальные цифры.</small></p>
</body>
</html>