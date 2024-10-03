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

    private function __clone()
    {
    }
    private function __wakeup()
    {
    }
}