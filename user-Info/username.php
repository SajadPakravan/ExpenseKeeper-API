<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$username = param('username');

$checkUsername = $pdo->prepare('SELECT * FROM users_auth WHERE username = ?');
$checkUsername->execute([$username]);

if ($checkUsername->fetch()) setError(400, 'Username Used');

$updateUsername = $pdo->prepare('UPDATE users_auth SET username = ? WHERE user_id = ?');
$updateUsername->execute([$username, $id]);
exit(json_encode(['message' => 'نام کاربری شما با موفقیت تغییر کرد', 'username' => $username]));