<?php
global $db;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$data = param('email');
$code = param('code');


$query = $db->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$data, $code]);

if ($query->fetch()) {
    $updateEmail = $db->prepare('UPDATE users SET email = ? WHERE id = ?');
    $updateEmail->execute([$data, $id]);
    exit(json_encode(['message' => 'ایمیل شما با موفقیت تغییر کرد', 'email' => $data]));
}
setError(400, 'Invalid Verify Code');