<?php
include '_init_.php';

setMethod('POST');
$data = param('data');
$code = param('code');
$password = param('password');

$checkCode = Database::select(table: 'users_verify_code', where: 'data = ? AND code = ?', value: [$data, $code]);

if (!empty($checkCode)) {
    $user = Database::select(table: 'users',where: 'email = ?', value: [$checkCode['email']]);
        db()->prepare('SELECT * FROM users WHERE email = ?');
    $user->execute([$code]);
    $user->fetch();
    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    $updatePass = db()->prepare('UPDATE users_auth SET password = ? WHERE user_id = ?');
    $updatePass->execute([$hashPass, $user['id']]);
    exit(json_encode(['message' => 'گذرواژه شما با موفقیت تغییر کرد']));
}
setError(400, 'Invalid Verify Code');