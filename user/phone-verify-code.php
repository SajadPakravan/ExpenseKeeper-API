<?php
global $db;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$phone = param('phone');
$code = param('code');

$query = $db->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$phone, $code]);

if ($query->fetch()) {
    $updatePhone = $db->prepare('UPDATE users SET phone = ? WHERE id = ?');
    $updatePhone->execute([$phone, $id]);
    exit(json_encode(['message' => 'شماره تلفن همراه شما با موفقیت تغییر کرد', 'email' => $phone]));
}
setError(400, 'Invalid Verify Code');