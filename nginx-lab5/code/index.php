<?php
require 'db.php';
require 'Student.php';

$student = new Student($pdo);
$all = $student->getAll();
$adults = $student->getAdults();
$totalCount = $student->getCount();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная №5</title>
</head>
<body>
    <a href="form.html">Заполнить форму</a>

    <h3>Статистика (Штрафное задание)</h3>
    <p>Всего студентов в базе: <b><?= $totalCount ?></b></p>

    <h2>Все сохранённые данные (сортировка по дате):</h2>
    <ul>
    <?php foreach($all as $row): ?>
        <li>
            <b><?= $row['name'] ?></b> (<?= $row['age'] ?> лет), <?= $row['faculty'] ?>, <?= $row['study_form'] ?>.
            Согласие: <?= $row['agree_rules'] ? 'Да' : 'Нет' ?>.
            <i>Добавлен: <?= $row['created_at'] ?></i>
        </li>
    <?php endforeach; ?>
    </ul>

    <h2>Только совершеннолетние (>18 лет):</h2>
    <ul>
    <?php foreach($adults as $row): ?>
        <li><b><?= $row['name'] ?></b> (<?= $row['age'] ?> лет) - <?= $row['faculty'] ?></li>
    <?php endforeach; ?>
    </ul>
</body>
</html>