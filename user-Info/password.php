<?php
global $pdo;
include '../tools/db_connect.php';

postMethod();

$currentPass = $_POST['current-password'] ?? '';
$newPass = $_POST['new-password'] ?? '';

$id = authorization();

nullCheck($currentPass, 'current-password');
nullCheck($newPass, 'new-password');

$user = $pdo->prepare('SELECT * FROM users_auth WHERE user_id = ?');
$user->execute([$id]);
$user->fetch();

if (!(password_verify($currentPass, $user['password']))) exit(json_encode(['error' => 'Current Password False', 'message' => 'گذرواژه فعلی نادرست است']));
$hashPass = password_hash($newPass, PASSWORD_DEFAULT);
$updatePass = $pdo->prepare('UPDATE users_auth SET password = ? WHERE user_id = ?');
$updatePass->execute([$hashPass, $id]);
exit(json_encode(['message' => 'گذرواژه با موفقیت تغییر کرد']));