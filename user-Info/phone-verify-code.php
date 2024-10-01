<?php
global $pdo;
include '../tools/db_connect.php';

$response = '';

setMethod();

$phone = $_POST['phone'] ?? '';
$code = $_POST['code'] ?? '';

$id = authorization();

nullCheck($phone, 'phone');
nullCheck($code, 'code');

$query = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$phone, $code]);

if ($query->fetch()) {
    $updatePhone = $pdo->prepare('UPDATE users SET phone = ? WHERE id = ?');
    $updatePhone->execute([$phone, $id]);
    exit(json_encode(['message' => 'شماره تلفن همراه با موفقیت تغییر کرد', 'email' => $phone]));
}
http_response_code(400);
exit(json_encode(['error' => 'Verify Code False', 'message' => 'کد تایید نامعتبر است']));