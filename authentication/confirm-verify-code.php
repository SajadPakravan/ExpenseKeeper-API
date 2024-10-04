<?php
include '_init_.php';

setMethod('POST');
$data = param('data');
$code = param('code');
$password = param('password');

$checkCode = Database::select(table: 'users_verify_code', where: 'data = ? AND code = ?', value: [$data, $code]);

if (!empty($checkCode)) {
    $user = Database::select(table: 'users', where: 'email = ?', value: [$checkCode['email']]);
    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    Database::update(table: 'users_auth', set: ['password' => $hashPass], where: ['user_id' => $user['id']]);
    exit(json_encode(['message' => 'گذرواژه شما با موفقیت تغییر کرد']));
}
setError(400, 'Invalid Verify Code');