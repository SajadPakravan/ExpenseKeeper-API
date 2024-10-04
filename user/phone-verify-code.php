<?php
include '_init_.php';

setMethod('POST');
$id = authorization();
$phone = param('phone');
$code = param('code');

$query = Database::select(table: 'users_verify_code', where: 'data = ? AND code = ?', value: [$phone, $code]);

if (!empty($query)) {
    Database::update(table: 'users', set: ['phone' => $phone], where: ['id' => $id]);
    exit(json_encode(['message' => 'شماره تلفن همراه شما با موفقیت تغییر کرد', 'phone' => $phone]));
}
setError(400, 'Invalid Verify Code');