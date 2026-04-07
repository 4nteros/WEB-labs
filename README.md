# Лабораторная работа №4: Composer, классы и работа с публичным API

## Автор
**ФИО:** Богатов Андрей Николаевич  
**Группа:** 2ПМИ-ИП2-ПГ3

## Цель работы
Подключить внешние библиотеки с помощью Composer и интегрировать в свой проект публичный API без регистрации. Освоить Composer и структуру vendor. Научиться работать с классами и внешними библиотеками. Научиться получать и отображать данные из публичного API. Отработать работу с куками и пользовательской информацией.

## Ход работы

### Шаг 0: Подготовка проекта
- В корне проекта выполнены команды:
  - `composer init`
  - `composer require guzzlehttp/guzzle`
- Проверено наличие папки `vendor` и файла `composer.json`.

### Шаг 1: Создание класса ApiClient.php
Реализован класс для работы с HTTP-запросами через Guzzle:
```php
<?php
require_once __DIR__ . '/vendor/autoload.php';
use GuzzleHttp\Client;

class ApiClient {
    private Client $client;

    public function __construct() {
        $this->client = new Client();
    }

    public function request(string $url): array {
        try {
            $response = $this->client->get($url);
            $body = $response->getBody()->getContents();
            return json_decode($body, true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
```
### Шаг 2: Интеграция API
- Выбрано публичное API — HH.ru (Список регионов): https://api.hh.ru/areas.
- После обработки формы происходит вызов: $apiData = $api->request($url);
- Полученные данные сохраняются в сессию: $_SESSION['api_data'] = $apiData;
- В index.php реализован вывод данных через print_r.
Шаг 3: Класс UserInfo и куки
Создан файл UserInfo.php для сбора метаданных:

```PHP
<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
            'time' => date('Y-m-d H:i:s')
        ];
    }
}
```
- Информация выводится на главной странице.
- После отправки формы устанавливается cookie: setcookie("last_submission", date('Y-m-d H:i:s'), time() + 3600, "/");

### Шаг 4: Штрафное задание (Кеширование)
- Реализовано кеширование ответов API в файл api_cache.json с TTL 5 минут (300 секунд). Если файл существует и он свежий, данные берутся из него, иначе выполняется новый запрос к API.