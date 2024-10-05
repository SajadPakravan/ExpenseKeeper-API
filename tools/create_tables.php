<?php
include '_init_.php';

$messages = [];

$messages[] = Database::createTable('users', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'name' => 'VARCHAR(30) NOT NULL',
    'email' => 'VARCHAR(50) UNIQUE NULL',
    'phone' => 'CHAR(11) UNIQUE NULL',
    'avatar' => 'VARCHAR(255) NOT NULL',
    'create_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
]);

$messages[] = Database::createTable('users_auth', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'user_id' => 'INT UNIQUE NOT NULL',
    'username' => 'VARCHAR(30) UNIQUE NOT NULL',
    'password' => 'VARCHAR(255) NOT NULL',
    'login_time' => 'DATETIME NOT NULL',
    'Logout_time' => 'DATETIME NULL',
    'status' => 'TINYINT(1) NOT NULL',
    'FOREIGN KEY' => '(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
]);

$messages[] = Database::createTable('users_token', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'user_id' => 'INT UNIQUE NOT NULL',
    'token' => 'VARCHAR(255) UNIQUE NOT NULL',
    'expire_at' => 'DATETIME NOT NULL',
    'FOREIGN KEY' => '(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
]);

$messages[] = Database::createTable('users_verify_code', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'data' => 'VARCHAR(30) UNIQUE NOT NULL',
    'code' => 'CHAR(6) NOT NULL',
    'create_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
]);

echo json_encode(['message' => $messages]);