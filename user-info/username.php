<?php
include '_init_.php';

if (!(setMethod('POST'))) setError(405, 'Wrong Method');
$id = authorization();
$username = param('username');

$checkUsername = Database::select(table: 'users_auth', where: 'username = ?', value: [$username]);
if (!empty($checkUsername)) setError(400, 'Username Used');
Database::update(table: 'users_auth', set: ['username' => $username], where: ['user_id' => $id]);
exit(json_encode(['message' => 'نام کاربری شما با موفقیت تغییر کرد', 'username' => $username]));