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
            $this->pdo->exec("SET NAMES 'utf8mb4'");
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

    public static function insert(string $table, array $data): void
    {
        $db = self::getConnection();

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        $values = array_values($data);

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($values);
        } catch (PDOException $e) {
            setError(500, 'Insert Error: ' . $e->getMessage());
        }
    }

    public static function select(string $table, string $columns = '*', string $where = '', array $value = []): array
    {
        $db = self::getConnection();

        $sql = "SELECT $columns FROM $table";

        if (!empty($where) && !empty($value)) $sql .= " WHERE $where";

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(array_values($value));
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            setError(500, 'Select Error: ' . $e->getMessage());
            return [];
        }
    }

    public static function update(string $table, array $set, array $where): void
    {
        $db = self::getConnection();

        $setSql = [];
        foreach ($set as $column => $value) {
            $setSql[] = "$column = ?";
        }

        $whereSql = [];
        foreach ($where as $column => $value) {
            $whereSql[] = "$column = ?";
        }

        $sql = "UPDATE $table SET " . implode(', ', $setSql) . ' WHERE ' . implode(' AND ', $whereSql);

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(array_merge(array_values($set), array_values($where)));
        } catch (PDOException $e) {
            setError(500, 'Update Error: ' . $e->getMessage());
        }
    }

    public static function delete(string $table, array $where): void
    {
        $db = self::getConnection();

        $whereSql = [];
        foreach ($where as $column => $value) {
            $whereSql[] = "$column = ?";
        }

        $sql = "DELETE FROM $table WHERE " . implode(' AND ', $whereSql);

        try {
            $stmt = $db->prepare($sql);
            $stmt->execute(array_values($where));
        } catch (PDOException $e) {
            setError(500, 'Delete Error: ' . $e->getMessage());
        }
    }

    private function __clone()
    {
    }

    public function __wakeup()
    {
    }
}