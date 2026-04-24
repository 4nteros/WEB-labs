# Лабораторная работа №5: MySQL, PHP и Docker

## Автор
**ФИО:** Богатов Андрей Николаевич  
**Группа:** 2ПМИ-ИП2-ПГ3

## Цель работы
Научиться работать с базой данных MySQL через PHP. Создать таблицу для данных формы. Реализовать сохранение и вывод данных из базы на странице. Использовать классы PHP для работы с таблицей. Освоить работу с Docker-контейнерами: nginx, PHP-FPM, MySQL и Adminer.

## Ход работы

### Шаг 1: Подготовка инфраструктуры (Docker)
- Создан `Dockerfile` для PHP-FPM с установкой расширений `pdo` и `pdo_mysql`.
- Настроен `docker-compose.yml`, включающий сервисы:
  - **php**: сборка из локального Dockerfile.
  - **db**: образ `mysql:8.0` с настройкой пользователя и БД.
  - **adminer**: для удобного управления базой (порт 8081).
- Запуск проекта выполнен командой: `docker-compose up -d`.

### Шаг 2: Подключение к БД и создание таблицы
Создан файл `db.php` для инициализации PDO-соединения:
```php
<?php
$host = 'db';
$db   = 'lab5_db';
$user = 'lab5_user';
$pass = 'lab5_pass';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
```
- В базе данных создана таблица `students` с полями: `id`, `name`, `age`, `faculty`, `agree_rules`, `study_form`.

### Шаг 3: Создание класса Student.php
Реализован класс для инкапсуляции логики работы с БД:
```php
<?php
class Student {
    private $pdo;
    public function __construct($pdo) { $this->pdo = $pdo; }

    public function add($name, $age, $faculty, $agree_rules, $study_form) {
        $stmt = $this->pdo->prepare("INSERT INTO students (name, age, faculty, agree_rules, study_form) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $age, $faculty, $agree_rules, $study_form]);
    }

    public function getAll() {
        return $this->pdo->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
    }
}
```

### Шаг 4: Обработка данных и вывод
- В `process.php` данные из формы передаются в метод `$student->add()`.
- В `index.php` реализован цикл `foreach` для вывода всех записей из базы данных.
- Проверка корректности данных в БД проводилась через **Adminer** (http://localhost:8081).

### Шаг 5: Штрафное задание
- В таблицу добавлено поле `created_at` (TIMESTAMP).
- Реализована сортировка вывода записей по дате добавления.
- Добавлен фильтр на странице для отображения только совершеннолетних студентов.
