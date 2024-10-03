<?php
include '_init_.php';

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        try {
            $this->pdo = new PDO(DSN, USER, PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            setError(500, 'Connection Failed | ' . $e->getMessage());
        }
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->pdo;
    }

    public static function createTable(string $name, array $columns): string
    {
        $db = self::getConnection();

        $columnsSql = [];
        foreach ($columns as $column => $attributes) {
            $columnsSql[] = "$column $attributes";
        }
        $columnsSqlString = implode(', ', $columnsSql);
        $sql = "CREATE TABLE IF NOT EXISTS $name ($columnsSqlString) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        try {
            $db->exec($sql);
            return "Create Table << $name >>";
        } catch (PDOException $e) {
            return "Create Table << $name >> Error: " . $e->getMessage();
        }
    }

    public static function insert(string $tableName): void
    {

    }

    public static function select(string $tableName): void
    {

    }

    public static function update(string $tableName): void
    {

    }

    public static function delete(string $tableName): void
    {

    }

    private function __clone()
    {
    }
    private function __wakeup()
    {
    }
}