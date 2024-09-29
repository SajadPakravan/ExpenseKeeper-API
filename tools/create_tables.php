<?php
global $pdo;
include 'db_connect.php';

$messages = [];

try {
    $messages[] = createTableUsers($pdo);
} catch (PDOException $e) {
    $messages[] = 'Create Table (users) Error >>> ' . $e->getMessage();
}

try {
    $messages[] = createTableUsersAuth($pdo);
} catch (PDOException $e) {
    $messages[] = 'Create Table (users_auth) Error >>> ' . $e->getMessage();
}

try {
    $messages[] = createTableUsersToken($pdo);
} catch (PDOException $e) {
    $messages[] = 'Create Table (users_token) Error >>> ' . $e->getMessage();
}

try {
    $messages[] = createTableUsersVerifyCode($pdo);
} catch (PDOException $e) {
    $messages[] = 'Create Table (users_verify_code) Error >>> ' . $e->getMessage();
}

echo json_encode(['messages' => $messages]);

function createTableUsers($pdo): string
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        email VARCHAR(30) UNIQUE NULL,
        phone CHAR(11) UNIQUE NULL,
        avatar VARCHAR(255) NOT NULL,
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
    return 'Created Table (users)';
}

function createTableUsersAuth($pdo): string
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users_auth (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNIQUE NOT NULL,
        username VARCHAR(30) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        login_time DATETIME NOT NULL,
        Logout_time DATETIME NULL,
        status TINYINT(1),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
    return 'Created Table (users_auth)';
}

function createTableUsersToken($pdo): string
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users_token (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNIQUE NOT NULL,
        token VARCHAR(255) UNIQUE NOT NULL,
        expire_at DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
    return 'Created Table (users_token)';
}

function createTableUsersVerifyCode($pdo): string
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS users_verify_code (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(30) UNIQUE NULL,
        phone VARCHAR(11) UNIQUE NULL,
        code CHAR(6) NOT NULL,
        create_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci');
    return 'Created Table (users_verify_code)';
}