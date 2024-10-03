<?php

include '_init_.php';

setMethod('POST');
$data = param('data');
$code = param('code');
$password = param('password');


$checkCode = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$checkCode->execute([$data, $code]);

if ($checkCode->fetch()) {
    $user = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $user->execute([$code]);
    $user->fetch();

    $hashPass = password_hash($password, PASSWORD_DEFAULT);
    $updatePass = $pdo->prepare('UPDATE users_auth SET password = ? WHERE user_id = ?');
    $updatePass->execute([$hashPass, $user['id']]);
    exit(json_encode(['message' => 'گذرواژه شما با موفقیت تغییر کرد']));
}
setError(400, 'Invalid Verify Code');