<?php
require 'db.php';
require 'Student.php';

$student = new Student($pdo);

$name = htmlspecialchars($_POST['name'] ?? '');
$age = intval($_POST['age'] ?? 0);
$faculty = htmlspecialchars($_POST['faculty'] ?? '');
$agree = isset($_POST['agree']) ? 1 : 0;
$form = htmlspecialchars($_POST['study_form'] ?? '');

if ($name && $age) {
    $student->add($name, $age, $faculty, $agree, $form);
}

header("Location: index.php");
exit();