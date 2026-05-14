<?php

class Student
{
    private ?PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo;
    }

    public function add(string $name): string
    {
        if (empty($name)) {
            throw new InvalidArgumentException("Имя не может быть пустым");
        }

        if ($this->pdo) {
            $stmt = $this->pdo->prepare("INSERT INTO students (name) VALUES (:name)");
            if ($stmt) {
                $stmt->execute(['name' => $name]);
            }
        }

        return "Student " . $name . " added";
    }

    public function getCount(): int
    {
        if (!$this->pdo) {
            return 0;
        }
        $stmt = $this->pdo->query("SELECT COUNT(*) FROM students");
        return (int)$stmt->fetchColumn();
    }
}
