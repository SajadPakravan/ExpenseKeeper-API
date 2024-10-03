<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$data = param('data');
$code = param('code');


$query = $pdo->prepare('SELECT * FROM users_verify_code WHERE data = ? AND code = ?');
$query->execute([$data, $code]);

if ($query->fetch()) exit(json_encode(['message' => 'کد صحیح است']));
setError(400, 'Invalid Verify Code');