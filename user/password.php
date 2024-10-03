<?php
include '_init_.php';

setMethod('POST');
$id = authorization();
$currentPass = param('current-password');
$newPass = param('new-password');

$user = db()->prepare('SELECT * FROM users_auth WHERE user_id = ?');
$user->execute([$id]);
$user = $user->fetch();

if (!(password_verify($currentPass, $user['password']))) setError(400,'Incorrect Current Password');
$hashPass = password_hash($newPass, PASSWORD_DEFAULT);
$updatePass = db()->prepare('UPDATE users_auth SET password = ? WHERE user_id = ?');
$updatePass->execute([$hashPass, $id]);
exit(json_encode(['message' => 'گذرواژه شما با موفقیت تغییر کرد']));