<?php
global $pdo;
include '../tools/db_connect.php';

$response = '';

setMethod('POST');
$id = authorization();
$data = param('email');
$code = param('code');


$query = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$data, $code]);

if ($query->fetch()) {
    $updateEmail = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
    $updateEmail->execute([$data, $id]);
    exit(json_encode(['message' => 'ایمیل شما با موفقیت تغییر کرد', 'email' => $data]));
}
setError(400, 'Invalid Verify Code');