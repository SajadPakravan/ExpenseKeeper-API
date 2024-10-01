<?php
global $pdo;
include '../tools/db_connect.php';

postMethod();

$username = $_POST['username'] ?? '';
$name = $_POST['name'] ?? '';
$password = $_POST['password'] ?? '';

nullCheck($username, 'username');
nullCheck($name, 'name');
nullCheck($password, 'password');

$checkUsername = $pdo->prepare('SELECT * FROM users_auth WHERE username = ?');
$checkUsername->execute([$username]);

if ($checkUsername->fetch()) {
    exit(json_encode(['error' => 'Username Used', 'message' => 'این نام کاربری قبلا استفاده شده']));
} else {
    $insertUser = $pdo->prepare('INSERT INTO users (name, email, phone, avatar, create_at) VALUES (?, null, null, ?, NOW())');
    if ($insertUser->execute([$name, DefaultAvatarUrl])) {
        $insertAuth = $pdo->prepare('INSERT INTO users_auth (user_id, username, password, login_time, Logout_time, status) VALUES (?, ?, ?, NOW(), null, 1)');
        $userId = $pdo->lastInsertId();
        $hashPass = password_hash($password, PASSWORD_DEFAULT);
        $insertAuth->execute([$userId, $username, $hashPass]);

        $token = createToken($userId);
        if ($token['status']) exit(json_encode(['message' => 'ثبت‌نام و ورود شما با موفقیت انجام شد', 'token' => $token['token']]));
        http_response_code(500);
        exit(json_encode(['error' => 'SignUp True SignIn False', 'message' => 'ثبت‌نام شما انجام شد اما ورود شما با مشکل مواجه شد']));
    }
}