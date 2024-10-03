<?php
include '_init_.php';

setMethod('POST');
$id = authorization();
$data = param('email');
$code = param('code');

$query = Database::select(table: 'users_verify_code', where: 'data = ? AND code = ?', value: [$data, $code]);

if (!empty($query)) {
    $updateEmail = db()->prepare('UPDATE users SET email = ? WHERE id = ?');
    $updateEmail->execute([$data, $id]);
    exit(json_encode(['message' => 'ایمیل شما با موفقیت تغییر کرد', 'email' => $data]));
}
setError(400, 'Invalid Verify Code');