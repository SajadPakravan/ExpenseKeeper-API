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
        $token = createToken($auth['user_id']);
        if ($token['status']) exit(json_encode(['message' => 'ورود شما با موفقیت انجام شد', 'token' => $token['token']]));
        http_response_code(500);
        exit(json_encode(['error' => 'SignIn False', 'message' => 'ورود شما با مشکل مواجه شد']));
    }
}
http_response_code(400);
exit(json_encode(['error' => 'Invalid Inputs', 'message' => 'نام کاربری یا گذرواژه صحیح نیست']));