<?php
include '_init_.php';

setMethod('POST');
$username = param('username');
$name = param('name');
$password = param('password');

$checkUsername = Database::select(table: 'users_auth', where: 'username = ?', value: [$username]);
if (!empty($checkUsername)) setError(400, 'Username Used');

date_default_timezone_set('Asia/Tehran');

Database::insert(table: 'users', data: [
    'name' => $name,
    'email' => null,
    'phone' => null,
    'avatar' => DefaultAvatarUrl,
    'create_at' => date('Y-m-d<>H:i:s'),
]);

$userId = Database::getConnection()->lastInsertId();
$hashPass = password_hash($password, PASSWORD_DEFAULT);
Database::insert(table: 'users_auth', data: [
    'user_id' => $userId,
    'username' => $username,
    'password' => $hashPass,
    'login_time' => date('Y-m-d<>H:i:s'),
]);

$token = createToken($userId);
exit(json_encode(['message' => 'ثبت‌نام و ورود شما با موفقیت انجام شد', 'token' => $token]));