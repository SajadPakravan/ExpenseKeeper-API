<?php
global $db;
include '../tools/db_connect.php';

setMethod('POST');
$username = param('username');
$name = param('name');
$password = param('password');

$checkUsername = $db->prepare('SELECT * FROM users_auth WHERE username = ?');
$checkUsername->execute([$username]);

if ($checkUsername->fetch()) setError(400, 'Username Used');

$insertUser = $db->prepare('INSERT INTO users (name, email, phone, avatar, create_at) VALUES (?, null, null, ?, NOW())');
$insertUser->execute([$name, DefaultAvatarUrl]);

$insertAuth = $db->prepare('INSERT INTO users_auth (user_id, username, password, login_time, Logout_time, status) VALUES (?, ?, ?, NOW(), null, 1)');
$userId = $db->lastInsertId();
$hashPass = password_hash($password, PASSWORD_DEFAULT);
$insertAuth->execute([$userId, $username, $hashPass]);

$token = createToken($userId);
exit(json_encode(['message' => 'ثبت‌نام و ورود شما با موفقیت انجام شد', 'token' => $token]));