<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../www/Student.php';

class StudentTest extends TestCase
{
    private $pdoMock;
    private $studentWithMock;

    protected function setUp(): void
    {
        $this->pdoMock = $this->createMock(PDO::class);
        $this->studentWithMock = new Student($this->pdoMock);
    }

    public function testAdd()
    {
        $student = new Student(null);
        $result = $student->add("Ivan");
        $this->assertEquals("Student Ivan added", $result);
    }

    public function testAddWithMock()
    {
        $result = $this->studentWithMock->add("Ivan");
        $this->assertEquals("Student Ivan added", $result);
    }

    public function testAddThrowsExceptionOnEmptyName()
    {
        $student = new Student(null);
        
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Имя не может быть пустым");
        
        $student->add("");
    }

    public function testIntegrationWithRealDatabase()
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $db   = $_ENV['DB_NAME'] ?? 'test_db';
        $user = $_ENV['DB_USER'] ?? 'test_user';
        $pass = $_ENV['DB_PASSWORD'] ?? 'test_pass';

        
        $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);


        $pdo->exec("CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL
        )");

        $pdo->exec("TRUNCATE TABLE students");

        $student = new Student($pdo);

        $this->assertEquals(0, $student->getCount());

        $result = $student->add("Petr");
        $this->assertEquals("Student Petr added", $result);

        $this->assertEquals(1, $student->getCount());
    }
}
