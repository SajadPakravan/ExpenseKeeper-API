<?php
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
$id = authorization();
$email = param('email');
$code = param('code');

$query = Database::select(table: 'users_verify_code', where: 'data = ? AND code = ?', value: [$email, $code]);

if (!empty($query)) {
    Database::update(table: 'users', set: ['email' => $email], where: ['id' => $id]);
    exit(json_encode(['message' => 'ایمیل شما با موفقیت تغییر کرد', 'email' => $email]));
}
setError(400, 'Invalid Verify Code');