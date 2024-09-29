<?php
global $pdo;
include '../tools/db_connect.php';

if (postMethod()) {
    $username = $_POST['username'] ?? '';
    $name = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        http_response_code(400);
        $response = ['status' => 'username Null', 'message' => 'نام کاربری وارد نشده است'];
        exit(json_encode($response));
    }

    if (empty($name)) {
        http_response_code(400);
        $response = ['status' => 'name Null', 'message' => 'نام وارد نشده است'];
        exit(json_encode($response));
    }

    if (empty($password)) {
        http_response_code(400);
        $response = ['status' => 'password Null', 'message' => 'گذرواژه وارد نشده است'];
        exit(json_encode($response));
    }

    $checkUsername = $pdo->prepare('SELECT * FROM users_auth WHERE username = ?');
    $checkUsername->execute([$username]);

    if ($checkUsername->fetch()) {
        $response = ['status' => 'Username Used', 'message' => 'این نام کاربری قبلا استفاده شده'];
        exit(json_encode($response));
    } else {
        $insertUser = $pdo->prepare('INSERT INTO users (name, email, phone, avatar, create_at) VALUES (?, null, null, ?, NOW())');
        if ($insertUser->execute([$name, DefaultAvatarUrl])) {
            $insertAuth = $pdo->prepare('INSERT INTO users_auth (user_id, username, password, login_time, Logout_time, status) VALUES (?, ?, ?, NOW(), null, 1)');

            $userId = $pdo->lastInsertId();
            $hashPass = password_hash($password, PASSWORD_DEFAULT);

            $insertAuth->execute([$userId, $username, $hashPass]);

            createToken($userId);
        }
    }
}