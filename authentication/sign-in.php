<?php
global $pdo;
include '../tools/db_connect.php';

postMethod();

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

nullCheck($username, 'username');
nullCheck($password, 'password');

$checkUsername = $pdo->prepare('SELECT * FROM users_auth WHERE username = ?');
$checkUsername->execute([$username]);
$auth = $checkUsername->fetch();

if ($auth) {
    if (password_verify($password, $auth['password'])) {
        $updateAuth = $pdo->prepare('UPDATE users_auth SET status = 1, login_time = NOW() WHERE username = ?');
        $updateAuth->execute([$username]);
        createToken($auth['user_id']);
    } else {
        http_response_code(400);
        $response = ['error' => 'Invalid Inputs', 'message' => 'نام کاربری یا گذرواژه صحیح نیست'];
        exit(json_encode($response));
    }
} else {
    http_response_code(400);
    $response = ['error' => 'Invalid Inputs', 'message' => 'نام کاربری یا گذرواژه صحیح نیست'];
    exit(json_encode($response));
}