<?php
include '_init_.php';

setMethod('POST');
$data = param('data');
$code = param('code');
$password = param('password');

$checkCode = Database::select(table: 'users_verify_code', where: 'data = ? AND code = ?', value: [$data, $code]);

if (!empty($checkCode)) {
    if (checkEmail($data)) selectUser('email');
    if (checkPhone($data)) selectUser('phone');
}
setError(400, 'Invalid Verify Code');

function selectUser(string $data): void
{
    global $checkCode, $password;
    $user = Database::select(table: 'users', where: "$data = ?", value: [$checkCode[0]['data']]);
    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    Database::update(table: 'users_auth', set: ['password' => $hashPass], where: ['user_id' => $user[0]['id']]);
    exit(json_encode(['message' => 'گذرواژه شما با موفقیت تغییر کرد']));
}