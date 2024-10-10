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
    'login_time' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
    'FOREIGN KEY' => '(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
]);

$messages[] = Database::createTable('users_token', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'user_id' => 'INT UNIQUE NOT NULL',
    'token' => 'VARCHAR(255) UNIQUE NOT NULL',
    'create_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
    'FOREIGN KEY' => '(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
]);

$messages[] = Database::createTable('users_verify_code', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'data' => 'VARCHAR(30) UNIQUE NOT NULL',
    'code' => 'CHAR(6) NOT NULL',
    'create_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
]);

$messages[] = Database::createTable('incomes_costs', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'user_id' => 'INT UNIQUE NOT NULL',
    'title' => 'VARCHAR(30) NOT NULL',
    'description' => 'VARCHAR(50) NOT NULL',
    'amount' => 'DECIMAL(12,1) NOT NULL',
    'type' => "ENUM('INCOME', 'COST') DEFAULT 'COST'",
    'create_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
    'FOREIGN KEY' => '(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
]);

$messages[] = Database::createTable('bank_sms', [
    'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
    'user_id' => 'INT UNIQUE NOT NULL',
    'title' => 'VARCHAR(30) NOT NULL',
    'description' => 'VARCHAR(50) NOT NULL',
    'amount' => 'DECIMAL(12,1) NOT NULL',
    'balance' => 'DECIMAL(12,1) NOT NULL',
    'type' => "ENUM('INCOME', 'COST') DEFAULT 'COST'",
    'create_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
    'FOREIGN KEY' => '(user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE',
]);

echo json_encode(['message' => $messages]);