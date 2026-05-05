<?php
require_once 'vendor/autoload.php';

use App\UserRedis;
use App\UserElastic;
use App\UserClickhouse;

$redis = new UserRedis();
$elastic = new UserElastic();
$clickhouse = new UserClickhouse();


if ($redis->getTotalUsers() == 0) {
    $redis->setUserProfile('2', 'Максим', 22, 2500); 
    $redis->setUserProfile('1', 'Андрей', 20, 1700); 
    $redis->setUserProfile('3', 'Администратор', 25, 1000);
    

    $redis->addReputation('2', 0);
    $redis->addReputation('1', 0);
    $redis->addReputation('3', 0);
}


try {
    $elastic->indexUser('1', ['name' => 'Андрей', 'role' => 'Студент']);
    $elasticResult = $elastic->searchUser('Андрей');
} catch (\Exception $e) {
    $elasticResult = null;
}


try {
    $clickhouse->execute("CREATE TABLE IF NOT EXISTS users (id UInt32, name String, age UInt8) ENGINE = MergeTree() ORDER BY id");
    $clickhouse->insertUser(1, 'Андрей', 20);
    $chCount = $clickhouse->execute("SELECT count() FROM users");
} catch (\Exception $e) {
    $chCount = "Ошибка";
}

$topUsers = $redis->getTopUsers(3);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №6</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 30px; background: #f0f2f5; color: #333; }
        .card { background: white; padding: 25px; margin-bottom: 25px; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header-info { margin-bottom: 30px; border-left: 5px solid #007bff; padding-left: 20px; }
        h1 { margin: 0 0 10px 0; color: #1a1a1a; }
        h2 { color: #007bff; margin-top: 0; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #eef0f2; padding: 12px; text-align: left; }
        th { background: #f8f9fa; font-weight: 600; }
        tr:nth-child(even) { background: #fafafa; }
        .status-ok { color: #28a745; font-weight: bold; }
        .highlight { background: #e7f3ff; font-weight: bold; padding: 2px 5px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="header-info">
        <h1>Контрольная панель</h1>
        <p><strong>Студент:</strong> Богатов Андрей</p>
        <p><strong>Группа:</strong> ИП2</p>
        <p><strong>Вариант 1:</strong> Пользователи</p>
    </div>


    <div class="card">
        <h2>Redis: Лидерборд репутации</h2>
        <table>
            <thead>
                <tr>
                    <th>Место</th>
                    <th>ID</th>
                    <th>Имя пользователя</th>
                    <th>Репутация</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($topUsers as $index => $u): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= htmlspecialchars($u['id']) ?></td>
                    <td class="<?= $u['name'] === 'Максим' ? 'highlight' : '' ?>">
                        <?= htmlspecialchars($u['name']) ?>
                    </td>
                    <td><?= $u['reputation'] ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>


    <div class="card">
        <h2>Elasticsearch: Полнотекстовый поиск</h2>
        <?php if (isset($elasticResult['hits']['hits'][0])): 
            $source = $elasticResult['hits']['hits'][0]['_source']; ?>
            <p>Запрос 'Андрей': <span class="status-ok">Найдено!</span></p>
            <p>Результат: <strong><?= $source['name'] ?></strong> (Тег: <?= $source['role'] ?>)</p>
        <?php else: ?>
            <p>Данные индексируются... Пожалуйста, <a href="">обновите страницу</a>.</p>
        <?php endif; ?>
    </div>


    <div class="card">
        <h2>Clickhouse: Аналитика данных</h2>
        <p>Обработано событий в системе: <span class="highlight"><?= $chCount ?></span></p>
        <p style="font-size: 0.8em; color: #777;">* Количество вставок увеличивается при каждом обновлении страницы.</p>
    </div>

</body>
</html>