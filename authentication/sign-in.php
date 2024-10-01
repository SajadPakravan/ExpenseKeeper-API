<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$username = param('username');
$password = param('password');

$checkUsername = $pdo->prepare('SELECT * FROM users_auth WHERE username = ?');
$checkUsername->execute([$username]);
$auth = $checkUsername->fetch();

if ($auth) {
    if (password_verify($password, $auth['password'])) {
        $updateAuth = $pdo->prepare('UPDATE users_auth SET status = 1, login_time = NOW() WHERE username = ?');
        $updateAuth->execute([$username]);
        $token = createToken($auth['user_id']);
        exit(json_encode(['message' => 'ورود شما با موفقیت انجام شد', 'token' => $token]));
    }
}
setError(400,'Invalid Inputs');