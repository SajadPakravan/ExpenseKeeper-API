<?php
global $pdo;
include '../tools/db_connect.php';

$response = '';

setMethod('POST');
$id = authorization();
$email = param('email');
$code = param('code');


$query = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$email, $code]);

if ($query->fetch()) {
    $updateEmail = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
    $updateEmail->execute([$email, $id]);
    exit(json_encode(['message' => 'ایمیل شما با موفقیت تغییر کرد', 'email' => $email]));
}
setError(400, 'Invalid Verify Code');