<?php
global $pdo;
include '../tools/db_connect.php';

$response = '';

setMethod();

$email = $_POST['email'] ?? '';
$code = $_POST['code'] ?? '';

$id = authorization();

nullCheck($email, 'email');
nullCheck($code, 'code');

$query = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$email, $code]);

if ($query->fetch()) {
    $updateEmail = $pdo->prepare('UPDATE users SET email = ? WHERE id = ?');
    $updateEmail->execute([$email, $id]);
    exit(json_encode(['message' => 'ایمیل با موفقیت تغییر کرد', 'email' => $email]));
}
http_response_code(400);
exit(json_encode(['error' => 'Verify Code False', 'message' => 'کد تایید نامعتبر است']));