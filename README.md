# Лабораторная работа №2: Настройка Nginx + PHP-FPM и работа с формами

## Автор
**ФИО:** Богатов Андрей Николаевич  
**Группа:** 2ПМИ-ИП2-ПГ3

## Цель работы
<<<<<<< Updated upstream
Научиться конфигурировать связку Nginx + PHP-FPM в Docker, освоить основы PHP и обработку HTML-форм с помощью JavaScript без перезагрузки страницы.

## Ход работы

### Шаг 1: Добавление PHP-FPM
- В `docker-compose.yml` добавлен сервис `php` на базе образа `php:8.2-fpm`.
- Настроена общая директория для кода `/var/www/html` для обоих контейнеров.
- Сервис `web` (Nginx) теперь зависит от `php`.

### Шаг 2: Настройка конфига Nginx
- Создан файл `nginx/default.conf`.
- Добавлена секция `location ~ \.php$`, которая перенаправляет запросы к PHP-файлам на порт 9000 контейнера `php`.

### Шаг 3: Проверка работы PHP
- Создан файл `index.php` с функцией `phpinfo()`.
- **Результат:** При переходе на `http://localhost:8080/index.php` отображается системная информация PHP.
- **[См. Скриншот]**

### Шаг 4: Создание HTML-формы (Вариант: Регистрация студента)
- В файле `form.html` реализована форма со следующими полями:
  - Имя (text)
  - Возраст (number)
  - Факультет (select)
  - Форма обучения (radio)
  - Согласие с правилами (checkbox)

### Шаг 5: Обработка на JavaScript
- Реализован перехват события `submit` через `addEventListener`.
- Использован объект `FormData` для сбора данных.
- Вывод результата настроен в отдельный блок `div` на странице без её перезагрузки.

## Вывод
В ходе работы была успешно настроена среда разработки с поддержкой PHP. Изучены принципы взаимодействия веб-сервера с интерпретатором через FastCGI и основы клиентской обработки данных форм.
=======
Цель — подключить внешние библиотеки с помощью Composer и интегрировать в свой проект публичный API без регистрации. Освоить Composer и структуру vendor.
Научиться работать с классами и внешними библиотеками. Научиться получать и отображать данные из публичного API. Отработать работу с куками и пользовательской информацией

## Ход работы

### Шаг 0: Подготовка проекта и Composer
Инициализирован проект командой composer init.

Установлена библиотека Guzzle для выполнения HTTP-запросов: composer require guzzlehttp/guzzle.

Сформирована структура проекта с папкой vendor и файлом автозагрузки autoload.php.

### Шаг 1: Создание класса ApiClient
Реализован класс ApiClient в файле ApiClient.php.

Класс использует GuzzleHttp\Client для выполнения GET-запросов.
>>>>>>> Stashed changes

Настроен метод request(), который возвращает декодированный JSON-ответ или массив с ошибкой в случае сбоя.

<<<<<<< Updated upstream
### Файл `docker-compose.yml`
```yaml
version: "3.9"
services:
  web:
    image: nginx:1.27-alpine
    ports:
      - "8080:80"
    volumes:
      - ./code:/var/www/html
      - ./nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - php
  php:
    image: php:8.2-fpm
    volumes:
      - ./code:/var/www/html
    expose:
      - "9000"
=======
### Шаг 2: Интеграция API (HH.ru)
Выбрано API — Список регионов HH.ru (https://api.hh.ru/areas).

После обработки формы в process.php (или save.php) происходит запрос к API.

Полученные данные сохраняются в сессию $_SESSION['api_data'] для последующего вывода.

### Шаг 3: Класс UserInfo и работа с метаданными
Создан файл UserInfo.php со статическим методом getInfo().

Класс собирает системную информацию: IP-адрес пользователя, User-Agent браузера и текущее время сервера.

Данные выводятся на главной странице index.php.

Реализована установка куки last_submission для фиксации времени последней отправки формы.

### Шаг 4: Реализация штрафного задания (Кеширование)
Добавлена логика кеширования ответов API в файл api_cache.json.

Время жизни кеша (TTL) составляет 5 минут (300 секунд).

Приложение сначала проверяет наличие актуального файла кеша и только при его отсутствии или устаревании выполняет новый запрос к серверу API.

### Вывод
В ходе работы изучены механизмы управления зависимостями в PHP. Освоено объектно-ориентированное программирование через создание классов-оберток. Получены навыки работы с внешними REST API и оптимизации нагрузки на сервер с помощью файлового кеширования.

### Исходный код ключевых файлов
Класс ApiClient.php
PHP
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
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
Класс UserInfo.php
PHP
<?php
class UserInfo {
    public static function getInfo(): array {
        return [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
            'time' => date('Y-m-d H:i:s')
        ];
    }
}
>>>>>>> Stashed changes
