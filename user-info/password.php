<?php
include '_init_.php';

setMethod('POST');
$id = authorization();
$currentPass = param('current-password');
$newPass = param('new-password');

$auth = Database::select(table: 'users_auth', where: 'user_id = ?', value: [$id]);
if (!(password_verify($currentPass, $auth[0]['password']))) setError(400, 'Incorrect Current Password');

$hashPass = password_hash($newPass, PASSWORD_DEFAULT);
Database::update(table: 'users_auth', set: ['password' => $hashPass], where: ['user_id' => $id]);
exit(json_encode(['message' => 'گذرواژه شما با موفقیت تغییر کرد']));