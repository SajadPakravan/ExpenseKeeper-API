<?php
global $pdo;
include '../tools/db_connect.php';

setMethod('POST');
$id = authorization();
$username = param('username');

$checkUsername = $pdo->prepare('SELECT * FROM users_auth WHERE username = ?');
$checkUsername->execute([$username]);

if ($checkUsername->fetch()) exit(json_encode(['error' => 'Username Used', 'message' => 'این نام کاربری قبلا استفاده شده است']));
$updateUsername = $pdo->prepare('UPDATE users_auth SET username = ? WHERE user_id = ?');

if (!($updateUsername->execute([$username, $id]))) exit(json_encode(['error' => 'Username Error', 'message' => 'تغییر نام کاربری با مشکل مواجه شد']));
exit(json_encode(['message' => 'نام کاربری با موفقیت تغییر کرد', 'username' => $username]));