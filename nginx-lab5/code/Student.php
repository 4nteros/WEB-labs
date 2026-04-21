<?php
class Student {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function add($name, $age, $faculty, $agree_rules, $study_form) {
        $stmt = $this->pdo->prepare(
            "INSERT INTO students (name, age, faculty, agree_rules, study_form) VALUES (?, ?, ?, ?, ?)"
        );
        $stmt->execute([$name, $age, $faculty, $agree_rules, $study_form]);
    }

    // Вывод записей, отсортированных по дате (Штрафное задание)
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM students ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Фильтр: студенты старше 18 лет (Штрафное задание)
    public function getAdults() {
        $stmt = $this->pdo->query("SELECT * FROM students WHERE age > 18 ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Подсчёт количества записей (Штрафное задание)
    public function getCount() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as total FROM students");
        return $stmt->fetch()['total'];
    }
}