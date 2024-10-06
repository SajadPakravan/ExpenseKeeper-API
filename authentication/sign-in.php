<?php
include '_init_.php';

setMethod('POST');
$username = param('username');
$password = param('password');

$auth = Database::select(table: 'users_auth', where: 'username = ?', value: [$username]);
if (!empty($auth)) {
    if (password_verify($password, $auth[0]['password'])) {
        date_default_timezone_set('Asia/Tehran');
        Database::update(table: 'users_auth', set: ['login_time' => date('Y-m-d H:i:s')], where: ['username' => $username]);
        $token = createToken($auth[0]['user_id']);
        exit(json_encode(['message' => 'ورود شما با موفقیت انجام شد', 'token' => $token]));
    }
}
setError(400, 'Invalid Inputs');