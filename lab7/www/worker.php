<?php
require 'vendor/autoload.php';
require 'QueueManager.php';

$q = new QueueManager();
echo "👷 Воркер запущен. Жду задач...\n";

$q->consume(function($data) {
    echo "📥 Получено: " . $data['name'] . " | ";
    
    if (str_contains(strtolower($data['name']), 'error') || rand(1, 10) > 7) {
        throw new Exception("Сбой обработки!");
    }

    echo "✅ Обработано успешно\n";
    sleep(1); 
});