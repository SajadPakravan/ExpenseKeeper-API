<?php
include '_init_.php';

setMethod('POST');
$username = param('username');
$password = param('password');

$auth = Database::select(table: 'users_auth', where: 'username = ?', value: [$username]);
if (!empty($auth)) {
    if (password_verify($password, $auth['password'])) {
        $updateAuth = Database::update(table: 'users_auth', set: ['status' => 1, 'login_time' => date('Y-m-d H:i:s')], where: ['username' => $username]);
        $token = createToken($auth['user_id']);
        exit(json_encode(['message' => 'ورود شما با موفقیت انجام شد', 'token' => $token]));
    }
}
setError(400, 'Invalid Inputs');