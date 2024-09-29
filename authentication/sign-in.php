<?php
global $pdo;
include '../tools/db_connect.php';

if (postMethod()) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        http_response_code(400);
        $response = ['status' => 'username Null', 'message' => 'نام کاربری وارد نشده است'];
        exit(json_encode($response));
    }

    if (empty($password)) {
        http_response_code(400);
        $response = ['status' => 'password Null', 'message' => 'گذرواژه وارد نشده است'];
        exit(json_encode($response));
    }

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
            $response = ['status' => 'Invalid Inputs', 'message' => 'نام کاربری یا گذرواژه صحیح نیست'];
            exit(json_encode($response));
        }
    } else {
        http_response_code(400);
        $response = ['status' => 'Invalid Inputs', 'message' => 'نام کاربری یا گذرواژه صحیح نیست'];
        exit(json_encode($response));
    }
}